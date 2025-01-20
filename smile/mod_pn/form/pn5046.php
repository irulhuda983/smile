<?php
$pagetype = "form";
$gs_pagetitle = "PN5007 - ENTRY PENETAPAN ULANG KLAIM";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_rpt.php';
$mid = $_REQUEST["mid"];
$ls_root_form = "PN5046";
/* ============================================================================
Ket : Form ini digunakan untuk penetapan ulang klaim
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
	$ls_kode_klaim_induk	= !isset($_GET['kode_klaim_induk']) ? $_POST['kode_klaim_induk'] : $_GET['kode_klaim_induk'];
	$btn_task 					= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
	$ls_dataid  				= $_REQUEST["dataid"];
	$ls_activetab  			= !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];  
	$ls_kode_antrian	    = !isset($_GET['kode_antrian']) ? $_POST['kode_antrian'] : $_GET['kode_antrian'];
	// $ls_blob	    = !isset($_GET['blob']) ? $_POST['blob'] : $_GET['blob'];
	// $ls_base64	    = !isset($_GET['base64']) ? $_POST['base64'] : $_GET['base64'];
	$ls_path_url = "";
	if ($ls_activetab=="")
  {
   $ls_activetab = "1";
  }


//   var_dump($_SESSION);
// exit();

  	//cek apakah pathfile ceph sudah ada atau belum?

	$qry=
	"
	select  path_url
	from    pn.pn_antrian_dokumen a
	where   kode_antrian = '$ls_kode_antrian'
	";
	//echo($sql)
	$DB->parse($qry);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_path_url = $row["PATH_URL"];
  
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
  include "../ajax/pn5046_css.php";
	include "../ajax/pn5046_actionbutton.php";
	include "../ajax/pn5046_js.php";
	// -------- end LOCAL JAVASCRIPTS, CSS & ACTION BUTTON -----------------------
	?>
			
	<?PHP
	// -------------- REQUEST TASK (GRID, NEW, EDIT, VIEW) -----------------------
  if(isset($_REQUEST["task"]) && ($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New"))
  {
	 	?>
			<div id="formframe">
				<div id="formKiri" style="width:1100px">	 
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
		  <input type="hidden" id="btn_submit_penetapan" name="btn_submit_penetapan" value="submit_penetapan_tanpa_otentikasi">
          <input type="hidden" id="btn_task" name="btn_task" value="">
          <input type="hidden" name="trigersubmit" value="0">
					<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">					
					<input type="hidden" id="count_empty_required" name="count_empty_required" value="0">
					<input type="hidden" id="errmess_empty_required" name="errmess_empty_required" value="">
					
					<table id="mydata2" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
						<tbody>
						<!--
              <tr>
              	<th colspan="10"><hr/></th>	
              </tr>		 
						-->	
							<tr>
								<th colspan="10" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
									<b>PENETAPAN ULANG KLAIM ATAS PENETAPAN NOMOR : &nbsp;</b>
									<input type="text" id="no_penetapan_induk" name="no_penetapan_induk" value="<?=$ls_no_penetapan_induk;?>" maxlength="30" readonly style="background-color:#ffff99;text-align:center;width:250px;">
                  <input type="hidden" id="kode_klaim_induk" name="kode_klaim_induk" value="<?=$ls_kode_klaim_induk;?>">
									<?
									if ($ls_kode_klaim =="")
									{
									?>
  									<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_lov_kodeklaim.php?p=pn5046.php&a=formreg&b=kode_klaim_induk&c=no_penetapan_induk&d=ket_klaim_atasnama&e=ket_jenis_klaim&f=kode_tipe_klaim&g=<?=$gs_kantor_aktif;?>','',980,500,1)">							
                    <img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
										&nbsp;|&nbsp;
										<a href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Generate Penetapan Ulang..?')) doGenPenetapanUlang();"><img src="../../images/refreshx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Generate Penetapan Ulang</b></a>	
									<?
									}else
									{
									?>
										<!--<img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
										&nbsp;|&nbsp;
										<a><img src="../../images/refreshx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Generate Penetapan Ulang</b></a>	
										-->
									<?
									}
									?>
									
									
									<?
									if ($ls_kode_klaim !="")
									{
           					if ($ls_status_submit_penetapan!="Y")
          					{
            					//cek apakah sudah ada input manfaat/penerima manfaat sudah diisikan
                      if ($ls_kode_tipe_klaim !="JPN01")
          						{
            						$sql = "select count(*) as v_jml from sijstk.pn_klaim_penerima_manfaat a ".
                      			 	 "where kode_klaim = '$ls_kode_klaim' ";
                        $DB->parse($sql);
                        $DB->execute();
                        $row = $DB->nextrow();		
                        $ln_cnt_penerima = $row['V_JML'];
          							
          							if ($ln_cnt_penerima=="0")
          							{
          							 	 $ls_disable_submit = "Y";
          							}else
          							{
              						$sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
                        			 	 "where kode_klaim = '$ls_kode_klaim' and nama_rekening_penerima is null ";
                          $DB->parse($sql);
                          $DB->execute();
                          $row = $DB->nextrow();		
                          $ln_cnt_penerima_null = $row['V_JML2'];	
          								
          								if ($ln_cnt_penerima_null>="1")
          								{
          								 	$ls_disable_submit = "Y"; 
          								}else
          								{
          								 	$ls_disable_submit = "T";	 
          								}
          							}
          						}else
          						{
          						 	$ls_disable_submit = "T";		 
          						}
          						
          						if ($ls_disable_submit=="Y")
          						{						
            						?>
                				&nbsp;|&nbsp;
  											<a href="#" onClick="alert('Data penetapan klaim belum dapat disubmit, harap isikan manfaat yg diterima dan informasi penerima manfaat..!!!');"><img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Submit Data Penetapan</b></a>	
  											<?
          						}else
          						{
								
            						?>
                				&nbsp;|&nbsp;  										
											  <!-- <a href="#" onclick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) doSubmitPenetapanTanpaOtentikasi();"><img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Submit Data Penetapan</b></a> -->
									
								 <a href="#" onClick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')){ if( '<?=$ls_jenis_klaim;?>' == 'JHT' ){doSubmitPenetapanTanpaOtentikasi()}else{f_js_val_nik_antrian('<?=$ls_nomor_identitas_penerima;?>', '<?=$ls_nomor_identitas;?>')}};"><img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Submit Data Penetapan</b></a>

								<?						
          						}
          					}
  									
        						if ($ls_status_submit_penetapan!="Y")
        						{						
          						?>
              				&nbsp;|&nbsp;
  										<a href="#" onClick="alert('Data penetapan klaim belum disubmit, belum dapat dilakukan Cetak Penetapan..!!!');"><img src="../../images/printx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Cetak Penetapan </b></a>	
  									<?
        						}else
        						{
          						?>
              				&nbsp;|&nbsp;
    									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_cetak.php?kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Cetak Penetapan Klaim',700,420,'no')"><img src="../../images/printx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Cetak Penetapan </b></a>
            					<?						
        						}
									}									
          				?>																	
								</th>			 
							</tr>
							
              <tr>
              	<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
              </tr>	
														
              <tr>
								<th colspan="1">Klaim A/N</th>	
								<th colspan="1">:</th>
								<th colspan="8">
  								<input type="text" id="ket_klaim_atasnama" name="ket_klaim_atasnama" value="<?=$ls_ket_klaim_atasnama;?>" style="width:470px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									<input type="text" id="ket_jenis_klaim" name="ket_jenis_klaim" value="<?=$ls_ket_jenis_klaim;?>" style="width:370px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									<input type="hidden" id="flag_kode_manfaat_beasiswa" name="flag_kode_manfaat_beasiswa" value="<?=$ls_flag_kode_manfaat_beasiswa;?>">
									<input type="hidden" id="kode_tipe_klaim" name="kode_tipe_klaim" value="<?=$ls_kode_tipe_klaim;?>">		
									<input type="hidden" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>">	
									<input type="hidden" id="nomor_identitas_penerima" name="nomor_identitas_penerima" value="<?=$ls_nomor_identitas_penerima;?>">				
									<input type="text" id="status_klaim" name="status_klaim" value="<?=$ls_status_klaim;?>" style="width:120px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
								</th>							
              </tr>	
							
              <tr>
								<th colspan="10">&nbsp;</th>					
              </tr>				
																																							
    					<?
    					 	if(!empty($_GET['dataid']))
    						{
    						 	if ($ls_jenis_klaim=="JHT")
    							{
    							 	include "../ajax/pn5046_penetapanulang_jht.php"; 
    							}elseif ($ls_jenis_klaim=="JHM")
    							{
    								include "../ajax/pn5046_penetapanulang_jhm.php";						 
    							}elseif ($ls_jenis_klaim=="JKK")
    							{
    							 	include "../ajax/pn5046_penetapanulang_jkk.php";
    							}elseif ($ls_jenis_klaim=="JKM")
    							{
    							 	include "../ajax/pn5046_penetapanulang_jkm.php";	
    							}																			  													 
    						}
    					?>	
							
						</tbody>		 
					</table>					 
				</div>
			</div>				
		<?													
	}else
	{
	 	?>	
    <table class="captionentry">
      <tr> 
      	<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
      </tr>
    </table>
			 			 
  	<div id="formframe">
      <div id="formKiri" style="width:1000px">	 
        <fieldset><legend><b>DATA PENETAPAN ULANG KLAIM</b></legend>
  				<div class="form-row_kiri">
  					<?PHP	 
          	if ($ld_tglawaldisplay=="" && $ld_tglakhirdisplay=="")//tampilkan dari 1 hari sebelumn ya
          	{
          		$sql2 = "select to_char(sysdate-1,'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
          		$DB->parse($sql2);
          		$DB->execute();
          		$row = $DB->nextrow();
          		$ld_tglawaldisplay  = $row["TGLAWAL"];						
          		$ld_tglakhirdisplay = $row["TGLAKHIR"];						
          	}
  					?>				
    				Tgl Penetapan Ulang &nbsp;
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
                $sql = "select kode_tipe_klaim,nama_tipe_klaim from sijstk.pn_kode_tipe_klaim order by kode_tipe_klaim";
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
                $sql = "select kode_sebab_klaim,nama_sebab_klaim, keyword from sijstk.pn_kode_sebab_klaim order by kode_sebab_klaim";
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
                  <th scope="col">Kode Klaim</th>
                  <th scope="col">Tgl Klaim</th>
                  <th scope="col">No. Referensi</th>
                  <th scope="col">Nama</th>								
                  <th scope="col">Tipe</th>
									<th scope="col">Segmen</th>
                  <th scope="col">Ktr</th>									
									<th scope="col">Status</th>                  
      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>
					
  				<div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;">
            <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
  					<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
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
		let newWindow;
    	let imageBase64;
		let blob;
				window.addEventListener('message', (event) => {
			if(event.data?.msg) {
				imageBase64 = event.data.msg;
				$("#base64_foto_peserta").val(imageBase64);
				console.log(imageBase64);
				document.getElementById('image1').src = imageBase64;

				var block = imageBase64.split(";");
				// Get the content type of the image
				var contentType = block[0].split(":")[1];// In this case "image/gif"
				// get the real base64 content of the file
				var realData = block[1].split(",")[1];

				blob = b64toBlob(realData, contentType);
				console.log(blob);		
		
			}
		})

		function b64toBlob(b64Data, contentType, sliceSize) {
			contentType = contentType || '';
			sliceSize = sliceSize || 512;

			console.log(b64Data);
			var byteCharacters = atob(b64Data);
			var byteArrays = [];

			for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
				var slice = byteCharacters.slice(offset, offset + sliceSize);

				var byteNumbers = new Array(slice.length);
				for (var i = 0; i < slice.length; i++) {
					byteNumbers[i] = slice.charCodeAt(i);
				}

				var byteArray = new Uint8Array(byteNumbers);

				byteArrays.push(byteArray);
			}

			var blob = new Blob(byteArrays, {type: contentType});
			return blob;
		}

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
							fl_js_cek_required_new();
							if ($('#count_empty_required').val() == '0')
							{
								preload(true);
              	$.ajax(
      					{
              		type: 'POST',
              		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_action.php?'+Math.random(),
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
                			window.location='pn5046.php?task=Edit&dataid='+jdata.DATAID+'&kode_klaim='+jdata.DATAID+'&mid=<?=$mid;?>';
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
          	url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_action.php?'+Math.random(),
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
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_action.php?'+Math.random(),
                  data: $('#formreg').serialize(),
                  success: function(data) {
                    preload(false);
                    console.log($('#formreg').serialize());	
                    console.log(data);
                    jdata = JSON.parse(data);
                    if(jdata.ret == '0'){
                    	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5046.php?task=Edit&dataid='+jdata.DATAID+'&kode_klaim='+jdata.DATAID+'&mid=<?=$mid;?>';
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
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_action.php?'+Math.random(),
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
        window.location='pn5046.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
      });
			
      $('#btn_edit').click(function() {													
        if(window.dataid != ''){
        	window.location='pn5046.php?task=Edit&kode_klaim='+window.dataid+'&dataid='+window.dataid+'&mid=<?=$mid;?>';
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
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_action.php?'+Math.random(),
              data: { TYPE:'DEL', DATAID:window.dataid},
              success: function(data) {
              	window.selected.slideUp(function(){						
              		$(this).remove();					
              	});
              	window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
								loadData();
              }
            });          
          }
        }else{
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }
      });
			
      $('#btn_new').click(function() {
      	window.location='pn5046.php?task=New&dataid=&mid=<?=$mid;?>';
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
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_query.php",
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
								{ "data": "STATUS_KLAIM" },
    	        ],
    	        'aoColumnDefs': [
    				{"className": "dt-center", "targets": [0,1,2,3,5,6,7,8]}
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


	function entry_rincian(URL_PATH,KODE_KLAIM,KODE_MANFAAT,mid)
    {


// &root_sender=pn5046.php&form_penetapan=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>

		let token_antrian = document.getElementById("token_antrian").value;
		let email_antrian = document.getElementById("email_antrian").value;
		let no_hp_antrian = document.getElementById("no_hp_antrian").value;
		let base64_foto_peserta =  document.getElementById("base64_foto_peserta").value;
		console.log("masuk entry");
		console.log(URL_PATH);
		console.log(KODE_KLAIM);
		console.log(KODE_MANFAAT);
		console.log(mid);
		console.log(token_antrian);
		console.log(email_antrian);
		console.log(no_hp_antrian);
		console.log(base64_foto_peserta);
		sessionStorage.setItem("token_antrian", token_antrian);
		sessionStorage.setItem("email_antrian", email_antrian);	
		sessionStorage.setItem("no_hp_antrian", no_hp_antrian);	
		sessionStorage.setItem("base64_foto_peserta", base64_foto_peserta);	
		
		//'<%Session["token_antrian"] = "' + token_antrian + '"; %>';
		// document.cookie = "token_antrian=" + token_antrian;				
		

		window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+URL_PATH+'?task=edit&kode_klaim='+KODE_KLAIM+'&kode_manfaat='+KODE_MANFAAT+'&root_sender=pn5046.php&form_penetapan=../form/pn5046.php&sender_activetab=2&sender_mid='+mid+'&email_antrian='+email_antrian+'&no_hp_antrian='+no_hp_antrian+'&token_antrian='+token_antrian,'Rincian Biaya dan Santunan',950,580,'no');

       
    }


	function doSubmitPenetapanTanpaOtentikasisisla() {			
		let token_antrian = document.getElementById("token_antrian").value;
		let email_antrian = document.getElementById("email_antrian").value;
		let no_hp_antrian = document.getElementById("no_hp_antrian").value;
		let base64_foto_peserta =  document.getElementById("base64_foto_peserta").value;
		let path_url_antrian_curr =  document.getElementById("path_url_antrian_curr").value;
		

		console.log(base64_foto_peserta);
		if (token_antrian!="" && no_hp_antrian==""){
			alert("No Handphone harus diisi");
			return;
		}else if(token_antrian!="" && email_antrian==""){
			alert("Email harus diisi");
			return;
		}else if(token_antrian!="" && base64_foto_peserta==""  && path_url_antrian_curr=="" && document.getElementById("base64_foto_peserta").disabled == true){
			alert("Foto harus diisi");
			return;
		}
		preload(true);
		$.ajax({
				type: 'POST',
				url: "../ajax/pn5046_action.php?"+Math.random(),
				data: $('#formreg').serialize(),
				success: function(data){
					
					jdata = JSON.parse(data);	
					if(jdata.ret="1"){
						console.log(jdata);
						if(jdata.kode_antrian != ""){
							f_js_upload_foto_wajah(jdata.kode_antrian);			 
						}								 						 						 
											window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
											window.location='pn5046.php?task=Edit&dataid='+jdata.dataid+'&kode_klaim='+jdata.dataid+'&mid='+jdata.mid;			
                    }else{
						//window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
						//					window.location='pn5046.php?task=Edit&dataid='+jdata.dataid+'&kode_klaim='+jdata.dataid+'&mid='+jdata.mid;			
						alert(jdata.msg);
				//		preload(false);
					};
				preload(false);
				},
				complete: function(){
					preload(false);
				},
				error: function(){
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
					preload(false);
				}
		});
	}

	function toUnicodeVariant(str, variant, flags) {
        const offsets = {
            m: [0x1d670, 0x1d7f6],
            b: [0x1d400, 0x1d7ce],
            i: [0x1d434, 0x00030],
            bi: [0x1d468, 0x00030],
            c: [0x1d49c, 0x00030],
            bc: [0x1d4d0, 0x00030],
            g: [0x1d504, 0x00030],
            d: [0x1d538, 0x1d7d8],
            bg: [0x1d56c, 0x00030],
            s: [0x1d5a0, 0x1d7e2],
            bs: [0x1d5d4, 0x1d7ec],
            is: [0x1d608, 0x00030],
            bis: [0x1d63c, 0x00030],
            o: [0x24B6, 0x2460],
            p: [0x249C, 0x2474],
            w: [0xff21, 0xff10],
            u: [0x2090, 0xff10]
        }

        const variantOffsets = {
            'monospace': 'm',
            'bold': 'b',
            'italic': 'i',
            'bold italic': 'bi',
            'script': 'c',
            'bold script': 'bc',
            'gothic': 'g',
            'gothic bold': 'bg',
            'doublestruck': 'd',
            'sans': 's',
            'bold sans': 'bs',
            'italic sans': 'is',
            'bold italic sans': 'bis',
            'parenthesis': 'p',
            'circled': 'o',
            'fullwidth': 'w'
        }

        // special characters (absolute values)
        var special = {
            m: {
                ' ': 0x2000,
                '-': 0x2013
            },
            i: {
                'h': 0x210e
            },
            g: {
                'C': 0x212d,
                'H': 0x210c,
                'I': 0x2111,
                'R': 0x211c,
                'Z': 0x2128
            },
            o: {
                '0': 0x24EA,
                '1': 0x2460,
                '2': 0x2461,
                '3': 0x2462,
                '4': 0x2463,
                '5': 0x2464,
                '6': 0x2465,
                '7': 0x2466,
                '8': 0x2467,
                '9': 0x2468,
            },
            p: {},
            w: {}
        }
        //support for parenthesized latin letters small cases 
        for (var i = 97; i <= 122; i++) {
            special.p[String.fromCharCode(i)] = 0x249C + (i - 97)
        }
        //support for full width latin letters small cases 
        for (var i = 97; i <= 122; i++) {
            special.w[String.fromCharCode(i)] = 0xff41 + (i - 97)
        }

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';

        var getType = function (variant) {
            if (variantOffsets[variant]) return variantOffsets[variant]
            if (offsets[variant]) return variant;
            return 'm'; //monospace as default
        }
        var getFlag = function (flag, flags) {
            if (!flags) return false
            return flags.split(',').indexOf(flag) > -1
        }

        var type = getType(variant);
        var underline = getFlag('underline', flags);
        var strike = getFlag('strike', flags);
        var result = '';

        for (var k of str) {
            let index
            let c = k
            if (special[type] && special[type][c]) c = String.fromCodePoint(special[type][c])
            if (type && (index = chars.indexOf(c)) > -1) {
                result += String.fromCodePoint(index + offsets[type][0])
            } else if (type && (index = numbers.indexOf(c)) > -1) {
                result += String.fromCodePoint(index + offsets[type][1])
            } else {
                result += c
            }
            if (underline) result += '\u0332' // add combining underline
            if (strike) result += '\u0336' // add combining strike
        }
        return result
    }

	function f_js_val_nik_antrian(nomor_identitas_penerima, nomor_identitas){
        let kode_tipe_klaim = $('#kode_tipe_klaim').val();
		
		console.log(kode_tipe_klaim);
        if (nomor_identitas_penerima ==''){
            let nomor_identitas_penerima = $('#nomor_identitas_penerima').val();
        }

		if( nomor_identitas_penerima == ''){
			nomor_identitas_penerima == nomor_identitas;
		}

        let kode_jenis_antrian = "SA01";
        let kode_jenis_antrian_detil = kode_jenis_antrian+""+kode_tipe_klaim;

		

        if(kode_tipe_klaim != ''){
			//console.log("masuk dia");
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                data: {
                    "TYPE" : "getValidNikAntrian",
                    "nomor_identitas" : nomor_identitas_penerima,
                    "kode_jenis_antrian"    : kode_jenis_antrian,
                    "kode_jenis_antrian_detil" : kode_jenis_antrian_detil
                },
                success: function (data) {
                    var jdata = JSON.parse(data);
                    //console.log(data);
                    if (jdata.ret == '-1') {
                        //$('#valid_nik_antrian').val('T');
                        alert ('Peserta sudah pernah dilayani oleh Kantor Cabang ' + toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') + ' pada tanggal '+ toUnicodeVariant(''+jdata.tglLayanan+'', 'bold sans', 'bold') +' dan saat ini berstatus Tunda Layanan, mohon untuk memastikan ke Kantor Cabang '+ toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') +' terkait tindak lanjut layanan dan token terbaru dapat dibatalkan jika proses layanan tetap dilakukan oleh Kantor Cabang '+ toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') +'');
                    } else {
						doSubmitPenetapanTanpaOtentikasisisla();
                    }
                },
                error: function(){
                    
                },
                complete: function(){
                    //preload(false);
                }
            });
        }
    }
	


	function f_js_upload_foto_wajah(kode_antrian){

		let path_ceph_file = $("#path_url_antrian_curr").val();
        let type_file      = $("#type_file_antrian").val();
        let base64_foto_peserta = $("#base64_foto_peserta").val();
        
        if(path_ceph_file != ""){
            base64_foto_peserta = path_ceph_file;
        }

        if (kode_antrian != "" && base64_foto_peserta !="") {
            if(path_ceph_file == ""){
                let blob_file = blob;
                type_file = blob_file.type;
                path_ceph_file = uploadCephFile(blob_file);
            }      
            if (path_ceph_file !=""){
                $.ajax({
                    type: 'POST',
                    url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                    data: {
                        "TYPE" : "updateDokAntrian",
                        "kode_antrian" : kode_antrian,
                        "path_file" : path_ceph_file,
                        "file_type" : type_file
                    },
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        console.log(data);
                        if (jdata.ret == '0') {
                            
                        } else {
                            
                        }
                    },
                    error: function(){
                        
                    },
                    complete: function(){
                        //preload(false);
                    }
                });
            }
        }
    }


	function uploadCephFile(file){
        var resultPath = "";
        let formData = new FormData();
        formData.append('TYPE', 'uploadDokumen');
        formData.append('file', file);
        formData.append('kode', kode_antrian);
		console.log(formData);
        $.ajax({
            url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function (data) {
                console.log(data);
                var jdata = JSON.parse(data);
                if (jdata.ret == '0') {
                    resultPath = jdata.pathCephFile;
                } else {
                    resultPath = "";
                    //alert(jdata.msg);
                }
            },
            complete: function(){
                //preload(false);
            },
            error: function(){
                alert("Terjadi kesalahan, coba beberapa saat lagi!");
                //preload(false);
            }
        });
        return resultPath;
    }

	</script>			
					
</form>	
<?php
include "../../includes/footer_app_nosql.php";
?>
