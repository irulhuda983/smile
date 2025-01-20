<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype = "form";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER = $_SESSION["USER"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];

function checkParam($key)
{
    return (isset($_GET[$key]) && !empty($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : NULL));
}

$php_file_name="pn5077";
$mid = (isset($_REQUEST["mid"]) && !empty($_REQUEST["mid"]) ? $_REQUEST["mid"] : NULL);
$dataid= isset($_GET['dataid'])?$_GET['dataid']:'';
$ls_kode_fiktif_klaim = htmlspecialchars(checkParam('kode_fiktif_klaim'));



?>
<script type="text/javascript" src="../../javascript/pdfutils/browser-image-compression.js"></script>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script language="javascript">
$(document).ready(function() {
    // $("input[type=text]").keyup(function(){
    // 	$(this).val( $(this).val().toUpperCase() );
    // });

    $(window).bind("resize", function() {
        resize();
    });
    resize();

    $(".digit").keypress(function(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            // $("#errmsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
    });

});

let path_url="";
let mime_type="";

function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

function showFormReload(mypage, myname, w, h, scroll) {
    var openwin = window.parent.Ext.create('Ext.window.Window', {
        title: myname,
        collapsible: true,
        animCollapse: true,
        maximizable: true,
        closable: true,
        width: w,
        height: h,
        minWidth: w,
        minHeight: h,
        layout: 'fit',
        modal: true,
        html: '<iframe src="' + mypage +
            '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
        listeners: {
            close: function() {
                reLoad();
            },
            destroy: function(wnd, eOpts) {}
        }
    });
    openwin.show();
    return openwin;
}

function reLoad() {
    location.reload();
}

function resize() {
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

function show(kode_fiktif_klaim) {

    var params = "&kode_fiktif_klaim=" + kode_fiktif_klaim;
    window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5077_upload_dokumen_la.php?' + params);

}

async function simpanFile_pn5077_upload() {

    var kode_fiktif_klaim = $('#kode_fiktif_klaim').val();
    var nama_dokumen_lainnya = $('#nama_dokumen_lainnya').val();
    var keterangan_dokumen_lainnya = $('#keterangan_dokumen_lainnya').val();
    var fileketerangan = $('#fileketerangan').val();

    if (kode_fiktif_klaim == '') {
        alert('Kode fiktif klaim tidak boleh kosong');
        return;
    }

    if (nama_dokumen_lainnya == '') {
        alert('Nama dokumen tidak boleh kosong');
        return;
    }

    if (keterangan_dokumen_lainnya == '') {
        alert('Keterangan tidak boleh kosong');
        return;
    }

    if (fileketerangan == '') {
        alert('File tidak boleh kosong');
        return;
    }

    let konfirmasi = "Apakah anda yakin akan menyimpan file ini?";

    confirmation("Konfirmasi", konfirmasi,
        async function() {
                await proses_upload_pn5077_upload();
                await proses_upload_db(path_url, mime_type);
                window.location = '../ajax/pn5077_upload_dokumen_la.php?&kode_fiktif_klaim=' + kode_fiktif_klaim + '&mid=';
                // setTimeout(function(){close();}, 1000)
            },
            setTimeout(function() {}, 1000));

}

async function proses_upload_pn5077_upload() {
    return new Promise(async function(resolve, reject) {
        var fileketerangan = $('#fileketerangan').val();

        let file_dokumen = $('#fileketerangan').val();
        let file = document.getElementById("fileketerangan").files[0];

        let upload = "";

        if (file) {
            let filesize = file.size;
            let maxsize = 6097152;
            if (filesize > maxsize) {
                return alert('Maximal file upload 6 MB!');
            } else {
                preload(true);
                upload = await uploadFile_pn5077(file);
                preload(false);
                if (upload.ret == "0") {
                    path_url += upload.data.path;
                    mime_type += upload.data.mimeType;

                }

            }
        }

        if (parseInt(upload.ret) == -1) {
            return alert('Terjadi kesalahan, gagal mengupload file.');
        }


        resolve(true);

    });
}

function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

async function uploadFile_pn5077(file) {

    let image_file = await getBase64(file);

    let data = "<?php 

    function encrypt_decrypt_pn5077_upload($action, $string)
    {
        /* =================================================
        * ENCRYPTION-DECRYPTION
        * =================================================
        * ENCRYPTION: encrypt_decrypt('encrypt', $string);
        * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
        */
        $output_pn5077_upload = false;
        $encrypt_method_pn5077_upload = "AES-256-CBC";
        $secret_key_pn5077_upload = 'WS-SERVICE-KEY';
        $secret_iv_pn5077_upload = 'WS-SERVICE-VALUE';
        // hash
        $key = hash('sha256', $secret_key_pn5077_upload);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv_pn5077_upload), 0, 16);
        if ($action == 'encrypt') {
        $output_pn5077_upload = base64_encode(openssl_encrypt($string, $encrypt_method_pn5077_upload, $key, 0, $iv));
        } else {
        if ($action == 'decrypt') {
            $output_pn5077_upload = openssl_decrypt(base64_decode($string), $encrypt_method_pn5077_upload, $key, 0, $iv);
        }
        }
        return $output_pn5077_upload;
    }

    $json_spec = array(
        "kodeDokumen" => "",
        "bucketName" => "smile",
        "folderName" => "potensiklaimfiktif/".$KD_KANTOR."/".date("Ym"),
        "idDokumen" => ""
    );

    echo encrypt_decrypt_pn5077_upload('encrypt',str_replace("\/", "/", json_encode($json_spec))) 		

    ?>";

    let spec = {
        "data": data,
        "fileBase64": image_file
    };


    return await doUploadDokumen(spec);

}

async function doUploadDokumen(spec) {
    let end_point = "<?php echo $wsFileSmile ?>";
    const response = await fetch(end_point + "/krgBayar/uploadFileKB", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            data: spec
        })
    });
    const data = await response.json();
    return data;
}

function proses_upload_db(path_url, mime_type) {

    let kode_fiktif_klaim = $('#kode_fiktif_klaim').val();
    let nama_dokumen_lainnya = $('#nama_dokumen_lainnya').val().trim();
    let keterangan_dokumen_lainnya = $('#keterangan_dokumen_lainnya').val().trim();

    let path_url_dokumen_lainnya = path_url;
    let mime_type_file_dokumen_lainnya = mime_type;

    preload(true);
    $.ajax({
        type: "POST",
        url: "../ajax/pn5077_action.php?" + Math.random(),
        data: {
            tipe: "savedokumenlain",
            nama_dokumen_lainnya: nama_dokumen_lainnya,
            keterangan_dokumen_lainnya: keterangan_dokumen_lainnya,
            path_url_dokumen_lainnya: path_url_dokumen_lainnya,
            mime_type_file_dokumen_lainnya: mime_type_file_dokumen_lainnya,
            kode_fiktif_klaim: kode_fiktif_klaim
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

</script>
<style>
    .div-container {
        min-width: 700px;
        width: 100%;
    }

    .div-header {
        min-width: 700px;
    }

    .div-body {
        overflow-x: auto;
        overflow-y: auto;
        white-space: nowrap;
    }

    .div-data {
        overflow-x: auto;
        overflow-y: auto;
        white-space: nowrap;
    }

    .div-footer {
        padding-top: 10px;
        border-bottom: 1px solid #eeeeee;
    }

    .hr-double {
        border-top: 3px double #8c8c8c;
        border-bottom: 3px double #8c8c8c;
    }

    .hr-double-top {
        border-top: 3px double #8c8c8c;
    }

    .hr-double-bottom {
        border-bottom: 3px double #8c8c8c;
    }

    .hr-double-left {
        border-left: 3px double #8c8c8c;
    }

    .hr-double-right {
        border-right: 3px double #8c8c8c;
    }

    .table-data {
        width: 100%;
        border-collapse: collapse;
        border-color: #c0c0c0;
        background-color: #ffffff;
    }

    .table-data th {
        padding: 10px 6px 10px 6px;
        font-weight: bold;
        text-align: left;
    }

    .table-data td {
        padding: 4px 6px 4px 6px;
        text-align: left;
        border-bottom: 1px solid #c0c0c0;
    }

    .table-data tr:last-child td {
        border-bottom: 3px double #8c8c8c;
    }

    .table-data tbody tr:hover {
        cursor: pointer;
        background-color: #f5f5f5;
    }

    .nohover-color:hover {
        cursor: pointer !important;
        background-color: #FFFFFF !important;
    }

    .value-modified {
        background-color: #b4eeb4 !important;
    }

    .input-disabled {
        background-color: #f5f5f5;
        /* color: rgb(170, 170, 170); */
        color: black;
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
                            <table width="100%" aria-describedby="tableData">
                                <thead id="data_header"><tr><th></th></tr></thead>
                                <tr>
                                    <td style="vertical-align:top;width:20px">
                                        <label for="kode_fiktif_klaim">Kode Fiktif Klaim <sup
                                                style="color:red">*</sup></label>
                                    </td>
                                    <td>
                                        <input type="text" readonly class="input-disabled" name="kode_fiktif_klaim"
                                            id="kode_fiktif_klaim" value="<?=$ls_kode_fiktif_klaim?>"
                                            style="width: 250px;" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">
                                        <label for="nama_dokumen_lainnya">Nama Dokumen <sup
                                                style="color:red">*</sup></label>
                                    </td>
                                    <td>
                                        <input type="text" name="nama_dokumen_lainnya" id="nama_dokumen_lainnya"
                                            style="width: 250px;" autofocus required>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">
                                        <label for="keterangan_dokumen_lainnya">Keterangan <sup
                                                style="color:red">*</sup></label>
                                    </td>
                                    <td>
                                        <textarea name="keterangan_dokumen_lainnya" id="keterangan_dokumen_lainnya"
                                            style="width: 250px;" rows="5" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top">
                                        <label for="file">File <sup style="color:red">*</sup></label>
                                    </td>
                                    <td>
                                        <input type="file" name="fileketerangan" id="fileketerangan"
                                            style="width: 250px;" accept="image/*, application/pdf" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="button" name="btnupload" id="btnupload"
                                            style="width: 80px;margin-top:5px" class="btn green" value="SIMPAN"
                                            onclick="simpanFile_pn5077_upload()" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
<script type="text/javascript">
</script>
<?php
require_once "../../includes/footer_app_nosql.php";
?>