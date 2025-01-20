<?php
$pagetype = "form";
$gs_pagetitle = "PN5002 - PENGAJUAN DAN PENETAPAN KLAIM";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$ls_root_form = "pn5041.php";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data agenda klaim
Hist: - 02/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<form name="formreg" id="formreg" role="form" method="post">
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
  </script>	
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->

	<?
	//------------- LOCAL CSS, JAVASCRIPTS & ACTION BUTTON -----------------------
  include_once "../ajax/pn5040_css.php";
	
	$ls_rg_kategori				= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
  //kategori -------------------------------------------------------
  if ($ls_rg_kategori=="")
  {
   	 $ls_rg_kategori = "6";
  }	
	// -------- end LOCAL JAVASCRIPTS, CSS & ACTION BUTTON -----------------------
	?>
	
	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) ----------------------------------->
  <?PHP
  if(isset($_REQUEST["task"]))
  {    
	}else
	{
  ?>	
    <table aria-describedby="captionentry" class="captionentry">
		<tr><th></th></tr>
		<tr> 
			<td align="left"><strong> <?=$gs_pagetitle;?></strong> </td>					 
		</tr>
    </table>

    <table aria-describedby="captionfoxrm" class="captionfoxrm">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
      </style>	
	  <tr><th></th></tr>	
      <tr>
    		<td id="header-caption2" colspan="3">
    			<h3><?=$gs_pagetitle;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?																 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
						case '3' : $sel3="checked"; break;
						case '4' : $sel4="checked"; break;
						case '5' : $sel5="checked"; break;
						case '6' : $sel6="checked"; break;
						case '7' : $sel7="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JHT</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JHT/JKM<span > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="3" onclick="this.form.submit()"  <?=$sel3;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JKK<span > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="4" onclick="this.form.submit()"  <?=$sel4;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JKM<span > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="5" onclick="this.form.submit()"  <?=$sel5;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JP<span > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="7" onclick="this.form.submit()"  <?=$sel7;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">JKP<span > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="6" onclick="this.form.submit()"  <?=$sel6;?>>&nbsp;<span style="color:#ffffff; size:2px; family:Verdana;">SEMUA DATA<span >
    			</h3>    
    		</td>
      </tr>
    </table>
					 
  	<div id="formframe">
      <div id="formKiri" style="width:1000px">	 
        <fieldset><legend><strong> DATA KLAIM SIAP UNTUK DITETAPKAN</strong> 
					</legend>
					  																 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:150px;" >
							<option value="KODE_KLAIM">Kode Klaim</option>
							<option value="NAMA_PENGAMBIL_KLAIM">Nama TK/Proyek/Pelaksana</option>              
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">            
            <select id="type2" name="type2" onclick="fl_js_reset_keyword2();">
				<option value="">Keyword Lain</option>
				<option value="">----------------</option>
				<option value="KODE_TIPE_KLAIM">Tipe Klaim</option> 
				<option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
				<option value="KODE_SEGMEN">Segmen Keps.</option>
				<option value="KANAL_PELAYANAN">Jenis Layanan</option>                         
            </select>

            <span id="KODE_TIPE_KLAIM" hidden="">
              <select size="1" id="keyword2a" name="keyword2a" value="" class="select_format" style="width:150px;">
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
              <select size="1" id="keyword2b" name="keyword2b" value="" class="select_format" style="width:150px;">
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
            <span id="KANAL_PELAYANAN" hidden="">
              <select size="1" id="keyword2d" name="keyword2d" value="" class="select_format" style="width:100px;">
                <option value="">-- Pilih --</option>
                <option value="sc_manual">MANUAL</option>
				<option value="sc_antol">ANTOL</option>
				<option value="sc_online">ONLINE</option>
				<option value="sc_onsite_wa">ONSITE WA</option>
				<option value="sc_onsite_web">ONSITE WEB</option>
				<option value="sc_bpjstku">BPJSTKU</option>
				<option value="sc_siap_kerja">SIAP KERJA</option>
				<option value="sc_sipp">SIPP</option>
				<option value="sc_onsite_jmo">ONLINE JMO</option>
				<option value="sc_eklaim_jmo">EKLAIM PMI JMO</option>
              </select>							
            </span>																			                      							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">						
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table aria-describedby="mydata_grid" class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col" style="width:20%;">Action</th>
                  <th scope="col" style="width:10%;">Kode Klaim</th>
                  <th scope="col" style="width:10%;">Tgl Klaim</th>
                  <th scope="col" style="width:10%;">No. Ref</th>
                  <th scope="col">Nama</th>								
                  <th scope="col" style="width:13%;">Tipe</th>
									<th scope="col" style="width:5%;">Segmen</th>
                  <th scope="col" style="width:5%;">Ktr</th>
            
                  <?PHP 
				  if ($ls_rg_kategori=="1") 
				  { 
				 ?>								              
                  <th scope="col" style="width:5%;">Jenis Layanan</th>
				  <!--
				  <th scope="col" style="width:5%;">Nominal</th>
                  <th scope="col" style="width:5%;">Cat.</th>	
				  -->
                  <?PHP 
				  }
				  else
				  {
				  ?>
				  <th scope="col" style="width:5%;">Jenis Layanan</th>
				  <?php
				  }
				  ?>

      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>	

  				<div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;">
            	<span style="background: #FF0; border: 1px solid #CCC;"><em> <strong> Keterangan:</strong> </em> </span>
  					<ul>
						<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
						<li style="margin-left:15px;">Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
					</ul>
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
        $('#KANAL_PELAYANAN').hide();    
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
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_query.php",
    	        	"type": "POST",							
    	        	"data" : function(e) { 
    	        		e.TYPE = $('#type').val();
    	        		e.KEYWORD = $('#keyword').val();
    	        		e.TYPE2 = $('#type2').val();
    	        		e.KEYWORD2A = $('#keyword2a').val();
    	        		e.KEYWORD2B = $('#keyword2b').val();
                  e.KEYWORD2C = $('#keyword2c').val();
                  e.KEYWORD2D = $('#keyword2d').val();
									e.KATEGORI = $("input[name='rg_kategori']:checked").val();
    	        	},complete : function(){
    	        		//$('#fieldset1').css("display","");
    	        		//$('#fieldset2').css("display","none");
    	        		preload(false);
    	        	},error: function (){
    	            	alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
    	            }
    	        },

              <?PHP if ($ls_rg_kategori=="1") { ?>		
                "columns": [
    	        	{ "data": "ACTION" },
    	          { "data": "KODE_KLAIM" },
    	          { "data": "TGL_KLAIM" },
    	          { "data": "KPJ" },
    	          { "data": "NAMA_PENGAMBIL_KLAIM" },
    	          { "data": "KET_TIPE_KLAIM" },
								{ "data": "KODE_SEGMEN" },
    	          { "data": "KODE_KANTOR" },
                { "data": "JHT_KANAL_PELAYANAN" }
                //{ "data": "JHT_NOMINAL_KLAIM" },
    	        //  { "data": "JHT_CATATAN_PETUGAS" }                
    	        ],
    	        'aoColumnDefs': [
				{"className": "dt-center", "targets": [0,1,2,3,5,6,7,8]}
                //{"className": "dt-center", "targets": [0,1,2,3,5,6,7,8,10]},
                //{"className": "dt-right", "targets": [9]}
              ]

              <?PHP }	 else { ?>
                "columns": [
    	        	{ "data": "ACTION" },
    	          { "data": "KODE_KLAIM" },
    	          { "data": "TGL_KLAIM" },
    	          { "data": "KPJ" },
    	          { "data": "NAMA_PENGAMBIL_KLAIM" },
    	          { "data": "KET_TIPE_KLAIM" },
								{ "data": "KODE_SEGMEN" },
    	          { "data": "KODE_KANTOR" },
				  { "data": "KANAL_PELAYANAN" }
    	        ],
    	        'aoColumnDefs': [
                {"className": "dt-center", "targets": [0,1,2,3,5,6,7,8]}
              ] 
              <?PHP } ?>

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
include_once "../../includes/footer_app_nosql.php";
?>

