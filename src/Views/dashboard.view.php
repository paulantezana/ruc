<div class="SnContent">
    <div class="SnGrid col-gap row-gap m-grid-4">
        <div class="m-col-3">
            <div class="SnCard SnMb-3">
                <div class="SnCard-body">
                    <div class="SnGrid col-gap row-gap m-grid-3">
                        <div>
                            <button type="button" class="SnBtn success jsAensusAction" onclick="censusDowloand(this)">Descargar</button>
                        </div>
                        <div>
                            <button type="button" class="SnBtn jsAensusAction" onclick="censusUnZip(this)">Descomprimir</button>
                        </div>
                        <div>
                            <button type="button" class="SnBtn jsAensusAction" onclick="censusPrepare(this)">Preparar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="SnCard">
                <div class="SnCard-body" id="censusFilesWrapperContainer">
                    <div class="SnMb-5">
                        <div class="SnBtn success SnMr-2" onclick="censusSetAllData()">Guardar Todo</div>
                        <div class="SnBtn primary" onclick="censusClear()">Limpiar</div>
                    </div>
                    <div id="censusFilesWrapper"></div>
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body" id="censusHistoryWrapperContainer">
                <div class="SnBtn primary" onclick="censusSetDataByFile()">Procesar</div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/census.js"></script>