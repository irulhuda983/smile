<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
$mid = $_REQUEST["mid"];
/*--------------------- Form History -----------------------------------------
File: pn500401.php

Deskripsi:
-----------
File ini dipergunakan untuk pembayaran klaim berkala

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
Hist: - 02/10/2017 : Pembuatan Form (Tim SIJSTK)	
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
//--------------------- start button action ----------------------------------
$ls_rg_kategori	= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
if ($ls_rg_kategori=="")
{
 	 $ls_rg_kategori = "4";
}

$ld_tglawaldisplay	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay	= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];

	
$ls_kode_cara_bayar			= $_POST['kode_cara_bayar'];																																								
$ls_kode_buku						= $_POST['kode_buku'];
$ls_kode_bank						= $_POST['kode_bank'];
$ls_nama_bank						= $_POST['nama_bank'];

//switch form ----------------------------------------------------------------
if ($ls_rg_kategori=="2") //jika yg diklik adalah BERKALA
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500401.php?mid=$mid');";
  echo "</script>";
}else if ($ls_rg_kategori=="3") //jika yg diklik adalah LUMPSUM (SENTRALISASI REKENING)
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500402.php?mid=$mid');";
  echo "</script>";
}	
	
//--------------------- end button action ------------------------------------
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>
	
<script type="text/javascript">
function doBayar() {
	window.open('../ajax/pn500401_popupbayar.php','bayar','width=400,height=100,top=100,left=100,scrollbars=yes')
}			
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
if ($ld_tglawaldisplay=="" && $ld_tglakhirdisplay=="")//tampilkan dari 7 hari sebelumnya
{
  $sql2 = "select to_char(sysdate-5,'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
  $DB->parse($sql2);
  $DB->execute();
  $row = $DB->nextrow();
  $ld_tglawaldisplay  = $row["TGLAWAL"];						
  $ld_tglakhirdisplay = $row["TGLAKHIR"];	
}	
	
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('A.NAMA_PENGAMBIL_KLAIM'  	=> 'Nama TK',
										'A.KPJ'  	=> 'No. Referensi',		
										'A.KODE_TIPE_KLAIM' => 'Tipe Klaim',			
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
  		$filtersearch		=	 "and ".$pilihsearch." like '%".$searchtxt."%' ";
  	}
	}			   
}
//filter kantor
if (strlen($gs_kantor_aktif)==3) 
{
 	$filterkantor = "and nvl(a.kode_kantor_pembayaran,a.kode_kantor) = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and nvl(a.kode_kantor_pembayaran,a.kode_kantor) in ".
      						"(	select kode_kantor from sijstk.ms_kantor ".
      						"		start with kode_kantor = '$gs_kantor_aktif' ".
      						"		connect by prior kode_kantor = kode_kantor_induk ".
      						"	) ";
}

if ($ld_tglawaldisplay!="" && $ld_tglakhirdisplay!="")
{
 	 $filtertgl = " and tgl_klaim between to_date('$ld_tglawaldisplay','dd/mm/yyyy') and to_date('$ld_tglakhirdisplay','dd/mm/yyyy') ";
}
	
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.kode_klaim '.$o.' ';
}
		
$url = 'pn500403.php';
$rows_per_page = 15; // untuk paging
$sql = 	"select ".
        "    kode_klaim, kode_kantor, kode_kantor_pembayaran, kode_user, tglklaim, ". 
        "    to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, kpj, nama_pengambil_klaim, ket_tipe_klaim, kode_segmen, ". 
        "    status_klaim, kode_tipe_klaim, kode_sebab_klaim, kode_pointer_asal, id_pointer_asal, ". 
        "    path_form_pembayaran, nom_netto, nom_sudah_bayar, nom_sisa, ".
				"		 (select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) nama_tipe_klaim ".
        "from sijstk.vw_pn_pembayaran_klaim a ".
        "where a.kode_user = '$username' ".
				$filtertgl.
				$filterkantor.
        $filtersearch;					 					 
				//$ls_order;
//echo $sql;
$total_rows  = f_count_rows($DB,$sql);
$total_pages = f_total_pages($total_rows, $rows_per_page);
$othervar		= "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&f=".$_GET['f']."&o=".$o."&tglawaldisplay=".$ld_tglawaldisplay."&tglakhirdisplay=".$ld_tglakhirdisplay;
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
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
	
	<tr>
		<td>
				<b>JENIS PEMBAYARAN &nbsp;&nbsp;
						<? 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
						case '3' : $sel3="checked"; break;
						case '4' : $sel4="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="4" onclick="this.form.submit()"  <?=$sel4;?>>&nbsp;<font  color="#009999"><b>LUMPSUM</b></font>&nbsp; | &nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#009999"><b>JP BERKALA</b></font> &nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="3" onclick="this.form.submit()"  <?=$sel3;?>>&nbsp;<font  color="#009999"><b>LUMPSUM (SENTRALISASI)</b></font> &nbsp;&nbsp;
					</b>
		</td>	
							
		<td align="right">Search By &nbsp
		<select name="pilihsearch" size="1" style="width:100px">
		<option value="">--ALL--</option>
		<?
		$arr_key = array_keys($arr_filter);
		for($i=0, $max_i=ExtendedFunction::count($arr_filter); $i<$max_i; $i++) {
		echo '<option value="'.$arr_key[$i].'"'.(($pilihsearch==$arr_key[$i])?' selected':'').'>'.$arr_filter[$arr_key[$i]].'</option>';
		} unset($arr_key);
		?>
		</select>
		<input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="20">
		<input type="submit" name="cari2" value="GO">
		</td>
	</tr>
	
	<tr>
    <td colspan="4">
    	 Tgl Klaim
    	 <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="12" onblur="convert_date(tglawaldisplay)" >  
			 <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" style="height:11px;" src="../../images/dynCalendar.gif" />&nbsp; s/d &nbsp;
			 <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="12" onblur="convert_date(tglakhirdisplay)" >
			 <input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" style="height:11px;" src="../../images/dynCalendar.gif" />		 
    	 <input type="submit" id="butdisplay" name="butdisplay" class="btn green" value=" TAMPILKAN DATA ">
		</td>	
	</tr>
</table>
<?
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&tglawaldisplay=".$ld_tglawaldisplay."&tglakhirdisplay=".$ld_tglakhirdisplay;
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata cellspacing=0>";
echo "<tr>";
echo "<th class=awal>&nbsp;Action</th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.blthproses&o=$o$sortvar\"><b>Tgl Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>No. Ref</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.nama_tk&o=$o$sortvar\"><b>Nama</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nama_penerima_berkala&o=$o$sortvar\"><b>Tipe</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_berkala&o=$o$sortvar\"><b>Segmen</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor_pembayaran&o=$o$sortvar\"><b>Ktr</b></a></th>";
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
	echo "<input type=hidden id=kode_berkala".$i." name=kode_berkala".$row["KODE_BERKALA"]." value=".$row["KODE_BERKALA"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KODE_BERKALA"]." value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=no_konfirmasi".$i." name=no_konfirmasi".$row["KODE_BERKALA"]." value=".$row["NO_KONFIRMASI"].">";
	echo "<input type=hidden id=no_proses".$i." name=no_proses".$row["KODE_BERKALA"]." value=".$row["NO_PROSES"].">";
	echo "<input type=hidden id=kd_prg".$i." name=kd_prg".$row["KODE_BERKALA"]." value=".$row["KD_PRG"].">";	
	echo "<input type=hidden id=kode_kantor_pembayaran".$i." name=kode_kantor_pembayaran".$row["KODE_BERKALA"]." value=".$row["KODE_KANTOR_PEMBAYARAN"].">";
	echo "&nbsp;<a href=# onclick=\"window.location.replace('../ajax/pn5004_pembayaran.php?task=View&root_sender=pn500403.php&sender=pn500403.php&activetab=2&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&sender_mid=$ls_mid&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay','info','width=1050,height=690,yes')\"><font  color=#009999><b>Bayar</b></font></a></td>";
	echo "<td>&nbsp;<a href=# onclick=\"window.location.replace('../ajax/pn5004_pembayaran.php?task=View&root_sender=pn500403.php&sender=pn500403.php&activetab=2&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&sender_mid=$ls_mid&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay','info','width=1050,height=690,yes')\">".$row["KODE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["TGL_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_PENGAMBIL_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["NAMA_TIPE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_SEGMEN"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_KANTOR"]."</a></td>";
	echo "</tr>";
	$i++; $n++;
}											
?>
</table>
<table class="paging">
	<tr>
		<td align="right">Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
		<td height="15" align="right">
		<b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>	
		</td>
	</tr>
</table>			

</br>
<!--
<div class="clear5"></div>
<div id="buttonbox">				
	<input type="hidden" name="trigersubmit" value="0">
  <div><input type="button" class="btn green" name="btnbayar" onclick="doBayar()" value="      BAYAR      "></div>
</div>
<div class="clear5"></div>
-->
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
<?										
//------------ end data grid -----------------------------------------------
?>
</div>	 				
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
