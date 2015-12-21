(
  function ($) {
    'use strict';
    $(function() {
      var plan_infos = $("#vindi_subscription_plan").data('plan-info');
      $("._subscription_period_interval_field, ._subscription_period_field, ._subscription_length_field").remove();

      $("#vindi_subscription_plan").change(function(){
        var id   = $(this).val();
        var plan = plan_infos[id];

        console.log(plan);

        $("input[name=_subscription_period_interval]").val(plan.interval_count);
        $("input[name=_subscription_period]").val(plan.interval.toString().replace(/s/g, ''));
        $("input[name=_subscription_length]").val(plan.billing_cycles);
      });
    });
  }(jQuery)
);
