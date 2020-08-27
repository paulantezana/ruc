document.addEventListener("DOMContentLoaded", () => {
  RequestApi.fetch("/home/getGlobalInfo", {
    method: "GET",
  }).then((res) => {
    if (res.success) {
      if (res.result.user) {
        let userTitleInfo = document.getElementById("userTitleInfo");
        let userDescriptionInfo = document.getElementById(
          "userDescriptionInfo"
        );
        if (userTitleInfo) {
          userTitleInfo.textContent = res.result.user.userName;
        }
        if (userDescriptionInfo) {
          userDescriptionInfo.textContent = res.result.user.email;
        }
      }
    } else {
      SnModal.error({ title: "Algo salió mal", content: res.message });
    }
  });

  SnMenu({
    menuId: "HeaderMenu",
    toggleButtonID: "HeaderMenu-toggle",
    toggleClass: "HeaderMenu-is-show",
    contextId: "AdminLayout",
    parentClose: true,
    menuCloseID: "HeaderMenu-wrapper",
  });

  SnMenu({
    menuId: "AsideMenu",
    toggleButtonID: "AsideMenu-toggle",
    toggleClass: "AsideMenu-is-show",
    contextId: "AdminLayout",
    parentClose: true,
    menuCloseID: "AsideMenu-wrapper",
    iconClassDown: 'fas fa-chevron-down',
    iconClassUp: 'fas fa-chevron-up',
  });
});
