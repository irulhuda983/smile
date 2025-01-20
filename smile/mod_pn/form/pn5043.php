<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
$mid = $_REQUEST["mid"];
/*--------------------- Form History -----------------------------------------
File: pn5043.php

Deskripsi:
-----------
File ini dipergunakan untuk pembayaran klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
Hist: - 02/10/2017 : Pembuatan Form (Tim SIJSTK)	
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
//update 28/10/2020, jika kantor sudah sentralisasi rekening maka diarahkan ke pn5063.php
$sql = "select count(*) as v_cnt from sijstk.ms_kantor ".
		 	 "where kode_kantor = '$gs_kantor_aktif' ".
			 "and nvl(status_rekening_sentral,'T')='Y'";
$DB->parse($sql);
$DB->execute();
$data = $DB->nextrow();
$ls_is_sentralrek = $data["V_CNT"];
if ($ls_is_sentralrek=="")
{
 $ls_is_sentralrek = "0";	 
}

if ($ls_is_sentralrek > "0")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn5063.php?mid=$mid');";
  echo "</script>";
}
//end cek sentralisasi ---------------------------------------------------------
//--------------------- start button action ----------------------------------
$ls_rg_kategori	= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
if ($ls_rg_kategori=="")
{
 	 $ls_rg_kategori = "4";
}

$ld_tglawaldisplay	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay	= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];

$ls_rg_kategori	= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
if ($ls_rg_kategori=="")
{
 	 $ls_rg_kategori = "1";
}

$ls_rg_jenisrekening	= !isset($_GET['rg_jenisrekening']) ? $_POST['rg_jenisrekening'] : $_GET['rg_jenisrekening'];
if ($ls_rg_jenisrekening=="")
{
 	 $ls_rg_jenisrekening = "r1";
}

$ld_tglawaldisplay	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay	= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];

//switch form ------------------------------------------------------------------
if ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r1")  											//lumpsum rekening kantor cabang
{
	//echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  //echo "window.location.replace('../form/pn5043.php?mid=$mid');";
  //echo "</script>";				 	 
}elseif ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r2")	 	 							//lumpsum rekening spo kantor cabang
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn504301.php?mid=$mid');";
  echo "</script>";				 	 
}elseif ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r3")	 	 							//lumpsum rekening spo kantor pusat
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn504302.php?mid=$mid');";
  echo "</script>";				 	 
}elseif ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r4")	 	 							//lumpsum rekening sentralisasi kantor pusat
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn504303.php?mid=$mid');";
  echo "</script>";				 	 
}elseif ($ls_rg_kategori=="2" && $ls_rg_jenisrekening == "r1")	 	 							//berkala rekening kantor cabang
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn504304.php?mid=$mid');";
  echo "</script>";				 
}elseif ($ls_rg_kategori=="2" && $ls_rg_jenisrekening == "r4")	 	 							//berkala rekening sentralisasi kantor pusat
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn504305.php?mid=$mid');";
  echo "</script>";				 	 
}										
//end switch form --------------------------------------------------------------	
	
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
  $sql2 = "select to_char(trunc(sysdate,'mm'),'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
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
 	 //$filtertgl = " and tgl_klaim between to_date('$ld_tglawaldisplay','dd/mm/yyyy') and to_date('$ld_tglakhirdisplay','dd/mm/yyyy') "; //update 24/08/2018, ubah menggunakan tgl_penetapan
	 
	 $filtertgl = " and tgl_penetapan between to_date('$ld_tglawaldisplay','dd/mm/yyyy') and to_date('$ld_tglakhirdisplay','dd/mm/yyyy') ";
}
	
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.kode_klaim '.$o.' ';
}
		
$url = 'pn5043.php';
$rows_per_page = 15; // untuk paging
$sql = 	"select ".
        "    kode_klaim, kode_kantor, kode_kantor_pembayaran, kode_user, tglklaim, ". 
        "    to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, ".
				"		 kpj, nama_pengambil_klaim, ket_tipe_klaim, kode_segmen, ". 
        "    status_klaim, kode_tipe_klaim, kode_sebab_klaim, kode_pointer_asal, id_pointer_asal, ". 
        "    path_form_pembayaran, nom_netto, nom_sudah_bayar, nom_sisa, ".
				"		 (select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) nama_tipe_klaim ".
        "from sijstk.vw_pn_pembayaran_klaim a ".
        "where a.kode_user = '$username' 
			and not exists
			(
				select null from pn.pn_klaim b
				where b.kode_klaim = a.kode_klaim
				and nvl(b.kanal_pelayanan,'0') = '40'   
			) 
		".
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
  <tr>
		<td id="header-caption2" colspan="3">
  		<h3><?=$gs_pagetitle;?> &nbsp;&nbsp;&nbsp;
					<? 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">LUMPSUM</font>&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">JP BERKALA</font>
  		
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<font  color="#FF0" size="2;" face="Arial,Verdana">MELALUI REKENING:</font>
						<?
						switch($ls_rg_jenisrekening)
            {
            case 'r1' : $selr1="checked"; break;
            case 'r2' : $selr2="checked"; break;
						case 'r3' : $selr3="checked"; break;
						case 'r4' : $selr4="checked"; break;
            }
            ?>
						<input type="radio" name="rg_jenisrekening" value="r1" onclick="this.form.submit()"  <?=$selr1;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">KANTOR CABANG</font>
						<input type="radio" name="rg_jenisrekening" value="r2" onclick="this.form.submit()"  <?=$selr2;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">SPO KACAB</font>
  					<input type="radio" name="rg_jenisrekening" value="r3" onclick="this.form.submit()"  <?=$selr3;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">SPO KAPU</font>
						<!--<input type="radio" name="rg_jenisrekening" value="r4" onclick="this.form.submit()"  <?=$selr4;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">SENTRALISASI KAPU</font>-->
			</h3>
		</td>
	</tr>	
  <tr><td colspan="3"></br></br></td></tr>	
	
	<tr>
    <td align="left">
    	 Tanggal &nbsp;
    	 <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="12" onblur="convert_date(tglawaldisplay)" >  
			 <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" style="height:11px;" src="../../images/dynCalendar.gif" />&nbsp; s/d &nbsp;
			 <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="12" onblur="convert_date(tglakhirdisplay)" >
			 <input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" style="height:11px;" src="../../images/dynCalendar.gif" />		 
    	 <input type="submit" id="butdisplay" name="butdisplay" class="btn green" value=" TAMPILKAN DATA ">
		</td>	
					
		<td colspan="2" align="right">Search By &nbsp
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
</table>
<?
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&tglawaldisplay=".$ld_tglawaldisplay."&tglakhirdisplay=".$ld_tglakhirdisplay;
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata cellspacing=0>";
echo "<tr>";
echo "<th class=awal>&nbsp;Action</th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.blthproses&o=$o$sortvar\"><b>Tgl Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.blthproses&o=$o$sortvar\"><b>Tgl Penetapan</b></a></th>";
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
	echo "&nbsp;<a href=# onclick=\"window.location.replace('../ajax/pn5043_pembayaran.php?task=View&root_sender=pn5043.php&sender=pn5043.php&activetab=2&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&sender_mid=$ls_mid&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay','info','width=1050,height=690,yes')\"><font  color=#009999><b>Bayar</b></font></a></td>";
	echo "<td>&nbsp;<a href=# onclick=\"window.location.replace('../ajax/pn5043_pembayaran.php?task=View&root_sender=pn5043.php&sender=pn5043.php&activetab=2&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&sender_mid=$ls_mid&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay','info','width=1050,height=690,yes')\">".$row["KODE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["TGL_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["TGL_PENETAPAN"]."</a></td>";
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
		<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">		
		</td>
	</tr>
</table>			

</br>

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

<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
	<li style="margin-left:15px;">Tanggal untuk menampilkan data merujuk pada tgl klaim untuk klaim yg bukan penetapan ulang. Untuk data penetapan ulang maka akan menggunakan tgl penetapan sebagai pencarian data.</li>					
	<li style="margin-left:15px;">Data klaim yang ditampilkan dikelompokkan sesuai dengan mekanisme pembayaran yang sudah ditetapkan pada saat penetapan klaim.</li>				
  <li style="margin-left:15px;">Pembayaran melalui <font color="#ff0000"> REKENING KANTOR CABANG</font> adalah semua klaim diluar SPO yang pembayaran menggunakan rekening <font color="#ff0000">KANTOR CABANG</font> baik melalui transfer ataupun SPB.</li>
  <li style="margin-left:15px;">Pembayaran melalui <font color="#ff0000"> SPO KACAB</font> adalah semua klaim melalui SPO dari bank yang saat ini masih <font color="#ff0000">belum tersentralisasi</font> seperti SPO Mandiri, BNI, dll (Diluar BTN dan BJB)</li>
	<li style="margin-left:15px;">Pembayaran melalui <font color="#ff0000"> SPO KAPU</font> adalah semua klaim melalui SPO dari bank yang saat ini <font color="#ff0000">sudah tersentralisasi</font> seperti BTN dan BJB</li>
	<li style="margin-left:15px;">Pembayaran melalui <font color="#ff0000"> SENTRALISASI KAPU</font> adalah semua klaim diluar SPO yang pembayarannya tersentralisasi menggunakan rekening <font color="#ff0000">KANTOR PUSAT</font>. </br>Manfaat <font color="#ff0000">otomatis ditransfer</font> ke rekening penerima pada saat klik Transfer</li>
</fieldset>

</div>	 	
<!--			
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
-->
