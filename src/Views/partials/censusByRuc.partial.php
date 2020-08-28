<?php if ($parameter['census'] != false) : ?>
    <ul>
        <li>
            <strong>RUC</strong>
            <span><?= $parameter['census']['ruc'] ?></span>
        </li>
        <li>
            <strong>Razón social</strong>
            <span><?= $parameter['census']['social_reason'] ?></span>
        </li>
        <li>
            <strong>Estado del contribuyente</strong>
            <span><?= $parameter['census']['taxpayer_state'] ?></span>
        </li>
        <li>
            <strong>Condición de domicilio</strong>
            <span><?= $parameter['census']['domicile_condition'] ?></span>
        </li>
        <li>
            <strong>Ubigeo</strong>
            <span><?= $parameter['census']['ubigeo'] ?></span>
        </li>
        <li>
            <strong>Tipo de vía</strong>
            <span><?= $parameter['census']['type_road'] ?></span>
        </li>
        <li>
            <strong>Nombre de vía</strong>
            <span><?= $parameter['census']['name_road'] ?></span>
        </li>
        <li>
            <strong>Código zona</strong>
            <span><?= $parameter['census']['zone_code'] ?></span>
        </li>
        <li>
            <strong>Tipo de zona</strong>
            <span><?= $parameter['census']['type_zone'] ?></span>
        </li>
        <li>
            <strong>Número</strong>
            <span><?= $parameter['census']['number'] ?></span>
        </li>
        <li>
            <strong>Interior</strong>
            <span><?= $parameter['census']['inside'] ?></span>
        </li>
        <li>
            <strong>Lote</strong>
            <span><?= $parameter['census']['lot'] ?></span>
        </li>
        <li>
            <strong>Departamento</strong>
            <span><?= $parameter['census']['department'] ?></span>
        </li>
        <li>
            <strong>Kilometro</strong>
            <span><?= $parameter['census']['kilometer'] ?></span>
        </li>
        <li>
            <strong>Dirección</strong>
            <span><?= $parameter['census']['address'] ?></span>
        </li>
        <li>
            <strong>Dirección completa</strong>
            <span><?= $parameter['census']['full_address'] ?></span>
        </li>
        <li>
            <strong>Ultimo actualización</strong>
            <span><?= $parameter['census']['last_update_sunat'] ?></span>
        </li>
    </ul>
<?php else : ?>
    <div class="Empty"></div>
<?php endif; ?>

<div onclick="queryRucSubmitNewQuery()" class="SnBtn primary">Otra consulta</div>