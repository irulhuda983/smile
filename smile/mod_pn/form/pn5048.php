<?php
$pagetype = "form";
$gs_pagetitle = "PN5029 - DAFTAR PEMBAYARAN KLAIM";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$ls_root_form = "pn5048.php";
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

  $ls_rg_kategori				= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
  //individu atau wadah --------------------------------------------------------
	if ($ls_rg_kategori=="")
  {
   	 $ls_rg_kategori = "1";
  }	
	// ------------ end GET/POST PARAMETER ---------------------------------------
	?>
	
	<!-- LOCAL JAVASCRIPTS------------------------------------------------------->		
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	
	<script language="javascript">
    function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';
      document.getElementById('keyword2b').value = '';		
    }	
		
    function NewWindow4(mypage,myname,w,h,scroll){
      var openwin = window.parent.Ext.create('Ext.window.Window', {
        title: myname,
        collapsible: true,
        animCollapse: true,
        
        maximizable: true,
        width: w,
        height: h,
        minWidth: 450,
        minHeight: 250,
        layout: 'fit',
        html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
      });
      openwin.show();
    }				
  </script>	
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->

	<?
	//------------- LOCAL CSS, JAVASCRIPTS & ACTION BUTTON -----------------------
  include "../ajax/pn5001_css.php";
	// -------- end LOCAL JAVASCRIPTS, CSS & ACTION BUTTON -----------------------
	?>
	
	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) ----------------------------------->
  <?PHP
  if(isset($_REQUEST["task"]))
  {    
	}else
	{
  ?>
    <table class="captionentry">
      <tr> 
      	<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
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
      <div id="formKiri" style="width:1150px">	 
        <fieldset><legend><b>PEMBAYARAN &nbsp;
						<? 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<font  color="#009999"><b>LUMPSUM</b></font>&nbsp; | &nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#009999"><b>BERKALA</b></font>
						</b>
					</legend>
									 	
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
    				Tgl Pembayaran &nbsp;
    				<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="10" onblur="convert_date(tglawaldisplay)" >  
    				<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
    				<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="10" onblur="convert_date(tglakhirdisplay)" >
    				<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;											 
  				</div>
																								 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:100px;" >
							<option value="KODE_KLAIM">Kode Klaim</option>
							<option value="KODE_PEMBAYARAN">Kode Pembayaran</option>
							<option value="NAMA_PENGAMBIL_KLAIM">Nama TK/Proyek/Pelaksana</option>              
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:100px;" placeholder="Keyword">            
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
                $sql = "select kode_sebab_klaim,nama_sebab_klaim from sijstk.pn_kode_sebab_klaim order by kode_sebab_klaim";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                echo "<option ";
                if ($row["KODE_SEBAB_KLAIM"]==$ls_list_kode_sebab_klaim && strlen($ls_list_kode_sebab_klaim)==strlen($row["KODE_SEBAB_KLAIM"])){ echo " selected"; }
                echo " value=\"".$row["KODE_SEBAB_KLAIM"]."\">".$row["NAMA_SEBAB_KLAIM"]."</option>";
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
						<input type="button" name="btncetaklapbyr" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_lapbyrklaim.php?&sender=pn5048.php&sender_mid=<?=$mid;?>','cetak_lap_pembayaran',700,400,'no');" href="javascript:void(0);" class="btn green" id="btndaftar" value="    CETAK PEMBAYARAN KLAIM   ">
						<!--<input type="button" name="btncetak" class="btn green" id="btncetak" value=" CETAK PEMBAYARAN KLAIM ">-->					
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col" style="width:2%;">Action</th>
									<th scope="col" style="width:10%;">Kode Bayar</th>
									<th scope="col" style="width:10%;">Kode Klaim</th>
									<th scope="col" style="width:5%;">Ktr</th>
									<th scope="col" style="width:10%;">Tgl Bayar</th>
                  <th scope="col" style="width:3%;">Tipe</th>
									<th scope="col" style="width:10%;">No. Ref</th>
                  <th scope="col" style="width:30%;">Nama</th>
									<th scope="col" style="width:30%;">Penerima</th>
									<th scope="col" style="width:10%;">Jml Bayar</th>
									<th scope="col" style="width:2%;">Prg</th>
									<th scope="col" style="width:2%;">Segmen</th>
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
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_query.php",
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
								{ "data": "KODE_PEMBAYARAN" },
								{ "data": "KODE_KLAIM" },
    	          { "data": "KODE_KANTOR" },
								{ "data": "TGL_PEMBAYARAN" },
    	          { "data": "KET_TIPE_KLAIM" },
								{ "data": "KPJ" },
    	          { "data": "NAMA_PENGAMBIL_KLAIM" },
    	          { "data": "NAMA_PENERIMA" },
								{ "data": "NOM_PEMBAYARAN" },
								{ "data": "NM_PRG" },
								{ "data": "KODE_SEGMEN" },
    	        ],
    	        'aoColumnDefs': [
						{"className": "dt-center", "targets": [0,1,2,3,4,5,6,10,11]},
						{"className": "dt-right", "targets": [9]}
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

