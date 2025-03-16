// Ajout d'un lien rapide vers un sondage récemment consulté
function addFastLink(pollUrl, pollTitle) {
	// Récupérer le tableau existant ou initialiser un tableau vide
	let fastLinks = JSON.parse(localStorage.getItem('fastlinks')) || [];

	// Supprimer tous les fastLinks qui ont déjà le même pollTitle mais pas la même URL
	fastLinks = fastLinks.filter(poll => poll.title !== pollTitle || poll.url === pollUrl);

	// Chercher l'index du sondage qui a déjà cette url
	const sameUrlIndex = fastLinks.findIndex(poll => poll.url === pollUrl);

	// Chercher l'index du sondage qui a déjà ce titre
	const sameTitleIndex = fastLinks.findIndex(poll => poll.title === pollTitle);

	if (sameUrlIndex !== -1) {
		// Le sondage existe avec cette URL, on met à jour son titre
		fastLinks[sameUrlIndex].title = pollTitle;
	} else if (sameTitleIndex !== -1) {
		// Le sondage existe avec ce titre, on met à jour son URL
		fastLinks[sameTitleIndex].url = pollUrl;
	} else {
		// Ajouter le sondage en fin de tableau
		fastLinks.push({url: pollUrl, title: pollTitle});

		// Limiter la liste à 10 sondages
		if (fastLinks.length > 10) {
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

	container.innerHTML = '';

	// Parcourir la liste des sondages récents
	fastLinks.forEach(poll => {
		// Créer l'élément <li> avec la classe nav-item
		const li = document.createElement('li');
		li.className = 'd-flex align-items-center justify-content-between py-2 py-md-1 fastlink';

		// Créer le lien <a> avec la classe dropdown-item
		const a = document.createElement('a');
		a.href = `/${poll.url}`;
		a.className = 'dropdown-item';

		// Créer l'icône "lien"
		const icon = document.createElement('i');
		icon.className = 'bi bi-caret-right me-1';
		a.appendChild(icon);

		const text = document.createElement('span');
		text.textContent = poll.title;
		a.appendChild(text);

		// Créer le bouton pour supprimer ce sondage de la liste
		const i = document.createElement('i');
		i.className = 'bi bi-x';
		const btnSupprimer = document.createElement('button');
		btnSupprimer.className = 'btn btn-light btn-sm delete-button';
		btnSupprimer.setAttribute('data-id', poll.url);
		btnSupprimer.appendChild(i);

		// Attacher un événement au clic pour supprimer le lien
		btnSupprimer.addEventListener('click', function (e) {
			e.preventDefault(); // Empêche la navigation si le bouton est cliqué à proximité du lien
			delFastLink(poll.url);
			displayFastLinks(); // Met à jour l'affichage après suppression
		});

		// Ajouter le lien et le bouton au conteneur de le lien
		li.appendChild(a);
		li.appendChild(btnSupprimer);

		// Ajouter le lien dans le conteneur principal
		container.appendChild(li);
	});
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
