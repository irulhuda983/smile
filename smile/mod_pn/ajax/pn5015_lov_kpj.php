<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";

$DB = new Database($eimadmin_DBUser,$eimadmin_DBPass,$eimadmin_DBName);
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Tenaga Kerja";

$kode_perusahaan = $_GET['kode_perusahaan'];
$kode_promotif = $_GET['kode_promotif'];
$ls_user_login = $_SESSION["USER"];
//$pilihsearch = $_POST['pilihsearch'];
//$searchtxt   = $_POST['searchtxt']; 

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
	//$pilihsearch = $_GET['pilihsearch'];
	//$searchtxt 	 = $_GET['searchtxt'];	
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
		
		
</script>		
</head>
<body>
<form action="<?=$PHP_SELF."?&kode_perusahaan=".$kode_perusahaan."&kode_user=".$ls_user_login."&kode_promotif=".$kode_promotif;?>" method="post" id="lov_inv_porto" name="lov_inv_porto">
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
          		case 'sc_kpj' : $sel1="selected"; break;
				case 'sc_nama_lengkap' : $sel2="selected"; break;
				case 'sc_nomor_identitas' : $sel3="selected"; break; 
				case 'sc_kode_tk' : $sel4="selected"; break;		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kpj" <?=$sel1;?>>KPJ</option>
				<option value="sc_nama_lengkap" <?=$sel2;?>>Nama TK</option> 
				<option value="sc_nomor_identitas" <?=$sel3;?>>Nomor Identitas</option>
				<option value="sc_kode_tk" <?=$sel4;?>>Kode TK</option>     
				
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="15" style="background-color: #ccffff;">
		  <input type="submit" name="cari2" id="cari2" value="GO">
          <!--<input type="button" id="btnsubMit" name="btnsubMit" value="GO" onclick="proceed();">-->
		  
		<script language="javascript">  
		function proceed () 
		{
			var form = document.createElement('form');
				form.setAttribute('method', 'post');
				form.setAttribute('action', '<?=$PHP_SELF."?&kode_perusahaan=".$kode_perusahaan."&kode_user=".$ls_user_login."&kode_promotif=".$kode_promotif;?>');
				form.style.display = 'hidden';
				document.body.appendChild(form)
				form.submit();
		}
		</script>
		    
		  
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

	//echo "paul";
	//echo $kode_user;
	//echo $kode_promotif;
	if (isset($_POST["btnsimpan"]))
	{
		$ln_panjang = $_POST['jml_row'];
		//echo $ln_panjang;
		for($i=0;$i<=$ln_panjang-1;$i++)
		{		 	           												 		        
			$ls_kode_tk	= $_POST['kode_perusahaan'.$i];
			//echo $ls_kode_perusahaan;
			$ls_cb	= $_POST['cb_box'.$i];
			//echo $ls_cb;
			//echo $ls_kode_tk;
			if ($ls_cb=="on" || $ls_cb=="ON" || $ls_cb=="Y")
			{
				$ls_cb = "Y";
				//echo $ls_cb;
			}else
			{
				$ls_cb = "T";	 
				//echo $ls_cb;
			}			
			if ($ls_cb=="Y")
			{
				$sql = "BEGIN PN.P_PN_PN5008.x_insert_pp_dtl('$kode_promotif','$ls_kode_tk','$ls_user_login');  COMMIT; END;";
				$DB2->parse($sql);
				$DB2->execute();
			}							
		}    
	}

?>	
				
<?
		//------------------------- start list data --------------------------------
		
		
			if(isset($searchtxt))
			{
				$searchtxt = strtoupper($searchtxt);
				if ($pilihsearch=="sc_kpj")
				{
					$filtersearch = " and upper(x.kpj) like '%".$searchtxt."%' ";
					//echo $filtersearch;
				}
				elseif ($pilihsearch=="sc_nama_lengkap")
				{
					$filtersearch = " and upper(x.nama_tk) like '%".$searchtxt."%' ";
					//echo $filtersearch;
				}
				elseif ($pilihsearch=="sc_nomor_identitas")
				{
					$filtersearch = " and upper(x.nomor_identitas) like '%".$searchtxt."%' ";
					//echo $filtersearch;
				}
				elseif ($pilihsearch=="sc_kode_tk")
				{
					$filtersearch = " and upper(x.kode_tk) like '%".$searchtxt."%' ";
					//echo $filtersearch;
				}											                		 
				else
				{
					$filtersearch = " and (upper(x.kode_tk) like '%".$searchtxt."%' or upper(x.kpj) like '%".$searchtxt."%' or upper(x.nama_tk) like '%".$searchtxt."%' or upper(x.nomor_identitas) like '%".$searchtxt."%') ";
					//echo $filtersearch;
				}
			}
			
			
  	//filter data		
 	  		
    // query with paging								
    $rows_per_page = 10;
    $url = 'pn5015_lov_kpj.php'; // url sama dengan nama file

	
    //The unfiltered SELECT
    $sql = "SELECT X.* FROM (SELECT DISTINCT TK.DUNS_NUM          KODE_TK,
							TK.OU_NUM            KPJ,
							CON.LAST_NAME        NAMA_TK,
							CON.SOC_SECURITY_NUM NOMOR_IDENTITAS,
							CON.X_ID_TYPE		 JENIS_IDENTITAS,
							REL.X_STATUS         STATUS,
							TK.CUST_SINCE_DT     PERIODE,
							PU.OU_NUM            NPP,
							PU.NAME              NAMA_PERUSAHAAN,
							PU.DUNS_NUM			 KODE_PERUSAHAAN,		
							PU.X_KANTOR_CABANG   KODE_KANTOR
			  FROM SIEBEL.S_PARTY_REL rel,
				   siebel.s_org_ext   pu,
				   siebel.s_org_ext   div,
				   siebel.s_org_ext   tk,
				   siebel.s_contact   con
			 WHERE     div.row_id = rel.party_id
				   AND div.par_ou_id = pu.row_id
				   AND rel.rel_party_id = tk.row_id
				   AND pu.x_toggle_id = '003'
				   AND div.x_toggle_id = '003'
				   AND tk.x_toggle_id = '002'
				   AND UPPER (div.ou_type_cd) = 'DIVISION'
				   AND pu.duns_num = '$kode_perusahaan'
				   AND con.row_id = tk.pr_con_id(+)
				   AND NVL (rel.end_dt, '31-dec-3000') = '31-dec-3000'
				   AND rel.x_status = 'PESERTA') X WHERE 1 IS NOT NULL " .
				   $filtersearch;
    //echo $sql;
    $total_rows  = f_count_rows($DB,$sql);
    $total_pages = f_total_pages($total_rows, $rows_per_page);
	
    //$othervar		 = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
	
	

	
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
    $_GET['page'] = 1;
    } else if ( $_GET['page'] > $total_pages ) {
    $_GET['page'] = $total_pages;
    }
    $start_row = f_page_to_row($_GET['page'], $rows_per_page);
    $jmlrow		 = $rows_per_page;
    ?>
	
    <?
    echo "<table  id=mydata cellspacing=0>";
    echo "<tr>";
	echo "<th class=awal>Action</th>";
	//echo "<th class=awal>&nbsp;<input type=checkbox name=toggle value=\"\" onclick=\"checkAll(".$jmlrow."); \" /></th>";
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=x.kode_tk&o=$o\"><b>Kode TK</b></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=x.kpj&o=$o\"><b>KPJ</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.nama_tk&o=$o\"><b>Nama TK</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.nomor_identitas&o=$o\"><b>Nomor Identitas</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.status&o=$o\"><b>Status</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.periode&o=$o\"><b>Periode</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.npp&o=$o\"><b>NPP</b></a></th>";
	echo "<th><a href=\"$PHP_SELF?f=x.nama_perusahaan&o=$o\"><b>Perusahaan</b></a></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
    while ($row = $DB->nextrow())
    {
    echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff").">";
    ?>
	  <td>
		<input hidden type="text" id="kode_perusahaan<?=$i;?>" name="kode_perusahaan<?=$i;?>" value="<?=$row["KODE_TK"];?>">
		<input type="checkbox" id="cb_box<?=$i;?>" name="cb_box<?=$i;?>" value="" onclick="fl_js_set_tickmark('<?=$i;?>');"></td>
	  </td>
      <td>
        <?= $row["KODE_TK"] ;?></td>
      <td>
        <?= $row["KPJ"] ;?></td>
      <td>
        <?= $row["NAMA_TK"] ;?></td>
      <td>
        <?= $row["NOMOR_IDENTITAS"] ;?></td>								
      <td>
        <?= $row["STATUS"] ;?></td>	
	  <td>
        <?= $row["PERIODE"] ?></td>
      <td>
        <?= $row["NPP"] ;?></td>
      <td>			
        <?= $row["NAMA_PERUSAHAAN"] ;?></td>																																																			
      </tr>
    <? 
    $i++; $nx++;
    }	
    
    ?>
    </table>	
    
    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
		<input hidden type="text" id="jml_row" name="jml_row" value="<?=$i;?>">
        <td height="15" align="right">
        <?$othervar = $othervar."&kode_perusahaan=".$kode_perusahaan."&kode_user=".$ls_user_login."&kode_promotif=".$kode_promotif;?>
        <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>
	<br>
	  <fieldset><legend></legend>
			<input type="submit" class="btn green" id="btnsimpan" name="btnsimpan" value="          SUBMIT             " />
	  </fieldset>
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
