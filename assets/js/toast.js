const toastContainer = document.querySelector("#toast-container");

function showToast(type = "info", message = "") {
  if (!toastContainer) return;

  const toast = document.createElement("div");
  toast.classList.add("toast", `toast--${type}`);

  // Icon mapping
  const icons = {
    success: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" 
      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big-icon 
      lucide-circle-check-big"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
    `,
    error: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF"
      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-octagon-x-icon lucide-octagon-x"><path d="m15 9-6 6"/>
      <path d="M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 
      0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"/><path d="m9 9 6 6"/></svg>
    `,
    warning: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" 
      stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert"><path d="m21.73 18-8-14a2 
      2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
    `,
    info: `
      <svg viewBox="0 0 24 24" fill="currentColor">
        <path d="M11 9h2V7h-2v2zm0 8h2v-6h-2v6zm1-16C5.9 1 1 5.9 1 12s4.9 11 11 11 11-4.9 11-11S18.1 1 12 1z"/>
      </svg>
    `,
  };

  toast.innerHTML = `
    <div class="toast__icon">
      ${icons[type] || icons.info}
    </div>
    <div class="toast__message">
      ${message}
    </div>
  `;

  toastContainer.appendChild(toast);

  const duration = type === "error" ? 4500 : 3500;

  setTimeout(() => {
    toast.classList.add("toast--hide");

    setTimeout(() => {
      toast.remove();
    }, 250);
  }, duration);
}
