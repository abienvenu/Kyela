document.addEventListener('DOMContentLoaded', function () {
	let tbody = document.getElementById('participants-list');
	let deletedIds = [];
	let deletedInput = document.getElementById('deleted_participants');
	let exportBloc = document.getElementById('export-text');

	// Fonction de mise à jour de la liste export
	function updateExportText() {
		let names = [];
		document.querySelectorAll('#participants-list input[type=\'text\']').forEach(function (input) {
			names.push(input.value);
		});
		exportBloc.innerText = names.join(';');
	}

	// Activation du drag & drop
	new Sortable(tbody, {
		animation: 150,
		ghostClass: 'sortable-ghost',
		handle: '.handle',
		onEnd: () => {
			formModified = true;
		}
	});

	// Suppression d'un participant
	document.querySelectorAll('.delete-participant').forEach(button => {
		button.addEventListener('click', function () {
			const row = this.closest('tr');
			const id = row.dataset.id;
			// Si l'élément a un ID, on le marque pour suppression
			if (id) {
				deletedIds.push(id);
				deletedInput.value = JSON.stringify(deletedIds);
			}
			row.remove();
			updateExportText();
		});
	});

	document.getElementById('new-participants').addEventListener('keydown', function (event) {
		if (event.key === 'Enter') {
			event.preventDefault(); // Empêche la soumission du formulaire
			document.getElementById('add-participants').click(); // Simule un clic sur le bouton "Ajouter"
		}
	});

	// Ajout de nouveaux participants
	document.getElementById('add-participants').addEventListener('click', function () {
		let input = document.getElementById('new-participants');
		let names = input.value.split(';').map(name => name.trim()).filter(name => name);
		input.value = '';

		names.forEach(name => {
			if (name) {
				let row = document.createElement('tr');
				row.innerHTML = `
<td class="handle"><i class="bi bi-list fs-4" style="cursor: grab;"></i></td>
<td>
  <input type="text" name="participants_name[]" value="${name}" class="form-control form-control-sm" required>
  <input type="hidden" name="participants_id[]" value="">
</td>
<td><button type="button" class="btn btn-secondary delete-participant"><i class="bi bi-trash"></i></button></td>
                `;
				tbody.appendChild(row);
				row.querySelector('.delete-participant').addEventListener('click', function () {
					this.closest('tr').remove();
				});
			}
		});
		updateExportText();
	});

	// Mise à jour lors de la modification d'un nom de participant
	tbody.addEventListener('input', function (event) {
		if (event.target.matches('input[type=\'text\']')) {
			updateExportText();
		}
	});

	// Bouton "Copier"
	document.getElementById('copy-btn').addEventListener('click', function () {
		navigator.clipboard.writeText(exportBloc.innerText)
			.then(() => {
				this.querySelector('span').textContent = this.dataset.copiedText;
				setTimeout(() => this.querySelector('span').textContent = this.dataset.copyText, 2000);
			})
			.catch(err => alert('Copy error: ' + err));
	});

	// Update initiale du bloc d'export
	updateExportText();

	// Popup de confirmation "quitter sans enregistrer"
	const form = document.getElementById('participants-form');
	const backButton = document.querySelector('.btn-secondary[href]'); // Bouton "Back"
	let formModified = false;

	// Détecter les changements dans les champs de texte
	form.addEventListener('input', () => {
		formModified = true;
	});

	// Détecter les suppressions de participants
	document.getElementById('participants-list').addEventListener('click', (event) => {
		if (event.target.closest('.delete-participant')) {
			formModified = true;
		}
	});

	// Détecter les ajouts de participants
	document.getElementById('add-participants').addEventListener('click', () => {
		formModified = true;
	});

	// Confirmation avant de quitter sans enregistrer
	backButton.addEventListener('click', function (event) {
		if (formModified) {
			const confirmLeave = confirm(quitNoSaveMessage);
			if (!confirmLeave) {
				event.preventDefault();
			}
		}
	});
});
