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

deleteAccountform.addEventListener("click", () => {
  form.submit();
});
