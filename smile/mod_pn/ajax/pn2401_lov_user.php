<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "DAFTAR KARYAWAN";
$gs_pagetitle = "Daftar Karyawan";
$USER = $_SESSION['USER'];
$KD_KANTOR  = $_SESSION['kdkantorrole'];

$p  = $_POST['vp'];
$a  = $_POST['va'];
$b  = $_POST['vb'];
$c  = $_POST['vc'];
$d  = $_POST['vd'];
$e  = $_POST['ve'];
$f  = $_POST['vf'];
$g  = $_POST['vg'];
$h  = $_POST['vh'];  
$j  = $_POST['vj'];
$k  = $_POST['vk'];
$l  = $_POST['vl'];
$m  = $_POST['vm'];
$n  = $_POST['vn'];
$q  = $_POST['vq'];
$pilihsearch = $_POST['pilihsearch'];
$searchtxt   = $_POST['searchtxt']; 
$orderby     = $_POST['orderby']==null?$_GET['orderby']:$_POST['orderby'];
$order       = $_POST['order']==null?$_GET['order']:$_POST['order'];
$order       = $order==null?"desc": ($order=="desc"?"asc":"desc");

if ($a=="")
{
  $p  = $_GET['p'];
  $a  = $_GET['a'];
  $b  = $_GET['b'];
  $c  = $_GET['c'];
  $d  = $_GET['d'];
  $e  = $_GET['e'];
  $f  = $_GET['f'];
  $g  = $_GET['g'];
  $h  = $_GET['h'];
  $j  = $_GET['j'];
  $k  = $_GET['k'];
  $l  = $_GET['l'];
  $m  = $_GET['m'];
  $n  = $_GET['n'];
  $q  = $_GET['q']; 
  $pilihsearch = $_GET['pilihsearch'];
  $searchtxt   = $_GET['searchtxt'];  
}

$formname=(!$a) ? "formreg" : $a; 
$fieldnameb=(!$b) ? "kode_kacab" : $b;  
$fieldnamec=(!$c) ? "nm_pic_kacab" : $c;  
$fieldnamed=(!$d) ? "kode_pic_cabang" : $d;
$fieldnamef=(!$f) ? "file" : $e;
$fieldnamef=(!$f) ? "filf" : $f;
$fieldnameg=(!$g) ? "filg" : $g;
$fieldnameh=(!$h) ? "filh" : $h;
$fieldnamej=(!$j) ? "filj" : $j;
$fieldnamek=(!$k) ? "filk" : $k;
$fieldnamel=(!$l) ? "fill" : $l;
$fieldnamem=(!$m) ? "film" : $m;
$fieldnamen=(!$n) ? "filn" : $n;
$fieldnameq=(!$q) ? "filq" : $q;    
$px=(!$p) ? "pn2401.php" : $p;

// echo $fieldnameb; die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
</head>
<body>
<form action="<?=$PHP_SELF;?>" method="post" id="lov_inv_porto" name="lov_inv_porto">
<div id="header-popup"> 
<h3><?=$gs_pagetitle;?></h3>
</div>
<div id="container-popup">
<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->
    <table class="caption">
      <tr> 
        <td colspan="2">
          &nbsp;        
        </td>
        <td align="right">Search By &nbsp;
          <select name="pilihsearch">
            <? 
            switch($pilihsearch)
            {
              case 'sc_kode_user' : $sel1="selected"; break;
              case 'sc_nama_user' : $sel2="selected"; break;   
              case 'sc_email' : $sel3="selected"; break;     
            }
            ?>
            <option value="sc_all">--ALL--</option>
            <option value="sc_kode_user" <?=$sel1;?>>Kode User</option>
            <option value="sc_nama_user" <?=$sel2;?>>Nama User</option>
            <option value="sc_email" <?=$sel3;?>>Email</option>     
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="15" style="background-color: #ccffff;">
          <input type="submit" name="cari2" value="GO">
          <input type="hidden" id="vp" name="vp" value="<?=$p;?>">
          <input type="hidden" id="va" name="va" value="<?=$a;?>">
          <input type="hidden" id="vb" name="vb" value="<?=$b;?>">
          <input type="hidden" id="vc" name="vc" value="<?=$c;?>">    
          <input type="hidden" id="vd" name="vd" value="<?=$d;?>">
          <input type="hidden" id="ve" name="ve" value="<?=$e;?>">
          <input type="hidden" id="vf" name="vf" value="<?=$f;?>">
          <input type="hidden" id="vg" name="vg" value="<?=$g;?>">
          <input type="hidden" id="vh" name="vh" value="<?=$h;?>">  
          <input type="hidden" id="vj" name="vj" value="<?=$j;?>">
          <input type="hidden" id="vk" name="vk" value="<?=$k;?>">
          <input type="hidden" id="vl" name="vl" value="<?=$l;?>">  
          <input type="hidden" id="vm" name="vm" value="<?=$m;?>">
          <input type="hidden" id="vn" name="vn" value="<?=$n;?>">  
          <input type="hidden" id="vq" name="vq" value="<?=$q;?>">    
        </td>
      </tr>
    </table>
        
    <?
    //------------------------- start list data --------------------------------
    //filter data   
    $get_kode_kantor = $_GET['d'];
    if(isset($searchtxt))
    {

      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kode_user")
      {
       $filtersearch = "and upper(mpk.kode_user) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_nama_user")
      {
       $filtersearch = "and upper(su.nama_user) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_email")
      {
       $filtersearch = "and upper(su.email) like '%".$searchtxt."%' ";
      }                                
      else
      {
       $filtersearch = "and (upper(kode_user) like '%".$searchtxt."%' or upper(nama_user) like '%".$searchtxt."%' or upper(email) like '%".$searchtxt."%') "; 
      }
    }  

      $sqlusr = "SELECT KODE_TIPE
                  FROM SMILE.MS_KANTOR
                  WHERE KODE_KANTOR ='".$e."'";
      $DB->parse($sqlusr);
      $DB->execute();
      $row = $DB->nextrow();
      $dataKantor = $row['KODE_TIPE'];
      // $lssearch = "(56)";
      if($dataKantor=='0'||$dataKantor=='2'){$f='1';}
      elseif ($dataKantor=='1') {$f='2';}
      elseif ($dataKantor=='3') {$f='3';}
      elseif ($dataKantor=='4'||$dataKantor=='5') {$f='4';}
    
    
    $ls_orderby = "";
    if($orderby != null){
      $ls_orderby = "ORDER BY $orderby $order";
    }
    // query with paging     
    
    $rows_per_page = 10;
    $url = 'pn2401_lov_user.php'; // url sama dengan nama file 
      // $sql = " SELECT *
      // FROM (SELECT DISTINCT (UFG.KODE_USER), UFG.KODE_KANTOR, UFG.KODE_FUNGSI, FSG.INISIAL_FUNGSI, FSG.NAMA_FUNGSI, USR.NAMA_USER, USR.EMAIL
      //       FROM SMILE.SC_USER_FUNGSI UFG
      //            LEFT JOIN SMILE.SC_FUNGSI FSG
      //               ON FSG.KODE_FUNGSI = UFG.KODE_FUNGSI
      //            LEFT JOIN SMILE.SC_USER USR ON USR.KODE_USER = UFG.KODE_USER
      //            LEFT JOIN SMILE.MS_PEJABAT_KANTOR JBK ON JBK.KODE_USER = UFG.KODE_USER 
      //      WHERE UFG.STATUS_FUNGSI = 'Y' AND UFG.KODE_KANTOR = '".$e."') 
      // WHERE KODE_FUNGSI ='5' AND 1=1 ".$filtersearch." ".$ls_orderby ;

      $sql = "SELECT
                  MJ.NAMA_JABATAN AS INISIAL_FUNGSI,
                  MPK.KODE_USER,
                  MPK.KODE_KANTOR,
                  MPK.KODE_JABATAN,
                  MJ.NAMA_JABATAN AS NAMA_JABATAN,
                  SU.NAMA_USER,
                  SU.EMAIL
              FROM
                  MS.MS_PEJABAT_KANTOR MPK
              JOIN
                  MS.MS_JABATAN MJ ON MPK.KODE_JABATAN = MJ.KODE_JABATAN
              JOIN
                  MS.SC_USER SU ON MPK.KODE_USER = SU.KODE_USER
              WHERE
                  MPK.KODE_JABATAN IN ('37', '703')
                  AND MPK.KODE_KANTOR = '".$e."'".$filtersearch." ".$ls_orderby;

      // echo $sql; die;
    $total_rows  = f_count_rows($DB,$sql);
    $total_pages = f_total_pages($total_rows, $rows_per_page);
    $othervar    = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
    $_GET['page'] = 1;
    } else if ( $_GET['page'] > $total_pages ) {
    $_GET['page'] = $total_pages;
    }
    $start_row = f_page_to_row($_GET['page'], $rows_per_page);
    $jmlrow    = $rows_per_page;
    ?>
    <?
    $ordervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";
    echo "<table id=mydata cellspacing=0>";
    echo "<tr>";
    // echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=inisial_fungsi&order=$order\"><b>Role User</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=kode_user&order=$order\"><b>Kode User</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=nama_user&order=$order\"><b>Nama User</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=nama_jabatan&order=$order\"><b>Jabatan</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=email&order=$order\"><b>Email</b></a></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff").">";
    ?>
      <!-- <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."';".
        "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"]." - ".$row["NAMA_USER"])."'; ". 
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."'; ".                
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["INISIAL_FUNGSI"]."</a>";?></td> -->
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."';".
        "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"]." - ".$row["NAMA_USER"])."'; ".   
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."'; ".                
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["KODE_USER"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."';".
        "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"]." - ".$row["NAMA_USER"])."'; ".    
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."'; ".               
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["NAMA_USER"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."';".
        "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"]." - ".$row["NAMA_USER"])."'; ".     
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."'; ".              
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["NAMA_JABATAN"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."';".
        "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"]." - ".$row["NAMA_USER"])."'; ".   
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_USER"])."'; ".              
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["EMAIL"]."</a>";?></td>                                                                                      
      </tr>
    <? 
    $i++; $nx++;
    }
    
    ?>
    </table>  
    
    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";?>
        <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>
    <?
    //---------------------------------------- end list data -------------------------
    ?>
  <div id="clear-bottom-popup"></div>
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SMILE - SISTEM INFORMASI PERLINDUNGAN PEKERJA</p>
</div>
</form>
</body>
</html>
