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
            <th>ID</th><th>Nome</th>
        </tr>
        <?php
        foreach ($api->get_plans()['names'] as $id => $name){
            echo "<tr>
                    <td>$id</td><td>$name</td>
                  </tr>";
        }
        ?>
    </table>

</div>
