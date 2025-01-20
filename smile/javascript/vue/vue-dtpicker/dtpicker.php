<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-dtpicker/":"http://".$HTTP_HOST."/javascript/vue/vue-dtpicker/";
?>
<script src="<?=$pathcomponents;?>vuejs-datepicker.min.js"></script>
<script>
  Vue.component('vDatepicker', vuejsDatepicker)
</script>