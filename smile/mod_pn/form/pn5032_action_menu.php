<?php

$ro_data = ($task_code=='View'?true:false);
$sql = 	"select STATUS_RTW_KLAIM from sijstk.PN_RTW_KLAIM  where kode_rtw_klaim='{$ls_kode_rtw}'";
$DB->parse($sql);// echo $sql;
$DB->execute();
if($row = $DB->nextrow())
{
    if($row['STATUS_RTW_KLAIM']=='SELESAI') $ro_data=true;
}

?>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
    <?php
    if($task_code!='')
    {
    ?>
      <?php
      if(  !$ro_data )
      {
      ?>
        <div style="float:left;" id="div_btnsave"><div class="icon">
          <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>
      <?php
      }; 
      ?>
      <div style="float:left;"><div class="icon">
        <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5032.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
      </div></div>
    <?php
    } 
    else 
    {
    ?>
      <div style="float:left;"><div class="icon">
        <a href="javascript:void(0)" id="btn_view">
        <img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a>
      </div></div>
      <div style="float:left;"><div class="icon">
        <a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
      </div></div>
      <!--
      <div style="float:left;"><div class="icon">
        <a id="btn_new" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
      </div></div>
      -->
    <?php
    }
    ?>
    </div>	
  </div>
</div>
<!--
<table class="captionentry">
    <tr><td align="left"><b><?=$gs_pagetitle;?></b></td></tr>
</table>
-->
<script type="text/javascript">
$(document).ready(function(){
    $('#btn_view').click(function() {
        if(window.dataid != ''){
          window.location='pn5032.php?task=View&dataid='+window.dataid+'&datastatus='+window.datastatus+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
    });
			
    $('#btn_edit').click(function() {									
        if(window.dataid != ''){
            if(window.datastatus!='SR4')
              window.location='pn5032.php?task=Edit&dataid='+window.dataid+'&datastatus='+window.datastatus+'&mid=<?=$mid;?>';
            else
              alert('Agenda dengan status BARU atau DIPROSES yang bisa di edit!');
        } else {
            alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
    });
});
</script>