<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-select/":"http://".$HTTP_HOST."/javascript/vue/vue-select/";
?>
<link rel="stylesheet" href="<?=$pathcomponents;?>vue-select.css">
<script src="<?=$pathcomponents;?>vue-select.js"></script>
<script>
  Vue.component('v-select', VueSelect.VueSelect)
</script>