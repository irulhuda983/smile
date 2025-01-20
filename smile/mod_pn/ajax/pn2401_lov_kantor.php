<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "LIST KANTOR";
$gs_pagetitle = "Daftar Kantor Tujuan";
$USER = $_SESSION['USER'];
$KODE_KANTOR  = $_SESSION['kdkantorrole'];

$p	= $_POST['vp'];
$a	= $_POST['va'];
$b	= $_POST['vb'];
$c	= $_POST['vc'];
$d	= $_POST['vd'];  
$e	= $_POST['ve'];
$f	= $_POST['vf'];
$g	= $_POST['vg'];
$h	= $_POST['vh'];
$j	= $_POST['vj'];
$k	= $_POST['vk'];
$l	= $_POST['vl'];
$m	= $_POST['vm'];
$n	= $_POST['vn'];
$q	= $_POST['vq'];
$pilihsearch = $_POST['pilihsearch'];
$searchtxt 	 = $_POST['searchtxt'];	
$orderby     = $_POST['orderby'] == null?$_GET['orderby']:$_POST['orderby'];
$order       = $_POST['order'] == null?$_GET['order']:$_POST['order'];
$order       = $order==null?"desc": ($order=="desc"?"asc":"desc");
	  
if ($a=="")
{
  $p	= $_GET['p'];
  $a	= $_GET['a'];
  $b	= $_GET['b'];
  $c	= $_GET['c'];
  $d	= $_GET['d'];
  $e	= $_GET['e'];
  $f	= $_GET['f'];
  $g	= $_GET['g'];
  $h	= $_GET['h'];
	$j	= $_GET['j'];
	$k	= $_GET['k'];
	$l	= $_GET['l'];
	$m	= $_GET['m'];
	$n	= $_GET['n'];
	$q	= $_GET['q'];	
	$pilihsearch = $_GET['pilihsearch'];
	$searchtxt 	 = $_GET['searchtxt'];	
}

$formname=(!$a) ? "formreg" : $a; 
$fieldnameb=(!$b) ? "kode_kacab" : $b;  
$fieldnamec=(!$c) ? "kode_nama_kantor" : $c;  
$fieldnamed=(!$d) ? "kode_kantor" : $d;
$fieldnamee=(!$e) ? "file" : $e;
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
          		case 'sc_kode_kantor' : $sel1="selected"; break;
							case 'sc_nama_kantor' : $sel2="selected"; break;
							case 'sc_kode_tipe' : $sel3="selected"; break;
							case 'sc_nama_kacab_induk' : $sel4="selected"; break;
							case 'sc_kode_group_kanwil' : $sel5="selected"; break;
							case 'sc_nama_kabupaten' : $sel6="selected"; break;
							case 'sc_nama_kota' : $sel7="selected"; break;		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kode_kantor" <?=$sel1;?>>Kode Kantor</option>
						<option value="sc_nama_kantor" <?=$sel2;?>>Nama Kantor</option>
						<option value="sc_kode_tipe" <?=$sel3;?>>Kode Tipe</option>
						<option value="sc_nama_kacab_induk" <?=$sel4;?>>Nama Kacab Induk</option>
						<option value="sc_kode_group_kanwil" <?=$sel5;?>>Kode Group Kanwil</option>
						<option value="sc_nama_kabupaten" <?=$sel6;?>>Nama Kabupaten</option>
						<option value="sc_nama_kota" <?=$sel7;?>>Nama Kota</option>     
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
          <input type="hidden" id="orderby" name="orderby" value="<?=$orderby;?>">
          <input type="hidden" id="order" name="order" value="<?=$order;?>">    
        </td>
      </tr>
    </table>
				
		<?
		//------------------------- start list data --------------------------------
  	//filter data		
 		if(isset($searchtxt))
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kode_kantor")
      {
       $filtersearch = "and upper(tt.kode_kantor) like '%".$searchtxt."%' ";
      }
      else if ($pilihsearch=="sc_nama_kantor")
      {
       $filtersearch = "and upper(tt.nama_kantor) like '%".$searchtxt."%' ";
      }			
      else if ($pilihsearch=="sc_kode_tipe")
      {
       $filtersearch = "and upper(tt.kode_tipe) like '%".$searchtxt."%' ";
      }
      else if ($pilihsearch=="sc_nama_kacab_induk")
      {
       $filtersearch = "and upper(tt.nama_kacab_induk) like '%".$searchtxt."%' ";
      }	
      else if ($pilihsearch=="sc_kode_group_kanwil")
      {
       $filtersearch = "and upper(tt.kode_group_kanwil) like '%".$searchtxt."%' ";
      }	
      else if ($pilihsearch=="sc_nama_kabupaten")
      {
       $filtersearch = "and upper(tt.nama_kabupaten) like '%".$searchtxt."%' ";
      }
      else if ($pilihsearch=="sc_nama_kota")
      {
       $filtersearch = "and upper(tt.nama_kota) like '%".$searchtxt."%' ";
      }																					                		 
      else
      {
       $filtersearch = "and (upper(tt.kode_kantor) like '%".$searchtxt."%' or upper(tt.nama_kantor) like '%".$searchtxt."%' or upper(tt.kode_tipe) like '%".$searchtxt."%' or upper(tt.nama_kacab_induk) like '%".$searchtxt."%' or upper(tt.kode_group_kanwil) like '%".$searchtxt."%' or upper(tt.nama_kabupaten) like '%".$searchtxt."%' or upper(tt.nama_kota) like '%".$searchtxt."%') ";
      }
		}		
    // query with paging								
    $rows_per_page = 10;
    $url = 'pn2401_lov_kantor.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    if($KODE_KANTOR == '1' ||
        $KODE_KANTOR == '2' ||
        $KODE_KANTOR == '3' ||
        $KODE_KANTOR == '4' ||
        $KODE_KANTOR == '5' ||
        $KODE_KANTOR == '6' ||
        $KODE_KANTOR == '7' ||
        $KODE_KANTOR == '8' ||
        $KODE_KANTOR == '9' ||
        $KODE_KANTOR == '10' ||
        $KODE_KANTOR == '11' ||
        $KODE_KANTOR == '12')
        {
          $qrytipektr="SELECT KODE_TIPE, KODE_KANTOR, KODE_KANTOR_INDUK FROM SMILE.MS_KANTOR
                          WHERE KODE_KANTOR_INDUK = '".$KODE_KANTOR."' 
                          AND KODE_TIPE = '2'";

    } else{
      $qrytipektr="SELECT KODE_TIPE, KODE_KANTOR, KODE_KANTOR_INDUK FROM SMILE.MS_KANTOR
      WHERE KODE_KANTOR = '".$KODE_KANTOR."'";
    }
    // echo $qrytipektr;
    $DB->parse($qrytipektr);
    $DB->execute();
    $get_tipe = $DB->nextrow();
    $tipe_ktr = $get_tipe['KODE_TIPE'];
    $kd_ktr_induk = $get_tipe['KODE_KANTOR_INDUK'];
    // echo "tipe ".$tipe_ktr." - jenis_kantor ";

    //kodekantor login user
    if($tipe_ktr == '0'){
      $filterbytipe= " WHERE 1=1 ";
    }
    else if($tipe_ktr == '1'){
      $filterbytipe= " KODE_KANTOR = '".$KODE_KANTOR."' 
                       OR KODE_KANTOR = '".$KODE_KANTOR."' 
                       OR KODE_TIPE = 0
                       OR KODE_TIPE IN(SELECT KODE_TIPE FROM SMILE.MS_KANTOR WHERE KODE_KANTOR = '".$KODE_KANTOR."' )
                       OR KODE_GROUP_KANWIL = '".$KODE_KANTOR."' ";
    }
    else if($tipe_ktr == '2'){
      $filterbytipe= "START WITH
                      kode_kantor = '".$kd_ktr_induk."' 
                    CONNECT BY
                      PRIOR kode_kantor = kode_kantor_induk ";
    }             
    else if($tipe_ktr == '3'){
      $filterbytipe= "START WITH
                      kode_kantor = '".$KODE_KANTOR."' 
                    CONNECT BY
                      PRIOR kode_kantor = kode_kantor_induk ";
    }
    else if($tipe_ktr == '4' || $tipe_ktr == '5'){
      $filterbytipe= "START WITH
                      kode_kantor = '".$KODE_KANTOR."' 
                    CONNECT BY
                      PRIOR kode_kantor = kode_kantor_induk ";
    }
    else{
      $filterbytipe= " 1=1 ";
    }
// 
    $ls_orderby = "";
    if($orderby != null){
      $ls_orderby = "ORDER BY $orderby $order";
    }
    // echo $f;
    $sql = "SELECT *
            FROM (SELECT KODE_KANTOR, NAMA_KANTOR, KODE_TIPE, NAMA_TIPE, KODE_KANTOR_INDUK, KODE_KACAB_INDUK, NAMA_KACAB_INDUK,
                         KODE_GROUP_KANWIL, NAMA_GROUP_KANWIL, KODE_KABUPATEN, NAMA_KABUPATEN, KODE_KOTA, NAMA_KOTA, KODE_USER,
                         (SELECT NAMA_USER FROM MS.SC_USER WHERE KODE_USER = TT.KODE_USER) AS NAMA_USER
                    FROM (          
                    SELECT A.KODE_KANTOR,
                                 A.NAMA_KANTOR,
                                 A.KODE_TIPE,
                                 A.KODE_KANTOR_INDUK,
                                 (SELECT NAMA_TIPE
                                    FROM SMILE.MS_KANTOR_TIPE
                                   WHERE KODE_TIPE = A.KODE_TIPE)
                                    NAMA_TIPE,
                                 CASE
                                    WHEN A.KODE_TIPE IN ('4', '5')
                                    THEN
                                       (SELECT A.KODE_KANTOR_INDUK
                                          FROM SMILE.MS_KANTOR
                                         WHERE KODE_KANTOR = A.KODE_KANTOR_INDUK)
                                    WHEN A.KODE_TIPE = '3'
                                    THEN
                                       A.KODE_KANTOR
                                    ELSE
                                       NULL
                                 END
                                    KODE_KACAB_INDUK,
                                 CASE
                                    WHEN A.KODE_TIPE IN ('4', '5')
                                    THEN
                                       (SELECT NAMA_KANTOR
                                          FROM SMILE.MS_KANTOR
                                         WHERE KODE_KANTOR = A.KODE_KANTOR_INDUK)
                                    WHEN A.KODE_TIPE = '3'
                                    THEN
                                       A.NAMA_KANTOR
                                    ELSE
                                       NULL
                                 END
                                    NAMA_KACAB_INDUK,
                                 CASE
                                    WHEN A.KODE_TIPE IN ('4', '5')
                                    THEN
                                       (SELECT KODE_KANTOR
                                          FROM SMILE.MS_KANTOR
                                         WHERE KODE_KANTOR IN
                                                  (SELECT KODE_KANTOR_INDUK
                                                     FROM SMILE.MS_KANTOR
                                                    WHERE KODE_KANTOR =
                                                             A.KODE_KANTOR_INDUK))
                                    WHEN A.KODE_TIPE = '3'
                                    THEN
                                       A.KODE_KANTOR_INDUK
                                    ELSE
                                       NULL
                                 END
                                    KODE_GROUP_KANWIL,
                                 CASE
                                    WHEN A.KODE_TIPE IN ('4', '5')
                                    THEN
                                       (SELECT NAMA_KANTOR
                                          FROM SMILE.MS_KANTOR
                                         WHERE KODE_KANTOR IN
                                                  (SELECT KODE_KANTOR_INDUK
                                                     FROM SMILE.MS_KANTOR
                                                    WHERE KODE_KANTOR =
                                                             A.KODE_KANTOR_INDUK))
                                    WHEN A.KODE_TIPE = '3'
                                    THEN
                                       (SELECT NAMA_KANTOR
                                          FROM SMILE.MS_KANTOR
                                         WHERE KODE_KANTOR = A.KODE_KANTOR_INDUK)
                                    ELSE
                                       NULL
                                 END
                                    NAMA_GROUP_KANWIL,
                                 A.KODE_KABUPATEN,
                                 B.NAMA_KABUPATEN,
                                 A.KODE_KOTA,
                                 C.NAMA_KOTA,
                                 CASE
                                    WHEN A.KODE_TIPE IN ('4', '5')
                                    THEN
                                       (SELECT KODE_USER FROM SMILE.MS_PEJABAT_KANTOR
                                       WHERE KODE_KANTOR = A.KODE_KANTOR
                                       AND KODE_JABATAN = '702'
                                       AND ROWNUM = 1)
                                    WHEN A.KODE_TIPE = '3'
                                    THEN
                                        (SELECT KODE_USER FROM SMILE.MS_PEJABAT_KANTOR
                                       WHERE KODE_KANTOR = A.KODE_KANTOR
                                       AND KODE_JABATAN = '47'
                                       AND ROWNUM = 1)
                                    WHEN A.KODE_TIPE = '2'
                                    THEN
                                        (SELECT KODE_USER FROM SMILE.MS_PEJABAT_KANTOR
                                       WHERE KODE_KANTOR = A.KODE_KANTOR
                                       AND KODE_JABATAN IN ('88','661')
                                       AND ROWNUM = 1)
                                    ELSE
                                       NULL
                                 END
                                    KODE_USER
                            FROM SMILE.MS_KANTOR A,
                                 SMILE.MS_KABUPATEN B,
                                 SMILE.MS_KOTA C
                           WHERE     A.KODE_KABUPATEN = B.KODE_KABUPATEN(+)
                                 AND A.KODE_KOTA = C.KODE_KOTA(+)
                                 AND A.AKTIF = 'Y'
                                 ) tt
                                  )tt 
                                  $filterbytipe";
                  //AND ".$lssearch." ".$filtersearch." ".$ls_orderby ; 
                  //   WHERE ".$filterbytipe.")tt 
                  // WHERE tt.KODE_TIPE in ".$lssearch." ".$filtersearch." ".$ls_orderby ; //.
           //"order by a.kode_kepemilikan ";
    //  echo $sql;  die;
    $total_rows  = f_count_rows($DB,$sql);
    $total_pages = f_total_pages($total_rows, $rows_per_page);
    $othervar		 = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
    $_GET['page'] = 1;
    } else if ( $_GET['page'] > $total_pages ) {
    $_GET['page'] = $total_pages;
    }
    $start_row = f_page_to_row($_GET['page'], $rows_per_page);
    $jmlrow		 = $rows_per_page;
    ?>
    <?
    // 
    $ordervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";
    echo "<table  id=mydata cellspacing=0>";
    echo "<tr>";
    // 
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.kode_kantor&order=$order\"><b>Kode Kantor</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.nama_kantor&order=$order\"><b>Nama Kantor</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.kode_tipe&order=$order\"><b>Tipe</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.nama_kacab_induk&order=$order\"><b>Kacab Induk</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.nama_group_kanwil&order=$order\"><b>Group Kanwil</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.nama_kabupaten&order=$order\"><b>Kabupaten</b></a></th>";    
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?$ordervar&orderby=tt.nama_kota&order=$order\"><b>Kota</b></a></th>";
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
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["KODE_KANTOR"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["NAMA_KANTOR"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  	
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".		
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["KODE_TIPE"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".    
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["KODE_KACAB_INDUK"]."-".$row["NAMA_KACAB_INDUK"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["KODE_GROUP_KANWIL"]."-".$row["NAMA_GROUP_KANWIL"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ". 			
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".	
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["NAMA_KABUPATEN"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  	
        "window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".
        "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"])."'; ".  
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();window.opener.clear_petugas();\" >".$row["NAMA_KOTA"]."</a>";?></td>																																																																	
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
