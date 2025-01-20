<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
?>
<!-- componenet Dialog-->
<script type="text/x-template" id="dialog-template">
  <div id="popup1" :class="classDlg" v-if="value">
    <div class="popup" :style="dlgWidth">
      <div class="header">
        <slot name="header">
          <div class="title">
            {{ title }}
          </div>
        </slot>
      </div>
      <a class="close" @click="$emit('input', false),$emit('onclose')">&times;</a>
      <div class="content" v-if="value">
        <slot></slot>
      </div>
      <slot name="footer" >
        <div class="action" v-if="!noAction">
          <slot name="action">
            <table  width="100%">
              <tr>
                <td><slot name="actionleft"></slot></td>
                <td style="text-align:right"><slot name="actionright"></slot></td>
              </tr>
            </table>
          </slot>
        </div>
      </slot>
    </div>
  </div>
</script>
<script src="<?=$pathcomponents;?>dialog.js"></script>