<script type="text/javascript">


	$(document).ready(function()
		{
			
		//	let token_antrian = '<%= Session["token_antrian"] %>';

			 let token_antrian = sessionStorage.getItem("token_antrian");
             let email_antrian = sessionStorage.getItem("email_antrian");
             let no_hp_antrian = sessionStorage.getItem("no_hp_antrian");
             let base64_foto_peserta = sessionStorage.getItem("base64_foto_peserta");
			 console.log(token_antrian);
             console.log(email_antrian);
             console.log(no_hp_antrian);
             console.log(base64_foto_peserta);
            //  let kode_antrian = '<?=$ls_kode_antrian?>';
            //  console.log(kode_antrian);
           

            if(token_antrian.trim() != ""){
                $("#token_antrian").val(token_antrian);
                $("#email_antrian").val(email_antrian);
                $("#no_hp_antrian").val(no_hp_antrian);
                document.getElementById('image1').src = base64_foto_peserta;	
                
                f_js_get_info_token();
            }
          

           

        });	
</script>

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
    // echo $sql;
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
        <input type="password" id="token_antrian" name="token_antrian" style="width:260px;" value ="<?=$ls_token_antrian;?>" <?=($ls_kode_antrian == "")? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\"";?>>
        <!-- <input type="password" id="token_antrian" name="token_antrian" style="width:260px;" value ="<?=$ls_token_antrian;?>" onblur="f_js_get_info_token();" > -->
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
        <input type="text" id="no_hp_antrian" name="no_hp_antrian" value="<?=$ls_no_hp_antrian;?>" style="width:150px;" onblur="fl_js_val_numeric_antrian('no_hp_antrian');" maxlength="15" <?=($ls_kode_antrian == "")? "" : "readonly class=\"disabled\"";?>>
        <!-- <input type="text" id="no_hp_antrian" name="no_hp_antrian" value="<?=$ls_no_hp_antrian;?>" style="width:150px;" onblur="fl_js_val_numeric_antrian('no_hp_antrian');" maxlength="15" > -->
    </div>																																																		
    <div class="clear"></div>
    <div class="form-row_kiri">
        <label  style = "text-align:right;">Tanggal Ambil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="tgl_ambil_antrian" name="tgl_ambil_antrian" value="<?=$ls_tgl_ambil_antrian;?>" style="width:200px;" readonly class="disabled">
        <!-- <input type="text" id="jam_ambil_antrian" name="jam_ambil_antrian" value="<?=$ls_jam_ambil_antrian;?>" style="width:140px;" readonly class="disabled"> -->
    </div>	
    <div class="form-row_kanan">
        <label  style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="email_antrian" name="email_antrian" value="<?=$ls_email_antrian;?>" style="width:150px;" onblur="fl_js_val_email_antrian('email_antrian');" maxlength="200" <?=($ls_kode_antrian == "")? "" : "readonly class=\"disabled\"";?>>
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
       
		<input id="image1" name="image1" type="image" <?=($ls_path_url_foto == "")? " src='../../images/empty-profile.png' " : " src='$ls_path_url_foto' ";?> align="center" style="!important; width: 140px !important; height: 115px; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <!-- <button onclick="openNewWindow(event);">Camera</button> -->
        <button id="btn_camera" name="btn_camera"  onclick="openNewWindow(event);" <?=($ls_kode_antrian != "")? "disabled" : "";?>>Camera</button>
        <textarea type="text" id="base64_foto_peserta" name="base64_foto_peserta" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
    </div>    <textarea type="text" id="path_url_antrian_curr" name="path_url_antrian_curr" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
    <input type="text" id="type_file_antrian" name="type_file_antrian" style="width:100px;" readonly class="disabled"hidden >	
    <!-- <input type="text" id="valid_nik_antrian" name="valid_nik_antrian" value="Y" style="width:70px;" readonly class="disabled"> -->		
    <div class="clear"></div>	 
    </fieldset>
</div> 
<!-- end div formKanan -->

<script type="text/javascript">
    //let newWindow;
    //const response = document.getElementById('response');
    // let imageBase64;
    // let blob;
    // window.addEventListener('message', (event) => {
    //     if(event.data?.msg) {
    //         imageBase64 = event.data.msg;
    //         $("#base64_foto_peserta").val(imageBase64);
    //         console.log(imageBase64);
    //         document.getElementById('image1').src = imageBase64;

    //         var block = imageBase64.split(";");
    //         // Get the content type of the image
    //         var contentType = block[0].split(":")[1];// In this case "image/gif"
    //         // get the real base64 content of the file
    //         var realData = block[1].split(",")[1];

    //         blob = b64toBlob(realData, contentType);
    //         console.log(blob);

    //         // let fileInputElement = document.getElementById('file_input');
    //         // // Here load or generate data
    //         // let data = blob;
    //         // let file = new File([data], "foto.jpeg",{type:"image/jpeg", lastModified:new Date().getTime()});
    //         // let container = new DataTransfer();
    //         // container.items.add(file);
    //         // fileInputElement.files = container.files;
    //         // console.log(fileInputElement.files);

    //         //let blob_file = blob;
    //         // let blob_file_size = 0;
    //         // if (path_file !=""){
    //         //     //file_size = $('#fileToUpload')[0].files[0].size / 1024 / 1024; // size file (MB)
    //         //     blob_file_size = blob.size / 1024 / 1024; // size file (MB)
    //         // }
      
    //     }
    // })
    const openNewWindow = (event) => {
        event.preventDefault()
        const width = 680;
        const height = 560;
        const left = ( screen.width - width ) / 2;
        const top = ( screen.height - height ) / 2;
        const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,left=${left},top=${top},width=${width},height=${height}`;
        newWindow = window.open('<?php echo $appsCam; ?>/camera.html', 'popup', params);
    }

    // function b64toBlob(b64Data, contentType, sliceSize) {
    //     contentType = contentType || '';
    //     sliceSize = sliceSize || 512;

    //     var byteCharacters = atob(b64Data);
    //     var byteArrays = [];

    //     for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    //         var slice = byteCharacters.slice(offset, offset + sliceSize);

    //         var byteNumbers = new Array(slice.length);
    //         for (var i = 0; i < slice.length; i++) {
    //             byteNumbers[i] = slice.charCodeAt(i);
    //         }

    //         var byteArray = new Uint8Array(byteNumbers);

    //         byteArrays.push(byteArray);
    //     }

    //     var blob = new Blob(byteArrays, {type: contentType});
    //     return blob;
    // }

    function f_js_get_info_token(){
        var token_antrian = $("#token_antrian").val();
        var kode_antrian = $("#kode_antrian").val();
      
        if (token_antrian != "" && kode_antrian == "") {
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
                                     if (token_antrian.trim() != "" && jdataInfo.nomor_hp.trim() != "" && jdataInfo.email.trim() != "") {
                                        $("#no_hp_antrian").val(jdataInfo.nomor_hp);	
                                        $("#email_antrian").val(jdataInfo.email);	
                                     }
                                    $("#path_url_antrian_curr").val(jdataInfo.path_url);	
                                    $("#type_file_antrian").val(jdataInfo.type_file);	
                                    if(jdataInfo.path_url.trim() !=""){	
                                        document.getElementById('btn_camera').disabled = true;	
                                        document.getElementById('image1').src = '<?=$wsIpStorage;?>'+jdataInfo.path_url;	
                                    } else {	
                                        let base64_foto_peserta = sessionStorage.getItem("base64_foto_peserta");
                                        if(base64_foto_peserta == ""){
                                            document.getElementById('btn_camera').disabled = false;	
                                            document.getElementById('image1').src = "../../images/empty-profile.png";	
                                        }else{                                            
                                            document.getElementById('btn_camera').disabled = false;	
                                            document.getElementById('image1').src = base64_foto_peserta;	
                                            var block = base64_foto_peserta.split(";");
                                            // Get the content type of the image
                                            var contentType = block[0].split(":")[1];// In this case "image/gif"
                                            // get the real base64 content of the file
                                            var realData = block[1].split(",")[1];
                                            blob = b64toBlob(realData, contentType);
                                            console.log(blob);
                                        }
                                 
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

            // $.ajax({
            //     type: 'POST',
            //     url: 'http://<?=$HTTP_HOST;?>/mod_il/ajax/il1001_entry_action.php?'+Math.random(),
            //     data: {
            //         "TYPE" : "getInfoToken",
            //         "token_antrian" : token_antrian
            //     },
            //     success: function(data){
            //         console.log(data);
            //         jdata = JSON.parse(data);								
            //         if (jdata.ret == '0') {
            //             var kode_jenis_antrian = "SA01";
            //             var nama_jenis_antrian = "Klaim";
            //             $("#kode_sisla").val(jdata.kode_sisla);
            //             $("#kode_kantor_antrian").val(jdata.kode_kantor);
            //             $("#nama_kantor_antrian").val(jdata.nama_kantor);
            //             $("#no_antrian").val(jdata.no_antrian);
            //             $("#tgl_ambil_antrian").val(jdata.tgl_ambil_antrian);
            //             $("#tgl_panggil_antrian").val(jdata.tgl_panggil_antrian);
            //             $("#kode_petugas_antrian").val(jdata.kode_petugas_antrian);
            //             $("#nama_petugas_antrian").val(jdata.nama_petugas_antrian);
            //             $("#kode_jenis_antrian").val(kode_jenis_antrian);
            //             $("#nama_jenis_antrian").val(nama_jenis_antrian);
            //         } else {
            //             alert(jdata.msg);
            //             $("#kode_sisla").val("");
            //             $("#kode_kantor_antrian").val("");
            //             $("#nama_kantor_antrian").val("");
            //             $("#no_antrian").val("");
            //             $("#tgl_ambil_antrian").val("");
            //             $("#tgl_panggil_antrian").val("");
            //             $("#kode_petugas_antrian").val("");
            //             $("#nama_petugas_antrian").val("");
            //             $("#kode_jenis_antrian").val("");
            //             $("#nama_jenis_antrian").val("");
            //             window.document.getElementById("dispInfoToken").innerHTML = "(* Data Token Antrian Tidak Ditemukan ..!!!";
            //             window.document.getElementById("dispInfoToken").style.display = 'block';
            //             //window.document.getElementById('token_antrian').focus();
            //         }
            //     },
            //     error: function(){
            //         alert("Error");
            //         //preload(false);
            //     },
            //     complete: function(){
            //         //preload(false);
            //     }
            // });
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

    
</script>

