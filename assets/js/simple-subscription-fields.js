(
  function ($) {
    'use strict';
    $(function() {
      var plan_infos = $("#vindi_subscription_plan").data('plan-info');

      $("#vindi_subscription_plan").change(function(){
        var id   = $(this).val();
        var plan = plan_infos[id];

        console.log($(".wc_input_subscription_period_interval"));
        
        $(".wc_input_subscription_period_interval").val(plan.interval_count);
        $(".wc_input_subscription_period").val(plan.interval.toString().replace(/s/g, ''));
        $(".wc_input_subscription_length").val(plan.billing_cycles || 0);
      });
    });
  }(jQuery)
);
