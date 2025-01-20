<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Pos";

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
<style>
.f_0 input,textarea, select  {
        border: 1px solid #dddddd;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
        padding:2px;
        font-size:10px;
        font-family: verdana, arial, tahoma, sans-serif;		
}
.gw_tr {cursor:pointer;}
.gw_tr:hover {background-color:#C0C0FF;}
</style>
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

    <table class="caption" >
      <tr> 
        <td colspan="2">
      		&nbsp;				
        </td>
        <td align="right">
            <div class="f_0">
            Search By &nbsp
          <select name="pilihsearch">
            <? 
            switch($pilihsearch)
            {
          		case 'sc_nama_kelurahan' : $sel1="selected"; break;
							case 'sc_nama_kecamatan' : $sel2="selected"; break;
							case 'sc_nama_kabupaten' : $sel3="selected"; break;
							case 'sc_nama_propinsi' : $sel4="selected"; break; 		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_nama_kelurahan" <?=$sel1;?>>Nama Kelurahan</option>
						<option value="sc_nama_kecamatan" <?=$sel2;?>>Nama Kecamatan</option>
						<option value="sc_nama_kabupaten" <?=$sel3;?>>Nama Kabupaten</option>
						<option value="sc_nama_propinsi" <?=$sel4;?>>Nama Propinsi</option>     
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="50" style="background-color: #ccffff;  width:260px;">
          <input type="submit" name="cari2" value="GO" class="btn green">
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
          <input type="hidden" id="vq" name="vq" value="<?=$q;?>">	</div>	
        </td>
      </tr>
    </table>
				
		<?
		//------------------------- start list data --------------------------------
  	//filter data		
 		if(isset($searchtxt))
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_nama_kelurahan")
      {
       $filtersearch = "and upper(a.nama_kelurahan) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_nama_kecamatan")
      {
       $filtersearch = "and upper(b.nama_kecamatan) like '%".$searchtxt."%' ";
      }	
      elseif ($pilihsearch=="sc_nama_kabupaten")
      {
       $filtersearch = "and upper(c.nama_kabupaten) like '%".$searchtxt."%' ";
      }							
      elseif ($pilihsearch=="sc_nama_propinsi")
      {
       $filtersearch = "and upper(d.nama_propinsi) like '%".$searchtxt."%' ";
      }					                		 
      else
      {
       $filtersearch = "and (upper(a.nama_kelurahan) like '%".$searchtxt."%' or upper(b.nama_kecamatan) like '%".$searchtxt."%' or upper(c.nama_kabupaten) like '%".$searchtxt."%' or upper(d.nama_propinsi) like '%".$searchtxt."%') ";
      }
		}		
    // query with paging								
    $rows_per_page = 10;
    $url = 'pn2401_lov_pos.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    $sql = "select a.kode_kelurahan, a.nama_kelurahan, a.kode_pos, a.kode_kecamatan, b.nama_kecamatan, ".
           "     b.kode_kabupaten, c.nama_kabupaten, c.kode_propinsi, d.nama_propinsi ".
           "from sijstk.ms_kelurahan a, sijstk.ms_kecamatan b, sijstk.ms_kabupaten c, sijstk.ms_propinsi d ".
           "where a.kode_kecamatan = b.kode_kecamatan and b.kode_kabupaten = c.kode_kabupaten ".
           "and c.kode_propinsi = d.kode_propinsi ".
           "and nvl(a.aktif,'T')='Y'  ".
    			 $filtersearch.
           "order by a.kode_kelurahan ";
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
    echo "<table  id=\"mydata\" cellspacing=\"0\" >";
    echo "<tr>";
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_kelurahan&o=$o\"><b>Kode</b></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=a.nama_kelurahan&o=$o\"><b>Kelurahan</b></a></th>";
    echo "<th><a href=\"$PHP_SELF?f=b.nama_kecamatan&o=$o\"><b>Kecamatan</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=c.nama_kabupaten&o=$o\"><b>Kabupaten</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=d.nama_propinsi&o=$o\"><b>Propinsi</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.kode_pos&o=$o\"><b>Kode Pos</b></a></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KELURAHAN"])."';".
    "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_KELURAHAN"])."'; ".
    "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KECAMATAN"])."'; ".
    "window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_KECAMATAN"])."'; ".
    "window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KABUPATEN"])."'; ".
    "window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_KABUPATEN"])."'; ".
    "window.opener.document.".$formname.".".$fieldnameh.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PROPINSI"])."'; ".
    "window.opener.document.".$formname.".".$fieldnamej.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PROPINSI"])."'; ".
    "window.opener.document.".$formname.".".$fieldnamek.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_POS"])."'; ".         											
"window.opener.document.getElementById('".$fieldnameb."').focus(); ".
"window.close();\" class=\"gw_tr\" >";
    ?>
      <td><?=$row["KODE_KELURAHAN"];?></td>
      <td><?=$row["NAMA_KELURAHAN"];?></td>
      <td><?=$row["NAMA_KECAMATAN"];?></td>	
      <td><?=$row["NAMA_KABUPATEN"];?></td>
      <td><?=$row["NAMA_PROPINSI"];?></td>
      <td><?=$row["KODE_POS"];?></td>																																																								
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
