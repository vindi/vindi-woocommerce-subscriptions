<div class="wrap">
    <h1>Plano</h1>
    <?php settings_errors();
    $button = 'Criar';
    $id = $name = $interval = $interval_count = $billing_trigger_type = $billing_trigger_day = $billing_cycles = $installments = $status = '';

    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
        $button = 'Editar';
        $data = json_decode(str_replace('\\', '', $_POST['data']), true);
        $name = $data['name'];
        $interval = $data['interval'];
        $interval_count = $data['interval_count'];
        $billing_trigger_type = $data['billing_trigger_type'];
        $billing_trigger_day = $data['billing_trigger_day'];
        $billing_cycles = $data['billing_cycles'];
        $installments = $data['installments'];
        $status = $data['status'];
    }

    $installments_count = $interval_count;

    if ($interval_count > 12) {
        $installments_count = 12;
    }

    if ('days' === $interval) {
        $installments_count = 1;
    }

    $select = '<select name="installments" id="installments">';
    for ($i = 1; $i <= $installments_count; $i++) {
        $selected = $installments === $i ? 'selected' : '';
        $title = $i === 1 ? 'À vista' : $i;
        $select .= '<option value="' . $i . '" ' . $selected . '>' . $title . '</option>';
    }
    $select .= '</select>';

    ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="inline-block">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" value="<?= $name ?>">
        <label for="interval">Intervalo:</label>
        <select name="interval" id="interval">
            <option value="days" <?= $interval === 'days' ? 'selected' : '' ?>>dias</option>
            <option value="months" <?= $interval === 'months' ? 'selected' : '' ?> >meses</option>
        </select>
        <label for="interval_count">Intervalo:</label>
        <input type="text" name="interval_count" id="interval_count" value="<?= $interval_count ?>">
        <label for="billing_trigger_type">Intervalo:</label>
        <select name="interval" id="interval">
            <option
                value="beginning_of_period" <?= $billing_trigger_type === 'beginning_of_period' ? 'selected' : '' ?>>
                Inicio do período
            </option>
            <option value="end_of_period" <?= $billing_trigger_type === 'end_of_period' ? 'selected' : '' ?>>Fim do
                período
            </option>
            <option value="day_of_month" <?= $billing_trigger_type === 'day_of_month' ? 'selected' : '' ?>>Dia do mês
            </option>
        </select>
        <label for="billing_trigger_day">Dia para geração da cobrança:</label>
        <input type="text" name="billing_trigger_day" id="billing_trigger_day" value="<?= $billing_trigger_day ?>">
        <label for="billing_cycles">Número máximo de períodos em uma assinatura:</label>
        <input type="text" name="billing_cycles" id="billing_cycles" value="<?= $billing_cycles ?>">
        <label for="installments">Parcelas:</label>
        <?= $select ?>
        <label for="status">status:</label>
        <select name="status" id="status">
            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Ativo</option>
            <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inativo</option>
        </select>
        <?php wp_nonce_field('vindi_plan', 'vindi_plan_nonce'); ?>
        <input type="hidden" name="action" value="update_or_create_plan">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="button" value="<?= $button ?>">
        <input type="submit" value="<?= $button ?>">
    </form>

</div>
