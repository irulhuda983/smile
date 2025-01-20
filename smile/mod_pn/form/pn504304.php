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
 	 $ls_rg_kategori = "2";
}

$ls_rg_jenisrekening	= !isset($_GET['rg_jenisrekening']) ? $_POST['rg_jenisrekening'] : $_GET['rg_jenisrekening'];
if ($ls_rg_jenisrekening=="")
{
 	 $ls_rg_jenisrekening = "r1";
}

$ls_rg_blth	= !isset($_GET['rg_blth']) ? $_POST['rg_blth'] : $_GET['rg_blth'];
if ($ls_rg_blth=="")
{
 	 $ls_rg_blth = "B1";
}

$ls_kode_cara_bayar			= $_POST['kode_cara_bayar'];																																								
$ls_kode_buku						= $_POST['kode_buku'];
$ls_kode_bank						= $_POST['kode_bank'];
$ls_nama_bank						= $_POST['nama_bank'];

//switch form ----------------------------------------------------------------
if ($ls_rg_kategori=="1") //jika yg diklik adalah LUMPSUM
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn5043.php?mid=$mid');";
  echo "</script>";
}	
	
if($trigersubmit=='1')
{
	$msg = '';
	if ($ls_kode_cara_bayar=="" || $ls_kode_buku=="")
	{ 
		$ls_error = "1";
		$msg .= "pembayaran berkala gagal, cara bayar atau rekening pembayaran kosong, lengkapi data input...!!!"."<br>";
	}else
	{
  	// Proses akan dimulai dari data yang pertama sampai data terakhir
		for($i=0, $max_i=ExtendedFunction::count($cebox); $i<$max_i; $i++) 
		{
			if (${"s".$cebox[$i]} !="")
			{
				$ls_kode_klaim  	= ${"kode_klaim".$cebox[$i]};
				$ln_no_konfirmasi = ${"no_konfirmasi".$cebox[$i]};
				$ln_no_proses  		= ${"no_proses".$cebox[$i]};
				$ls_kd_prg  			= ${"kd_prg".$cebox[$i]};
				$ls_kode_kantor_pembayaran  	= ${"kode_kantor_pembayaran".$cebox[$i]};
						
				// pembayaran klaim berkala -------------------------------------------
				$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_BAYAR_BERKALA ( ".
								"			'$ls_kode_klaim','$ln_no_konfirmasi','$ln_no_proses','$ls_kd_prg', ".
								"			'$ls_kode_kantor_pembayaran','$ls_kode_cara_bayar', '$ls_kode_buku', '$ls_kode_bank', 'T', ".
								"			'$username',:p_sukses,:p_mess);END;";											 	
				$proc = $DB->parse($qry);				
				oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
				oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
				$DB->execute();				
				$ls_sukses = $p_sukses;	
				$ls_mess = $p_mess;	
				
	 						// -----------------------------start update pending matters 09032022------------------------
				if($ls_sukses == '1'){
					if($ln_no_konfirmasi < 1){
						$sql = "select kode_pembayaran from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = '$ls_kode_klaim' and no_proses = '$ln_no_proses' and kode_kantor_pembayar = '$ls_kode_kantor_pembayaran' and kd_prg = '$ls_kd_prg'  ";
						$DB->parse($sql);
						$DB->execute();
						$row = $DB->nextrow();
						$ls_kode_pembayaran			= $row["KODE_PEMBAYARAN"];

						include "../ajax/pn504304_arsip_digital.php";
					}	
				}	
	 						// -----------------------------start update pending matters 09032022------------------------

							
				$msg .= "pembayaran berkala untuk kode berkala ".${"kode_berkala".$cebox[$i]}." berhasil..."."<br>";	 

			}	
		}
	}
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?rg_kategori=$ls_rg_kategori&rg_jenisrekening=$ls_rg_jenisrekening&rg_blth=$ls_rg_blth&mid=$mid&msg=$msg&ls_error=$ls_error');";
  echo "</script>";	
}
//--------------------- end button action ------------------------------------
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript">
  function doBayar() {
  	window.open('../ajax/pn5043_popupbayar.php','bayar','width=400,height=100,top=100,left=100,scrollbars=yes')
  }		

	function doBayarJPBerkala()
	{
   	var c_kode_cara_bayar						 = window.document.getElementById('kode_cara_bayar').value;
   	var c_kode_buku  								 = window.document.getElementById('kode_buku').value;

    if (c_kode_buku==""){
      alert('Kode Buku tidak boleh kosong...!!!');
      window.document.getElementById('kode_buku').focus();	
		}else if (c_kode_cara_bayar==""){
      alert('Cara Bayar tidak boleh kosong...!!!');
      window.document.getElementById('kode_cara_bayar').focus();										
    }else
		{
		 NewWindow('../ajax/pn504304_popupbayar.php?','bayar',300,50,'no');		
		}								 
	}   	
</script>

<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('A.NAMA_TK'  	 => 'Nama TK',
										'A.KPJ'  			 => 'No. Referensi',					
										'A.KODE_KLAIM' => 'Kode Klaim',
										'A.BLTHPROSES' => 'Bulan (YYYYMM)'														 
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
  		$filtersearch		=	 "and ".$pilihsearch." like '".$searchtxt."%' ";
  	}
	}			   
}
//filter kantor
if (strlen($gs_kantor_aktif)==3) 
{
 	$filterkantor = "and a.kode_kantor_pembayaran = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and a.kode_kantor_pembayaran in ".
      						"(	select kode_kantor from sijstk.ms_kantor ".
      						"		start with kode_kantor = '$gs_kantor_aktif' ".
      						"		connect by prior kode_kantor = kode_kantor_induk ".
      						"	) ";
}

if ($ls_rg_blth=="B0")
{
  $filterblth = "and to_char(a.blth_proses,'yyyymm') < to_char(sysdate,'yyyymm') ";
}elseif ($ls_rg_blth=="B1")
{
 	$filterblth = "and to_char(a.blth_proses,'yyyymm') = to_char(sysdate,'yyyymm') "; 		  
}elseif ($ls_rg_blth=="B2")
{
 	$filterblth = "and to_char(a.blth_proses,'yyyymm') > to_char(sysdate,'yyyymm') ";			
} 

// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.blthproses,a.kode_klaim  '.$o.' ';
}

//ambil total keseluruhan ------------------------------------------------------
$sql = "select sum(nvl(a.nom_kompensasi,0)) nom_kompensasi, sum(nvl(a.nom_rapel,0)) nom_rapel, sum(nvl(a.nom_berjalan,0)) nom_berjalan, ".
		 	 "			 sum(nvl(a.nom_berkala,0)) nom_berkala, sum(nvl(a.nom_pph,0)) nom_pph, sum(nvl(a.nom_pembulatan,0)) nom_pembulatan, ".
			 "			 sum(nvl(a.nom_manfaat_netto,0)) nom_manfaat_netto ". 
       "from sijstk.vw_pn_pembayaran_berkala a ".
       "where a.kode_user = '$username' ".
			 $filterblth.
			 "and a.kd_prg = '4' ".
			 $filterkantor.
       $filtersearch;
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();		
$ln_tot_all_kompensasi = $row['NOM_KOMPENSASI'];	
$ln_tot_all_rapel 		 = $row['NOM_RAPEL'];	
$ln_tot_all_berjalan 	 = $row['NOM_BERJALAN'];	
$ln_tot_all_berkala 	 = $row['NOM_BERKALA'];	
$ln_tot_all_pph 			 = $row['NOM_PPH'];	
$ln_tot_all_pembulatan = $row['NOM_PEMBULATAN'];	
$ln_tot_all_manfaat_netto = $row['NOM_MANFAAT_NETTO'];	
//end total keseluruhan --------------------------------------------------------
		
$url = 'pn504304.php';
$rows_per_page = 10; // untuk paging
$sql = 	"select ".
        "    a.kode_user, a.kode_berkala, a.kode_klaim, a.no_konfirmasi, a.no_proses, a.kd_prg, a.blth_proses, ".
				"		 to_char(a.blth_proses,'mm-yyyy') ket_blth_proses, a.blthproses,  ". 
        "    a.kode_kantor_pembayaran, a.kode_tk, a.nama_tk, a.kpj, ". 
        "    a.kode_tipe_penerima, a.kode_penerima_berkala, ". 
        "    a.nama_penerima_berkala, ". 
        "    a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala, ". 
        "    a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
        "    a.kode_pajak_pph, a.keterangan, ".
				"		 case when to_char(a.blth_proses,'yyyymm') <= to_char(sysdate,'yyyymm') then 'Y' else 'T' end flag_jt,  ".
				"		 (   select kode_kantor_konfirmasi from sijstk.pn_klaim_berkala ".
        "		 		 where kode_klaim=a.kode_klaim   ".
        "				 and no_konfirmasi=a.no_konfirmasi  ".
    		"				 ) kode_kantor_konfirmasi ". 				 
        "from sijstk.vw_pn_pembayaran_berkala a ".
        "where a.kode_user = '$username' ".
				$filterblth.
				"and a.kd_prg = '4' ".
				//"and nvl(a.status_rekening_sentral,'T')= 'T' ". -- untuk antisipasi singgungan dengan form sentralisasi rekening pembayaran jaminan
				$filterkantor.
        $filtersearch.					 					 
				$ls_order;
//echo $sql;
$total_rows  = f_count_rows($DB,$sql);
$total_pages = f_total_pages($total_rows, $rows_per_page);
$othervar		= "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&f=".$_GET['f']."&o=".$o."&rg_blth=".$ls_rg_blth;
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
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">JP BERKALA</font>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="1" onclick="this.form.submit()"  <?=$sel1;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">LUMPSUM</font>
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<font  color="#FF0" size="2;" face="Arial,Verdana">MELALUI REKENING:</font>
						<?
						switch($ls_rg_jenisrekening)
            {
            case 'r1' : $selr1="checked"; break;
						case 'r4' : $selr4="checked"; break;
            }
            ?>
						<input type="radio" name="rg_jenisrekening" value="r1" onclick="this.form.submit()"  <?=$selr1;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">KANTOR CABANG</font>
						<!--<input type="radio" name="rg_jenisrekening" value="r4" onclick="this.form.submit()"  <?=$selr4;?>>&nbsp;<font  color="#ffffff" size="2;" face="Arial,Verdana">SENTRALISASI KAPU</font>-->
			</h3>
		</td>
	</tr>	
  <tr><td colspan="3"></br></td></tr>	
</table>

</br>

<?
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&ld_tglawaldisplay=".$ld_tglawaldisplay."&ld_tglakhirdisplay=".$ld_tglakhirdisplay."&rg_blth=".$ls_rg_blth;				 
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata2 cellspacing=0 width=99% border=0 align=center bordercolor=#C0C0C0 background-color= #ffffff>";						 												 					 	  
?>
<tr>
  <td colspan="8" align="left">
      <? 
      switch($ls_rg_blth)
      {
      case 'B0' : $selB0="checked"; break;
			case 'B1' : $selB1="checked"; break;
      case 'B2' : $selB2="checked"; break;
  		case 'B3' : $selB3="checked"; break;
      }
      ?>
      <input type="radio" name="rg_blth" value="B0" onclick="this.form.submit()"  <?=$selB0;?>>&nbsp;<font color="#009999" size="1;" face="Arial,Verdana"><b>JATUH TEMPO S/D BULAN LALU</b></font>&nbsp;&nbsp;
			<input type="radio" name="rg_blth" value="B1" onclick="this.form.submit()"  <?=$selB1;?>>&nbsp;<font color="#009999" size="1;" face="Arial,Verdana"><b>JATUH TEMPO BULAN BERJALAN</b></font>&nbsp;&nbsp;
      <input type="radio" name="rg_blth" value="B2" onclick="this.form.submit()"  <?=$selB2;?>>&nbsp;<font  color="#009999" size="1;" face="Arial,Verdana"><b>BELUM JATUH TEMPO</b></font>
  		<input type="radio" name="rg_blth" value="B3" onclick="this.form.submit()"  <?=$selB3;?>>&nbsp;<font  color="#009999" size="1;" face="Arial,Verdana"><b>SEMUA BULAN</b></font>
  </td>		
  
  <td colspan="5" align="right">Search By &nbsp
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
	<th colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>
</tr>	

<tr>
	<th></th>
	<th colspan="3" style="text-align:center;">Periode Pembayaran</th>
	<th colspan="3" style="text-align:center;">Penerima Manfaat Berkala</th>
	<th colspan="6" style="text-align:center;">Rincian Pembayaran (Rapel + Kompensasi + JP Berkala Bulan Berjalan - PPh)</th>	
</tr>

<tr>
	<th colspan="1">&nbsp;</th>
	<th colspan="3"><hr style="color:#F5F5F5;"/></th>
	<th colspan="3"><hr style="color:#F5F5F5;"/></th>
	<th colspan="6"><hr style="color:#F5F5F5;"/></th>	
</tr>

<?		

echo "<tr>";
echo "<th class=awal>&nbsp;<input type=checkbox name=toggle value=\"\" onclick=\"checkAll(".$jmlrow."); \" /><b></b></th>";
echo "<th style=text-align:center;width:70px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b><font color=#000000>Kode Klaim</font></b></a></th>";
echo "<th style=text-align:center;width:50px;>&nbsp;<a href=\"$PHP_SELF?f=a.blthproses&o=$o$sortvar\"><b><font color=#000000>Bulan</font></b></a></th>";
echo "<th style=text-align:center;width:30px;><a href=\"$PHP_SELF?f=a.kode_kantor_pembayaran&o=$o$sortvar\"><b><font color=#000000>Ktr</font></b></a></th>";
echo "<th style=text-align:center;width:30px;><a href=\"$PHP_SELF?f=a.kode_kantor_pembayaran&o=$o$sortvar\"><b><font color=#000000>Konf</font></b></a></th>";
echo "<th style=text-align:center;>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b><font color=#000000>No. Ref</font></b></a></th>";
echo "<th style=text-align:center;>&nbsp;<a href=\"$PHP_SELF?f=a.nama_tk&o=$o$sortvar\"><b><font color=#000000>Nama TK</font></b></a></th>";
echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.nama_penerima_berkala&o=$o$sortvar\"><b><font color=#000000>Nama Penerima</font></b></a></th>";
echo "<th style=text-align:right;center;width:90px;><a href=\"$PHP_SELF?f=a.nom_rapel&o=$o$sortvar\"><b><font color=#000000>Rapel</font></b></a></th>";
echo "<th style=text-align:right;center;width:90px;><a href=\"$PHP_SELF?f=a.nom_kompensasi&o=$o$sortvar\"><b><font color=#000000>Kompensasi</font></b></a></th>";
echo "<th style=text-align:right;center;width:90px;><a href=\"$PHP_SELF?f=a.nom_berjalan&o=$o$sortvar\"><b><font color=#000000>Bln Berjalan</font></b></a></th>";
echo "<th style=text-align:right;center;width:90px;><a href=\"$PHP_SELF?f=a.nom_berkala&o=$o$sortvar\"><b><font color=#000000>Tot Berkala</font></b></a></th>";
echo "<th style=text-align:right;center;width:50px;><a href=\"$PHP_SELF?f=a.nom_pph&o=$o$sortvar\"><b><font color=#000000>PPh</font></b></a></th>";
echo "<th style=text-align:right;center;width:90px;><a href=\"$PHP_SELF?f=a.nom_manfaat_netto&o=$o$sortvar\"><b><font color=#000000>Jml Dibayar</font></b></a></th>";
echo "</tr>";	

?>
<tr>
	<th colspan="13"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
</tr>
<?					
$sql = f_query_perpage($sql, $start_row, $rows_per_page);
$DB->parse($sql);
$DB->execute();
$i=0;
$n=1;
$ln_tot_rapel = 0;
$ln_tot_kompensasi = 0;
$ln_tot_berjalan = 0;
$ln_tot_manfaat_netto = 0;
while ($row = $DB->nextrow())
{	    	
	echo "<tr bgcolor=#".($n%2 ? "f3f3f3" : "ffffff").">"; 
	echo "<td class=awal>&nbsp;";
	echo "<input type=hidden name=cebox[] value=".$row["KODE_BERKALA"].">";
	echo "<input type=hidden id=kode_berkala".$i." name=kode_berkala".$row["KODE_BERKALA"]." value=".$row["KODE_BERKALA"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KODE_BERKALA"]." value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=no_konfirmasi".$i." name=no_konfirmasi".$row["KODE_BERKALA"]." value=".$row["NO_KONFIRMASI"].">";
	echo "<input type=hidden id=no_proses".$i." name=no_proses".$row["KODE_BERKALA"]." value=".$row["NO_PROSES"].">";
	echo "<input type=hidden id=kd_prg".$i." name=kd_prg".$row["KODE_BERKALA"]." value=".$row["KD_PRG"].">";	
	echo "<input type=hidden id=kode_kantor_pembayaran".$i." name=kode_kantor_pembayaran".$row["KODE_BERKALA"]." value=".$row["KODE_KANTOR_PEMBAYARAN"].">";
	if ($row["FLAG_JT"]=="Y")
	{
	 	 echo "<input type=checkbox id=cb".$i." name=s".$row["KODE_BERKALA"]." value=".$row["KODE_BERKALA"]." onclick=\"isChecked(this.checked);\">";
	}else
	{
	 	 echo "<input type=hidden id=cb".$i." name=s".$row["KODE_BERKALA"].">";
	}
	echo "&nbsp;</td>";
	echo "<td style=\"text-align:center;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;<a href=# onclick=\"NewWindow('../form/pn5049.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn504304.php','info',1050,690,'yes')\">".$row["KODE_KLAIM"]."</a></td>";
  echo "<td style=\"text-align:center;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;".$row["KET_BLTH_PROSES"]."</a></td>";
	echo "<td style=\"text-align:center;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;".$row["KODE_KANTOR_PEMBAYARAN"]."</a></td>";
	echo "<td style=\"text-align:center;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;".$row["KODE_KANTOR_KONFIRMASI"]."</a></td>";
  echo "<td style=\"text-align:center;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td style=\"text-align:left;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;".$row["NAMA_TK"]."</a></td>";
	echo "<td style=\"text-align:left;font-family:Arial, verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;<a href=# onclick=\"NewWindow('../form/pn5045.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&no_konfirmasi=".$row["NO_KONFIRMASI"]."&mid=$ls_mid&sender=pn504304.php','info',1180,580,'yes')\">".$row["NAMA_PENERIMA_BERKALA"]."</a></td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_RAPEL"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_KOMPENSASI"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_BERJALAN"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_BERKALA"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_PPH"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right;font-family:Arial,verdana,tahoma, arial,sans-serif;font-size:11px;\">&nbsp;". ExtendedFunction::number_format_null($row["NOM_MANFAAT_NETTO"],2,".",",") ."</td>";
	echo "</tr>";
	$i++; $n++;
	
	$ln_tot_rapel += $row["NOM_RAPEL"];
	$ln_tot_kompensasi += $row["NOM_KOMPENSASI"];
	$ln_tot_berjalan += $row["NOM_BERJALAN"];
	$ln_tot_berkala += $row["NOM_BERKALA"];
	$ln_tot_pph += $row["NOM_PPH"];
	$ln_tot_manfaat_netto += $row["NOM_MANFAAT_NETTO"];
}											
?>
<tr>
	<!--<td colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.25), rgba(0,0,0,0));"/></td>-->
	<th colspan="13"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>
</tr>

<tr>
  <td align="left" colspan="6">
		<b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
  	<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">	
  </td>	
	<td colspan="1" style="text-align:center"><i>Sub Total (page):</i></td>	
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_rapel,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_kompensasi,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_berjalan,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_berkala,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_pph,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_manfaat_netto,2,".",",");?></td>
</tr>

<tr>
	<td colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.15), rgba(0,0,0,0));"/></td>
</tr>

<tr>
  <td align="left" colspan="6">
		Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b>
  </td>	
	<td colspan="1" style="text-align:center"><i>Total Keseluruhan:</i></td>	
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_rapel,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_kompensasi,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_berjalan,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_berkala,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_pph,2,".",",");?></td>
	<td style="text-align:right"><?=ExtendedFunction::number_format_null($ln_tot_all_manfaat_netto,2,".",",");?></td>
</tr>

<tr>
	<!--<td colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.15), rgba(0,0,0,0));"/></td>-->
	<td></td>
	<td colspan="11"><hr style="color:#F5F5F5;"/></td>
	<td></td>
</tr>

<!--
<tr>
	<td colspan="13">&nbsp;</td>
</tr>
-->

<tr>
  	<td colspan="13" style="text-align:center">
      Dibayar Melalui &nbsp;&nbsp;&nbsp;&nbsp;*
			<?
			if ($ls_kode_cara_bayar=="")
			{
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' and rownum=1 ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();		
        $ls_kode_cara_bayar = $row['KODE'];			 	 
			}
			?>	
      <select size="1" id="kode_cara_bayar" name="kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>" class="select_format" style="background-color:#ffff99;text-align:center;width:100px;height:25px;">
      <option value="">- cara byr --</option>
      <? 
      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' order by seq";
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
        echo "<option ";
        if (($row["KODE"]==$ls_kode_cara_bayar && strlen($row["KODE"])==strlen($ls_kode_cara_bayar))) { echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select> 
			
			<?
			if ($ls_kode_buku=="")
			{
        $sql = "select a.kode_kantor, a.kode_buku, a.kode_bank, c.nama_bank||' ('||a.kode_rekening||')' keterangan ".
               "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ".
               "where a.kode_kantor = b.kode_kantor(+) ". 
               "and a.kode_bank = b.kode_bank(+) ". 
               "and a.kode_rekening = b.kode_rekening(+) ". 
               "and a.kode_buku = b.kode_buku(+) ". 
               "and a.kode_bank = c.kode_bank ". 
               "and b.tipe_rekening = '16' ". 
               "and a.kode_kantor = '$gs_kantor_aktif' ".
               "and a.kd_prg = '4' ".
    					 "and nvl(a.aktif,'T')='Y' ".
							 "and rownum = 1";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();		
        $ls_kode_buku = $row['KODE_BUKU'];	
				$ls_kode_bank = $row['KODE_BANK'];
				$ls_nama_bank = $row['KETERANGAN'];		 	 
			}
			?>			
		  <input type="text" id="kode_buku" name="kode_buku" value="<?=$ls_kode_buku;?>" tabindex="2" readonly maxlength="30" style="background-color:#ffff99;text-align:center;width:70px;height:20px;">
      <input type="text" id="nama_bank" name="nama_bank" value="<?=$ls_nama_bank;?>" readonly class="disabled" border="0" style="background-color:#F5F5F5;text-align:center;width:250px;height:20px;">    								
      <input type="hidden" id="kode_bank" name="kode_bank" value="<?=$ls_kode_bank;?>">
			<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn504301_lov_kodebuku.php?p=pn504301.php&a=adminForm&b=kode_buku&c=kode_bank&d=nama_bank&e=<?=$gs_kantor_aktif;?>&f=4','',800,500,1)">							
      <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle" style="height:26px;"></a>
			
			&nbsp;&nbsp;<input type="button" class="btn green" name="btnbayar" style="height:25px;" onclick="if(confirm('Apakah anda yakin akan melakukan pembayaran JP Berkala..?')) doBayarJPBerkala()" value="              BAYAR              ">
			<input type="hidden" name="trigersubmit" value="0">   				  			
  	</td>
</tr>

</table>

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

</br>

<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
	<li style="margin-left:15px;"><font color="#ff0000">JATUH TEMPO S/D BULAN LALU </font> dan <font color="#ff0000">JATUH TEMPO S/D BULAN BERJALAN </font> menampilkan data JP Berkala yang sudah jatuh tempo dari periode sebelumnya sampai dengan bulan berjalan dan siap untuk dibayarkan.</li>		
	<li style="margin-left:15px;"><font color="#ff0000">BELUM JATUH TEMPO</font> menampilkan data JP Berkala periode bulan berikutnya (belum jatuh tempo). Tickmark pembayaran dihilangkan untuk menandakan bahwa data belum bisa dibayarkan.</li>
	<!--<li style="margin-left:15px;"><font color="#ff0000">SEMUA BULAN</font> menampilkan data JP Berkala baik yg sudah maupun belum jatuh tempo, dengan tujuan untuk mempermudah melakukan pencarian semua periode JP Berkala yang belum dibayarkan.</li>-->		
	<li style="margin-left:15px;">Klik data kolom <font color="#ff0000">Kode Klaim</font> untuk menampilkan detil informasi klaim dan <font color="#ff0000">Nama Penerima</font> untuk menampilkan detil konfirmasi, penerima manfaat, maupun periode jatuh tempo pembayaran.</li>	
	<li style="margin-left:15px;"><font color="#ff0000">Tickmark</font> JP Berkala yang akan dibayarkan kemudian pilih <font color="#ff0000">Dibayar Melalui </font> dan <font color="#ff0000">Rekening</font> yang digunakan untuk pembayaran JP Berkala kemudian klik tombol <font color="#ff0000">Bayar</font>.</li>
	<li style="margin-left:15px;"><i>Note: Pembayaran dapat melalui <font color="#ff0000"> REKENING KANTOR CABANG</font> ataupun <font color="#ff0000"> REKENING SENTRALISASI KANTOR PUSAT</font>.</li>
</fieldset>

</div>	 	

<!--
<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>
-->

