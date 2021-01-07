<div class="MainContainer HomeContainer">
    <div style="text-align: center;" class="SnMt-5 SnMb-5">
        <h1 style="margin: 0; line-height: 1em;">Consulta RUC Y DNI</h1>
        <p>Consulta RUC de la SUNAT Y DNI </br>Usando cualquier lenguaje de programación</p>
    </div>
    <div class="SnCard CardQuery SnMt-5" style="max-width: 400px; margin: auto" id="queryRuc">
        <div class="SnCard-body">
            <div class="SnForm-item">
                <label for="ruc" class="SnForm-label">Tipo de documento</label>
                <select class="SnForm-control" id="documentType">
                    <option value="ruc">RUC</option>
                    <option value="dni">DNI</option>
                </select>
            </div>
            <div class="SnForm-item required">
                <label for="ruc" class="SnForm-label">Número de Documento DNI/RUC</label>
                <div class="SnControl-wrapper">
                    <i class="fas fa-barcode SnControl-prefix"></i>
                    <input type="text" class="SnForm-control SnControl" required id="documentNumber" placeholder="Número de Documento DNI/RUC">
                </div>
            </div>
            <input type="hidden" name="googleKey" id="googleKey">
            <button type="submit" class="SnBtn block primary" disabled id="queryRucSubmitBtn" onclick="queryRucSubmit()" name="commit">Consultar</button>
        </div>
    </div>
    <div id="queryRucResult" style="max-width: 400px; margin: auto"></div>
    <div style="margin-top: 5rem;">
        <?php require_once(__DIR__ . '/partials/howToUse.php') ?>
    </div>
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