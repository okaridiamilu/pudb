document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;
  const themeToggle = document.getElementById("theme-toggle");

  // Mettre le thème clair par défaut
  body.classList.add("light-mode");

  themeToggle.addEventListener("click", () => {
    body.classList.toggle("light-mode");
    body.classList.toggle("dark-mode");
  });
});



document.addEventListener('DOMContentLoaded', () => {
  const checkboxes = document.querySelectorAll('input[name="pokemon_ids[]"]');
  checkboxes.forEach(cb => cb.addEventListener('change', () => {
    const selected = [...checkboxes].filter(c => c.checked);
    if (selected.length > 5) {
      cb.checked = false;
      alert("Vous ne pouvez sélectionner que 5 Pokémon.");
    }
  }));
});

