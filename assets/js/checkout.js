(
  function ($) {
    'use strict';
    $(function() {
      $('.vindi-old-cc-data').hide();
      $('.wc-credit-card-form-card-number').payment('formatCardNumber');
      $('.wc-credit-card-form-card-cvc').payment('formatCardCVC');
      $('body').on('updated_checkout', function() {
        $('.wc-credit-card-form-card-number').payment('formatCardNumber');
        $('.wc-credit-card-form-card-cvc').payment('formatCardCVC');
        $(".wc-vindi-change-card").bind('click', function(){
          $('.vindi-old-cc-data').hide();
          $('.vindi-old-cc-data').find('input').prop('disabled', true);

          $('.vindi-new-cc-data').find('input, select').prop('disabled', false);
          $('.vindi-new-cc-data').show();

          return false;
        });

        if($('.vindi-old-cc-data').length) {
          $('.vindi-old-cc-data').show();
          $('.vindi-old-cc-data').find('input').prop('disabled', false);

          $('.vindi-new-cc-data').find('input, select').prop('disabled', true);
          $('.vindi-new-cc-data').hide();
        }
      });

    });
  }(jQuery)
);
