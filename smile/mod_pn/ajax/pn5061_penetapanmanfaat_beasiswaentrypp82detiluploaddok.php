<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
include '../../phpqrcode/qrlib.php';
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

function handleError($errno, $errstr,$error_file,$error_line) {
    if($errno == 2){
      $errorMsg = $errstr;
      if (strpos($errstr, 'failed to open stream:') !== false) {
        $errorMsg = 'Terdapat masalah dengan koneksi WebService.';
      } elseif(strpos($errstr, 'oci_connect') !== false) {
        $errorMsg = 'Terdapat masalah dengan koneksi database.';
      } else {
        $errorMsg = $errstr;
      }
      echo '{ "ret":-1, "msg":"<b>Error:</b> '.$errorMsg.'" }';
      die();
    }
  }
  set_error_handler("DefaultGlobalErrorHandler");

$pagetype           = "form";
$gs_pagetitle       = "Upload Dokumen";
$gs_kantor_aktif    = $_SESSION['gs_kantor_aktif'];
$user 		        	= $_SESSION["USER"];
$mid                = $_REQUEST["mid"];

$ls_btn_task        = !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
$ls_kode_klaim      = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_nik_penerima   	= !isset($_GET['nik_penerima']) ? $_POST['nik_penerima'] : $_GET['nik_penerima'];
$ls_tahun   				= !isset($_GET['tahun']) ? $_POST['tahun'] : $_GET['tahun'];
$ls_nama_dokumen    = !isset($_GET['nama_dokumen']) ? $_POST['nama_dokumen'] : $_GET['nama_dokumen'];
$ln_no_urut_dokumen = !isset($_GET['no_urut_dokumen']) ? $_POST['no_urut_dokumen'] : $_GET['no_urut_dokumen'];
$ls_data_id					= $ls_nik_penerima;
$ls_file            = $_FILES["lob_upload"];
$ls_file_mime_type  = $_FILES["lob_upload"]["type"];


if($ls_btn_task=="upload")
{      
      if (isset($_POST['upload']))
			{
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

        if ($size > 2000000){
            $status_size=1;
            $msg_size=' Ukuran dokumen melebihi batas maksimal sebesar 2 Megabytes';
        }else{
            $status_size=0;
            $msg_size=' ';
        }
				
        if($status_tipe==1 && $status_size==0)
				{
           
            $curl = curl_init();
  
            curl_setopt_array($curl, array(
            CURLOPT_URL => $wsIpStorage."/put-object",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                                // 'file' =>
                                //     '@'            . $ls_file['tmp_name']
                                //     . ';filename=' . $ls_file['name']
                                //     . ';type='     . $ls_file['type'],
                                    'file' =>new CurlFile(
                                        $ls_file['tmp_name'],
                                        $ls_file['type'],
                                        $ls_file['name']),
                                    'namaBucket' => 'smile',
                                    'namaFolder' => 'mod_pn/'.$gs_kantor_aktif.'/'.date("Ym")
                              ),
            CURLOPT_HTTPHEADER => array(
              "Accept: /",
              "Cache-Control: no-cache",
              "Connection: keep-alive",
              "Content-Type: multipart/form-data"
            ),
          ));
  
          $response = curl_exec($curl);
          $err = curl_error($curl);
  
          curl_close($curl);
  
          if ($err) {
            echo "cURL Error #:" . $err;
          } else {
             $result = json_decode($response);
          }

          $ls_file_path_url= $result->path;
					
					//insert dokumen -----------------------------------------------------
					if ($ln_no_urut_dokumen=="")
					{			
            $sql = "select nvl(no_urut,0)+1 as no_urut from ".
                   "( ".
                   "    select max(no_urut) as no_urut from smile.pn_penerima_beasiswa_dok ".
                   "    where nik_penerima = '$ls_nik_penerima' ".
                   ")";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_no_urut_dokumen = $row["NO_URUT"];
								
            $sql = "INSERT INTO SMILE.PN_PENERIMA_BEASISWA_DOK ( ".
                   "    NIK_PENERIMA, NO_URUT, KODE_KLAIM,  ".
                   "    NAMA_DOKUMEN, KETERANGAN, URL,  ".
                   "    NAMA_FILE, MIME_TYPE,  ".
                   "    STATUS_DISERAHKAN, TGL_DISERAHKAN, ". 
                   "    TGL_REKAM, PETUGAS_REKAM ".
									 ")  ".
                   "VALUES (  ".
  								 "    '$ls_nik_penerima', '$ln_no_urut_dokumen', '$ls_kode_klaim',  ".
                   "    '$ls_nama_dokumen', 'PENETAPAN_BEASISWA_'||'$ls_tahun', '$ls_file_path_url',  ".
                   "    '$FILENAME', '$ls_file_mime_type',  ".
                   "    'Y', sysdate, ". 
                   "    sysdate, '$user' ".
                   ")";
            $DB->parse($sql); 
						$DB->execute();
						$STATUS_UPLOAD=1;
					}else
					{
              $sql_update = "UPDATE SMILE.PN_PENERIMA_BEASISWA_DOK SET ".
										 "		KODE_KLAIM = '$ls_kode_klaim', ".			
                     "    NAMA_DOKUMEN = '$ls_nama_dokumen', ".
  									 "		KETERANGAN = 'PENETAPAN_BEASISWA_'||'$ls_tahun', ".
  									 "		URL = '$ls_file_path_url',  ".
                     "    NAMA_FILE = '$FILENAME', ".
  									 "		MIME_TYPE = '$ls_file_mime_type',  ".
                     "    STATUS_DISERAHKAN = 'Y', ".
  									 "		TGL_DISERAHKAN = sysdate, ". 
                     "    TGL_UBAH = sysdate, ".
  									 "		PETUGAS_UBAH = '$user' ".
  									 "WHERE NIK_PENERIMA = '$ls_nik_penerima' ".
  									 "AND NO_URUT = '$ln_no_urut_dokumen' ";
  						$DB->parse($sql_update);
          		$DB->execute();
  						$STATUS_UPLOAD=1;											
					}   
        }
         
        if($STATUS_UPLOAD==1 && $status_size==0 && $status_tipe==1){
            
            $msg = "Upload dokumen berhasil, session dilanjutkan...";  
            echo "<script language=\"JavaScript\" type=\"text/javascript\">";
            echo "window.opener.fl_js_setval_postuploaddok('$ls_file_path_url', '$FILENAME', '$ln_no_urut_dokumen');";
						echo "window.close();";
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
		$nik_penerima  = $_GET['nik_penerima'];
    $no_urut     = $_GET['no_urut_dokumen'];
  	if ($no_urut=="")
  	{
      $sql = "select nvl(no_urut,0)+1 as no_urut from ".
             "( ".
             "    select max(no_urut) as no_urut from smile.pn_penerima_beasiswa_dok ".
             "    where nik_penerima = '$nik_penerima' ".
             ")";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $no_urut = $row["NO_URUT"];
  	}						
								
    $isi_teks = $kode_klaim.'#'.$nik_penerima.'#'.$no_urut;
    $namafile = $kode_klaim.'_'.$nik_penerima.'_'.$no_urut.".png";
    $quality  = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
    $ukuran   = 3; //batasan 1 paling kecil, 10 paling besar
    $padding  = 0;

    QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 
?>

<!-- <form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data"> -->
<form name="fpop" method="post" action="pn5061_penetapanmanfaat_beasiswaentrypp82detiluploaddok.php" enctype="multipart/form-data">
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
																<input type="hidden" id="nik_penerima" name="nik_penerima" value="<?=$ls_nik_penerima;?>">
																<input type="hidden" id="tahun" name="tahun" value="<?=$ls_tahun;?>">
                                <input type="hidden" id="nama_dokumen" name="nama_dokumen" value="<?=$ls_nama_dokumen;?>">
																<input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen" value="<?=$ln_no_urut_dokumen;?>">
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
              <li>Maksimal ukuran dokumen : 2 Mb</li> 
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
      window.opener.reloadFormUtama();
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
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5065_action.php?'+Math.random(),
            data: { tipe:'hapus_qrcode', FILE:file },
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

