Kyela = {
	init: function()
	{
		$("button.participation").click(function (event) {
			$.get($(this).data("url"), null, Kyela.onParticipationUpdateResponse);
		});
	},
	onParticipationUpdateResponse: function()
	{
		console.log("Done.");
	}
}

$(document).ready(function () {
	Kyela.init();
});
