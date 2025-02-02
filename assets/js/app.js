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
