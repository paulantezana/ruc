<div class="MainContainer">
    <h1>Tokens</h1>
    <p>IMPORTANTE: Debes usar esta ruta para tus consultas</p>
    <pre><code><?php echo HOST . URL_PATH ?>/api/v1?token=YOUR_TOKEN</code></pre>
    <div class="SnDivider"></div>
    <div class="SnTable-wrapper SnMb-3">
        <table class="SnTable">
            <thead>
                <tr>
                    <th>DESCRIPCIÓN</th>
                    <th>TOKEN</th>
                    <th>ÚLTIMA CONEXIÓN</th>
                    <th>IP DE ÚLTIMA CONSULTA</th>
                    <th>CONSULTAS</th>
                    <th>ACTIVO?</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parameter['userToken'] as $row) : ?>
                    <tr>
                        <td>-</td>
                        <td><input type="text" class="SnForm-control" value="<?= $row['api_token'] ?>"></td>
                        <td></td>
                        <td><?= $row['updated_at'] ?></td>
                        <td><?= $row['query_count'] ?></td>
                        <td></td>
                        <td>
                            <!-- <div class="SnTable-action">
                                <div class="SnBtn icon jsUserOption" title="Clave" onclick="userShowModalApiToken(<?= $row['user_token_id'] ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                            </div> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php require_once(__DIR__ . '/partials/howToUse.php') ?>
</div>
