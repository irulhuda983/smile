<?php
$pagetype = "form";
$gs_pagetitle = "PN5037 - ENTRY AGENDA KELAYAKAN KLAIM JHT 30%";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_rpt.php';
$mid = $_REQUEST["mid"];
$ls_root_form = "PN5037";
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
  $ls_kode_klaim 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
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
  include "../ajax/pn5037_css.php";
	include "../ajax/pn5037_actionbutton.php";
	include "../ajax/pn5037_js.php";
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
						//------------- informasi antrian ------------------------------------
						include "../ajax/pn5037_tabinfoantrian.php";
						// -------- end informasi antrian ------------------------------------
						//------------- informasi klaim ------------------------------------
						include "../ajax/pn5037_tabinfoklaim.php";
						// -------- end informasi klaim ------------------------------------				 	  	 
					}else
					{
					 	if(!empty($_GET['dataid']))
						{
						 	include "../ajax/pn5037_agenda_jht.php"; 																	  													 
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
        <fieldset><legend><b>DATA AGENDA KELAYAKAN KLAIM </b></legend>				
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
    				Tgl Agenda Kelayakan&nbsp;
    				<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="9" onblur="convert_date(tglawaldisplay)" >  
    				<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
    				<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="9" onblur="convert_date(tglakhirdisplay)" >
    				<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;											 
  				</div>														 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:100px;" >
							<option value="KODE_KLAIM">Kode Klaim</option>
							<option value="KPJ">No. Ref</option> 
							<option value="NAMA_PENGAMBIL_KLAIM">Nama</option>                           
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:130px;" placeholder="Keyword">            
            <select id="type2" name="type2" onclick="fl_js_reset_keyword2();">
              <option value="">Keyword Lain</option>
              <option value="">----------------</option>
              <option value="KODE_TIPE_KLAIM">Tipe Klaim</option> 
							<option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
							<option value="KODE_SEGMEN">Segmen Keps.</option>                       
            </select>

            <span id="KODE_TIPE_KLAIM" hidden="">
              <select size="1" id="keyword2a" name="keyword2a" value="" class="select_format" style="width:100px;">
                <option value="">-- Pilih --</option>
                <? 
                $sql = "select kode_tipe_klaim,nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where nvl(status_nonaktif,'T')='T' order by no_urut";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                echo "<option ";
                if ($row["KODE_TIPE_KLAIM"]==$ls_list_kode_tipe_klaim && strlen($ls_list_kode_tipe_klaim)==strlen($row["KODE_TIPE_KLAIM"])){ echo " selected"; }
                echo " value=\"".$row["KODE_TIPE_KLAIM"]."\">".$row["NAMA_TIPE_KLAIM"]."</option>";
                }
                ?>
              </select>							
            </span>	            
            <span id="KODE_SEBAB_KLAIM" hidden="">
              <select size="1" id="keyword2b" name="keyword2b" value="" class="select_format" style="width:100px;">
                <option value="">-- Pilih --</option>
                <? 
                $sql = "select kode_sebab_klaim,nama_sebab_klaim, keyword from sijstk.pn_kode_sebab_klaim where nvl(status_nonaktif,'T')='T' order by kode_tipe_klaim,no_urut";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                echo "<option ";
                if ($row["KODE_SEBAB_KLAIM"]==$ls_list_kode_sebab_klaim && strlen($ls_list_kode_sebab_klaim)==strlen($row["KODE_SEBAB_KLAIM"])){ echo " selected"; }
                echo " value=\"".$row["KODE_SEBAB_KLAIM"]."\">".$row["NAMA_SEBAB_KLAIM"]." (".$row["KEYWORD"].")</option>";
                }
                ?>
              </select>							
            </span>
            <span id="KODE_SEGMEN" hidden="">
              <select size="1" id="keyword2c" name="keyword2c" value="" class="select_format" style="width:100px;">
                <option value="">-- Pilih --</option>
                <? 
                $sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen order by kode_segmen";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                echo "<option ";
                if ($row["KODE_SEGMEN"]==$ls_list_kode_segmen && strlen($ls_list_kode_segmen)==strlen($row["KODE_SEGMEN"])){ echo " selected"; }
                echo " value=\"".$row["KODE_SEGMEN"]."\">".$row["NAMA_SEGMEN"]."</option>";
                }
                ?>
              </select>							
            </span>												                      							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">						
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col">Action</th>
                  <th scope="col">Kode Kelayakan</th>
                  <th scope="col">Tgl Kelayakan</th>
                  <th scope="col">No. Referensi</th>
                  <th scope="col">Nama</th>								
                  <th scope="col">Tipe Kelayakan</th>
				  <th scope="col">Segmen</th>
                  <th scope="col">Ktr</th>									
				  <th scope="col">Status Kelayakan</th>  
					<th scope="col">Surat Keterangan</th>  				  
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
			$('#type2').change(function(e){
       	$('#KODE_TIPE_KLAIM').hide();
				$('#KODE_SEBAB_KLAIM').hide(); 
				$('#KODE_SEGMEN').hide();   
      	$('#'+$('#type2').val()).show();
      	$('#keyword2').val('');
      	$('#keyword2').focus();
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
				// f_js_val_nik_antrian();
				fl_js_cek_required_new();
				if ($('#count_empty_required').val() == '0')
				{
					preload(true);
					$.ajax(
							{
						type: 'POST',
						url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_action.php?'+Math.random(),
						data: $('#formreg').serialize(),
						success: function(data)
								{
								preload(false);
								console.log($('#formreg').serialize());	
								console.log(data);
								jdata = JSON.parse(data);									
								if(jdata.ret == '0')
										{				
											console.log("sukses1");
											f_js_upload_foto_wajah(jdata.kodeAntrian);		 		 						 						 
											window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
									window.location='pn5037.php?task=Edit&dataid='+jdata.DATAID+'&kode_klaim='+jdata.DATAID+'&root_form=pn5037&mid=<?=$mid;?>';
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
          	url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_action.php?'+Math.random(),
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
								$('#DATAID').val(jdata.data[0].KODE_KLAIM);													
              }
          	}
          });
          $('#btn_save').click(function() 
  				{
							fl_js_cek_required_edit();
							if ($('#count_empty_required').val() == '0')
							{
  							preload(true);
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_action.php?'+Math.random(),
                  data: $('#formreg').serialize(),
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5037.php?task=Edit&dataid='+jdata.DATAID+'&kode_klaim='+jdata.DATAID+'&mid=<?=$mid;?>';
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
          setTimeout( function() {
          	preload(true);
          }, 100);
          $.ajax({
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_action.php?'+Math.random(),
            data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:v_dataid2},
            success: function(data) {
              setTimeout( function() {
              	preload(false);
              }, 100); 
              console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+v_dataid2+"}");	
              console.log(data);
              jdata = JSON.parse(data);
              if(jdata.ret == '0'){
                $('#DATAID').val(jdata.data[0].KODE_KLAIM);
								$('#KODE_KLAIM').val(jdata.data[0].KODE_KLAIM);
              }
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
        window.location='pn5037.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
      });
			
      $('#btn_edit').click(function() {													
        if(window.dataid != ''){
        	window.location='pn5037.php?task=Edit&kode_klaim='+window.dataid+'&dataid='+window.dataid+'&root_form=pn5037&mid=<?=$mid;?>';
        } else {
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
      });
			
      $('#btn_delete').click(function() {
        if(window.dataid != ''){
          var r = confirm("Apakah anda yakin ?");
          if (r == true) 
					{
            $.ajax(
						{
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_action.php?'+Math.random(),
              data: { TYPE:'DEL', DATAID:window.dataid},
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
      	window.location='pn5037.php?task=New&dataid=&mid=<?=$mid;?>';
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
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_query.php",
    	        	"type": "POST",							
    	        	"data" : function(e) { 
    	        		e.TYPE = $('#type').val();
    	        		e.KEYWORD = $('#keyword').val();
    	        		e.TYPE2 = $('#type2').val();
    	        		e.KEYWORD2A = $('#keyword2a').val();
    	        		e.KEYWORD2B = $('#keyword2b').val();
									e.KEYWORD2C = $('#keyword2c').val();
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
    	          { "data": "KODE_KLAIM" },
    	          { "data": "TGL_KLAIM" },
    	          { "data": "KPJ" },
    	          { "data": "NAMA_PENGAMBIL_KLAIM" },
    	          { "data": "KET_TIPE_KLAIM" },
								{ "data": "KODE_SEGMEN" },
    	          { "data": "KODE_KANTOR" },
								{ "data": "KETERANGAN_STATUS_KELAYAKAN" },
								{ "data": "SURAT_KETERANGAN" },
    	        ],
    	        'aoColumnDefs': [
    				{"className": "dt-center", "targets": [0,1,2,3,5,6,7,8,9]}
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
	// function f_js_upload_foto_wajah(kode_antrian){
	// 	console.log("sukses2");
    //     let blob_file = blob;
    //     let base64_foto_peserta = $("#base64_foto_peserta").val();
    //     if (kode_antrian != "" && base64_foto_peserta !="") {
    //         var path_ceph_file = uploadCephFile(blob_file);
    //         console.log(path_ceph_file);
    //         if (path_ceph_file !=""){
    //             $.ajax({
    //                 type: 'POST',
    //                 url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
    //                 data: {
    //                     "TYPE" : "updateDokAntrian",
    //                     "kode_antrian" : kode_antrian,
    //                     "path_file" : path_ceph_file,
    //                     "file_type" : blob_file.type
    //                 },
    //                 success: function (data) {
    //                     var jdata = JSON.parse(data);
    //                     console.log(data);
    //                     if (jdata.ret == '0') {
                            
    //                     } else {
                            
    //                     }
    //                 },
    //                 error: function(){
                        
    //                 },
    //                 complete: function(){
    //                     //preload(false);
    //                 }
    //             });
    //         }
    //     }
    // }
		<!--end $(document).ready(function() ------------------------------------ ->
	</script>			
</form>	
<?php
include "../../includes/footer_app_nosql.php";
?>
