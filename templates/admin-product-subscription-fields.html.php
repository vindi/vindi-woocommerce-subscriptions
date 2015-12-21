<?php if (!defined( 'ABSPATH')) exit; ?>
<style type="text/css">
    ._subscription_sign_up_fee_field,
    ._subscription_trial_length_field,
    ._subscription_trial_period_field {
        display: none !important;
    }
</style>

<div class="options_group vindi-subscription_pricing show_if_subscription">

<?php
    woocommerce_wp_select(array(
        'id'                 => 'vindi_subscription_plan',
        'label'              => __('Plano da Vindi', VINDI_IDENTIFIER),
        'options'            => $plans['names'],
        'description'        => __('Selecione o plano da Vindi que deseja relacionar a esse produto', VINDI_IDENTIFIER),
        'desc_tip'           => true,
        'value'              => $selected_plan,
        'custom_attributes'  => array(
            'data-plan-info' => json_encode($plans['infos'])
        )
    ));
?>
<input type="hidden" name="_subscription_period_interval">
<input type="hidden" name="_subscription_period">
<input type="hidden" name="_subscription_length">
</div>
<div class="show_if_subscription clear"></div>
