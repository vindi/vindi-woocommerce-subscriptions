<?php if (!defined( 'ABSPATH')) exit; ?>

<?php if (! $gateway->container->check_ssl()): ?>
    <div class="error">
        <p>
            <?php printf(__('É necessário um <strong> Certificado SSL </strong> para ativar este método de pagamento em modo de produção. Por favor, verifique se um certificado SSL está instalado em seu servidor !')); ?> 
        </p>
    </div>
<?php endif; ?>

<h3><?php _e('Vindi', VINDI_IDENTIFIER); ?></h3>
<p><?php _e('Utiliza a rede Vindi como meio de pagamento recorrente para cobranças.', VINDI_IDENTIFIER); ?></p>
<table class="form-table">
    <?php $gateway->generate_settings_html(); ?>
</table>
