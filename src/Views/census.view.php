<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>PERU RUC</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsCensusAction" onclick="censusToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsCensusAction" onclick="censusList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                <span class="SnControl-suffix icon-search4"></span>
            </div>
            <div id="censusTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/census.js"></script>