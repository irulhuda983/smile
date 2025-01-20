<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$DB_PROFIL 	= new Database($olap_DBUser,$olap_DBPass,$olap_DBName);

$pagetitle = "CORE SIJSTK";
$gs_pagetitle = "DAFTAR PERUSAHAAN ELIGIBLE";

$ld_tglawaldisplay     = !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay    = !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];

$kode_kantor = $_GET['kode_kantor'];
$kode_user = $_GET['kode_user'];
$pilihsearch = $_POST['pilihsearch'];
$searchtxt   = $_POST['searchtxt']; 

$ls_aktif	 = $_POST['aktif'];

if ($ls_aktif=="on" || $ls_aktif=="ON" || $ls_aktif=="Y")
{
	$ls_aktif = "Y";
}else
{
	$ls_aktif = "T";
}

$p  = $_POST['vp'];
$a  = $_POST['va'];
$b  = $_POST['vb'];
$c  = $_POST['vc'];
$d  = $_POST['vd'];
$e  = $_POST['ve'];
$f  = $_POST['vf'];
$g  = $_POST['vg'];
$h  = $_POST['vh'];
$j  = $_POST['vj'];
$k  = $_POST['vk'];
$l  = $_POST['vl'];
$m  = $_POST['vm'];
$n  = $_POST['vn'];
$q  = $_POST['vq'];
  
if ($a=="")
{
  $p  = $_GET['p'];
  $a  = $_GET['a'];
  $b  = $_GET['b'];
  $c  = $_GET['c'];
  $d  = $_GET['d'];
  $e  = $_GET['e'];
  $f  = $_GET['f'];
  $g  = $_GET['g'];
  $h  = $_GET['h'];
  $j  = $_GET['j'];
  $k  = $_GET['k'];
  $l  = $_GET['l'];
  $m  = $_GET['m'];
  $n  = $_GET['n'];
  $q  = $_GET['q']; 
  $pilihsearch = $_POST['pilihsearch'];
  $searchtxt   = $_POST['searchtxt']; 
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
<script type="text/javascript" src="../../javascript/appform.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>
<script language="javascript">
function fl_js_set_tickmark(v_i)
    {
    	var form = document.fpopup;
		var n_tickmark_prs = 'cb_box'+v_i;		
    	if (document.getElementById(n_tickmark_prs).checked)
    	{
			document.getElementById(n_tickmark_prs).value = 'Y';
    	}
    	else
    	{
    		document.getElementById(n_tickmark_prs).value = 'T';
    	}	
    }
		function proceed () 
		{
			var form = document.createElement('form');
				form.setAttribute('method', 'post');
				form.setAttribute('action', '<?=$PHP_SELF."?&kode_kantor=".$kode_kantor."&kode_user=".$kode_user;?>');
				form.style.display = 'hidden';
				document.body.appendChild(form)
				form.submit();
		}

	</script>	

</head>
<body>
<form action="<?=$PHP_SELF."?&kode_kantor=".$kode_kantor."&kode_user=".$kode_user;?>" method="post" id="fpopup" name="fpopup">
<div id="header-popup"> 
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">

<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->
<table class="caption">
  <tr> 
    <td colspan="2">
      &nbsp;        
    </td>
    <td align="right">Search By &nbsp
      <select name="pilihsearch">
      <? 
      switch($pilihsearch)
      {
    case 'sc_npp' : $sel1="selected"; break;
    case 'sc_pembina' : $sel2="selected"; break;  
      }
      ?>
    <option value="sc_all">--ALL--</option>
    <option value="sc_npp" <?=$sel1;?>>NPP</option>
    <option value="sc_pembina" <?=$sel2;?>>Kode Pembina</option>     
      </select>
      <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="15" style="background-color: #ccffff;">
      <input type="submit" name="cari2" id="cari2" value="GO">
	 
	  
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
if (isset($_POST["btnsubMit"]))
{
	$ln_panjang = $_POST['jml_row'];
      for($i=0;$i<=$ln_panjang-1;$i++)
      {		 	           												 		        
        $ls_kode_perusahaan				= $_POST['kode_perusahaan'.$i];
		$ls_cb	= $_POST['cb_box'.$i];
        if ($ls_cb=="on" || $ls_cb=="ON" || $ls_cb=="Y")
        {
        	$ls_cb = "Y";
        }else
        {
        	$ls_cb = "T";	 
        }		
        if ($ls_cb=="Y")
        {
          
		  /*$sql = "update sijstk.pn_klaim_dokumen set ".
                 "	 tgl_diserahkan			= to_date('$ld_d_adm_tgl_diserahkan','dd/mm/yyyy'), ". 
        				 "	 ringkasan					= '$ls_d_adm_ringkasan', ".
        				 "	 url								= '$ls_d_adm_url', ". 
        				 "	 keterangan					= '$ls_d_adm_keterangan', ". 
        				 "	 status_diserahkan	= '$ls_d_adm_status_diserahkan', ". 
        				 "	 tgl_ubah						= sysdate, ". 
        				 "	 petugas_ubah 			= '$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ".
  							 "and kode_dokumen = '$ls_d_adm_kode_dokumen' ";		
          $DB->parse($sql);
          $DB->execute();*/
		  
		  $sql = "BEGIN PN.P_PN_PN5008.x_insert_pp('$ls_kode_perusahaan','$kode_user');  COMMIT; END;";
		  
		  $DB->parse($sql);
          $DB->execute();
        }							
      }    
	
}	

?>
        
<?
  //------------------------------------------- start list data --------------------------------
        // query with paging  
      if(isset($searchtxt))
        {
        $searchtxt    = strtoupper($searchtxt);
          if ($pilihsearch=="sc_npp")
          {
               $filtersearch = " and NPP like upper('%".$searchtxt."%') ";
          }             
          elseif($pilihsearch=="sc_pembina")
          {
               $filtersearch = " and kd_pembina like upper('%".$searchtxt."%') ";
          }  
      }   
      
	  $o = strtoupper($_GET['o']);
	if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
	if($_GET['f']!=''){
		$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
	}else{
		$ls_order = ' ORDER BY kode_menu '.$o.' ';
	}
	  
        $rows_per_page = 10;
        $url = 'pn5008_eligible.php'; // url sama dengan nama file        
        //The unfiltered SELECT
        /*$sql = "SELECT  TK.OU_NUM KPJ, TK.DUNS_NUM KODE_TK, NVL(CON.LAST_NAME,'UNDEFINED') NAMA_TK
                FROM    SIEBEL.S_ORG_EXT PU
                        , SIEBEL.S_ORG_EXT DIV
                        , SIEBEL.S_PARTY_REL TK_REL
                        , SIEBEL.S_ORG_EXT TK
                        , SIEBEL.S_CONTACT CON
                WHERE   PU.ROW_ID = DIV.PAR_OU_ID
                    AND DIV.ROW_ID = TK_REL.PARTY_ID
                    AND TK_REL.REL_PARTY_ID = TK.ROW_ID
                    AND TK.PR_CON_ID = CON.ROW_ID (+)
                    AND DIV.ROW_ID = '$div_id' 
                    AND PU.X_TOGGLE_ID = '003' AND DIV.X_TOGGLE_ID = '003' AND TK.X_TOGGLE_ID = '002'
                    AND NVL(TK_REL.END_DT, TO_DATE('31123000','DDMMRRRR')) = TO_DATE('31123000','DDMMRRRR') " .*/
	   
	    $sql = "SELECT NPP,
					   NAMA_PERUSAHAAN,
					   TGL_AWAL_PST,
					   KD_PEMBINA,
					   KODEPERUSAHAAN,
					   TO_CHAR (BLTH_REKON, 'MM-RRRR') BLTH_REKON
				  FROM (SELECT ROWNUM                              no,
							   B.NPP,
							   B.NM_PRS                            NAMA_PERUSAHAAN,
							   TO_CHAR (B.TGL_AWAL_PST, 'MM-RRRR') TGL_AWAL_PST,
							   A.KD_PEMBINA,
							   A.KODEPERUSAHAAN,
							   (SELECT MAX (TO_DATE (BULAN || '-' || TAHUN, 'MM-RRRR'))
								  FROM CORE_SIJSTK.TX_IUR_PRS
								 WHERE     KD_PRS = TO_NUMBER (A.KODEPERUSAHAAN)
									   AND STATUS_IUR = 'R')
								  BLTH_REKON
						  FROM clearance.PROFIL_PRS_PDS A, CORE_SIJSTK.AM_PRS B
						 WHERE     TO_NUMBER (A.KODEPERUSAHAAN) = B.KD_PRS
							   AND A.piutang = 'PATUH'
							   AND A.PDSPROGRAM = 'PATUH'
							   AND (A.PERIODE BETWEEN ADD_MONTHS (TRUNC (SYSDATE, 'MM'), -1)
												  AND LAST_DAY (TRUNC (SYSDATE)))
							   AND A.KODECABANG = '$kode_kantor'
							   AND FLOOR ( (SYSDATE - b.tgl_awal_pst) / 365) >= 3
							   AND NVL (B.TGL_NONAKTIF, '31-DEC-3000') = '31-DEC-3000'
							   AND (   NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS_VERIF_PERMINTAAN, 'BELUM') IN
														 ('BELUM', 'DISETUJUI')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR'))
									OR NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS_VERIFIKASI_PENGAJUAN,
														   'BELUM') IN
														 ('BELUM', 'DISETUJUI')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR'))
									OR NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS_PENETAPAN_KEGIATAN,
														   'BELUM') IN
														 ('BELUM', 'DISETUJUI')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR'))
									OR NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS_APPROVAL_PENGAJUAN,
														   'BELUM') IN
														 ('BELUM', 'DISETUJUI')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR'))
									OR NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS_VERIF_APPROVAL, 'BELUM') IN
														 ('BELUM', 'DISETUJUI')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR'))
									OR NOT EXISTS
										  (SELECT NULL
											 FROM PN.PN_PROMOTIF@TO_DEV
											WHERE     NVL (STATUS, 'BELUM') IN
														 ('BELUM',
														  'PERENCANAAN',
														  'PENGAJUAN',
														  'KLAIM')
												  AND KODE_PERUSAHAAN = A.KODEPERUSAHAAN
												  AND TAHUN = TO_CHAR (SYSDATE, 'RRRR')))
							   AND A.PERIODE IN
									  (SELECT MAX (PERIODE)
										 FROM clearance.PROFIL_PRS_PDS
										WHERE     KODEPERUSAHAAN = A.KODEPERUSAHAAN
											  AND KODECABANG = A.KODECABANG)) WHERE NPP IS NOT NULL " .
      
               // $filtertgl.
               $filtersearch;
               //"order by to_char(a.tgl_awal_pry,'yyyymmdd') ";
        //echo $sql;
        $total_rows  = f_count_rows($DB_PROFIL,$sql);
        $total_pages = f_total_pages($total_rows, $rows_per_page);
        //$othervar    = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
		
        if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
            $_GET['page'] = 1;
        } else if ( $_GET['page'] > $total_pages ) {
            $_GET['page'] = $total_pages;
        }
        $start_row = f_page_to_row($_GET['page'], $rows_per_page);
        $jmlrow    = $rows_per_page;
        
          echo "<table  id=mydata cellspacing=0>";
          echo "<tr>";
          echo "<th class=awal>Action</th>";
		  //echo "<th class=awal>&nbsp;<input type=checkbox name=checkboxkodeperusahaan value=\"\" onclick=\"checkAll(".$jmlrow."); \" /></th>";
          echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=npp&o=$o\"><b>NPP</b></a></th>";
          echo "<th><a href=\"$PHP_SELF?f=nama_perusahaan&o=$o\"><b>Nama Perusahaan</b></a></th>";
		  echo "<th><a href=\"$PHP_SELF?f=tgl_awal_pst&o=$o\"><b>Tgl Kepesertaan</b></a></th>";
		  echo "<th><a href=\"$PHP_SELF?f=blth_itrf&o=$o\"><b>BLTH ITRF</b></a></th>";
		  echo "<th><a href=\"$PHP_SELF?f=kode_pembina&o=$o\"><b> Kode Pembina</b></a></th>";
          echo "</tr>";
          
          $sql = f_query_perpage($sql, $start_row, $rows_per_page);
          $DB_PROFIL->parse($sql);
          $DB_PROFIL->execute();
          $i=0;
          $nx=1;
          // print_r($sql);
          while ($row = $DB_PROFIL->nextrow())
          {
        // $row['TARIF'] = number_format($row['TARIF'],2,',','.');
           echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff").">";
		   echo "<td class=awal>&nbsp;";
           echo "<input type=hidden name=checkboxkodeperusahaan[] value=".$row["KODEPERUSAHAAN"].">";
		   ?>
		   <input hidden type="text" id="kode_perusahaan<?=$i;?>" name="kode_perusahaan<?=$i;?>" value="<?=$row["KODEPERUSAHAAN"];?>">
		   <input type="checkbox" id="cb_box<?=$i;?>" name="cb_box<?=$i;?>" value="" onclick="fl_js_set_tickmark('<?=$i;?>');"></td>
		   <?
		   
           echo "<td>".$row["NPP"]."</td>";
		   echo "<td>".$row["NAMA_PERUSAHAAN"]."</td>";
		   echo "<td>".$row["TGL_AWAL_PST"]."</td>";
		   echo "<td>".$row["BLTH_REKON"]."</td>";
		   echo "<td>".$row["KD_PEMBINA"]."</td>";
              /*
			  <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".$row["KPJ"]."';".
              "window.opener.document.".$formname.".".$fieldnamec.".value='".$row["KODE_TK"]."'; ".
              "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
              "window.close();\" >".$row["KPJ"]."</a>";?></td>
            <td>
              <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".$row["KPJ"]."';".
              "window.opener.document.".$formname.".".$fieldnamec.".value='".$row["KODE_TK"]."'; ".
              "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
              "window.close();\" >".$row["NAMA_TK"]."</a>";?></td>
            </tr>*/
		   echo "</tr>";
            
           $i++; $nx++;
          }
    
  ?>
  </table>  
          
  <table class="paging">
      <tr>
          <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b>
			  <input hidden type="text" id="jml_row" name="jml_row" value="<?=$i;?>">
		  </td>
          <td height="15" align="right">
          <?$othervar = $othervar."&kode_kantor=".$kode_kantor."&kode_user=".$kode_user."";?>
          <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
          </td>
      </tr>
  </table>
  <br>
  <fieldset><legend></legend>
			<input type="submit" class="btn green" id="btnsubMit" name="btnsubMit" value="          SUBMIT             " />
		</fieldset>
  <?
  //---------------------------------------- end list data -------------------------
  ?>
  <div id="clear-bottom-popup"></div>
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">CORE SIJSTK</p>
</div>
</form>
</body>
</html>
