<?php if (!defined('ABSPATH')) exit; ?>

<?php if ($is_trial): ?>
    <div style="padding: 10px;border: 1px solid #f00; background-color: #fdd; color: #f00; margin: 10px 2px">
        <h3 style="color: #f00"><?php _e( 'MODO DE TESTES', VINDI_IDENTIFIER ); ?></h3>
        <p>
            <?php _e('Sua conta na Vindi está em <strong>Modo Trial</strong>. Este modo é proposto para a realização de testes e, portanto, nenhum pedido será efetivamente cobrado.', VINDI_IDENTIFIER); ?>
        </p>
    </div>
<?php endif; ?>

<fieldset class="vindi-fieldset">
    <p class="form-row form-row-first">
        <label for="vindi_cc_fullname">
            <?php _e("Nome Impresso no Cartão", VINDI_IDENTIFIER); ?>
            <span class="required">*</span>
        </label>
        <input type="text" class="input-text" name="vindi_cc_fullname"/>
    </p>

    <p class="form-row form-row-last">
        <label for="vindi_cc_number">
            <?php _e("Número do Cartão de Crédito", VINDI_IDENTIFIER); ?>
            <span class="required">*</span>
        </label>
        <input type="text" class="input-text wc-credit-card-form-card-number" maxlength="20" name="vindi_cc_number" autocomplete="off" placeholder="•••• •••• •••• ••••" style="padding-right:55px"/>
    </p>

    <div class="clear"></div>

    <p class="form-row form-row-first">
        <label for="vindi_cc_monthexpiry"><?php _e( "Validade do Cartão", VINDI_IDENTIFIER ) ?>
            <span class="required">*</span>
        </label>

        <select name="vindi_cc_monthexpiry" class="input-text">
            <?php echo $months; ?>
        </select>

        <select name="vindi_cc_yearexpiry" style="width:90px" class="input-text">
            <?php echo $years; ?>
        </select>
    </p>

    <p class="form-row form-row-last">
        <label for="vindi_cc_cvc">
            <?php _e("Código de Segurança do Cartão", VINDI_IDENTIFIER); ?>
            <span class="required">*</span>
        </label>

        <input type="text" class="input-text wc-credit-card-form-card-cvc" name="vindi_cc_cvc" placeholder="CCV" autocomplete="off" maxlength="4" style="width: 3em;"/>

        <span class="help vindi_card_csc_description">
            <?php _e( '3 ou 4 dígitos localizados do verso do cartão.', VINDI_IDENTIFIER ); ?>
        </span>
    </p>

    <?php if ( isset( $installments ) ) : ?>
        <p class="form-row form-row-wide">
            <label for="vindi_cc_installments"><?php _e("Número de Parcelas", VINDI_IDENTIFIER); ?>
                <span class="required">*</span>
            </label>
            <select name="vindi_cc_installments" class="input-text" style="width: 100%">
                <?php echo $installments; ?>
            </select>
        </p>
    <?php endif; ?>

<div class="clear"></div>
</fieldset>
