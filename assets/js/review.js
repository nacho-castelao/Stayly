"use strict";

/**
 * Review modal controller (dashboard → My Bookings).
 *
 * Drives the star + category selectors, the comment character counter,
 * client-side validation, the loading state and the AJAX submit to
 * Review/create or Review/update. The server re-validates everything; the
 * client checks are UX only. On success it refreshes the originating booking
 * card in place (no reload) so a freshly written review immediately offers
 * "View / edit review". Relies on the global showToast() from toast.js.
 *
 * No-ops on pages without the modal, so it's safe to load across the dashboard.
 */
(function () {
  const modal = document.getElementById("reviewModal");
  const form = document.getElementById("reviewForm");
  if (!modal || !form) return;

  const CATEGORIES = [
    "cleanliness",
    "accuracy",
    "communication",
    "checkin",
    "location",
    "value",
  ];
  const FIELDS = ["rating", ...CATEGORIES];

  const titleEl = document.getElementById("reviewModalTitle");
  const subtitleEl = document.getElementById("reviewModalSubtitle");
  const commentEl = document.getElementById("reviewComment");
  const counterEl = document.getElementById("reviewCounter");
  const errorEl = document.getElementById("reviewError");
  const submitBtn = document.getElementById("reviewSubmit");
  const groups = new Map(); // field -> .star-input element

  modal.querySelectorAll(".star-input").forEach((g) => groups.set(g.dataset.field, g));

  // ── Star selectors ──────────────────────────────────────────────
  function paint(group, value) {
    group.dataset.selected = String(value);
    group.querySelectorAll(".star-input__star").forEach((star) => {
      const on = Number(star.dataset.value) <= value;
      star.classList.toggle("is-on", on);
      star.setAttribute("aria-checked", Number(star.dataset.value) === value ? "true" : "false");
    });
  }

  groups.forEach((group) => {
    group.addEventListener("click", (e) => {
      const star = e.target.closest(".star-input__star");
      if (!star) return;
      paint(group, Number(star.dataset.value));
      hideError();
    });
  });

  function setValues(values) {
    FIELDS.forEach((field) => paint(groups.get(field), Number(values[field]) || 0));
  }

  function readValues() {
    const out = {};
    FIELDS.forEach((field) => (out[field] = Number(groups.get(field).dataset.selected) || 0));
    return out;
  }

  // ── Character counter ───────────────────────────────────────────
  function updateCounter() {
    counterEl.textContent = String(commentEl.value.length);
  }
  commentEl.addEventListener("input", updateCounter);

  // ── Error helpers ───────────────────────────────────────────────
  function showError(message) {
    errorEl.textContent = message;
    errorEl.hidden = false;
  }
  function hideError() {
    errorEl.hidden = true;
  }

  // ── Open / close ────────────────────────────────────────────────
  let activeBtn = null; // booking card button that opened the modal

  function open() {
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }
  function close() {
    modal.classList.add("hidden");
    document.body.style.overflow = "";
    activeBtn = null;
  }

  function reset() {
    setValues({});
    commentEl.value = "";
    updateCounter();
    hideError();
    setLoading(false);
  }

  function openFor(btn) {
    activeBtn = btn;
    reset();

    const mode = btn.dataset.mode === "edit" ? "edit" : "create";
    form.elements.mode.value = mode;
    form.elements.booking_id.value = btn.dataset.bookingId || "";
    form.elements.review_id.value = btn.dataset.reviewId || "";

    const title = btn.dataset.propertyTitle || "your stay";
    if (mode === "edit") {
      titleEl.textContent = "Edit your review";
      subtitleEl.textContent = "Update your rating of " + title + ".";
      setValues({
        rating: btn.dataset.rating,
        cleanliness: btn.dataset.cleanliness,
        accuracy: btn.dataset.accuracy,
        communication: btn.dataset.communication,
        checkin: btn.dataset.checkin,
        location: btn.dataset.location,
        value: btn.dataset.value,
      });
      commentEl.value = btn.dataset.comment || "";
      updateCounter();
      submitBtn.querySelector(".review-form__submit-label").textContent = "Save changes";
    } else {
      titleEl.textContent = "Leave a review";
      subtitleEl.textContent = "Share your experience of " + title + " to help other guests.";
      submitBtn.querySelector(".review-form__submit-label").textContent = "Post review";
    }

    open();
  }

  // Delegated: any "Leave a review" / "View / edit review" button.
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".js-review-btn");
    if (btn) openFor(btn);
  });

  document.getElementById("reviewCancel")?.addEventListener("click", close);
  document.getElementById("reviewModalClose")?.addEventListener("click", close);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) close();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) close();
  });

  // ── Loading state ───────────────────────────────────────────────
  function setLoading(loading) {
    submitBtn.classList.toggle("is-loading", loading);
    submitBtn.disabled = loading;
  }

  // ── Submit ──────────────────────────────────────────────────────
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (submitBtn.disabled) return;

    const values = readValues();
    if (FIELDS.some((field) => values[field] < 1 || values[field] > 5)) {
      showError("Please give a rating from 1 to 5 for every category.");
      return;
    }

    const mode = form.elements.mode.value;
    const url = mode === "edit" ? form.dataset.updateUrl : form.dataset.createUrl;
    const payload = { ...values, comment: commentEl.value.trim() };
    if (mode === "edit") payload.review_id = Number(form.elements.review_id.value);
    else payload.booking_id = Number(form.elements.booking_id.value);

    setLoading(true);
    hideError();

    try {
      const res = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(payload),
      });

      let data = {};
      try {
        data = await res.json();
      } catch (_) {
        /* non-JSON (e.g. server error page) — handled below */
      }

      if (res.status === 401 || data.status === "unauthorized") {
        showToast("error", "Please log in again to post your review.");
        setLoading(false);
        return;
      }

      if (!res.ok || data.status !== "success") {
        const message = data.message || "Something went wrong. Please try again.";
        showError(message);
        setLoading(false);
        return;
      }

      if (activeBtn) refreshCard(activeBtn, data, payload, mode);
      showToast("success", data.message || "Your review has been saved.");
      close();
    } catch (err) {
      showError("Network error. Please check your connection and try again.");
      setLoading(false);
    }
  });

  /**
   * Turn the originating button into (or keep it as) an "edit" button carrying
   * the freshly saved review, so the card reflects the new state without a reload.
   */
  function refreshCard(btn, data, payload, mode) {
    btn.dataset.mode = "edit";
    btn.dataset.reviewId = String(data.review_id);
    btn.dataset.rating = String(payload.rating);
    CATEGORIES.forEach((c) => (btn.dataset[c] = String(payload[c])));
    btn.dataset.comment = payload.comment || "";
    btn.textContent = "View / edit review";
    btn.classList.add("booking-item__action--ghost");
  }
})();
