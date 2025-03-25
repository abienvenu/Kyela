// Switch des icônes en haut à droite quand on plie/déplie
document.querySelectorAll('.toggle-button').forEach(button => {
	const icon = button.querySelector('i');
	const target = document.querySelector(button.dataset.bsTarget);
	const targetId = button.dataset.bsTarget; // Identifiant unique pour le localStorage

	// Mapping entre la classe "ouverte" et la classe "fermée"
	const classes = {
		'bi-calendar-minus': 'bi-calendar3',
		'bi-info-square': 'bi-info-circle',
		'bi-layout-text-sidebar-reverse': 'bi-arrow-bar-down'
	};

	// Fonction utilitaire pour remplacer une classe par une autre
	const toggleClass = (from, to) => {
		if (icon.classList.contains(from)) {
			icon.classList.replace(from, to);
		}
	};

	// Fonction pour enregistrer l'état dans le localStorage
	const saveState = (isOpen) => {
		localStorage.setItem(`collapseState-${targetId}`, isOpen ? 'open' : 'closed');
	};

	// Récupération de l'état au chargement de la page
	const savedState = localStorage.getItem(`collapseState-${targetId}`);
	if (savedState === 'closed') {
		new bootstrap.Collapse(target, {hide: true}); // Force la fermeture si enregistré comme "closed"
	}

	target.addEventListener('hidden.bs.collapse', () => {
		Object.entries(classes).forEach(([open, closed]) => toggleClass(open, closed));
		saveState(false); // Sauvegarde l'état fermé
	});

	target.addEventListener('shown.bs.collapse', () => {
		Object.entries(classes).forEach(([open, closed]) => toggleClass(closed, open));
		saveState(true); // Sauvegarde l'état ouvert
	});
});

// Bouton copier (à la création ou modification d'un sondage)
if (document.getElementById('copyButton')) {
	document.getElementById('copyButton').addEventListener('click', function () {
		const text = document.getElementById('sondageUrl').innerText;
		navigator.clipboard.writeText(text)
			.then(() => {
				this.textContent = this.dataset.copiedText;
				setTimeout(() => this.textContent = this.dataset.copyText, 2000);
			})
			.catch(err => console.error('Erreur lors de la copie :', err));
	});
}
