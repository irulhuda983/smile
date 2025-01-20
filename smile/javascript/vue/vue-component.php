<?php
include_once __DIR__."/../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
echo '<script src="'.$pathcomponents.'utils.js"></script>';
include_once('vue-components/dialog.php');
include_once('vue-components/table.php');
include_once('vue-components/lov.php');
// include_once(dirname(dirname(__FILE__)).'/includes/vue-components/decimal.php');
?>
<script>
Vue.filter('formatDecimal', formatDecimal)
</script>