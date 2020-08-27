const APP = {
  path: "",
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

  static fetch(path, options = {}, responseType = "json") {
    NProgress.start();
    const newOptions = RequestApi.setHeaders(options);

    return fetch(APP.path + path, newOptions)
      .then((response) => {
        if (responseType === "json") {
          return response.json();
        } else if (responseType === "text") {
          return response.text();
        } else {
          return response;
        }
      })
      .catch((err) => {
        console.warn(err);
        return err;
      })
      .finally((e) => {
        NProgress.done();
      });
  }
}

(() => {
  let SnFreezeGScope = document.createElement("div");
  SnFreezeGScope.classList.add("SnFreeze-wrapper");

  let SnFreeze = {
    unFreeze(selector) {
      let parentSelector = document.querySelector(selector) || document;
      let element = parentSelector.querySelector(".SnFreeze-wrapper");
      if (element) {
        element.classList.add("is-unfreezing");
        setTimeout(() => {
          if (element) {
            element.classList.remove("is-unfreezing");
            if (
              element.parentElement != null ||
              element.parentElement != undefined
            ) {
              element.parentElement.removeChild(element);
            }
          }
        }, 250);
      }
    },
    freeze(options = {}) {
      let parent = document.querySelector(options.selector) || document.body;
      SnFreezeGScope.setAttribute("data-text", options.text || "Cargando...");
      if (document.querySelector(options.selector)) {
        SnFreezeGScope.style.position = "absolute";
        parent.style.position = "relative";
      }
      parent.appendChild(SnFreezeGScope);
    },
  };

  window.SnFreeze = SnFreeze;
})();

const printArea = function (idElem) {
  let dataTable = document.getElementById(idElem);
  if (dataTable) {
    var content = dataTable.outerHTML;
    var mywindow = window.open("", "Print", "height=600,width=800");

    mywindow.document.write("<html><head><title>Print</title>");
    mywindow.document.write("</head><body >");
    mywindow.document.write(content);
    mywindow.document.write("</body></html>");

    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
  }
};
