document.addEventListener("DOMContentLoaded", () => {
    censusGetFiles();
    scandirWrapper();
});

function censusSetLoading(state, element) {
    if (state) {
        element.setAttribute("disabled", "disabled");
        element.classList.add("loading");
    } else {
        element.removeAttribute("disabled");
        element.classList.remove("loading");
    }
    // let jsAensusAction = document.querySelectorAll(".jsAensusAction");
    // if (state) {
    //     if (jsAensusAction) {
    //         jsAensusAction.forEach((item) => {
    //             item.setAttribute("disabled", "disabled");
    //             item.classList.add("loading");
    //         });
    //     }
    // } else {
    //     if (jsAensusAction) {
    //         jsAensusAction.forEach((item) => {
    //             item.removeAttribute("disabled");
    //             item.classList.remove("loading");
    //         });
    //     }
    // }
}

function censusGetFiles() {
    SnFreeze.freeze({ selector: "#censusFilesWrapperContainer" });
    RequestApi.fetch("/admin/census/getFiles")
        .then((res) => {
            if (res.success) {
                let censusFilesWrapperContainer = document.getElementById(
                    "censusFilesWrapper"
                );
                if (censusFilesWrapperContainer) {
                    censusFilesWrapperContainer.innerHTML = res.html;
                }
            } else {
                SnModal.error({
                    title: "Algo salió mal",
                    content: res.message,
                });
            }
        })
        .finally((e) => {
            SnFreeze.unFreeze("#censusFilesWrapperContainer");
        });
}

function censusDowloand(element) {
    SnModal.confirm({
        title: "¿Está seguro de descargar el padrón ruc en el servidor?",
        content: "Esta acción descargará el padrón reducido de ruc de la SUNAT",
        onOk() {
            censusSetLoading(true, element);
            
            let option = {}
            option.enabledAgent = document.getElementById('enabledAgent').checked;
            option.enabledVerifyHost = document.getElementById('enabledVerifyHost').checked;
            option.enabledVerfyPer = document.getElementById('enabledVerfyPer').checked;
            
            RequestApi.fetch("/admin/census/dowloand",{
                method: "POST",
                body: option,
            })
                .then((res) => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                })
                .finally((e) => {
                    censusSetLoading(false, element);
                    scandirWrapper();
                });
        },
    });
}

function censusUnZip(element) {
    SnModal.confirm({
        title: "Esta seguro de descomprimir el archivo zip descargado?",
        content:
            "Esta acción descomprimirá el padrón en formato zip previamente descargado.",
        onOk() {
            censusSetLoading(true, element);
            RequestApi.fetch("/admin/census/unZip")
                .then((res) => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                })
                .finally((e) => {
                    censusSetLoading(false, element);
                    scandirWrapper();
                });
        },
    });
}

function censusPrepare(element) {
    SnModal.confirm({
        title: "Esta seguro de preparar los datos?",
        content:
            "Esta acción dividirá en archivos independientes para optimizar la inserción en la base de datos.",
        onOk() {
            censusSetLoading(true, element);
            RequestApi.fetch("/admin/census/prepare")
                .then((res) => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                    censusGetFiles();
                })
                .finally((e) => {
                    censusSetLoading(false, element);
                    scandirWrapper();
                });
        },
    });
}

function censusSetData(censusFileId) {
    SnModal.confirm({
        title: "Esta seguro de almacenar los datos?",
        content: "Esta acción insertara los datos a la base de datos.",
        onOk() {
            censusSetDataItem(censusFileId);
        },
    });
}

function censusSetAllData() {
    SnModal.confirm({
        title: "Insertar todo los datos?",
        content: "Esta acción insertara todos los datos a la base de datos.",
        onOk() {
            SnFreeze.freeze({ selector: "#censusFilesWrapperContainer" });
            RequestApi.fetch("/admin/census/getFilesIsNotProcess")
                .then((res) => {
                    if (res.success) {
                        censusSetAllDataProcess(res.result);
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                })
                .finally((e) => {
                    SnFreeze.unFreeze("#censusFilesWrapperContainer");
                });
        },
    });
}

async function censusSetAllDataProcess(dataResult) {
    for (let i = 0; i < dataResult.length; i++) {
        const element = dataResult[i];

        let response = await censusSetDataItem(element.census_file_id);
        if(!response.success){
            SnModal.error({
                title: "Algo salió mal",
                content: response.message,
            });
            break;
        }
    }
}

function censusSetDataItem(censusFileId){
    SnFreeze.freeze({ selector: "#censusFilesWrapperContainer" });
    return RequestApi.fetch("/admin/census/setData", {
        method: "POST",
        body: { censusFileId },
    })
        .then((res) => {
            if (res.success) {
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({
                    title: "Algo salió mal",
                    content: res.message,
                });
            }
            censusGetFiles();
            return res;
        })
        .finally((e) => {
            SnFreeze.unFreeze("#censusFilesWrapperContainer");
            scandirWrapper();
        });
}


function censusClear(){
    SnModal.confirm({
        title: "Limpiar archivos?",
        content: "Esta acción limpiara los archivos divididos.",
        onOk() {
            SnFreeze.freeze({ selector: "#censusFilesWrapperContainer" });
            RequestApi.fetch("/admin/census/clear")
                .then((res) => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                    censusGetFiles();
                })
                .finally((e) => {
                    SnFreeze.unFreeze("#censusFilesWrapperContainer");
                    scandirWrapper();
                });
        }
    });
}

function censusSetDataByFile(){
    SnModal.confirm({
        title: "Esta seguro?",
        content: "....",
        onOk() {
            SnFreeze.freeze({ selector: "#censusHistoryWrapperContainer" });
            RequestApi.fetch("/admin/census/setDataByFile")
                .then((res) => {
                    if (res.success) {
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({
                            title: "Algo salió mal",
                            content: res.message,
                        });
                    }
                    censusGetFiles();
                })
                .finally((e) => {
                    SnFreeze.unFreeze("#censusHistoryWrapperContainer");
                });
        }
    });
}

function scandirWrapper(){
    SnFreeze.freeze({ selector: "#scandirWrapper" });
    RequestApi.fetch("/admin/census/scandirWrapper")
        .then((res) => {
            if (res.success) {
                let scandirWrapper = document.getElementById('scandirWrapper');
                if(scandirWrapper){
                    scandirWrapper.innerHTML = res.view;
                }
            } else {
                SnModal.error({
                    title: "Algo salió mal",
                    content: res.message,
                });
            }
        })
        .finally((e) => {
            SnFreeze.unFreeze("#scandirWrapper");
        });
}


function deleteFileWrapper(fileName){
    SnFreeze.freeze({ selector: "#scandirWrapper" });
    return RequestApi.fetch("/admin/census/deleteFileWrapper",{
        method: 'POST',
        body: { fileName }
    })
        .then((res) => {
            if (res.success) {
                SnMessage.success({ content: res.message });
            } else {
                SnModal.error({
                    title: "Algo salió mal",
                    content: res.message,
                });
            }
        })
        .finally((e) => {
            SnFreeze.unFreeze("#scandirWrapper");
            scandirWrapper();
        });
}