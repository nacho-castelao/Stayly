"use strict";
const BASE_URL = "/stayly";

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

// Wishlist button logic

const favIcon = document.querySelector(".fav-icon");

function toggleWishlist(houseId) {
  fetch(`${BASE_URL}/public/Wishlist/toggle`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",   //“The data I´m gonna send you is in JSON format.”
      "X-Requested-With": "XMLHttpRequest", //“This request comes from JavaScript/AJAX and not from a traditional form.”
    },
    body: JSON.stringify({                  //Converts the JS object to JSON.
      propertyId: houseId,                  //Pass the house id.
    }),
  })
    .then((response) => response.text())
    .then((data) => {
      console.log(data);

      if (JSON.parse(data).remove === false) {
        showToast("success", "Added to wishlist!");
      }
    });
}

favIcon.addEventListener("click", function (e) {
  const propertyId = this.dataset.id;

  toggleWishlist(propertyId);

  this.classList.toggle("active");
});
