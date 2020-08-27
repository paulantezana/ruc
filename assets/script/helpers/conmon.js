const APP = {
    path: "",
};

const codeMessage = {
    200: "El servidor devolvió con éxito los datos solicitados. ",
    201: "Datos nuevos o modificados son exitosos. ",
    202: "Una solicitud ha ingresado a la cola de fondo (tarea asíncrona). ",
    204: "Eliminar datos con éxito. ",
    400: "La solicitud se envió con un error. El servidor no realizó ninguna operación para crear o modificar datos. ",
    401: "El usuario no tiene permiso (token, nombre de usuario, contraseña es incorrecta). ",
    403: "El usuario está autorizado, pero el acceso está prohibido. ",
    404: "La solicitud se realizó a un registro que no existe y el servidor no funcionó. ",
    406: "El formato de la solicitud no está disponible. ",
    410: "El recurso solicitado se elimina permanentemente y no se obtendrá de nuevo. ",
    422: "Al crear un objeto, se produjo un error de validación. ",
    500: "El servidor tiene un error, por favor revise el servidor. ",
    502: "Error de puerta de enlace. ",
    503: "El servicio no está disponible, el servidor está temporalmente sobrecargado o mantenido. ",
    504: "La puerta de enlace agotó el tiempo. ",
};

class RequestApi {
    static setHeaders(options) {
        if (!(options.body instanceof FormData)) {
            options.headers = {
                Accept: "application/json",
                "Content-Type": "application/json; charset=utf-8",
                ...options.headers,
            };
            options.body = JSON.stringify(options.body);
        } else {
            options.headers = {
                Accept: "application/json",
                ...options.headers,
            };
        }
        return options;
    }

    static checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response;
        }
        const errortext = codeMessage[response.status] || response.statusText;
        SnMessage.error({
            content: `Error de solicitud ${response.status}: ${response.url} ${errortext}`,
        });
        let error = new Error(errortext);
        error.name = response.status;
        error.response = response;
        throw error;
    }

    static fetch(path, options = {}) {
        NProgress.start();
        const newOptions = RequestApi.setHeaders(options);

        return fetch(APP.path + path, newOptions)
            .then(RequestApi.checkStatus)
            .then((response) => {
              return response.json();
            })
            .catch(e => {
                console.warn(e,'FETCH_ERROR');
                return e;
            })
            .finally(e => {
                NProgress.done();
            });
    }
}

const printArea = function (idElem) {
    let dataTable = document.getElementById(idElem);
    if (dataTable) {
        let content = dataTable.outerHTML;
        let mywindow = window.open("", "Print", "height=600,width=800");

        mywindow.document.write("<html><head><title>Print</title>");
        mywindow.document.write("</head><body >");
        mywindow.document.write(content);
        mywindow.document.write("</body></html>");

        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
    }
};

const TableToExcel = (
    tableHtml,
    sheetName = "Sheet 1",
    fileName = "report"
) => {
    const template =
        '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>';
    const base64 = function (s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };
    const format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
        });
    };
    const s2ab = (s) => {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    };

    const ctx = { worksheet: sheetName, table: tableHtml };

    const blob = new Blob([s2ab(atob(base64(format(template, ctx))))], {
        type: "",
    });

    let link = document.createElement("a"); //console.log(nombreArchivo);
    link.download = fileName + ".xls";

    link.href = URL.createObjectURL(blob);
    link.click();
};
