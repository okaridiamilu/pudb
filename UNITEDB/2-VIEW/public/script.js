const themeToggle = document.getElementById("theme-toggle");


themeToggle.addEventListener("click", () => {
    const body = document.body;
    const isLight = body.classList.contains("light-mode");

    body.classList.toggle("light-mode", !isLight);
    body.classList.toggle("dark-mode", isLight);

    document.querySelectorAll(".post-container").forEach(post => {
        post.classList.toggle("light-mode", !isLight);
        post.classList.toggle("dark-mode", isLight);
    });
});

// Appliquer le mode clair par défaut au chargement
window.addEventListener("DOMContentLoaded", () => {
    document.body.classList.add("light-mode");
});

function confirmDelete() {
  return confirm("⚠️ Cette action supprimera définitivement votre compte et toutes vos équipes. Continuer ?");
}