<?php
$pagetype = "form";
$gs_pagetitle = "MB8011 - INPUT KEBUTUHAN/TARGET KEPESERTAAN";
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
            modal: true,
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
                }],
            listeners: {
                close: function () {
                },
                destroy: function (wnd, eOpts) {
                    //loadDataTk();
                    //window.dataid_tk = '';
                }
            }
        });
        openwin.show();
        return openwin;
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
                        <a id="btn_close" href="http://<?= $HTTP_HOST; ?>/mod_mb/form/mb8011.php?mid=<?= $mid; ?>">
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
                                <input type="hidden" name="TYPE" value="EDIT">
                                <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Segmentasi Keps <span style="color:#ff0000;">*</span></label>
                                    <select size="1" id="kode_segmen" name="kode_segmen" value="" class="select_format" style="width:230px;" disabled>
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
                                    <label>Periode <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="periode" name="periode" value="" class="select_format" style="width:70px;" disabled>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select tahun from sijstk.mb_blanko_periode where tahun between to_char(sysdate,'yyyy') and to_char(sysdate,'yyyy') + 1 order by tahun asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["TAHUN"] . "\">" . $row["TAHUN"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jenis Blanko <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="kode_jenis" name="kode_jenis" value="" class="select_format" style="width:230px;" disabled>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select KODE_JENIS, NAMA_JENIS from sijstk.MB_BLANKO_KODE_JENIS where STATUS_NONAKTIF = 'T' order by NO_URUT asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["KODE_JENIS"] . "\">" . $row["NAMA_JENIS"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>

                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jumlah Target </label>	
                                    <input  type="numeric" name="jml_kebutuhan" id="jml_kebutuhan" value="<?= $ls_jml_kebutuhan; ?>" size="35" tabindex="2" onkeyup = "javascript:this.value = Comma(this.value);"  onkeypress="return isNumberKey(event)" style="text-align:right;" readonly class="disabled" >
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Keterangan </label>
                                    <textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?= $ls_keterangan; ?>" tabindex="5" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled">
                                    </textarea>   
                                </div>
                                <div class="clear"></div>
                            </fieldset>
                            <?php
                            // detail target masing-masing kantor cabang
                            //include '../ajax/mb8011_target_kacab.php';
                            ?>
                            <br>
                            <br>
                            <fieldset>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Kantor Wilayah</label>	
                                    <select size="1" id="kode_wilayah" name="kode_wilayah" value="" class="select_format" style="width:230px;">
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select distinct KODE_WILAYAH, (select a.NAMA_KANTOR from MS.MS_KANTOR a WHERE a.KODE_KANTOR = KODE_WILAYAH ) NAMA_WILAYAH from KN.VW_MS_KANTOR_BLANKO order by KODE_WILAYAH asc ";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["KODE_WILAYAH"] . "\">" . $row["NAMA_WILAYAH"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-row_kanan">
                                    <label></label>	
                                    <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
                                </div>
                                <div class="clear"></div>
                                <table id="tabletargetkacab" class="table responsive-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="10%" class="align-center" style="vertical-align: middle;">Action</th>
                                            <th scope="col" width="15%">Kode Kantor</th>
                                            <th scope="col">Nama Kantor</th>
                                            <th scope="col" width="15%">Jml Kebutuhan</th>	
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
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
                                <input type="hidden" name="TYPE" value="VIEW">
                                <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Segmentasi Keps <span style="color:#ff0000;">*</span></label>
                                    <select size="1" id="kode_segmen" name="kode_segmen" value="" class="select_format" style="width:230px;" disabled>
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
                                    <label>Periode <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="periode" name="periode" value="" class="select_format" style="width:70px;" disabled>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select tahun from sijstk.mb_blanko_periode where tahun between to_char(sysdate,'yyyy') and to_char(sysdate,'yyyy') + 1 order by tahun asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["TAHUN"] . "\">" . $row["TAHUN"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jenis Blanko <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="kode_jenis" name="kode_jenis" value="" class="select_format" style="width:230px;" disabled>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select KODE_JENIS, NAMA_JENIS from sijstk.MB_BLANKO_KODE_JENIS where STATUS_NONAKTIF = 'T' order by NO_URUT asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["KODE_JENIS"] . "\">" . $row["NAMA_JENIS"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>

                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jumlah Target </label>	
                                    <input  type="numeric" name="jml_kebutuhan" id="jml_kebutuhan" value="<?= $ls_jml_kebutuhan; ?>" size="35" tabindex="2" onkeyup = "javascript:this.value = Comma(this.value);"  onkeypress="return isNumberKey(event)" style="text-align:right;" readonly class="disabled" >
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Keterangan </label>
                                    <textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?= $ls_keterangan; ?>" tabindex="5" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled">
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
                                <legend>Entri Data Periode Blanko </legend>
                                <input type="hidden" name="TYPE" value="NEW">
                                <input type="hidden" id="DATAID" name="DATAID" value="<?= $_REQUEST["dataid"]; ?>">
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Segmentasi Keps <span style="color:#ff0000;">*</span></label>
                                    <select size="1" id="kode_segmen" name="kode_segmen" value="" class="select_format" style="width:230px;">
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
                                    <label>Periode <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="periode" name="periode" value="" class="select_format" style="width:70px;">
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select tahun from sijstk.mb_blanko_periode where tahun between to_char(sysdate,'yyyy') and to_char(sysdate,'yyyy') + 1 order by tahun asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["TAHUN"] . "\">" . $row["TAHUN"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jenis Blanko <span style="color:#ff0000;">*</span></label>	
                                    <select size="1" id="kode_jenis" name="kode_jenis" value="" class="select_format" style="width:230px;">
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $sql = "select KODE_JENIS, NAMA_JENIS from sijstk.MB_BLANKO_KODE_JENIS where STATUS_NONAKTIF = 'T' order by NO_URUT asc";
                                        $DB->parse($sql);
                                        $DB->execute();
                                        while ($row = $DB->nextrow()) {
                                            echo "<option value=\"" . $row["KODE_JENIS"] . "\">" . $row["NAMA_JENIS"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="clear"></div>

                                <div class="clear"></div>
                                <div class="form-row_kiri">
                                    <label>Jumlah Target </label>	
                                    <input  type="numeric" name="jml_kebutuhan" id="jml_kebutuhan" value="<?= $ls_jml_kebutuhan; ?>" size="35" tabindex="2" onkeyup = "javascript:this.value = Comma(this.value);"  onkeypress="return isNumberKey(event)" style="text-align:right;" readonly class="disabled" >
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
            window.kodejenis = '';
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
                ]
            });
            window.onload = loadData();

            window.onload = loadDataKacabTarget($('#kode_jenis').val());

            $('#btn_view').click(function () {
                if (window.dataid != '') {
                    window.location = 'mb8011.php?task=View&tasktk=View&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
                } else {
                    alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
                }

            });
            $('#btn_edit').click(function () {
                if (window.dataid != '') {
                    window.location = 'mb8011.php?task=Edit&tasktk=Edit&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
                } else {
                    alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
                }
            });
            $('#btn_new').click(function () {
                window.location = 'mb8011.php?task=New&dataid=' + window.dataid + '&mid=<?= $mid; ?>';
            });
            $('#btn_delete').click(function () {
                if (window.dataid != '') {
                    var r = confirm("Apakah anda yakin ?");
                    if (r == true) {
                        $.ajax({
                            type: 'POST',
                            url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_action.php?' + Math.random(),
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
                window.kodejenis = '<?= $_REQUEST["kodejenis"]; ?>';
    <?PHP
    if ($_REQUEST["task"] == "View") {
        ?>
                    setTimeout(function () {
                        preload(true);
                    }, 100);
                    $.ajax({
                        type: 'POST',
                        url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_action.php?' + Math.random(),
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
                                $('#periode').val(jdata.data[0].KODE_PERIODE);
                                $('#kode_segmen').val(jdata.data[0].KODE_SEGMEN);
                                $('#kode_jenis').val(jdata.data[0].KODE_JENIS);
                                $('#jml_kebutuhan').val(jdata.data[0].JML_KEBUTUHAN);
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
                        url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_action.php?' + Math.random(),
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
                                $('#periode').val(jdata.data[0].KODE_PERIODE);
                                $('#kode_segmen').val(jdata.data[0].KODE_SEGMEN);
                                $('#kode_jenis').val(jdata.data[0].KODE_JENIS);
                                $('#jml_kebutuhan').val(jdata.data[0].JML_KEBUTUHAN);
                                $('#keterangan').val(jdata.data[0].KETERANGAN);
                                /*
                                 var config = {
                                 ajax: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab_query.php?kode_jenis=' + $('#kode_jenis').val() + '&mid' + Math.random(),
                                 columnDefs: [
                                 {targets: 0, data: "KODE_KANTOR"},
                                 {targets: 1, data: "NAMA_KANTOR"},
                                 {targets: 2, data: "JML_KEBUTUHAN"}
                                 ]
                                 };
                                 */

                                loadDataKacabTarget($('#kode_jenis').val());
                            }
                        }
                    });
                    $('#btn_save').click(function () {
                        if ($('#tahun').val() != '' && $('#tgl_aktif').val() != '' && $('#tgl_nonaktif').val() != ''
                                && $('#status_nonaktif').val() != '') {
                            preload(true);
                            $.ajax({
                                type: 'POST',
                                url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_action.php?' + Math.random(),
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
                                url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_action.php?' + Math.random(),
                                data: $('#formreg').serialize(),
                                success: function (data) {
                                    preload(false);
                                    console.log($('#formreg').serialize());
                                    console.log(data);
                                    //console.log(JSON.parse(data));
                                    jdata = JSON.parse(data);
                                    if (jdata.ret == '0') {
                                        window.parent.Ext.notify.msg('Berhasil', jdata.msg);
                                        window.location = 'mb8011.php?task=Edit&dataid=' + $('#tahun').val() + '&mid=<?= $mid; ?>';
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
                url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_query.php?' + Math.random(),
                data: {TYPE: $('#type').val(), KEYWORD: $('#keyword').val()},
                success: function (data) {
                    preload(false);
                    //console.log(data);
                    jdata = JSON.parse(data);
                    if (jdata.ret == 0) {
                        for (i = 0; i < jdata.data.length; i++) {
                            window.table.row.add([
                                '<input type="checkbox" KODE_JENIS = "' + jdata.data[i].KODE_JENIS + '" KODE_KEBUTUHAN="' + jdata.data[i].KODE_KEBUTUHAN + '" id="CHECK_' + i + '" urut="' + i + '" name="CHECK[' + i + ']"><input type="hidden" name="KODE_KEBUTUHAN[' + i + ']" id="KODE_KEBUTUHAN' + i + '" value="' + jdata.data[i].KODE_KEBUTUHAN + '">',
                                '<div style="text-align:center !important">' + jdata.data[i].KODE_PERIODE + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].NAMA_JENIS + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].JML_KEBUTUHAN + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].JML_BLANKO_TAMBAHAN + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].TOTAL_BLANKO_KEBUTUHAN + '</div>',
                                jdata.data[i].KETERANGAN
                            ]).draw();
                        }

                        $('input[type="checkbox"]').change(function () {
                            if (this.checked) {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
                                window.selected = $(this).closest('tr');
                                console.log(window.dataid);
                                console.log(window.kodejenis);
                            } else {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
                            }
                        });
                        $('input[type="hidden"]').change(function () {
                            if (this.clicked) {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
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
                                    window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                    window.kodejenis = $(this).attr('KODE_JENIS');
                                    window.selected = $(this).closest('tr');
                                    console.log(window.kodejenis);
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


        function loadDataKacabTarget($kode_jenis)
        {
            $.ajax({
                type: 'POST',
                url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab_query.php?kode_jenis=' + $kode_jenis + '&mid' + Math.random(),
                data: {TYPE: $('#type').val(), KEYWORD: $('#keyword').val()},
                success: function (data) {
                    preload(false);
                    //console.log(data);
                    jdata = JSON.parse(data);
                    if (jdata.ret == 0) {
                        for (i = 0; i < jdata.data.length; i++) {
                            $parameter = jdata.data[i].KODE_SEGMEN + ',' + jdata.data[i].KODE_PERIODE + ',' + jdata.data[i].KODE_JENIS + ',' + jdata.data[i].KODE_KANTOR + ',' + jdata.data[i].KODE_KEBUTUHAN ;
                            window.table.row.add([
                                '<div style="text-align:center !important"><a href="#" onclick="NewWindow4(\'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab.php?kode_kebutuhan=\' , \'\', 800, 600, 1)"> <font color = "#009999" > TARGET </font></a> </div>',
                                '<div style="text-align:center !important">' + jdata.data[i].KODE_KANTOR + '</div>',
                                '<div style="text-align:left !important">' + jdata.data[i].NAMA_KANTOR + '</div>',
                                '<div style="text-align:center !important">' + jdata.data[i].JML_KEBUTUHAN + '</div>'
                            ]).draw();
                        }

                        $('input[type="checkbox"]').change(function () {
                            if (this.checked) {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
                                window.selected = $(this).closest('tr');
                                console.log(window.dataid);
                                console.log(window.kodejenis);
                            } else {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
                            }
                        });
                        $('input[type="hidden"]').change(function () {
                            if (this.clicked) {
                                window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                window.kodejenis = $(this).attr('KODE_JENIS');
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
                        window.table = $('#tabletargetkacab').DataTable();
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
                                    window.dataid = $(this).attr('KODE_KEBUTUHAN');
                                    window.kodejenis = $(this).attr('KODE_JENIS');
                                    window.selected = $(this).closest('tr');
                                    console.log(window.kodejenis);
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

        function Comma(Num) { //function to add commas to textboxes
            Num += '';
            Num = Num.replace(',', '');
            Num = Num.replace(',', '');
            Num = Num.replace(',', '');
            Num = Num.replace(',', '');
            Num = Num.replace(',', '');
            Num = Num.replace(',', '');
            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }

        function isNumberKey(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
            if (key.length == 0)
                return;
            var regex = /^[0-9\b]+$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        }
        
        function inputTargetKacab() {
            NewWindow4('http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab.php?kode_kebutuhan=' , '', 800, 600, 1);
        }

        function inputTargetKacab2(p_kode_segmen, p_periode, p_kode_jenis_blanko, p_kode_kantor, p_kode_kebutuhan) {
            NewWindow4('http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab.php?kode_segmen=' + p_kode_segmen + '&kode_periode=' + p_periode + ',kode_jenis_blanko=' + p_kode_jenis_blanko +
                    '&kode_kantor=' + p_kode_kantor + '&kode_kebutuhan=' + p_kode_kebutuhan, '', 800, 600, 1);
        }

        function inputTargetKacab1(p_kode_segmen, p_periode, p_kode_jenis_blanko, p_kode_kantor, p_kode_kebutuhan) {
            console.log(p_kode_report);
            preload(true);
            $.ajax({
                url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab_action.php?kodekebutuhan=' + p_kode_kebutuhan + Math.random(),
                //url: 'http://<?= $HTTP_HOST; ?>/mod_mb/ajax/mb8011_target_kacab_action.php?kodekebutuhan=' + 
                type: 'POST',
                data: {TYPE: 'INPUT_TARGET', KODE_SEGMEN: p_kode_segmen, PERIODE: p_periode, KODE_JENIS_BLANKO: p_kode_jenis_blanko, KODE_KANTOR: p_kode_kantor, KODE_KEBUTUHAN: p_kode_kebutuhan},
                success: function (data) {
                    preload(false);
                    NewWindow(data, '', 800, 600, 1);
                }, error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        }

    </script>
    <?php
    include "../../includes/footer_app_nosql.php";
    ?>
