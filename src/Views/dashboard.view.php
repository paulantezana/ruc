<div class="SnContent">
    <div class="SnGrid col-gap row-gap m-grid-4">
        <div class="m-col-3">
            <div class="SnCard SnMb-3">
                <div class="SnCard-body">
                    <div class="SnCard-title">Descargar y preparar archivo</div>
                    <ol>
                        <li class="SnMb-5">
                            <div class="SnMb-2">Descargar el padrón reducido desde la SUNAT.  El archivo suele pesar como 400MB aproximadamente.</div>
                            <div class="SnGrid col-gap row-gap m-grid-2 l-grid-3 SnMb-4">
                                <div class="SnSwitch">
                                    <input class="SnSwitch-control" type="checkbox" id="enabledAgent">
                                    <label class="SnSwitch-label" for="enabledAgent">CURLOPT_USERAGENT</label>
                                </div>
                                <div class="SnSwitch">
                                    <input class="SnSwitch-control" type="checkbox" id="enabledVerifyHost">
                                    <label class="SnSwitch-label" for="enabledVerifyHost">CURLOPT_SSL_VERIFYHOST</label>
                                </div>
                                <div class="SnSwitch">
                                    <input class="SnSwitch-control" type="checkbox" id="enabledVerfyPer" checked>
                                    <label class="SnSwitch-label" for="enabledVerfyPer">CURLOPT_SSL_VERIFYPEER</label>
                                </div>
                            </div>
                            <button type="button" class="SnBtn primary jsAensusAction" onclick="censusDowloand(this)"><i class="fas fa-download SnMr-2"></i>Iniciar descarga</button>
                        </li>
                        <li class="SnMb-5">
                            <div class="SnMb-2">Una ves que la descarga haya finalizado descomprima el archivo zip.</div>
                            <button type="button" class="SnBtn primary jsAensusAction" onclick="censusUnZip(this)"><i class="far fa-file-archive SnMr-2"></i>Descomprimir</button>
                        </li>
                        <li class="SnMb-5">
                            <div class="SnMb-2">Para aligerar el procesamiento de inserción a la base de datos divida el archivo en múltiples partes.</div>
                            <div class="SnSwitch" style="display: none;">
                                <input class="SnSwitch-control" type="checkbox" id="enabledIsSql">
                                <label class="SnSwitch-label" for="enabledIsSql">CURLOPT_SSL_VERIFYPEER</label>
                            </div>
                            <button type="button" class="SnBtn primary jsAensusAction" onclick="censusPrepare(this)"><i class="fas fa-columns SnMr-2"></i>Dividir archivo</button>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="SnCard">
                <div class="SnCard-body" id="censusFilesWrapperContainer">
                    <div class="SnMb-5">
                        <div class="SnMb-5">
                            <div class="SnSwitch">
                                <input class="SnSwitch-control" type="checkbox" id="enabledFirstTime">
                                <label class="SnSwitch-label" for="enabledFirstTime">Guardar datos a una base de datos vacía.</label>
                            </div>
                        </div>
                        <div class="SnBtn success SnMr-2" onclick="censusSetAllData()"><i class="far fa-save SnMr-2"></i>Guardar Todo</div>
                        <div class="SnBtn primary SnMr-2" onclick="censusClear()"><i class="far fa-sticky-note SnMr-2"></i>Limpiar archivos</div>
                        <div class="SnBtn error" onclick="censusTruncate()"><i class="fas fa-database SnMr-2"></i>Limpiar DB</div>
                    </div>
                    <div id="censusFilesWrapper"></div>
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body" id="censusHistoryWrapperContainer">
                <div id="scandirWrapper"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/dashboard.js"></script>