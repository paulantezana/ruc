<div class="Result">
    <h1 class="Result-title">404</h1>
    <?php if (isset($parameter['message']) && $parameter['message'] != '') : ?>
        <p><?= $parameter['message'] ?></p>
    <?php endif; ?>
    <p class="Result-description">Lo sentimos, la página que visitaste no existe</p>
    <a href="<?= URL_PATH ?>/" class="SnBtn primary">Volver al Inicio</a>
</div>