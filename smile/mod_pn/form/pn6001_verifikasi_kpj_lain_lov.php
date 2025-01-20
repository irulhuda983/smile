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
        // query with paging                
        $rows_per_page = 10;
        $url = 'pn6001_verifikasi_kpj_lain_lov.php'; // url sama dengan nama file        
        //The unfiltered SELECT
        if(isset($searchtxt)){
        $sql = "
            SELECT  ROWNUM NO, A.* FROM 
            (
                SELECT aa.kode_tk, aa.kpj, aa.nomor_identitas, nama_tk, tempat_lahir, TO_CHAR(tgl_lahir, 'DD-MM-YYYY') tgl_lahir, aktif_tk, npp, nama_perusahaan, kode_na,
                rank() over (PARTITION BY aa.kode_tk,
                                            aa.kpj,
                                            aa.kode_perusahaan,
                                            aa.kode_divisi
                            ORDER BY no_mutasi DESC, tgl_na DESC)    urut 
                FROM sijstk.vw_kn_tk aa
                WHERE
                aa.flag_klaim_jht = 'T'
                AND aa.kode_segmen = 'PU'
                AND (SELECT COUNT(Y.NILAI_TRANSAKSI) FROM KN.KN_TK_SALDO Y WHERE Y.KODE_TK = aa.KODE_TK AND Y.TAHUN = TO_CHAR(SYSDATE, 'YYYY')) > 0
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
                ) A WHERE 1=1 AND A.urut = 1 AND NVL (A.kode_na, 'XX') NOT IN ('AG', 'AS') ORDER BY $orderby $order";

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
        $DB->parse($sql_utama);
        $DB->execute();
        $i=0;
        $nx=1;
        while ($row = $DB->nextrow()){
            $nik = $row['NOMOR_IDENTITAS'];
            $selected_obj = "var obj = {
                KPJ : '" . $row["KPJ"] . "',
                NOMOR_IDENTITAS : '" . $row["NOMOR_IDENTITAS"] . "',
                NAMA_TK : '" . $row["NAMA_TK"] . "',
                TEMPAT_LAHIR : '" . $row["TEMPAT_LAHIR"] . "',
                TGL_LAHIR : '" . $row["TGL_LAHIR"] . "',
                NPP : '" . $row["NPP"] . "',
                NAMA_PERUSAHAAN : '" . $row["NAMA_PERUSAHAAN"] . "',
                AKTIF_TK : '" . $row["AKTIF_TK"] . "'
            };" .
            "window.opener.fl_ls_get_lov_by_selected('NOMOR_IDENTITAS', obj);";
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KODE_TK"]."</a></td>";
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
