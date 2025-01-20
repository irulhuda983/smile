<?php
$pagetype = "form";
$gs_pagetitle = "PN5058 - ENTRY AGENDA CETAK PERNYATAAN";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_rpt.php';
$mid = $_REQUEST["mid"];
$ls_root_form = "PN5058";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data agenda klaim
Hist: - 02/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<form name="formreg" id="formreg" role="form" method="post">
	<?
	// ------------ GET/POST PARAMETER -------------------------------------------
	$ld_tglawaldisplay 	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
  $ld_tglakhirdisplay = !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];
  $ls_kode_agenda_pernyataan 			= !isset($_GET['kode_agenda_pernyataan']) ? $_POST['kode_agenda_pernyataan'] : $_GET['kode_agenda_pernyataan'];
	$ls_jenis_klaim			= !isset($_GET['jenis_klaim']) ? $_POST['jenis_klaim'] : $_GET['jenis_klaim'];
	$btn_task 					= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
	$ls_dataid  				= $_REQUEST["dataid"];
	$ls_activetab  			= !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];  
	if ($ls_activetab=="")
  {
   $ls_activetab = "1";
  }
  
	if(isset($_POST["butentry"]))
  {
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?task=New&mid=$mid');";
    echo "</script>";		
  }
	
	// ------------ end GET/POST PARAMETER ---------------------------------------
	?>
	
	<?
	//------------- LOCAL CSS, JAVASCRIPTS & ACTION BUTTON -----------------------
  include "../ajax/pn5058_css.php";
	include "../ajax/pn5058_actionbutton.php";
	include "../ajax/pn5058_js.php";
	// -------- end LOCAL JAVASCRIPTS, CSS & ACTION BUTTON -----------------------
	?>
	
	<?PHP
	// -------------- REQUEST TASK (GRID, NEW, EDIT, VIEW) -----------------------
  if(isset($_REQUEST["task"]) && ($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New"))
  {
	 	?>
			<div id="formframe">
				<div id="formKiri" style="width:1150px">	 
          <input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
					<input type="hidden" name="act" value="<?=$_REQUEST["act"];?>">
          <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
          <input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	 
          <div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
          <input type="hidden" id="st_errval1" name="st_errval1">					
          <span id="dispError2" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval2" name="st_errval2">
          <span id="dispError3" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval3" name="st_errval3">
          <span id="dispError4" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval4" name="st_errval4">
          <span id="dispError5" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval5" name="st_errval5">
					<span id="dispError6" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval6" name="st_errval6">
					<span id="dispError7" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval7" name="st_errval7">
          <input type="hidden" id="btn_task" name="btn_task" value="">
          <input type="hidden" name="trigersubmit" value="0">
					<input type="hidden" id="kode_pelaporan" name="kode_pelaporan" value="<?=$ls_kode_pelaporan;?>">
					<input type="hidden" id="count_empty_required" name="count_empty_required" value="0">
					<input type="hidden" id="errmess_empty_required" name="errmess_empty_required" value="">
					
					<?
					if ($_REQUEST["task"] == "New")
					{
          	//------------- informasi klaim ------------------------------------
            include "../ajax/pn5058_tabinfoklaim.php";
          	// -------- end informasi klaim ------------------------------------				 	  	 
					}else
					{
					 	if(!empty($_GET['dataid']))
						{
						 	include "../ajax/pn5058_agenda_pernyataan.php"; 																	  													 
						}
					}
					?>
					
				</div>
			</div>				
		<?													
	}else
	{
	 	?>	
    <table class="captionentry">
      <tr> 
      	<td align="left"><b><?=$gs_pagetitle;?></b></td>						 
      </tr>
    </table>
			 			 
  	<div id="formframe">
		<div id="formKiri" style="width:1000px">	 
        <fieldset><legend><b>DATA AGENDA CETAK PERNYATAAN </b></legend>				
  				<div class="form-row_kiri">
  					<?PHP	 
          	if ($ld_tglawaldisplay=="" && $ld_tglakhirdisplay=="")//tampilkan dari 1 hari sebelumnya
          	{
          		$sql2 = "select to_char(sysdate-1,'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
          		$DB->parse($sql2);
          		$DB->execute();
          		$row = $DB->nextrow();
          		$ld_tglawaldisplay  = $row["TGLAWAL"];						
          		$ld_tglakhirdisplay = $row["TGLAKHIR"];						
          	}
  					?>				
    				Tgl Agenda Cetak Pernyataan&nbsp;
    				<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="9" onblur="convert_date(tglawaldisplay)" >  
    				<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
    				<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="9" onblur="convert_date(tglakhirdisplay)" >
    				<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;											 
  				</div>														 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:100px;" >
							<option value="KODE_AGENDA_PERNYATAAN">Kode Cetak Pernyataan</option>
							<option value="KPJ">No. Ref</option> 
							<option value="NAMA_TK">Nama</option>                           
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:130px;" placeholder="Keyword">            
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">						
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col">Action</th>
                  <th scope="col">Kode Cetak</th>
                  <th scope="col">No Identitas</th>
                  <th scope="col">No. Referensi</th>
                  <th scope="col">Nama TK</th>
				  				<th scope="col">Segmen</th>
                  <th scope="col">Ktr</th>									
					<th scope="col">Status Submit</th>  
					<th scope="col">Status Cetak</th> 
					<th scope="col">Tgl Cetak</th>  
					<th scope="col">Petugas Cetak</th>   		
					<th scope="col">Surat Pernyataan</th>  		  
      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>
					
  				<div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;">
            <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
  					<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <font color="#ff0000"> TAMPILKAN DATA</font> untuk memulai pencarian data</li>	
            <li style="margin-left:15px;">Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
          </div>																																																											
        </fieldset>
  		</div>
  	</div>	
  	<?PHP		
	}
	// ---------- end REQUEST TASK (GRID, NEW, EDIT, VIEW) -----------------------
  ?>

	<script type="text/javascript">
		$(document).ready(function()
		{
      $('#keyword').focus();            
			$('input').keyup(function(){
       	this.value = this.value.toUpperCase();
      });      
			$('#type').change(function(e){
       	$('#keyword').focus();
      });     
			
			var v_dataid2 = '';

      <?PHP
			//------------------- TASK -----------------------------------------------
      if(isset($_REQUEST["task"]))
  		{
			 	?>													
        window.dataid = '<?=$_REQUEST["dataid"];?>';
        v_dataid2 		= '<?=$_REQUEST["dataid2"];?>';
				
				<?PHP
  			//NEW ------------------------------------------------------------------
        if($_REQUEST["task"] == "New")
  			{
         	?>
          $('#btn_save').click(function() 
  				{
							fl_js_cek_required_new();
							if ($('#count_empty_required').val() == '0')
							{
								preload(true);
              	$.ajax(
      					{
              		type: 'POST',
              		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
              		data: $('#formreg').serialize(),
              		success: function(data)
      						{
                		preload(false);
                		console.log($('#formreg').serialize());	
                		console.log(data);
                		jdata = JSON.parse(data);									
                		if(jdata.ret == '0')
        						{						 		 						 						 
  									  window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
                			window.location='pn5058.php?task=Edit&dataid='+jdata.DATAID+'&kode_agenda_pernyataan='+jdata.DATAID+'&root_form=pn5058&mid=<?=$mid;?>';
                		}else 
        						{
                		 	alert(jdata.msg);
                		}
              		}
              	});							
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}															        
          });		
        	<?PHP
        };				
  			//end NEW ------------------------------------------------
  			?>	
				
        <?PHP
  			//EDIT ---------------------------------------------------
        if($_REQUEST["task"] == "Edit")
  			{
        ?>
          setTimeout( function(){
          	preload(true);
          }, 100); 				
          $.ajax({
          	type: 'POST',
          	url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
          	data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:v_dataid2},
          	success: function(data) {
          		setTimeout( function() {
          			preload(false);
          		}, 100); 
          		console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+v_dataid2+"}");	
          		console.log(data);        		
          		jdata = JSON.parse(data);
              if(jdata.ret == '0')
  						{
								$('#DATAID').val(jdata.data[0].KODE_AGENDA_PERNYATAAN);													
              }
          	}
          });
          $('#btn_simpan').click(function() 
  				{
							fl_js_cek_required_edit();
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "simpan" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php?task=Edit&dataid='+jdata.DATAID+'&kode_agenda_pernyataan='+jdata.DATAID+'&mid=<?=$mid;?>';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}																												        
          });
					$('#btn_batal').click(function() 
  				{
						var r = confirm("Apakah anda yakin ?");
						if (r == true) 
						{
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "batal" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}
						}else{
							alert('Gagal Delete');
						}																												        
          }); 
					$('#btn_ubah').click(function() 
  				{
							fl_js_cek_required_edit();
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "ubah" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php?task=Edit&dataid='+jdata.DATAID+'&kode_agenda_pernyataan='+jdata.DATAID+'&mid=<?=$mid;?>';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}																												        
          });         				
        	<?PHP
        };
  			//end EDIT ---------------------------------------------------
        ?>

  			<?PHP
        //------------------- VIEW -----------------------------------		
  			if($_REQUEST["task"] == "View")
  			{
        ?>
          setTimeout( function(){
          	preload(true);
          }, 100); 				
          $.ajax({
          	type: 'POST',
          	url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
          	data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:v_dataid2},
          	success: function(data) {
          		setTimeout( function() {
          			preload(false);
          		}, 100); 
          		console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+v_dataid2+"}");	
          		console.log(data);        		
          		jdata = JSON.parse(data);
              if(jdata.ret == '0')
  						{
								$('#DATAID').val(jdata.data[0].KODE_AGENDA_PERNYATAAN);													
              }
          	}
          });
          $('#btn_simpan').click(function() 
  				{
							fl_js_cek_required_edit();
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "simpan" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php?task=Edit&dataid='+jdata.DATAID+'&kode_agenda_pernyataan='+jdata.DATAID+'&mid=<?=$mid;?>';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}																												        
          });
					$('#btn_batal').click(function() 
  				{
						var r = confirm("Apakah anda yakin ?");
						if (r == true) 
						{
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "batal" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}
						}else{
							alert('Gagal Delete');
						}																												        
          }); 
					$('#btn_ubah').click(function() 
  				{
							fl_js_cek_required_edit();
							if ($('#count_empty_required').val() == '0')
							{
								var data = $('#formreg').serializeArray();
								data.push({ name: "subact", value: "ubah" });
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
                  data: data,
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5058.php?task=Edit&dataid='+jdata.DATAID+'&kode_agenda_pernyataan='+jdata.DATAID+'&mid=<?=$mid;?>';
                    } else {
                    	alert(jdata.msg);
                    }
                	}
            		});						
							}else
							{
							 	var v_err_mess;
								v_err_mess = $('#errmess_empty_required').val();
								alert(v_err_mess);
							}																												        
          });
          <?PHP
        };
        ?>													
			<?PHP						
      };
			//------------------- end TASK -------------------------------------------
			?>						

      window.dataid = '';
      $('#keyword').focus();      
      
			$('input').keyup(function() {
      	this.value = this.value.toUpperCase();
      });			
      
			$('textarea').keyup(function() {
      	this.value = this.value.toUpperCase();
      });
			      
      $('#type').change(function(e) {
      	$('#keyword').focus();
      });
      
			$("#menutable").hide();
      
      $('#approve').attr('disabled','disabled');
      $(".menutable").html($('#menutable').html());
      $(".menutable").hide();
									
  		loadData();
			
      $('#btn_view').click(function() {
        if(window.dataid != ''){
        window.location='pn5058.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
      });
			
      $('#btn_edit').click(function() {													
        if(window.dataid != ''){
        	window.location='pn5058.php?task=Edit&act=Edit&subact=Edit&kode_agenda_pernyataan='+window.dataid+'&dataid='+window.dataid+'&root_form=pn5058&mid=<?=$mid;?>';
        } else {
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
      });
			$('#btn_simpan').click(function() {													
        if(window.dataid != ''){
        	window.location='pn5058.php?task=Edit&act=Simpan&kode_agenda_pernyataan='+window.dataid+'&dataid='+window.dataid+'&root_form=pn5058&mid=<?=$mid;?>';
        } else {
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
      });

			// $('#btn_cetak').click(function() {													
      //   if(window.dataid != ''){
      //   	window.location='pn5058.php?task=Edit&act=Simpan&kode_agenda_pernyataan='+window.dataid+'&dataid='+window.dataid+'&root_form=pn5058&mid=<?=$mid;?>';
      //   } else {
      //   	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
      //   } 								
      // });
			
      $('#btn_batal').click(function() {
        if(window.dataid != ''){
          var r = confirm("Apakah anda yakin ?");
          if (r == true) 
					{
            $.ajax(
						{
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_action.php?'+Math.random(),
              data: { TYPE:'BATAL', DATAID:window.dataid},
              success: function(data) {
              	window.selected.slideUp(function(){						
              		$(this).remove();					
              	});
            		jdata = JSON.parse(data);
								if(jdata.ret == '0')
    						{						 		 						 						 
							  	window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
            		}else 
    						{
            		 	alert(jdata.msg);
            		}								
								loadData();
              }
            });          
          }
        }else{
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }
      });
			
      $('#btn_new').click(function() {
      	window.location='pn5058.php?task=New&dataid=&mid=<?=$mid;?>';
      });
      
      $("#btncari").click(function() {
  			loadData();
    	});
		
  		function loadData()
  		{
  			 window.table = $('#mydata_grid').DataTable({
    			"scrollCollapse"	: true,
    			"paging"			: true,
    			'sPaginationType'	: 'full_numbers',
    			scrollY				: "300px",
    	    scrollX				: true,
    	  	"processing"		: true,
    			"serverSide"		: true,
    			"search"			: {
    			    "regex": true
    			},
    			select			: true,
    			"searching"	: false,
    			"destroy"		: true,
    	        "ajax"				: {
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_query.php",
    	        	"type": "POST",							
    	        	"data" : function(e) { 
    	        		e.TYPE = $('#type').val();
    	        		e.KEYWORD = $('#keyword').val();
    	        		e.TGLAWALDISPLAY = $('#tglawaldisplay').val();
    	        		e.TGLAKHIRDISPLAY = $('#tglakhirdisplay').val();
    	        	},complete : function(){
    	        		preload(false);
    	        	},error: function (){
    	            	alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
    	            }
    	        },
    	        "columns": [
    	        	{ "data": "ACTION" },
    	          { "data": "KODE_AGENDA_PERNYATAAN" },
    	          { "data": "NOMOR_IDENTITAS" },
    	          { "data": "KPJ" },
    	          { "data": "NAMA_TK" },
    	          { "data": "KODE_SEGMEN" },
								{ "data": "KODE_KANTOR" },
    	          { "data": "STATUS_SUBMIT_PERNYATAAN" },
								{ "data": "STATUS_CETAK_PERNYATAAN" },
								{ "data": "TGL_CETAK_PERNYATAAN" },
                { "data": "PETUGAS_CETAK_PERNYATAAN" },
                { "data": "SURAT_PERNYATAAN" },
    	        ],
    	        'aoColumnDefs': [
    				{"className": "dt-center", "targets": [0,1,2,3,5,6,7,8,9,10,11]}
    			]
					  	        
    	    });//end window.table

    			window.table.on('draw.dt',function(){
            $('input[type="checkbox"]').change(function() {
      				if(this.checked) {
                window.dataid= $(this).attr('KODE');
                v_dataid2 = $(this).attr('KODE2');
                window.selected = $(this).closest('tr');
                console.log(v_dataid2);
              }
        		});			
    			});
					//end window.table.on						  		
  		}//end function load data
						
    });	
		<!--end $(document).ready(function() ------------------------------------ ->
	</script>			
</form>	
<?php
include "../../includes/footer_app_nosql.php";
?>
