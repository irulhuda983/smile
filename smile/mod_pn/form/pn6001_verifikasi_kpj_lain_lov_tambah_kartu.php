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
$ls_nik    = !isset($_GET['nik']) ? $_POST['nik'] : $_GET['nik'];
$ls_kode_agenda    = !isset($_GET['kode_agenda']) ? $_POST['kode_agenda'] : $_GET['kode_agenda'];

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
        
    <?
        
        // query with paging                
        $rows_per_page = 10;
        $url = 'pn6001_verifikasi_kpj_lain_lov_tambah_kartu.php'; // url sama dengan nama file        
        //The unfiltered SELECT
        $sql_jml_kpj = "
        SELECT count(1) jml_kode_tk FROM 
        (
            SELECT aa.kode_tk
                FROM pn.pn_agenda_verifikasi_jht_keps aa
                WHERE     kode_agenda = '$ls_kode_agenda'
                    AND NOT EXISTS
                            (SELECT NULL
                                FROM PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP b
                                WHERE     b.kode_tk = aa.kode_tk
                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                    AND b.kode_agenda = aa.kode_agenda)
        ) A WHERE 1=1";
        $DB->parse($sql_jml_kpj);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_jml_kode_tk = $row['JML_KODE_TK'];
        
        $sql = "
        SELECT ROWNUM NO, A.* FROM 
        (
            SELECT aa.kode_tk,
                    (SELECT b.kpj
                        FROM kn.kn_tk b
                        WHERE b.kode_tk = aa.kode_tk) kpj,
                    (SELECT b.nomor_identitas
                        FROM kn.kn_tk b
                        WHERE b.kode_tk = aa.kode_tk) nomor_identitas,
                    (SELECT b.nama_lengkap
                        FROM pn.pn_agenda_verifikasi_jht b
                        WHERE b.kode_agenda = aa.kode_agenda) nama_tk,
                    (SELECT b.tempat_lahir
                        FROM pn.pn_agenda_verifikasi_jht b
                        WHERE b.kode_agenda = aa.kode_agenda) tempat_lahir,
                    (SELECT b.tgl_lahir
                        FROM pn.pn_agenda_verifikasi_jht b
                        WHERE b.kode_agenda = aa.kode_agenda) tgl_lahir,
                    aa.status_aktif_tk aktif_tk,
                    (SELECT b.npp
                        FROM kn.kn_perusahaan b
                        WHERE b.kode_perusahaan = aa.kode_perusahaan) npp,
                    aa.nama_perusahaan,
                    KN.F_KN_GET_STATUS_JHT (aa.kode_tk) STATUS_KLAIM_JHT
                FROM pn.pn_agenda_verifikasi_jht_keps aa
                WHERE     kode_agenda = '$ls_kode_agenda'
                    AND NOT EXISTS
                            (SELECT NULL
                                FROM PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP b
                                WHERE     b.kode_tk = aa.kode_tk
                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                    AND b.kode_perusahaan = aa.kode_perusahaan
                                    AND b.kode_agenda = aa.kode_agenda)
        ) A WHERE 1=1 ORDER BY $orderby $order ";
        

        // var_dump($sql); die();
        
        $total_rows = f_count_rows($DB,$sql);
        $total_pages = f_total_pages($total_rows, $rows_per_page);
        $othervar = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
        // CUSTOM FILTER
        $othervar = $othervar . "&f_kode_kantor=".$ls_kode_kantor."&nik=".$ls_nik."&kode_agenda=".$ls_kode_agenda;


        $start = 1;
        $end = 10; 
        
        if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] <= 1 ) {
            $_GET['page'] = 1;
            $start = 1;
            $end = 10;
        } else {
            $_GET['page'] = $total_pages;
            $start =($total_pages-1)*10+1;
            $end = ($total_pages-1)*10+10;
        }
        $start_row = f_page_to_row($_GET['page'], $rows_per_page);
        $jmlrow = $rows_per_page;


        $sql_utama = "SELECT X.* FROM($sql) X WHERE X.NO BETWEEN $start AND $end";
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
        echo "<th><a href=\"" . $PHP_SELF . "?$othervar&orderby=A.aktif_tk&order=$order\"><b>Status Klaim JHT</b></a></th>";   
        echo "</tr>";

        // CUSTOM ORDER BY
        $othervar = $othervar . "&orderby=".$orderby."&order=".$order;
        // $sql = f_query_perpage($sql, $start_row, $rows_per_page);
        $DB->parse($sql_utama);
        $DB->execute();
        $i=0;
        $nx=1;

        if($ls_jml_kode_tk > 1){
            while ($row = $DB->nextrow()){
                $ls_display_cek_klaim = '';
                $ls_display_name = '';
                if($row["STATUS_KLAIM_JHT"] != ''){
                   $ls_display_cek_klaim =  "style='pointer-events: none; color:red;'";
                   $ls_display_name = "style='pointer-events: none; color:red;'";
                }

                $nik = $row['NOMOR_IDENTITAS'];
                $selected_obj = "var obj = {
                    KPJ : '" . $row["KPJ"] . "',
                    KODE_TK : '" . $row["KODE_TK"] . "',
                    NOMOR_IDENTITAS : '" . $row["NOMOR_IDENTITAS"] . "',
                    NAMA_TK : '" . $row["NAMA_TK"] . "',
                    TEMPAT_LAHIR : '" . $row["TEMPAT_LAHIR"] . "',
                    TGL_LAHIR : '" . $row["TGL_LAHIR"] . "',
                    NPP : '" . $row["NPP"] . "',
                    NAMA_PERUSAHAAN : '" . $row["NAMA_PERUSAHAAN"] . "',
                    AKTIF_TK : '" . $row["AKTIF_TK"] . "'
                };" .
                "window.opener.fl_ls_get_lov_by_selected('TAMBAH_KARTU', obj);";
                echo "<tr ".$ls_display_cek_klaim." bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KODE_TK"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["KPJ"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NOMOR_IDENTITAS"]."</a></td>";
                echo "<td><a ".$ls_display_name." href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_TK"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TEMPAT_LAHIR"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["TGL_LAHIR"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NPP"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["NAMA_PERUSAHAAN"]."</a></td>";
                echo "<td><a href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["AKTIF_TK"]."</a></td>";
                echo "<td><a ".$ls_display_name." href=\"#\" onclick=\"javascript:" . $selected_obj . "window.close();\" >".$row["STATUS_KLAIM_JHT"]."</a></td>";
                echo "</tr>";
                $i++; $nx++;
            }
        } else {
                echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." class= \"gw_tr\">";
                echo "<td colspan=9 style='text-align:center'><b>--Data Tidak Ditemukan--</b></td>";
                echo "</tr>";
                $total_rows     = 0;
                $total_pages    = 0;
        }
        echo "</table> ";
    ?>
            <table aria-describedby="mydesc" class="paging">
                <tr><th></th></tr>
                <tr>
                    <td>Total Rows <strong><?=$total_rows; ?></strong> | Total Pages <strong><?=$total_pages; ?></strong></td>
                    <td height="15" align="right">
                    <strong>Page :</strong> <?php echo f_draw_pager($url, $total_pages, $_GET['page'], $othervar); ?>
                    </td>
                </tr>
            </table>
            <fieldset style="background-color:#F5F5F5; text-align:left;" ><legend>Keterangan</legend>
                <span style="color:red">*Kartu yang dapat diproses harus memiliki lebih dari satu kepesertaan <br> *Kartu yang dapat diproses belum pernah ada proses klaim JHT sebelumnya</span><br>
                </br>
            </fieldset>
            <div id="clear-bottom-popup"></div>
            
            <br>
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
