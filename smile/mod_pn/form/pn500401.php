<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM JP BERKALA";
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

$ls_kode_cara_bayar			= $_POST['kode_cara_bayar'];																																								
$ls_kode_buku						= $_POST['kode_buku'];
$ls_kode_bank						= $_POST['kode_bank'];
$ls_nama_bank						= $_POST['nama_bank'];

//switch form ----------------------------------------------------------------
if ($ls_rg_kategori=="4") //jika yg diklik adalah LUMPSUM
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500403.php?mid=$mid');";
  echo "</script>";
}else if ($ls_rg_kategori=="3") //jika yg diklik adalah LUMPSUM (SENTRALISASI REKENING)
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500402.php?mid=$mid');";
  echo "</script>";
}	
	
if($trigersubmit=='1')
{
	$msg = '';
	if ($ls_kode_cara_bayar=="" || $ls_kode_buku=="")
	{ 
		$ls_error = "1";
		$msg .= "pembayaran berkala gagal, cara bayar atau kode buku kosong, lengkapi data input...!!!"."<br>";
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
					
  				//pembayaran klaim berkala ---------------------------------------------
      		$qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_BAYAR_BERKALA ( ".
  						 	 "			'$ls_kode_klaim','$ln_no_konfirmasi','$ln_no_proses','$ls_kd_prg', ".
  							 "			'$ls_kode_kantor_pembayaran','$ls_kode_cara_bayar', '$ls_kode_buku', '$ls_kode_bank', ".
  						 	 "			'$username',:p_sukses,:p_mess);END;";											 	
          $proc = $DB->parse($qry);				
          oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ls_sukses = $p_sukses;	
      		$ls_mess = $p_mess;	
  						
  				$msg .= "pembayaran berkala untuk kode berkala ".${"kode_berkala".$cebox[$i]}." berhasil..."."<br>";	 
  		}	
  	}
	}
}
//--------------------- end button action ------------------------------------
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript">
function doBayar() {
	window.open('../ajax/pn500401_popupbayar.php','bayar','width=400,height=100,top=100,left=100,scrollbars=yes')
}			
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('A.NAMA_TK'  	=> 'Nama TK',
										'A.KPJ'  	=> 'No. Referensi',					
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
 	$filterkantor = "and a.kode_kantor_pembayaran = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and a.kode_kantor_pembayaran in ".
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
	$ls_order = ' ORDER BY a.blthproses, a.kode_klaim '.$o.' ';
}
		
$url = 'pn500401.php';
$rows_per_page = 10; // untuk paging
$sql = 	"select ".
        "    a.kode_user, a.kode_berkala, a.kode_klaim, a.no_konfirmasi, a.no_proses, a.kd_prg, a.blth_proses, ".
				"		 to_char(a.blth_proses,'mm-yyyy') ket_blth_proses, a.blthproses,  ". 
        "    a.kode_kantor_pembayaran, a.kode_tk, a.nama_tk, a.kpj, ". 
        "    a.kode_tipe_penerima, a.kode_penerima_berkala, ". 
        "    a.nama_penerima_berkala, ". 
        "    a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala, ". 
        "    a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
        "    a.kode_pajak_pph, a.keterangan ". 
        "from sijstk.vw_pn_pembayaran_berkala a ".
        "where a.kode_user = '$username' ".
				"and to_char(a.blth_proses,'yyyymm') <= to_char(sysdate,'yyyymm') ".
				"and a.kd_prg = '4' ".
				$filterkantor.
        $filtersearch.					 					 
				$ls_order;
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
	<tr>
		<td style="text-align:left;font: 14px Arial, Verdana, Helvetica, sans-serif;"><b><?=$gs_pagetitle;?></b></td> 
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
	</tr>
</table>
<?
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&ld_tglawaldisplay=".$ld_tglawaldisplay."&ld_tglakhirdisplay=".$ld_tglakhirdisplay;
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata cellspacing=0>";
echo "<tr>";
echo "<th class=awal>&nbsp;<input type=checkbox name=toggle value=\"\" onclick=\"checkAll(".$jmlrow."); \" /></th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.blthproses&o=$o$sortvar\"><b>Blth</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>No. Ref</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.nama_tk&o=$o$sortvar\"><b>Nama TK</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nama_penerima_berkala&o=$o$sortvar\"><b>Nama Penerima</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_berkala&o=$o$sortvar\"><b>Nom Berkala</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_pph&o=$o$sortvar\"><b>Nom PPh</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_pembulatan&o=$o$sortvar\"><b>Pembulatan</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_manfaat_netto&o=$o$sortvar\"><b>Nom Bayar</b></a></th>";
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
	echo "<input type=hidden name=cebox[] value=".$row["KODE_BERKALA"].">";
	echo "<input type=hidden id=kode_berkala".$i." name=kode_berkala".$row["KODE_BERKALA"]." value=".$row["KODE_BERKALA"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KODE_BERKALA"]." value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=no_konfirmasi".$i." name=no_konfirmasi".$row["KODE_BERKALA"]." value=".$row["NO_KONFIRMASI"].">";
	echo "<input type=hidden id=no_proses".$i." name=no_proses".$row["KODE_BERKALA"]." value=".$row["NO_PROSES"].">";
	echo "<input type=hidden id=kd_prg".$i." name=kd_prg".$row["KODE_BERKALA"]." value=".$row["KD_PRG"].">";	
	echo "<input type=hidden id=kode_kantor_pembayaran".$i." name=kode_kantor_pembayaran".$row["KODE_BERKALA"]." value=".$row["KODE_KANTOR_PEMBAYARAN"].">";
	echo "<input type=checkbox id=cb".$i." name=s".$row["KODE_BERKALA"]." value=".$row["KODE_BERKALA"]." onclick=\"isChecked(this.checked);\"></td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../ajax/pn5002_penetapan.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn500401.php','info','width=1050,height=690,yes')\">".$row["KODE_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["KET_BLTH_PROSES"]."</a></td>";
  echo "<td>&nbsp;".$row["KPJ"]."</a></td>";
	echo "<td>&nbsp;".$row["NAMA_TK"]."</a></td>";
  echo "<td>&nbsp;".$row["NAMA_PENERIMA_BERKALA"]."</a></td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_BERKALA"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_PPH"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_PEMBULATAN"],2,".",",") ."</td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_MANFAAT_NETTO"],2,".",",") ."</td>";			
	echo "<td>&nbsp;".$row["KODE_KANTOR_PEMBAYARAN"]."</a></td>";
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

<table>
	<tr>
  	<td>
      Cara Bayar &nbsp;&nbsp;&nbsp;&nbsp;*
    </td>
		<td>
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
      <select size="1" id="kode_cara_bayar" name="kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>" class="select_format" style="background-color:#ffff99;width:300px;">
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
  	</td>
	</tr>
	
	<tr>
  	<td>
      Kode Buku &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*
    </td>
		<td>
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
    					 "and nvl(a.aktif,'T')='Y' and rownum = 1";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();		
        $ls_kode_buku = $row['KODE_BUKU'];	
				$ls_kode_bank = $row['KODE_BANK'];
				$ls_nama_bank = $row['KETERANGAN'];		 	 
			}
			?>			
		  <input type="text" id="kode_buku" name="kode_buku" value="<?=$ls_kode_buku;?>" tabindex="2" readonly size="7" maxlength="30" style="background-color:#ffff99;">
      <input type="hidden" id="kode_bank" name="kode_bank" value="<?=$ls_kode_bank;?>" size="5" readonly class="disabled">
      <input type="text" id="nama_bank" name="nama_bank" value="<?=$ls_nama_bank;?>" size="36" readonly class="disabled">    								
      <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5004_lov_kodebuku.php?p=pn500401.php&a=adminForm&b=kode_buku&c=kode_bank&d=nama_bank&e=<?=$gs_kantor_aktif;?>&f=4','',800,500,1)">							
      <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>   			
  	</td>
	</tr>			 
</table>

<div class="clear5"></div>
<div id="buttonbox">				
	<input type="hidden" name="trigersubmit" value="0">
  <div><input type="button" class="btn green" name="btnbayar" onclick="doBayar()" value="      BAYAR      "></div>
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
</div>	 				
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
