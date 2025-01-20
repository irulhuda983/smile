<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
$mid = $_REQUEST["mid"];
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$DB3 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn500401.php

Deskripsi:
-----------
File ini dipergunakan untuk pembayaran klaim lumpsum menggunakan rekening kantor cabang

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
 	 $ls_rg_kategori = "1";
}

$ls_rg_jenisrekening	= !isset($_GET['rg_jenisrekening']) ? $_POST['rg_jenisrekening'] : $_GET['rg_jenisrekening'];
if ($ls_rg_jenisrekening=="")
{
 	 $ls_rg_jenisrekening = "r2";
}

$ld_tglawaldisplay	= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay	= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];

//switch form ------------------------------------------------------------------
if ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r1")  											//lumpsum rekening kantor cabang
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn5043.php?mid=$mid');";
  echo "</script>";				 	 
}elseif ($ls_rg_kategori=="1" && $ls_rg_jenisrekening == "r2")	 	 							//lumpsum rekening spo kantor cabang
{
	//echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  //echo "window.location.replace('../form/pn504301.php?mid=$mid');";
  //echo "</script>";				 	 
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
				
if($trigersubmit=='1')
{
	$msg = '';
	// Proses akan dimulai dari data yang pertama sampai data terakhir
	for($i=0, $max_i=ExtendedFunction::count($cebox); $i<$max_i; $i++) 
	{
		if (${"s".$cebox[$i]} !="")
    {
				$ls_kode_klaim  			 = ${"kode_klaim".$cebox[$i]};
				$ls_kode_tipe_penerima = ${"kode_tipe_penerima".$cebox[$i]};
				$ls_kd_prg  					 = ${"kd_prg".$cebox[$i]};
				$ls_kode_bank_pembayar = ${"kode_bank_pembayar".$cebox[$i]};
				$ls_kode_buku  				 = ${"kode_buku".$i};
				$ls_kode_cara_bayar  	 = ${"kode_cara_bayar".$i};
				$ls_kode_kantor_pembayaran = ${"kode_kantor_pembayaran".$cebox[$i]};
				
				if ($ls_kode_klaim!="" && $ls_kode_tipe_penerima!="" && $ls_kd_prg!="" && $ls_kode_bank_pembayar!="" && $ls_kode_buku!="" && $ls_kode_cara_bayar!="" && $ls_kode_kantor_pembayaran!="")
				{
  				//pembayaran klaim ---------------------------------------------------
      		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_BAYAR( ".
  						 	 "			'$ls_kode_klaim', ".
  							 "			'$ls_kode_tipe_penerima', ".
  							 "			'$ls_kd_prg', ".
  							 "			'$ls_kode_bank_pembayar', ".
  							 "			'$ls_kode_buku', ".
  							 "			'$ls_kode_cara_bayar', ".
  							 "			'$ls_kode_kantor_pembayaran', ".
  							 "			nvl('$username',user), ".
  							 "			:p_sukses,:p_mess);END;";											 	
          $proc = $DB->parse($qry);				
          oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ls_sukses = $p_sukses;	
      		$ls_mess = $p_mess;	
  				
  				if ($ls_sukses=="1")
  				{		
  					$msg .= "Pembayaran Lumpsum untuk kode klaim ".${"s".$cebox[$i]}." berhasil..."."<br>";
  				}else
  				{
  					$ls_error = "1";
  					$msg .= "Pembayaran Lumpsum untuk kode klaim ".${"s".$cebox[$i]}." gagal..."."<br>";
  				}	
				}
				else
				{
				 	$ls_error = "1";	 
				 	$msg .= "Pembayaran Lumpsum untuk kode klaim ".${"s".$cebox[$i]}." gagal, informasi tidak lengkap..."."<br>";	 
				}
					 
		}	
	}
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.location.replace('?rg_kategori=$ls_rg_kategori&rg_jenisrekening=$ls_rg_jenisrekening&mid=$mid&msg=$msg&ls_error=$ls_error');";
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
	window.open('../ajax/pn5043_popupbayar.php','bayar','width=400,height=100,top=100,left=100,scrollbars=yes')
}			
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
if ($ld_tglawaldisplay=="" && $ld_tglakhirdisplay=="")//tampilkan dari 7 hari sebelumnya
{
  $sql2 = "select to_char(sysdate-1,'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
  $DB->parse($sql2);
  $DB->execute();
  $row = $DB->nextrow();
  $ld_tglawaldisplay  = $row["TGLAWAL"];						
  $ld_tglakhirdisplay = $row["TGLAKHIR"];	
}	
	
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('TT.NAMA_PENGAMBIL_KLAIM'  	=> 'Nama TK',
										'TT.KPJ'  	=> 'No. Referensi',		
										'TT.KODE_TIPE_KLAIM' => 'Tipe Klaim',			
										'TT.KODE_KLAIM' => 'Kode Klaim' 													 
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
	
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY tt.kode_klaim,tt.kode_tipe_penerima '.$o.' ';
}
		
$url = 'pn504301.php';
$rows_per_page = 8; // untuk paging
$sql = "select ".
       "     kode_klaim||kode_tipe_klaim||kd_prg siap_bayar_id, kode_klaim, kode_tipe_klaim, nama_tipe_klaim, kode_sebab_klaim, to_char(tt.tgl_rekam,'dd/mm/yyyy') tgl_rekam, kode_klaim_induk, kode_kantor, kode_segmen, ".
       "     kpj, nama_pengambil_klaim, nomor_identitas, kode_perusahaan, kode_divisi, kode_kantor_pembayaran, kode_tipe_penerima, nama_penerima, ". 
       "     kd_prg, nm_prg, nom_siap_bayar, kode_bank_pembayar, nama_bank_pembayar, default_cara_bayar, ".
       "     ( ".
       "           select a.kode_buku from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
       "           where a.kode_kantor = b.kode_kantor(+) ".  
       "           and a.kode_bank = b.kode_bank(+) ".  
       "           and a.kode_rekening = b.kode_rekening(+) ".  
       "           and a.kode_buku = b.kode_buku(+) ".  
       "           and a.kode_bank = c.kode_bank ".  
       "           and a.kode_kantor = tt.kode_kantor_pembayaran ". 
       "           and a.kode_bank = tt.kode_bank_pembayar ".
       "           and a.kd_prg = tt.kd_prg ". 
       "           and b.tipe_rekening = '39' ".  //spo kacab
       "           and nvl(a.aktif,'T')='Y' ". 
       "           and rownum = 1 ".
       "     ) default_kode_buku ". 			  
       "from ". 
       "( ".
       "     select ".
       "         c.kode_klaim, c.kode_tipe_klaim, c.kode_sebab_klaim, c.tgl_klaim, c.tgl_penetapan, c.kode_klaim_induk, c.kode_kantor,c.kode_segmen, ".
       "         (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = c.kode_tipe_klaim) nama_tipe_klaim, ".
       "         c.kpj, decode (nvl (c.kode_pointer_asal, 'x'),'PROMOTIF', c.nama_pelaksana_kegiatan,(decode(c.kode_segmen,'JAKON', c.nama_tk,c.nama_tk))) nama_pengambil_klaim, ".
			 "				 c.nomor_identitas, c.kode_perusahaan, c.kode_divisi, ".
       "         decode(nvl(c.kode_klaim_induk,'XXX'),'XXX', c.tgl_klaim, c.tgl_penetapan) tgl_rekam, ".
       "         case when (select kode_tipe from sijstk.ms_kantor where kode_kantor = c.kode_kantor) = '5' then ".
       "             (select kode_kantor_induk from sijstk.ms_kantor where kode_kantor = c.kode_kantor) ".
       "         when (select kode_tipe from sijstk.ms_kantor where kode_kantor = c.kode_kantor) = '4' then ".
       "             case when nvl(( select count(*) from sijstk.pn_klaim_approval where kode_klaim = c.kode_klaim and nvl(status_approval,'T')='Y' and kode_kantor in (select kode_kantor from sijstk.ms_kantor where kode_tipe='3')),0)>0 then ".
       "                 (select kode_kantor_induk from sijstk.ms_kantor where kode_kantor = c.kode_kantor) ".
       "             else ".
       "                 c.kode_kantor ".
       "             end ".
       "         else ".
       "             c.kode_kantor ".
       "         end kode_kantor_pembayaran, ".
       "         a.kode_tipe_penerima, b.nama_penerima, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg, ".
       "         nvl(a.nom_manfaat_netto,0)- nvl( ".
       "             (select sum(nvl(nom_pembayaran,0)) from sijstk.pn_klaim_pembayaran where kode_klaim = a.kode_klaim and kode_tipe_penerima = a.kode_tipe_penerima ". 
       "              and kd_prg = a.kd_prg and nvl(status_batal,'T')='T' ".
       "              ) ". 
       "         ,0) nom_siap_bayar, ".
       "         b.kode_bank_pembayar, (select nama_bank from sijstk.ms_bank where kode_bank = b.kode_bank_pembayar) nama_bank_pembayar, ".
       "         ( ".
       "             select kode from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' and nvl(kategori,'XXX') = 'DEFAULT' ".    
       "         ) default_cara_bayar ".       
       "     from sijstk.pn_klaim_penerima_manfaat_prg a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c ".
       "     where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima ".
       "     and a.kode_klaim = c.kode_klaim ".
       "     and nvl(a.status_lunas,'T')='T' ".
       "     and nvl(c.status_lunas,'T')='T' ".
       "     and nvl(c.status_batal,'T')='T' ".
       "     and nvl(c.kode_pointer_asal,'XXX') = 'SPO' ".
			 "		 and b.kode_bank_pembayar not in (select kode from sijstk.ms_lookup where tipe='SPOKAPU' and nvl(aktif,'T')='Y') ".
       "     and c.status_klaim in ('DISETUJUI', 'TUNDABAYAR', 'SIAP_BAYAR') ".
       "     and decode(nvl(c.kode_klaim_induk,'XXX'),'XXX',c.tgl_klaim, c.tgl_penetapan) between to_date('$ld_tglawaldisplay','dd/mm/yyyy') and to_date('$ld_tglakhirdisplay','dd/mm/yyyy') ".
       ") tt ".
       "where kode_kantor_pembayaran = '$gs_kantor_aktif' ".
       "and nvl(nom_siap_bayar,0) > 0 ".
			 $filtersearch.		 					 
			 $ls_order;
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
echo "<th style=width:280px;text-align:center;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Pembayaran Melalui</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.kode_klaim&o=$o$sortvar\"><b>Tanggal</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=tt.kode_segmen&o=$o$sortvar\"><b>Seg</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=tt.kode_tipe_klaim&o=$o$sortvar\"><b>Tipe</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.kpj&o=$o$sortvar\"><b>No. Ref</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.nama_pengambil_klaim&o=$o$sortvar\"><b>Nama</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.nama_tipe_penerima&o=$o$sortvar\"><b>Nama Penerima</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.kode_tipe_penerima&o=$o$sortvar\"><b>TP</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.nom_siap_bayar&o=$o$sortvar\"><b>Jml Bayar</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=tt.kd_prg&o=$o$sortvar\"><b>Prg</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=tt.kode_kantor_pembayaran&o=$o$sortvar\"><b>Ktr</b></a></th>";
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
	echo "<input type=hidden name=cebox[] value=".$row["SIAP_BAYAR_ID"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["SIAP_BAYAR_ID"]." value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=kode_tipe_penerima".$i." name=kode_tipe_penerima".$row["SIAP_BAYAR_ID"]." value=".$row["KODE_TIPE_PENERIMA"].">";
	echo "<input type=hidden id=kd_prg".$i." name=kd_prg".$row["SIAP_BAYAR_ID"]." value=".$row["KD_PRG"].">";	
	echo "<input type=hidden id=kode_bank_pembayar".$i." name=kode_bank_pembayar".$row["SIAP_BAYAR_ID"]." value=".$row["KODE_BANK_PEMBAYAR"].">";	
	echo "<input type=hidden id=kode_kantor_pembayaran".$i." name=kode_kantor_pembayaran".$row["SIAP_BAYAR_ID"]." value=".$row["KODE_KANTOR_PEMBAYARAN"].">";	
	echo "<input type=checkbox id=cb".$i." name=s".$row["SIAP_BAYAR_ID"]." value=".$row["SIAP_BAYAR_ID"]." onclick=\"isChecked(this.checked);\">";
	echo "</td>";
	echo "<td>";
	?>
  <select size="1" id="kode_cara_bayar<?=$i;?>" name="kode_cara_bayar<?=$i;?>" value="<?=$row["DEFAULT_CARA_BAYAR"];?>" class="select_format" style="background-color:#ffff99;width:90px;">
  <option value="">- cara byr --</option>
  <? 
  $sql2 = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' order by seq";
  $DB2->parse($sql2);
  $DB2->execute();
  while($row2 = $DB2->nextrow())
  {
    echo "<option ";
    if (($row2["KODE"]==$row["DEFAULT_CARA_BAYAR"] && strlen($row2["KODE"])==strlen($row["DEFAULT_CARA_BAYAR"]))) { echo " selected"; }
    echo " value=\"".$row2["KODE"]."\">".$row2["KETERANGAN"]."</option>";
  }
  ?>
  </select>	
	<?
	$ls_kode_kantor_pembayaran = "";
	$ls_kode_bank_pembayar = "";
	$ls_kd_prg = "";
	
	$ls_kode_kantor_pembayaran = $row["KODE_KANTOR_PEMBAYARAN"];
	$ls_kode_bank_pembayar = $row["KODE_BANK_PEMBAYAR"];
	$ls_kd_prg = $row["KD_PRG"];
		
	?>
  <select size="1" id="kode_buku<?=$i;?>" name="kode_buku<?=$i;?>" value="<?=$row["DEFAULT_KODE_BUKU"];?>" class="select_format" style="background-color:#ffff99;width:170px;">
  <option value="">- rekening --</option>
  <?	
  $sql3 = "select a.kode_buku, a.kode_buku||' - '||c.nama_bank||' (SPO)' nm_rek from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
          "where a.kode_kantor = b.kode_kantor(+) ".  
          "and a.kode_bank = b.kode_bank(+) ".  
          "and a.kode_rekening = b.kode_rekening(+) ".  
          "and a.kode_buku = b.kode_buku(+) ".  
          "and a.kode_bank = c.kode_bank ".  
          "and a.kode_kantor = '$ls_kode_kantor_pembayaran' ". 
          "and a.kode_bank = '$ls_kode_bank_pembayar' ".
          "and a.kd_prg = '$ls_kd_prg' ". 
          "and b.tipe_rekening = '39' ".  //spo kacab
          "and nvl(a.aktif,'T')='Y' ";
  $DB3->parse($sql3);
  $DB3->execute();
  while($row3 = $DB3->nextrow())
  {
    echo "<option ";
    if (($row3["KODE_BUKU"]==$row["DEFAULT_KODE_BUKU"] && strlen($row3["KODE_BUKU"])==strlen($row["DEFAULT_KODE_BUKU"]))) { echo " selected"; }
    echo " value=\"".$row3["KODE_BUKU"]."\">".$row3["NM_REK"]."</option>";
  }
  ?>
  </select>		
	<?
	echo "</td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../ajax/pn5048_view_pembayaran.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn5047.php&activetab=1','info',1045,600,'yes')\">".$row["KODE_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["TGL_REKAM"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_SEGMEN"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_TIPE_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_PENGAMBIL_KLAIM"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_PENERIMA"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_TIPE_PENERIMA"]."</a></td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_SIAP_BAYAR"],2,".",",") ."</td>";	
  echo "<td>&nbsp;".$row["NM_PRG"]."</a></td>";
	echo "<td>&nbsp;".$row["KODE_KANTOR"]."</a></td>";
	echo "</tr>";
	$i++; $n++;
}											
?>
</table>
<table class="paging">
	<tr>
		<td align="left">Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
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
         "where kode_kantor = '$gs_kantor_aktif' ". 
         "and kode_jabatan in ('46','701','709') ". //701 (PENATA MADYA PEMASARAN KEUANGAN DAN TI), 709 (PENATA MADYA KEUANGAN KANTOR CABANG),  46 (KABID KEUANGAN DAN TI)
         "and kode_user = '$username' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ln_cnt = $row['V_JML'];
  
  if ($ln_cnt >= "1")
  {
    ?>
  	<div>
  			 <input type="button" class="btn green" name="btnbayar" onclick="doBayar()" value="           BAYAR           ">
  	</div>
    <?
  }else
  {
    ?>
    <div>
  			 <input type="button" class="btn green" name="btnbayar" onclick="alert('Anda tidak memiliki wewenang untuk melakukan Pembayaran Klaim...!!!');" value="           BAYAR           ">
  	</div>
    <?
  }
  ?>
</div>
<div class="clear5"></div>

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
  <li style="margin-left:15px;">Pembayaran melalui <font color="#ff0000"> SPO KACAB</font> adalah semua klaim melalui SPO dari bank yang saat ini masih <font color="#ff0000">belum tersentralisasi</font> seperti SPO Mandiri, BNI, dll (Diluar BTN dan BJB). </br>Jika <font color="#ff0000">REKENING SPO KACAB TIDAK TAMPIL </font>pada pilihan "Pembayaran Melalui" maka hubungi Administrator untuk melakukan <font color="#ff0000">SETUP REKENING DENGAN KODE TIPE REKENING 39 PADA MENU SETUP BANK</font></li>
</fieldset>
				
</div>	 				
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
