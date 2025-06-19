fetch('data.json')
  .then(response => response.json())
  .then(data => {
    const container = document.getElementById('pokemon-container');

    data.forEach(pokemon => {
      const card = document.createElement('div');
      card.className = 'pokemon-card';

      const img = document.createElement('img');
      img.src = pokemon.portrait || 'placeholder.png'; // Utilise l'image par défaut si nécessaire
      img.alt = pokemon.name;

      const name = document.createElement('h3');
      name.textContent = pokemon.name;

      const showMoreBtn = document.createElement('button');
      showMoreBtn.className = 'show-more';
      showMoreBtn.textContent = 'Show More';

      const details = document.createElement('div');
      details.className = 'details';

      const statLines = pokemon.stats ? `
        <p><strong>HP:</strong> ${pokemon.stats.hp || 'Données non disponibles'}</p>
        <p><strong>Attack:</strong> ${pokemon.stats.attack || 'Données non disponibles'}</p>
        <p><strong>Defense:</strong> ${pokemon.stats.defense || 'Données non disponibles'}</p>` 
        : '<p><em>Données non disponibles</em></p>';

      const moveLines = pokemon.moves ? pokemon.moves.map(
        move => `<p><strong>${move.name}:</strong> ${move.description || 'Données non disponibles'}</p>`
      ).join('') : '<p><em>Aucune attaque trouvée</em></p>';

      details.innerHTML = `
        <p><strong>Role:</strong> ${pokemon.role || 'Inconnu'}</p>
        <p><strong>Difficulty:</strong> ${pokemon.difficulty || 'Inconnu'}</p>
        ${statLines}
        ${moveLines}
      `;

      // Gestion du clic sur "Show More"
      showMoreBtn.addEventListener('click', () => {
        details.classList.toggle('open');
        showMoreBtn.textContent = details.classList.contains('open') ? 'Show Less' : 'Show More';
      });

      card.appendChild(img);
      card.appendChild(name);
      card.appendChild(showMoreBtn);
      card.appendChild(details);
      container.appendChild(card);
    });
  })
  .catch(error => console.error('Erreur:', error));
