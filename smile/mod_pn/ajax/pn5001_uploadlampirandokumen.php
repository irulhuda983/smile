<?php
require_once "../../includes/header_app_nosql.php";	
include '../../phpqrcode/qrlib.php';
$pagetype           = "form";
$gs_kode_segmen     = "PU";
$gs_pagetitle       = "Upload Dokumen";
$gs_kantor_aktif    = $_SESSION['gs_kantor_aktif'];
$user 		        = $_SESSION["USER"];
$mid                = $_REQUEST["mid"];

$ls_btn_task          = !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
// $ls_kode_kepesertaan  = !isset($_GET['kode_kepesertaan']) ? $_POST['kode_kepesertaan'] : $_GET['kode_kepesertaan'];
$ls_no_urut           = !isset($_GET['no_urut']) ? $_POST['no_urut'] : $_GET['no_urut'];
$ls_kode_klaim        = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_dokumen      = !isset($_GET['kode_dokumen']) ? $_POST['kode_dokumen'] : $_GET['kode_dokumen'];

    if($ls_btn_task=="upload"){
          
      
      if (isset($_POST['upload'])){
                      
        
        $ext = end(explode(".", $_FILES['lob_upload']['name']));
        $jml_ext=strlen($ext)+1;
        $FILENAME = substr(pathinfo($_FILES['lob_upload']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext;
        $size = $_FILES['lob_upload']['size'];
        

        if ($ext=='jpg' or $ext=='jpeg' or $ext=='png' or $ext=='pdf'){
            $status_tipe=1;
            $msg_tipe=' ';
        }else{
            $status_tipe=0;
            $msg_tipe=' Tipe dokumen yang diperbolehkan upload jpg, jpeg, png, dan pdf';
        }

        if ($size > 1000000){
            $status_size=1;
            $msg_size=' Ukuran dokumen melebihi batas maksimal sebesar 2 Megabytes';
        }else{
            $status_size=0;
            $msg_size=' ';
        }

        // echo $status_tipe;
        // echo $status_size;
        // die;

        if($status_tipe==1 && $status_size==0){
            $DOC_FILE = file_get_contents($_FILES['lob_upload']['tmp_name']);
            
              $sql = "  UPDATE SIJSTK.PN_KLAIM_DOKUMEN
                        SET   
                           NAMA_FILE='$FILENAME',
                           DOC_FILE = EMPTY_BLOB(),
                           TGL_DISERAHKAN = SYSDATE,
                           STATUS_DISERAHKAN = 'Y',
                           TGL_UBAH = SYSDATE,
                           PETUGAS_UBAH = '$user'
                        WHERE  KODE_KLAIM    = '".$ls_kode_klaim."' 
                        AND    KODE_DOKUMEN  = '".$ls_kode_dokumen."'  
                        RETURNING
                        DOC_FILE INTO :LOB_A
                      ";
              // var_dump($sql);
              // die;
            $stmt = oci_parse($DB->conn, $sql);
            
            $myLOB = oci_new_descriptor($DB->conn, OCI_D_LOB);
            oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
            oci_execute($stmt, OCI_DEFAULT)
                or die ("Unable to execute query\n");
            if ( !$myLOB->save($DOC_FILE.date('H:i:s',time())) ) {
               
                oci_rollback($DB->conn);
            } else {
                $STATUS_UPLOAD=oci_commit($DB->conn);
            }

            oci_free_statement($stmt);
            $myLOB->free();

        }else{
            
        }
         

        if($STATUS_UPLOAD && $status_size==0 && $status_tipe==1){
            
            $msg = "Upload dokumen berhasil, session dilanjutkan...";  
                echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                // echo "window.opener.getElementById('d_adm_status_diserahkan".$ls_no_urut."').checked";
                // echo "window.opener.fl_js_set_status_diserahkan(".$ls_no_urut.")";
                // echo "window.opener.location.reload();";             /mod_pn/form/pn5001.php?task=Edit&kode_klaim=KL18020800003139&dataid=KL18020800003139&activetab=3#
                echo "window.close();";
                echo "window.opener.location.href='http://".$HTTP_HOST."/mod_pn/form/pn5001.php?task=Edit&kode_klaim=".$ls_kode_klaim."&dataid=".$ls_kode_klaim."&activetab=11';";
                echo "window.opener.refreshActivePage('".$FILENAME."');";
                // echo "window.opener.fl_js_set_status_diserahkan(".$ls_no_urut.")";
                echo "</script>"; 

        }else{
            echo 'else';
            
            $msg = "Upload dokumen gagal.".$msg_tipe.$msg_size;

                echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                echo $msg;
                echo "</script>"; 

        }
      }
     

      }

?>
 
<?php
    $tempdir  = "../../temp_qrcode/"; //<-- Nama Folder file QR Code kita nantinya akan disimpan
    //kalau folder belum ada, maka buat.
    // if (!file_exists($tempdir)){ 
    //     mkdir($tempdir);
    // }
    //parameter inputan
    $kode_klaim  = $_GET['kode_klaim'];
    $kode_agenda = $_GET['kode_dokumen'];
    $no_urut     = $_GET['no_urut'];
    $isi_teks = $kode_klaim.'#'.$kode_agenda.'#'.$no_urut;
    $namafile = $kode_klaim.'_'.$kode_agenda.'_'.$no_urut.".png";
    $quality  = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
    $ukuran   = 3; //batasan 1 paling kecil, 10 paling besar
    $padding  = 0;

    QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 
?>

<!-- <form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data"> -->
<form name="fpop" method="post" action="pn5001_uploadlampirandokumen.php" enctype="multipart/form-data">
    <!-- LOCAL JAVASCRIPTS -->	
    <script type="text/javascript" src="../../javascript/calendar.js"></script>
    <script type="text/javascript" src="../../javascript/common.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
    
    <script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
    <script src="../../highcharts/js/highcharts.js"></script>
    <script language="javascript">
    </script>
    <!-- END LOCAL JAVASCRIPTS -->

    <!-- LOCAL CSS -->
    <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
    <link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
    <style>
        .form-row_kiri label span {
            color: #ff0000;
            float: right;
        }
        
        .clear {
            height: 2px!important;
        }

        .btn {
            padding-left: 6px!important;
            padding-right: 6px!important;
            padding-bottom: 3px!important;
            min-width: 88px!important;
        }

        textarea:-moz-read-only, input[type="text"]:-moz-read-only {
            background: #f2f2f2!important;
        }

        textarea:read-only, input[type="text"]:read-only {
            background: #f2f2f2!important;
        }

        select:required, textarea:required, input[type="text"]:required {
            background: #ffff99;
        }

        .fs-guide {
            padding: 6px; 
            background: #fef65b;
        }
        .fs-guide legend {
            padding: 4px; 
            background: #fef65b; 
            border: 1px solid #bdc3c7;
        }
        .fs-guide li {
            margin-left: 10px;
        }

        /* CSS ERRORS  */
        .err_form {
            width: 100%;
            padding: 6px;
        }

        .err_message {
            font-style: italic;
            font-size: 1em;
            color: red;
            margin-top: 6px;   
        }
        /* END CSS ERRORS*/

        /* CSS GRID */
        .grid {
            width: 100%;    
        }

        .row:before, 
        .row:after {
            content:"";
            display: table ;
            clear:both;
        }

        [class*='col-'] {
            float: left; 
            min-height: 1px; 
            width: 10%; 
        }

        .col-1{ width: 10%; }
        .col-2{ width: 20%; }
        .col-3{ width: 30%; }
        .col-4{ width: 40%; }
        .col-5{ width: 50%; }
        .col-6{ width: 60%; }
        .col-7{ width: 70%; }
        .col-8{ width: 80%; }
        .col-9{ width: 90%; }
        .col-10{ width: 100%; }  
        /* END CSS GRID */
    </style>
	<!-- END LOCAL CSS -->

    <?php
    /** GET POST/GET DATA */
    $task             = $_REQUEST["task"];
    $p_kode_iuran     = $_REQUEST["kode_iuran"];
    /** END GET POST/GET DATA */
    ?>

    <div id="header-popup"> 
        <h3><?=$gs_pagetitle;?></h3>
    </div>

    <div id="container-popup">

        <div id="formKiri" style="width: 100%;">
            
            <fieldset style="width:405px; height: 125px;"><legend style="font-size: 14px;">Pilih Dokumen yang akan di Upload &nbsp;&nbsp;* :</legend>
                <table>
                    <tr>
                        <td>
                            <br>
                            <div class="clear"></div>
                            <div class="form-row_kiri">
                                <input type="hidden" id="btn_task" name="btn_task">
                                <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
                                <input type="hidden" id="kode_dokumen" name="kode_dokumen" value="<?=$ls_kode_dokumen;?>">
                                <input type="file" name="lob_upload" value="<?=$ls_nama_file;?>" style="width: 300px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="clear"></div>
                            <div class="form-row_kiri">
                                <input type="submit" class="btn green" id="upload" name="upload" value="UPLOAD"  onClick="upload_dokumen('upload');"/>
                                <input type="button" class="btn green" id="btntutup" name="btntutup" value="TUTUP"/>
                            </div>
                            <br>
                            <br>
                            <br>
                            <font style="color: red;"><?=$msg;?></font>
                        </td>
                        <td>
                            <img style="align-content: center;" src="../../temp_qrcode/<?=$namafile?>" styltitle="Link to Google.com" />
                        </td>
                    </tr>
                </table>
            </fieldset>          
            <br>
            <fieldset style="background: #FF0; width: 405px;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
              <li>Pilih dokumen yang akan di upload</li>  
              <li>Tipe dokumen : jpg, jpeg, png dan pdf</li> 
              <li>Maksimal ukuran dokumen : 1 Mb</li> 
              <li>Klik Tombol Upload untuk melakukan proses upload dokumen</li>  
              <!-- <li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li> -->
            </fieldset>
            <div style="padding-top: 60px;"></div>
        </div>
    </div>

    <!-- LOAD DATA -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#keyword').focus();

            rem_qrcode('<?=$namafile?>');
            
            $('input').keyup(function(){
                this.value = this.value.toUpperCase();
            });      
            
            $('textarea').keyup(function() {
                this.value = this.value.toUpperCase();
            });

            $('#btnproses').click(function() {
                prosesUpload();
            });
			
			$('#btntutup').click(function() {
                tutup();
            });
        });

		function tutup() {
			window.close();
            window.opener.location.href='http://<?=$HTTP_HOST;?>/mod_pn/form/pn5001.php?task=Edit&kode_klaim=<?=$ls_kode_klaim;?>&dataid=<?=$ls_kode_klaim;?>&activetab=11';
            window.opener.refreshActivePage('<?=$FILENAME?>');
		}
		
        
    function upload_dokumen(metode)
    {
      var form = document.fpop;
      if(form.lob_upload.value==""){
        alert('Dokumen upload tidak boleh kosong...!!!');
        form.lob_upload.focus();
      }else
        {  

         form.btn_task.value=metode; 
         form.submit();                          
        }                               
    }

    function rem_qrcode(file){
        $.ajax({
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_action.php?'+Math.random(),
            data: { TYPE:'hapus_qrcode', FILE:file },
            success: function(data) {
                console.log(data);
            }
        });
    }
    </script>	
    <!-- END LOAD DATA -->  		
</form>	
<?php
include "../../includes/footer_app.php";
?>

