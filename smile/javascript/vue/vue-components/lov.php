<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
include_once(dirname(__FILE__).'/dialog.php');
include_once(dirname(__FILE__).'/table.php');
?>

<script src="<?=$pathcomponents;?>lov.js"></script>
<!-- componenet lov-->
<script type="text/x-template" id="lov-template">
  <span>
    <i class="fa fa-question-circle fa-2x" style="color: orange;cursor:pointer;" @click="openLov" v-if="!iconEdit"></i>
    <i class="fa fa-pencil" style="color: orange;cursor:pointer;" @click="openLov" v-else></i> 
    <v-dialog v-model="dlg" :title="dlgTitle"  no-action :width="width" >
      <v-table 
        :value="tableData" 
        :headers="headers"
        :options.sync="options"
        :item-total="totalData"
        style="margin-right: 3px" 
        hide-footer 
        click-on-row 
        @onclickrow="$emit('onclickrow',$event),dlg=false"
        :dense-paging="densePaging"
      > 
        <template #filterright> 
          <input type="text" placeholder="pencarian..." v-model="searchTxtTmp"/>
          <button class="btn-info" @click="cariData">
            <i class="fa fa-search"></i>
            Tampilkan
          </button>
        </template>
      </v-table>
    </v-dialog>
  </span>
</script>