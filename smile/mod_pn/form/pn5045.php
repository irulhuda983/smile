<?php
$pagetype = "form";
$gs_pagetitle = "PN5006 - KONFIRMASI JP BERKALA";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$ls_root_form = "pn5045.php";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data agenda klaim
Hist: - 02/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<form name="formreg" id="formreg" role="form" method="post">
	<?
  $ls_rg_kategori				= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
  //lumpsum atau berkala -------------------------------------------------------
	if ($ls_rg_kategori=="")
  {
   	 $ls_rg_kategori = "1";
  }
	
	$ls_kode_klaim						= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
	$ln_no_konfirmasi_induk		= !isset($_GET['no_konfirmasi_induk']) ? $_POST['no_konfirmasi_induk'] : $_GET['no_konfirmasi_induk'];
	$ln_no_konfirmasi					= !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
	$ls_btn_task							= $_POST['btn_task'];
	
	//ambil informasi klaim ------------------------------------------------------
  if ($ls_kode_klaim!="")
  {
    $sql = "select ".
            "   a.kode_klaim, a.no_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, a.kode_divisi, a.kode_tk, a.nama_tk, ".
            "   to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, a.no_penetapan,a.petugas_penetapan, ". 
            "   a.nama_tk||' (No.Referensi: '||a.kpj||' | NIK: '||a.nomor_identitas||' | NPP: '||(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||' - '||(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||')' ket_nama_tk	".																	
            "from sijstk.pn_klaim a	".
            "where kode_klaim = '$ls_kode_klaim' ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_no_penetapan							= $row['NO_PENETAPAN'];
    $ls_ket_klaim_atasnama				= $row['KET_NAMA_TK'];
		$ls_kode_kantor								= $row['KODE_KANTOR'];									 
  }			
	?>
		
	<?
	//------------- LOCAL CSS, JAVASCRIPTS & ACTION BUTTON -----------------------
  include "../ajax/pn5040_css.php";
	include "../ajax/pn5045_js.php";
	include "../ajax/pn5045_actionbutton.php";
	// -------- end LOCAL JAVASCRIPTS, CSS & ACTION BUTTON -----------------------
	?>
	
	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) ----------------------------------->
  <?PHP
  if(isset($_REQUEST["task"]) && ($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New"))
  {
	 	?>
      <table class="captionfoxrm">
        <style>
          #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
          #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
        </style>		
        <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
				<tr><td colspan="3"></br></br></td></tr>	
      </table>
  					
  		<div id="formframe">
  			<div id="formKiri" style="width:1200px">
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
          <input type="hidden" id="btn_task" name="btn_task" value="">
          <input type="hidden" name="trigersubmit" value="0">
					<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
					<input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?=$ln_no_konfirmasi_induk;?>">
					<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
					<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$mid;?>">
					<input type="hidden" id="sender_rg_kategori" name="sender_rg_kategori" value="<?=$ls_rg_kategori;?>">
					<input type="hidden" id="count_empty_required" name="count_empty_required" value="0">
					<input type="hidden" id="errmess_empty_required" name="errmess_empty_required" value="">
									
					<table id="mydata2" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
						<tbody>
							<tr>
								<th colspan="10" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
									<b>ATAS PENETAPAN NOMOR : &nbsp;</b>
									<input type="text" id="no_penetapan" name="no_penetapan" value="<?=$ls_no_penetapan;?>" readonly style="background-color:#ffff99;text-align:center;width:180px;">		
									&nbsp;A/N&nbsp;
									<input type="text" id="ket_klaim_atasnama" name="ket_klaim_atasnama" value="<?=$ls_ket_klaim_atasnama;?>" style="width:350px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									&nbsp;
									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5045_view_dokumen.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',980,500,'no')"><img src="../../images/pages.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><b> Dokumen</b></font></a>&nbsp;|&nbsp;
									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5045_view_ahliwaris.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',980,500,'no')"><img src="../../images/user_go.png" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><b> Ahli Waris</b></font></a>&nbsp;|&nbsp;
									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5045_view_pembayaran.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1025,500,'no')"><img src="../../images/money.png" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><b> Daftar Pembayaran</b></font></a>
								</th>	
							</tr>		 
							
              <tr>
              	<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
              </tr>	
							
							<?
							if ($_REQUEST["task"] == "New")
							{
								include "../ajax/pn5045_new.php";
							}elseif ($_REQUEST["task"] == "Edit")
							{
								include "../ajax/pn5045_edit.php";
							}elseif ($_REQUEST["task"] == "View")
							{
								include "../ajax/pn5045_view.php";
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
    <td align="left"><b><?=$gs_pagetitle;?></b></td>					 
    </tr>
    </table>

    <table class="captionfoxrm">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
      </style>		
      <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
    </table>
					 
  	<div id="formframe">
      <div id="formKiri" style="width:1100px">	 
        <fieldset><legend><b>JATUH TEMPO KONFIRMASI BERKALA&nbsp;&nbsp;
						<? 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
						case '3' : $sel3="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<font  color="#009999"><b>KANTOR CABANG</b></font>&nbsp; | &nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#009999"><b>KANTOR CABANG LAIN</b></font> &nbsp; | &nbsp;
						<input type="radio" name="rg_kategori" value="3" onclick="this.form.submit()"  <?=$sel3;?>>&nbsp;<font  color="#009999"><b>SEMUA KANTOR</b></font> &nbsp;&nbsp;
						</b>
					</legend> 																 									 
        
				  <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:150px;" >
							<option value="KPJ">No. Referensi</option>			
							<option value="NAMA_TK">Nama TK</option>
							<option value="NAMA_PENERIMA_BERKALA">Nama Penerima</option> 
							<option value="KODE_KLAIM">Kode Klaim</option>             
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">            
            <select id="type2" name="type2" onclick="fl_js_reset_keyword2();">
              <option value="">Keyword Lain</option>
              <option value="">----------------</option>                      
            </select>                							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">					
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col" style="width:10%;">Action</th>
                  <th scope="col" style="width:10%;">Kode Klaim</th>
									<th scope="col" style="width:10%;">No. Ref</th>
                  <th scope="col">Nama TK</th>
									<th scope="col" style="width:15%;">Konf Terakhir</th>
									<th scope="col" style="width:20%;">Penerima Berkala</th>
									<th scope="col" style="width:15%;">Periode</th>
									<!--<th scope="col" style="width:5%;">Segmen</th>-->
                  <th scope="col" style="width:5%;">Ktr</th>									              
      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>																																																					
        </fieldset>
				
				</br>
				
				<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li style="margin-left:15px;">Pilih Jenis Pencarian</li>	
          <li style="margin-left:15px;">Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian</li>	
          <li style="margin-left:15px;">Klik Tombol <font color="#ff0000"> TAMPILKAN DATA</font> untuk memulai pencarian data. Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>	
					<li style="margin-left:15px;">Daftar Jatuh tempo berkala <font color="#ff0000"> KANTOR CABANG</font> menampilkan data JP Berkala yang penetapan klaimnya dilakukan di kantor cabang login.</li>
					<li style="margin-left:15px;">Daftar Jatuh tempo berkala <font color="#ff0000"> KANTOR CABANG LAIN</font> menampilkan data JP Berkala yang penetapan klaimnya dilakukan di kantor cabang lain.</li>
					
        </fieldset>
  		</div>
  	</div>	
  	<?PHP		
	}
  ?>	
	<!-- end REQUEST TASK (GRID, NEW, EDIT, VIEW) ------------------------------->
	
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
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5045_query.php?mid=<?=$mid;?>",
    	        	"type": "POST",							
    	        	"data" : function(e) { 
    	        		e.TYPE = $('#type').val();
    	        		e.KEYWORD = $('#keyword').val();
    	        		e.TYPE2 = $('#type2').val();
    	        		e.KEYWORD2A = $('#keyword2a').val();
    	        		e.KEYWORD2B = $('#keyword2b').val();
									e.KATEGORI = $("input[name='rg_kategori']:checked").val();
    	        	},complete : function(){
    	        		//$('#fieldset1').css("display","");
    	        		//$('#fieldset2').css("display","none");
    	        		preload(false);
    	        	},error: function (){
    	            	alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
    	            }
    	        },
    	        "columns": [
    	        	{ "data": "ACTION" },
    	          { "data": "KODE_KONFIRMASI" },
    	          { "data": "KPJ" },
    	          { "data": "NAMA_TK" },
    	          { "data": "TGL_KONFIRMASI" },
    	          { "data": "KET_PENERIMA_BERKALA" },
								{ "data": "KET_PERIODE" },
								//{ "data": "KODE_SEGMEN" },
    	          { "data": "KODE_KANTOR" },
    	        ],
    	        'aoColumnDefs': [
    				{"className": "dt-center", "targets": [0,1,2,4,6,7]}
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

