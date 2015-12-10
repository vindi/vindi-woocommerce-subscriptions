<?php if (!defined( 'ABSPATH')) exit; ?>

<?php if (! $gateway->container->check_ssl()): ?>
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
    <?php $gateway->generate_settings_html(); ?>
</table>
