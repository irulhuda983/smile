<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar IKS";

$key=$_GET['aid'];

$frm= $_GET['frm'];
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
$o	= $_GET['o'];
$p	= $_GET['p'];
$q	= $_GET['q'];	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$pagetitle;?></title>
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

<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>
<div id="container-popup">
<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->
		
<?php
    // query with paging								
$rows_per_page = 20;
//The unfiltered SELECT
$sql = "select KODE_IKS,NO_IKS,TO_CHAR(TGL_AWAL_IKS,'DD/MM/YYYY') TGL_AWAL_IKS,TO_CHAR(TGL_AKHIR_IKS,'DD/MM/YYYY') TGL_AKHIR_IKS, nvl(status_na,'Y') status_na,
        case 
            when nvl(KODE_ADDENDUM,'x')<>'x' then (select KODE_IKS || '|' || no_iks || '|' || to_char(tgl_akhir_iks,'YYYYMMDD') from tc.tc_iks where kode_faskes= '{$key}' and kode_iks=a.kode_addendum)
            else ''
        end NO_ADDENDUM
    from sijstk.tc_iks a
    WHERE KODE_FASKES='{$key}' order by KODE_IKS desc";
//echo $sql;
$total_rows  = f_count_rows($DB,$sql);
$total_pages = f_total_pages($total_rows, $rows_per_page);
$othervar		 = "";
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
    echo "<th class=awal><b>Kode IKS</b></th>";    
    echo "<th><b>No IKS</b></th>";
    echo "<th><b>Tgl Awal</b></th>";
    echo "<th><b>Tgl Akhir</b></th>";
    echo "<th><b>NA</b></th>";
    echo "<th><b>No Ref Addendum</b></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    /*echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$frm.".".$a.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_IKS"])."';".
    "window.opener.document.".$frm.".".$b.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_IKS"])."'; ".
    "window.opener.document.".$frm.".".$c.".value='".ExtendedFunction::ereg_replace("'","\'",$row["TGL_AWAL_IKS"])."'; ".
    "window.opener.document.".$frm.".".$d.".value='".ExtendedFunction::ereg_replace("'","\'",$row["TGL_AKHIR_IKS"])."'; ".
"window.close();\" class=\"gw_tr\" >";*/
    echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.setIKSlamp('".ExtendedFunction::ereg_replace("'","\'",$row["KODE_IKS"])."','".ExtendedFunction::ereg_replace("'","\'",$row["NO_IKS"])."','".ExtendedFunction::ereg_replace("'","\'",$row["TGL_AWAL_IKS"])."','".ExtendedFunction::ereg_replace("'","\'",$row["TGL_AKHIR_IKS"])."'); ".
"window.close();\" class=\"gw_tr\" >";
    ?>
      <td><?=$row["KODE_IKS"];?></td>
      <td><?=$row["NO_IKS"];?></td>
      <td><?=$row["TGL_AWAL_IKS"];?></td>	
      <td><?=$row["TGL_AKHIR_IKS"];?></td>
      <td><?=$row["STATUS_NA"];?></td>
      <td><?=$row["NO_ADDENDUM"];?></td>																						
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
        <?$othervar = $othervar."&frm=".$frm."&a=".$a."&b=".$b."&c=".$c."&d=".$d."";?>
        <b>Page :</b> <?php echo f_draw_pager('<?=$_PHPSELF;?>', $total_pages, $_GET['page'],$othervar); ?>
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

</body>
</html>
