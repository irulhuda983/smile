<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once '../../includes/fungsi_newrpt.php';

$pagetype = "form"; # type form
$gs_pagetitle = "PN5075 - Monitoring Koreksi Pembayaran Klaim Return"; # untuk judul form
$formAjax = "../ajax/pn5075_action.php?"; # inisialisasikan lokasi file ajax
$formDetail = "/mod_pn/form/pn5075_form_detail.php"; # inisialisasikan lokasi file detail

# open koneksi db
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
# get data dari session
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];


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

function checkParam($key)
{
    return (isset($_GET[$key]) && !empty($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : NULL));
}

# parameter filter
$former_search_by   		= htmlspecialchars(checkParam('former_search_by'));
$former_search_txt   		= htmlspecialchars(checkParam('former_search_txt'));
$former_tglawaldisplay   	= checkParam('former_tglawaldisplay');
$former_tglakhirdisplay   	= checkParam('former_tglakhirdisplay');
$former_status_proses   	= htmlspecialchars(checkParam('former_status_proses'));
		
?>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="../../javascript/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" media="all" href="../../javascript/chosen_v1.8.7/chosen.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../../style/kn_style.css?abc">
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<script language="javascript">
    $(document).ready(function() {
        $("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});
        search_by_changed()
        // setHeader()
        if ('<?=$former_search_by?>' || '<?=$former_search_txt?>' || '<?=$former_status_proses?>') {
            setTimeout(function() {
                filter(0, '<?=$former_search_by?>', '<?=$former_search_txt?>',
                    '<?=$former_status_proses?>');
            }, 500);
        } else {
            setTimeout(function() {
                filter();
            }, 500);
        }

    });


    function getValue(val) {
        return val == null ? '' : val;
    }

    function search_by_changed() {
        var type = $('#search_by').find(':selected').data('type');
        $('#span_search_tgl').hide()
        $('.cari').hide()
        $('.cari').attr('name', 'cari');
        $('.select2').hide()
        $('#search_select').hide()
        if (type == 'select') {
            //
            var option = $('#search_by').find(':selected').data('option');
            $('#search_select').show()
            $('.select2').show()
            $('#search_select').attr('name', 'search_txt');
            $('#search_select').val('')
            if (option == 'status') {
                html = `
                    <option value="" selected disabled>-- Pilih --</option>
                    <option value="T">MENUNGGU PERSETUJUAN</option>
                    <option value="Y">DISETUJUI</option>
                    <option value="R">DITOLAK</option>
                `;
                $('#search_select').html(html)
                $("#search_select").select2()
            } else if (option == 'bank') {
                cariBank()
            }
        } else if(type == 'date'){
            //
            $('#span_search_tgl').show()
            $('#search_tgl').show()
            $('#search_tgl').attr('name', 'search_txt');
        } else {
            $('#search_txt').show()
            $('#search_txt').attr('type', type);
            $('#search_txt').attr('name', 'search_txt');
        }
    }

    function cariBank()
    {
        $("#search_select").select2({
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
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
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
                    filter();
                },
                destroy: function(wnd, eOpts) {}
            }
        });
        openwin.show();
        return openwin;
    }

    function showForm(mypage, myname, w, h, scroll) {
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
                close: function() {},
                destroy: function(wnd, eOpts) {}
            }
        });
        openwin.show();
        return openwin;
    }

    function filter(val = 0, former_search_by, former_search_txt, former_status_proses) {
        var pages = new Number($("#pages").val());
        var page = new Number($("#page").val());
        var page_item = $("#page_item").val();
        var kode_kantor = $("#kode_kantor").val();
        var status_proses = $('input[name=status_proses]:checked').val();

        var search_by = $("#search_by").val();
        var type = $('#search_by').find(':selected').data('type');
        if (type == 'select') {
            var search_txt = $("select[name=search_txt]").find(':selected').val();
        } else {
            var search_txt = $("input[name=search_txt]").val();
        }

        if (former_search_by, former_search_txt, former_status_proses) {
            search_by = former_search_by;
            search_txt = former_search_txt;
            status_proses = former_status_proses;
            $('input:radio[name=tahap_1]').checked = true;
        }

        if (former_status_proses) {
            $("input[value=" + former_status_proses + "]").prop("checked", true);
        }

        page = setPage(val)


        var url = "<?=$formAjax?>" + Math.random()
        preload(true);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                tipe: 'select',
                fn: "<?=encrypt_decrypt('encrypt', 'getData')?>",
                page: page,
                kode_kantor: kode_kantor,
                page_item: page_item,
                search_by: search_by,
                search_txt: search_txt,
                status_proses: status_proses
            },
            dataType: "JSON",
            beforeSend: function() {
                setHeader()
                $('#search_by').val(search_by)
            },
            success: function(data) {
                // console.log(data)
                setDataTable(data, page, page_item)
                preload(false);
            },
            complete: function() {
                preload(false);
            },
            error: function() {
                alert("Terjadi kesalahan, coba beberapa saat lagi!");
                preload(false);
            }
        });
    }

    function setHeader() {
        var x = $('input[name=status_proses]:checked').val();
        if (x == 'belum') {
            var htmlHead = `<tr><th style="text-align: center;">No</th>
                            <th style="text-align: center;">Aksi</th>
                            <th style="text-align: center;">Kode Klaim</th>
                            <th style="text-align: center;">Nomor Identitas</th>
                            <th style="text-align: center;">Nama Peserta</th>
                            <th style="text-align: center;">KPJ</th>
                            <th style="text-align: center;">Tanggal <br> Klaim</th>	
                            <th style="text-align: center;">Nominal Manfaat <br> (Rp)</th>
                            <th style="text-align: center;">Nama Bank <br> Penerima</th>
                            <th style="text-align: center;">Nomor Rekening <br> Penerima</th>
                            <th style="text-align: center;">Nama Rekening <br> Penerima</th>
                            <th style="text-align: center;">Keterangan Retur</th></tr>`;
            var htmlOption = `<option value="" data-type="text">-- Pilih --</option>
                                <option data-type="text" value="KODE_KLAIM">Kode Klaim</option>
                                <option data-type="number" value="NOMOR_IDENTITAS">Nomor Identitas</option>
                                <option data-type="text" value="NAMA_TK">Nama Peserta</option>
                                <option data-type="text" value="KPJ">KPJ</option>
                                <option data-type="date" value="TGL_KLAIM">Tanggal Klaim</option>
                                <option data-type="select" data-option="bank" value="KODE_BANK_PENERIMA">Nama Bank Penerima</option>
                                <option data-type="number" value="NOMOR_REKENING_PENERIMA">Nomor Rekening Penerima</option>
                                <option data-type="text" value="NAMA_REKENING_PENERIMA">Nama Rekening Penerima</option>`;
        } else {
            var htmlHead = `<tr><th style="text-align: center;">No</th>
                            <th style="text-align: center;">Aksi</th>
                            <th style="text-align: center;">Kode Agenda</th>
                            <th style="text-align: center;">Kode Klaim</th>
                            <th style="text-align: center;">Nomor Identitas</th>
                            <th style="text-align: center;">Nama Peserta</th>
                            <th style="text-align: center;">KPJ</th>
                            <th style="text-align: center;">Tanggal <br> Klaim</th>	
                            <th style="text-align: center;">Nominal Manfaat <br> (Rp)</th>
                            <th style="text-align: center;">Nama Bank <br> Penerima</th>
                            <th style="text-align: center;">Nomor Rekening <br> Penerima</th>
                            <th style="text-align: center;">Nama Rekening <br> Penerima</th>
                            <th style="text-align: center;">Tanggal Koreksi</th>
                            <th style="text-align: center;">Petugas Koreksi</th>
                            <th style="text-align: center;">Kantor Koreksi</th>
                            <th style="text-align: center;">Status Koreksi</th></tr>`;
            var htmlOption = `<option value="" data-type="text">-- Pilih --</option>
                                <option data-type="text" value="KODE_AGENDA_KOREKSI">Kode Agenda</option>
                                <option data-type="text" value="KODE_KLAIM">Kode Klaim</option>
                                <option data-type="number" value="NOMOR_IDENTITAS">Nomor Identitas</option>
                                <option data-type="text" value="NAMA_TK">Nama Peserta</option>
                                <option data-type="text" value="KPJ">KPJ</option>
                                <option data-type="date" value="TGL_KLAIM">Tanggal Klaim</option>
                                <option data-type="select" data-option="bank" value="KODE_BANK_PENERIMA">Nama Bank Penerima</option>
                                <option data-type="number" value="NOMOR_REKENING_PENERIMA">Nomor Rekening Penerima</option>
                                <option data-type="text" value="NAMA_REKENING_PENERIMA">Nama Rekening Penerima</option>
                                <option data-type="date" value="TGL_KOREKSI">Tanggal Koreksi</option>
                                <option data-type="text" value="PETUGAS_KOREKSI">Petugas Koreksi</option>
                                <option data-type="text" value="KANTOR_KOREKSI">Kantor Koreksi</option>
                                <option data-type="select" data-option="status" value="STATUS_KOREKSI">Status Koreksi</option>`;
        }
        $(`#data_header`).html(htmlHead)
        $(`#search_by`).html(htmlOption)
    }

    function setDataTable(jdata, page, page_item) {
        var status_proses = $('input[name=status_proses]:checked').val();
        var search_by = $("#search_by").val();
        var search_txt = $("#search_txt").val();
        if (jdata.ret == 0) {
            var html_data = "";
            var num = 0;
            for (var i = 0; i < jdata.data.length; i++) {
                var no = ((page_item * page) - page_item) + num + 1;
                var row = jdata.data[i];
                var id = row.id;
                html_data += '<tr>';
                html_data += '<td style="text-align: center;">' + no + '</td>';
                // html_data += '<td style="text-align: center;"><a onclick="showFormDetail(`'+row.params+'`,`'+row.KPJ+'`,`'+row.NOMOR_IDENTITAS+'`,`'+status_proses+'`,`'+search_by+'`,`'+search_txt+'`)">Lihat</a></td>';
                html_data += '<td style="text-align: center;"><a onclick="showFormDetail(`'+row.params+'`)">Lihat</a></td>';
                if (status_proses == 'belum') {
                    html_data += '<td style="text-align: center;">' + getValue(row.KODE_KLAIM) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.NOMOR_IDENTITAS) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NAMA_TK) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.KPJ) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.TGL_KLAIM) + '</td>';
                    html_data += '<td style="text-align: right;">' + getValue(row.NOM_MANFAAT) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.BANK_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NO_REKENING_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NAMA_REKENING_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.KETERANGAN_RETUR) + '</td>';
                } else {
                    if (row.STATUS_APPROVAL == 'T') {
                        status_approval = 'MENUNGGU PERSETUJUAN';
                    } else if (row.STATUS_APPROVAL == 'Y') {
                        status_approval = 'DISETUJUI';
                    } else if (row.STATUS_APPROVAL == 'R') {
                        status_approval = 'DITOLAK';
                    }
                    
                    html_data += '<td style="text-align: center;">' + getValue(row.KODE_AGENDA_KOREKSI) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.KODE_KLAIM) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.NOMOR_IDENTITAS) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NAMA_TK) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.KPJ) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.TGL_KLAIM) + '</td>';
                    html_data += '<td style="text-align: right;">' + getValue(row.NOM_MANFAAT) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.BANK_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NO_REKENING_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.NAMA_REKENING_PENERIMA) + '</td>';
                    html_data += '<td style="text-align: center;">' + getValue(row.TGL_AGENDA_KOREKSI) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.PETUGAS_KOREKSI) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(row.KODE_KANTOR_KOREKSI) + '</td>';
                    html_data += '<td style="text-align: left;">' + getValue(status_approval) + '</td>';
                }

                html_data += '</tr>';
                num++;
            }

            if (html_data == "") {
                html_data += '<tr class="nohover-color">';
                html_data += '<td colspan="21" style="text-align: center;">-- Data tidak ditemukan --</td>';
                html_data += '</tr>';
            }
            $("#data_list").html(html_data);

            // load info halaman
            $("#pages").val(Math.ceil(jdata.recordsTotal / page_item));
            $("#span_info_halaman").html('dari ' + Math.ceil(jdata.recordsTotal / page_item) + ' halaman');
            var start = ((page_item * page) - page_item) + 1;
            var end = ((page_item * page) - page_item) + 10;
            // load info item
            $("#span_info_item").html('Menampilkan item ke ' + start + ' sampai dengan ' + no + ' dari ' + jdata
                .recordsTotal + ' items');
            $("#hdn_total_records").val(jdata.recordsTotal);

            // resize();
        } else if (jdata.ret == -2) {
            var html_data = "";
            html_data += '<tr class="nohover-color">';
            html_data += '<td colspan="21" style="text-align: center;">-- Data tidak ditemukan --</td>';
            html_data += '</tr>';
            $("#data_list").html(html_data);
        } else {
            alert(jdata.msg);
        }
    }

    function setPage(val) {
        var pages = new Number($("#pages").val());
        var page = new Number($("#page").val());
        if (val == 1) {
            page = (page + 1) > pages ? pages : (page + 1);
        } else if (val == 2) {
            page = pages;
        } else if (val == -1) {
            page = (page - 1) <= 0 ? 1 : (page - 1);
        } else if (val == -2) {
            page = 1;
        } else if (val == 0) {
            page = 1;
        }

        $("#page").val(page);
        return page;
    }

    function showFormDetail(params) {
        var url = 'http://<?= $HTTP_HOST; ?><?=$formDetail?>?params=' + params;
        window.location.replace(url);
    }
</script>

<div id="formKiri" class="form-container">
    <form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="order_by" name="order_by" value="">
        <input type="hidden" id="order_type" name="order_type" value="">
        <div id="actmenu">
            <div class="menu-title">
                <h3><?=$gs_pagetitle;?></h3>
            </div>
        </div>
        <div id="formframe" class="form-container">
            <div class="div-container">
                <div class="div-header">
                    <div class="div-header-content">
                        <div class="div-row">
                            <div class="item">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="div-body">
                    <div class="div-filter">
                        <div class="div-row">


                        </div>
                        <div class="div-row-clear"></div>
                        <div class="div-row-clear"></div>
                        <div class="div-row-between">
                            <div class="left-item">
                                <div class="div-row">
                                    <div class="item">
                                        <input type="radio" name="status_proses" value="belum" onclick="filter()"
                                            checked>&nbsp;<span style="color:#0093FF;"><b>Belum Diproses</b></span> &nbsp;
                                        &nbsp;
                                        <input type="radio" name="status_proses" value="sudah" onclick="filter()">&nbsp;
                                        <span style="color:#0093FF;"><b>Sudah diproses</b></span> &nbsp;
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="right-item">
                                <div class="div-row">
                                    <div class="item">
                                        <select name="search_by" id="search_by" style="width: 136px;"
                                            onchange="search_by_changed()">
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="text" id="search_txt" class="cari" placeholder="Search.."
                                            style="width: 136px;" autocomplete>
                                            
                                        <span id="span_search_tgl"  hidden="">
                                            <input type="text" id="search_tgl" class="cari" value="" placeholder="dd/mm/yyyy" size="12" readonly>  
                                            <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('search_tgl', 'dd-mm-yyyy');" src="../../images/dynCalendar.gif" style="height: 11px !important;" alt="img"/>
                                        </span>
                                        <select id="search_select" class="cari" style="width: 136px;">
                                            <option selected disabled>-pilih-</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <input type="button" name="btnfilter" style="width: 100px" class="btn green"
                                            id="btnfilter" value="TAMPILKAN" onclick="filter()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-data">
                        <div class="div-row">
                            <div class="item full">
                                <div id="div-container-table" class="item full" data-height="360"
                                    style="overflow-y: auto; min-height: 120px; height: 335px;">
                                    <!-- <div id="div-container-table" class="item full" data-height="360" style="overflow-y: auto; min-height: 120px;"> -->
                                    <table class="table-data sticky" cellspacing='0' aria-describedby="tableData">
                                        <thead id="data_header"><tr><th></th></tr></thead>
                                        <tbody id="data_list"><tr><td></td></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-page">
                        <input type="hidden" id="pages" name="pages" value="">
                        <input type="hidden" id="hdn_total_records" name="hdn_total_records" value="">
                        <div class="div-row-between">
                            <div class="left-item">
                                <div class="div-row">
                                    <div class="item">Halaman</div>
                                    <div class="item">
                                        <a href="javascript:void(0)" title="First Page" class="icon-link"
                                            onclick="filter('-2')">
                                            <<</a>
                                    </div>
                                    <div class="item">
                                        <a href="javascript:void(0)" title="Previous Page" class="pagenext"
                                            onclick="filter('-1')">Prev</a>
                                    </div>
                                    <div class="item">
                                        <input type="text" value="1" id="page" name="page" class="pageinput"
                                            onkeypress="return isNumber(event)" onblur="filter(this.value);">
                                    </div>
                                    <div class="item">
                                        <a href="javascript:void(0)" title="Next Page" class="icon-link"
                                            onclick="filter('1')">Next</a>
                                    </div>
                                    <div class="item">
                                        <a href="javascript:void(0)" title="Last Page" class="icon-link"
                                            onclick="filter('2')">>></a>
                                    </div>
                                    <div class="item">
                                        <span id="span_info_halaman">dari 1 halaman</span>
                                    </div>
                                </div>
                            </div>
                            <div class="right-item">
                                <div class="item">
                                    <span id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
                                    <select name="page_item" id="page_item" style="width: 50px;" onchange="filter()">
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="item">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
<script type="text/javascript">
</script>
<?php
require_once "../../includes/footer_app_nosql.php";
?>