<?php if (!defined( 'ABSPATH')) exit; ?>
<div class="options_group vindi-subscription_pricing show_if_variable-subscription">

<?php
    woocommerce_wp_select(array(
        'id'                 => 'vindi_variable_subscription_plan[' . $loop . ']',
        'label'              => __('Plano da Vindi', VINDI_IDENTIFIER),
        'options'            => $plans['names'],
        'description'        => __('Selecione o plano da Vindi que deseja relacionar a esse produto', VINDI_IDENTIFIER),
        'desc_tip'           => true,
        'value'              => $selected_plan,
        'class'              => 'select short variable_vindi_subscription_plan'
    ));
?>
</div>
<div class="show_if_subscription clear"></div>