<div class="SnTable-wrapper">
    <table class="SnTable" id="censusCurrentTable">
        <thead>
            <tr>
                <th>RUC</th>
                <th>Razón social</th>
                <th>Estado del contribuyente</th>
                <th>Condición de domicilio</th>
                <th>Ubigeo</th>
                <th>Tipo de vía</th>
                <th>Nombre de vía</th>
                <th>Código zona</th>
                <th>Tipo de zona</th>
                <th>Número</th>
                <th>Interior</th>
                <th>Lote</th>
                <th>Departamento</th>
                <th>Kilometro</th>
                <th>Dirección</th>
                <th>Dirección completa</th>
                <th>Ultimo actualización</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parameter['census']['data'] as $row) : ?>
                <tr>
                    <td><?= $row['ruc'] ?></td>
                    <td><?= $row['social_reason'] ?></td>
                    <td><?= $row['taxpayer_state'] ?></td>
                    <td><?= $row['domicile_condition'] ?></td>
                    <td><?= $row['ubigeo'] ?></td>
                    <td><?= $row['type_road'] ?></td>
                    <td><?= $row['name_road'] ?></td>
                    <td><?= $row['zone_code'] ?></td>
                    <td><?= $row['type_zone'] ?></td>
                    <td><?= $row['number'] ?></td>
                    <td><?= $row['inside'] ?></td>
                    <td><?= $row['lot'] ?></td>
                    <td><?= $row['department'] ?></td>
                    <td><?= $row['kilometer'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['full_address'] ?></td>
                    <td><?= $row['last_update_sunat'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$currentPage = $parameter['census']['current'];
$totalPage = $parameter['census']['pages'];
$limitPage = $parameter['census']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="censusList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="censusList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="censusList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="censusList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="censusList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>