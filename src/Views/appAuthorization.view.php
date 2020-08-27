<div class="SnContent">
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnForm-item">
                <label for="userRoleId">Rol</label>
                <select id="userRoleId" class="SnForm-control">
                    <?php foreach ($parameter['userRole'] as $row) : ?>
                        <option value="<?= $row['user_role_id'] ?>"><?= $row['description'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="SnTable-wrapper SnMb-3">
                <table class="SnTable" id="userRoleAuthTable">
                    <thead>
                        <tr>
                            <th>Modulo</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parameter['appAuthorization'] ?? [] as $row) : ?>
                            <tr data-id="<?= $row['app_authorization_id'] ?>">
                                <td><?= $row['module'] ?></td>
                                <td><?= $row['action'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td>
                                    <div class="SnSwitch" title="Autorizar">
                                        <input class="SnSwitch-control" type="checkbox" id="userRoleAuthState<?= $row['app_authorization_id'] ?>">
                                        <label class="SnSwitch-label" for="userRoleAuthState<?= $row['app_authorization_id'] ?>"></label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="button" onclick="userRoleSaveAuthorization()" class="SnBtn primary block">Guadar</button>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/appAuthorization.js"></script>