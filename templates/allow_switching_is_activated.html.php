<?php if (!defined( 'ABSPATH')) exit; ?>

<div class="error notice">
	<h3><?php echo __('ATENÇÃO!', VINDI_IDENTIFIER );?></h3>
	<p><?php echo __('No momento não é suportado Upgrade de Downgrade de Assinaturas na Vindi, desative a opção '.__("Allow Switching", 'woocommerce-subscriptions').' nas configurações do WooCommerce Subscriptions. Isso pode causar divergência nos valores das assinaturas.', VINDI_IDENTIFIER); ?></p>
</div>
