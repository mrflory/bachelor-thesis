/**
 * This file is being used for the assistant in the backend.
 */

(function($){
    var currencies = [
        "%01.2f Euro",
        "%01.2f USD",
        "%01.2f \u20ac",
        "$%01.2f",
        "%01.0f Yuán",
        "a%04.3fb (Bsp.: a0023.300b)"
    ];
    //Autocomplete for currencies
    $( "#currency" ).autocomplete({
        source: currencies,
        minLength: 0
    }).focus(function(){
        if(this.value == '') {
            $(this).autocomplete('search', '');
        }
    });
    //Setup nice looking radio buttons
    $( ".radio" ).buttonset();
})( jQuery );
