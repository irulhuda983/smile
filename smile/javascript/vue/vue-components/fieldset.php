<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
?>
<!-- componenet Dialog-->
<script type="text/x-template" id="fieldset-template">
  <fieldset style="border-radius: 5px;border-color: #ddd;">
    <legend v-if="title">{{title}}</legend>
    <slot></slot>
  </fieldset>
</script>
<script src="<?=$pathcomponents;?>fieldset.js"></script>