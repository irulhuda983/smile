<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
?>
<!-- componenet decimal field-->
<script type="text/x-template" id="decimal-template">
  <input 
    v-model="amount" 
    @blur="focus.amount = false" 
    @focus="focus.amount = true" 
    :style="styledec"
    v-bind="$attrs" 
    v-on="{
      ...$listeners,
      input: event => $emit('input', event.target.value)
    }"
    @keypress="onlyForCurrency" 
  />
</script>
<script src="<?=$pathcomponents;?>decimal.js"></script>