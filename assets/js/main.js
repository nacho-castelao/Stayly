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