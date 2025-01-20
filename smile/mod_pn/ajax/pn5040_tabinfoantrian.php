<?php
 if ($_GET['dataid'] != ""){
    //query data -------------------------------------------------------------
    $sql = "
    select  a.kode_antrian,
            a.kode_jenis_antrian,
            (select nama_jenis_antrian from pn.pn_antrian_kode_jenis where kode_jenis_antrian = a.kode_jenis_antrian) nama_jenis_antrian,
            b.kode_status_antrian,
            (select nama_status_antrian from pn.pn_antrian_kode_status where kode_status_antrian = b.kode_status_antrian) nama_status_antrian,
            a.token_sisla,
            a.kode_sisla,
            a.kode_kantor,
            (select nama_kantor from ms.ms_kantor where kode_kantor = a.kode_kantor) nama_kantor,
            a.nomor_antrian,
            to_char(a.tgl_ambil_antrian, 'YYYY-MM-DD HH24:MI:SS') tgl_ambil_antrian,
            to_char(a.tgl_panggil_antrian, 'YYYY-MM-DD HH24:MI:SS') tgl_panggil_antrian,
            a.petugas_panggil,
            (select nama_user from ms.sc_user where kode_user = a.petugas_panggil) nama_petugas_panggil,
            b.kode_klaim_agenda,
            a.nomor_identitas,
            a.no_hp,
            a.email,
            a.keterangan,
            (select path_url from pn.pn_antrian_dokumen where kode_antrian = a.kode_antrian and rownum=1) path_url_foto														
    from    pn.pn_antrian a,
            pn.pn_antrian_program b
    where   a.kode_antrian = b.kode_antrian
    and     b.kode_klaim_agenda = '".$_GET['dataid']."' ";
    //echo $sql;
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_kode_antrian = $row['KODE_ANTRIAN'];
    $ls_kode_jenis_antrian = $row['KODE_JENIS_ANTRIAN'];
    $ls_nama_jenis_antrian = $row['NAMA_JENIS_ANTRIAN'];
    $ls_kode_status_antrian = $row['KODE_STATUS_ANTRIAN'];
    $ls_nama_status_antrian = $row['NAMA_STATUS_ANTRIAN'];
    $ls_token_antrian = $row['TOKEN_SISLA'];
    $ls_kode_sisla = $row['KODE_SISLA'];
    $ls_kode_kantor_antrian = $row['KODE_KANTOR'];
    $ls_nama_kantor_antrian = $row['NAMA_KANTOR'];
    $ls_no_antrian = $row['NOMOR_ANTRIAN'];
    $ls_tgl_ambil_antrian = $row['TGL_AMBIL_ANTRIAN'];
    $ls_tgl_panggil_antrian = $row['TGL_PANGGIL_ANTRIAN'];
    $ls_kode_petugas_antrian = $row['PETUGAS_PANGGIL'];
    $ls_nama_petugas_antrian = $row['NAMA_PETUGAS_PANGGIL'];
    $ls_nomor_identitas_antrian = $row['NOMOR_IDENTITAS'];
    $ls_no_hp_antrian = $row['NO_HP'];
    $ls_email_antrian = $row['EMAIL'];
    $ls_path_url_foto = $row['PATH_URL_FOTO'];

    if($ls_path_url_foto != ""){
        $ls_path_url_foto = $wsIpStorage.$ls_path_url_foto;
    }
}
?>
<div id="formKiri" style="width:800px;">
	<fieldset><legend><b><i><font color="#009999">Entry Antrian Klaim</font></i></b></legend>	
    <div id="dispInfoToken" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Token Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="password" id="token_antrian" name="token_antrian" style="width:260px;" value ="<?=$ls_token_antrian;?>" <?=($_REQUEST["task"] == "New")? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\"";?>>
    </div>
    <div class="form-row_kanan">
        <label  style = "text-align:right;">Kode Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="kode_antrian" name="kode_antrian" value="<?=$ls_kode_antrian;?>" style="width:150px;" readonly class="disabled">
    </div>																																																			
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">No Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="no_antrian" name="no_antrian" value="<?=$ls_no_antrian;?>" style="width:80px; text-align:center" readonly class="disabled">
        <input type="text" id="kode_sisla" name="kode_sisla" value="<?=$ls_kode_sisla;?>" style="width:150px;" readonly class="disabled">
    </div>
    <div class="form-row_kanan">
        <label  style = "text-align:right;">Status Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="hidden" id="kode_status_antrian" name="kode_status_antrian" value="<?=$ls_kode_status_antrian;?>" style="width:40px;" readonly class="disabled">
        <input type="text" id="nama_status_antrian" name="nama_status_antrian" value="<?=$ls_nama_status_antrian;?>" style="width:150px;" readonly class="disabled">
    </div>																																																		
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Jenis Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="kode_jenis_antrian" name="kode_jenis_antrian" value="<?=$ls_kode_jenis_antrian;?>" style="width:50px;" readonly class="disabled">
        <input type="text" id="nama_jenis_antrian" name="nama_jenis_antrian" value="<?=$ls_nama_jenis_antrian;?>" style="width:180px;" readonly class="disabled">
    </div>	
    <div class="form-row_kanan">
        <label  style = "text-align:right;">No Handphone &nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="no_hp_antrian" name="no_hp_antrian" value="<?=$ls_no_hp_antrian;?>" style="width:150px;" onblur="fl_js_val_numeric_antrian('no_hp_antrian');" maxlength="15" <?=($_REQUEST["task"] == "New")? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\"";?>>
    </div>																																																		
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Tanggal Ambil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="tgl_ambil_antrian" name="tgl_ambil_antrian" value="<?=$ls_tgl_ambil_antrian;?>" style="width:200px;" readonly class="disabled">
        <!-- <input type="text" id="jam_ambil_antrian" name="jam_ambil_antrian" value="<?=$ls_jam_ambil_antrian;?>" style="width:140px;" readonly class="disabled"> -->
    </div>	
    <div class="form-row_kanan">
        <label  style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="email_antrian" name="email_antrian" value="<?=$ls_email_antrian;?>" style="width:150px;" onblur="fl_js_val_email_antrian('email_antrian');" maxlength="200" <?=($_REQUEST["task"] == "New")? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\"";?>>
    </div>																																														
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Tanggal Panggil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="tgl_panggil_antrian" name="tgl_panggil_antrian" value="<?=$ls_tgl_panggil_antrian;?>" style="width:200px;" readonly class="disabled">
        <!-- <input type="text" id="jam_panggil_antrian" name="jam_panggil_antrian" value="<?=$ls_jam_panggil_antrian;?>" style="width:140px;" readonly class="disabled"> -->
    </div>	
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Kantor Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="kode_kantor_antrian" name="kode_kantor_antrian" value="<?=$ls_kode_kantor_antrian;?>" style="width:40px;" readonly class="disabled">
        <input type="text" id="nama_kantor_antrian" name="nama_kantor_antrian" value="<?=$ls_nama_kantor_antrian;?>" style="width:190px;" readonly class="disabled">			
    </div>		
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Petugas Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="kode_petugas_antrian" name="kode_petugas_antrian" value="<?=$ls_kode_petugas_antrian;?>" style="width:60px;" readonly class="disabled">
        <input type="text" id="nama_petugas_antrian" name="nama_petugas_antrian" value="<?=$ls_nama_petugas_antrian;?>" style="width:170px;" readonly class="disabled">
    </div>	
    <div class="clear"></div>		
	</fieldset>	 	
</div> <!-- end div formKiri -->

<div id="formKanan">	
    <fieldset> 				              									
    <div class="form-row_kiri">
		<input id="image1" name="image1" type="image" disabled <?=($ls_path_url_foto == "")? " src='../../images/empty-profile.png' " : " src='$ls_path_url_foto' ";?> align="center" style="!important; width: 140px !important; height: 115px; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button id="btn_camera" name="btn_camera" disabled onclick="openNewWindow(event);" <?=($_REQUEST["task"] == "View" || $_REQUEST["task"] == "Edit")? "disabled" : "";?>>Camera</button>
        <textarea type="text" id="base64_foto_peserta" name="base64_foto_peserta" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
    </div>	
    <textarea type="text" id="path_url_antrian_curr" name="path_url_antrian_curr" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
    <input type="text" id="type_file_antrian" name="type_file_antrian" style="width:100px;" readonly class="disabled"hidden >	
    <!-- <input type="text" id="valid_nik_antrian" name="valid_nik_antrian" value="Y" style="width:70px;" readonly class="disabled"> -->
    <div class="clear"></div>	
    </fieldset>
</div> 
<!-- end div formKanan -->

<script type="text/javascript">
    let newWindow;
    let imageBase64;
    let blob;
    window.addEventListener('message', (event) => {
        if(event.data?.msg) {
            imageBase64 = event.data.msg;
            $("#base64_foto_peserta").val(imageBase64);
            console.log(imageBase64);
            document.getElementById('image1').src = imageBase64;

            var block = imageBase64.split(";");
            // Get the content type of the image
            var contentType = block[0].split(":")[1];// In this case "image/gif"
            // get the real base64 content of the file
            var realData = block[1].split(",")[1];

            blob = b64toBlob(realData, contentType);
            console.log(blob);
        }
    })
    const openNewWindow = (event) => {
        event.preventDefault()
        const width = 680;
        const height = 560;
        const left = ( screen.width - width ) / 2;
        const top = ( screen.height - height ) / 2;
        const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,left=${left},top=${top},width=${width},height=${height}`;
        //newWindow = window.open('https://sidia-testing.bpjsketenagakerjaan.go.id/camera.html', 'popup', params);
        newWindow = window.open('<?=$appsCam;?>'+'/camera.html', 'popup', params);
    }

    function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        var blob = new Blob(byteArrays, {type: contentType});
        return blob;
    }

    function f_js_get_info_token(){
        var token_antrian = $("#token_antrian").val();
        $("#kode_sisla").val("");
        $("#kode_kantor_antrian").val("");
        $("#nama_kantor_antrian").val("");
        $("#no_antrian").val("");
        $("#tgl_ambil_antrian").val("");
        $("#tgl_panggil_antrian").val("");
        $("#kode_petugas_antrian").val("");
        $("#nama_petugas_antrian").val("");
        $("#kode_jenis_antrian").val("");
        $("#nama_jenis_antrian").val("");
        window.document.getElementById("dispInfoToken").innerHTML = "";
        window.document.getElementById("dispInfoToken").style.display = 'none';
        if (token_antrian != "") {
            //preload(true);
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                data: {
                    "TYPE" : "getDecryptToken",
                    "token_antrian" : token_antrian
                },
                success: function(data){
                    console.log(data);
                    jdata = JSON.parse(data);								
                    if (jdata.ret == '0') {
                        var kode_jenis_antrian = "SA01";
                        var kode_jenis_antrian_detil = kode_jenis_antrian+""+$('#kode_tipe_klaim').val();
                        var nomor_identitas = $('#nomor_identitas').val();
                        $.ajax({
                            type: 'POST',
                            url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                            data: {
                                "TYPE"                  : "validasiInfoToken",
                                "kode_sisla"            : jdata.kode_sisla,
                                "kode_kantor"           : jdata.kode_kantor,
                                "no_antrian"            : jdata.no_antrian,
                                "tgl_ambil_antrian"     : jdata.tgl_ambil_antrian,
                                "tgl_panggil_antrian"   : jdata.tgl_panggil_antrian,
                                "kode_petugas_antrian"  : jdata.kode_petugas_antrian,
                                "kode_jenis_antrian"    : kode_jenis_antrian,
                                "kode_jenis_antrian_detil" : kode_jenis_antrian_detil,
                                "nomor_identitas"       : nomor_identitas
                            },
                            success: function(dataInfo){
                                console.log(dataInfo);
                                jdataInfo = JSON.parse(dataInfo);				
                                if (jdataInfo.ret == '0') {
                                    var nama_jenis_antrian = "Klaim";
                                    $("#kode_sisla").val(jdata.kode_sisla);
                                    $("#kode_kantor_antrian").val(jdata.kode_kantor);
                                    $("#nama_kantor_antrian").val(jdataInfo.nama_kantor);
                                    $("#no_antrian").val(jdata.no_antrian);
                                    $("#tgl_ambil_antrian").val(jdata.tgl_ambil_antrian);
                                    $("#tgl_panggil_antrian").val(jdata.tgl_panggil_antrian);
                                    $("#kode_petugas_antrian").val(jdata.kode_petugas_antrian);
                                    $("#nama_petugas_antrian").val(jdataInfo.nama_petugas_antrian);
                                    $("#kode_jenis_antrian").val(kode_jenis_antrian);
                                    $("#nama_jenis_antrian").val(nama_jenis_antrian);
                                    if(jdataInfo.nomor_hp !=""){
                                        $("#no_hp_antrian").val(jdataInfo.nomor_hp);
                                    }
                                    if(jdataInfo.email !=""){
                                        $("#email_antrian").val(jdataInfo.email);
                                    }
                                    
                                    $("#path_url_antrian_curr").val(jdataInfo.path_url);
                                    $("#type_file_antrian").val(jdataInfo.type_file);
                                    if(jdataInfo.path_url !=""){
                                        document.getElementById('btn_camera').disabled = true;
                                        document.getElementById('image1').src = '<?=$wsIpStorage;?>'+jdataInfo.path_url;
                                    } else {
                                        document.getElementById('btn_camera').disabled = false;
                                        //document.getElementById('image1').src = "../../images/empty-profile.png";
                                    }
                                    if(jdataInfo.kode_antrian !=""){
                                        $("#no_hp_antrian").attr('readonly', 'readonly');
                                        $("#email_antrian").attr('readonly', 'readonly');
                                    } else {
                                        $("#no_hp_antrian").removeAttr('readonly');
                                        $("#email_antrian").removeAttr('readonly');
                                    }
  
                                } else {
                                    if(jdataInfo.ret == '-2'){
                                        alert ('Peserta sudah pernah dilayani oleh Kantor Cabang ' + toUnicodeVariant(''+jdataInfo.kantorLayanan+'', 'bold sans', 'bold') + ' pada tanggal '+ toUnicodeVariant(''+jdataInfo.tglLayanan+'', 'bold sans', 'bold') +' dan saat ini berstatus Tunda Layanan, mohon untuk memastikan ke Kantor Cabang '+ toUnicodeVariant(''+jdataInfo.kantorLayanan+'', 'bold sans', 'bold') +' terkait tindak lanjut layanan dan token terbaru dapat dibatalkan jika proses layanan tetap dilakukan oleh Kantor Cabang '+ toUnicodeVariant(''+jdataInfo.kantorLayanan+'', 'bold sans', 'bold') +'');
                                        //$('#valid_nik_antrian').val('T');
                                        //reset data tk ------------------------------------------------------------
                                        $('#kpj').val('');
                                        $('#kode_tk').val('');
                                        $('#nama_tk').val('');
                                        $('#nomor_identitas').val('');
                                        $('#jenis_identitas').val('');
                                        $('#kode_kantor_tk').val('');
                                        $('#kode_perusahaan').val('');
                                        $('#npp').val('');
                                        $('#nama_perusahaan').val('');
                                        $('#kode_divisi').val('');
                                        $('#nama_divisi').val('');
                                        $('#no_proyek').val('');
                                        $('#nama_proyek').val('');
                                        $('#kode_proyek').val('');
                                    } else {
                                        alert(jdataInfo.msg);
                                    }
                                    
                                    $("#token_antrian").val("");
                                    $("#kode_sisla").val("");
                                    $("#kode_kantor_antrian").val("");
                                    $("#nama_kantor_antrian").val("");
                                    $("#no_antrian").val("");
                                    $("#tgl_ambil_antrian").val("");
                                    $("#tgl_panggil_antrian").val("");
                                    $("#kode_petugas_antrian").val("");
                                    $("#nama_petugas_antrian").val("");
                                    $("#kode_jenis_antrian").val("");
                                    $("#nama_jenis_antrian").val("");
                                    $("#no_hp_antrian").val("");
                                    $("#email_antrian").val("");
                                    $("#path_url_antrian_curr").val("");
                                    $("#type_file_antrian").val("");
                                    window.document.getElementById("dispInfoToken").innerHTML = jdataInfo.msg;
                                    window.document.getElementById("dispInfoToken").style.display = 'block';
                                    document.getElementById('btn_camera').disabled = true;
                                }
                            },
                            error: function(){
                                alert("Error");
                                //preload(false);
                            },
                            complete: function(){
                                //preload(false);
                            }
                        });
                    } else {
                        alert(jdata.msg);
                        $("#token_antrian").val("");
                        window.document.getElementById("dispInfoToken").innerHTML = jdata.msg;
                        window.document.getElementById("dispInfoToken").style.display = 'block';
                        document.getElementById('btn_camera').disabled = true;
                    }
                },
                error: function(){
                    alert("Error");
                    //preload(false);
                },
                complete: function(){
                    //preload(false);
                }
            });
        }
    }

    function uploadCephFile(file){
        var resultPath = "";
        let formData = new FormData();
        formData.append('TYPE', 'uploadDokumen');
        formData.append('file', file);
        formData.append('kode', kode_antrian);
        $.ajax({
            url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function (data) {
                console.log(data);
                var jdata = JSON.parse(data);
                if (jdata.ret == '0') {
                    resultPath = jdata.pathCephFile;
                } else {
                    resultPath = "";
                    //alert(jdata.msg);
                }
            },
            complete: function(){
                //preload(false);
            },
            error: function(){
                alert("Terjadi kesalahan, coba beberapa saat lagi!");
                //preload(false);
            }
        });
        return resultPath;
    }

    function f_js_upload_foto_wajah(kode_antrian){
        let path_ceph_file = $("#path_url_antrian_curr").val();
        let type_file      = $("#type_file_antrian").val();
        let base64_foto_peserta = $("#base64_foto_peserta").val();
        
        if(path_ceph_file != ""){
            base64_foto_peserta = path_ceph_file;
        }

        if (kode_antrian != "" && base64_foto_peserta !="") {
            if(path_ceph_file == ""){
                let blob_file = blob;
                type_file = blob_file.type;
                path_ceph_file = uploadCephFile(blob_file);
            }
           
            if (path_ceph_file !=""){
                $.ajax({
                    type: 'POST',
                    url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                    data: {
                        "TYPE" : "updateDokAntrian",
                        "kode_antrian" : kode_antrian,
                        "path_file" : path_ceph_file,
                        "file_type" : type_file
                    },
                    success: function (data) {
                        var jdata = JSON.parse(data);
                        console.log(data);
                        if (jdata.ret == '0') {
                            
                        } else {
                            
                        }
                    },
                    error: function(){
                        
                    },
                    complete: function(){
                        //preload(false);
                    }
                });
            }
        }
    }

    function fl_js_val_email_antrian(v_field_id)
    {
        var x = window.document.getElementById(v_field_id).value;
        var atpos=x.indexOf("@");
        var dotpos=x.lastIndexOf(".");
        if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
        {
        document.getElementById(v_field_id).value = '';				 
            window.document.getElementById(v_field_id).focus();
            alert("Format Email tidak valid, belum ada (@ DAN .)");         
            return false; 	
        }
    }

    function fl_js_val_numeric_antrian(v_field_id)
    {
        var c_val = window.document.getElementById(v_field_id).value;
        var number=/^[0-9]+$/;
        
        if ((c_val!='') && (!c_val.match(number)))
        {
        document.getElementById(v_field_id).value = '';				 
        window.document.getElementById(v_field_id).focus();
        alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
        return false; 				 
        }		
    }

    function toUnicodeVariant(str, variant, flags) {
        const offsets = {
            m: [0x1d670, 0x1d7f6],
            b: [0x1d400, 0x1d7ce],
            i: [0x1d434, 0x00030],
            bi: [0x1d468, 0x00030],
            c: [0x1d49c, 0x00030],
            bc: [0x1d4d0, 0x00030],
            g: [0x1d504, 0x00030],
            d: [0x1d538, 0x1d7d8],
            bg: [0x1d56c, 0x00030],
            s: [0x1d5a0, 0x1d7e2],
            bs: [0x1d5d4, 0x1d7ec],
            is: [0x1d608, 0x00030],
            bis: [0x1d63c, 0x00030],
            o: [0x24B6, 0x2460],
            p: [0x249C, 0x2474],
            w: [0xff21, 0xff10],
            u: [0x2090, 0xff10]
        }

        const variantOffsets = {
            'monospace': 'm',
            'bold': 'b',
            'italic': 'i',
            'bold italic': 'bi',
            'script': 'c',
            'bold script': 'bc',
            'gothic': 'g',
            'gothic bold': 'bg',
            'doublestruck': 'd',
            'sans': 's',
            'bold sans': 'bs',
            'italic sans': 'is',
            'bold italic sans': 'bis',
            'parenthesis': 'p',
            'circled': 'o',
            'fullwidth': 'w'
        }

        // special characters (absolute values)
        var special = {
            m: {
                ' ': 0x2000,
                '-': 0x2013
            },
            i: {
                'h': 0x210e
            },
            g: {
                'C': 0x212d,
                'H': 0x210c,
                'I': 0x2111,
                'R': 0x211c,
                'Z': 0x2128
            },
            o: {
                '0': 0x24EA,
                '1': 0x2460,
                '2': 0x2461,
                '3': 0x2462,
                '4': 0x2463,
                '5': 0x2464,
                '6': 0x2465,
                '7': 0x2466,
                '8': 0x2467,
                '9': 0x2468,
            },
            p: {},
            w: {}
        }
        //support for parenthesized latin letters small cases 
        for (var i = 97; i <= 122; i++) {
            special.p[String.fromCharCode(i)] = 0x249C + (i - 97)
        }
        //support for full width latin letters small cases 
        for (var i = 97; i <= 122; i++) {
            special.w[String.fromCharCode(i)] = 0xff41 + (i - 97)
        }

        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';

        var getType = function (variant) {
            if (variantOffsets[variant]) return variantOffsets[variant]
            if (offsets[variant]) return variant;
            return 'm'; //monospace as default
        }
        var getFlag = function (flag, flags) {
            if (!flags) return false
            return flags.split(',').indexOf(flag) > -1
        }

        var type = getType(variant);
        var underline = getFlag('underline', flags);
        var strike = getFlag('strike', flags);
        var result = '';

        for (var k of str) {
            let index
            let c = k
            if (special[type] && special[type][c]) c = String.fromCodePoint(special[type][c])
            if (type && (index = chars.indexOf(c)) > -1) {
                result += String.fromCodePoint(index + offsets[type][0])
            } else if (type && (index = numbers.indexOf(c)) > -1) {
                result += String.fromCodePoint(index + offsets[type][1])
            } else {
                result += c
            }
            if (underline) result += '\u0332' // add combining underline
            if (strike) result += '\u0336' // add combining strike
        }
        return result
    }

    function f_js_val_nik_antrian(nomor_identitas){
        let kode_tipe_klaim = $('#kode_tipe_klaim').val();
        if (nomor_identitas ==''){
            let nomor_identitas = $('#nomor_identitas').val();
        }
        let kode_jenis_antrian = "SA01";
        let kode_jenis_antrian_detil = kode_jenis_antrian+""+kode_tipe_klaim;
        if(kode_tipe_klaim != '' && nomor_identitas != ''){
            $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
                data: {
                    "TYPE" : "getValidNikAntrian",
                    "nomor_identitas" : nomor_identitas,
                    "kode_jenis_antrian"    : kode_jenis_antrian,
                    "kode_jenis_antrian_detil" : kode_jenis_antrian_detil
                },
                success: function (data) {
                    var jdata = JSON.parse(data);
                    //console.log(data);
                    if (jdata.ret == '-1') {
                        //$('#valid_nik_antrian').val('T');
                        alert ('Peserta sudah pernah dilayani oleh Kantor Cabang ' + toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') + ' pada tanggal '+ toUnicodeVariant(''+jdata.tglLayanan+'', 'bold sans', 'bold') +' dan saat ini berstatus Tunda Layanan, mohon untuk memastikan ke Kantor Cabang '+ toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') +' terkait tindak lanjut layanan dan token terbaru dapat dibatalkan jika proses layanan tetap dilakukan oleh Kantor Cabang '+ toUnicodeVariant(''+jdata.kantorLayanan+'', 'bold sans', 'bold') +'');
                        //reset data tk ------------------------------------------------------------
                        $('#kpj').val('');
                        $('#kode_tk').val('');
                        $('#nama_tk').val('');
                        $('#nomor_identitas').val('');
                        $('#jenis_identitas').val('');
                        $('#kode_kantor_tk').val('');
                        $('#kode_perusahaan').val('');
                        $('#npp').val('');
                        $('#nama_perusahaan').val('');
                        $('#kode_divisi').val('');
                        $('#nama_divisi').val('');
                        $('#no_proyek').val('');
                        $('#nama_proyek').val('');
                        $('#kode_proyek').val('');
                    } else {
                    }
                },
                error: function(){
                    
                },
                complete: function(){
                    //preload(false);
                }
            });
        }
    }
    
</script>
