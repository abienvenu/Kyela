Kyela = {
	init: function()
	{
		$("button.participation").click(function (event) {
			$(this).parent().parent().parent().children("button").hide();
			$(this).parent().parent().parent().children("img.ajaxloader").show();
			$.get($(this).data("url"), null, Kyela.onParticipationUpdateResponse($(this).parent().parent().parent()));
		});
	},
	onParticipationUpdateResponse: function(elt)
	{
		elt.children("img.ajaxloader").hide();
		elt.children("button").show();
		console.log("Done");
	}
}

$(document).ready(function () {
	Kyela.init();
});
