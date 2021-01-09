<div class="MainContainer">
    <h1>Tokens</h1>
    <p>IMPORTANTE: Debes usar esta ruta para tus consultas</p>
    <pre><code><?php echo HOST . URL_PATH ?>/api/v1?token=YOUR_TOKEN</code></pre>
    <div class="SnDivider"></div>
    <div class="SnTable-wrapper SnMb-3">
        <table class="SnTable">
            <thead>
                <tr>
                    <th>PLAN</th>
                    <th>TOKEN</th>
                    <th>CONSULTAS</th>
                    <th>ACTIVO?</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parameter['userToken'] as $row) : ?>
                    <tr>
                        <td><?= $row['tariff_title'] ?></td>
                        <td>
                            <div class="SnControlGroup">
                                <div class="SnControl-wrapper SnControlGroup-input">
                                    <i class="far fa-envelope SnControl-prefix"></i>
                                    <input type="text" class="SnForm-control SnControl" value="<?= $row['api_token'] ?>" id="userTokenData_<?= $row['user_token_id'] ?>" required>
                                </div>
                                <div class="SnControlGroup-append">
                                    <div class="SnBtn icon primary" onclick="copyToken(<?= $row['user_token_id'] ?>)"><i class="far fa-copy"></i></div>
                                </div>
                            </div>
                        </td>
                        <td><?= $row['query_count'] ?> de <?= $row['max_query_count'] ?></td>
                        <td><?= $row['state'] ? '<i class="fas fa-check"></i>' : '<i class="fas fa-ban"></i>' ?></td>
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

<script>
    function copyToken(userTokenId) {
        let userTokenData = document.getElementById(`userTokenData_${userTokenId}`);
        userTokenData.select();
        userTokenData.setSelectionRange(0, 99999);
        document.execCommand("copy");
        SnMessage.success({ content: "El contenido se copió correctamente." });
        return;
    }
</script>
