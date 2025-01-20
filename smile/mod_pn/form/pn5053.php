<?php
$pagetype = "form";
$gs_pagetitle = "PN5053 - POTENSI KLAIM LEWAT SLA";
require_once "../../includes/header_app_nosql.php";
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "PU";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data wadah BPU
Hist: - 02/08/2017 : Pembuatan Form (Tim SIJSTK)
-----------------------------------------------------------------------------*/
?>
<form name="formreg" id="formreg" role="form" method="post">
	<!-- LOCAL JAVASCRIPTS------------------------------------------------------->
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
  <link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>
	<script language="javascript">
    function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';
      document.getElementById('keyword2b').value = '';
    }

    function fl_js_nom_rincian()
    {
  		var form = document.formreg;
			var	v_kode_penerimaan = form.kode_penerimaan.value;

			if (v_kode_penerimaan !="")
  		{
  		 window.document.getElementById("span_ket_rincian_prg").style.display = 'block';
			 window.document.getElementById("span_nom_jht").style.display = 'block';
			 window.document.getElementById("span_nom_jkk").style.display = 'block';
			 window.document.getElementById("span_nom_jkm").style.display = 'block';
			 window.document.getElementById("span_nom_jpn").style.display = 'block';
			 window.document.getElementById("span_nom_tot").style.display = 'block';
			 window.document.getElementById("span_status_rekon").style.display = 'block';
			}else
  		{
  		 window.document.getElementById("span_ket_rincian_prg").style.display = 'none';
			 window.document.getElementById("span_nom_jht").style.display = 'none';
			 window.document.getElementById("span_nom_jkk").style.display = 'none';
			 window.document.getElementById("span_nom_jkm").style.display = 'none';
			 window.document.getElementById("span_nom_jpn").style.display = 'none';
			 window.document.getElementById("span_nom_tot").style.display = 'none';
			 window.document.getElementById("span_status_rekon").style.display = 'none';
  		}
    }

    function radio_selected(val){
        if(val=='tab_log') {
            document.getElementById('tab_log').checked    = true;
            document.getElementById('tab_setup').checked  = false;
            location.replace('../../mod_iv/form/iv9123.php');
        }else{
            document.getElementById('tab_log').checked    = false;
            document.getElementById('tab_setup').checked  = true;
            location.replace('../../mod_iv/form/iv9124_setup_mail.php');
        }
    }

  </script>
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->

	<!-- LOCAL CSS -------------------------------------------------------------->
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
  <link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
  <style>
  .errorField{
  	border: solid #fe8951 1px !important;
      background: rgba(254, 145, 81, 0.24);
  }
  .dataValid{
    background: #09b546;
    padding: 2px;
    color: #FFF;
    border-radius: 5px;
  }
  input.file{
  	box-shadow:0 0 !important;
  	border:0 !important;
  }
  input[disabled].file{
    background:#FFF !important;
  }
  input.file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#5DBBF6, #2788E0);
    border: 1px solid #5492D6;
    border-radius:2px;
    color:#FFF;
    cursor:pointer;
  }
  input[disabled].file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
    border: 1px solid #ABABAB;
    cursor:no-drop;
  }
  input.file::-webkit-file-upload-button:hover {
    background: linear-gradient(#157fcc, #2a6d9e);
  }
  input[disabled].file::-webkit-file-upload-button:hover {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
  }

  #mydata_grid th {
		font: 12px Verdana, Arial, Helvetica, sans-serif;
    border-right: 1px solid silver;
    border-bottom: 0.5pt solid silver !important;
    border-top: 0.5pt solid silver !important;
    text-align: center !important;
  }
  #mydata_grid td {
		font: 10px Verdana, Arial, Helvetica, sans-serif;
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
    padding-top: 2px;
    padding-bottom: 2px;
  }

  </style>
	<!-- end LOCAL CSS ---------------------------------------------------------->

	<!-- LOCAL GET/POST PARAMETER ----------------------------------------------->
  <?
	$ld_tgl_trans1_display	= !isset($_GET['tgl_trans1_display']) ? $_POST['tgl_trans1_display'] : $_GET['tgl_trans1_display'];
	$ld_tgl_trans2_display	= !isset($_GET['tgl_trans2_display']) ? $_POST['tgl_trans2_display'] : $_GET['tgl_trans2_display'];
	if ($ld_tgl_trans1_display=="")
	{
    $sql = "select to_char(sysdate-1,'dd/mm/yyyy') tgl1,to_char(sysdate,'dd/mm/yyyy') tgl2 from dual";
    $DB->parse($sql);
    $DB->execute();
    $data = $DB->nextrow();
    $ld_tgl_trans1_display = $data["TGL1"];
    $ld_tgl_trans2_display = $data["TGL2"];
	}

  $ls_dataid  					 = $_REQUEST["dataid"];
  $ls_kode_penerimaan		 = !isset($_GET['kode_penerimaan']) ? $_POST['kode_penerimaan'] : $_GET['kode_penerimaan'];
	$ls_sender		 				 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];

  if(isset($_POST["butclose"]))
  {
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&mid=$mid&tgl_trans1_display=$ld_tgl_trans1_display&tgl_trans2_display=$ld_tgl_trans2_display');";
    echo "</script>";
  }

  if($_REQUEST["task"] == "View")
  {

	}
  ?>
	<!-- end LOCAL GET/POST PARAMETER ------------------------------------------->

  <div class="div-action-menu">
  	<div class="menu"><div class="item"><?= $gs_pagetitle;?></div></div>
  </div>

	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) ----------------------------------->
  <?PHP
  if(isset($_REQUEST["task"]))
  {

	}
  else
	{
  ?>
  	<div id="formframe">
      <div id="formKiri" style="width:1150px">
        <fieldset><legend><b>DAFTAR KLAIM LEWAT SLA &nbsp;&nbsp;</b></legend>
					<div class="form-row_kiri">
            <!-- Periode &nbsp;
            <input type="text" id="tgl_trans1_display" name="tgl_trans1_display" value="<?=$ld_tgl_trans1_display;?>" size="12" maxlength="10" tabindex="1" onblur="convert_date(tgl_trans1_display);">
            <input id="btn_tgl_trans1_display" type="image" align="top" onclick="return showCalendar('tgl_trans1_display', 'dd-mm-y');" src="../../images/calendar.gif" />
            &nbsp; s/d &nbsp;
            <input type="text" id="tgl_trans2_display" name="tgl_trans1_display" value="<?=$ld_tgl_trans2_display;?>" size="12" maxlength="10" tabindex="1" onblur="convert_date(tgl_trans2_display);">
            <input id="btn_tgl_trans2_display" type="image" align="top" onclick="return showCalendar('tgl_trans2_display', 'dd-mm-y');" src="../../images/calendar.gif" />
             -->
            <?php
              $kode_kantor = $_SESSION['kdkantorrole'];
              $sql = "select kode_tipe from sijstk.ms_kantor where kode_kantor = '$kode_kantor'";
              // echo $sql;
              $DB->parse($sql);
              $DB->execute();
              $data = $DB->nextrow();
              $kode_tipe_kantor = $data['KODE_TIPE'];
              if ($kode_tipe_kantor=='0'){
                $sql2 = "select kode_kantor, nama_kantor from sijstk.ms_kantor where kode_kantor_induk = '$kode_kantor' and kode_tipe <> 0 and aktif = 'Y' and status_online = 'Y' order by kode_kantor asc";
                $DB->parse($sql2);
                $DB->execute();
                $count = 0;
                echo '<select id="cb_kode_kantor" name="cb_kode_kantor" style="width:250px;">';
                echo '<option value="-" selected>-- Pilih salah satu --</option>';
                while($data_kantor = $DB->nextrow())
                {
                  // if ($count==0){
                  //   echo '<option value="'.$data_kantor['KODE_KANTOR'].'" selected>'.$data_kantor['KODE_KANTOR'].' - '.$data_kantor['NAMA_KANTOR'].'</option>';
                  // }else{
                    echo '<option value="'.$data_kantor['KODE_KANTOR'].'">'.$data_kantor['KODE_KANTOR'].' - '.$data_kantor['NAMA_KANTOR'].'</option>';
                  // }
                  // $count++;
                }
                echo '</select>';
              // }else if ($kode_tipe_kantor=='0'){
              //   $sql2 = "select kode_kantor, nama_kantor from sijstk.ms_kantor where aktif = 'Y' and status_online = 'Y' and kode_tipe not in ('1', '2')";
              //   $DB->parse($sql2);
              //   $DB->execute();
              //   $count = 0;
              //   echo '<select id="cb_kode_kantor" name="cb_kode_kantor" style="width:250px;">';
              //   while($data_kantor = $DB->nextrow())
              //   {
              //     if ($count==0){
              //       echo '<option value="'.$data_kantor['KODE_KANTOR'].'" selected>'.$data_kantor['KODE_KANTOR'].' - '.$data_kantor['NAMA_KANTOR'].'</option>';
              //     }else{
              //       echo '<option value="'.$data_kantor['KODE_KANTOR'].'">'.$data_kantor['KODE_KANTOR'].' - '.$data_kantor['NAMA_KANTOR'].'</option>';
              //     }
              //     $count++;
              //   }
              //   echo '</select>';
              }else{
                echo '<input type="hidden" id="cb_kode_kantor" name="cb_kode_kantor" value="'.$kode_kantor.'">';
              }
            ?>
        	</div>
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:150px;" >
							<option value="NAMA_KANTOR">Nama Kantor</option>
              <option value="KODE_KANTOR">Kode Kantor</option>
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
						<input type="hidden" id="grid_kode_segmen" name="grid_kode_segmen" value="<?=$gs_kode_segmen;?>">
          </div>
          <div class="clear"></div>

          <div id="formsplit">
            <div class="clear"></div>
            <table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col" width="3%" class="align-center">No</th>
								  <th scope="col" width="15%" class="align-center">Kode Kantor</th>
                  <th scope="col" width="55%" class="align-center">Nama Kantor</th>
                  <th scope="col" width="20%" class="align-center">Jumlah Klaim Belum Bayar</th>
                  <th scope="col" width="7%" class="align-center">Action</th>
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

        <br>


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
        kode_kantor = $('#cb_kode_kantor').val();
        if (kode_kantor != '-'){
            window.table = $('#mydata_grid').DataTable({
              "scrollCollapse"	: true,
              "paging"			    : true,
              "pageLength"      : 100,
              'sPaginationType'	: 'full_numbers',
              scrollY				    : "300px",
              scrollX				    : true,
              "processing"		  : true,
              "serverSide"		  : true,
              "search"			    : {
                  "regex" : true
              },
              select			: true,
              "searching"	: false,
              "destroy"		: true,
                  "ajax"	: {
                    "url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5053_query.php",
                    "type": "POST",
                    "data": function(e) {
                      e.TYPE     = $('#type').val();
                      e.KEYWORD  = $('#keyword').val();
                      e.TGL1     = $('#tgl_trans1_display').val();
                      e.TGL2     = $('#tgl_trans2_display').val();
                      e.KD_KTR   = $('#cb_kode_kantor').val();
                    },complete : function(){
                      preload(false);
                    },error: function (){
                      alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
                    }
                  },
                  "columns": [
                    { "data": "NO" },
                    { "data": "KODE_KANTOR" },
                    { "data": "NAMA_KANTOR" },
                    { "data": "JUMLAH_LEWAT_SLA" },
                    { "data": "ACTION" }
                  ],
                  'aoColumnDefs': [
                {"className": "dt-center", "targets": [0,1,3,4]},
                {"className": "dt-left", "targets": [2]}
              ]
              });
        } 		  
      }
    });
	</script>
</form>
<?php
include "../../includes/footer_app_nosql.php";
?>
