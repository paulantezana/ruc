let userRoleState = {
  currentUserRoleId: 0,
};

function userRoleLoadAuthorities(userRoleId, content) {
  userRoleState.currentUserRoleId = userRoleId;
  SnFreeze.freeze({ selector: "#userRoleAuthTable" });
  RequestApi.fetch("/appAuthorization/byUserRoleId", {
    method: "POST",
    body: {
      userRoleId: userRoleId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        let rows = document.querySelectorAll(
          '#userRoleAuthTable [id*="userRoleAuthState"]'
        );
        rows.forEach((item) => {
          item.checked = false;
        });

        [...res.result].forEach((item) => {
          let autState = document.querySelector(
            `#userRoleAuthTable #userRoleAuthState${item.app_authorization_id}`
          );
          if (autState) {
            autState.checked = true;
          }
        });
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      SnFreeze.unFreeze("#userRoleAuthTable");
    });
}

function userRoleSaveAuthorization() {
  if (!(userRoleState.currentUserRoleId >= 1)) {
    SnModal.error({ title: "Algo salió mal", content: "No se indico el rol" });
    return;
  }

  let rows = document.querySelectorAll("#userRoleAuthTable tbody tr");

  let enableAuth = [];
  rows.forEach((item) => {
    let authId = item.dataset.id;
    let authState = item.querySelector(`#userRoleAuthState${authId}`);
    if (authState.checked) {
      enableAuth.push(parseInt(authId));
    }
  });

  SnFreeze.freeze({ selector: "#userRoleAuthTable" });
  RequestApi.fetch("/appAuthorization/save", {
    method: "POST",
    body: {
      authIds: enableAuth || [],
      userRoleId: userRoleState.currentUserRoleId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        SnMessage.success({ content: res.message });
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      SnFreeze.unFreeze("#userRoleAuthTable");
    });
}

document.addEventListener("DOMContentLoaded", () => {
  let userRoleId = document.getElementById("userRoleId");
  if (userRoleId) {
    userRoleId.addEventListener('change',()=>{
      userRoleLoadAuthorities(userRoleId.value, "");
    })
    userRoleLoadAuthorities(userRoleId.value, "");
  }
});
