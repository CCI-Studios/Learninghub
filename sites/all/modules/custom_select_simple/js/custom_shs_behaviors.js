/**
 * @file
 * Custom behaviors for Simple hierarchical select:
 *   Change the first option text from "- None -" to whatever you want (default: "- Any -").
 *   Create labels for each level.
 */
 (function($) {
  Drupal.behaviors.customShsBehaviors = {
    attach: function (context, settings) {

      // Helper function for an option text replacement.
      // use: optionObj.replaceOptionText(); for default replacement from '- None -' to '- Any -'.
      // or optionObj.replaceOptionText('Foo'); to replace the '- None -' with 'Foo'.
      $.fn.replaceOptionText = function(replacement) {
        var replacement = replacement ? replacement : '- Any -';
        if ($(this).text() == Drupal.t('- None -')) {
          $(this).text(Drupal.t(replacement));
        }
      };

      // Function that helps to get the select element width including its padding and margin.
      $.fn.getSelectWidth = function() {
        var width = Math.round($(this).wrap('<span></span>').outerWidth(true));
        $(this).unwrap();
        return width;
      }

      // Define labels.
      var labels = ['Competency', 'Task Group', 'Level Indicator'];

      // act when the ajaxStop event is triggered (there are no more Ajax requests being processed.)
      // see: @link http://api.jquery.com/Ajax_Events/ @endlink
      $('.shs-wrapper-processed').ajaxStop(function(event, xhr, settings) {

        $("select.shs-select").each(function(level) {

          // fix the SHS bug - new level that is being created has the same ID as the previous one.
          // make sure that each select has an ID and the class corresponding to the level.
          // YOU CAN USE THESE TWO LINES ONLY IF YOU ARE USING SHS WITH THE ABILITY TO ADD NEW LEVELS.
          $(this).attr('id', $(this).attr('id').replace(/(.*)select-(\d+)/, "$1select-" + (level+1)));
          $(this).attr('class', $(this).attr('class').replace(/(.*)level-(\d+)/, "$1level-" + (level+1)));

          // Replace the first option text using the helper function for an option text replacement.
          $("option", this).first().replaceOptionText();

          // Get label width.
          var labelWidth = $(this).getSelectWidth();

          // Create label.
          if ($("label[for='" + $(this).attr('id') + "']").length == 0) {

            $(this).before(
              "<label for='" + $(this).attr('id') + "' style='display: inline-block; width: " + labelWidth + "px'>" + labels[level] + "</label>"
            );
          }

          // Remove the label if the select element has just been removed by clicking any option on lower level.
          // There might be several Ajax requests bound with this action,
          // so we have to make sure that our "removing process" will be executed at most once.
          // See: @link http://api.jquery.com/one/ @endlink
          $("option:not(:selected)", this).one("click", function() {
            $(".shs-wrapper-processed").find("label").each(function() {

               if ($(this).attr('for').slice(-1) > level+1) {
                $(this).remove();
              }
           
            });
          });
        });
      });
    }
  };
})(jQuery);