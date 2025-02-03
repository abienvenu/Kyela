// Ajout d'un lien rapide vers un sondage récemment consulté
function addFastLink(pollUrl, pollTitle) {
	// Récupérer le tableau existant ou initialiser un tableau vide
	let fastLinks = JSON.parse(localStorage.getItem('fastlinks')) || [];

	// Vérifier si le sondage est déjà dans la liste
	const exists = fastLinks.some(poll => poll.url === pollUrl);
	if (!exists) {
		// Ajouter le sondage en fin de tableau
		fastLinks.push({url: pollUrl, title: pollTitle});

		// Limiter la liste à 6 sondages
		if (fastLinks.length > 6) {
			fastLinks.shift(); // Supprime le premier élément
		}
	}

	// Sauvegarder le tableau mis à jour dans le localStorage
	localStorage.setItem('fastlinks', JSON.stringify(fastLinks));
}

// Affichage des sondages récemment consultés
function displayFastLinks() {
	// Récupérer la liste des sondages récents depuis le localStorage
	const fastLinks = JSON.parse(localStorage.getItem('fastlinks')) || [];
	const container = document.getElementById('menuFastLinks');

    // Supprimer les anciens <li> dynamiques (ceux qui n'ont pas la classe "fixed-links")
    container.querySelectorAll('li:not(.fixed-links)').forEach(li => li.remove());

    // Créer un fragment pour insérer les liens dynamiques
    const fragment = document.createDocumentFragment();

	// Parcourir la liste des sondages récents
	fastLinks.forEach(poll => {
		// Créer l'élément <li> avec la classe nav-item
		const li = document.createElement('li');
		li.className = 'nav-item d-flex align-items-center mb-2'; // d-flex pour aligner le lien et le bouton

		// Créer le lien <a> avec la classe nav-link
		const a = document.createElement('a');
		a.href = `/${poll.url}`;
		a.className = 'nav-link';
		a.textContent = poll.title;

		// Créer le bouton pour supprimer ce sondage de la liste
		const i = document.createElement('i');
		i.className = 'bi bi-x';
		const btnSupprimer = document.createElement('button');
		btnSupprimer.className = 'btn btn-secondary btn-sm delete-button';
		btnSupprimer.setAttribute('data-id', poll.url);
		btnSupprimer.appendChild(i);

		// Attacher un événement au clic pour supprimer l'poll
		btnSupprimer.addEventListener('click', function (e) {
			e.preventDefault(); // Empêche la navigation si le bouton est cliqué à proximité du lien
			delFastLink(poll.url);
			displayFastLinks(); // Met à jour l'affichage après suppression
		});

		// Ajouter le lien et le bouton au conteneur de l'poll
		li.appendChild(a);
		li.appendChild(btnSupprimer);

		// Ajouter l'poll dans le conteneur principal
		fragment.appendChild(li);
	});

    // Insérer les éléments dynamiques avant le premier élément fixe (class "fixed-links")
    const premierFixe = container.querySelector('li.fixed-links');
    if (premierFixe) {
      container.insertBefore(fragment, premierFixe);
    } else {
      container.appendChild(fragment);
    }
}

// Supprime un sondage de la liste des polls récents.
function delFastLink(pollUrl) {
	let fastLinks = JSON.parse(localStorage.getItem('fastlinks')) || [];
	// Filtrer la liste pour retirer l'article avec l'id correspondant
	fastLinks = fastLinks.filter(poll => poll.url !== pollUrl);
	localStorage.setItem('fastlinks', JSON.stringify(fastLinks));
}

// Afficher la liste dès que la page est chargée
document.addEventListener('DOMContentLoaded', displayFastLinks);
