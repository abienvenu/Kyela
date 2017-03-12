Kyela = {
	init: function()
	{
		$("button.participation").click(Kyela.onParticipationClick);
	},
	onParticipationClick: function ()
	{
		var participationCell = $(this).closest("td");
		participationCell.find("img.ajaxloader").show();
		participationCell.find("button").hide();
		$.get($(this).data("url"), null, function(data) {
			Kyela.onParticipationUpdateResponse(data, participationCell);
		});

	},
	onParticipationUpdateResponse: function(data, participationCell)
	{
		participationCell.html(data);
		var score = participationCell.find("div").data("score");
		participationCell.closest("tbody").next("tfoot").find("th").eq(participationCell.index()).html(score);
		$("div.list-group").find("span.badge").eq(participationCell.index()-1).html(score);
		participationCell.find("button.participation").click(Kyela.onParticipationClick);
	}
};

$(document).ready(function () {
	Kyela.init();
});
