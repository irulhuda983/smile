<?php
require_once "../../includes/header_app_nosql.php"; // pn5077_form_detail.php
require_once "../../includes/conf_global.php"; // pn5077_form_detail.php
require_once "../../includes/class_database.php"; // pn5077_form_detail.php
require_once '../../includes/fungsi_newrpt.php'; // pn5077_form_detail.php
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

$USER_pn5077_detail         = $_SESSION["USER"];
$NAMA_pn5077_detail         = $_SESSION["NAMA"];

$formAjax_pn5077_detail = "../ajax/pn5077_action.php?"; # inisialisasikan lokasi file ajax
$formUtama_pn5077_detail = "/mod_pn/form/pn5077.php"; # inisialisasikan lokasi file utama
$formDetail_pn5077_detail = "/mod_pn/form/pn5077_form_detail.php"; # inisialisasikan lokasi file detail


function checkParam_pn5077_detail($key)
{
    return (isset($_GET[$key]) && !empty($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : NULL));
}

function encrypt_decrypt_pn5077_detail($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt_pn5077_detail('encrypt', $string);
     * DECRYPTION: encrypt_decrypt_pn5077_detail('decrypt', $string) ;
     */
    $output_pn5077_detail = false;
    $encrypt_method_pn5077_detail = "AES-256-CBC";
    $secret_key_pn5077_detail = 'WS-SERVICE-KEY';
    $secret_iv_pn5077_detail = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key_pn5077_detail);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv_pn5077_detail), 0, 16);
    if ($action == 'encrypt') {
        $output_pn5077_detail = base64_encode(openssl_encrypt($string, $encrypt_method_pn5077_detail, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output_pn5077_detail = openssl_decrypt(base64_decode($string), $encrypt_method_pn5077_detail, $key, 0, $iv);
        }
    }
    return $output_pn5077_detail;
}

$kode_fiktif_klaim  = htmlspecialchars(checkParam_pn5077_detail('kode_fiktif_klaim'));
$status_proses	    = htmlspecialchars(checkParam_pn5077_detail('status_proses'));
$search_by			= htmlspecialchars(checkParam_pn5077_detail('search_by'));
$search_txt			= htmlspecialchars(checkParam_pn5077_detail('search_txt'));
$gs_pagetitle 		= "PN5077 - Verifikasi Potensi Klaim Fiktif - " . ($status_proses == 'belum' ? 'Belum Diproses' : 'Sudah Diproses');
$disabled           = ($status_proses == 'sudah' ? 'disabled' : 'required');
$ls_status_valid_rekening_penerima = 'T';
?>

<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" media="all" href="../../javascript/chosen_v1.8.7/chosen.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../../style/kn_style.css?abc">
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<!-- custom css -->
<link href="../style.custom.css?a=<?=random_int(0,999999999)?>" rel="stylesheet" />
<!-- js async loader starts here -->
<script type="text/javascript">
    let _asyncAwaitFn = [];
    let _asyncPreloadRunning = [];
    let _asyncPreload;

    window.asyncPreload = function(idloader, state) {
        idloader = idloader || 'data';
        if (state) {
            _asyncPreloadRunning[idloader] = state;
        } else {
            delete _asyncPreloadRunning[idloader];
        }
    }

    window.asyncPreloadStart = function() {
        _asyncPreload = setInterval(function() {
            try {
                if (_asyncPreloadRunning) {
                    if (Object.keys(_asyncPreloadRunning).length > 0) {
                        preload(true);
                    } else {
                        let fn = _asyncAwaitFn.shift();
                        if (fn && typeof fn === 'function') {
                            fn();
                        } else {
                            preload(false);
                        }
                    }
                }
            } catch (e) {
                window.asyncPreloadEnd();
            }
        }, 100)
    }

    window.asyncPreloadEnd = function() {
        preload(false);
        clearInterval(_asyncPreload);
    }

    window.asyncAwaitFn = function(fn) {
        _asyncAwaitFn.push(fn);
    }

    window.asyncPreloadStart();
    </script>
    <!-- js async loader ends here -->

    <!-- js common function starts here -->
    <script language="javascript">
    function getValue(val) {
        return val == null || val == undefined ? '' : val;
    }

    function resubmit(formName = 'formreg') {
        $(`#${formName}`).submit();
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

    function showFormCallback(mypage, myname, w, h, scroll) {
        let openwin = window.parent.Ext.create('Ext.window.Window', {
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
                    if (typeof callback === 'function') {
                        callback();
                    }
                    filter();
                },
                destroy: function(wnd, eOpts) {}
            }
        });
        openwin.show();
        return openwin;
    }
</script>
<!-- js common function endS here -->

<!-- js function starts here -->
<script language="javascript">
    // untuk datatable
    let active_window = window.parent.Ext.WindowManager.getActive();
    let active_window_height = active_window ? active_window.height : 0;
    if (active_window) {
        active_window.on('resize', function() {
            let default_height_table = Number($('#div-container-table').attr('data-height'));
            let active_window_height_now = Number(active_window.height);
            let active_window_height_diff = Number(active_window_height_now) - Number(active_window_height);

            if (active_window.maximized) {
                $('#div-container-table').css('height', default_height_table);
            } else {
                $('#div-container-table').css('height', default_height_table + active_window_height_diff);
            }
        });
    }

    function arrayRemove(arr, value) {
        return arr.filter(function(ele) {
            return ele != value;
        });
    }

    function renumbered() {
        $('#data_list > tr').each(function(index, tr) {
            let idx_tr = $(tr).attr('idx');
            $('#no_tr' + idx_tr).html(index + 1);
        });
    }
</script>
<!-- js function ends here -->
<script language="javascript">
    $(document).ready(function() {
        $("input[type=text]").keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
        $(window).bind("resize", function() {
            resize();
        });
        resize('');
        submitActive()
        setTimeout(function() {
            filter();
        }, 500);

        $('#formSubmit').submit(function(e) {
            e.preventDefault();
            var pengecekan_kasus = $('#pengecekan_kasus').find(':selected').val();
            if (pengecekan_kasus == '') {
                alert('Pengecekan Kasus tidak boleh kosong');
                return;
            }
            
            var tgl_verifikasi_kasus = $('#tgl_verifikasi_kasus').val();
            if (tgl_verifikasi_kasus == '') {
                alert('Tanggal Verifikasi Kasus tidak boleh kosong');
                return;
            }
            
            var hasil_verifikasi_kasus = $('#hasil_verifikasi_kasus').find(':selected').val();
            if (hasil_verifikasi_kasus == '') {
                alert('Hasil Verifikasi Kasus tidak boleh kosong');
                return;
            }

            var keterangan_verifikasi_kasus = $('#keterangan_verifikasi_kasus').val();
            if (keterangan_verifikasi_kasus == '') {
                alert('Keterangan Verifikasi Kasus tidak boleh kosong');
                return;
            }
            
            var url = "<?=$formAjax_pn5077_detail?>" + Math.random()
            var formData = new FormData(this)
            var tipe = 'submitData';
            formData.append('tipe', tipe);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {
                    $('#btn_submit').html(
                        '<i id="spinn" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"> LOADING...</span>'
                        )
                    $('#btn_submit').attr('disabled', '');
                },
                success: function(data) {
                    $('#btn_submit').removeAttr('disabled');
                    $('#btn_submit').removeClass('btn green');
                    $('#btn_submit').html('Submit');
                    alert(data.msg)
                    var kode_fiktif_klaim = data.kode_fiktif_klaim
                    var url = 'http://<?= $HTTP_HOST; ?><?=$formDetail_pn5077_detail?>?kode_fiktif_klaim=' + kode_fiktif_klaim + '&status_proses=sudah&search_by=&search_txt=';
                    window.location.href = url
                },
                error: function(data) {
                    $('#btn_submit').removeAttr('disabled');
                    $('#btn_submit').html('Submit');
                    data1 = JSON.parse(data.responseText)
                    alert(data1.msg)
                }
            });
        });

    });

    function resize(i)
    {
        $("#div_header").width($("#div_dummy").width());
        $("#div_body").width($("#div_dummy").width());
        $("#div_footer").width($("#div_dummy").width());

        $("#div_filter").width(0);
        $("#div_data").width(0);
        $("#div_page").width(0);

        $("#div_filter").width($("#div_dummy_data").width());
        $("#div_data").width($("#div_dummy_data").width());
        $("#div_page").width($("#div_dummy_data").width());

        $("#div_container").css('max-height', $(window).height());

        let margin_height = 40;
        let filter_height = $('#div_body').height() - $('#div_data').height() + margin_height;
        $('#div_data').css('max-height', $(window).height() - $('#div_header').height() - $('#div_page').height() - $(
            '#div_footer').height() - filter_height);
    }

    function getValue(val) {
        return val == null ? '' : val;
    }

    function filter() {

        window.asyncPreload('filter', true);
        var url = "<?=$formAjax_pn5077_detail?>" + Math.random()
        var kode_fiktif_klaim = "<?=encrypt_decrypt_pn5077_detail('encrypt', $kode_fiktif_klaim)?>"
        var status_proses = "<?=$status_proses?>"
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                tipe: 'select_detail',
                kode_fiktif_klaim: kode_fiktif_klaim,
                status_proses: status_proses

            },
            dataType: "JSON",
            success: function(data) {
                setData(data)
                window.asyncPreload('filter', false);
            },
            complete: function() {
                window.asyncPreload('filter', false);
            },
            error: function() {
                alert("Terjadi kesalahan, coba beberapa saat lagi!");
                window.asyncPreload('filter', false);
            }
        });
    }

    function setData(jdata) {
        var status_proses = "<?=$status_proses?>"
        var row = jdata.data
        if (jdata.ret == 0) {
            $('#kode_fiktif_klaim').val(row.KODE_FIKTIF_KLAIM)
            var nomor_identitas_antrian = `<a href="javascript:void(0)" id="btn_nomor_identitas_antrian" onclick="newWindow('${row.ID_POINTER_ASAL}', '${row.KODE_POINTER_ASAL}', '${row.JENIS_ANTRIAN ?? null}', '${row.KODE_TIPE_PENERIMA ?? null}')" title="Klik untuk melihat window Proses Layanan Antrian"><span style="display:block;color:#0080FF;"> <b>${row.ID_POINTER_ASAL}</b></span></a>`;
            $('#nomor_identitas_antrian').html(nomor_identitas_antrian)
            $('#tgl_rekam_pengajuan').val(row.TGL_REKAM_PENGAJUAN)
            $('#petugas_pengajuan').val(row.PETUGAS_REKAM_PENGAJUAN)
            $('#nama_petugas').val(row.NAMA_PETUGAS)
            $('#kantor_pengajuan').val(row.KODE_KANTOR_PENGAJUAN)
            $('#nama_kantor').val(row.NAMA_KANTOR)
            $('#status_approval').val(row.NAMA_STATUS_PENGAJUAN)

            $('#status_pelapor').val(row.STATUS_PELAPOR)


            var dataKpj = row.dataKpj
            if (dataKpj.length > 0) {
                var html_kpj = "";
                var num = 0;
                for (var i = 0; i < dataKpj.length; i++) {
                    var no = num + 1;
                    var rowPer = dataKpj[i];
                    html_kpj += '<tr>';
                    html_kpj += '<td style="text-align: center;">' + no + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.KODE_TK ? rowPer.KODE_TK : '') + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.KPJ ? rowPer.KPJ : '') + '</td>';
                    html_kpj += '<td style="text-align: left;">' + (rowPer.NAMA_TK ? rowPer.NAMA_TK : '') + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.NIK ? rowPer.NIK : '') + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.TGL_LAHIR ? rowPer.TGL_LAHIR : '') + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.KODE_DIVISI ? rowPer.KODE_DIVISI : '') + '</td>';
                    html_kpj += '<td style="text-align: center;">' + (rowPer.NPP ? rowPer.NPP : '') + '</td>';
                    html_kpj += '<td style="text-align: left;">' + (rowPer.NAMA_PERUSAHAAN ? rowPer.NAMA_PERUSAHAAN : '') + '</td>';
                    num++;
                }
            } else {
                html_kpj += '<tr class="nohover-color">';
                html_kpj += '<td colspan="9" style="text-align: center;">-- Data tidak ditemukan --</td>';
                html_kpj += '</tr>';
            }
            $("#tbDataKPJ").html(html_kpj);

            var dataDokumen = row.dataDokumen
            if (dataDokumen.length > 0) {
                var html_dokumen = "";
                var num = 0;
                for (var i = 0; i < dataDokumen.length; i++) {
                    var no = num + 1;
                    var rowDoc = dataDokumen[i];
                    var aksi_dokumen = '<div style="text-align: center; width:100%">';
                    aksi_dokumen += `<a href="javascript:void(0)" id="dokumenPendukung" onclick="downloadFileSmile('${rowDoc.PATH_URL}')" title="Klik untuk melihat/download dokumen pendukung" style="display: inline-block"><span style="display:block;color:#0080FF;"> <b>Download</b></span></a>`;
                    var sumber = (rowDoc.SUMBER ? rowDoc.SUMBER : '');
                    var kode_dokumen = (rowDoc.KODE_DOKUMEN ? rowDoc.KODE_DOKUMEN : '');
                    if (kode_dokumen == 'D224' && sumber != 'CSO' && status_proses == 'belum') {
                        aksi_dokumen += `<a href="javascript:void(0)" id="hapusDokumen" onclick="hapusFileSmile('${rowDoc.KODE_FIKTIF_KLAIM}','${rowDoc.NAMA_DOKUMEN}','${rowDoc.KODE_DOKUMEN}','${rowDoc.PATH_URL}')" title="Klik Menghapus Dokumen" style="display: inline-block; margin-left:10px"><span style="display:block;color:#0080FF;"> <b>Hapus</b></span></a>`
                    }
                    aksi_dokumen += `</div>`

                    html_dokumen += '<tr>';
                    html_dokumen += '<td style="text-align: center;">' + no + '</td>';
                    html_dokumen += '<td style="text-align: left;">' + (rowDoc.NAMA_DOKUMEN ? rowDoc.NAMA_DOKUMEN : '') + '</td>';
                    html_dokumen += '<td style="text-align: left;">' + (rowDoc.KETERANGAN ? rowDoc.KETERANGAN : '') + '</td>';
                    html_dokumen += '<td style="text-align: center;">' + (rowDoc.TGL_UPLOAD ? rowDoc.TGL_UPLOAD : '') + '</td>';
                    html_dokumen += '<td style="text-align: center;">' + sumber + '</td>';
                    html_dokumen += '<td style="text-align: center;">'+aksi_dokumen+'</td>';
                    num++;
                }
            } else {
                html_dokumen += '<tr class="nohover-color">';
                html_dokumen += '<td colspan="6" style="text-align: center;">-- Data tidak ditemukan --</td>';
                html_dokumen += '</tr>';
            }
            $("#tbDataDokumen").html(html_dokumen);

            var dataKodeVerifikasi = row.dataKodeVerifikasi
            if (dataKodeVerifikasi.length > 0) {
                var html_kode_var = `<option value="" selected disabled>-- Pilih --</option>`;
                for (var iv = 0; iv < dataKodeVerifikasi.length; iv++) {
                    var rowKodV = dataKodeVerifikasi[iv];
                    html_kode_var += `<option value="${rowKodV.KODE_VERIFIKASI}">${rowKodV.NAMA_VERIFIKASI}</option>`;
                }
                $('#hasil_verifikasi_kasus').html(html_kode_var)
            }

            if (row.FLAG_PERPANJANGAN_SLA == 'Y') {
                $('#view_tgl_perpanjangan').show()
            } else {
                $('#view_tgl_perpanjangan').hide()
            }

            if (status_proses == 'sudah') {
                $('#pengecekan_kasus').val(row.FLAG_CEK_KASUS)
                $('#hasil_verifikasi_kasus').val(row.KODE_VERIFIKASI)
                $('#perpanjangan_verifikasi_kasus').val(row.FLAG_PERPANJANGAN_SLA)
                $('#perpanjangan_verifikasi_kasus_v').val(row.FLAG_PERPANJANGAN_SLA)
                $('#keterangan_verifikasi_kasus').text(row.KETERANGAN_VERIFIKASI)

                $('#tgl_verifikasi_kasus').val(row.TGL_VERIFIKASI_KASUS)
                $('#tgl_sla_verifikasi_kasus').val(row.TGL_SLA1_VERIFIKASI_KASUS)
                $('#tgl_sla_perpanjangan_verifikasi_kasus').val(row.TGL_SLA2_VERIFIKASI_KASUS)

                if (row.STATUS_APPROVAL == 'Y') {
                    status_approval = 'DISETUJUI';
                } else if (row.STATUS_APPROVAL == 'R') {
                    status_approval = 'DITOLAK';
                } else {
                    status_approval = 'MENUNGGU PERSETUJUAN';
                }
                $('#status_persetujuan').val(status_approval)
                $('#tgl_persetujuan').val(row.TGL_APPROVAL)
                $('#petugas_persetujuan').val(row.PETUGAS_APPROVAL)
                $('#nama_petugas_persetujuan').val(row.NAMA_PETUGAS_APPROVAL)
                $('#keterangan_persetujuan').html(row.KETERANGAN_APPROVAL)

                $('#petugas_verifikasi').val(row.PETUGAS_VERIFIKASI_KASUS)
                $('#nama_petugas_verifikasi').val(row.NAMA_PETUGAS_VERIFIKASI_KASUS)
            } else {
                $('#perpanjangan_verifikasi_kasus_v').val(row.FLAG_PERPANJANGAN_SLA)
                $('#perpanjangan_verifikasi_kasus').val(row.FLAG_PERPANJANGAN_SLA)

                $('#hasil_verifikasi_kasus').val(row.KODE_VERIFIKASI)
                $('#tgl_verifikasi_kasus').val('<?=date('d-m-Y')?>')
                $('#petugas_verifikasi').val('<?=$USER_pn5077_detail?>')
                $('#nama_petugas_verifikasi').val('<?=$NAMA_pn5077_detail?>')
                $('#tgl_sla_perpanjangan_verifikasi_kasus').val(row.TGL_SLA2_VERIFIKASI_KASUS)
                $('#tgl_sla_verifikasi_kasus').val(row.TGL_SLA1_VERIFIKASI_KASUS)
            }

        } else {
            alert(jdata.msg);
        }
    }

    function submitActive() {
        var setuju = $('#setuju:checked').val();
        if (setuju == 'Y') {
            $('#btn_submit').removeAttr('disabled');
            $('#btn_submit').addClass('btn green');
        } else {
            $('#btn_submit').attr('disabled', '');
            $('#btn_submit').removeClass('btn green');
        }
    }

    function kembali() {
        var url = 'http://<?= $HTTP_HOST; ?><?=$formUtama_pn5077_detail?>?former_search_by=' + '<?=$search_by?>' +
            '&former_status_proses=' + '<?=$status_proses?>' + '&former_search_txt=' + '<?=$search_txt?>';
        window.location.replace(url);
    }

    function newWindow(no_antrian, kode_antrian, jenis_antrian = null, kode_tipe_penerima = null)
    {
        var url = "http://<?=$HTTP_HOST?>/mod_il/ajax/il1001_detil.php?kode_antrian="+no_antrian+"&sender=PN5077";
        if(kode_antrian.includes('EC5013')){
            url = "http://<?=$HTTP_HOST?>/mod_ec/form/ec5013_detail.php?kode_booking="+no_antrian+"&sender=PN5077";
        } else if (kode_antrian.includes('EC5095')){
            url = "http://<?=$HTTP_HOST?>/mod_ec/ajax/ec5094_detail.php?kode_booking="+no_antrian+"&status_piloting=Y&jenis_antrian="+jenis_antrian+"&kode_tipe_penerima="+kode_tipe_penerima+"&sender=PN5077";
        }
        
        NewWindow(url,'',900,2000,1);
    }

    function downloadFileSmile(path_url){
        var url = "<?=$formAjax_pn5077_detail?>" + Math.random()
        let endPoint= "<?php echo $wsFileSmile ?>";
        preload(true);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                tipe        : 'downloadFileSmile',
                path_url    : path_url
            },     
            success: function(data){     
                let jdata = JSON.parse(data);
                NewWindow(endPoint+"/krgBayar/downloadFileKB?data="+jdata.pathUrlEncrypt);
                preload(false);
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
    
    function hapusFileSmile(kode_fiktif_klaim, nama_dokumen, kode_dokumen, path_url){
        let konfirmasi = "Apakah anda yakin akan menghapus file ini?";
        confirmation("Konfirmasi", konfirmasi,
            async function() {
                await hapusFile(kode_fiktif_klaim, nama_dokumen, kode_dokumen, path_url);
            },
        setTimeout(function() {}, 1000));
    }

    function hapusFile(kode_fiktif_klaim, nama_dokumen, kode_dokumen, path_url)
    {
        window.asyncPreload('filter', true);
        var url = "<?=$formAjax_pn5077_detail?>" + Math.random()
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                tipe        : 'deleteDokumenLain',
                kode_fiktif_klaim    : kode_fiktif_klaim,
                nama_dokumen    : nama_dokumen,
                kode_dokumen    : kode_dokumen,
                path_url    : path_url,
            },     
            success: function(data){     
                let jdata = JSON.parse(data);
                if (jdata.ret == 0) {
                    filter()
                    alert(jdata.msg)
                } else {
                    alert(jdata.msg);
                }
                window.asyncPreload('filter', false);
            },
            complete: function(){
                window.asyncPreload('filter', false);
            },
            error: function(){
                alert("Terjadi kesalahan, coba beberapa saat lagi!");
                window.asyncPreload('filter', false);
            }
        });
    }

    function tambahDokumenLain(){
        let kode_fiktif_klaim = "<?php echo $kode_fiktif_klaim; ?>";
        var params = "&kode_fiktif_klaim=" + kode_fiktif_klaim;
        showFormReload('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5077_upload_dokumen_la.php?' + params, "FORM UPLOAD DOKUMEN LAINNYA", 550, 300, scroll);
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					filter();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
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

    .l_frm {
        clear: left;
        float: left;
        margin-bottom: 2px;
        text-align: right;
        margin-right: 2px;
    }

    .r_frm {
        float: left;
        margin-bottom: 2px;
    }

    .r_frm input,
    .r_frm select {
        border-radius: 2px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border: 1px solid #bbb;
    }

    .column {
        float: left;
        padding: 1px;
        /*height: 200px; /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    input.button-accept {
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        background-color: #0091FF;
        /*background-image: url(../../images/app_form_edit.png);*/
        background-image: url(../../images/accept.png);
        background-position: 0px 0px;
        background-repeat: no-repeat;
        padding: 0px 20px 12px 40px;
    }

    input.button-close {
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        background-color: #0091FF;
        background-image: url(../../images/cancel.png);
        /*background-size: 20px;*/
        background-position: 0px 0px;
        background-repeat: no-repeat;
        padding: 0px 20px 12px 40px;
    }

    input.button-proses {
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        background-color: #0091FF;
        background-image: url(../../images/proses.png);
        background-position: 0px 0px;
        background-repeat: no-repeat;
        padding: 0px 20px 12px 40px;
    }

    input.button-verif {
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        background-color: #0091FF;
        background-image: url(../../images/open.gif);
        background-position: 0px 0px;
        background-repeat: no-repeat;
        padding: 0px 20px 12px 40px;
    }

    input.button-back {
        width: 100%;
        height: 30px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        background-color: #0091FF;
        background-image: url(../../images/application_view_columns.png);
        background-position: 0px 0px;
        background-repeat: no-repeat;
        padding: 0px 20px 12px 40px;
    }

    .input-disabled {
        background-color: #f5f5f5;
        /* color: rgb(170, 170, 170); */
        color: black;
    }
</style>

<div id="actmenu">
    <h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>
</div>
<div id="formframe">
    <div id="div_dummy" style="width: 100%;"></div>
    <div id="formKiri">
        <div id="div_container" class="div-container">
            <div id="div_header" class="div-header">
            </div>
            <div id="div_body" class="div-body">
                <div style="width: 100%;"></div>
                <br>
                <div class="div-data">
                    <div id="konten" style="width: 100%;">
                        <form id="formSubmit">
                            <fieldset>
                                <legend style="color:#0080FF !important">Potensi Klaim Fiktif</legend>
                                <div class="div-row">
                                    <div class="div-col" style="width:49%; max-height: 100%;">
                                        <div class="l_frm" style="width:220px"><label for="status_pelapor">Status Pelapor</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="status_pelapor" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>                                        
                                        <div class="l_frm" style="width:220px"><label for="kode_fiktif_klaim">Kode Fiktif Klaim</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kode_fiktif_klaim" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="nomor_identitas_antrian">Kode Booking/Nomor Antrian</label></div>
                                        <div class="r_frm" id="nomor_identitas_antrian">

                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="tgl_rekam_pengajuan">Tanggal Pengajuan</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="tgl_rekam_pengajuan" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="petugas_pengajuan">Petugas Pengajuan</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="petugas_pengajuan" name="petugas_pengajuan"
                                                style="width: 80px;" disabled>
                                            <input type="text" id="nama_petugas" name="nama_petugas"
                                                style="width: 211px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="kantor_pengajuan">Kantor Pengajuan</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kantor_pengajuan" name="kantor_pengajuan"
                                                style="width: 50px;" disabled>
                                            <input type="text" id="nama_kantor" name="nama_kantor" style="width: 241px;"
                                                disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="status_approval">Status Pengajuan</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="status_approval" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend style="color:#0080FF !important">Daftar KPJ</legend>
                                <div class="row">
                                    <div>
                                        <table style="width:100%;" class="table-data2" aria-describedby="tableDataKPJ">
                                            <thead>
                                                <tr class="hr-double">
                                                    <th style="text-align: center;" width="10">No</th>
                                                    <th style="text-align: center;" width="120">Kode TK</th>
                                                    <th style="text-align: center;" width="120">KPJ</th>
                                                    <th style="text-align: left;" width="150">Nama TK</th>
                                                    <th style="text-align: center;" width="120">NIK</th>
                                                    <th style="text-align: center;" width="70">Tanggal <br> Lahir</th>
                                                    <th style="text-align: center;" width="70">Kode Divisi</th>
                                                    <th style="text-align: center;" width="70">NPP</th>
                                                    <th style="text-align: left;" width="200">Nama Perusahaan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbDataKPJ">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend style="color:#0080FF !important">Daftar Dokumen Verifikasi/Tindak Lanjut Kasus</legend>
                                <div class="row">
                                    <div>
                                        <table style="width:100%;" class="table-data2" aria-describedby="tableDataDokumen">
                                            <thead>
                                                <tr class="hr-double">
                                                    <th style="text-align: center;" width="10">No</th>
                                                    <th style="text-align: left;" width="120">Nama Dokumen</th>
                                                    <th style="text-align: left;" width="250">Keterangan</th>
                                                    <th style="text-align: center;" width="50">Tanggal Upload</th>
                                                    <th style="text-align: center;" width="50">Sumber</th>
                                                    <th style="text-align: center;" width="200">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbDataDokumen">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php if($status_proses == 'belum') { ?>
                                    <div class="" style="width:100%; text-align:right;margin-top:10px; margin-right:20px">
                                        <input type="button" name="btn_tambah_dokumen" style="width: 120px" id="btn_tambah_dokumen" class="btn green" value="TAMBAH DOKUMEN" onclick="tambahDokumenLain()" />
                                    </div>
                                <?php } ?>
                            </fieldset>
                            <fieldset>
                                <legend style="color:#0080FF !important">Verifikasi/Tindak Lanjut Kasus</legend>
                                <div class="row">
                                    <div class="column">
                                        <div class="l_frm" style="width:220px"><label for="kode_bank_penerima">Pengecekan Kasus <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label></div>
                                        <div class="r_frm row">
                                            <select name="pengecekan_kasus" id="pengecekan_kasus" style="width: 305px;" <?=($status_proses == 'sudah' ? 'disabled' : '')?>>
                                                <option value="" selected disabled>-- Pilih --</option>
                                                <option value="Y">YA</option>
                                                <option value="T">TIDAK</option>
                                            </select>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="tgl_sla_verifikasi_kasus">Tanggal SLA Verifikasi Kasus</label></div>
                                        <div class="r_frm">
                                            <input type="text" class="input-disabled" name="tgl_sla_verifikasi_kasus" id="tgl_sla_verifikasi_kasus" style="width: 300px;" readonly>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="perpanjangan_verifikasi_kasus">Perpanjangan Verifikasi Kasus</label></div>
                                        <div class="r_frm">
                                            <select class="input-disabled" name="perpanjangan_verifikasi_kasus_v" id="perpanjangan_verifikasi_kasus_v" style="width: 305px;" disabled>
                                                <option value="T">TIDAK</option>
                                                <option value="Y">YA</option>
                                            </select>
                                            <input type="hidden" name="perpanjangan_verifikasi_kasus" id="perpanjangan_verifikasi_kasus">
                                        </div>
                                        <div id="view_tgl_perpanjangan">
                                            <div class="div-row-clear"></div>
                                            <div class="l_frm" style="width:220px"><label for="tgl_sla_perpanjangan_verifikasi_kasus">Tanggal SLA Perpanjangan Verifikasi Kasus</label></div>
                                            <div class="r_frm row">
                                                <input type="text" class="input-disabled" name="tgl_sla_perpanjangan_verifikasi_kasus" id="tgl_sla_perpanjangan_verifikasi_kasus" style="width: 300px;" readonly>
                                            </div>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="tgl_verifikasi_kasus">Tanggal Verifikasi Kasus <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label></div>
                                        <div class="r_frm row">
                                            <input type="text" class="input-disabled" name="tgl_verifikasi_kasus" id="tgl_verifikasi_kasus" style="width: 300px;" readonly>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="petugas_verifikasi">Petugas Verifikasi</label></div>
                                        <div class="r_frm row">
                                            <input type="text" class="input-disabled" name="petugas_verifikasi" id="petugas_verifikasi" style="width: 80px;" readonly>
                                            <input type="text" class="input-disabled" name="nama_petugas_verifikasi" id="nama_petugas_verifikasi" style="width: 211px;" readonly>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="hasil_verifikasi_kasus">Hasil Verifikasi Kasus <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label></div>
                                        <div class="r_frm">                                            
                                            <select name="hasil_verifikasi_kasus" id="hasil_verifikasi_kasus" style="width: 305px;" <?=($status_proses == 'sudah' ? 'disabled' : '')?>>
                                            </select>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label for="keterangan_verifikasi_kasus">Keterangan Verifikasi Kasus  <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label></div>
                                        <div class="r_frm row">
                                            <textarea name="keterangan_verifikasi_kasus" id="keterangan_verifikasi_kasus" style="width: 300px;" rows="5" <?=($status_proses == 'sudah' ? 'disabled' : '')?>></textarea>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <?php if ($status_proses == 'belum') { ?>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label
                                                style="text-align:right;">&nbsp;</label></div>
                                        <div class="r_frm">
                                            <input type="checkbox" id="setuju" name="setuju" value="Y"
                                                onclick="submitActive()">
                                            <b style="font-size: 12px;">Dengan mencentang kotak ini, saya telah
                                                memeriksa dan meneliti kebenaran serta keabsahan data yang diinput /
                                                upload</b>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:220px"><label
                                                style="text-align:right;">&nbsp;</label></div>
                                        <div class="r_frm">
                                            <input type="hidden" readonly id="id" name="id"
                                                value="<?=encrypt_decrypt_pn5077_detail('encrypt',$kode_fiktif_klaim)?>">
                                            <input type="submit" name="btn_submit" style="width: 100px" id="btn_submit"
                                                value="SUBMIT" onclick="submitData()" />
                                            <input type="button" name="btn_kembali" style="width: 100px"
                                                class="btn green" id="btn_kembali" value="TUTUP" onclick="kembali()" />
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                            <?php if ($status_proses == 'sudah') { ?>
                                <fieldset>
                                    <legend style="color:#0080FF !important">Persetujuan Verifikasi/Tindak Lanjut Kasus</legend>
                                    <div class="row">
                                        <div class="column">
                                            <div class="l_frm" style="width:220px"><label for="kode_bank_penerima">Status Persetujuan</label></div>
                                            <div class="r_frm row">
                                                <input type="text" class="input-disabled" name="status_persetujuan" id="status_persetujuan" style="width: 300px;" disabled>
                                            </div>
                                            <div class="div-row-clear"></div>
                                            <div class="l_frm" style="width:220px"><label for="tgl_persetujuan">Tanggal Persetujuan</label></div>
                                            <div class="r_frm">
                                                <input type="text" class="input-disabled" name="tgl_persetujuan" id="tgl_persetujuan" style="width: 300px;" disabled>
                                            </div>
                                            <div class="div-row-clear"></div>
                                            <div class="l_frm" style="width:220px"><label for="petugas_persetujuan">Petugas Persetujuan</label></div>
                                            <div class="r_frm">
                                                <input type="text" class="input-disabled" name="petugas_persetujuan" id="petugas_persetujuan" style="width: 80px;" disabled>
                                            <input type="text" class="input-disabled" name="nama_petugas_persetujuan" id="nama_petugas_persetujuan" style="width: 211px;" disabled>
                                            </div>
                                            <div class="div-row-clear"></div>
                                            <div class="l_frm" style="width:220px"><label for="keterangan_persetujuan">Keterangan Persetujuan</label></div>
                                            <div class="r_frm row">
                                                <textarea name="keterangan_persetujuan" id="keterangan_persetujuan" style="width: 300px;" rows="5" disabled></textarea>
                                            </div>
                                            <div class="div-row-clear"></div>
                                            <div class="l_frm" style="width:220px"></div>
                                            <div class="r_frm">
                                                <input type="button" name="btn_kembali" style="width: 100px"
                                                    class="btn green" id="btn_kembali" value="TUTUP" onclick="kembali()" />
                                            </div>
                                            <div class="div-row-clear"></div>
                                        </div>
                                    </div>
                                </fieldset>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once "../../includes/footer_app_nosql.php";
?>