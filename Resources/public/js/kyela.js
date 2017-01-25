Kyela = {
	init: function()
	{
		$("button.participation").click(function (event) {
			var participationCell = $(this).closest("td");
			participationCell.find("img.ajaxloader").show();
			participationCell.find("button").hide();
			$.get($(this).data("url"), null, function(data) {
				Kyela.onParticipationUpdateResponse(data, participationCell)
			});
		});
	},
	onParticipationUpdateResponse: function(data, participationCell)
	{
		participationCell.html(data);
		Kyela.init();
	}
}

$(document).ready(function () {
	Kyela.init();
});
