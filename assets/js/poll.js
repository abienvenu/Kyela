// Scripts de mise à jour de la participations
document.addEventListener('DOMContentLoaded', () => {
	const confirmationDialog = new bootstrap.Modal(document.getElementById('participation_confirmation'));
	let opener;

	// Call AJAX et mise à jour de l'option affichée dans le tableau
	function updateParticipation() {
		const participationUrl = opener.getAttribute('data-url');
		fetch(participationUrl)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Trouver le bouton principal parent
					const dropdownButton = opener.closest('div').querySelector('.dropdown-toggle');
					const deleteButton = opener.closest('ul').querySelector('li:last-of-type');

					if (data.name) {
						dropdownButton.innerHTML = `<i class="bi bi-${data.icon} me-2"></i> ${data.name}`;
						dropdownButton.className = `btn border dropdown-toggle choice-${data.color} shadow`;
						deleteButton.classList.remove('d-none');
					} else {
						dropdownButton.innerHTML = ' - ';
						dropdownButton.className = `btn border dropdown-toggle shadow`;
						deleteButton.classList.add('d-none');
					}

					// Mise à jour des compteurs
					const cell = opener.closest('td');
					opener.closest('table')
						.querySelector('tfoot')
						.querySelector('tr').cells[cell.cellIndex]
						.querySelector('span').innerHTML = data.score;
					document.getElementById('eventTable')
						.querySelectorAll('tbody tr')[cell.cellIndex - 1].cells[0]
						.querySelector('span').innerHTML = data.score;

				} else {
					alert('Erreur lors de la mise à jour de la participation.');
				}
			})
			.catch(error => console.error('Erreur:', error));
	}

	// Action quand on choisit une option
	document.querySelectorAll('.participation .dropdown-item').forEach(item => {
		item.addEventListener('click', function (event) {
			event.preventDefault(); // Empêche le lien de changer de page
			opener = this;

			// Update managed participants, ask confirmation for any new one
			const participantName = this.closest('tr').getAttribute('data-name');
			if (!localStorage.getItem('managed_participants')) {
				localStorage.setItem('managed_participants', JSON.stringify([participantName]));
			} else {
				let managedParticipants = JSON.parse(localStorage.getItem('managed_participants'));
				if (managedParticipants.indexOf(participantName) === -1) {
					document.getElementById('participation_confirmation_name').innerHTML = participantName;
					confirmationDialog.show();
					return;
				}
			}
			updateParticipation();
		});
	});

	document.getElementById('participation_confirmation').querySelectorAll('button').forEach(button => {
		button.addEventListener('click', () => {
			const answer = button.getAttribute('data-answer');
			if (answer === 'always') {
				const participantName = opener.closest('tr').getAttribute('data-name');
				let managedParticipants = JSON.parse(localStorage.getItem('managed_participants'));
				managedParticipants.push(participantName);
				localStorage.setItem('managed_participants', JSON.stringify(managedParticipants));
			}
			if (answer === 'always' || answer === 'once') {
				updateParticipation();
			}
			confirmationDialog.hide();
		});
	});

	// Collapse des groupes de participants
	document.querySelectorAll('.participant-separator').forEach(button => {
		button.addEventListener('click', function () {
			const groupName = this.getAttribute('data-group-toggle');
			document.querySelectorAll(`tr[data-group="${groupName}"]`).forEach(row => {
				row.classList.toggle('d-none');
			});
		});
	});
});

// Switch des icônes en haut à droite quand on plie/déplie
document.querySelectorAll('.toggle-button').forEach(button => {
	const icon = button.querySelector('i');
	const target = document.querySelector(button.dataset.bsTarget);
	const targetId = button.dataset.bsTarget; // Identifiant unique pour le localStorage

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

// Sélection d'une ligne seule
document.querySelectorAll('.participant').forEach(cell => {
	cell.addEventListener('click', function () {
		const row = cell.closest('tr');
		const allRows = document.querySelectorAll('table.participation tbody tr');

		// Si la ligne est déjà sélectionnée, on rétablit l'apparence par défaut
		if (row.classList.contains('selected')) {
			allRows.forEach(r => {
				r.classList.remove('dimmed', 'selected');
				r.querySelectorAll('td').forEach(td => td.classList.remove('disabled'));
			});
		} else {
			// On ajoute l'effet dimmed et désactive les cellules sur toutes les lignes...
			allRows.forEach(r => {
				r.classList.add('dimmed');
				r.classList.remove('selected');
				r.querySelectorAll('td').forEach(td => td.classList.add('disabled'));
			});
			// ...puis on retire l'effet sur la ligne cliquée et on la marque comme sélectionnée
			row.classList.remove('dimmed');
			row.classList.add('selected');
			row.querySelectorAll('td').forEach(td => td.classList.remove('disabled'));
		}
	});
});

// Désactivation des clics sur les cellules des lignes non sélectionnées
document.querySelectorAll('tbody td').forEach(cell => {
	cell.addEventListener('click', function (event) {
		if (cell.classList.contains('disabled')) {
			event.stopPropagation(); // Empêche le clic d'avoir un effet
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
