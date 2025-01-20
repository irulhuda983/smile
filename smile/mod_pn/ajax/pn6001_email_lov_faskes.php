<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Faskes/BLK";

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
$orderby = $orderby == "" ? "KODE_FASKES" : $orderby;
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
        <table class="caption">
            <tr> 
                <td colspan="2">&nbsp;</td>
                <td align="right">Search By &nbsp
                    <select id="pilihsearch" name="pilihsearch">
                        <option value="sc_all">--ALL--</option>
                        <option value="sc_kode_faskes" <?=($pilihsearch == "sc_kode_faskes" ? "selected" : "")?>>Kode Faskes</option>
                        <option value="sc_no_faskes" <?=($pilihsearch == "sc_no_faskes" ? "selected" : "")?>>No Faskes</option>
                        <option value="sc_nama_faskes" <?=($pilihsearch == "sc_nama_faskes" ? "selected" : "")?>>Nama Faskes</option>
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
            if ($pilihsearch=="sc_kode_faskes") {
                $filtersearch = "and upper(a.kode_faskes) like '%".$searchtxt."%' ";
            } elseif ($pilihsearch=="sc_no_faskes") {
                $filtersearch = "and upper(a.no_faskes) like '%".$searchtxt."%' ";
            } elseif ($pilihsearch=="sc_nama_faskes") {
                $filtersearch = "and upper(a.nama_faskes) like '%".$searchtxt."%' ";
            } else {
                $filtersearch = "and (upper(a.kode_faskes) like '%".$searchtxt."%' or upper(a.no_faskes) like '%".$searchtxt."%' or upper(a.nama_faskes) like '%".$searchtxt."%') ";
            }
    }   
        // query with paging                
        $rows_per_page = 10;
        $url = 'pn6001_email_lov_faskes.php'; // url sama dengan nama file        
        //The unfiltered SELECT
        $sql = "
        SELECT  * FROM 
        (
            SELECT  C.KODE_FASKES,
                    B.NO_FASKES,
                    B.KODE_KANTOR,
                    B.NAMA_FASKES,
                    B.ALAMAT                              
            FROM TC.TC_REG_FASKES C, TC.TC_FASKES B
            WHERE C.KODE_FASKES = B.KODE_FASKES
            AND B.KODE_KANTOR = '$gs_kantor_aktif'
        ) A WHERE 1=1 ".$filtersearch. $filtercustom ." ORDER BY $orderby $order ";
        //echo $sql ;
        
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
        // echo "<th class=awal>&nbsp;<a href=\"" . $_SERVER['PHP_SELF'] . "?$othervar&order=$order\"><b>Kacab</b></a></th>";    
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=a.no_faskes&order=$order\"><b>No Faskes</b></a></th>";
    echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=a.nama_faskes&order=$order\"><b>Nama faskes</b></a></th>";
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=a.alamat&order=$order\"><b>Alamat</b></a></th>";    
        // echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=a.status&order=$order\"><b>Status</b></a></th>";   
        echo "</tr>";

        // CUSTOM ORDER BY
        $othervar = $othervar . "&orderby=".$orderby."&order=".$order;
    
        $sql = f_query_perpage($sql, $start_row, $rows_per_page);
        $DB->parse($sql);
        $DB->execute();
        $i=0;
        $nx=1;
        while ($row = $DB->nextrow()){
            $selected_obj = "var obj = {
                KODE_FASKES : '" . $row["KODE_FASKES"] . "',
                NO_FASKES : '" . $row["NO_FASKES"] . "',
                NAMA_FASKES : '" . $row["NAMA_FASKES"] . "',
                ALAMAT : '" . $row["ALAMAT"] . "'
            };" .
            "window.opener.fl_ls_get_lov_by_selected('KODE_FASKES', obj);";
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NO_FASKES"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_FASKES"]."</a></td>";
            echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["ALAMAT"]."</a></td>";
            // echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["STATUS"]."</a></td>";
            echo "</tr>";
            $i++; $nx++;
        }
        echo "</table>";
    ?>
            <table class="paging">
                <tr>
                    <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
                    <td height="15" align="right">
                    <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'], $othervar); ?>
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
