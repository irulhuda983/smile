<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Klaim JKK RTW";

$pilihsearch = $_POST['pilihsearch'];
$searchtxt 	 = $_POST['searchtxt'];	

$formname = isset($_REQUEST['fname'])?$_REQUEST['fname']:'';
$fieldnamefocus = isset($_REQUEST['ffocus'])?$_REQUEST['ffocus']:'';
$fieldnamea = isset($_REQUEST['f1'])?$_REQUEST['f1']:'';
$fieldnameb = isset($_REQUEST['f2'])?$_REQUEST['f2']:'';
$fieldnamec = isset($_REQUEST['f3'])?$_REQUEST['f3']:'';
$fieldnamed = isset($_REQUEST['f4'])?$_REQUEST['f4']:'';
$fieldnamee = isset($_REQUEST['f5'])?$_REQUEST['f5']:'';
$fieldnamef = isset($_REQUEST['f6'])?$_REQUEST['f6']:'';
$fieldnameg = isset($_REQUEST['f7'])?$_REQUEST['f7']:'';
$fieldnameh = isset($_REQUEST['f8'])?$_REQUEST['f8']:'';
$fieldnamei = isset($_REQUEST['f9'])?$_REQUEST['f9']:'';
$fieldnamej = isset($_REQUEST['f10'])?$_REQUEST['f10']:'';
$fieldnamek = isset($_REQUEST['f11'])?$_REQUEST['f11']:'';
$fieldnamel = isset($_REQUEST['f12'])?$_REQUEST['f12']:'';
$fieldnamem = isset($_REQUEST['f13'])?$_REQUEST['f13']:'';
$fieldnamen = isset($_REQUEST['f14'])?$_REQUEST['f14']:'';

$formname = isset($_POST['fname'])?$_POST['fname']:$formname;
$fieldnamefocus = isset($_POST['ffocus'])?$_POST['ffocus']:$fieldnamefocus;
$fieldnamea = isset($_POST['f1'])?$_POST['f1']:$fieldnamea;
$fieldnameb = isset($_POST['f2'])?$_POST['f2']:$fieldnameb;
$fieldnamec = isset($_POST['f3'])?$_POST['f3']:$fieldnamec;
$fieldnamed = isset($_POST['f4'])?$_POST['f4']:$fieldnamed;
$fieldnamee = isset($_POST['f5'])?$_POST['f5']:$fieldnamee;
$fieldnamef = isset($_POST['f6'])?$_POST['f6']:$fieldnamef;
$fieldnameg = isset($_POST['f7'])?$_POST['f7']:$fieldnameg;
$fieldnameh = isset($_POST['f8'])?$_POST['f8']:$fieldnameh;
$fieldnamei = isset($_POST['f9'])?$_POST['f9']:$fieldnamei;
$fieldnamej = isset($_POST['f10'])?$_POST['f10']:$fieldnamej;
$fieldnamek = isset($_POST['f11'])?$_POST['f11']:$fieldnamek;
$fieldnamel = isset($_POST['f12'])?$_POST['f12']:$fieldnamel;
$fieldnamem = isset($_POST['f13'])?$_POST['f13']:$fieldnamem;
$fieldnamen = isset($_POST['f14'])?$_POST['f14']:$fieldnamen;
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
<input type="hidden" name='fname' value="<?=$fname;?>" />
<input type="hidden" name='ffocus' value="<?=$fieldnamefocus;?>" />
<input type="hidden" name='f1' value="<?=$fieldnamea;?>" />
<input type="hidden" name='f2' value="<?=$fieldnameb;?>" />
<input type="hidden" name='f3' value="<?=$fieldnamec;?>" />
<input type="hidden" name='f4' value="<?=$fieldnamed;?>" />
<input type="hidden" name='f5' value="<?=$fieldnamee;?>" />
<input type="hidden" name='f6' value="<?=$fieldnamef;?>" />
<input type="hidden" name='f7' value="<?=$fieldnameg;?>" />
<input type="hidden" name='f8' value="<?=$fieldnameh;?>" />
<input type="hidden" name='f9' value="<?=$fieldnamei;?>" />
<input type="hidden" name='f10' value="<?=$fieldnamej;?>" />
<input type="hidden" name='f11' value="<?=$fieldnamek;?>" />
<input type="hidden" name='f12' value="<?=$fieldnamel;?>" />
<input type="hidden" name='f13' value="<?=$fieldnamem;?>" />
<input type="hidden" name='f14' value="<?=$fieldnamen;?>" />
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
        		<option value="nama_faskes" <?php echo $pilihsearch=='nama_faskes'?'selected':'';?>>Nama Faskes</option>
                <option value="nama_jenis" <?php echo $pilihsearch=='nama_jenis'?'selected':'';?>>Jenis Faskes</option>
                <option value="nama_pic" <?php echo $pilihsearch=='nama_pic'?'selected':'';?>>Nama PIC</option>
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="50" style="background-color: #ccffff;  width:260px;">
          <input type="submit" name="cari2" value="GO" class="btn green">
          </div>	
        </td>
      </tr>
    </table>
				
		<?php
		//------------------------- start list data --------------------------------
      //filter data		
      $filtersearch='';
 		if(isset($searchtxt))
		{
            $searchtxt    = strtoupper($searchtxt);
            if ($pilihsearch=="nama_faskes")
            {
            $filtersearch = " and upper(a.NAMA_FASKES) like '%".$searchtxt."%' ";
            }
            elseif ($pilihsearch=="nama_jenis")
            {
            $filtersearch = " and upper(B.NAMA_JENIS) like '%".$searchtxt."%'  ";
            }	
            elseif ($pilihsearch=="nama_pic")
            {
            $filtersearch = " and upper(a.NAMA_PIC) like '%".$searchtxt."%' ";
            }
		}		
    // query with paging								
    $rows_per_page = 10;
    $url = 'pn5032_lov_faskes.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    $sql = "select A.NAMA_FASKES,A.NAMA_PIC,A.HANDPHONE_PIC,A.TELEPON_AREA_PEMILIK,A.TELEPON_PEMILIK,A.TELEPON_EXT_PEMILIK,
    b.NAMA_JENIS,c.NAMA_JENIS_DETIL,A.KODE_JENIS,A.KODE_JENIS_DETIL,A.KODE_FASKES
    from sijstk.tc_faskes A left outer join sijstk.tc_kode_jenis b on a.kode_jenis=b.kode_jenis
    left outer join sijstk.tc_kode_jenis_detil c on a.kode_jenis_detil=c.kode_jenis_detil
    where kode_status='ST3' {$filtersearch}
    order by A.NAMA_FASKES"; 
    // /echo $sql;
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
    <?php
    echo "<table  id=\"mydata\" cellspacing=\"0\" >";
    echo "<tr>";
    echo "<th class=awal>&nbsp;<b>Nama Faskes</b></th>";    
    echo "<th><b>Nama PIC</b></th>";
    echo "<th><b>Handphone</b></th>";
    echo "<th><b>Jenis</b></th>";
    echo "<th><b>Sub Jenis</b></th>";
    echo "<th><b>Telp Pemilik</b></th>";
    echo "<th><b>Ext Telp Pemilik</b></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    echo "<tr bgcolor=#".($nx%2 ? "fafafa" : "ffffff")." onclick=\"javascript:window.opener.document.{$formname}.{$fieldnamea}.value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_FASKES"])."';".
    "window.opener.document.{$formname}.{$fieldnameb}.value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_FASKES"])."'; ".
    "window.opener.document.{$formname}.{$fieldnamec}.value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PIC"])."'; ".
    "window.opener.document.{$formname}.{$fieldnamed}.value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_JENIS"])."'; ".
    "window.opener.document.{$formname}.{$fieldnamee}.value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_JENIS_DETIL"])."'; ".
    "window.opener.document.{$formname}.{$fieldnamef}.value='".ExtendedFunction::ereg_replace("'","\'",$row["TELEPON_AREA_PEMILIK"])."'; ".
    "window.opener.document.{$formname}.{$fieldnameg}.value='".ExtendedFunction::ereg_replace("'","\'",$row["TELEPON_PEMILIK"])."'; ".
    "window.opener.document.{$formname}.{$fieldnameh}.value='".ExtendedFunction::ereg_replace("'","\'",$row["TELEPON_EXT_PEMILIK"])."'; ".
    "window.opener.document.{$formname}.{$fieldnamei}.value='".ExtendedFunction::ereg_replace("'","\'",$row["HANDPHONE_PIC"])."'; ".
    "window.opener.document.getElementById('".$fieldnamefocus."').focus();window.close();\" class=\"gw_tr\" > ";
    ?>
      <td><?=$row["NAMA_FASKES"];?></td>
      <td><?=$row["NAMA_PIC"];?></td>
      <td><?=$row["HANDPHONE"];?></td>	
      <td><?=$row["NAMA_JENIS"];?></td>
      <td><?=$row["NAMA_JENIS_DETIL"];?></td>
      <td><?=$row["TELEPON_AREA_PEMILIK"];?> - <?=$row["TELEPON_PEMILIK"];?></td>																																																								
      <td><?=$row["TELEPON_EXT_PEMILIK"];?></td>
      </tr>
    <?php 
    $i++; $nx++;
    }
    ?>
    </table>	
    
    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&fname=".$formname."&ffocus=".$fieldnamefocus."&f1=".$fieldnamea."&f2=".$fieldnameb."&f3=".$fieldnamec."&f4=".$fieldnamed."&f5=".$fieldnamee."&f6=".$fieldnamef."&f7=".$fieldnameg."&f8=".$fieldnameh."&f9=".$fieldnamei."&f10=".$fieldnamej."&f11=".$fieldnamek."&f12=".$fieldnamel."&f13=".$fieldnamem."&f14=".$fieldnamen;?>
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
