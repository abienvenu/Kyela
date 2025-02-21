// Action quand on choisit une option
document.querySelectorAll('.dropdown-item').forEach(item => {
	item.addEventListener('click', function (event) {
		event.preventDefault(); // Empêche le lien de changer de page

		const participationUrl = this.getAttribute('data-url');

		// Requête AJAX
		fetch(participationUrl)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Trouver le bouton principal parent
					const dropdownButton = this.closest('div').querySelector('.dropdown-toggle');

					dropdownButton.innerHTML = `<i class="bi bi-${data.icon} me-2"></i> ${data.name}`;
					dropdownButton.className = `btn border dropdown-toggle choice-${data.color} shadow`;
					// Mettre à jour l'interface si nécessaire
				} else {
					alert('Erreur lors de la mise à jour de la participation.');
				}
			})
			.catch(error => console.error('Erreur:', error));
	});
});

// Switch des icônes en haut à droite quand on plie/déplie
document.querySelectorAll('.toggle-button').forEach(button => {
	const icon = button.querySelector('i');
	const target = document.querySelector(button.dataset.bsTarget);

	// Mapping entre la classe "ouverte" et la classe "fermée"
	const classes = {
		'bi-calendar3': 'bi-calendar-minus',
		'bi-info-square': 'bi-info-circle',
		'bi-layout-text-sidebar-reverse': 'bi-arrow-bar-down'
	};

	// Fonction utilitaire pour remplacer une classe par une autre
	const toggleClass = (from, to) => {
		if (icon.classList.contains(from)) {
			icon.classList.replace(from, to);
		}
	};

	target.addEventListener('hidden.bs.collapse', () => {
		Object.entries(classes).forEach(([open, closed]) => toggleClass(open, closed));
	});

	target.addEventListener('shown.bs.collapse', () => {
		Object.entries(classes).forEach(([open, closed]) => toggleClass(closed, open));
	});
});

// Sélection d'une ligne seule
document.querySelectorAll('.participant').forEach(cell => {
	cell.addEventListener('click', function () {
		const row = cell.parentElement.parentElement;
		const allRows = document.querySelectorAll('tbody tr');

		// Si la ligne est déjà sélectionnée, on rétablit l'apparence par défaut
		if (row.classList.contains('selected')) {
			allRows.forEach(r => {
				r.classList.remove('dimmed', 'selected');
			});
		} else {
			// On ajoute l'effet dimmed sur toutes les lignes...
			allRows.forEach(r => {
				r.classList.add('dimmed');
				r.classList.remove('selected');
			});
			// ...puis on retire l'effet sur la ligne cliquée et on la marque comme sélectionnée
			row.classList.remove('dimmed');
			row.classList.add('selected');
		}
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
