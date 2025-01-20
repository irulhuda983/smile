
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
    <?php
    if($task_code!='')
    {
    ?>
        <div style="float:left;" id="div_btnsave"><div class="icon">
            <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>
        <div style="float:left;"><div class="icon">
            <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/<?=$php_file_name;?>.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
        </div></div>
    <?php
    } 
    else 
    {
    ?> 
     <div style="float:left;"><div class="icon">
        <a id="btn_view" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_search.png" align="absmiddle" border="0"> View</a>
      </div></div>
      <div style="float:left;"><div class="icon">
        <a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
      </div></div>
      <div style="float:left;"><div class="icon">
        <a id="btn_new" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
      </div></div>      
    <?php
    }
    ?>
    </div>	
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#btn_new').click(function() {                                    
        window.location='<?=$php_file_name;?>.php?task=New&mid=<?=$mid;?>';
    });
    $('#btn_view').click(function() {
        if(window.dataid != ''){
          window.location='<?=$php_file_name;?>.php?task=View&dataid='+window.dataid+'&datastatus='+window.datastatus+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
    });
			
    $('#btn_edit').click(function() {									
        if(window.dataid != ''){
            window.location='<?=$php_file_name;?>.php?task=Edit&dataid='+window.dataid+'&datastatus='+window.datastatus+'&mid=<?=$mid;?>';
        } else {
            alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
    });
});
</script>