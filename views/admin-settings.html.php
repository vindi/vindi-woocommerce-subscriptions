<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var WC_Vindi_Base_Gateway $this */

if ( ! $this->plugin->checkSsl() ):
	?>
	<div class="error">
		<p>
			<strong>
				<?php _e( 'Vindi WooCommerce Assinaturas Desativado', 'woocommerce-vindi' ); ?></strong>:
			<?php printf( __( 'Um certificado SSL é necessário para ativar este método de pagamento em modo de produção. Por favor, verifique se um certificado SSL está instalado em seu servidor e ative a opção %s.', 'woocommerce-vindi' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section' ) ) . '">' . __( 'Forçar finalização segura', 'woocommerce-vindi' ) . '</a>' ); ?>
		</p>
	</div>
	<?php
endif;
?>
	<h3><?php _e( 'Vindi', 'woocommerce-vindi' ); ?></h3>
	<p><?php _e( 'Utiliza a rede Vindi como meio de pagamento recorrente para cobranças.', 'woocommerce-vindi' ); ?></p>
	<table class="form-table">

	</table>
<?php

if ( ! empty ( $this->api_key ) ) : ?>
	<div class="updated">
		<h3 class="wc-settings-sub-title">
			<?php _e( 'Link de configuração dos Eventos da Vindi', 'woocommerce-vindi' ); ?>
		</h3>

		<p><?php _e( 'Copie esse link e utilize-o para configurar os eventos nos Webhooks da Vindi.', 'woocommerce-vindi' ); ?></p>

		<p>
			<input type="text" value="<?php echo $this->getEventsUrl(); ?>" readonly="readonly" style="width:100%;"
			       onClick="this.select();"/>
		</p>

		<h3 class="wc-settings-sub-title">
			<?php _e( 'Teste de conexão com a Vindi', 'woocommerce-vindi' ); ?>
		</h3>

		<div>
			<?php
			if ( $merchant = $this->api->getMerchant() ) {
				echo '<p>' . __( 'Conectado com sucesso!', 'woocommerce-vindi' ) . '</p>';
				echo '<p>' . sprintf( __( 'Conta: <strong>%s</strong>.', 'woocommerce-vindi' ), $merchant['name'] ) . '</p>';
				echo '<p>' . sprintf( __( 'Status: <strong>%s</strong>.', 'woocommerce-vindi' ), ucwords( $merchant['status'] ) ) . '</p>';
			} else {
				echo '<p>Falha na conexão!</p>';
			}
			?>
		</div>
	</div>
<?php endif;
