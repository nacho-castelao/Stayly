"use strict";

/**
 * Calendar — an embeddable, instance-scoped range date picker.
 *
 * Markup: pass an empty container and the component renders its own skeleton
 * into it (the root becomes the `.cal-card`). Pass a container that already
 * holds the documented skeleton and the component uses it as-is, throwing a
 * clear error if a required element (the `.cal-grid`) is missing.
 *
 * Usage:
 *   const cal = new Calendar(rootElement, {
 *     initialDate: new Date(),         // month to open on
 *     onSelect: ({ start, end }) => {} // fired whenever the range changes
 *     isDisabled: (date) => false,     // hook for min-date / availability (host-specific)
 *   });
 *   cal.getRange();        // → { start, end }
 *   cal.setRange(a, b);    // programmatic selection
 *   cal.clear();           // reset
 *   cal.destroy();         // unbind listeners + empty the grid (root is left in place)
 *
 * Importing the script has no DOM side effects. To auto-mount, mark a root
 * element with `data-calendar-auto`; the instance is stored on `el.calendar`.
 *
 * Also dispatches a "calendar:rangeselect" CustomEvent on the root element,
 * with { detail: { start, end } }.
 */

const MONTH_NAMES = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December",
];

const TOTAL_CELLS = 42;

const DAY_LABELS = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];

// The markup the component renders into an empty root (the root itself becomes
// the `.cal-card`). The `.cal-grid` cells are built separately in _buildGrid().
const SKELETON_HTML = `
    <div class="cal-header">
        <button type="button" class="nav-btn" data-cal-nav="prev" aria-label="Previous month">
            <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M10 12L6 8l4-4" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <div class="month-pills">
            <div class="month-pill">
                <span class="pill-month-name"></span>
                <span class="pill-month-year"></span>
                <div class="pill-corner"></div>
            </div>
        </div>
        <button type="button" class="nav-btn" data-cal-nav="next" aria-label="Next month">
            <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M6 12l4-4-4-4" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <div class="cal-days-header">
        ${DAY_LABELS.map((d) => `<div class="day-label">${d}</div>`).join("")}
    </div>
    <div class="cal-grid"></div>
    <div class="cal-status"></div>
`;

// ── Date helpers (pure, no shared state) ─────────────────────────
function startOfDay(date) {
    return new Date(date.getFullYear(), date.getMonth(), date.getDate());
}

function getFirstDay(date) {
    return new Date(date.getFullYear(), date.getMonth(), 1);
}

function daysInMonth(date) {
    if (!(date instanceof Date)) {
        throw new Error('The [date] argument passed to "daysInMonth" must be a Date instance.');
    }
    // Day "zero" of the next month resolves to the last day of this month.
    return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
}

// Monday-first offset: how many leading cells belong to the previous month.
function mondayOffset(firstDayOfMonth) {
    const day = firstDayOfMonth.getDay();
    return day === 0 ? 6 : day - 1;
}

function sameDay(a, b) {
    return !!a && !!b && a.getTime() === b.getTime();
}

function addDays(date, n) {
    return new Date(date.getFullYear(), date.getMonth(), date.getDate() + n);
}

function formatDate(date) {
    return date.toLocaleDateString(undefined, { day: "numeric", month: "short", year: "numeric" });
}

class Calendar {
    constructor(root, options = {}) {
        if (!root) throw new Error("Calendar requires a root element.");

        this.root = root;
        this.options = options;
        this.isDisabled = typeof options.isDisabled === "function" ? options.isDisabled : () => false;

        // Empty container → render our own markup. Non-empty → host owns the
        // skeleton and we validate it below.
        this._ensureSkeleton();

        // Scoped element lookups — no document-wide queries, no global IDs.
        this.grid = this._requireEl(".cal-grid", "day grid");
        this.monthName = root.querySelector(".pill-month-name");
        this.yearLabel = root.querySelector(".pill-month-year");
        this.statusEl = root.querySelector(".cal-status");
        this.prevBtn = root.querySelector('[data-cal-nav="prev"]');
        this.nextBtn = root.querySelector('[data-cal-nav="next"]');

        // Instance state (was module-level globals before).
        this.viewDate = getFirstDay(options.initialDate ? new Date(options.initialDate) : new Date());
        this.rangeStart = null;
        this.rangeEnd = null;
        this.hoverDate = null;
        this.focusedDate = null;       // roving-tabindex anchor for keyboard nav

        this.cells = [];               // 42 .cal-cell elements
        this.cellDates = [];           // parallel Date for each cell
        this._listeners = [];          // [el, type, handler] for teardown
        this._destroyed = false;

        this._buildGrid();
        this._bindEvents();
        this.render();
    }

    // ── Public API ───────────────────────────────────────────────
    getRange() {
        return { start: this.rangeStart, end: this.rangeEnd };
    }

    setRange(start, end = null) {
        this.rangeStart = start ? startOfDay(new Date(start)) : null;
        this.rangeEnd = end ? startOfDay(new Date(end)) : null;
        if (this.rangeStart) this.viewDate = getFirstDay(this.rangeStart);
        this.render();
        this._emit();
    }

    clear() {
        this.rangeStart = null;
        this.rangeEnd = null;
        this.render();
        this._emit();
    }

    goToMonth(delta) {
        this.viewDate = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() + delta, 1);
        this.render();
    }

    // Remove every listener and clear the grid. Safe to call more than once.
    // The host owns the root element, so we leave it in the DOM (just emptied).
    destroy() {
        if (this._destroyed) return;
        this._destroyed = true;
        for (const [el, type, handler] of this._listeners) {
            el.removeEventListener(type, handler);
        }
        this._listeners = [];
        if (this._selfRendered) {
            // We created the whole skeleton — return the root to how we got it.
            this.root.innerHTML = "";
            this.root.classList.remove("cal-card");
        } else if (this.grid) {
            this.grid.innerHTML = "";
        }
        this.cells = [];
        this.cellDates = [];
    }

    // ── Setup ────────────────────────────────────────────────────
    // Self-render the markup when handed an empty container. A container that
    // already has content is assumed to carry the host's own skeleton, which
    // _requireEl then validates piece by piece.
    _ensureSkeleton() {
        if (this.root.children.length > 0) return;
        this.root.classList.add("cal-card");
        this.root.innerHTML = SKELETON_HTML;
        this._selfRendered = true;
    }

    _requireEl(selector, label) {
        const el = this.root.querySelector(selector);
        if (!el) {
            throw new Error(
                `Calendar: required element "${selector}" (${label}) was not found in the root. ` +
                `Pass an empty container to auto-render the markup, or include the documented skeleton.`
            );
        }
        return el;
    }

    _buildGrid() {
        this.grid.innerHTML = "";
        this.grid.setAttribute("role", "grid");

        for (let i = 0; i < TOTAL_CELLS; i++) {
            const cell = document.createElement("div");
            const inner = document.createElement("div");
            cell.className = "cal-cell";
            cell.setAttribute("role", "gridcell");
            inner.className = "cal-cell-inner";
            cell.append(inner);
            this.grid.append(cell);
            this.cells.push(cell);
        }
    }

    _bindEvents() {
        if (this.prevBtn) this._on(this.prevBtn, "click", () => this.goToMonth(-1));
        if (this.nextBtn) this._on(this.nextBtn, "click", () => this.goToMonth(1));

        this._on(this.grid, "click", (e) => {
            const cell = e.target.closest(".cal-cell");
            if (!cell) return;
            const date = this.cellDates[this.cells.indexOf(cell)];
            if (date) this._selectDate(date);
        });

        this._on(this.grid, "mouseover", (e) => {
            const cell = e.target.closest(".cal-cell");
            const date = cell ? this.cellDates[this.cells.indexOf(cell)] : null;
            this._setHover(date);
        });

        this._on(this.grid, "mouseleave", () => this._setHover(null));

        this._on(this.grid, "keydown", (e) => this._onKeyDown(e));
    }

    // Register a listener and remember it so destroy() can unbind it.
    _on(el, type, handler) {
        el.addEventListener(type, handler);
        this._listeners.push([el, type, handler]);
    }

    // ── Selection ────────────────────────────────────────────────
    _selectDate(date) {
        if (this._isSelectable(date) === false) return;

        // Restart if no start yet, range already complete, or clicking before start.
        if (!this.rangeStart || this.rangeEnd || date < this.rangeStart) {
            this.rangeStart = date;
            this.rangeEnd = null;
        } else {
            this.rangeEnd = date;
        }

        this.hoverDate = null;
        this.render();
        this._emit();
    }

    _isSelectable(date) {
        if (!date) return false;
        // Overflow (other-month) cells stay non-selectable, preserving prior behavior.
        if (date.getMonth() !== this.viewDate.getMonth()) return false;
        if (this.isDisabled(date)) return false;
        return true;
    }

    _emit() {
        const detail = { start: this.rangeStart, end: this.rangeEnd };
        if (typeof this.options.onSelect === "function") this.options.onSelect(detail);
        this.root.dispatchEvent(new CustomEvent("calendar:rangeselect", { detail, bubbles: true }));
    }

    // ── Hover preview ────────────────────────────────────────────
    _setHover(date) {
        const active = date && this.rangeStart && !this.rangeEnd
            && this._isSelectable(date) && date > this.rangeStart;
        this.hoverDate = active ? date : null;
        this._paintHover();
    }

    // ── Rendering ────────────────────────────────────────────────
    render() {
        const year = this.viewDate.getFullYear();
        const month = this.viewDate.getMonth();
        const firstDay = getFirstDay(this.viewDate);
        const offset = mondayOffset(firstDay);

        if (this.monthName) this.monthName.textContent = MONTH_NAMES[month];
        if (this.yearLabel) this.yearLabel.textContent = year;

        // Keep the roving focus anchor inside the visible month.
        if (!this.focusedDate || this.focusedDate.getMonth() !== month || this.focusedDate.getFullYear() !== year) {
            this.focusedDate = this._defaultFocusDate();
        }

        const firstCellDate = new Date(year, month, 1 - offset);
        const today = startOfDay(new Date());

        for (let i = 0; i < TOTAL_CELLS; i++) {
            const date = addDays(firstCellDate, i);
            const cell = this.cells[i];
            const inner = cell.firstChild;

            this.cellDates[i] = date;
            inner.textContent = date.getDate();

            const isOtherMonth = date.getMonth() !== month;
            const disabled = isOtherMonth || this.isDisabled(date);

            cell.className = "cal-cell";
            if (isOtherMonth) {
                cell.classList.add("faded");
                inner.classList.add("faded");
            } else {
                inner.classList.remove("faded");
            }
            if (disabled) cell.classList.add("disabled");
            if (sameDay(date, today)) cell.classList.add("today");

            // Accessibility wiring.
            cell.setAttribute("aria-label", formatDate(date));
            cell.setAttribute("aria-disabled", String(disabled));
            cell.tabIndex = sameDay(date, this.focusedDate) ? 0 : -1;
        }

        this._paintRange();
        this._paintHover();
        this._renderStatus();
    }

    _paintRange() {
        for (let i = 0; i < TOTAL_CELLS; i++) {
            const cell = this.cells[i];
            const date = this.cellDates[i];
            cell.classList.remove("range-start", "range-mid", "range-end");
            cell.removeAttribute("aria-selected");

            if (!this.rangeStart || date.getMonth() !== this.viewDate.getMonth()) continue;

            let inRange = false;
            if (sameDay(date, this.rangeStart)) { cell.classList.add("range-start"); inRange = true; }
            if (sameDay(date, this.rangeEnd)) { cell.classList.add("range-end"); inRange = true; }
            if (this.rangeEnd && date > this.rangeStart && date < this.rangeEnd) {
                cell.classList.add("range-mid");
                inRange = true;
            }
            if (inRange) cell.setAttribute("aria-selected", "true");
        }
    }

    _paintHover() {
        this.cells.forEach(c => c.classList.remove("hover-mid", "hover-end"));
        this.grid.classList.toggle("hovering", !!this.hoverDate);
        if (!this.hoverDate) return;

        for (let i = 0; i < TOTAL_CELLS; i++) {
            const cell = this.cells[i];
            const date = this.cellDates[i];
            if (date.getMonth() !== this.viewDate.getMonth()) continue;

            if (date > this.rangeStart && date < this.hoverDate) cell.classList.add("hover-mid");
            if (sameDay(date, this.hoverDate)) cell.classList.add("hover-end");
        }
    }

    _renderStatus() {
        if (!this.statusEl) return;
        if (!this.rangeStart) {
            this.statusEl.textContent = "Select a check-in date";
        } else if (!this.rangeEnd) {
            this.statusEl.innerHTML = `Check-in <strong>${formatDate(this.rangeStart)}</strong> — select check-out`;
        } else {
            const nights = Math.round((this.rangeEnd - this.rangeStart) / 86400000);
            this.statusEl.innerHTML =
                `<strong>${formatDate(this.rangeStart)}</strong> → <strong>${formatDate(this.rangeEnd)}</strong> · ${nights} night${nights === 1 ? "" : "s"}`;
        }
    }

    // ── Keyboard navigation ──────────────────────────────────────
    _defaultFocusDate() {
        const today = startOfDay(new Date());
        if (today.getMonth() === this.viewDate.getMonth() && today.getFullYear() === this.viewDate.getFullYear()) {
            return today;
        }
        return getFirstDay(this.viewDate);
    }

    _onKeyDown(e) {
        const anchor = this.focusedDate || this._defaultFocusDate();
        let next = null;

        switch (e.key) {
            case "ArrowLeft":  next = addDays(anchor, -1); break;
            case "ArrowRight": next = addDays(anchor, 1); break;
            case "ArrowUp":    next = addDays(anchor, -7); break;
            case "ArrowDown":  next = addDays(anchor, 7); break;
            case "Home":       next = addDays(anchor, -mondayOffset(anchor)); break;
            case "End":        next = addDays(anchor, 6 - mondayOffset(anchor)); break;
            case "PageUp":     this.goToMonth(-1); this._focusCell(); e.preventDefault(); return;
            case "PageDown":   this.goToMonth(1); this._focusCell(); e.preventDefault(); return;
            case "Enter":
            case " ":
                e.preventDefault();
                this._selectDate(anchor);
                this._focusCell();
                return;
            default:
                return;
        }

        e.preventDefault();
        this._moveFocus(next);
    }

    _moveFocus(date) {
        const monthChanged = date.getMonth() !== this.viewDate.getMonth()
            || date.getFullYear() !== this.viewDate.getFullYear();
        this.focusedDate = date;
        if (monthChanged) {
            this.viewDate = getFirstDay(date);
            this.render();
        } else {
            this.cells.forEach((c, i) => { c.tabIndex = sameDay(this.cellDates[i], date) ? 0 : -1; });
        }
        this._focusCell();
    }

    _focusCell() {
        const idx = this.cellDates.findIndex(d => sameDay(d, this.focusedDate));
        if (idx >= 0) this.cells[idx].focus();
    }
}

// ── Opt-in auto-init ─────────────────────────────────────────────
// Importing this script has no DOM side effects by default — an embedding
// host stays in control via `new Calendar(root, options)`. To auto-mount,
// mark the root with `data-calendar-auto` (the instance is stored on
// `element.calendar`). The standalone demo page opts in this way.
if (typeof document !== "undefined") {
    const mountAll = () => {
        document.querySelectorAll("[data-calendar-auto]").forEach((el) => {
            if (!el.calendar) el.calendar = new Calendar(el);
        });
    };
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", mountAll, { once: true });
    } else {
        mountAll();
    }
}

// Expose for embedding (script tag → window; bundler → module export).
if (typeof window !== "undefined") window.Calendar = Calendar;
if (typeof module !== "undefined" && module.exports) module.exports = Calendar;
