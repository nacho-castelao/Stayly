"use strict";

const arriveCol = document.querySelector(".date-col.arrive");
const endCol = document.querySelector(".date-col.end");

if (arriveCol) {
  arriveCol.addEventListener("click", () => {
    document.querySelector("#arrive").showPicker();
  });
}

if (endCol) {
  endCol.addEventListener("click", () => {
    document.querySelector("#end").showPicker();
  });
}

const userIcon = document.querySelector(".user-icon");
const dropdown = document.querySelector(".drop-down");

if (userIcon && dropdown) {
  userIcon.addEventListener("click", (event) => {
    event.stopPropagation();
    dropdown.classList.toggle("dropAnimate");
  });

  document.addEventListener("click", function (event) {
    if (!dropdown.contains(event.target) && !userIcon.contains(event.target)) {
      dropdown.classList.remove("dropAnimate");
    }
  });
}

/* Toggle password visibility */
document.querySelectorAll(".toggle-pw").forEach((btn) => {
  btn.addEventListener("click", () => {
    const input = document.getElementById(btn.dataset.target);
    if (!input) return;
    input.type = input.type === "password" ? "text" : "password";
    btn.querySelector(".eye-icon").style.opacity =
      input.type === "text" ? "0.5" : "1";
  });
});

// Delete Account (modal logic)
const deleteAccountform = document.getElementById("delete-form");
const modal = document.getElementById("deleteModal");
const formButton = document.querySelector("#confirmDelete");

deleteAccountform.addEventListener("submit", function (e) {
  e.preventDefault(); // stop instant submit
  modal.classList.remove("hidden"); // show modal
});

modal.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.classList.add("hidden");
  }
});

deleteAccountform.addEventListener('click', () => {
  form.submit();
});



