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


$kd_perihal = $_POST['kd_perihal'];
$kd_perihal = $kd_perihal == "" ? $_GET['kd_perihal'] : $kd_perihal;
if($kd_perihal == 'PP0303'){
    $kd_perihal = 'PP0302';
} else if($kd_perihal  == 'PP0304'){
    $kd_perihal = 'PP0301';
} else {
    $kd_perihal = '';
}

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
$orderby = $orderby == "" ? "C.KPJ" : $orderby;
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

                    <input type="hidden" id="kd_perihal" name="kd_perihal" value="<?=$_GET['kd_perihal'];?>">
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
                $filtersearch = "AND C.KPJ = '".$searchtxt."' ";
            } elseif ($pilihsearch=="sc_nik") {
                $filtersearch = "AND C.NOMOR_IDENTITAS ='".$searchtxt."' ";
            } else {
                $filtersearch = "AND 1=2";
            }
        }   
        // query with paging                
        $rows_per_page = 10;
        $url = 'pn6001_verifikasi_kpj_diakui_lov.php?kd_perihal='.$_GET['kd_perihal']; // url sama dengan nama file        
        //The unfiltered SELECT
        if(isset($searchtxt)){
        $sql = "
                SELECT *
                    FROM (  SELECT B.KODE_TK,
                                (SELECT X.KPJ
                                    FROM KN.KN_TK X
                                    WHERE X.KODE_TK = B.KODE_TK)
                                    KPJ,
                                A.NOMOR_IDENTITAS,
                                A.NAMA_LENGKAP,
                                A.TEMPAT_LAHIR,
                                A.TGL_LAHIR,
                                B.NAMA_PERUSAHAAN,
                                (SELECT C.NPP
                                    FROM KN.VW_KN_TK C
                                WHERE C.KODE_KEPESERTAAN = B.KODE_KEPESERTAAN AND ROWNUM = 1) NPP
                            FROM PN.PN_AGENDA_VERIFIKASI_JHT A,
                                PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP B
                            WHERE     A.KODE_AGENDA = B.KODE_AGENDA
                                AND A.STATUS_APPROVAL = 'Y'
                                AND A.STATUS_BATAL = 'T'
                                AND B.FLAG_DIAKUI = 'T'
                                AND A.KODE_JENIS_AGENDA_DETIL = '$kd_perihal'
                        ORDER BY A.KODE_AGENDA) C
                        WHERE 1=1
                        ".$filtersearch. $filtercustom ." 
                GROUP BY C.KODE_TK,
                        C.KPJ,
                        C.NOMOR_IDENTITAS,
                        C.NAMA_LENGKAP,
                        C.TGL_LAHIR,
                        C.TEMPAT_LAHIR,
                        C.NPP,
                        C.NAMA_PERUSAHAAN
                ORDER BY $orderby $order 
            ";
        }
        
        $total_rows = f_count_rows($DB,$sql);
        $total_pages = f_total_pages($total_rows, $rows_per_page);
        $othervar = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
        // CUSTOM FILTER
        $othervar = $othervar . "&f_kode_kantor=".$ls_kode_kantor;
        
        if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
            $_GET['page'] = 1;
        } else if ( $_GET['page'] > $total_pages ) {
            $_GET['page'] = $total_pages;
        }
        $start_row = f_page_to_row($_GET['page'], $rows_per_page);
        $jmlrow = $rows_per_page;
    ?>
    <?
        echo "<table  id=mydata cellspacing=0>";
        echo "<tr>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.kode_tk&order=$order\"><b>Kode TK</b></a></th>";    
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.kpj&order=$order\"><b>No. Peserta</b></a></th>";    
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.nomor_identitas&order=$order\"><b>NIK</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.nama_tk&order=$order\"><b>Nama TK</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.tempat_lahir&order=$order\"><b>Tempat Lahir</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=C.tgl_lahir&order=$order\"><b>Tgl Lahir</b></a></th>";
        echo "</tr>";

        // CUSTOM ORDER BY
        $othervar = $othervar . "&orderby=".$orderby."&order=".$order;
        $sql = f_query_perpage($sql, $start_row, $rows_per_page);
        $DB->parse($sql);
        $DB->execute();
        $i=0;
        $nx=1;
        while ($row = $DB->nextrow()){
            $nik = $row['NOMOR_IDENTITAS'];
            $selected_obj = "var obj = {
                KODE_TK : '" . $row["KODE_TK"] . "',
                KPJ : '" . $row["KPJ"] . "',
                NPP : '" . $row["NPP"] . "',
                NAMA_PERUSAHAAN : '" . $row["NAMA_PERUSAHAAN"] . "',
                NOMOR_IDENTITAS : '" . $row["NOMOR_IDENTITAS"] . "',
                NAMA_TK : '" . $row["NAMA_TK"] . "',
                TEMPAT_LAHIR : '" . $row["TEMPAT_LAHIR"] . "',
                TGL_LAHIR : '" . $row["TGL_LAHIR"] . "'
            };" .
            "window.opener.fl_ls_get_lov_by_selected('NOMOR_IDENTITAS', obj);";
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KODE_TK"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KPJ"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NOMOR_IDENTITAS"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_TK"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TEMPAT_LAHIR"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TGL_LAHIR"]."</a></td>";
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
