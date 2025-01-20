<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Tenaga Kerja";

$p	= $_POST['vp'];
$a	= $_POST['va'];
$b	= $_POST['vb'];
$c	= $_POST['vc'];
$d	= $_POST['vd'];
$e	= $_POST['ve'];
$f	= $_POST['vf'];
$g	= $_POST['vg'];
$h	= $_POST['vh'];
$j	= $_POST['vj'];
$k	= $_POST['vk'];
$l	= $_POST['vl'];
$m	= $_POST['vm'];
$n	= $_POST['vn'];
$q	= $_POST['vq'];
$pilihsearch = $_POST['pilihsearch'];
$searchtxt 	 = $_POST['searchtxt'];	
	  
if ($a=="")
{
  $p	= $_GET['p'];
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
	$q	= $_GET['q'];	
	$pilihsearch = $_GET['pilihsearch'];
	$searchtxt 	 = $_GET['searchtxt'];	
}

$formname=(!$a) ? "adminForm" : $a;	
$fieldnameb=(!$b) ? "porto_id" : $b;	
$fieldnamec=(!$c) ? "filc" : $c;	
$fieldnamed=(!$d) ? "fild" : $d;
$fieldnamee=(!$e) ? "file" : $e;
$fieldnamef=(!$f) ? "filf" : $f;
$fieldnameg=(!$g) ? "filg" : $g;
$fieldnameh=(!$h) ? "filh" : $h;
$fieldnamej=(!$j) ? "filj" : $j;
$fieldnamek=(!$k) ? "filk" : $k;
$fieldnamel=(!$l) ? "fill" : $l;
$fieldnamem=(!$m) ? "film" : $m;
$fieldnamen=(!$n) ? "filn" : $n;
$fieldnameq=(!$q) ? "filq" : $q;		
$px=(!$p) ? "inv2301_entrydealingticket_deposito.php" : $p;
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
</head>
<body>
<form action="<?=$PHP_SELF;?>" method="post" id="lov_inv_porto" name="lov_inv_porto">

    <table class="captionform">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
      </style>
      <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
      <tr><td colspan="3"></br></br></td></tr>	
  	</table>
		
    <table class="caption">
      <tr> 
        <td colspan="2">
      		&nbsp;				
        </td>
        <td align="right">Search By &nbsp
          <select name="pilihsearch" style="width:150px;">
            <? 
            switch($pilihsearch)
            {
          		case 'sc_nomor_identitas' : $sel1="selected"; break;
							case 'sc_kpj' : $sel2="selected"; break;
							case 'sc_nama_lengkap' : $sel3="selected"; break;
							case 'sc_kode_tk' : $sel4="selected"; break;		
            }
            ?>
        		<option value="sc_kpj" <?=$sel2;?>>No. Referensi</option>
						<option value="sc_nomor_identitas" <?=$sel1;?>>Nomor Identitas</option>
						<option value="sc_nama_lengkap" <?=$sel3;?>>Nama TK</option> 
						<option value="sc_kode_tk" <?=$sel4;?>>Kode TK</option>      
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" style="background-color: #ccffff;width:150px;">
          <input type="submit" name="cari2" value="GO">
          <input type="hidden" id="vp" name="vp" value="<?=$p;?>">
          <input type="hidden" id="va" name="va" value="<?=$a;?>">
          <input type="hidden" id="vb" name="vb" value="<?=$b;?>">
          <input type="hidden" id="vc" name="vc" value="<?=$c;?>">   	
          <input type="hidden" id="vd" name="vd" value="<?=$d;?>">
          <input type="hidden" id="ve" name="ve" value="<?=$e;?>">
          <input type="hidden" id="vf" name="vf" value="<?=$f;?>">
          <input type="hidden" id="vg" name="vg" value="<?=$g;?>">
          <input type="hidden" id="vh" name="vh" value="<?=$h;?>">	
          <input type="hidden" id="vj" name="vj" value="<?=$j;?>">
          <input type="hidden" id="vk" name="vk" value="<?=$k;?>">
          <input type="hidden" id="vl" name="vl" value="<?=$l;?>">	
          <input type="hidden" id="vm" name="vm" value="<?=$m;?>">
          <input type="hidden" id="vn" name="vn" value="<?=$n;?>">	
          <input type="hidden" id="vq" name="vq" value="<?=$q;?>">		
        </td>
      </tr>
    </table>
				
		<?
		//------------------------- start list data --------------------------------
  	//filter data		
 		if(isset($pilihsearch) && $searchtxt!="")
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kpj")
      {
       	$filtersearch = "and a.kpj = '".$searchtxt."' ";
      }elseif ($pilihsearch=="sc_nama_lengkap")
      {
       	$filtersearch = "and a.nama_tk like '".$searchtxt."%' ";
      }elseif ($pilihsearch=="sc_nomor_identitas")
      {
       	$filtersearch = "and a.nomor_identitas = '".$searchtxt."' ";
      }elseif ($pilihsearch=="sc_kode_tk")
      {
       	$filtersearch = "and a.kode_tk = '".$searchtxt."' ";
      }else
      {
       	$filtersearch = "and (a.kode_tk = '".$searchtxt."' or a.kpj = '".$searchtxt."' or a.nama_tk like '".$searchtxt."%' or a.nomor_identitas = '".$searchtxt."') ";
      }
			
  		// query with paging								
      $rows_per_page = 8;
      $url = 'pn5001_lov_kpj.php'; // url sama dengan nama file				
      //The unfiltered SELECT
      $sql = "select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, kpj, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, ". 
             "     kode_kantor, kode_divisi, nama_divisi,status_valid_identitas, ket_valid_identitas,status_tk, ". 
             "     to_char(tgl_efektif,'dd/mm/yyyy') tgl_efektif, to_char(tgl_expired,'dd/mm/yyyy') tgl_expired ".
             "from ".
             "( ".
             "    select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, kpj, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, kode_kantor, kode_divisi, ". 
             "        (select nama_divisi from kn.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_divisi = a.kode_divisi) nama_divisi,kode_na, ".
             "        status_valid_identitas, decode(nvl(a.status_valid_identitas,'T'),'Y','VALID','TIDAK VALID') ket_valid_identitas, ".
             "  			status_tk, tgl_efektif, tgl_expired, ".
             "        rank() over (partition by kode_kepesertaan,kode_tk order by kode_kepesertaan,kode_tk, no_mutasi desc) rank ". 
             "    from sijstk.vw_kn_tk a  ".
             "    where a.kode_segmen = '$j' ". 
						 			$filtersearch.
             ") x ".
             "where rank = 1 ".
             "and nvl(kode_na,'XXXXX') <> 'AG' ";
      //echo $sql;
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
			
      echo "<table  id=mydata cellspacing=0>";
      echo "<tr>";
      echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_tk&o=$o\"><b>Kode TK</b></a></th>";    
      echo "<th><a href=\"$PHP_SELF?f=a.kpj&o=$o\"><b>KPJ</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.nama_tk&o=$o\"><b>Nama TK</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.nomor_identitas&o=$o\"><b>Nomor Identitas</b></a></th>";
			echo "<th><a href=\"$PHP_SELF?f=a.status_valid_identitas&o=$o\"><b>NIK Valid</b></a></th>";
      echo "<th><a href=\"$PHP_SELF?f=a.status&o=$o\"><b>Status</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.kode_tk&o=$o\"><b>Periode</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.npp&o=$o\"><b>NPP</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.nama_perusahaan&o=$o\"><b>Perusahaan</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.kode_divisi&o=$o\"><b>Unit</b></a></th>";
			echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor&o=$o\"><b>Ktr Keps</b></a></th>";
      echo "</tr>";
      
      $sql = f_query_perpage($sql, $start_row, $rows_per_page);
      $DB->parse($sql);
      $DB->execute();
      $i=0;
      $nx=1;
  		//echo $sql;
      while ($row = $DB->nextrow())
      {
				echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TK"])."';".
                "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KPJ"])."'; ".  
        				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_TK"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_PERUSAHAAN"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_PERUSAHAAN"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnameg.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_DIVISI"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnameh.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NAMA_DIVISI"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamek.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NPP"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamel.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NOMOR_IDENTITAS"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamem.".value='".ExtendedFunction::ereg_replace("'","\'",$row["JENIS_IDENTITAS"])."'; ".
        				"window.opener.document.".$formname.".".$fieldnamen.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KANTOR"])."'; ".     											
                "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
  							"window.close();\" style = \"cursor: pointer;\" >";?>
  				<td><?=$row["KODE_TK"];?></td>
  				<td><?=$row["KPJ"];?></td>
  				<td><?=$row["NAMA_TK"];?></td>
					<td><?=$row["NOMOR_IDENTITAS"];?></td>
					<td><?=$row["KET_VALID_IDENTITAS"];?></td>
					<td><?=$row["STATUS_TK"];?></td>
    			<td><?=$row["TGL_EFEKTIF"];?> s/d <?=$row["TGL_EXPIRED"];?></td>
    			<td><?=$row["NPP"];?></td>
  				<td><?=$row["NAMA_PERUSAHAAN"];?></td>
  				<td><?=$row["KODE_DIVISI"];?></td>
  				<td><?=$row["KODE_KANTOR"];?></td>																																																							
        </tr>
				<?
       	$i++; $nx++;
      }
			
      if ($i == 0) {
        echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
        echo '<td colspan="10" style="text-align:center">-- Data Tidak Ditemukan --</td>';
        echo '</tr>';
      }			
      
			echo "</table>";
		}
		?>
		
    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";?>
        <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>
    <?
    //---------------------------------------- end list data -------------------------
    ?>
		
		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Pilih Jenis Pencarian</li>	
      <li style="margin-left:15px;">Untuk pencarian menggunakan No.Identitas isikan nomor identitas LENGKAP. </li>
			<li style="margin-left:15px;">Untuk pencarian menggunakan No.Referensi isikan nomor referensi LENGKAP</li>
			<li style="margin-left:15px;">Untuk pencarian menggunakan Nama TK, Nama dapat diakhiri % untuk keyword sebagian. <font color="#ff0000"> Pencarian dg NAMA TK memerlukan waktu yang lama, harap tunggu sampai proses benar-benar selesai..!!!</font></li>
			<li style="margin-left:15px;">Klik Tombol GO untuk memulai pencarian data</li>
		</div>
		
		</br>
				
	<div id="clear-bottom-popup"></div>
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SIJSTK</p>
</div>
</form>
</body>
</html>
