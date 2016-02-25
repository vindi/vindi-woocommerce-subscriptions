(
  function ($) {
    'use strict';
    $(function() {
      var plan_infos = $("#vindi_subscription_plan").data('plan-info');

      $("#vindi_subscription_plan").change(function(){
        var id   = $(this).val();
        var plan = plan_infos[id];

        $("select[name=_subscription_period_interval]").val(plan.interval_count);
        $("select[name=_subscription_period]").val(plan.interval.toString().replace(/s/g, ''));
        $("select[name=_subscription_length]").val(plan.billing_cycles);
      });
    });
  }(jQuery)
);
