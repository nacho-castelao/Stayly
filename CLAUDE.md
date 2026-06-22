# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Stayly** is an Airbnb-like property booking marketplace built with **plain PHP** (no framework) using a hand-rolled **MVC architecture**. Users browse properties, manage wishlists (via AJAX), book stays, leave reviews, and host their own listings through a multi-step wizard. Frontend is vanilla JavaScript + CSS; data lives in MySQL accessed through PDO.

## Running the Project

This is a XAMPP/Apache + MySQL app — there is **no build step, no package manager scripts, and no test suite**.

- **Serve it:** Place the repo under Apache's docroot (it lives at `E:\xampp\htdocs\stayly`) and start Apache + MySQL via XAMPP. The app entry point is `public/index.php`; the public URL base is `http://localhost/Stayly/` (see `DEFAULT_URL` in `core/Config.php`).
- **Install PHP deps:** `composer install` (regenerates `vendor/` and the Composer autoloader).
- **Database:** MySQL database named `stayly`, connected as `root` with no password (hard-coded in `core/db/Database.php`). The schema lives at `database/schema.sql` (structure only). Create a fresh DB with `mysql -u root stayly < database/schema.sql` (after `CREATE DATABASE stayly`). After the schema, load reference data with `mysql -u root stayly < database/seed.sql` (seeds the `amenities` and `languages` lookup tables, required for the host wizard and language profiles). There is no migration tooling — keep `database/schema.sql` in sync manually after schema changes, e.g. `mysqldump -u root --no-data --skip-comments stayly > database/schema.sql`.
- **Env:** `.env` (gitignored, loaded via `vlucas/phpdotenv`) holds `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` for Google OAuth login.

## Architecture

### Request lifecycle & routing

1. `public/.htaccess` rewrites clean URLs `Controller/action` → `index.php?controller=Controller&action=action` (only when the path isn't a real file/dir).
2. `public/index.php` boots the app: `session_start()`, loads Composer + `.env` + `core/Autoloader.php` + `core/Config.php`, then dispatches based on `$_GET['controller']` and `$_GET['action']`.
3. Dispatch rules: the controller name is validated against a regex, then suffixed with `Controller` (e.g. `Home` → `HomeController`). If the class+method exist, it's called; if the class exists but the action doesn't, it falls back to `DEFAULT_ACTION` (`index`); otherwise it loads `HomeController::index` (`DEFAULT_CONTROLLER`/`DEFAULT_ACTION` from `core/Config.php`).

So a link like `public/Home/showLogin` runs `HomeController::showLogin()`.

### Autoloading (`core/Autoloader.php`)

A custom `spl_autoload_register` resolves classes **by name suffix**, not PSR-4:
- `*Controller` → `controllers/`
- `*Database` → `core/db/`
- everything else → `models/`

This is separate from the Composer autoloader (which only covers `vendor/`). Because of this convention, **class names must match these suffixes and live in the matching folder**, and controllers/models often also `require_once` their dependencies explicitly at the top of the file.

### Layers

- **Controllers (`controllers/`)** — one per domain (Home, Property, Booking, Host, Payment, Review, User, Wishlist). They read `$_GET`/`$_SESSION`/JSON body, call models, and either render views or echo JSON. Most extend `BaseController`.
- **`BaseController`** — shared helpers every controller should use instead of reinventing them:
  - `requireAuth($redirect_url)` — gate for logged-in-only actions. Returns a `401` JSON `{"status":"unauthorized"}` for AJAX requests (detected via `X-Requested-With`), otherwise sets a toast and redirects to login.
  - `redirect($path)` — `Location:` redirect relative to `DEFAULT_URL`.
  - `loadToast($type, $message)` — queues a flash message in `$_SESSION['toast']` (consumed by the toast UI on next render).
  - `view($view, $data)` — `extract()`s `$data` into scope and `require`s `views/$view.php`.
- **Models (`models/`)** — plain PHP classes wrapping a PDO connection from `Database::connect()`. They follow a getter/setter property-bag style: you `setId()`/`setUser_id()` etc., then call query methods like `getOne()`, `getPaginated()`, `insertOne()`. SQL is written inline with prepared statements.
- **Views (`views/`)** — raw PHP templates. `views/layout/header.php` + `footer.php` wrap pages. Controllers render either via `BaseController::view()` or by `require_once`-ing header → page → footer directly (both patterns exist in the codebase).
- **Frontend (`assets/`)** — vanilla JS modules (`main.js`, `calendar.js`, `wizard.js`, `map.js`, `modal.js`, `toast.js`) and per-feature CSS files. No bundler.

### Database access (`core/db/Database.php`)

`Database` is a singleton: `Database::connect()` returns a single shared `PDO` instance configured with `ERRMODE_EXCEPTION` and `FETCH_ASSOC` by default. Connection params (`localhost`, db `stayly`, user `root`, empty password) are hard-coded. Always go through this rather than instantiating PDO directly.

## Conventions & Patterns

- **AJAX endpoints** read the raw body with `json_decode(file_get_contents("php://input"), true)`, set `header('Content-Type: application/json')`, `echo json_encode([...])`, and typically return `{"status": "success"|"error", ...}`. Wishlist toggle (`WishlistController::toggle`) is the reference example.
- **Auth** is session-based: `$_SESSION['user_id']` indicates a logged-in user. Call `requireAuth()` at the top of protected actions.
- **Flash messages / toasts** flow through `$_SESSION['toast']` (`{type, message}`) set via `loadToast()` and rendered client-side.
- **SQL** always uses PDO prepared statements with bound params — keep this; never interpolate user input into queries.
- **Models** use nullable typed properties + fluent setters (some `return $this`), and method naming like `getOne`, `getPaginated`, `getBySearch`, `countAll`, `insertOne`, `insertImages`, `insertAmenities`.
- **Property images** are uploaded to `assets/img/properties/uploads/{property_id}/`, with the DB storing the public relative path and `is_main` flagging the cover image (see `Property::insertImages`).

## Key Files

- `public/index.php` — front controller / router
- `public/.htaccess` — URL rewriting
- `core/Autoloader.php` — suffix-based class autoloading
- `core/Config.php` — app constants (`DEFAULT_URL`, defaults, Google OAuth)
- `core/db/Database.php` — singleton PDO connection
- `controllers/BaseController.php` — shared controller helpers

## Notable Constraints

- No automated tests, linting, or CI exist. Verify changes by exercising the app in the browser.
- Routing, autoloading, and DB credentials are convention/hard-coded — adding a new feature usually means: a `*Controller` in `controllers/`, a model in `models/`, view(s) in `views/`, and wiring via the `Controller/action` URL pattern. No central route registry to update.
- `DEFAULT_URL` and the rewrite assume the app is served under `/Stayly/`; keep generated links/redirects relative to `DEFAULT_URL`.
