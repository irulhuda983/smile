<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Detil Diagnosa";

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
          		case 'sc_kode_group_icd' : $sel1="selected"; break;
							case 'sc_nama_group_icd' : $sel2="selected"; break;
							case 'sc_kode_diagnosa' : $sel3="selected"; break;
							case 'sc_nama_diagnosa' : $sel4="selected"; break;
							case 'sc_kode_diagnosa_detil' : $sel5="selected"; break;
							case 'sc_nama_diagnosa_detil' : $sel6="selected"; break;	
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kode_group_icd" <?=$sel1;?>>Kode Group ICD</option> 
						<option value="sc_nama_group_icd" <?=$sel2;?>>Nama Group ICD</option>
						<option value="sc_kode_diagnosa" <?=$sel3;?>>Kode Diagnosa</option>
						<option value="sc_nama_diagnosa" <?=$sel4;?>>Nama Diagnosa</option>						
						<option value="sc_kode_diagnosa_detil" <?=$sel4;?>>Kode Detil Diagnosa</option>
						<option value="sc_nama_diagnosa_detil" <?=$sel5;?>>Nama Detil Diagnosa</option>
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
      if ($pilihsearch=="sc_kode_diagnosa_detil")
      {
       $filtersearch = "and upper(a.kode_diagnosa_detil) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_nama_diagnosa_detil")
      {
       $filtersearch = "and upper(a.nama_diagnosa_detil) like '%".$searchtxt."%' ";
      }
      elseif ($pilihsearch=="sc_kode_diagnosa")
      {
       $filtersearch = "and upper(b.kode_diagnosa) like '%".$searchtxt."%' ";
      }						
      elseif ($pilihsearch=="sc_nama_diagnosa")
      {
       $filtersearch = "and upper(b.nama_diagnosa) like '%".$searchtxt."%' ";
      }	
      elseif ($pilihsearch=="sc_kode_group_icd")
      {
       $filtersearch = "and upper(c.kode_group_icd) like '%".$searchtxt."%' ";
      } 
      elseif ($pilihsearch=="sc_nama_group_icd")
      {
       $filtersearch = "and upper(c.nama_group_icd) like '%".$searchtxt."%' ";
      } 			    			                		 
      else
      {
       $filtersearch = "and (upper(a.kode_diagnosa_detil) like '%".$searchtxt."%' or upper(a.nama_diagnosa_detil) like '%".$searchtxt."%' or upper(b.kode_diagnosa) like '%".$searchtxt."%' or upper(b.nama_diagnosa) like '%".$searchtxt."%' or upper(c.kode_group_icd) like '%".$searchtxt."%' or upper(c.nama_group_icd) like '%".$searchtxt."%') ";
      }
		}		
    // query with paging								
    $rows_per_page = 8;
    $url = 'pn5002_lovtapjkk1_diagnosa.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    $sql = "select a.kode_diagnosa_detil, a.nama_diagnosa_detil, a.kode_diagnosa, b.nama_diagnosa, b.kode_group_icd, c.nama_group_icd ".
           "from sijstk.pn_kode_diagnosa_detil a, sijstk.pn_kode_diagnosa b, sijstk.pn_kode_group_icd c ".
           "where a.kode_diagnosa = b.kode_diagnosa and b.kode_group_icd = c.kode_group_icd ".
					 "and nvl(a.status_nonaktif,'T')='T' ".
    			 $filtersearch.
           "order by a.no_urut ";
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
		echo "<th><a href=\"$PHP_SELF?f=c.nama_group_icd&o=$o\"><b>Group ICD</b></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=b.nama_diagnosa&o=$o\"><b>Nama Diagnosa</b></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=a.nama_diagnosa_detil&o=$o\"><b>Detil Diagnosa</b></a></th>";
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
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_DIAGNOSA_DETIL"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_DIAGNOSA_DETIL"])."'; ".        											
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["KODE_GROUP_ICD"]." - ".$row["NAMA_GROUP_ICD"]."</a>";?></td>		
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_DIAGNOSA_DETIL"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_DIAGNOSA_DETIL"])."'; ".        											
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["KODE_DIAGNOSA"]." - ".$row["NAMA_DIAGNOSA"]."</a>";?></td>							
      <td>
        <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_DIAGNOSA_DETIL"])."';".
				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_DIAGNOSA_DETIL"])."'; ".        											
        "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
        "window.close();\" >".$row["KODE_DIAGNOSA_DETIL"]." - ".$row["NAMA_DIAGNOSA_DETIL"]."</a>";?></td>			      																																																						
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
