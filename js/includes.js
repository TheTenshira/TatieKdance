// /js/includes.js
function loadHTML(selector, path, isHead = false) {
  fetch(path)
    .then(res => {
      if (!res.ok) throw new Error(`Erreur HTTP ${res.status}`);
      return res.text();
    })
    .then(html => {
      if (isHead) {
        document.head.insertAdjacentHTML("beforeend", html);
      } else if (selector) {
        const target = document.querySelector(selector);
        if (target) target.innerHTML = html;
      }
    })
    .catch(err => console.error(`Erreur de chargement de ${path}:`, err));
}

function loadIncludes() {
  const base = "/tatiekdance"; //laisser vide quand en ligne
  loadHTML(null, `${base}/header.html`, true);
  loadHTML("#footer", `${base}/footer.html`);
}

window.addEventListener("DOMContentLoaded", loadIncludes);
