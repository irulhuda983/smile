<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once '../../includes/fungsi_newrpt.php';
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

$pagetype     = "form";
$USER         = $_SESSION["USER"];
$KD_KANTOR    = $_SESSION['kdkantorrole'];
$KODE_ROLE    = $_SESSION['regrole'];

$formAjax = "../ajax/pn5075_action.php?"; # inisialisasikan lokasi file ajax
$formUtama = "/mod_pn/form/pn5075.php"; # inisialisasikan lokasi file utama
$formDetail = "/mod_pn/form/pn5075_form_detail.php"; # inisialisasikan lokasi file detail


function checkParam($key)
{
    return (isset($_GET[$key]) && !empty($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : NULL));
}

function checkArray($array, $key)
{
    return (isset($array[$key]) && !empty($array[$key]) ? $array[$key] : NULL);
}

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

function decode_arr($data) {
	return unserialize(base64_decode($data));
}

function open_params($params)
{
	if ($params) {
		return decode_arr(encrypt_decrypt('decrypt', $params));
	}
	return NULL;
}
 
$getParams			= checkParam('params'); # mendapatkan params yg diencrypt
$params				= open_params($getParams);
$kode_klaim			= checkArray($params, 'kode_klaim');
$kode_agenda	    = checkArray($params, 'kode_agenda');
// $kpj				= checkArray($params, 'kpj');
// $nomor_identitas	= checkArray($params, 'nomor_identitas');

$status_proses	    = checkArray($params, 'status_proses');
$search_by			= checkArray($params, 'search_by');
$search_txt			= checkArray($params, 'search_txt');
$gs_pagetitle 		= "PN5075 - Monitoring Koreksi Pembayaran Klaim Return - " . ($status_proses == 'belum' ? 'Belum Doroses' : 'Sudah Diproses');
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
        setTimeout(function() {
            filter();
        }, 500);
        cekPilihBank()

        $("#kode_bank_baru").select2({
            ajax: {
                url: "<?=$formAjax?>" + Math.random(),
                type: "POST",
                dataType: 'JSON',
                delay: 200,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        fn: "<?=encrypt_decrypt('encrypt', 'cariBank')?>",
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#kode_bank_baru').change(function(e) {
            e.preventDefault();
            data = $("#kode_bank_baru").select2('data')[0];
            console.log(data.nama_bank_attribute); //displays hello world
            $('#nama_bank_baru').val(data.nama_bank_attribute)
            cekPilihBank()
            cekValidasiRekening()
            getBankPembayar()
        });

        $('#formSubmit').submit(function(e) {
            e.preventDefault();
            var url = "<?=$formAjax?>" + Math.random()
            var formData = new FormData(this)
            var fn = '<?=encrypt_decrypt('encrypt','submitData')?>';
            formData.append('fn', fn);
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
                    // $('#btn_submit').removeAttr('disabled');
                    $('#btn_submit').removeClass('btn green');
                    $('#btn_submit').html('Submit');
                    $('.form-control').val('')
                    $('.form-control').text('')
                    $('#rincian_pemeriksaan').hide()
                    alert(data.msg)
                    param = data.param
                    var url = 'http://<?= $HTTP_HOST; ?><?=$formDetail?>?params=' + param;
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
    
    function getBankPembayar()
    {
        var kode_bank_baru = $('#kode_bank_baru').find(':selected').val()
        $.ajax({
            type: "POST",
            url: "<?=$formAjax?>" + Math.random(),
            data: {
                fn: "<?=encrypt_decrypt('encrypt', 'cariBankPembayaran')?>",
                kodeBankTujuan: kode_bank_baru,
            },
            dataType: "JSON",
            success: function(data) {
                data = data.data
                $('#kode_bank_pembayar_baru_display').removeAttr('disabled');
                option = '';
                for (let i = 0; i < data.length; i++) {
                    const d = data[i];
                    option +=
                        `<option data-nama="${d.NAMA_BANK}" value="${d.KODE_BANK}">${d.NAMA_BANK}</option>`
                }
                $('#kode_bank_pembayar_baru_display').html(option);
                $('#kode_bank_pembayar_baru_display').select2();
                $('#kode_bank_pembayar_baru_display').attr('disabled', '');
                kode = $('#kode_bank_pembayar_baru_display').find(':selected').val();
                nama_bank = $('#kode_bank_pembayar_baru_display').find(':selected').data('nama');
                $('#kode_bank_pembayar_baru').val(kode)
                $('#nama_bank_pembayar_baru').val(nama_bank)
            }
        });
    }

    function getValue(val) {
        return val == null ? '' : val;
    }

    function filter() {

        window.asyncPreload('filter', true);
        var url = "<?=$formAjax?>" + Math.random()
        var kode_klaim = "<?=encrypt_decrypt('encrypt', $kode_klaim)?>"
        var kode_agenda = "<?=encrypt_decrypt('encrypt', $kode_agenda)?>"
        var status_proses = "<?=$status_proses?>"
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                tipe: 'form_detil',
                fn: "<?=encrypt_decrypt('encrypt', 'getData')?>",
                kode_klaim: kode_klaim,
                kode_agenda: kode_agenda,
                // kpj : kpj,
                // nomor_identitas : nomor_identitas,
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
            $('#kode_klaim').val(row.KODE_KLAIM)
            $('#nomor_identitas').val(row.NOMOR_IDENTITAS)
            $('#nama_tk').val(row.NAMA_TK)
            $('#kpj').val(row.KPJ)
            $('#tgl_klaim').val(row.TGL_KLAIM)
            $('#nama_penerima_manfaat').val(row.NAMA_PENERIMA_MANFAAT)
            $('#tgl_lahir').val(row.TGL_LAHIR)
            $('#nom_manfaat').val(row.NOM_MANFAAT)
            $('#keterangan_retur').text(row.KETERANGAN_RETUR)

            $('#kode_bank_penerima').val(row.KODE_BANK_PENERIMA)
            $('#bank_penerima').val(row.BANK_PENERIMA)
            $('#nama_rekening_penerima').val(row.NAMA_REKENING_PENERIMA)
            $('#no_rekening_penerima').val(row.NO_REKENING_PENERIMA)
            
            $('#kode_bank_pembayar').val(row.KODE_BANK_PEMBAYAR)
            $('#nama_bank_pembayar').val(row.NAMA_BANK_PEMBAYAR)

            if (status_proses == 'belum'){
                $('#kode_bank').val(row.KODE_BANK_PENERIMA)
                $('#nama_bank').val(row.BANK_PENERIMA)
                $('#no_konfirmasi').val(row.NO_KONFIRMASI)
                $('#no_proses').val(row.NO_PROSES)
                $('#kd_prg').val(row.KD_PRG)
            }


            if (status_proses == 'sudah') {
                $('#kode_agenda').val(row.KODE_AGENDA_KOREKSI)
                $('#kode_bank_baru').val(row.KODE_BANK_PENERIMA_BARU)
                $('#nama_bank_baru').val(row.BANK_PENERIMA_BARU)
                $('#nama_penerima_baru').val(row.NAMA_REKENING_PENERIMA_BARU)
                $('#kode_bank_pembayar_baru').val(row.KODE_BANK_PEMBAYAR_BARU)
                $('#nama_bank_pembayar_baru').val(row.NAMA_BANK_PEMBAYAR_BARU)
                $('#no_rekening_penerima_baru').val(row.NO_REKENING_PENERIMA_BARU)
                $('#keterangan_koreksi_baru').val(row.KETERANGAN_KOREKSI)

                $('#kantor_koreksi').val(row.KODE_KANTOR_KOREKSI)
                $('#nama_kantor').val(row.NAMA_KANTOR)
                $('#tgl_koreksi').val(row.TGL_AGENDA_KOREKSI)
                $('#petugas_koreksi').val(row.PETUGAS_KOREKSI)
                $('#nama_petugas').val(row.NAMA_PETUGAS)
                if (row.STATUS_APPROVAL == 'T') {
                    status_approval = 'MENUNGGU PERSETUJUAN';
                } else if (row.STATUS_APPROVAL == 'Y') {
                    status_approval = 'DISETUJUI';
                } else if (row.STATUS_APPROVAL == 'R') {
                    status_approval = 'DITOLAK';
                }
                $('#status_koreksi').val(status_approval)
                var dataPersetujuaKoreksi = row.persetujuaKoreksi
                if (dataPersetujuaKoreksi.length > 0) {
                    var html_persetujuan = "";
                    var num = 0;
                    for (var i = 0; i < dataPersetujuaKoreksi.length; i++) {
                        var no = num + 1;
                        var rowPer = dataPersetujuaKoreksi[i];
                        if (rowPer.STATUS_APPROVAL == 'Y') {
                            // status = `<input type="checkbox" checked disabled>`;
                            status = `<span style="color:green">DISETUJUI</span>`;
                        } else if (rowPer.STATUS_APPROVAL == 'R'){
                            // status = `<input type="checkbox" disabled>`;
                            status = `<span style="color:red">DITOLAK</span>`;
                        } else {
                            status = `-`;
                        }
                        html_persetujuan += '<tr>';
                        html_persetujuan += '<td style="text-align: center;">' + no + '</td>';
                        html_persetujuan += '<td style="text-align: left;">' + (rowPer.PENJABAT ? rowPer.PENJABAT : '') +
                            '</td>';
                        html_persetujuan += '<td style="text-align: left;">' + rowPer.KODE_KANTOR + ' - ' + rowPer
                            .NAMA_KANTOR + '</td>';
                        html_persetujuan += '<td style="text-align: center;">' + status + '</td>';
                        html_persetujuan += '<td style="text-align: center;">' + (rowPer.TGL_APPROVAL ? rowPer
                            .TGL_APPROVAL : '') + '</td>';
                        html_persetujuan += '<td style="text-align: left;">' + (rowPer.PETUGAS_APPROVAL ? rowPer
                            .PETUGAS_APPROVAL : '') + ' - '+ (rowPer.NAMA_PETUGAS_APPROVAL ? rowPer
                            .NAMA_PETUGAS_APPROVAL : '') + '</td>';
                        html_persetujuan += '<td style="text-align: left;">' + (rowPer.KETERANGAN_APPROVAL ? rowPer
                            .KETERANGAN_APPROVAL : '') + '</td>';
                        html_persetujuan += '</tr>';
                        num++;
                    }
                } else {
                    html_persetujuan += '<tr class="nohover-color">';
                    html_persetujuan += '<td colspan="6" style="text-align: center;">-- Data tidak ditemukan --</td>';
                    html_persetujuan += '</tr>';
                }
                $("#tbDataPersetujuanKoreksi").html(html_persetujuan);
            }
        } else {
            alert(jdata.msg);
        }
    }

    function cekValidasiRekening() {
        window.asyncPreload('filter', true);
        //var bank_list 		= document.getElementById('list_bank_penerima');
        //var bank_selected = bank_list.options[bank_list.selectedIndex].value;
        var v_bank = $('#kode_bank_baru').find(':selected').val();
        var v_norek = $('#no_rekening_penerima_baru').val();

        //if(bank_selected!=''){
        if (v_bank != '' && v_norek != '') {
            var url = "<?=$formAjax?>" + Math.random()
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    fn: "<?=encrypt_decrypt('encrypt', 'cekValidasiRekening')?>",
                    TYPE: 'cek_validasi_rekening',
                    NOREK: v_norek,
                    BANK: v_bank,
                    KODE_BANK: v_bank,
                    NAMA_BANK: $('#nama_bank_baru').val()
                },
                success: function(data) {
                    window.asyncPreload('filter', false);
                    //console.log(data);
                    jdata = JSON.parse(data);
                    //console.log(jdata);     
                    if (jdata.ret == "0") {
                        $('#nama_penerima_baru').val(jdata.data['NAMA_REK_TUJUAN']);
                        window.document.getElementById('cb_valid_rekening').checked = true;
                        $('#status_valid_rekening_penerima').val('Y');
                        $('#nama_penerima_baru').val(jdata.data['NAMA_REK_TUJUAN']);
                        document.getElementById('nama_penerima_baru').readOnly = true;
                        document.getElementById('nama_penerima_baru').style.backgroundColor='#f5f5f5';
                    } else {
                        window.parent.Ext.notify.msg('Gagal validasi rekening...', jdata.msg);
                        //nama_rekening dapat diinput manual ---------------------------------
                        document.getElementById('nama_penerima_baru').readOnly = false;
                        document.getElementById('nama_penerima_baru').style.backgroundColor = '#ffff99';
                        $('#status_valid_rekening_penerima').val('T');
                        $('#nama_penerima_baru').val('');
                        $('#nama_penerima_baru').val('');
                        document.getElementById('nama_penerima_baru').placeholder =
                            "-- isikan NAMA secara manual --";
                        window.document.getElementById('cb_valid_rekening').checked = false;
                    }
                },
                error: function(data) {
                    window.asyncPreload('filter', false);
                }
            });
        } else {
            window.asyncPreload('filter', false);
            $('#nama_penerima_baru').val('');
            window.document.getElementById('cb_valid_rekening').checked = false;
            $('#status_valid_rekening_penerima').val('T');
            $('#nama_penerima_baru').val('');
        }
    }

    function copyNamaRekeningPenerima() {
        fl_js_set_status_valid_rekening_penerima();
    }

    function cekPilihBank() {
        var nama_bank = $('#nama_bank_baru').val();
        if (nama_bank != '') {
            $('#no_rekening_penerima_baru').removeAttr('readonly');
            $('#nama_penerima_baru').removeAttr('readonly');
            $('#keterangan_koreksi_baru').removeAttr('readonly');
        } else {
            $('#no_rekening_penerima_baru').attr('readonly', '');
            $('#nama_penerima_baru').attr('readonly', '');
            $('#keterangan_koreksi_baru').attr('readonly', '');
        }
        submitActive()
    }

    function submitActive() {
        var nama_bank = $('#nama_bank_baru').val();
        var no_rekening_penerima_baru = $('#no_rekening_penerima_baru').val();
        var nama_penerima_baru = $('#nama_penerima_baru').val();
        var valid = $('#cb_valid_rekening:checked').val();
        var setuju = $('#setuju:checked').val();
        // if (nama_bank != '' && no_rekening_penerima_baru != '' && nama_penerima_baru != '' && setuju == 'Y' && valid == 'Y') {
        if (nama_bank != '' && no_rekening_penerima_baru != '' && nama_penerima_baru != '' && setuju == 'Y') {
            $('#btn_submit').removeAttr('disabled');
            $('#btn_submit').addClass('btn green');
        } else {
            $('#btn_submit').attr('disabled', '');
            $('#btn_submit').removeClass('btn green');
        }
    }

    function fl_js_set_status_valid_rekening_penerima() {
        var form = document.fpop;
        if (form.cb_valid_rekening.checked) {
            form.status_valid_rekening_penerima.value = "Y";
            $('#nama_rekening_penerima').val($('#nama_penerima_baru').val());
        } else {
            form.status_valid_rekening_penerima.value = "T";
            form.nama_rekening_penerima.value = "";
        }
    }

    function kembali() {
        var url = 'http://<?= $HTTP_HOST; ?><?=$formUtama?>?former_search_by=' + '<?=$search_by?>' +
            '&former_status_proses=' + '<?=$status_proses?>' + '&former_search_txt=' + '<?=$search_txt?>';
        window.location.replace(url);
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
                                <legend style="color:#0080FF !important">Informasi Klaim</legend>
                                <div class="row">
                                    <div class="column" style="width:700px">
                                        <?php if($status_proses == 'sudah') { ?>
                                        <div class="l_frm" style="width:190px"><label for="kode_agenda">Kode
                                                Agenda</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kode_agenda" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <?php } ?>
                                        <div class="l_frm" style="width:190px"><label for="kode_klaim">Kode
                                                Klaim</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kode_klaim" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nomor_identitas">Nomor
                                                Identitas</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="nomor_identitas" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nama_tk">Nama Peserta</label>
                                        </div>
                                        <div class="r_frm">
                                            <input type="text" id="nama_tk" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="kpj">KPJ</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kpj" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="tgl_klaim">Tanggal
                                                Klaim</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="tgl_klaim" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nama_penerima_manfaat">Nama Penerima Manfaat</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="nama_penerima_manfaat" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="tgl_lahir">Tanggal Lahir Penerima Manfaat</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="tgl_lahir" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nom_manfaat">Nominal Manfaat
                                                (Rp)</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="nom_manfaat" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="keterangan_retur">Keterangan
                                                Retur</label></div>
                                        <div class="r_frm">
                                            <textarea id="keterangan_retur" style="width: 300px;" rows="5"
                                                disabled></textarea>
                                        </div>
                                        <div class="div-row-clear"></div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend style="color:#0080FF !important">Data Rekening Lama</legend>
                                <div class="row">
                                    <div class="column" style="background-color:;">
                                        <div class="l_frm" style="width:190px"><label for="kode_bank_penerima">Kode Bank
                                                / Nama Bank Penerima</label></div>
                                        <div class="r_frm row">
                                            <input type="text" id="kode_bank_penerima" style="width: 50px;" disabled>
                                            <input type="text" id="bank_penerima" style="width: 241px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="no_rekening_penerima">Nomor
                                                Rekening Penerima</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="no_rekening_penerima" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nama_penerima">Nama Rekening
                                                Penerima</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="nama_rekening_penerima" style="width: 300px;"
                                                disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="kode_bank_pembayar">Nama Bank Pembayar</label></div>
                                        <div class="r_frm row">
                                            <input type="hidden" id="kode_bank_pembayar" style="width: 50px;" disabled>
                                            <input type="text" id="nama_bank_pembayar" style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend style="color:#0080FF !important">Data Rekening Baru</legend>
                                <div class="row">
                                    <div class="column" style="background-color:;">
                                        <div class="l_frm" style="width:190px"><label for="kode_bank_baru">Kode Bank /
                                                Nama Bank Penerima
                                                <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label>
                                        </div>
                                        <div class="r_frm row">
                                            <?php if ($status_proses == 'belum') { ?>
                                            <select id="kode_bank_baru" name="kode_bank_baru" style="width: 305px;"
                                                <?=$disabled?>></select>
                                            <input type="hidden" id="nama_bank_baru" name="nama_bank_baru"
                                                <?=$disabled?>>
                                            <?php } else { ?>
                                            <input type="text" id="kode_bank_baru" name="kode_bank_baru"
                                                style="width: 50px;" <?=$disabled?>>
                                            <input type="text" id="nama_bank_baru" name="nama_bank_baru"
                                                style="width: 241px;" <?=$disabled?>>
                                            <?php } ?>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label
                                                for="no_rekening_penerima_baru">Nomor Rekening Penerima
                                                <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label>
                                        </div>
                                        <div class="r_frm">
                                            <?php if($status_proses == 'belum') { ?>
                                            <input type="number" id="no_rekening_penerima_baru"
                                                name="no_rekening_penerima_baru" maxlength="20" style="width: 250px;"
                                                <?=$disabled?> onblur="cekValidasiRekening();"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                            <input type="checkbox" style="pointer-events:none" id="cb_valid_rekening"
                                                name="cb_valid_rekening" class="cebox"
                                                onclick="copyNamaRekeningPenerima()"
                                                <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>
                                                value="Y"><i>
                                                <span style="color:#009999;">Valid</span>
                                            </i>
                                            <?php } else { ?>
                                            <input type="number" id="no_rekening_penerima_baru"
                                                name="no_rekening_penerima_baru" maxlength="20" style="width: 300px;"
                                                <?=$disabled?> onblur="cekValidasiRekening();"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                            <?php } ?>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="nama_penerima_baru">Nama
                                                Rekening Penerima
                                                <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label>
                                        </div>
                                        <div class="r_frm">
                                            <input type="text" id="nama_penerima_baru" name="nama_penerima_baru"
                                                style="width: 300px;" <?=$disabled?> maxlength="100"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                onblur="this.value=this.value.toUpperCase();"
                                                placeholder="-- validasi rekening bank --" onchange="submitActive()">
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="kode_bank_pembayar_baru">Nama Bank Pembayar
                                                <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label>
                                        </div>
                                        <div class="r_frm row">
                                            <?php if ($status_proses == 'belum') { ?>
                                            <select id="kode_bank_pembayar_baru_display"
                                                name="kode_bank_pembayar_baru_display" style="width: 305px;"
                                                disabled></select>
                                            <input type="hidden" id="kode_bank_pembayar_baru"
                                                name="kode_bank_pembayar_baru" <?=$disabled?>>
                                            <input type="hidden" id="nama_bank_pembayar_baru"
                                                name="nama_bank_pembayar_baru" <?=$disabled?>>
                                            <?php } else { ?>
                                            <input type="hidden" id="kode_bank_pembayar_baru"
                                                name="kode_bank_pembayar_baru" style="width: 50px;" <?=$disabled?>>
                                            <input type="text" id="nama_bank_pembayar_baru"
                                                name="nama_bank_pembayar_baru" style="width: 300px;" <?=$disabled?>>
                                            <?php } ?>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label
                                                for="keterangan_koreksi_baru">Keterangan Koreksi
                                                <?=$disabled=='required' ? '<sup style="color:red">*</sup>' : NULL ?></label>
                                        </div>
                                        <div class="r_frm">
                                            <textarea name="keterangan_koreksi_baru" id="keterangan_koreksi_baru"
                                                style="width: 300px;" rows="5" <?=$disabled?>
                                                onchange="submitActive()"></textarea>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <?php if ($status_proses == 'belum') { ?>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label
                                                style="text-align:right;">&nbsp;</label></div>
                                        <div class="r_frm">
                                            <input type="checkbox" id="setuju" name="setuju" value="Y"
                                                onclick="submitActive()">
                                            <b style="font-size: 12px;">Dengan mencentang kotak ini, saya telah
                                                memeriksa dan meneliti kebenaran serta keabsahan data yang diinput /
                                                upload</b>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label
                                                style="text-align:right;">&nbsp;</label></div>
                                        <div class="r_frm">
                                            <input type="hidden" readonly id="id" name="id"
                                                value="<?=encrypt_decrypt('encrypt',$kode_klaim)?>">
                                            <input type="hidden" readonly name="no_konfirmasi" id="no_konfirmasi">
                                            <input type="hidden" readonly name="no_proses" id="no_proses">
                                            <input type="hidden" readonly name="kd_prg" id="kd_prg">
                                            <input type="hidden" readonly name="kode_bank" id="kode_bank">
                                            <input type="hidden" readonly name="nama_bank" id="nama_bank">
                                            <input type="submit" name="btn_submit" style="width: 100px" id="btn_submit"
                                                value="SUBMIT" onclick="submitData()" />
                                            <input type="button" name="btn_kembali" style="width: 100px"
                                                class="btn green" id="btn_kembali" value="TUTUP" onclick="kembali()" />
                                        </div>
                                        <?php } ?>
                                        <?php if ($status_proses == 'sudah') { ?>
                                        <div class="l_frm" style="width:190px"><label for="tgl_koreksi">Tanggal
                                                Koreksi</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="tgl_koreksi" name="tgl_koreksi" style="width: 300px;"
                                                disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="petugas_koreksi">Petugas
                                                Koreksi</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="petugas_koreksi" name="petugas_koreksi"
                                                style="width: 80px;" disabled>
                                            <input type="text" id="nama_petugas" name="nama_petugas"
                                                style="width: 211px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="kantor_koreksi">Kantor
                                                Koreksi</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="kantor_koreksi" name="kantor_koreksi"
                                                style="width: 50px;" disabled>
                                            <input type="text" id="nama_kantor" name="nama_kantor" style="width: 241px;"
                                                disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <div class="l_frm" style="width:190px"><label for="status_koreksi">Status
                                                Koreksi</label></div>
                                        <div class="r_frm">
                                            <input type="text" id="status_koreksi" name="status_koreksi"
                                                style="width: 300px;" disabled>
                                        </div>
                                        <div class="div-row-clear"></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>

                            <?php if ($status_proses == 'sudah') { ?>
                            <br />
                            <fieldset>
                                <legend style="color:#0080FF !important">Daftar Persetujuan Koreksi</legend>
                                <div class="row">
                                    <div>
                                        <table style="width:95%;" class="table-data2" aria-describedby="tableDataApp">
                                            <thead>
                                                <tr class="hr-double">
                                                    <th style="text-align: center;" width="20">No</th>
                                                    <th style="text-align: center;" width="200">Penjabat</th>
                                                    <th style="text-align: center;" width="200">Kantor</th>
                                                    <th style="text-align: center;" width="50">Status <br> Persetujuan
                                                    </th>
                                                    <th style="text-align: center;" width="100">Tanggal <br> Persetujuan
                                                    </th>
                                                    <th style="text-align: center;" width="70">Petugas</th>
                                                    <th style="text-align: center;">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbDataPersetujuanKoreksi">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br><br>
                                <div class="div-row-clear"></div>
                                <div class="form-row_kiri">
                                    <input type="button" name="btn_kembali" style="width: 80px;" class="btn green"
                                        id="btn_kembali" value="TUTUP" onclick="kembali()" />
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