<div class="wrap">
    <h1>Plano</h1>
    <?php settings_errors();
    $button = 'Criar';
    $name = '';
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
        $button = 'Editar';
        $plano = $api->get_plan($id);
        $name = $plano['name'];
    }
    ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="inline-block">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" value="<?= $name ?>">
        <?php wp_nonce_field('vindi_plan', 'vindi_plan_nonce'); ?>
        <input type="hidden" name="action" value="update_or_create_plan">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="button" value="<?= $button ?>">
        <input type="submit" value="<?= $button ?>">
    </form>

</div>
