<?php if (!defined('ABSPATH')) exit; ?>

<?php if ($is_trial): ?>
    <div style="padding: 10px;border: 1px solid #f00; background-color: #fdd; color: #f00; margin: 10px 2px">
    	<h3 style="color: #f00"><?php _e('MODO DE TESTES', VINDI_IDENTIFIER); ?></h3>

    	<p>
    		<?php _e( 'Sua conta na Vindi está em <strong>Modo Trial</strong>. Este modo é proposto para a realização de testes e, portanto, nenhum pedido será efetivamente cobrado.', VINDI_IDENTIFIER ); ?>
    	</p>
    </div>
<?php endif; ?>
<fieldset>
	<div class="vindi-invoice-description" style="padding: 20px 0; font-weight: bold;">
		<?php
		if ($is_single_order){
			_e('Um Boleto Bancário será enviado para o seu endereço de e-mail.', VINDI_IDENTIFIER);
		} else {
			_e('Um boleto bancário será enviado para o seu e-mail de acordo com a sua assinatura. ', VINDI_IDENTIFIER);
		}
		?>
	</div>
</fieldset>