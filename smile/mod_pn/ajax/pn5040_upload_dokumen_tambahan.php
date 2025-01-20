<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype = "form";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER = $_SESSION["USER"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];


$php_file_name="pn5040";                                                                        
$mid = $_REQUEST["mid"]; 
$dataid= isset($_GET['dataid'])?$_GET['dataid']:'';
$ls_kode_klaim = $_GET['kode_klaim'];



?>
<script type="text/javascript" src="../../javascript/pdfutils/browser-image-compression.js"></script>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script language="javascript">
	$(document).ready(function(){
		// $("input[type=text]").keyup(function(){
		// 	$(this).val( $(this).val().toUpperCase() );
		// });

  		$(window).bind("resize", function(){
			resize();
		});
		resize();

		$(".digit").keypress(function (e) {
                       //if the letter is not digit then display error and don't type anything
                       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                          //display error message
                         // $("#errmsg").html("Digits Only").show().fadeOut("slow");
                                 return false;
                      }
           });
	});

	function resize(){
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);
		$("#div_footer").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
		$("#div_footer").width($("#div_dummy_data").width());

		$("#div_container").css('max-height', $(window).height());
	}
	
	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}


	function close() {
		var win = window.parent.parent.Ext.WindowManager.getActive();
    		if (win) {
     	 win.close();
    	}
	}

	function getBase64(file) {
		return new Promise((resolve, reject) => {
		const reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = () => resolve(reader.result);
		reader.onerror = error => reject(error);
	});
	}

	function updateData(url,mimetype){

let kode_klaim=$('#kode_klaim').val();	
let nama_dokumen_lainnya = $('#nama_dokumen_lainnya').val().trim();
let keterangan_dokumen_lainnya = $('#keterangan_dokumen_lainnya').val().trim();

let path_url_dokumen_lainnya=url;
let mime_type_file_dokumen_lainnya=mimetype;

	preload(true);
	$.ajax({
				type: 'POST',
				url: "../ajax/pn5040_upload_dokumen_tambahan_action.php?"+Math.random(),
				data :{
					tipe : "savedokumenlain",
					nama_dokumen_lainnya : nama_dokumen_lainnya,
					keterangan_dokumen_lainnya : keterangan_dokumen_lainnya,
					path_url_dokumen_lainnya : path_url_dokumen_lainnya,
					mime_type_file_dokumen_lainnya : mime_type_file_dokumen_lainnya,
					kode_klaim : kode_klaim
				}, 
				success: function(data){
					jdata = JSON.parse(data);

					if (jdata.ret == 0){
						
						close();						
						//filter();
						// alert("Sukses, berkas berhasil di upload, silahkan upload kembali berkas yang lain di form ini atau tutup jika sudah selesai.");
						// show(jdata.data);

						preload(false);
						
					} else {
						
						alert(jdata.msg);
					}
					
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

async function simpan() {
	

var form = $('#formreg');
var formdata = false;
if (window.FormData){
	formdata = new FormData(form[0]);
}

let file_keterangan = $('#file_keterangan').val();
let fileketerangan = document.getElementById("fileketerangan").files[0];
let fileName = fileketerangan.name;
let fileExt  = fileName.split('.')[fileName.split('.').length - 1];

let maxsize = 6097152;
if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
	const options = {
		quality: 0.85,
		maxSizeMB: maxsize,
		maxWidthOrHeight: 1920,
		useWebWorker: true
	}
	fileketerangan = await imageCompression(fileketerangan, options);
}

let nama_dokumen_lainnyax = $('#nama_dokumen_lainnya').val();
let keterangan_dokumen_lainnyax = $('#keterangan_dokumen_lainnya').val();

let image_file="";
if(fileketerangan){
 image_file = await getBase64(fileketerangan);
}
let folder_name ="<?php echo $KD_KANTOR.'/'.date("Ym") ?>";
let lapakasik = "lapakasik";
let end_point= "<?php echo $wsIpLapakAsikOnsiteDomain ?>";

let data = "<?php 

function encrypt_decrypt($action, $string)
	{
		/* =================================================
		* ENCRYPTION-DECRYPTION
		* =================================================
		* ENCRYPTION: encrypt_decrypt('encrypt', $string);
		* DECRYPTION: encrypt_decrypt('decrypt', $string) ;
		*/
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'WS-SERVICE-KEY';
		$secret_iv = 'WS-SERVICE-VALUE';
		// hash
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ($action == 'encrypt') {
			$output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
		} else {
			if ($action == 'decrypt') {
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		}
		return $output;
	}

	$json_spec = array(
		"bucketName" => "lapakasik",
		"folderName" => $KD_KANTOR.'/'.date("Ym"),
		"pathFile" => ""
		);

	echo encrypt_decrypt('encrypt',str_replace("\/", "/", json_encode($json_spec))) 		

?>";

// console.log(data);
// console.log(JSON.stringify(data));		

let spec = 
	{
	
		"imageFile" : image_file,
		"dataEncrypt" : data	
};

// console.log(spec);




	confirmation("Konfirmasi", "Apakah Anda yakin untuk menambahkan data ini?",
		function () {
			if(fileketerangan){
			let filesizeketerangan = fileketerangan.size;
			if(filesizeketerangan > maxsize){
			  return alert('Maximal file upload 6 MB!');
			} 
		  }else{
			  return alert('File tidak boleh kosong');
		  } 
			  if(nama_dokumen_lainnyax==""){
				return alert('Nama dokumen tidak boleh kosong');
			}

			if(keterangan_dokumen_lainnyax==""){
				return alert('Keterangan dokumen tidak boleh kosong');
			}

			preload(true);

			$.ajax({ 
			   type: 'POST', 
				 url: end_point+"/services/upload/uploadDokumenSmile",
			data: JSON.stringify({
				data : spec
			}), 
			dataType: 'json', 
			contentType: 'application/json; charset=utf-8',
			success: function(data){
					//console.log(data);
			
					if (data.ret == 0){
						
						updateData(data.data.path, data.data.mimeType);
						
					} else {
					
						alert("Terjadi kesalahan, pastikan data yang di input lengkap dan benar.");
						
					}
					
				},
				complete: function(){
					
					preload(false);
				},
				error: function(){
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				
					preload(false);
				}
			});
		}, 
	function (){});
}
</script>
<style>
	.div-container{
		min-width: 700px;
		width: 100%;
	}
	.div-header{
		min-width: 700px;
	}
	.div-body{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-data{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-footer{
		padding-top: 10px;
		border-bottom: 1px solid #eeeeee;
	}
	.hr-double{
		border-top:3px double #8c8c8c;
		border-bottom:3px double #8c8c8c;
	}
  .hr-double-top{
    border-top:3px double #8c8c8c;
	}
  .hr-double-bottom{
  	border-bottom:3px double #8c8c8c;
	}
	.hr-double-left{
    border-left:3px double #8c8c8c;
	}
  .hr-double-right{
    border-right:3px double #8c8c8c;
	}
	.table-data{
		width: 100%;
		border-collapse: collapse;
		border-color: #c0c0c0;
		background-color: #ffffff;
	}
	.table-data th{
		padding: 10px 6px 10px 6px;
		font-weight: bold;
		text-align: left;
	}
	.table-data td{
		padding: 4px 6px 4px 6px;
		text-align: left;
		border-bottom: 1px solid #c0c0c0;
	}
	.table-data tr:last-child td{
		border-bottom:3px double #8c8c8c;
	}
	.table-data tbody tr:hover{
		cursor: pointer;
		background-color:#f5f5f5;
	}
  .nohover-color:hover {
		cursor: pointer!important;
    background-color:#FFFFFF!important;
	}
	.value-modified{
    background-color: #b4eeb4!important;
  }
</style>
<div id="formframe2">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<div id="div_container" class="div-container">
				<div id="div_header" class="div-header">
				</div>
				<div id="div_body" class="div-body">
					<div id="div_dummy_data" style="width: 100%;"></div>
					<div id="div_filter">
						<div style="padding-top: 0px;">
							<div style="float: left; width: 100%; padding-bottom: 4px;">
								<div style="width: 120px; display: inline-block; text-align: left;">Kode Klaim :</div>
								<input type= "text" id = "kode_klaim" name = "kode_klaim" style="width: 250px;" value="<?php echo $ls_kode_klaim?>" readonly  class="disabled">
								<input type="hidden" name="tipe" id="tipe" style="width: 250px;"  value="savedokumenlain" readonly  class="disabled">  
							</div>
							<div style="float: left; width: 100%; padding-bottom: 4px;">
								<div style="width: 120px; display: inline-block; text-align: left;">Nama Dokumen :</div>
								<input type="text" name="nama_dokumen_lainnya" id="nama_dokumen_lainnya"  style="width: 250px;">
							</div>
							<div style="float: left; width: 100%; padding-bottom: 4px;">
								<div style="width: 120px; display: inline-block; text-align: left;">Keterangan :</div>
								<input type="text" name="keterangan_dokumen_lainnya" id="keterangan_dokumen_lainnya" style="width: 250px; ">
							</div>
							<div style="float: left; width: 100%; padding-bottom: 4px;">
								<div style="width: 120px; display: inline-block; text-align: left;">File :</div>
								<input type="file" name="fileketerangan" id="fileketerangan" style="width: 250px;" accept="image/*, application/pdf">
							</div>
							<div style="float: left; width: 100%; padding-bottom: 4px;">
								<div style="width: 120px; display: inline-block; text-align: left;">&nbsp;</div>
								
								
								<input type="button" name="btnupload" id="btnupload" style="width: 80px" class="btn green" value="Simpan" onclick="simpan()"/>
								
							</div>
						</div>
					</div>
			</div>	
		</form>
	</div>
</div>
<script type="text/javascript">
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>