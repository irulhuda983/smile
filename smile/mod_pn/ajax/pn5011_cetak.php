<?php
$pagetype = "form";
$gs_pagetitle = "PN5002 - Cetak Dokumen Agenda Klaim";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$ls_activetab  		= !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];
if ($ls_activetab=="")
{
 $ls_activetab = "1";
}
?>
<form name="formreg" id="formreg" role="form" method="post">	
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>	
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
  #mydata th {
    border-right: 1px solid silver;
    border-bottom: 0.5pt solid silver !important;
    border-top: 0.5pt solid silver !important;
    text-align: center !important;
  }
  #listdata td {
    text-align: left !important;
  }
  
  .dataTables_length {
  	margin-bottom: 10px;	
  }
  .dataTables_wrapper{
    position: relative;
    clear: both;
    zoom: 1;
    background: #ebebeb;
    padding-top: 10px;
    padding-bottom: 5px;
    border: 1px solid #dddddd;
  }
  #mylistdata_wrapper thead tr th {
    padding-top: 2px;
    padding-bottom: 2px;
  }
  
  #mydata td {
    font-size: 10px;
    text-align: center;
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
    padding-top: 2px;
    padding-bottom: 2px;
  }
  
  #mydata {
  	text-align: center;
  }
  #simple-table{
  	font-size:11px;
  	font-weight:normal;
  }
  #simple-table>tbody>tr>td{
  	font-size:11px;
  	font-weight:normal;
  	text-align:left;
  }
  </style>
  
  <!-- ACTION BUTTON -->
  <!-- END ACTION BUTTON -->

  <link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
  <!-- <script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script> -->

  <div id="formframe">
  <div id="formKiri">
   	<div id="formframe"> 
      <div id="formKiri">
        

        <fieldset style="width: 564px" id="fs_individu" style="margin-top: 10px"><legend></legend>
            <div class="form-row_kiri">
                <table border="0">
                    <tr>
                        <td>Kode Klaim</td>
                        <td colspan="2">
                        <input type="text" name="tb_kode_klaim" id="tb_kode_klaim" readonly="readonly" style="background-color: #F8F8F8"></td>
                    </tr>
                    <tr>
                        <td>Jenis Laporan</td>
                        <input type="text" name="tb_kd_laporan" id="tb_kode_laporan" hidden="hidden">
                        <input type="text" name="tb_url_laporan" id="tb_url_laporan" hidden="hidden">
                        <input type="text" name="tb_param_laporan" id="tb_param_laporan" hidden="hidden">
                        <td colspan="3" style="width: 150px">
                            <select size="1" id="sl_laporan" name="sl_laporan" value="0" class="select_format" style="width:250px;">
                                <option value="" selected="selected">-- Pilih --</option>
                                <?PHP
                                $KODE_JAMINAN   = $_REQUEST['jenis_klaim'];
                                $KODE_PELAKSANA = $_REQUEST['kategori_pelaksana'];
                                if($KODE_JAMINAN == 'JKK'){
                                  if($KODE_PELAKSANA == 'TG'){
                                    $sql = "select kode_report, nama_report from sijstk.kn_kode_report where kode_report in ('PNR500805','PNR500807')";
                                    $DB->parse($sql);
                                    $DB->execute();
                                    while($row = $DB->nextrow())
                                    {
                                        echo "<option value=\"".$row["KODE_REPORT"]."\">".$row["NAMA_REPORT"]."</option>";
                                    }
                                    // $OPTION   = '<option value="JKKTTPVPK">Dokumen Tanda Terima Promotif/Preventif JKK Bagi Pihak Ketiga</option>';  
                                    // $OPTION  .= '<option value="JKKPTPVPK">Dokumen Penetapan Promotif/Preventif JKK Bagi Pihak Ketiga</option>';
                                  }elseif($KODE_PELAKSANA == 'PR'){
                                    $sql = "select kode_report, nama_report from sijstk.kn_kode_report where kode_report in ('PNR500804','PNR500806')";
                                    $DB->parse($sql);
                                    $DB->execute();
                                    while($row = $DB->nextrow())
                                    {
                                        echo "<option value=\"".$row["KODE_REPORT"]."\">".$row["NAMA_REPORT"]."</option>";
                                    }
                                    // $OPTION   = '<option value="JKKTTPVPRS">Dokumen Tanda Terima Promotif/Preventif JKK Bagi Perusahaan</option>';
                                    // $OPTION  .= '<option value="JKKPTPVPRS">Dokumen Penetapan Promotif/Preventif JKK Bagi Perusahaan</option>';
                                  }                                  
                                }
                                 // echo $OPTION;
                                ?>
                            </select>   
                        </td>
                    </tr>
                    <tr style="height: 30px">
                        <td>
                            <input type="button" class="btn green" onclick="cetak()" id="btn_cetak" name="btn_cetak" value="CETAK LAPORAN">
                        </td>                        
                    </tr>
                </table>
            </div>
            <br>
        </fieldset>
        <br>
        <br>


				<fieldset hidden="hidden" style="background: #FF0; width: 550px"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian</li>	
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
          <li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
        </fieldset>
  		

      </div>
  	</div>
  </div>
  </div>	


<script type="text/javascript">
$(document).ready(function()
{
    window.kode_klaim     = '<?=$_REQUEST["kode_klaim"]?>';
    window.jenis_klaim    = '<?=$_REQUEST["jenis_klaim"]?>';
    window.kode_realisasi = '<?=$_REQUEST["kode_realisasi"]?>';
    window.kode_pelaksana = '<?=$_REQUEST["kategori_pelaksana"]?>';
    document.getElementById('tb_kode_klaim').value = window.kode_klaim;
});


// =====================================FUNCTION=============================================				


function cetak(){
  kode_report = $('#sl_laporan').val();
  // alert(kode_report);
  if (kode_report != 0){
      preload(true);
      $.ajax({
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
        type:'POST',
        data: { TYPE:'CETAK_REPORT',KODE_KLAIM:window.kode_klaim,KODE_REPORT:kode_report,KODE_REALISASI:window.kode_realisasi,JENIS_KLAIM:window.jenis_klaim,KODE_PELAKSANA:window.kode_pelaksana}, 
        success:function(data) {
          // console.log(data);
          preload(false);
          NewWindow(data,'',800,600,1);
        }, error: function(errorThrown) { 
          console.log(errorThrown);
        }  
      });  
  }else{
      alert("Pilih terlebih dahulu laporan yang akan dicetak");
  }
  
}

// =====================================END OF FUNCTION======================================
</script>    

</form>	
<?php
include "../../includes/footer_app_nosql.php";
?>	