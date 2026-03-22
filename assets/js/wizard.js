// wizard.js

document.addEventListener("DOMContentLoaded", () => {
  const steps = document.querySelectorAll(".progress-bar .step");

  // Get step from backend (inject it from PHP)
  const currentStep = window.currentStep || 1;

  steps.forEach((step, index) => {
    if (index === currentStep - 1) {
      step.classList.add("active");
      step.setAttribute("aria-selected", "true");
    } else {
      step.classList.remove("active");
      step.setAttribute("aria-selected", "false");
    }
  });

  // Step 3 – Photos
  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("fileInput");
  const photoGrid = document.getElementById("photoGrid");

  if (uploadArea) {
    let photos = [];

    uploadArea.addEventListener("click", () => fileInput.click());

    uploadArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      uploadArea.classList.add("dragover");
    });

    uploadArea.addEventListener("dragleave", () =>
      uploadArea.classList.remove("dragover"),
    );
    uploadArea.addEventListener("drop", (e) => {
      e.preventDefault();
      uploadArea.classList.remove("dragover");
      handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener("change", () => handleFiles(fileInput.files));

    function handleFiles(files) {
      Array.from(files).forEach((file) => {
        if (!file.type.startsWith("image/")) return;
        photos.push({ url: URL.createObjectURL(file), file });
        renderGrid();
      });
    }

    function renderGrid() {
      photoGrid.innerHTML = "";
      photos.forEach((p, i) => {
        const div = document.createElement("div");
        div.className = "photo-thumb" + (i === 0 ? " cover" : "");
        div.innerHTML = `<img src="${p.url}" alt="photo ${i + 1}">
          <button class="remove-btn" data-i="${i}" title="Remove">✕</button>`;
        photoGrid.appendChild(div);
      });
      photoGrid.querySelectorAll(".remove-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          photos.splice(+btn.dataset.i, 1);
          renderGrid();
        });
      });
    }
  }

  // Step 4 – Details
  const textarea = document.getElementById("description");
  const charHint = document.getElementById("charHint");

  if (textarea) {
    textarea.addEventListener("input", () => {
      const len = textarea.value.length;
      if (len > 0 && len < 100) {
        charHint.textContent = `${len}/100 characters — minimum 100 recommended.`;
        charHint.classList.add("warn");
      } else {
        charHint.textContent = "Minimum 100 characters recommended.";
        charHint.classList.remove("warn");
      }
    });
  }

  // Step 5 – Pricing
  const priceInput = document.getElementById("priceInput");
  const priceHint = document.getElementById("priceHint");

  if (priceInput) {
    priceInput.addEventListener("input", () => {
      const val = parseInt(priceInput.value) || 0;
      priceHint.textContent =
        val > 0
          ? `Guests will pay ${val}€ per night before fees.`
          : "Enter a price per night.";
    });
  }

  // Step 7 
  let isCancelled = false;
  let seconds = 5;
  let countdownEl = document.querySelector("#countdown");

  const cancelRedirect = () => {
    isCancelled = true;
    clearInterval(interval);
  };

  document.addEventListener("click", cancelRedirect);

  const interval = setInterval(() => {
    if (isCancelled) return;

    seconds--;
    countdownEl.textContent = seconds;

    if (seconds <= 0) {
      clearInterval(interval);
      window.location.href = "http://localhost/PROYECTOAIRBNB/public";
    }
  }, 1000);
});
