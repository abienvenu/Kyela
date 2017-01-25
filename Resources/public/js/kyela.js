Kyela = {
	init: function()
	{
		$("button.participation").click(function (event) {
			var elt = $(this).parent().parent().parent();
			elt.children("button").hide();
			elt.children("img.ajaxloader").show();
			$.get($(this).data("url"), null, function(data) { Kyela.onParticipationUpdateResponse(data, elt) });
		});
	},
	onParticipationUpdateResponse: function(data, elt)
	{
		elt.children("img.ajaxloader").hide();
		elt.children("button").show();
		console.log("Done, got:" + data.color);
	}
}

$(document).ready(function () {
	Kyela.init();
});
