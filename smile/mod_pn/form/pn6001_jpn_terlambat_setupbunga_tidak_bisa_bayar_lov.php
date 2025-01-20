<?php
$pagetype = "form";
$gs_pagetitle1 = "PN6001- DAFTAR JPN KURANG BAYAR KARENA PERBEDAAN TINGKAT HASIL PENGEMBANGAN";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_rpt.php';
$mid = $_REQUEST["mid"];
$ls_root_form = "PN6001";
/* ============================================================================
Ket : Form ini digunakan untuk daftar klaim yang akan di koreksi pajaknya
Hist: - 02/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
 
$ld_tglawaldisplay 	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay = !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];
$ls_kode_klaim 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_jenis_klaim			= !isset($_GET['jenis_klaim']) ? $_POST['jenis_klaim'] : $_GET['jenis_klaim'];
$btn_task 					= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
$ls_dataid  				= $_REQUEST["dataid"];
$ls_activetab  			= !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];

?>


<form name="formreg" id="formreg" role="form" method="post">
    <!-- LOCAL CSS -->
    <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
    <link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
    <!-- LOCAL JAVASCRIPTS-->
    <script type="text/javascript" src="../../javascript/calendar.js"></script>
    <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
    <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="../../javascript/validator.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>

    <script type="text/javascript">
        //Create validator object
        var validator = new formValidator();
        var ajax = new sack();
    </script>
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
    <table class="captionentry">
      <tr> 
      	<td align="left"><b><?=$gs_pagetitle1;?></b> </td>						 
      </tr>
    </table>
			 			 
  	<div id="formframe">
      <div id="formKiri" style="width:1000px">
              <div id="header-popup">
                  <h3><?=$gs_pagetitle1;?></h3>
              </div>
          <br />
            <div class="form-row_kiri">
            </div>
            <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="search1" name="search1" style="width:100px;" >
                <option value="KODE_KLAIM">Kode Klaim</option>
                <option value="KPJ">No. Ref</option>
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
                  <th scope="col">Kode Klaim</th>
                  <th scope="col">Tgl Klaim</th>
                  <th scope="col">No. Referensi</th>
                  <th scope="col">Nama</th>								
                  <th scope="col">Tipe</th>
									<th scope="col">Segmen</th>
                  <th scope="col">Ktr</th>									
									<!-- <th scope="col">Nom</th>     -->              
      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>																																																					
        </fieldset>
				
        <br>
        
				<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian</li>	
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>
        </fieldset>
  		</div>
  	</div>
</form>
<script type="text/javascript">
var v_dataid2 = '';
window.dataid = '';
window.selected="";
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
            "url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_jpn_terlambat_setupbunga_tidak_bisa_bayar_query.php",
            "type": "POST",
            "data" : function(e) {
                e.TYPE = "l";
                e.KEYWORD = $('#keyword').val();
                e.SEARCH1 = $('#search1').val();
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
            // { "data": "NOM_TINGKAT_PENGEMBANGAN" },
        ],
        'aoColumnDefs': [
            {"className": "dt-center", "targets": [0,1,2,3,5,6,7]}
        ]

    });//end window.table

    window.table.on('draw.dt',function(){
        $('input[type="checkbox"]').change(function() {
            if(this.checked) {
                window.dataid= $(this).attr('KODE');
                //v_dataid2 = $(this).attr('KODE2');
                window.selected = $(this).closest('tr');
                console.log(v_dataid2);
            }
        });
    });
    //end window.table.on
}//end function load data
$(document).ready(function()
{
    $('#keyword').focus();
    $('input').keyup(function(){
        this.value = this.value.toUpperCase();
    });
    $('#search1').change(function(e){
        $('#keyword').focus();
    });
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $("#btncari").click(function() {
        loadData();
    });

    loadData();

    $('#keyword').focus();

    $('textarea').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
