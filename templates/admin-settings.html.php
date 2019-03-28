<?php if (!defined( 'ABSPATH')) exit; ?>

<?php if (! $settings->check_ssl()): ?>
<div class="error">
    <p>
        <strong><?php _e('Vindi WooCommerce Desabilitado', VINDI_IDENTIFIER); ?></strong>:
        <?php printf(__('É necessário um <strong> Certificado SSL </strong> para ativar este método de pagamento em modo de produção. Por favor, verifique se um certificado SSL está instalado em seu servidor !')); ?>
    </p>
</div>
<?php endif; ?>

<h3><?php _e('Vindi', VINDI_IDENTIFIER); ?></h3>
<p><?php _e('Utiliza a rede Vindi como meio de pagamento para cobranças.', VINDI_IDENTIFIER); ?></p>
<table class="form-table">
    <?php $settings->generate_settings_html(); ?>
</table>
<?php

    $merchant = false;
    $api_key  = $settings->get_api_key();
    if(!empty($api_key))
        $merchant = $settings->api->get_merchant(true);

?>
<div class="below-h2 <?php echo $merchant !== false ? 'updated' : 'error'; ?>">
	<h3 class="wc-settings-sub-title">
		<?php _e('Link de configuração dos Eventos da Vindi', VINDI_IDENTIFIER); ?>
	</h3>

	<p><?php _e( 'Copie esse link e utilize-o para configurar os eventos nos Webhooks da Vindi.', VINDI_IDENTIFIER); ?></p>

	<p>
		<input type="text" value="<?php echo $settings->get_events_url(); ?>" readonly="readonly" style="width:100%;"
		       onClick="this.select();"/>
	</p>

	<h3 class="wc-settings-sub-title">
		<?php _e( 'Teste de conexão com a Vindi', VINDI_IDENTIFIER); ?>
	</h3>

	<div>
		<?php
		if ($merchant) {
			echo '<p>' . __( 'Conectado com sucesso!', VINDI_IDENTIFIER) . '</p>';
			echo '<p>' . sprintf( __( 'Conta: <strong>%s</strong>.', VINDI_IDENTIFIER), $merchant['name']) . '</p>';
			echo '<p>' . sprintf( __( 'Status: <strong>%s</strong>.', VINDI_IDENTIFIER), ucwords($merchant['status'])) . '</p>';
		} else {
			echo sprintf('<p>Falha na conexão! <br><b>%s</b></</p>', $settings->api->last_error);
		}
		?>
	</div>
</div>
