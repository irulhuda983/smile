<?
$pagetype="report";
$gs_pagetitle = "PN5005 - PEMBATALAN KLAIM";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
include "../../style/gridstyle.css";
$mid = $_REQUEST["mid"];
//$gs_kode_segmen = "PU";
/*--------------------- Form History -----------------------------------------
File: pn5044.php

Deskripsi:
-----------
File ini dipergunakan untuk pembatalan klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
Hist: - 19/11/2017 : Pembuatan Form (Tim SIJSTK)	
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/

$ld_tgl_trans1_display	= !isset($_GET['tgl_trans1_display']) ? $_POST['tgl_trans1_display'] : $_GET['tgl_trans1_display'];							
$ld_tgl_trans2_display	= !isset($_GET['tgl_trans2_display']) ? $_POST['tgl_trans2_display'] : $_GET['tgl_trans2_display'];
if ($ld_tgl_trans1_display=="")
{
  $sql = "select to_char(sysdate-3,'dd/mm/yyyy') tgl1,to_char(sysdate,'dd/mm/yyyy') tgl2 from dual";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ld_tgl_trans1_display = $data["TGL1"];	
  $ld_tgl_trans2_display = $data["TGL2"];	 	 
}
$ls_rg_kategori				= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
//kategori -------------------------------------------------------
if ($ls_rg_kategori=="")
{
 	 $ls_rg_kategori = "6";
}	
//--------------------- start button action ----------------------------------

if($trigersubmit=='2')
{
	$msg = '';
	// Proses akan dimulai dari data yang pertama sampai data terakhir
	for($i=0, $max_i=ExtendedFunction::count($cebox); $i<$max_i; $i++) 
	{
		if (${"s".$cebox[$i]} !="")
    {
  			//pembatalan klaim --------------------------------------------------
    		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_BATAL_KLAIM('".${"kode_klaim".$cebox[$i]}."','$username',:p_sukses,:p_mess);END;";											 	
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        $DB->execute();				
        $ls_sukses = $p_sukses;	
    		$ls_mess = $p_mess;	
				
				if ($ls_sukses=="1")
				{		
					$msg .= "Pembatalan klaim untuk kode klaim ".${"kode_klaim".$cebox[$i]}." berhasil..."."<br>";
				}else
				{
					$ls_error = "1";
					$msg .= "Pembatalan klaim untuk kode klaim ".${"kode_klaim".$cebox[$i]}." gagal..."."<br>";
				}		 
		}	
	}
}
//--------------------- end button action ------------------------------------
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
		
<script type="text/javascript">
function batalKlaim() {
	window.open('../ajax/pn5044_popupbatal.php','batal','width=400,height=100,top=100,left=100,scrollbars=yes')
}		
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('A.KPJ'  	=> 'No. Referensi',
										'A.KODE_KLAIM' => 'Kode Klaim' 														 
											);
if (isset($searchtxt) && $searchtxt!="")
{
	$searchtxt					=	 strtoupper($searchtxt);
	if ($pilihsearch		== "")
	{
  	$filtersearch='and (';
  	$arr_key = array_keys($arr_filter);
  	for($i=0, $max_i=ExtendedFunction::count($arr_filter); $i<$max_i; $i++) {
  		$filtersearch		.=	 (($i>0)?'or ':'').$arr_key[$i]." like '".$searchtxt."' ";
  	} unset($arr_key);
  	$filtersearch.=') ';
	}else
	{
  	if(strtoupper(substr($pilihsearch,0,5))=='DATE(')
  	{
    	$arr_dateparam = explode(',',substr($pilihsearch,5,strlen($pilihsearch)-6));
    	$fieldsearch   = " TO_CHAR(".$arr_dateparam[0].", '".$arr_dateparam[1]."') ";
    	unset($arr_dateparam);
    
    	$filtersearch		=	 "and ".$fieldsearch." like '".$searchtxt."' ";
  	}else{
  		$filtersearch		=	 "and ".$pilihsearch." = '".$searchtxt."' ";
  	}
	}			   
}
//filter kantor
if (strlen($gs_kantor_aktif)==3) 
{
 	$filterkantor = "and a.kode_kantor = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and a.kode_kantor in ".
      						"(	select kode_kantor from sijstk.ms_kantor ".
      						"		start with kode_kantor = '$gs_kantor_aktif' ".
      						"		connect by prior kode_kantor = kode_kantor_induk ".
      						"	) ";
}

if ($ls_rg_kategori=="1")
{
	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JHT%' ";
}elseif ($ls_rg_kategori=="2")
{
	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JHM%' ";
}elseif ($ls_rg_kategori=="3")
{
	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JKK%' ";
}elseif ($ls_rg_kategori=="4")
{
	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JKM%' ";
}elseif ($ls_rg_kategori=="5")
{
	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JPN%' ";
}
	
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.kode_klaim '.$o.' ';
}
		
$url = 'pn5044.php';
$rows_per_page = 8; // untuk paging
$sql = 	"select ". 
        "		kode_user, kode_jabatan, kode_klaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, kpj, ". 
        "		nama_pengambil_klaim, ket_tipe_klaim, kode_segmen, kode_kantor, kode_kantor_tk, ". 
        "		status_klaim, kode_tipe_klaim, kode_sebab_klaim, kode_pointer_asal, id_pointer_asal, ".
				"		kode_klaim_induk, to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, ".
				"		to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_trans ".
 				"from sijstk.vw_pn_batal_klaim a ".
        "where kode_user = '$username' ".
				"and a.tgl_klaim between trunc(to_date('$ld_tgl_trans1_display','dd/mm/yyyy'),'dd') and trunc(to_date('$ld_tgl_trans2_display','dd/mm/yyyy'),'dd') ".
        "and a.kode_klaim_induk is null ".
				$ls_filter_tipeklaim.
				$filtersearch.
				"UNION ALL ".
				"select ". 
        "		kode_user, kode_jabatan, kode_klaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, kpj, ". 
        "		nama_pengambil_klaim, ket_tipe_klaim, kode_segmen, kode_kantor, kode_kantor_tk, ". 
        "		status_klaim, kode_tipe_klaim, kode_sebab_klaim, kode_pointer_asal, id_pointer_asal, ".
				"		kode_klaim_induk, to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, ".
				"		to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_trans ".
 				"from sijstk.vw_pn_batal_klaim a ".
        "where kode_user = '$username' ".
				"and a.tgl_penetapan between trunc(to_date('$ld_tgl_trans1_display','dd/mm/yyyy'),'dd') and trunc(to_date('$ld_tgl_trans2_display','dd/mm/yyyy'),'dd') ".
        "and a.kode_klaim_induk is not null ".
				$ls_filter_tipeklaim.
				$filtersearch;
//echo $sql;
$total_rows  = f_count_rows($DB,$sql);
$total_pages = f_total_pages($total_rows, $rows_per_page);
$othervar		= "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&f=".$_GET['f']."&o=".$o;
if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) 
	{
  $_GET['page'] = 1;
} 
	else if ( $_GET['page'] > $total_pages ) 
	{
  $_GET['page'] = $total_pages;
}	
$start_row = f_page_to_row($_GET['page'], $rows_per_page);
$jmlrow		 = $rows_per_page;
?>
<table class="captionform">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
  </style>
  <tr>
		<td id="header-caption2" colspan="3">
			<h3><?=$gs_pagetitle;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?																 
        switch($ls_rg_kategori)
        {
          case '1' : $sel1="checked"; break;
          case '2' : $sel2="checked"; break;
          case '3' : $sel3="checked"; break;
          case '4' : $sel4="checked"; break;
          case '5' : $sel5="checked"; break;
          case '6' : $sel6="checked"; break;
        }
        ?>
        <input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">JHT</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">JHT/JKM</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="rg_kategori" value="3" onclick="this.form.submit()"  <?=$sel3;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">JKK</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="rg_kategori" value="4" onclick="this.form.submit()"  <?=$sel4;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">JKM</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="rg_kategori" value="5" onclick="this.form.submit()"  <?=$sel5;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">JP</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="rg_kategori" value="6" onclick="this.form.submit()"  <?=$sel6;?>>&nbsp;<font color="#ffffff" size="2;" face="Arial,Verdana">SEMUA DATA</font>
      </h3>    
		</td>
  </tr>
	<tr><td colspan="3"></br></br></td></tr>
				
	<tr>
		<td>
			Tgl Klaim/Penetapan Ulang
      <input type="text" id="tgl_trans1_display" name="tgl_trans1_display" value="<?=$ld_tgl_trans1_display;?>" size="12" maxlength="10" tabindex="1" onblur="convert_date(tgl_trans1_display);">
      <input id="btn_tgl_trans1_display" type="image" align="top" onclick="return showCalendar('tgl_trans1_display', 'dd-mm-y');" src="../../images/calendar.gif" />
  		s/d
  		<input type="text" id="tgl_trans2_display" name="tgl_trans2_display" value="<?=$ld_tgl_trans2_display;?>" size="12" maxlength="10" tabindex="5" onblur="convert_date(tgl_trans2_display);">
      <input id="btn_tgl_trans2_display" type="image" align="top" onclick="return showCalendar('tgl_trans2_display', 'dd-mm-y');" src="../../images/calendar.gif" />		
		</td>	
		<td align="right">Search By &nbsp
		<select name="pilihsearch" size="1" style="width:150px">
		<option value="">--ALL--</option>
		<?
		$arr_key = array_keys($arr_filter);
		for($i=0, $max_i=ExtendedFunction::count($arr_filter); $i<$max_i; $i++) {
		echo '<option value="'.$arr_key[$i].'"'.(($pilihsearch==$arr_key[$i])?' selected':'').'>'.$arr_filter[$arr_key[$i]].'</option>';
		} unset($arr_key);
		?>
		</select>
		<input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="30">
		<input type="submit" name="cari2" value="GO">
		</td>
	</tr>
</table>
<?
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&tgl_trans1_display=".$ld_tgl_trans1_display."&tgl_trans2_display=".$ld_tgl_trans2_display;
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata cellspacing=0>";
echo "<tr>";
echo "<th class=awal>&nbsp;<input type=checkbox name=toggle value=\"\" onclick=\"checkAll(".$jmlrow."); \" /></th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.tgl_klaim&o=$o$sortvar\"><b>Tanggal</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>No. Referensi</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>Nama</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>Tgl Kejadian</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.kode_tipe_klaim&o=$o$sortvar\"><b>Tipe</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.kode_segmen&o=$o$sortvar\"><b>Segmen</b></a></th>";		
echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor&o=$o$sortvar\"><b>Ktr</b></a></th>";	
echo "<th><a href=\"$PHP_SELF?f=a.status_klaim&o=$o$sortvar\"><b>Status</b></a></th>";						
echo "</tr>";							 												 					 	  

$sql = f_query_perpage($sql, $start_row, $rows_per_page);
$DB->parse($sql);
$DB->execute();
$i=0;
$n=1;
while ($row = $DB->nextrow())
{	    	
	echo "<tr bgcolor=#".($n%2 ? "f3f3f3" : "ffffff").">"; 
	echo "<td class=awal>&nbsp;";
	echo "<input type=hidden name=cebox[] value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KODE_KLAIM"]." value=".$row["KODE_KLAIM"].">";	
	echo "<input type=checkbox id=cb".$i." name=s".$row["KODE_KLAIM"]." value=".$row["KODE_KLAIM"]." onclick=\"isChecked(this.checked);\"></td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../form/pn5049.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn5044.php','info',1045,600,'yes')\">".$row["KODE_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["TGL_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_PENGAMBIL_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["TGL_KEJADIAN"]."</a></td>";
	echo "<td>&nbsp;".$row["KET_TIPE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_SEGMEN"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_KANTOR"]."</a></td>";
	echo "<td>&nbsp;".$row["STATUS_KLAIM"]."</a></td>";
	echo "</tr>";
	$i++; $n++;
}											
?>
</table>
<table class="paging">
	<tr>
		<td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
		<td height="15" align="right">
		<b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>	
		</td>
	</tr>
</table>			
<div class="clear5"></div>
<div id="buttonbox">				
<input type="hidden" name="trigersubmit" value="0">
<div><input type="button" class="btn green" name="batalklaim" onclick="batalKlaim();" value="           BATAL KLAIM          "></div>
<?
if (isset($msg))		
{
  ?>
  <fieldset>
  <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
  <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
  </fieldset>		
  <?
}
?>
</div>
<div class="clear5"></div>														
<?										
//------------ end data grid -----------------------------------------------
?>
</div>	 				
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
