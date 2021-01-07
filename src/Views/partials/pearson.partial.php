<div class="SnCard">
    <div class="SnCard-body">
        <div class="SnMb-5">
            <?php if ($parameter['pearson'] != false) : ?>
                <ul>
                    <li>
                        <strong>Nombre:</strong>
                        <span> <?= $parameter['pearson']['name'] ?></span>
                    </li>
                    <li>
                        <strong>Apellido materno:</strong>
                        <span> <?= $parameter['pearson']['motherLastName'] ?></span>
                    </li>
                    <li>
                        <strong>Apellido paterno:</strong>
                        <span> <?= $parameter['pearson']['lastName'] ?></span>
                    </li>
                    <li>
                        <strong>DNI:</strong>
                        <span> <?= $parameter['pearson']['documentNumber'] ?></span>
                    </li>
                    <li>
                        <strong>Sexo:</strong>
                        <span> <?= $parameter['pearson']['sex'] ?></span>
                    </li>
                    <li>
                        <strong>Fecha nacimiento:</strong>
                        <span> <?= $parameter['pearson']['birthDate'] ?></span>
                    </li>
                </ul>
            <?php else : ?>
                <div class="SnEmpty">
                    <img src="<?= URL_PATH . '/assets/images/empty.svg' ?>" alt="local">
                    <div>No se encontró ningún resultado</div>
                </div>
            <?php endif; ?>
        </div>
        <div onclick="queryRucSubmitNewQuery()" class="SnBtn block primary">Otra consulta</div>
    </div>
</div>