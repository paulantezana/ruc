<div class="SnTable-wrapper">
    <table class="SnTable" id="userCurrentTable">
        <thead>
            <tr>
                <th style="width: 40px">Avatar</th>
                <th>Usuario</th>
                <th>Nombre completo</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parameter['user']['data'] as $row) : ?>
                <tr>
                    <td>
                        <div class="SnAvatar">
                            <?php if($row['avatar'] !== ''): ?>
                                <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $row['avatar'] ?>" alt="avatar">
                            <?php else: ?>
                                <div class="SnAvatar-text"><?= substr($row['user_name'], 0, 2); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['user_role'] ?></td>
                    <td>
                        <div class="SnSwitch" style="height: 18px">
                            <input class="SnSwitch-control" type="checkbox" id="userState<?= $row['user_id'] ?>" type="checkbox" <?php echo $row['state'] ? 'checked' : '' ?> disabled>
                            <label class="SnSwitch-label" for="userState<?= $row['user_id'] ?>"></label>
                        </div>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsUserOption" title="Cambiar contraseña" onclick="userShowModalUpdatePassword(<?= $row['user_id'] ?>)">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="SnBtn icon jsUserOption" title="Editar" onclick="userShowModalUpdate(<?= $row['user_id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$currentPage = $parameter['user']['current'];
$totalPage = $parameter['user']['pages'];
$limitPage = $parameter['user']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="userList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="userList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>