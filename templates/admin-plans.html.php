<div class="wrap">
    <h1>Planos</h1>
    <?php settings_errors(); ?>

    <form method="post" action="#" class="inline-block">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name">

        <?php
        wp_nonce_field('vindi_plan_create', 'vindi_plan');
        submit_button('Enviar', 'primary small', 'submit', false);
        ?>
    </form>


    <?php

    if (isset($_POST['name']) && wp_verify_nonce($_POST['vindi_plan'], 'vindi_plan_create')) {
//        $api->create_plan($body);
        require plugin_dir_path(__FILE__,2) . '../includes/teste.php';
    }
    ?>

</div>
