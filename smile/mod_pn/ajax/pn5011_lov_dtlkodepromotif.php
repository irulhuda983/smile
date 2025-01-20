<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Perusahaan yg Memperoleh Manfaat Promotif/Preventif";

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

$formname=(!$a) ? "adminForm" : $a;	
$fieldnameb=(!$b) ? "porto_id" : $b;	
$fieldnamec=(!$c) ? "filc" : $c;	
$fieldnamed=(!$d) ? "fild" : $d;
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
$px=(!$p) ? "inv2301_entrydealingticket_deposito.php" : $p;
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
        <td align="right">Search By &nbsp
          <select name="pilihsearch">
            <? 
            switch($pilihsearch)
            {
          		case 'sc_kode_promotif' : $sel1="selected"; break;
							case 'sc_nama_perusahaan' : $sel2="selected"; break; 		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kode_promotif" <?=$sel1;?>>Kode Promotif</option>
						<option value="sc_nama_perusahaan" <?=$sel2;?>>Nama Perusahaan</option>     
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
 		if(isset($searchtxt))
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kode_promotif")
      {
       $filtersearch = "and upper(a.kode_promotif) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_nama_perusahaan")
      {
       $filtersearch = "and upper(a.nama_perusahaan) like '%".$searchtxt."%' ";
      }					                		 
      else
      {
       $filtersearch = "and (upper(a.kode_promotif) like '%".$searchtxt."%' or upper(a.nama_perusahaan) like '%".$searchtxt."%') ";
      }
		}		
    // query with paging								
    $rows_per_page = 20;
    $url = 'pn5011_lov_dtlkodepromotif.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    $sql = "select kode_promotif, kode_perusahaan, nama_perusahaan, npp, alamat_perusahaan,kode_kantor, ".
				 	 " 			 a.kode_kegiatan, a.kode_sub_kegiatan, ".
           " 			 (   select bentuk_kegiatan from sijstk.pn_kode_promotif_sub where kode_kegiatan = a.kode_kegiatan ".
           "     	 		 and to_char(a.tgl_promotif,'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd') ".
           "     			 and kode_sub_kegiatan = a.kode_sub_kegiatan ".
           "     			 and rownum = 1 ".
           " 					 ) bentuk_kegiatan ". 
           "from sijstk.pn_promotif a ".
           "where status_approval_pengajuan = 'DISETUJUI' ".
           "and kode_klaim is null  ".
					 "and kode_kegiatan = '$f' ".
					 "and kode_promotif like nvl('$e','%') ".
					 "and exists ".
           "( ".
           "   select null from sijstk.pn_kode_promotif_sub ".
           "   where kode_kegiatan = a.kode_kegiatan ".
           "   and kode_sub_kegiatan = a.kode_sub_kegiatan ".
           "   and bentuk_kegiatan = '$h' ".  
           ") ".					 
    			 $filtersearch.
           "order by kode_promotif ";
    //echo $sql;
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
    echo "<table  id=mydata cellspacing=0>";
    echo "<tr>";
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_promotif&o=$o\"><b>Kode Promotif</b></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=a.kode_promotif&o=$o\"><b>Bentuk Kegiatan</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.npp&o=$o\"><b>NPP</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.nama_perusahaan&o=$o\"><b>Perusahaan</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.alamat_perusahaan&o=$o\"><b>Alamat</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor&o=$o\"><b>Ktr</b></a></th>";
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
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ". 
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".        											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["KODE_PROMOTIF"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ". 
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".      											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["BENTUK_KEGIATAN"]."</a>";?></td>				
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ". 
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".      											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["NPP"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ". 
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".      											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["NAMA_PERUSAHAAN"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ".  
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".   											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["ALAMAT_PERUSAHAAN"]."</a>";?></td>
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROMOTIF"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ".   
				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BENTUK_KEGIATAN"])."'; ".    											
        "window.opener.document.getElementById('".$fieldnamed."').focus(); ".
        "window.close();\" >".$row["KODE_KANTOR"]."</a>";?></td>												     																																																								
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
  <p class="rgt">SIJSTK</p>
</div>
</form>
</body>
</html>
