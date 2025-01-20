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
            <option value="sc_all">--ALL--</option>
            <option value="sc_kode_kantor" <?php echo $pilihsearch=='sc_kode_kantor'?'selected':'';?>>Kode Kantor</option>
            <option value="sc_nama_kantor"  <?php echo $pilihsearch=='sc_nama_kantor'?'selected':'';?>>Nama Kantor</option>
            <option value="sc_kode_tipe"  <?php echo $pilihsearch=='sc_kode_tipe'?'selected':'';?>>Kode Tipe</option>
            <option value="sc_nama_kacab_induk"  <?php echo $pilihsearch=='sc_nama_kacab_induk'?'selected':'';?>>Nama Kacab Induk</option>
            <option value="sc_kode_group_kanwil"  <?php echo $pilihsearch=='sc_kode_group_kanwil'?'selected':'';?>>Kode Group Kanwil</option>
            <option value="sc_nama_kabupaten"  <?php echo $pilihsearch=='sc_nama_kabupaten'?'selected':'';?>>Nama Kabupaten</option>
            <option value="sc_nama_kota"  <?php echo $pilihsearch=='sc_nama'?'selected':'';?>>Nama Kota</option>     
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="50" style="background-color: #ccffff;  width:140px;">
          <input type="submit" name="cari2" value="GO" class="btn green">
          </div>	
        </td>
      </tr>
    </table>
				
    <?php
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
    $url = 'pn5031_lov_dstkantor.php'; // url sama dengan nama file				
    //The unfiltered SELECT
    $sql = "select kode_kantor, nama_kantor, kode_tipe, nama_tipe,kode_kacab_induk,nama_kacab_induk, ".
           "     kode_group_kanwil,nama_group_kanwil,kode_kabupaten,nama_kabupaten,kode_kota,nama_kota ".
           "from ".
           "( ".       
           "     select a.kode_kantor, a.nama_kantor, a.kode_tipe, (select nama_tipe from sijstk.ms_kantor_tipe where kode_tipe=a.kode_tipe) nama_tipe, ".    
           "         case when a.kode_tipe in ('4','5') then ".
           "             (   select a.kode_kantor_induk from sijstk.ms_kantor ". 
           "                 where kode_kantor  = a.kode_kantor_induk ".               
           "             ) ".        
           "         else ".
           "             null ".
           "         end kode_kacab_induk, ".
           "         case when a.kode_tipe in ('4','5') then ".
           "             (   select nama_kantor from sijstk.ms_kantor ". 
           "                 where kode_kantor  = a.kode_kantor_induk ".               
           "             ) ".        
           "         else ".
           "             null ".
           "         end nama_kacab_induk, ".
           "         case when a.kode_tipe in ('4','5') then ".
           "             (   select kode_kantor from sijstk.ms_kantor ". 
           "                 where kode_kantor in ". 
           "                 (   select kode_kantor_induk from sijstk.ms_kantor ". 
           "                     where kode_kantor = a.kode_kantor_induk ".
           "                     ) ".
           "             ) ". 
           "         when a.kode_tipe = '3' then ".               
           "             a.kode_kantor_induk ". 
           "         else ".
           "             null ".        
           "         end kode_group_kanwil, ".        
           "         case when a.kode_tipe in ('4','5') then ".
           "             (   select nama_kantor from sijstk.ms_kantor ". 
           "                 where kode_kantor in ". 
           "                 (   select kode_kantor_induk from sijstk.ms_kantor ". 
           "                     where kode_kantor = a.kode_kantor_induk ".
           "                     ) ".
           "             ) ". 
           "         when a.kode_tipe = '3' then ".               
           "             (   select nama_kantor from sijstk.ms_kantor ". 
           "                 where kode_kantor = a.kode_kantor_induk ".                
           "             ) ".
           "         else ".
           "             null ".        
           "         end nama_group_kanwil, ".
           "         a.kode_kabupaten, b.nama_kabupaten, a.kode_kota, c.nama_kota ".
           "     from sijstk.ms_kantor a, sijstk.ms_kabupaten b, sijstk.ms_kota c ".
           "     where a.kode_kabupaten = b.kode_kabupaten(+) and a.kode_kota = c.kode_kota(+) ".
           "     and a.kode_tipe not in ('0','1','2') ".
           ") tt where 1=1 and kode_kantor<>'{$gs_kantor_aktif}'".
    			 $filtersearch;
           //"order by a.kode_kepemilikan ";
    // echo $sql;
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
    echo "<th class=awal>&nbsp;<b>Kode Kantor</b></th>";    
    echo "<th><b>Nama Kantor</b></th>";
    echo "<th><b>Tipe</b></th>";
    echo "<th><b>Kacab Induk</b></th>";
    echo "<th><b>Group Kanwil</b></th>";
    echo "<th><b>Kabupaten</b></th>";
    echo "<th><b>Kota</b></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    echo "<tr bgcolor=#".($nx%2 ? "fafafa" : "ffffff")." onclick=\"javascript:window.opener.document.getElementById('{$fieldnamea}').value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."';".
    "window.opener.document.getElementById('{$fieldnameb}').value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_KANTOR"])."'; ".
    "window.opener.document.getElementById('".$fieldnamefocus."').focus(); ".
    "window.close();\" class=\"gw_tr\" >";
    ?>
      <td><?=$row["KODE_KANTOR"];?></td>
      <td><?=$row["NAMA_KANTOR"];?></td>
      <td><?=$row["KODE_TIPE"];?></td>
      <td><?=$row["KODE_KACAB_INDUK"];?></td>
      <td><?=$row["KODE_GROUP_KANWIL"];?></td>
      <td><?=$row["NAMA_KABUPATEN"];?></td>
      <td><?=$row["NAMA_KOTA"];?></td>
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
        <?$othervar = $othervar."&fname=".$formname."&ffocus=".$fieldnamefocus."&f1=".$fieldnamea."&f2=".$fieldnameb."&f3=".$fieldnamec."&f4=".$fieldnamed."&f5=".$fieldnamee."&f6=".$fieldnamef."&f7=".$fieldnameg."&f8=".$fieldnameh."&f9=".$fieldnamei."&f10=".$fieldnamej."&m=".$m."&n=".$n."&q=".$q."";?>
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
