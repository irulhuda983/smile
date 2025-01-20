<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Pilih Tenaga Kerja";

$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$username = $_SESSION["USER"];

$pilihsearch = $_POST['pilihsearch'];
$pilihsearch = $pilihsearch == "" ? $_GET['pilihsearch'] : $pilihsearch;
$searchtxt = $_POST['searchtxt'];
$searchtxt = $searchtxt == "" ? $_GET['searchtxt'] : $searchtxt;

$orderby = $_POST["orderby"];
$orderby = $orderby == "" ? $_GET['orderby'] : $orderby;
$order = $_POST["order"];
$order = $order == "" ? $_GET['order'] : $order;
$order = $order == "" ? "" : ($order == "ASC" ? "DESC" : "ASC");

// DEFAULT ORDER
$orderby = $orderby == "" ? "A.KPJ" : $orderby;
$order = $order == "" ? "ASC" : $order;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?=$pagetitle;?></title>
    <meta name="Author" content="JroBalian" />
    <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
    <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
    <script type="text/javascript" src="../../javascript/calendar.js"></script>
    <script type="text/javascript" src="../../javascript/common.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
    <script type="text/javascript"></script>
    <style type="text/css">
        .gw_tr {cursor:pointer;}
        .gw_tr:hover {background-color:#C0C0FF;}
    </style>
</head>
<body>
    <form action="<?=$PHP_SELF;?>" method="post" id="lov" name="lov">
        <div id="header-popup"> 
            <h3><?=$gs_pagetitle;?></h3>
        </div>
        <div id="container-popup">
        <table  aria-describedby="mydesc" class="caption">
            <tr><th></th></tr>
            <tr> 
                <td colspan="2">&nbsp;</td>
                <td align="right">Search By &nbsp
                    <select id="pilihsearch" name="pilihsearch">
                        <option value="">--ALL--</option>
                        <option value="sc_nik" <?=($pilihsearch == "sc_nik" ? "selected" : "")?>>Nomor Identitas</option>
                        <option value="sc_kpj" <?=($pilihsearch == "sc_kpj" ? "selected" : "")?>>No. Peserta</option>
                    </select>
                    <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="15" style="background-color: #ccffff;">
                    <input type="submit" name="cari2" value="GO">

                    <input type="hidden" id="f_kode_kantor" name="f_kode_kantor" value="<?=$ls_kode_kantor;?>">
                    <input type="hidden" id="orderby" name="orderby" value="<?=$ls_orderby;?>">
                    <input type="hidden" id="order" name="order" value="<?=$ls_order;?>">
                    
                </td>
            </tr>
        </table>
    <?
        if(isset($searchtxt)) {
            $searchtxt = strtoupper($searchtxt);
            if  ($pilihsearch=="sc_kpj") {
                $filtersearch = "AND aa.kpj = '".$searchtxt."' ";
            } elseif ($pilihsearch=="sc_nik") {
                $filtersearch = "AND aa.nomor_identitas ='".$searchtxt."' ";
            } else {
                $filtersearch = "AND 1=2";
            }
        }  
        $start = 1;
        $end = 10; 
        $jumlah_kpj = 0;
        $rowCek = array();
        // query with paging                
        $rows_per_page = 10;
        $url = 'pn6001_verifikasi_kpj_dengan_satu_nomor_keps_lov.php'; // url sama dengan nama file        
        //The unfiltered SELECT
        if(isset($searchtxt)){
            $sql_cek_nik = "SELECT 
            a.kode_agenda,
            aa.nomor_identitas,
            b.status_approval,
            b.status_submit_tindak_lanjut,
            b.status_batal,
            AA.KPJ,
            a.kode_kantor,
            (SELECT c.nama_kantor FROM ms.vw_ms_kantor c WHERE c.kode_kantor = a.kode_kantor) nama_kantor
        FROM
            pn.pn_agenda_koreksi a
            INNER JOIN PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP atu on atu.KODE_AGENDA = a.kode_agenda and atu.FLAG_DIAKUI = 'T'
            INNER JOIN pn.PN_AGENDA_VERIFIKASI_JHT b ON a.kode_agenda = b.kode_agenda AND b.status_batal = 'T' and b.status_approval != 'Y' 
            LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil 
            LEFT JOIN sijstk.vw_kn_tk aa ON aa.nomor_identitas = b.nomor_identitas
        WHERE
            b.kode_jenis_agenda_detil = 'PP0305' AND ROWNUM = 1 $filtersearch";
        $DB->parse($sql_cek_nik);
        if($DB->execute()) {
            $rowCek = $DB->nextrow();
        }

            $sql = "
                SELECT  ROWNUM NO, A.* FROM 
                (
                    SELECT aa.kode_tk, aa.kpj, aa.nomor_identitas, nama_tk, tempat_lahir, TO_CHAR(tgl_lahir,'DD-MM-YYYY') tgl_lahir, aktif_tk, npp, nama_perusahaan, kode_na,
                    rank() over (PARTITION BY aa.kode_tk,
                                                aa.kpj,
                                                aa.kode_perusahaan,
                                                aa.kode_divisi
                                ORDER BY no_mutasi DESC, tgl_na DESC)    urut 
                    FROM sijstk.vw_kn_tk aa
                    WHERE
                    aa.kode_paket IN ('L', 'N')
                    AND aa.flag_klaim_jht = 'T'
                    AND aa.kode_segmen = 'PU'
                    ".$filtersearch. $filtercustom ." 
                    AND NOT EXISTS
                                (SELECT NULL
                                    FROM PN.PN_AGENDA_VERIFIKASI_JHT Y,
                                        PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP Z
                                    WHERE     Y.KODE_AGENDA = Z.KODE_AGENDA
                                        AND Z.KODE_TK = aa.KODE_TK
                                        AND Y.STATUS_APPROVAL = 'Y'
                                        AND Y.STATUS_BATAL = 'T'
                                        AND Z.FLAG_DIAKUI = 'T')
                    AND NOT EXISTS (SELECT NULL 
                                    FROM
                                        PN.PN_AGENDA_VERIFIKASI_JHT Y
                                    WHERE
                                         Y.NOMOR_IDENTITAS = aa.NOMOR_IDENTITAS 
                                        AND Y.STATUS_BATAL = 'T' 
                                        AND KODE_JENIS_AGENDA_DETIL IN ('PP0301', 'PP0302')
                                    )
                    ) A WHERE 1=1 AND A.urut = 1 AND NVL (A.kode_na, 'XX') NOT IN ('AG', 'AS') ORDER BY $orderby $order";
            
            $sql_jumlah_kpj = "SELECT COUNT(DISTINCT kpj) jumlah_kpj FROM ($sql)";
            $DB->parse($sql_jumlah_kpj);
            if($DB->execute()) {
                $rowid = $DB->nextrow();
            }
            $jumlah_kpj = $rowid['JUMLAH_KPJ'];
        }
        
        $total_rows = f_count_rows($DB,$sql);
        $total_pages = f_total_pages($total_rows, $rows_per_page);
        $othervar = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
        // CUSTOM FILTER
        $othervar = $othervar . "&f_kode_kantor=".$ls_kode_kantor;
        
        if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] <= 1 ) {
            $_GET['page'] = 1;
            $start = 1;
            $end = 10;
        } else {
            $_GET['page'] = $total_pages;
            $start =($total_pages-1)*10+1;
            $end = ($total_pages-1)*10+10;
        }
        if(isset($searchtxt)){
            $sql_utama = "SELECT X.* FROM($sql) X WHERE X.NO BETWEEN $start AND $end";
            // echo $sql_utama;
        }

        $jmlrow = $rows_per_page;
    ?>
    <?
        echo "<table  id=mydata cellspacing=0>";
        echo "<tr>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.kode_kepesertaan&order=$order\"><b>Kode TK</b></a></th>";    
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.kpj&order=$order\"><b>No. Peserta</b></a></th>";    
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.nomor_identitas&order=$order\"><b>NIK</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.nama_tk&order=$order\"><b>Nama TK</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.tempat_lahir&order=$order\"><b>Tempat Lahir</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.tgl_lahir&order=$order\"><b>Tgl Lahir</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.npp&order=$order\"><b>NPP</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.nama_perusahaan&order=$order\"><b>Nama Perusahaan</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.aktif_tk&order=$order\"><b>Status Aktif</b></a></th>";   
        echo "</tr>";

        // CUSTOM ORDER BY
        $othervar = $othervar . "&orderby=".$orderby."&order=".$order;
        // $sql = f_query_perpage($sql, $start_row, $rows_per_page);
        $i=0;
        $nx=1;
        if ($rowCek) {
            $nik = $rowCek['NOMOR_IDENTITAS'];
            $no_agenda = $rowCek['KODE_AGENDA'];
            $kode_kantor = $rowCek['KODE_KANTOR'];
            $nama_kantor = $rowCek['NAMA_KANTOR'];
            if ($rowCek['STATUS_APPROVAL'] == 'Y') {
                $sts = 'sudah proses approve KBL';
            } else if ($rowCek['STATUS_SUBMIT_TINDAK_LANJUT'] == 'Y') {
                $sts = 'masih dalam proses menunggu approval KBL';
            } else if ($rowCek['STATUS_SUBMIT_TINDAK_LANJUT'] == 'T') {
                $sts = 'masih dalam proses agenda';
            }

            $msg = "Terdapat data Agenda Koreksi dengan perihal Verifikasi Data Klaim yang $sts pada NIK $nik dengan kode agenda $no_agenda di $kode_kantor - $nama_kantor";
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
            echo "<td style='text-align:center' colspan=9>-- $msg --</td>";
            echo "</tr>";
            echo "<script>alert('$msg')</script>";
            $total_rows = 0;
        } else {
            if ($jumlah_kpj <= 1) {
                $DB->parse($sql_utama);
                if ($DB->execute()) {
                    $row = $DB->nextrow();
                    if ($row) {
                        // validasi untuk nik yang sedang dikoreksi
                        $kode_jenis_agenda_detil = $row["AKTIF_TK"] == 'y' ? 'PP0301' : 'PP0312';
                        $kode_tk = $row["KODE_TK"];
                        $sql_validasi = "SELECT
                                B.KODE_AGENDA,
                                B.KODE_JENIS_AGENDA_DETIL,
                                TGL_AGENDA
                            FROM
                                KN.KN_AGENDA B 
                            WHERE
                                B.KODE_TK = '$kode_tk'
                                AND TGL_AGENDA = TO_DATE( SYSDATE, 'DD/MM/RRRR' )
                                AND B.KODE_JENIS_AGENDA_DETIL = '$kode_jenis_agenda_detil'
                        ";
                        $msg_validasi = null; 
                        $DB->parse($sql_validasi);
                        if ($DB->execute()) {
                            $row_validasi = $DB->nextrow();
                            if ($row_validasi) {
                                $kd_agenda_validasi = $row_validasi['KODE_AGENDA'];
                                if ($row["AKTIF_TK"] = 'Y' and $kd_agenda_validasi != '') {
                                    $msg_validasi = "Koreksi perihal PESERTA TERVERIFIKASI TIDAK MEMILIKI KPJ DENGAN 1 (SATU) NOMOR KEPESERTAAN belum dapat dilakukan, karena sudah ada koreksi ELEMEN DATA TENAGA KERJA AKTIF di hari yang sama";
                                }

                                if ($row["AKTIF_TK"] = 'T' and $kd_agenda_validasi != '') {
                                    $msg_validasi = "Koreksi perihal PESERTA TERVERIFIKASI TIDAK MEMILIKI KPJ DENGAN 1 (SATU) NOMOR KEPESERTAAN belum dapat dilakukan, karena sudah ada koreksi ELEMEN DATA TENAGA KERJA NONAKTIF di hari yang sama";
                                }
                            }
                        }


                        if (!empty($msg_validasi)) {
                            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                            echo "<td style='text-align:center' colspan=9>-- $msg --</td>";
                            echo "</tr>";
                            echo "<script>alert('$msg_validasi')</script>";
                        } else {
                            $nik = $row['NOMOR_IDENTITAS'];
                            $selected_obj = "var obj = {
                                KPJ : '" . $row["KPJ"] . "',
                                NOMOR_IDENTITAS : '" . $row["NOMOR_IDENTITAS"] . "',
                                NAMA_TK : '" . $row["NAMA_TK"] . "',
                                TEMPAT_LAHIR : '" . $row["TEMPAT_LAHIR"] . "',
                                TGL_LAHIR : '" . $row["TGL_LAHIR"] . "',
                                NPP : '" . $row["NPP"] . "',
                                NAMA_PERUSAHAAN : '" . $row["NAMA_PERUSAHAAN"] . "',
                                AKTIF_TK : '" . $row["AKTIF_TK"] . "',
                                KODE_TK : '" . $kode_tk . "'
                            };" .
                            "window.opener.fl_ls_get_lov_by_selected('NOMOR_IDENTITAS', obj);";
                            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$kode_tk."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KPJ"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NOMOR_IDENTITAS"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_TK"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TEMPAT_LAHIR"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TGL_LAHIR"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NPP"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_PERUSAHAAN"]."</a></td>";
                            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["AKTIF_TK"]."</a></td>";
                            echo "</tr>";
                            $i++; $nx++;
                        }
                    } else {
                        echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                        echo "<td style='text-align:center' colspan=9>-- Data tidak ditemukan --</td>";
                        echo "</tr>";
                    }
                }
            } else {
                $msg = "Peserta terdaftar dengan lebih dari 1 KPJ";
                echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                echo "<td style='text-align:center' colspan=9>-- $msg --</td>";
                echo "</tr>";
                echo "<script>alert('$msg')</script>";
                $total_rows = 0;
            }
        }
        echo "</table> ";
    ?>
            <table  aria-describedby="mydesc" class="paging">
                <tr><th></th></tr>
                <tr>
                    <td>Total Rows <strong><?=$total_rows; ?></strong> | Total Pages <strong><?=$total_pages; ?></strong></td>
                    <td height="15" align="right">
                    <strong>Page :</strong> <?php echo f_draw_pager($url, $total_pages, $_GET['page'], $othervar); ?>
                    </td>
                </tr>
            </table>
            <div id="clear-bottom-popup"></div>
        </div> 

        <div id="footer-popup">
            <p class="lft"></p>
            <p class="rgt">SIJSTK</p>
        </div>
    </form>
</body>
</html>

<script language="javascript">

    function setDataId(nik){
        var nomor = nik;
           console.log(window.dataid);
        if(window.dataid != ''){
           window.onclick= window.opener.loadData(nomor);
		   window.close();
        }
        else {
          alert("Data Tidak Ditemukan!");
        } 
    }

</script>
