<div class="wrap">
    <h1>Listar Planos</h1>
    <?php settings_errors();


    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($plans['names'] as $id => $plan) : ?>
            <tr>
                <td><?= $id ?></td>
                <td><?= $plan ?></td>
                <td><?= $plans['infos'][$id]['status'] === 'active' ? 'Ativo' : 'Inativo' ?></td>
                <td>
                    <form action="<?= esc_url(admin_url('admin.php?page=vindi_plans_create')) ?>" method="post">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="data" value='<?= json_encode($plans['infos'][$id]) ?>'>
                        <?php wp_nonce_field('vindi_plan_edit', 'vindi_plan_nonce'); ?>
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>