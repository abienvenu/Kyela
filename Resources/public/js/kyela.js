Kyela = {
	init: function()
	{
		$("button.participation").click(function (event) {
			var srcElt = $(this);
			var dstElt = $(this).parent().parent().parent();
			dstElt.children("button").hide();
			dstElt.children("img.ajaxloader").show();
			$.get($(this).data("url"), null, function(data) {
				Kyela.onParticipationUpdateResponse(srcElt, dstElt)
			});
		});
	},
	onParticipationUpdateResponse: function(srcElt, dstElt)
	{
		dstElt.children("img.ajaxloader").hide();
		if (srcElt.data("newcolor") == "none") {
			dstElt.children("button").html(' - ');
			dstElt.children("ul").children("li").last().hide();
		}
		else {
			dstElt.children("button").html(srcElt.html());
			dstElt.children("ul").children("li").last().show();
		}
		dstElt.children("button").append('<span class="caret"></span>');
		dstElt.children("button").removeClass("choice-" + srcElt.data("oldcolor"));
		dstElt.children("button").addClass("choice-" + srcElt.data("newcolor"));
		dstElt.children("button").show();
	}
}

$(document).ready(function () {
	Kyela.init();
});
