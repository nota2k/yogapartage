/**
 * Masque le champ titre de l’éditeur lorsque le modèle « page sans titre » est actif.
 */
(function () {
  var lastTemplate = null;

  function templateIsNoTitle(template) {
    if (!template || typeof template !== "string") {
      return false;
    }
    return template.indexOf("page-no-title") !== -1;
  }

  function sync() {
    if (typeof wp === "undefined" || !wp.data || !wp.data.select) {
      return;
    }
    var select = wp.data.select("core/editor");
    if (!select || typeof select.getEditedPostAttribute !== "function") {
      return;
    }
    if (typeof select.getCurrentPostType === "function") {
      if (select.getCurrentPostType() !== "page") {
        document.body.classList.remove("yogapartage-hide-editor-post-title");
        lastTemplate = null;
        return;
      }
    }
    var tmpl = select.getEditedPostAttribute("template") || "";
    if (tmpl === lastTemplate) {
      return;
    }
    lastTemplate = tmpl;
    if (templateIsNoTitle(tmpl)) {
      document.body.classList.add("yogapartage-hide-editor-post-title");
    } else {
      document.body.classList.remove("yogapartage-hide-editor-post-title");
    }
  }

  function init() {
    sync();
    if (wp.data && typeof wp.data.subscribe === "function") {
      wp.data.subscribe(sync);
    }
  }

  if (typeof wp !== "undefined" && wp.domReady) {
    wp.domReady(init);
  } else {
    document.addEventListener("DOMContentLoaded", init);
  }
})();
