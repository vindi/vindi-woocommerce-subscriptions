<?php if (!defined('ABSPATH')) exit; ?>

<?php if (isset($download_url)): ?>
<div class="woocommerce-message">
	<span>
		<a class="button" href="<?php echo esc_url( $download_url ); ?>" target="_blank">
			<?php _e( 'Baixar boleto', VINDI_IDENTIFIER ); ?>
		</a>
		<?php _e( 'Por favor, clique no botão ao lado para realizar o download do Boleto Bancário.', VINDI_IDENTIFIER ); ?>
		<br/>
		<?php _e( 'Você pode imprimi-lo e pagá-lo via internet banking ou em agências bancárias e lotéricas.', VINDI_IDENTIFIER ); ?>
		<br/>
		<?php _e( 'Após recebermos a confirmação do pagamento, seu pedido será processado.', VINDI_IDENTIFIER ); ?>
	</span>
</div>
<?php endif; ?>
