# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Stayly** is an Airbnb-like property booking marketplace built with **plain PHP** (no framework) using a hand-rolled **MVC architecture**. Users browse properties, manage wishlists (via AJAX), book stays (date-range picker → reservation → payment), leave reviews, and host their own listings through a multi-step wizard. Frontend is vanilla JavaScript + CSS; data lives in MySQL accessed through PDO.

## Running the Project

This is a XAMPP/Apache + MySQL app — there is **no build step, no package manager scripts, and no test suite**.

- **Serve it:** Place the repo under Apache's docroot (it lives at `E:\xampp\htdocs\stayly`) and start Apache + MySQL via XAMPP. The app entry point is `public/index.php`; the public URL base is `http://localhost/Stayly/` (see `DEFAULT_URL` in `core/Config.php`).
- **Install PHP deps:** `composer install` (regenerates `vendor/` and the Composer autoloader).
- **Database:** MySQL database named `stayly`, connected as `root` with no password (hard-coded in `core/db/Database.php`). The schema lives at `database/schema.sql` (structure only). Create a fresh DB with `mysql -u root stayly < database/schema.sql` (after `CREATE DATABASE stayly`), then load reference data with `mysql -u root stayly < database/seed.sql` (seeds the `amenities` and `languages` lookup tables). No migration tooling — keep `database/schema.sql` in sync manually after schema changes, e.g. `mysqldump -u root --no-data --skip-comments stayly > database/schema.sql`.
- **Env:** `.env` (gitignored, loaded via `vlucas/phpdotenv`) holds `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` for Google OAuth login.
- **Verifying logic without a browser:** model/value-object logic can be exercised directly with the XAMPP PHP CLI, e.g. `E:\xampp\php\php.exe -r '...'`, defining `BASE_PATH` and `require`-ing `core/db/Database.php` + the model. Always clean up any rows a test inserts (respect FK order: payments → bookings).

## Architecture

### Request lifecycle & routing

1. `public/.htaccess` rewrites clean URLs `Controller/action` → `index.php?controller=Controller&action=action` (only when the path isn't a real file/dir).
2. `public/index.php` boots the app: `session_start()`, loads Composer + `.env` + `core/Autoloader.php` + `core/Config.php`, then dispatches based on `$_GET['controller']` and `$_GET['action']`.
3. Dispatch rules: the controller name is validated against a regex, then suffixed with `Controller` (e.g. `Home` → `HomeController`). If the class+method exist, it's called; if the class exists but the action doesn't, it falls back to `DEFAULT_ACTION` (`index`); otherwise it loads `HomeController::index` (`DEFAULT_CONTROLLER`/`DEFAULT_ACTION` from `core/Config.php`).

So a link like `public/Home/showLogin` runs `HomeController::showLogin()`. There is no central route registry — URLs map directly to controller/action.

### Autoloading (`core/Autoloader.php`)

A custom `spl_autoload_register` resolves classes **by name suffix**, not PSR-4:
- `*Controller` → `controllers/`
- `*Database` → `core/db/`
- everything else → `models/`

This is separate from the Composer autoloader (which only covers `vendor/`). Because of this convention, **class names must match these suffixes and live in the matching folder**. Plain value objects with no suffix (e.g. `DateRange`) also load from `models/`. Controllers/models often additionally `require_once` their dependencies at the top of the file.

### Layers

- **Controllers (`controllers/`)** — one per domain (Home, Property, Booking, Host, Payment, Review, User, Wishlist). They read `$_GET`/`$_POST`/`$_SESSION`/JSON body, call models, and either render views or echo JSON. Most extend `BaseController`.
- **`BaseController`** — shared helpers every controller should use instead of reinventing them:
  - `requireAuth($redirect_url)` — gate for logged-in-only actions. Returns a `401` JSON `{"status":"unauthorized"}` for AJAX requests (detected via `X-Requested-With`), otherwise sets a toast and redirects to login.
  - `redirect($path)` — `Location:` redirect relative to `DEFAULT_URL`, then **`exit`**. Code in this codebase relies on `redirect()` exiting, so guard clauses don't need an explicit `return` after it.
  - `loadToast($type, $message)` — queues a flash message in `$_SESSION['toast']`.
  - `view($view, $data)` — `extract()`s `$data` into scope and `require`s `views/$view.php`.
- **Models (`models/`)** — plain PHP classes wrapping the shared PDO from `Database::connect()`. Property-bag style: `setId()`/`setUser_id()` etc., then call query methods (`getOne()`, `getPaginated()`, `create()`…). SQL is inline with prepared statements.
- **Views (`views/`)** — raw PHP templates. `views/layout/header.php` + `footer.php` wrap full pages. Controllers render either via `BaseController::view()` or by `require_once`-ing header → page → footer directly (both patterns exist). Page-specific views (e.g. `property/show.php`, `payment/show.php`) are body fragments included between header and footer.
- **Frontend (`assets/`)** — vanilla JS (`main.js`, `calendar.js`, `wizard.js`, `map.js`, `modal.js`, `toast.js`) and per-feature CSS. No bundler.

### Database access (`core/db/Database.php`)

`Database` is a singleton: `Database::connect()` returns a single shared `PDO` (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`). Connection params are hard-coded. Always go through this rather than instantiating PDO directly. Because the connection is shared, a transaction begun on it (`beginTransaction`) spans every model using it — this is how cross-model transactions work (see Payment confirmation below).

### Booking flow (date-range picker → reservation → payment)

The booking subsystem is the worked example of the conventions above:

1. **Datepicker** (`assets/js/calendar.js`) is a self-contained, instance-scoped range component (do not refactor it). On the property page (`views/property/show.php`) it writes the chosen range into hidden `start_date`/`end_date` inputs (`YYYY-MM-DD`) and a `guests` select; unavailable days are greyed out via its `isDisabled` hook, fed by `PropertyController::availability()`.
2. **`DateRange` (`models/DateRange.php`)** is the **authoritative server-side validator** — strict `YYYY-MM-DD` parsing (rejects impossible dates), no past check-in, check-out strictly after check-in, max-nights cap. It mirrors the calendar's client rules; client checks are UX only.
3. **`BookingController::create`** (POST from the form) runs `requireAuth` → `DateRange` validation → guest-capacity check → `Property::isRangeBlocked` (host blocks) → price = `nights × price_per_night` → `Booking::createIfAvailable` → PRG redirect to `Payment/show`.
4. **`Booking::createIfAvailable`** runs the overlap check (`hasOverlap(true)` = `FOR UPDATE`) and the insert in one transaction, so concurrent requests can't double-book. Returns `0` when the dates are taken.
5. **`PaymentController::show` / `process`** render a summary, then record a `Payment` and flip the booking `awaiting_payment → confirmed` in a single transaction. **The actual charge is a stub** — no provider SDK is configured; the code marks where Stripe/Redsys checkout + callback would slot in. Booking status enum: `pending → awaiting_payment → confirmed` (or `cancelled`).

## Conventions & Patterns

- **Date ranges are half-open `[check-in, check-out)`.** The check-out day is not "occupied", so back-to-back bookings (one guest checks out, another checks in same day) are allowed. Overlap, host-block, and calendar-greying logic all follow this.
- **Availability is opt-out.** A day is bookable unless an `availability` row marks it `is_available = 0`. Absence of a row = open (matches the column default), so newly created properties — which have no availability rows — are fully bookable. Bookings are the source of truth for occupancy; `availability` is a host block-list layered on top.
- **Concurrency-sensitive writes** (booking creation) use a transaction + `SELECT … FOR UPDATE`; status transitions (`confirm()`) are guarded by the expected current status in the `WHERE` clause and check `rowCount()`.
- **SQL `INSERT`s should name their columns explicitly** (see `Booking::create`, `Payment::create`). Note `Property::insertOne` uses a legacy **positional** `INSERT ... VALUES(NULL, ...)` tied to column order — fragile; don't copy that pattern, and prefer named columns when touching it.
- **`created_in` is a project-wide property name that maps to the DB column `created_at`.** `User`, `Property`, `Booking` all use it; the column is filled by `DEFAULT CURRENT_TIMESTAMP`, so models never write it. Keep the convention rather than "fixing" one model.
- **AJAX endpoints** read the raw body with `json_decode(file_get_contents("php://input"), true)`, set `header('Content-Type: application/json')`, and echo JSON (often `{"status": "success"|"error", ...}`). `WishlistController::toggle` is the reference; read-only feeds like `PropertyController::availability` echo a plain JSON payload.
- **Auth** is session-based via `$_SESSION['user_id']`; call `requireAuth()` at the top of protected actions. Actions that act on a user-owned record must also verify ownership (e.g. `PaymentController` checks the booking's `user_id`).
- **Flash toasts** flow through `$_SESSION['toast']` (`{type, message}`) set by `loadToast()`. **A page only renders them if it loads `toast.js` as a classic `<script>` and includes the inline bridge** that calls `showToast(...)` from `$_SESSION['toast']` then unsets it. `main.js` imports `toast.js` as a module, which does **not** expose `showToast` globally — so a page relying only on `main.js` cannot show flash toasts. `home`, `login`, `register`, `wizard`, `property/show`, and `payment/show` include the bridge.
- **SQL** always uses prepared statements with bound params — never interpolate user input.
- **Property images** upload to `assets/img/properties/uploads/{property_id}/`; the DB stores the public relative path and `is_main` flags the cover image (`Property::insertImages`).

## Key Files

- `public/index.php` — front controller / router; `public/.htaccess` — URL rewriting
- `core/Autoloader.php` — suffix-based autoloading; `core/Config.php` — constants (`DEFAULT_URL`, defaults, Google OAuth); `core/db/Database.php` — singleton PDO
- `controllers/BaseController.php` — shared controller helpers
- `models/DateRange.php` — authoritative booking-date validator (pure, no DB)
- `controllers/BookingController.php` + `models/Booking.php` — reservation flow & concurrency safety
- `controllers/PaymentController.php` + `models/Payment.php` — payment handoff & confirmation (charge stubbed)
- `database/schema.sql` / `database/seed.sql` — DB structure & lookup-table seed data

## Notable Constraints

- No automated tests, linting, or CI. Verify with `php -l` and the PHP CLI for logic, and by exercising the app in the browser.
- Routing, autoloading, and DB credentials are convention/hard-coded — a new feature means: a `*Controller`, a model in `models/`, view(s), and wiring via the `Controller/action` URL pattern.
- `DEFAULT_URL` and the rewrite assume the app is served under `/Stayly/`; keep generated links/redirects relative to `DEFAULT_URL`.
- Payment is not a real integration (no provider SDK); the booking lifecycle and data model are complete, but money movement is stubbed.
