/* /// T&C ARTICLE SELECT /// */
function salesPro_selectArticle(id, title, catid, object) {
    document.getElementById("spr_config_core[tcpage]").value = id;
    document.getElementById("tcpage_name").value = title;
    SqueezeBox.close();
}