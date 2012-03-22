/**
 * This file is being used to change the filter forms in the advanced section
 * of the backend to add a nice button which slides down the filters.
 */

(function($){
    var admin_bar = $("#sf_admin_bar"),
        admin_filter;
    //Add filter button and slide toggle effect
    if(admin_bar.length > 0) {
        admin_filter = admin_bar.find(".sf_admin_filter").hide();
        admin_bar.prepend('<div id="togglefilter"><p>Filter</p></div>');
        $( "#togglefilter" )
            .height(admin_filter.innerHeight())
            .on('click', function() {
                admin_filter.toggle('slide', {'direction': 'right'}, 200);
            });
    }
    //Add nice looking submit buttons
    $( "input[type=submit]" ).button();
})( jQuery );
