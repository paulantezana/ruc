document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("searchContent").addEventListener("input", (e) => {
        let value = e.target.value;
        if(value.length == 11){
            censusList(1, 10, e.target.value);
        }
      });
    censusList();
});

function censusList(page = 1, limit = 10, search = "") {
    let censusTable = document.getElementById("censusTable");
    if (censusTable) {
        SnFreeze.freeze({ selector: "#censusTable" });
        RequestApi.fetch(`/admin/census/table?limit=${limit}&page=${page}&search=${search}`,{ 
            method: "GET",
        })
            .then((res) => {
                if (res.success) {
                    censusTable.innerHTML = res.view;
                } else {
                    SnModal.error({
                        title: "Algo salió mal",
                        content: res.message,
                    });
                }
            })
            .finally((e) => {
                SnFreeze.unFreeze("#censusTable");
            });
    }
}