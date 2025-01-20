<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
    <?php
    if($task_code!='')
    {
    ?>
      <?php
      if($task_code == "Edit" || $task_code == "New" || $task_code == "Daftar" )
      {
      ?>
        <div style="float:left;"><div class="icon">
          <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>
      <?php
      }; 
      ?>
      <div style="float:left;"><div class="icon">
        <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5031.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
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
      <!--
      <div style="float:left;"><div class="icon">
        <a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
      </div></div>
      -->
      <div style="float:left;"><div class="icon">
        <a id="btn_new" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
      </div></div>
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
        window.location='pn1027.php?task=View&kode_faskes='+window.dataid+'&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
    });
			
    $('#btn_edit').click(function() {									
        if(window.dataid != ''){
            window.location='pn1027.php?task=Edit&kode_faskes='+window.dataid+'&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
            alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
    });        
    $('#btn_new').click(function() {
        window.location='pn1027.php?task=New&dataid='+window.dataid+'&mid=<?=$mid;?>';
    });
});
</script>