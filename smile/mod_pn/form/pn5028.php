<?
$pagetype="report";
$gs_pagetitle = "PN5028 - PEMBATALAN PEMBAYARAN KLAIM";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
include "../../style/gridstyle.css";
$mid = $_REQUEST["mid"];
//$gs_kode_segmen = "PU";
/*--------------------- Form History -----------------------------------------
File: pn5005.php

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
  $sql = "select to_char(trunc(sysdate,'mm'),'dd/mm/yyyy') tgl1,to_char(sysdate,'dd/mm/yyyy') tgl2 from dual";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ld_tgl_trans1_display = $data["TGL1"];	
  $ld_tgl_trans2_display = $data["TGL2"];	 	 
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
    		$qry = "BEGIN SIJSTK.P_PN_PN5005.X_BATAL_PEMBAYARAN('".${"kode_pembayaran".$cebox[$i]}."','$username',:p_sukses,:p_mess);END;";											 	
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        $DB->execute();				
        $ls_sukses = $p_sukses;	
    		$ls_mess = $p_mess;	
				
				if ($ls_sukses=="1")
				{		
					$msg .= "Pembatalan Pembayaran klaim untuk kode pembayaran ".${"kode_pembayaran".$cebox[$i]}." berhasil..."."<br>";
				}else
				{
					$ls_error = "1";
					$msg .= "Pembatalan Pembayaran klaim untuk kode pembayaran ".${"kode_pembayaran".$cebox[$i]}." gagal..."."<br>";
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
	window.open('../ajax/pn5005_popupbatal.php','batal','width=400,height=100,top=100,left=100,scrollbars=yes')
}		
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('B.KPJ'  	=> 'No. Referensi',
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
  		$filtersearch		=	 "and ".$pilihsearch." like '".$searchtxt."' ";
  	}
	}			   
}
//filter kantor
if (strlen($gs_kantor_aktif)==3) 
{
 	$filterkantor = "and a.kode_kantor_pembayar = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and a.kode_kantor_pembayar in ".
      						"(	select kode_kantor from sijstk.ms_kantor ".
      						"		start with kode_kantor = '$gs_kantor_aktif' ".
      						"		connect by prior kode_kantor = kode_kantor_induk ".
      						"	) ";
}
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.kode_klaim '.$o.' ';
}
		
$url = 'pn5028.php';
$rows_per_page = 8; // untuk paging
$sql = 	"select ". 
        "    a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, a.kode_buku, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg=a.kd_prg) nm_prg, a.kode_kantor_pembayar, a.nom_pembayaran, nvl(a.flag_spo,'T') flag_spo, ".
        "    (select nama_penerima from pn.pn_klaim_penerima_manfaat where kode_klaim = a.kode_klaim and kode_tipe_penerima = a.kode_tipe_penerima) nama_penerima, ".
        "    a.kode_kantor,b.kpj, to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, ".
        "    decode (nvl(b.kode_pointer_asal, 'x'), 'PROMOTIF', b.nama_pelaksana_kegiatan,(decode(b.kode_segmen,'JAKON', (select nama_proyek from jn.jn_proyek where kode_proyek = b.kode_proyek),b.nama_tk))) nama_pengambil_klaim, ".
        "    (select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = b.kode_tipe_klaim)||' '|| b.kode_pointer_asal ket_tipe_klaim, ".
        "    b.kode_segmen, b.kode_kantor_tk, b.status_klaim, b.kode_tipe_klaim, b.kode_sebab_klaim, b.kode_pointer_asal, b.id_pointer_asal  ".
        "from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim b ".
        "where a.kode_klaim = b.kode_klaim ".
        "and nvl(a.status_batal,'T')='T' ".
        "and a.tgl_pembayaran between trunc(to_date('01/01/2018','dd/mm/yyyy'),'dd') and trunc(to_date('23/07/2018','dd/mm/yyyy'),'dd') ".
				$filterkantor.
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
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
				
	<tr>
		<td>
			Tgl Pembayaran
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
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_pembayaran&o=$o$sortvar\"><b>Kode Pembayaran</b></a></th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.tgl_pembayaran&o=$o$sortvar\"><b>Tgl Pembayaran</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kode_buku&o=$o$sortvar\"><b>Rek</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kd_prg&o=$o$sortvar\"><b>Prg</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=b.kpj&o=$o$sortvar\"><b>No. Referensi</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=b.kpj&o=$o$sortvar\"><b>Nama</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=b.kode_tipe_klaim&o=$o$sortvar\"><b>Tipe</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=b.kode_segmen&o=$o$sortvar\"><b>Segmen</b></a></th>";		
echo "<th><a href=\"$PHP_SELF?f=b.kode_kantor&o=$o$sortvar\"><b>Ktr Klaim</b></a></th>";	
echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor_pembayar&o=$o$sortvar\"><b>Ktr Byr</b></a></th>";		
echo "<th><a href=\"$PHP_SELF?f=a.flag_spo&o=$o$sortvar\"><b>SPO</b></a></th>";				
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
	echo "<input type=hidden name=cebox[] value=".$row["KODE_PEMBAYARAN"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KODE_KLAIM"]." value=".$row["KODE_KLAIM"].">";	
	echo "<input type=hidden id=kode_pembayaran".$i." name=kode_pembayaran".$row["KODE_PEMBAYARAN"]." value=".$row["KODE_PEMBAYARAN"].">";
	echo "<input type=checkbox id=cb".$i." name=s".$row["KODE_PEMBAYARAN"]." value=".$row["KODE_PEMBAYARAN"]." onclick=\"isChecked(this.checked);\"></td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../ajax/pn5004_pembayaran_entry.php?task=view&kode_pembayaran=".$row["KODE_PEMBAYARAN"]."&mid=$ls_mid&sender=pn5005.php','info','width=700,height=550,resizable=no,scrollbars=no')\">".$row["KODE_PEMBAYARAN"]."</a></td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../form/pn5001.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn5005.php','info','width=800,height=550,resizable=no,scrollbars=no')\">".$row["KODE_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["TGL_PEMBAYARAN"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_BUKU"]."</a></td>";
	echo "<td>&nbsp;".$row["NM_PRG"]."</a></td>";
  echo "<td>&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_PENGAMBIL_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["KET_TIPE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_SEGMEN"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_KANTOR"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_KANTOR_PEMBAYAR"]."</a></td>";
	echo "<td>&nbsp;".($row["FLAG_SPO"]=="Y" ? "<img src=http://$HTTP_HOST/images/file_apply.gif>" : "")."</td>";
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
<?//otorisasi pembatalan pembayaran klaim --------------------------------------
$sql = "select count(*) as v_jml from sijstk.ms_pejabat_kantor ". 
       "where kode_kantor = decode('$gs_kantor_aktif','ATP','P','0','P','$gs_kantor_aktif') ". 
       "and kode_jabatan in ('46','704','204') ". 
       "and kode_user = '$username' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();		
$ln_cnt = $row['V_JML'];

if ($ln_cnt >= "1")
{
  ?>
	<div><input type="button" class="btn green" name="batalklaim" onclick="batalKlaim();" value="      BATAL PEMBAYARAN KLAIM      "></div>
  
  <?
}else
{
  ?>
  <div><input type="button" class="btn green" name="batalklaim" onclick="alert('Anda tidak memiliki wewenang untuk melakukan Pembatalan Pembayaran Klaim...!!!');" value="      BATAL PEMBAYARAN KLAIM      "></div>
  <?
}
?>

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
