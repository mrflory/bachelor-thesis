var currencies = [
  "%01.2f Euro",
  "%01.2f USD",
  "%01.2f \u20ac",
  "$%01.2f",
  "%01.0f Yuán",
  "a%04.3fb (Bsp.: a0023.300b)"
];

/**
 * Initialization, add autocomplete for currency and setup nice radio buttons
 */
$(document).ready(function() {
  $( "#currency" ).autocomplete({
        source: currencies,
        minLength: 0
      }).focus(function(){
        if(this.value == '') {
          $(this).autocomplete('search', '');
        }
      });

  $( ".radio" ).buttonset();
});
