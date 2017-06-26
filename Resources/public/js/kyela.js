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

// Thanks to http://www.miximum.fr/creer-une-liste-triable-avec-symfony-et-jquery-ui.html
$(function() {
    $("table tbody.sortable").sortable({
        // limitons les déplacements sur l'axe des ordonnées, ce sera plus propre
        axis: 'y',

        // Il faut cliquer sur cet élément pour pouvoir initier le drag'n'drop
        handle: 'td',

        // Créons un joli trou stylé lors des déplacements
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,

        // Cette fonction permet à notre ligne de conserver son formatage lors du déplacement
        // Pas vraiment utile, mais plus agréable à l'œil
        helper: function(e, tr)
        {
          var $originals = tr.children();
          var $helper = tr.clone();
          $helper.children().each(function(index)
          {
            // Set helper cell sizes to match the original sizes
            $(this).width($originals.eq(index).width())
          });
          return $helper;
        },

        // La fonction appelée quand un élément change de position
        // C'est le code vraiment utile, en fait
        update: function(event, ui){
          // Construit un tableau des ids des stories
          var serial = $(this).sortable('serialize');

          // Appelle une action en ajax
          $.post($(this).data("sort-url"), serial,
            function(response) {
              if (response.code != 100) {
                  alert('Failed to save order')
              }
            }
          );
        }
    });
});

$(document).ready(function () {
	Kyela.init();
});
