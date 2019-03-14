<div class="wrap">
    <h1>Planos</h1>
    <?php settings_errors(); ?>

    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="inline-block">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name">

        <?php wp_nonce_field('vindi_plan_create', 'vindi_plan'); ?>
        <input type="hidden" name="action" value="create_plan">
        <input type="submit" value="Enviar">
    </form>

</div>
