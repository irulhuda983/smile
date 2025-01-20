<?php
$pagetype = "form";
$gs_pagetitle = "KN8011 - INPUT KEBUTUHAN/TARGET KEPESERTAAN";
require_once "../../includes/header_app_nosql.php";
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "PU";

/* ============================================================================
  Ket : Form ini digunakan sebagai form Input Kebutuhan/Target Kepesertaan Monitoring Blanko
  Hist: - 01/09/2017 : Pembuatan Form (Tim SIJSTK)
  ----------------------------------------------------------------------------- */
?>

<!-- LOCAL JAVASCRIPTS------------------------------------------------------->		
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../javascript/select2.full.js"></script>
<script language="javascript">
    function NewWindow(mypage, myname, w, h, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        var settings = 'height=' + h + ',';
        settings += 'width=' + w + ',';
        settings += 'top=' + wint + ',';
        settings += 'left=' + winl + ',';
        settings += 'scrollbars=' + scroll + ',';
        settings += 'resizable=1';
        settings += 'location=0';
        settings += 'menubar=0';
        win = window.open(mypage, myname, settings);
        if (parseInt(navigator.appVersion) >= 4) {
            win.window.focus();
        }
    }

    function NewWindow3(mypage, myname, w, h, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        var settings = 'height=' + h + ',';
        settings += 'width=' + w + ',';
        settings += 'top=' + wint + ',';
        settings += 'left=' + winl + ',';
        settings += 'scrollbars=' + scroll + ',';
        settings += 'resizable=1';
        win = window.open(mypage, myname, settings);
        if (parseInt(navigator.appVersion) >= 4) {
            win.window.focus();
        }
    }

    function NewWindow4(mypage, myname, w, h, scroll) {
        var openwin = window.parent.Ext.create('Ext.window.Window', {
            title: myname,
            collapsible: true,
            animCollapse: true,
            maximizable: true,
            width: w,
            height: h,
            minWidth: 900,
            minHeight: 600,
            layout: 'fit',
            html: '<iframe src="' + mypage + '"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    items: [
                        {
                            xtype: 'button',
                            text: 'Tutup',
                            handler: function () {
                                openwin.close();
                            }
                        }
                    ]
                }]
        });
        openwin.show();
    }

    function fl_js_reset_keyword2()
    {
        document.getElementById('keyword2a').value = '';
        document.getElementById('keyword2b').value = '';
        document.getElementById('keyword2c').value = '';
        document.getElementById('keyword2d').value = '';
    }

    function fl_js_val_numeric(v_field_id)
    {
        var c_val = window.document.getElementById(v_field_id).value;
        var number = /^[0-9]+$/;

        if ((c_val != '') && (!c_val.match(number)))
        {
            document.getElementById(v_field_id).value = '';
            window.document.getElementById(v_field_id).focus();
            alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");
            return false;
        }
    }

</script>


<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->

<!-- LOCAL CSS -------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../style/select2.min.css">
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
<!-- end LOCAL CSS ---------------------------------------------------------->

<!-- LOCAL GET/POST PARAMETER ----------------------------------------------->
<?php
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_jenis_err = "";
$ls_message_err = "";
?>

<!-- end LOCAL GET/POST PARAMETER ------------------------------------------->
<div id="actmenu" style="display:none;">
    <h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?= $gs_pagetitle; ?></h3>     
</div>
<div id="actmenu" >
    <div id="actbutton">
        <div style="float:left;">
            <?php
            if (isset($_REQUEST["task"])) {
                ?>
                <?php
                if ($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New" || $_REQUEST["tasktk"] == "Edit") {
                    ?>
                    <div style="float:left;">
                        <div class="icon">
                            <a id="btn_save" href="javascript:void(0)"><img src="http://<?= $HTTP_HOST; ?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
                        </div>
                    </div>
                    <?php
                };
                ?>
                <div style="float:left;">
                    <div class="icon">
                        <a id="btn_close" href="http://<?= $HTTP_HOST; ?>/mod_kn/form/kn8011.php?mid=<?= $mid; ?>">
                            <img src="http://<?= $HTTP_HOST; ?>/images/file_cancel.gif" align="absmiddle" border="0"> Close
                        </a> 
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="icon">
                    <a href="javascript:void(0)" id="btn_view">
                        <img src="http://<?= $HTTP_HOST; ?>/images/application_get.png" align="absmiddle" border="0"> View</a>
                </div>
            </div>
            <div style="float:left;">
                <div class="icon">
                    <a id="btn_edit" href="javascript:void(0)" ><img src="http://<?= $HTTP_HOST; ?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
                </div>
            </div>
            <div style="float:left;">
                <div class="icon">
                    <a id="btn_delete" href="javascript:void(0)"><img src="http://<?= $HTTP_HOST; ?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a>
                </div>
            </div>
            <div style="float:left;">
                <div class="icon">
                    <a id="btn_new" href="javascript:void(0)"><img src="http://<?= $HTTP_HOST; ?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="clear"></div>
<div id="formframe">
    <div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    <div id="formKiri" style="width:1000px">
        <form name="formreg" id="formreg" role="form" method="post">
            <div id="konten">
                <?php
                if (isset($_REQUEST["task"])) {
                    ?>
                    <?php
                    if ($_REQUEST["task"] == "Edit") {
                        ?>
                        <br />
                        <br/>
                        <br/>
                        <div class="clear"></div>
                        <div id="formsplit">
                            <fieldset>
                                <legend>Data Periode Blanko </legend>
                                <div class="form-row_kiri">
                                    <label>Tahun </label>	
                                    <input  type="text" name="tahun" id="tahun" value="<?= $ls_tahun; ?>" size="35" tabindex="1" maxlength="4" onkeypress="validateDigit(event)">
                                    <input type="hidden" name="TYPE" value="EDIT">
                                    <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Tanggal Aktif </label>
                                    <input type="text" id="tgl_aktif" name="tgl_aktif" value="<?= $ld_tgl_aktif; ?>" size="32" maxlength="10" tabindex="2" onblur="convert_date(tgl_aktif);" >
                                    <input id="tgl_aktif" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_aktif', 'dd-mm-y');" src="../../images/calendar.gif" readonly class="disabled"/>						
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Tanggal Non Aktif </label>
                                    <input type="text" id="tgl_nonaktif" name="tgl_nonaktif" value="<?= $ld_tgl_nonaktif; ?>" size="32" maxlength="10" tabindex="3" onblur="convert_date(tgl_nonaktif);" >
                                    <input id="tgl_nonaktif" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_nonaktif', 'dd-mm-y');" src="../../images/calendar.gif" readonly class="disabled"/>						
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Status Non Aktif </label>
                                    <select size="1" id="status_nonaktif" name="status_nonaktif" value="<?= $ls_status_nonaktif; ?>" tabindex="4" class="select_format" style="width:230px;background-color:#ffff99;" >
                                        <option value="">-- Pilih --</option>
                                        <option value="T" <?php if (isset($ls_status_nonaktif) && $ls_status_nonaktif == "T") echo "selected"; ?> >T</option>
                                        <option value="Y" <?php if (isset($ls_status_nonaktif) && $ls_status_nonaktif == "Y") echo "selected"; ?> >Y</option>
                                    </select>		
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Keterangan </label>
                                    <textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?= $ls_keterangan; ?>" tabindex="5" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
                                    </textarea>   
                                </div>
                                <div class="clear"></div>
                            </fieldset>
                        </div>

                        <?php
                    }
                    ?>
                    <?php
                    if ($_REQUEST["task"] == "View") {
                        ?>
                        <br />
                        <br/>
                        <br/>
                        <div class="clear"></div>
                        <div id="formsplit">
                            <fieldset>
                                <legend>Data Periode Blanko </legend>
                                <div class="form-row_kiri">
                                    <label>Tahun <span style="color:#ff0000;">*</span></label>	
                                    <input  type="text" name="tahun" id="tahun" value="<?= $ls_tahun; ?>" size="35" tabindex="1" readonly class="disabled">
                                    <input type="hidden" name="TYPE" value="VIEW">
                                    <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Tanggal Aktif <span style="color:#ff0000;">*</span></label>
                                    <input type="text" id="tgl_aktif" name="tgl_aktif" value="<?= $ld_tgl_aktif; ?>" size="32" maxlength="10" tabindex="2" onblur="convert_date(tgl_aktif);" readonly class="disabled">
                                    <input id="tgl_aktif" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_aktif', 'dd-mm-y');" src="../../images/calendar.gif" readonly class="disabled"/>						
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Tanggal Non Aktif <span style="color:#ff0000;">*</span></label>
                                    <input type="text" id="tgl_nonaktif" name="tgl_nonaktif" value="<?= $ld_tgl_nonaktif; ?>" size="32" maxlength="10" tabindex="3" onblur="convert_date(tgl_nonaktif);" readonly class="disabled">
                                    <input id="tgl_nonaktif" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_nonaktif', 'dd-mm-y');" src="../../images/calendar.gif" readonly class="disabled"/>						
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">	
                                    <label>Status Non Aktif <span style="color:#ff0000;">*</span></label>
                                    <select size="1" id="status_nonaktif" name="status_nonaktif" value="<?= $ls_status_nonaktif; ?>" tabindex="4" class="select_format" style="width:230px;background-color:#ffff99;" disabled class="disabled">
                                        <option value="">-- Pilih --</option>
                                        <option value="T" <?php if (isset($ls_status_nonaktif) && $ls_status_nonaktif == "T") echo "selected"; ?> >T</option>
                                        <option value="Y" <?php if (isset($ls_status_nonaktif) && $ls_status_nonaktif == "Y") echo "selected"; ?> >Y</option>
                                    </select>		
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Keterangan </label>
                                    <textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?= $ls_keterangan; ?>" tabindex="5" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled class="disabled">
                                    </textarea>   
                                </div>
                                <div class="clear"></div>
                            </fieldset>
                        </div>
                        <?php
                    };
                    ?>
                    <?php
                    if ($_REQUEST["task"] == "New") {
                        ?>
                        <br />
                        <br/>
                        <br/>
                        <div class="clear"></div>
                        <div id="formsplit">
                            <fieldset>
                                <legend>Data Periode Blanko </legend>
                                <input type="hidden" name="TYPE" value="NEW">
                                <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                <div class="form-row_kiri">
                                    <label>Periode <span style="color:#ff0000;">*</span></label>	
                                    <input  type="text" name="periode" id="periode" value="<?= $ls_periode; ?>" size="8" tabindex="1" readonly class="disabled">
                                    <input  type="text" name="nama_user" id="nama_user" value="<?= $ls_nama_user; ?>" size="23" tabindex="2" readonly class="disabled">
                                    <a href="#" onclick="NewWindow('http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_lov_periode.php?p=kn8011.php&a=formreg&b=kode_user&c=nama_user&f=email&g=' + formreg.kode_kantor.value + '', '', 900, 500, 1)">
                                        <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Segmentasi Keps </label>
                                    <select size="1" id="kode_segmen" name="kode_segmen" value="" class="select_format" style="width:100px;">
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen order by kode_segmen";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["KODE_SEGMEN"] . "\">" . $row["NAMA_SEGMEN"] . "</option>";
                                        }
                                        ?>
                                    </select>	
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jenis Blanko </label>	
                                    <input  type="text" name="kode_user" id="kode_user" value="<?= $ls_kode_user; ?>" size="8" tabindex="2" readonly class="disabled">
                                    <input  type="text" name="nama_user" id="nama_user" value="<?= $ls_nama_user; ?>" size="23" tabindex="2" readonly class="disabled">
                                    <a href="#" onclick="NewWindow('http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_lov_periode.php?p=kn8011.php&a=formreg&b=kode_user&c=nama_user&f=email&g=' + formreg.kode_kantor.value + '', '', 900, 500, 1)">
                                        <img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jumlah Target </label>	
                                    <input  type="text" name="jml_kebutuhan" id="jml_kebutuhan" value="<?= $ls_jml_kebutuhan; ?>" size="35" tabindex="2" >
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Keterangan </label>
                                    <textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?= $ls_keterangan; ?>" tabindex="5" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
                                    </textarea>   
                                </div>
                                <div class="clear"></div>
                            </fieldset>
                        </div>
                        <?php
                    };
                    ?>
                    <?php
                } else {
                    ?>

                    <table class="captionentry">
                        <tr> 
                            <td align="left"><b><?= $gs_pagetitle; ?></b> </td>						 
                        </tr>
                    </table>
                    <br>
                    <div class="clear"></div>
                    <div class="form-row_kiri">
                        <span style="margin-right:5px;">Search by:</span>
                        <select id="type" name="type">
                            <option value="">-- Kategori --</option>
                            <option value="TAHUN">Tahun</option>
                            <option value="KETERANGAN">Keterangan</option>
                        </select>
                        <input  type="text" name="keyword" id="keyword" style="width:200px;" placeholder="Keyword">
                        <input type="button" name="btncari" class="btn green" id="btncari" value="TAMPILKAN DATA">
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="clear"></div>
                    <div id="formsplit">
                        <fieldset>
                            <legend>Daftar Periode Monitoring Blanko</legend>	
                            <div class="clear"></div>
                            <table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
                                        <th scope="col" width="10%">Periode</th>
                                        <th scope="col" width="15%">Jenis Blanko</th>
                                        <th scope="col" width="15%">Jml Kebutuhan</th>	
                                        <th scope="col" width="15%">Jml Kebutuhan Tambahan</th>
                                        <th scope="col">Total Kebutuhan</th>							
                                    </tr>
                                </thead>
                                <tbody id="listdata">
                                </tbody>
                            </table>
                            <div class="clear"></div>
                            <div class="clear"></div>																																																					
                        </fieldset>
                        <br>
                        <div class="clear"></div>
                        <fieldset style="background: #ededed;">
                            <div class="clear"></div>	
                            <div class="form-row_kiri">
                                <br>
                            </div>
                        </fieldset>

                        <?php
                    }
                    ?>
                </div>				
                <br>
                <br>
                <fieldset style="background: #FF0;">
                    <legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
                    <li>Pilih Jenis Pencarian</li>	
                    <li>Input Kata Kunci (Keyword)</li>	
                    <li>Klik Tombol CARI DATA untuk memulai pencarian data</li>	
                    <li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
                </fieldset>
            </div> 
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            window.dataid = '';

            $('#keyword').focus();

            $('input').keyup(function () {
                this.value = this.value.toUpperCase();
            });

            $('textarea').keyup(function () {
                this.value = this.value.toUpperCase();
            });

            $('#type').change(function (e) {
                $('#keyword').focus();
            });

            $('textarea').each(function () {
                $(this).val($(this).val().trim());
            }
            );

            window.table = $('#mydata').DataTable({
                "scrollCollapse": true,
                "paging": true,
                "iDisplayLength": 10,
                'sPaginationType': 'full_numbers',
                "stateSave": true,
                'aoColumnDefs': [
                    {'bSortable': false, 'aTargets': []}
                ],
            });

            window.onload = loadData();

            $('#btn_view').click(function () {
                if (window.dataid != '') {
                    window.location = 'kn8011.php?task=View&tasktk=View&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
                } else {
                    alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
                }

            });

            $('#btn_edit').click(function () {
                if (window.dataid != '') {
                    window.location = 'kn8011.php?task=Edit&tasktk=Edit&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
                } else {
                    alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
                }
            });

            $('#btn_new').click(function () {
                window.location = 'kn8011.php?task=New&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
            });

            $('#btn_delete').click(function () {
                if (window.dataid != '') {
                    var r = confirm("Apakah anda yakin ?");
                    if (r == true) {
                        $.ajax({
                            type: 'POST',
                            url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_action.php?' + Math.random(),
                            data: {TYPE: 'DEL', DATAID: window.dataid},
                            success: function (data) {
                                jdata = JSON.parse(data);
                                if (jdata.ret == '0') {
                                    window.parent.Ext.notify.msg('Berhasil', jdata.msg);
                                    window.selected.slideUp(function () {
                                        $(this).remove();
                                    });
                                    //location.reload();
                                } else {
                                    alert(jdata.msg);
                                }
                            }
                        });
                    }
                } else {
                    alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
                }
            });

            $("#btncari").click(function () {
                loadData();
            });

<?PHP
if (isset($_REQUEST["task"])) {
    ?>
                window.dataid = '<?= $_REQUEST["dataid"]; ?>';

    <?PHP
    if ($_REQUEST["task"] == "View") {
        ?>
                    setTimeout(function () {
                        preload(true);
                    }, 100);
                    $.ajax({
                        type: 'POST',
                        url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_action.php?' + Math.random(),
                        data: {TYPE: 'VIEW', DATAID: window.dataid, DATAID2: window.kodeperusahaan},
                        success: function (data) {
                            setTimeout(function () {
                                preload(false);
                            }, 100);
                            console.log("{ TYPE:'VIEW', DATAID:" + window.dataid + "}");
                            console.log(data);
                            //console.log(JSON.parse(data));
                            jdata = JSON.parse(data);
                            if (jdata.ret == '0') {
                                $('#kode_periode').val(jdata.data[0].KODE_PERIODE);
                                $('#tahun').val(jdata.data[0].TAHUN);
                                $('#tgl_aktif').val(jdata.data[0].TGL_AKTIF);
                                $('#tgl_nonaktif').val(jdata.data[0].TGL_NONAKTIF);
                                $('#status_nonaktif').val(jdata.data[0].STATUS_NONAKTIF);
                                $('#keterangan').val(jdata.data[0].KETERANGAN);
                            }
                        }
                    });
        <?PHP
    };
    ?>
    <?PHP
    if ($_REQUEST["task"] == "Edit") {
        ?>
                    setTimeout(function () {
                        preload(true);
                    }, 100);
                    $.ajax({
                        type: 'POST',
                        url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_action.php?' + Math.random(),
                        data: {TYPE: 'VIEW', DATAID: window.dataid},
                        success: function (data) {
                            setTimeout(function () {
                                preload(false);
                            }, 100);
                            console.log("{ TYPE:'VIEW', DATAID:" + window.dataid + "}");
                            console.log(data);
                            //console.log(JSON.parse(data));
                            jdata = JSON.parse(data);
                            if (jdata.ret == '0') {
                                $('#kode_periode').val(jdata.data[0].KODE_PERIODE);
                                $('#tahun').val(jdata.data[0].TAHUN);
                                $('#tgl_aktif').val(jdata.data[0].TGL_AKTIF);
                                $('#tgl_nonaktif').val(jdata.data[0].TGL_NONAKTIF);
                                $('#status_nonaktif').val(jdata.data[0].STATUS_NONAKTIF);
                                $('#keterangan').val(jdata.data[0].KETERANGAN);
                            }
                        }
                    });

                    $('#btn_save').click(function () {
                        if ($('#tahun').val() != '' && $('#tgl_aktif').val() != '' && $('#tgl_nonaktif').val() != ''
                                && $('#status_nonaktif').val() != '') {
                            preload(true);
                            $.ajax({
                                type: 'POST',
                                url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_action.php?' + Math.random(),
                                data: $('#formreg').serialize(),
                                success: function (data) {
                                    preload(false);
                                    console.log($('#formreg').serialize());
                                    console.log(data);
                                    console.log('test: ' + JSON.parse(data));
                                    jdata = JSON.parse(data);
                                    if (jdata.ret == '0') {
                                        window.parent.Ext.notify.msg('Berhasil', jdata.msg);
                                        location.reload();
                                    } else {
                                        alert(jdata.msg);
                                    }
                                }
                            });
                        } else {
                            alert('Silahkan mengisi data mandatori!');
                        }
                    });
        <?PHP
    };
    ?>

    <?PHP
    if ($_REQUEST["task"] == "New") {
        ?>
                    $('#btn_save').click(function () {
                        if ($('#tahun').val() != '' && $('#tgl_aktif').val() != '' && $('#tgl_nonaktif').val() != ''
                                && $('#status_nonaktif').val() != '') {
                            preload(true);
                            $.ajax({
                                type: 'POST',
                                url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_action.php?' + Math.random(),
                                data: $('#formreg').serialize(),
                                success: function (data) {
                                    preload(false);
                                    console.log($('#formreg').serialize());
                                    console.log(data);
                                    //console.log(JSON.parse(data));
                                    jdata = JSON.parse(data);
                                    if (jdata.ret == '0') {
                                        window.parent.Ext.notify.msg('Berhasil', jdata.msg);
                                        window.location = 'kn8011.php?task=Edit&dataid=' + $('#tahun').val() + '&mid=<?= $mid; ?>';
                                    } else {
                                        alert(jdata.msg);
                                    }
                                }
                            });

                        } else {
                            alert('Silahkan mengisi data mandatori!');
                        }
                    });
        <?PHP
    };
    ?>
    <?PHP
}
?>
        });

        function loadData() {
            preload(true);
            window.table
                    .clear()
                    .draw();
            $('input[aria-controls="mydata"]').val('');
            console.log('{ TYPE:' + $('#type').val() + ', KEYWORD:' + $('#keyword').val() + '}');
            $.ajax({
                type: 'POST',
                url: 'http://<?= $HTTP_HOST; ?>/mod_kn/ajax/kn8011_query.php?' + Math.random(),
                data: {TYPE: $('#type').val(), KEYWORD: $('#keyword').val()},
                success: function (data) {
                    preload(false);
                    //console.log(data);
                    jdata = JSON.parse(data);
                    if (jdata.ret == 0) {
                        for (i = 0; i < jdata.data.length; i++) {
                            window.table.row.add([
                                '<input type="checkbox" KODE_PERIODE="' + jdata.data[i].KODE_PERIODE + '" id="CHECK_' + i + '" urut="' + i + '" name="CHECK[' + i + ']"><input type="hidden" name="KODE_PERIODE[' + i + ']" id="KODE_PERIODE' + i + '" value="' + jdata.data[i].KODE_PERIODE + '">',
                                '<div style="text-align:center !important">' + jdata.data[i].TAHUN + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].NAMA_JENIS + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].TGL_AKTIF + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].TGL_NONAKTIF + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].STATUS_NONAKTIF + '</div>',
                                jdata.data[i].KETERANGAN
                            ]).draw();
                        }

                        $('input[type="checkbox"]').change(function () {
                            if (this.checked) {
                                window.dataid = $(this).attr('KODE_PERIODE');
                                window.selected = $(this).closest('tr');
                                console.log(window.dataid);
                            } else {
                                window.dataid = $(this).attr('KODE_PERIODE');
                            }
                        });

                        $('input[type="hidden"]').change(function () {
                            if (this.clicked) {
                                window.dataid = $(this).attr('KODE_PERIODE');
                                window.selected = $(this).closest('tr');
                            }
                        });
                        $('tbody>tr[role="row"').mouseout(function (e) {
                            $(this).css('background-color', '#fff');
                            $(this).css('cursor', 'hand');
                        });
                        $('tbody>tr[role="row"').mouseover(function (e) {
                            $(this).css('cursor', 'hand');
                            $(this).css('background-color', '#ddd');
                        });

                        window.table = $('#mydata').DataTable();
                        window.table.on('draw.dt', function () {
                            $('tbody>tr[role="row"').mouseout(function (e) {
                                $(this).css('background-color', '#fff');
                                $(this).css('cursor', 'hand');
                            });
                            $('tbody>tr[role="row"').mouseover(function (e) {
                                $(this).css('cursor', 'hand');
                                $(this).css('background-color', '#ddd');
                            });

                            $('input[type="checkbox"]').change(function () {
                                if (this.checked) {
                                    window.dataid = $(this).attr('KODE_PERIODE');
                                    window.selected = $(this).closest('tr');
                                    console.log(window.kodeperusahaan);
                                }
                            });
                        });

                    } else if (jdata.ret == '-2') {
                        alertError(jdata.msg);
                        window.table
                                .clear()
                                .draw();

                    } else {
                        alertError(jdata.msg);
                    }
                }
            });
        }

        function validateDigit(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
            var regex = /[0-9]|\./;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        }

    </script>
    <?php
    include "../../includes/footer_app_nosql.php";
    ?>
