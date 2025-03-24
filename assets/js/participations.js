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
						dropdownButton.className = `btn border dropdown-toggle choice-${data.color}`;
						deleteButton.classList.remove('d-none');
					} else {
						dropdownButton.innerHTML = ' - ';
						dropdownButton.className = `btn border dropdown-toggle`;
						deleteButton.classList.add('d-none');
					}

					// Mise à jour du compteur en bas de table
					const cell = opener.closest('td');
					opener.closest('table')
						.querySelector('tfoot')
						.querySelector('tr').cells[cell.cellIndex]
						.querySelector('span').innerHTML = data.score;

					// Mise à jour du compteur de groupe
					let row = cell.closest('tr');
					let previousRow = row.previousElementSibling;
					while (previousRow) {
						if (previousRow.querySelector('th > a.participant-separator')) {
							previousRow.cells[cell.cellIndex].querySelector('span').innerHTML = '('+data.groupScore+')';
						}
						previousRow = previousRow.previousElementSibling;
					}

					// Mise à jour du compteur dans la table des évènements
					const eventTable = document.getElementById('eventTable');
					if (eventTable) {
						eventTable
							.querySelectorAll('tbody tr')[cell.cellIndex - 1].cells[0]
							.querySelector('span').innerHTML = data.score;
					}
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

	// Action quand on confirme la popup
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

	// Stockage des groupes collapsés
	if (!localStorage.getItem('collapsed_groups')) {
		localStorage.setItem('collapsed_groups', JSON.stringify({}));
	}
	const collapsedGroups = JSON.parse(localStorage.getItem('collapsed_groups'));
	if (!collapsedGroups[pollUrl]) {
		collapsedGroups[pollUrl] = [];
	}

	// Restauration de l'état de collapse
	collapsedGroups[pollUrl].forEach(groupName => {
		const toggleElement = document.querySelector(`a[data-group-toggle="${groupName}"]`);
		const rowToCollapse = toggleElement ? toggleElement.closest('tr') : null;
		if (rowToCollapse) {
			const rows = document.querySelectorAll(`tr[data-group="${groupName}"]`);
			rows.forEach(row => row.classList.toggle('d-none'));
			const icon = rowToCollapse.querySelector('.toggle-icon');
			icon.classList.replace('bi-caret-down-fill', 'bi-caret-right-fill');
		}
	});

	// Collapse des groupes de participants
	document.querySelectorAll('.participant-separator').forEach(button => {
		button.addEventListener('click', function () {
			const groupName = this.getAttribute('data-group-toggle');
			const rows = document.querySelectorAll(`tr[data-group="${groupName}"]`);
			const icon = this.querySelector('.toggle-icon');

			// Basculer la visibilité des participants du groupe
			rows.forEach(row => row.classList.toggle('d-none'));

			if (icon.classList.contains('bi-caret-down-fill')) {
				// Collapse
				icon.classList.replace('bi-caret-down-fill', 'bi-caret-right-fill');
				if (collapsedGroups[pollUrl].indexOf(groupName) === -1) {
					collapsedGroups[pollUrl].push(groupName);
				}
			} else {
				// Expand
				icon.classList.replace('bi-caret-right-fill', 'bi-caret-down-fill');
				if (collapsedGroups[pollUrl].indexOf(groupName) !== -1) {
					collapsedGroups[pollUrl].splice(collapsedGroups[pollUrl].indexOf(groupName), 1);
				}
			}
			localStorage.setItem('collapsed_groups', JSON.stringify(collapsedGroups));
		});
	});

	const allRows = document.querySelectorAll('table.participation tbody tr');

	// Stockage de la ligne sélectionnée
	if (!localStorage.getItem('selected_participants')) {
		localStorage.setItem('selected_participants', JSON.stringify({}));
	}
	// Restauration de la ligne sélectionnée
	const selectedParticipants = JSON.parse(localStorage.getItem('selected_participants'));
	if (selectedParticipants[pollUrl]) {
		const rowToSelect = Array.from(allRows)
			.find(row => row.getAttribute('data-name') === selectedParticipants[pollUrl]);
		if (rowToSelect) {
			selectRow(rowToSelect);
		}
	}

	// Sélection de la ligne quand on clique sur un participant
	document.querySelectorAll('.participant').forEach(cell => {
		cell.addEventListener('click', function () {
			const row = cell.closest('tr');
			const participantName = row.getAttribute('data-name');

			// Si la ligne est déjà sélectionnée, on rétablit l'apparence par défaut
			if (row.classList.contains('selected')) {
				clearSelection();
				// On supprime la mémorisation de la sélection
				delete selectedParticipants[pollUrl];
			} else {
				// On ajoute l'effet dimmed et désactive les cellules sur toutes les lignes...
				clearSelection();
				selectRow(row);
				// On mémorise la sélection
				selectedParticipants[pollUrl] = participantName;
			}
			localStorage.setItem('selected_participants', JSON.stringify(selectedParticipants));
		});
	});

	function selectRow(row) {
		// On ajoute l'effet dimmed et désactive les cellules sur toutes les lignes...
		allRows.forEach(r => {
			r.classList.add('dimmed');
			r.querySelectorAll('td').forEach(td => td.classList.add('disabled'));
		});
		// ...puis on retire l'effet sur la ligne cliquée et on la marque comme sélectionnée
		row.classList.remove('dimmed');
		row.classList.add('selected');
		row.querySelectorAll('td').forEach(td => td.classList.remove('disabled'));
	}

	function clearSelection() {
		allRows.forEach(r => {
			r.classList.remove('dimmed', 'selected');
			r.querySelectorAll('td').forEach(td => td.classList.remove('disabled'));
		});
	}

	// Désactivation des clics sur les cellules des lignes non sélectionnées
	document.querySelectorAll('tbody td').forEach(cell => {
		cell.addEventListener('click', function (event) {
			if (cell.classList.contains('disabled')) {
				event.stopPropagation(); // Empêche le clic d'avoir un effet
			}
		});
	});
});
