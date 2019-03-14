<div class="wrap">
    <h1>Planos</h1>
    <?php settings_errors(); ?>

    <form method="post" action="" class="inline-block">
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name">

        <?php
        wp_nonce_field('vindi_plan_create', 'vindi_plan');
        submit_button('Enviar', 'primary small', 'submit', false);
        ?>
    </form>


    <?php

    if (isset($_POST['teste'])) {
        wp_nonce_field('vindi_plan_create', 'vindi_plan');
        $handler = fopen(plugin_dir_path(__FILE__) . 'teste.txt', "w+");
        fwrite($handler, $_POST['teste']);
        fclose($handler);
    }
    ?>

</div>
