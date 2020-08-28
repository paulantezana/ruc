<div class="MainContainer">
    <h1>Consulta RUC Web Service SUNAT</h1>
    <div class="SnForm" id="queryRuc">
        <div class="SnForm-item required">
            <label for="ruc" class="SnForm-label">Prueba ingresando un RUC</label>
            <div class="SnControl-wrapper">
                <i class="fas fa-barcode SnControl-prefix"></i>
                <input type="text" class="SnForm-control SnControl" required id="ruc" name="ruc" placeholder="Nombre de usuario">
            </div>
        </div>
        <input type="hidden" name="googleKey" id="googleKey">
        <button type="submit" class="SnBtn block primary" disabled id="queryRucSubmitBtn" onclick="queryRucSubmit()" name="commit">Consultar</button>
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