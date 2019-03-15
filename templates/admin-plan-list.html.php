<div class="wrap">
    <h1>Planos</h1>
    <?php settings_errors(); ?>
    <style>
        td, table {
            border: #0f0f0f solid .5px;
        }
    </style>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ação</th>
        </tr>
        <?php
        foreach ($plans as $id => $plan) { ?>
            <tr>
                <td>
                    <?= $id ?>
                </td>
                <td>
                    <?= $plan ?>
                </td>
                <td>
                    <form action="<?= esc_url(admin_url('admin.php?page=vindi_plans_create')) ?>" method="post">
                        <input type="hidden" name="action" value="edit_plan">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php wp_nonce_field('vindi_plan_edit', 'vindi_plan_nonce'); ?>
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>
