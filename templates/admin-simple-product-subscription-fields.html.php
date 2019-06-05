<?php if (!defined( 'ABSPATH')) exit; ?>
<style type="text/css">
    ._subscription_sign_up_fee_field,
    ._subscription_trial_length_field,
    ._subscription_trial_period_field,
    ._subscription_period_interval_field,
    ._subscription_period_field,
    ._subscription_length_field,
    .wc_input_subscription_period,
    .wc_input_subscription_period_interval,
    .wc_input_subscription_intial_price,
    .wc_input_subscription_trial_length,
    .wc_input_subscription_trial_period,
    .variable_subscription_trial {
        display: none !important;
    }
</style>
<div class="options_group vindi-subscription_pricing show_if_subscription show_if_variable-subscription">

<?php

    if (preg_match('/variable-subscription/', $product_type)) {
        $label = __('Plano padrão da Vindi', VINDI_IDENTIFIER);
        $description = __('Selecione o plano padrão da Vindi que deseja relacionar a esse produto caso não especifique na variação', VINDI_IDENTIFIER);
    } else {
        $label = __('Plano da Vindi', VINDI_IDENTIFIER);
        $description = __('Selecione o plano da Vindi que deseja relacionar a esse produto', VINDI_IDENTIFIER);
    }

    woocommerce_wp_select(array(
        'id'                 => 'vindi_subscription_plan',
        'label'              => $label,
        'options'            => $plans['names'],
        'description'        => $description,
        'desc_tip'           => true,
        'value'              => $selected_plan,
        'custom_attributes'  => array(
            'data-plan-info' => json_encode($plans['infos'])
        )
    ));
?>
</div>
<div class="show_if_subscription clear"></div>
