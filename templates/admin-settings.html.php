<?php if (! $settings->check_ssl()): ?>
<div class="error">
    <p>
        <strong><?php _e('Vindi WooCommerce Assinaturas Desativado', VINDI_IDENTIFIER); ?></strong>:
        <?php printf(__('Um certificado SSL é necessário para ativar este método de pagamento em modo de produção. Por favor, verifique se um certificado SSL está instalado em seu servidor e ative a opção %s.', VINDI_IDENTIFIER), '<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section')) . '">' . __('Forçar finalização segura', VINDI_IDENTIFIER) . '</a>'); ?>
    </p>
</div>
<?php endif; ?>

<h3><?php _e('Vindi', VINDI_IDENTIFIER); ?></h3>
<p><?php _e('Utiliza a rede Vindi como meio de pagamento recorrente para cobranças.', VINDI_IDENTIFIER); ?></p>
<table class="form-table">
    <?php $settings->generate_settings_html(); ?>
</table>
<?php

    $merchant = false;

    if(!empty($settings->get_api_key()))
        $merchant = $settings->api->get_merchant();

?>
<div class="<?php echo $merchant !== false ? 'updated' : 'error'; ?>">
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
