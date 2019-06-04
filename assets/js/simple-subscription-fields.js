(
  function ($) {
    'use strict';
    $(function() {
      var plan_infos = $("#vindi_subscription_plan").data('plan-info');

      $("#vindi_subscription_plan").change(function(){
        var id   = $(this).val();
        var plan = plan_infos[id];

        $("#_subscription_period_interval").val(plan.interval_count);
        $("#_subscription_period").val(plan.interval.toString().replace(/s/g, ''));
        $("#_subscription_length").val(plan.billing_cycles || 0);
      });

      $(document).on("change", ".variable_vindi_subscription_plan", function(){
        var id   = $(this).val();
        var plan = plan_infos[id];

        $(this).parents(".data").find(".wc_input_subscription_period_interval").val(plan.interval_count);
        $(this).parents(".data").find(".wc_input_subscription_period").val(plan.interval.toString().replace(/s/g, ''));
        $(this).parents(".data").find(".wc_input_subscription_length").val(plan.billing_cycles || 0);
      });
    });
  }(jQuery)
);
