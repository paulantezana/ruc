<div class="MainContainer HomeContainer">
    <img src="<?= URL_PATH ?>/assets/images/icon/144.png" alt="logo" style="height: 64px;">
    <h1><?= APP_NAME ?></h1>
    <p>Consulta RUC de la SUNAT</br>usando cualquier lenguaje de programación</p>
    <div class="SnCard CardQuery">
        <div class="SnCard-body">
            <div class="SnForm" id="queryRuc">
                <div class="SnForm-item required">
                    <label for="ruc" class="SnForm-label">Prueba ingresando un RUC</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-barcode SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" required id="ruc" name="ruc" placeholder="Nombre de usuario">
                    </div>
                </div>
                <input type="hidden" name="googleKey" id="googleKey">
                <p>Consulta RUC se actualiza DIARIAMENTE con la información que optenemos desde el PADRÓN REDUCIDO que publica la SUNAT todos los días.</p>
                <button type="submit" class="SnBtn block primary" disabled id="queryRucSubmitBtn" onclick="queryRucSubmit()" name="commit">Consultar</button>
            </div>
        </div>
    </div>
    <div class="SnGrid  SnMt-5">
        <?php if (!isset($_SESSION[SESS_KEY])) : ?>
            <a itemprop="name" title="Registrarse" href="<?= URL_PATH ?>/page/register" class="SnBtn primary">Registrarse</a>
            <a itemprop="name" title="Ingresar" href="<?= URL_PATH ?>/page/login" class="SnBtn">Ingresar</a>
        <?php endif; ?>
    </div>
    <div id="queryRucResult"></div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render=<?= GOOGLE_RE_KEY ?>"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('<?= GOOGLE_RE_KEY ?>', {action: 'submit'}).then(function(token) {
        document.getElementById('googleKey').value = token;
        document.getElementById('queryRucSubmitBtn').removeAttribute('disabled');
    });
});
</script>
<script src="<?= URL_PATH ?>/assets/script/home.js"></script>