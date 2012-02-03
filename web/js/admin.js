/**
 * Initialization, add filter sidebar, style buttons
 */
$(document).ready(function() {
  $( "input[type=submit]" ).button();
  
  $( "#sf_admin_bar .sf_admin_filter" ).hide();
  $( "#sf_admin_bar" ).prepend('<div id="togglefilter"><p>Filter</p></div>');
  $( "#togglefilter" ).height($( "#sf_admin_bar .sf_admin_filter" ).innerHeight());

  $( "#togglefilter" ).click(
    function() {
      $( "#sf_admin_bar .sf_admin_filter" ).toggle('slide', {'direction': 'right'}, 200);
    }
  );
});
