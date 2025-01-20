<?php
$pagetype = "report";
$gs_pagetitle = "Dashboard Pelayanan";
require_once "../../includes/header_app.php";
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$DB3 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_user 	= $_SESSION["USER"];

$ls_kode_segmen = !isset($_POST['kode_segmen']) ? $_GET['kode_segmen'] : $_POST['kode_segmen'];
$ls_nama_segmen = "";
if ($ls_kode_segmen!="")
{
  $sql = "select nama_segmen nama_segmen from kn.kn_kode_segmen where kode_segmen='$ls_kode_segmen' ";		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_nama_segmen  = $row["NAMA_SEGMEN"];
}
	
$ls_kode_kantor = !isset($_POST['kode_kantor']) ? $_GET['kode_kantor'] : $_POST['kode_kantor'];						
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
if($ls_kode_kantor=="")
{
 	$ls_kode_kantor =  $gs_kantor_aktif;
}

$ld_tgl_mmddyyyy  = !isset($_POST['tgl_mmddyyyy']) ? $_GET['tgl_mmddyyyy'] : $_POST['tgl_mmddyyyy'];

if ($ld_tgl_mmddyyyy=="")						
{
  $sql = "select ".
  			"				to_char(sysdate,'mm/dd/yyyy') tgl_mmddyyyy, ".
  			"				to_char(sysdate,'dd/mm/yyyy') tgl, ".
  			"				to_char(sysdate,'DD')||' '||(select upper(nama_periode) from lk.gl_kode_periode where kode_periode = to_char(sysdate,'mm'))||' '||to_char(sysdate,'YYYY') ket_tgl, ".
  			"				to_char(sysdate,'yyyy') tahun ".
  			"from dual ";		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
	$ld_tgl_mmddyyyy  = $row["TGL_MMDDYYYY"];
	$ld_tgl  					= $row["TGL"];
	$ls_kettgl  			= $row["KET_TGL"];
	$ls_tahun  				= $row["TAHUN"];
}else
{
	$sql = "select to_char(to_date('$ld_tgl_mmddyyyy','mm/dd/yyyy'),'dd/mm/yyyy') tgl from dual "; 		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
	$ld_tgl  = $row["TGL"];  
	
	$sql = "select to_char(to_date('$ld_tgl','dd/mm/yyyy'),'DD')||' '||(select upper(nama_periode) from lk.gl_kode_periode where kode_periode = to_char(to_date('$ld_tgl','dd/mm/yyyy'),'mm'))||' '||to_char(to_date('$ld_tgl','dd/mm/yyyy'),'YYYY') ket_tgl, ".
					"				to_char(to_date('$ld_tgl','dd/mm/yyyy'),'yyyy') tahun ".
					"from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
	$ls_kettgl  = $row["KET_TGL"];
	$ls_tahun  = $row["TAHUN"];
}

$sql = "select to_char(max(tgl_laporan),'dd Mon yyyy hh24:mi:ss') as last_update from pn.pn_dashboard where blth_laporan = trunc(to_date('$ld_tgl','dd/mm/yyyy'),'mm')";		
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ld_last_update  = $row["LAST_UPDATE"];

//hitung total pembayaran klaim jht, jkk, jkm, jpn -----------------------------
//get data from WS -----------------------------------------------------------
global $wsIp;
$ipDev  = $wsIp."/JSPN9010/GetDataDashboard";
$url    = $ipDev;
$chId   = 'CORE';

// set HTTP header -----------------------------------------------------------
$headers = array(
  'Content-Type'=> 'application/json',
'Accept'=> 'application/json',
	'X-Forwarded-For'=> $ipfwd,
);

if ($ls_kode_segmen=="")
{
 	$ls_kode_segmen_set = ''; 
}else
{
 	$ls_kode_segmen_set = $ls_kode_segmen;	 
}

// set POST params -----------------------------------------------------------
$data = array(
    'chId'				 => $chId,
    'reqId'				 => $ls_user,
    'KODE_KANTOR'	 => $ls_kode_kantor,    
    'P_TGL'		 		 => $ld_tgl,  
    'KODE_SEGMEN'	 => $ls_kode_segmen_set 
);

// Open connection -----------------------------------------------------------
$ch = curl_init();

// Set the url, number of POST vars, POST data -------------------------------
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute post --------------------------------------------------------------
$result = curl_exec($ch);
$resultArray = json_decode(utf8_encode($result));

$ln_nom_pembayaran_jht 	= $resultArray->DATA->NOM_PEMBAYARAN_JHT_FORMAT;
$ln_nom_pembayaran_jkk 	= $resultArray->DATA->NOM_PEMBAYARAN_JKK_FORMAT;
$ln_nom_pembayaran_jkm 	= $resultArray->DATA->NOM_PEMBAYARAN_JKM_FORMAT;
$ln_nom_pembayaran_jpn 	= $resultArray->DATA->NOM_PEMBAYARAN_JPN_FORMAT;
										 																													
?>

<!--refresh setiap 1 menit ------------->
<meta http-equiv="refresh" content="300">

<!-- App css -->
<link href="../../adminx/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../../adminx/assets/css/icons.css" rel="stylesheet" type="text/css" />
<link href="../../adminx/assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="../../adminx/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link href="../../adminx/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"  type="text/css">
<link href="../../adminx/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" rel="stylesheet">

<link href="../../adminx/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
<link href="../../adminx/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="../../adminx/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="../../adminx/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<script src="../../highcharts/modules/exporting.js"></script>
<script type="text/javascript" src="../../highcharts/js/drilldown.js"></script>
<script type="text/javascript" src="../../highcharts/js/highcharts-3d.js"></script>

<div class="content-page">
	<!-- start content -->
	<div class="content">
		<div class="container">
			
			<div class="row">
				<div class="col-2"></div>
					 
				<div class="col-8">
					<div class="page-title-box">
            <h5 class="page-title float-center">DASHBOARD PEMBAYARAN KLAIM PERIODE S/D <?=$ls_kettgl;?></h5>
          </div>	 
				</div>	
				
				<div class="col-2"></div>
			</div>
			<!-- end row -->
						
			<div class="row">
        <div class="col-6">
            <div class="form-group m-b-20">
              <!--<label>Kantor :</label>-->
              <div>
                  <select class="selectpicker show-tick" id="kode_kantor" name="kode_kantor" data-style="btn-dafault" onchange="this.form.submit()">
                      <option value="">-- kantor --</option>
              				<? 
              				$sql = "select kode_kantor, nama_kantor from ms.ms_kantor ".    									 	 
              				"start with kode_kantor = '$gs_kantor_aktif' ".
              				"connect by prior kode_kantor = kode_kantor_induk";
              				$DB->parse($sql);
              				$DB->execute();
              				while($row = $DB->nextrow())
              				{
              				echo "<option ";
              				if ($row["KODE_KANTOR"]==$ls_kode_kantor && strlen($ls_kode_kantor)==strlen($row["KODE_KANTOR"])){ echo " selected"; }
              				echo " value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
              				}
              				?>
                  </select>
              </div>
          	</div>
        </div>
				
				<div class="col-3">
            <div class="form-group m-b-20">
              <!--<label>Segmen :</label>-->
              <div>
                  <select class="selectpicker show-tick" id="kode_segmen" name="kode_segmen" data-style="btn-dafault" onchange="this.form.submit()">
                      <option value="">------  semua segmen   ------</option>
              				<? 
              				$sql = 	"select kode_segmen, nama_segmen from kn.kn_kode_segmen order by no_urut";						
              				$DB->parse($sql);
              				$DB->execute();
              				while($row = $DB->nextrow())
              				{
              					echo "<option ";
              					if ($row["KODE_SEGMEN"]==$ls_kode_segmen && strlen($ls_kode_segmen)==strlen($row["KODE_SEGMEN"])){ echo " selected"; }
              					echo " value=\"".$row["KODE_SEGMEN"]."\">".$row["NAMA_SEGMEN"]."</option>";
              				}
              				?>
                  </select>
              </div>
          	</div>
        </div>
				
				<div class="col-3">
            <div class="form-group m-b-20">
              <!--<label>Periode <i>(mm/dd/yyyy)</i> :</label>-->
              <div>
									<input class="form-control input-datepicker" type="text" id="tgl_mmddyyyy" name="tgl_mmddyyyy" value="<?=$ld_tgl_mmddyyyy;?>" onchange="this.form.submit();"/>
							</div>
          	</div>
        </div>	
			</div>	
			<!-- end row -->
			
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<img src="../../adminx/assets/images/jht-ico.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">
								<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9010_jht_graph01.php?task=view&kode_kantor=<?=$ls_kode_kantor;?>&kode_segmen=<?=$ls_kode_segmen;?>&tgl=<?=$ld_tgl;?>&tgl_mmddyyyy=<?=$ld_tgl_mmddyyyy;?>&sender=pn9010.php&sender_mid=<?=$mid;?>','Drilldown',1000,590,'no');" href="javascript:void(0);">
									 KLAIM JHT
								</a>  
							</p>
							<h5 class="">
								<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9010_jht_graph01.php?task=view&kode_kantor=<?=$ls_kode_kantor;?>&kode_segmen=<?=$ls_kode_segmen;?>&tgl=<?=$ld_tgl;?>&tgl_mmddyyyy=<?=$ld_tgl_mmddyyyy;?>&sender=pn9010.php&sender_mid=<?=$mid;?>','Drilldown',1000,590,'no');" href="javascript:void(0);">
									 <b><span data-plugin="counterup"><?=$ln_nom_pembayaran_jht;?></span></b>
								</a>
							</h5>
							<p class="m-0"><i>(Juta Rupiah)</i></p>	 
						</div>	 
					</div>	 
				</div><!-- end col -->
				
				<div class="col-lg-3 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<img src="../../adminx/assets/images/jkk-ico.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">Klaim JKK</p>
							<h5 class="">
								<a href="#">
									 <b><span data-plugin="counterup"><?=$ln_nom_pembayaran_jkk;?></span></b>
								</a>
							</h5>
							<p class="m-0"><i>(Juta Rupiah)</i></p>	 
						</div>	 
					</div>	 
				</div><!-- end col -->
				
				<div class="col-lg-3 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<img src="../../adminx/assets/images/jkm-ico.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">Klaim JKM</p>
							<h5 class="">
								<a href="#" >
									 <b><span data-plugin="counterup"><?=$ln_nom_pembayaran_jkm;?></span></b>
								</a>
							</h5>
							<p class="m-0"><i>(Juta Rupiah)</i></p>	 
						</div>	 
					</div>	 
				</div><!-- end col -->
				
				<div class="col-lg-3 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<img src="../../adminx/assets/images/icon-jpn.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">
								<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9010_jpn_graph01.php?task=view&kode_kantor=<?=$ls_kode_kantor;?>&kode_segmen=<?=$ls_kode_segmen;?>&tgl=<?=$ld_tgl;?>&tgl_mmddyyyy=<?=$ld_tgl_mmddyyyy;?>&sender=pn9010.php&sender_mid=<?=$mid;?>','Drilldown',1000,590,'no');" href="javascript:void(0);">
  							 	Klaim JP
  							</a>
							</p>
							<h5 class="">
  							<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9010_jpn_graph01.php?task=view&kode_kantor=<?=$ls_kode_kantor;?>&kode_segmen=<?=$ls_kode_segmen;?>&tgl=<?=$ld_tgl;?>&tgl_mmddyyyy=<?=$ld_tgl_mmddyyyy;?>&sender=pn9010.php&sender_mid=<?=$mid;?>','Drilldown',1000,590,'no');" href="javascript:void(0);">
  							 	<b><span data-plugin="counterup"><?=$ln_nom_pembayaran_jpn;?></span></b>
  							</a>
							</h5>
							<p class="m-0"><i>(Juta Rupiah)</i></p>	 
						</div>	 
					</div>	 
				</div><!-- end col -->
					 
			</div> 
			<!-- end row -->
			
			<!-------------------- grafik pembayaran jaminan per bulan -------------->
			<?
			//pembayaran klaim jht per bulan -----------------------------------------
      $ls_sub_judul_jht = "  ";
    	$data_param_jht = "";

      $ls_graph1_bar_xAxisCategory1_temp = "[";
      $ls_graph1_bar_val_temp = "[";
      $ln_graph1_bar_val_tot  = 0;			
      $ln_graph1_bar_val = 0;
      
      $ls_graph1_pie_val_temp .= "";
      $ls_graph1_pie_val = "";
      for($i=0;$i<ExtendedFunction::count($resultArray->JHT_BLN);$i++)
			{
        //grafik bar -----------------------------------------------------------
      	$ln_graph1_bar_val = 0;
        $ln_graph1_bar_val = $resultArray->JHT_BLN[$i]->NOM_PEMBAYARAN == "" ? "0" : $resultArray->JHT_BLN[$i]->NOM_PEMBAYARAN;
        $ln_graph1_bar_val_tot = $ln_graph1_bar_val_tot + (float)$ln_graph1_bar_val;
        $ls_graph1_bar_xAxisCategory1_temp .= "'".$resultArray->JHT_BLN[$i]->KET_BULAN."',";
        $ls_graph1_bar_val_temp .= $ln_graph1_bar_val.",";
      	
      	//grafik pie -----------------------------------------------------------
      	$ls_graph1_pie_val_x1 = $resultArray->JHT_BLN[$i]->BULAN;
        $ls_graph1_pie_val_x2 = $resultArray->JHT_BLN[$i]->KET_BULAN;
        $ln_graph1_pie_val_x3 = $resultArray->JHT_BLN[$i]->NOM_PEMBAYARAN;
        $ls_graph1_pie_val_temp .= "{ name: '{$ls_graph1_pie_val_x2}', y: {$ln_graph1_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&periode_trans={$ls_graph1_pie_val_x1}' },";
      }
      //var_dump($xAxisCategory1_temp);
      $ls_graph1_bar_xAxisCategory1 = $ls_graph1_bar_xAxisCategory1_temp == "[" ? "[]" : substr($ls_graph1_bar_xAxisCategory1_temp, 0, -1)."]";
      $ls_graph1_bar_val = $ls_graph1_bar_val_temp == "[" ? "[]" : substr($ls_graph1_bar_val_temp, 0, -1)."]";
      
      $ls_graph1_pie_val_temp = substr($ls_graph1_pie_val_temp, 0, -1);
      $ls_graph1_pie_val = $ls_graph1_pie_val.$ls_graph1_pie_val_temp.",";
      $ls_graph1_pie_display = substr($ls_graph1_pie_val, 0, -1);			
			
			//pembayaran klaim jkk per bulan -----------------------------------------
      $ls_sub_judul_jkk = " ";
    	$data_param_jkk = "";
			
      $ls_graph2_bar_xAxisCategory1_temp = "[";
      $ls_graph2_bar_val_temp = "[";
      $ln_graph2_bar_val_tot  = 0;			
      $ln_graph2_bar_val = 0;
			
      $ls_graph2_pie_val_temp .= "";
      $ls_graph2_pie_val = "";
      for($i=0;$i<ExtendedFunction::count($resultArray->JKK_BLN);$i++)
			{
        //grafik bar -----------------------------------------------------------------
      	$ln_graph2_bar_val = 0;
        $ln_graph2_bar_val = $resultArray->JKK_BLN[$i]->NOM_PEMBAYARAN == "" ? "0" : $resultArray->JKK_BLN[$i]->NOM_PEMBAYARAN;
        $ln_graph2_bar_val_tot = $ln_graph2_bar_val_tot + (float)$ln_graph2_bar_val;
        $ls_graph2_bar_xAxisCategory1_temp .= "'".$resultArray->JKK_BLN[$i]->KET_BULAN."',";
        $ls_graph2_bar_val_temp .= $ln_graph2_bar_val.",";
      	
      	//grafik pie -----------------------------------------------------------------
      	$ls_graph2_pie_val_x1 = $resultArray->JKK_BLN[$i]->BULAN;
        $ls_graph2_pie_val_x2 = $resultArray->JKK_BLN[$i]->KET_BULAN;
        $ln_graph2_pie_val_x3 = $resultArray->JKK_BLN[$i]->NOM_PEMBAYARAN;
        $ls_graph2_pie_val_temp .= "{ name: '{$ls_graph2_pie_val_x2}', y: {$ln_graph2_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&periode_trans={$ls_graph2_pie_val_x1}' },";
      }
      //var_dump($xAxisCategory1_temp);
      $ls_graph2_bar_xAxisCategory1 = $ls_graph2_bar_xAxisCategory1_temp == "[" ? "[]" : substr($ls_graph2_bar_xAxisCategory1_temp, 0, -1)."]";
      $ls_graph2_bar_val = $ls_graph2_bar_val_temp == "[" ? "[]" : substr($ls_graph2_bar_val_temp, 0, -1)."]";
      
      $ls_graph2_pie_val_temp = substr($ls_graph2_pie_val_temp, 0, -1);
      $ls_graph2_pie_val = $ls_graph2_pie_val.$ls_graph2_pie_val_temp.",";
      $ls_graph2_pie_display = substr($ls_graph2_pie_val, 0, -1);		
			
			//pembayaran klaim jkm per bulan -----------------------------------------
      $ls_sub_judul_jkm = " ";
    	$data_param_jkm = "";    

      $ls_graph3_bar_xAxisCategory1_temp = "[";
      $ls_graph3_bar_val_temp = "[";
      $ln_graph3_bar_val_tot  = 0;			
      $ln_graph3_bar_val = 0;
      
      $ls_graph3_pie_val_temp .= "";
      $ls_graph3_pie_val = "";

      for($i=0;$i<ExtendedFunction::count($resultArray->JKM_BLN);$i++)
			{
        //grafik bar -----------------------------------------------------------------
      	$ln_graph3_bar_val = 0;
        $ln_graph3_bar_val = $resultArray->JKM_BLN[$i]->NOM_PEMBAYARAN == "" ? "0" : $resultArray->JKM_BLN[$i]->NOM_PEMBAYARAN;
        $ln_graph3_bar_val_tot = $ln_graph3_bar_val_tot + (float)$ln_graph3_bar_val;
        $ls_graph3_bar_xAxisCategory1_temp .= "'".$resultArray->JKM_BLN[$i]->KET_BULAN."',";
        $ls_graph3_bar_val_temp .= $ln_graph3_bar_val.",";
      	
      	//grafik pie -----------------------------------------------------------------
      	$ls_graph3_pie_val_x1 = $resultArray->JKM_BLN[$i]->BULAN;
        $ls_graph3_pie_val_x2 = $resultArray->JKM_BLN[$i]->KET_BULAN;
        $ln_graph3_pie_val_x3 = $resultArray->JKM_BLN[$i]->NOM_PEMBAYARAN;
        $ls_graph3_pie_val_temp .= "{ name: '{$ls_graph3_pie_val_x2}', y: {$ln_graph3_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&periode_trans={$ls_graph3_pie_val_x1}' },";      
			}
      //var_dump($xAxisCategory1_temp);
      $ls_graph3_bar_xAxisCategory1 = $ls_graph3_bar_xAxisCategory1_temp == "[" ? "[]" : substr($ls_graph3_bar_xAxisCategory1_temp, 0, -1)."]";
      $ls_graph3_bar_val = $ls_graph3_bar_val_temp == "[" ? "[]" : substr($ls_graph3_bar_val_temp, 0, -1)."]";
      
      $ls_graph3_pie_val_temp = substr($ls_graph3_pie_val_temp, 0, -1);
      $ls_graph3_pie_val = $ls_graph3_pie_val.$ls_graph3_pie_val_temp.",";
      $ls_graph3_pie_display = substr($ls_graph3_pie_val, 0, -1);		
			
			//pembayaran klaim jpn per bulan -----------------------------------------
      $ls_sub_judul_jpn = " ";
    	$data_param_jpn = "";    	
			
      $ls_graph4_bar_xAxisCategory1_temp = "[";
      $ls_graph4_bar_val_temp = "[";
      $ln_graph4_bar_val_tot  = 0;			
      $ln_graph4_bar_val = 0;
      
      $ls_graph4_pie_val_temp .= "";
      $ls_graph4_pie_val = "";
			for($i=0;$i<ExtendedFunction::count($resultArray->JPN_BLN);$i++)
			{
        //grafik bar -----------------------------------------------------------------
      	$ln_graph4_bar_val = 0;
        $ln_graph4_bar_val = $resultArray->JPN_BLN[$i]->NOM_PEMBAYARAN == "" ? "0" : $resultArray->JPN_BLN[$i]->NOM_PEMBAYARAN;
        $ln_graph4_bar_val_tot = $ln_graph4_bar_val_tot + (float)$ln_graph4_bar_val;
        $ls_graph4_bar_xAxisCategory1_temp .= "'".$resultArray->JPN_BLN[$i]->KET_BULAN."',";
        $ls_graph4_bar_val_temp .= $ln_graph4_bar_val.",";
      	
      	//grafik pie -----------------------------------------------------------------
      	$ls_graph4_pie_val_x1 = $resultArray->JPN_BLN[$i]->BULAN;
        $ls_graph4_pie_val_x2 = $resultArray->JPN_BLN[$i]->KET_BULAN;
        $ln_graph4_pie_val_x3 = $resultArray->JPN_BLN[$i]->NOM_PEMBAYARAN;
        $ls_graph4_pie_val_temp .= "{ name: '{$ls_graph4_pie_val_x2}', y: {$ln_graph4_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&periode_trans={$ls_graph4_pie_val_x1}' },";
			}
      //var_dump($xAxisCategory1_temp);
      $ls_graph4_bar_xAxisCategory1 = $ls_graph4_bar_xAxisCategory1_temp == "[" ? "[]" : substr($ls_graph4_bar_xAxisCategory1_temp, 0, -1)."]";
      $ls_graph4_bar_val = $ls_graph4_bar_val_temp == "[" ? "[]" : substr($ls_graph4_bar_val_temp, 0, -1)."]";
      
      $ls_graph4_pie_val_temp = substr($ls_graph4_pie_val_temp, 0, -1);
      $ls_graph4_pie_val = $ls_graph4_pie_val.$ls_graph4_pie_val_temp.",";
      $ls_graph4_pie_display = substr($ls_graph4_pie_val, 0, -1);		
			?>
			
			<script type="text/javascript">							 
      	var chart1_bar; // grafik 1 - bar - pembayaran klaim jht per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart1_bar = new Highcharts.Chart(
        		{ 
              chart: {
      						renderTo: 'container_graph1_bar',	 
                  type: 'column',
                  options3d: {
                      enabled: true,
                      alpha: 10,
                      beta: 25,
                      depth: 70
                  }
              },
              title: {
                  text: null
              },
              plotOptions: {
                  column: {
                      depth: 25
                  }
              },
              xAxis: {
              	categories: <?=$ls_graph1_bar_xAxisCategory1?>,
      					labels: {
                    skew3d: true,
										rotation: -45,
                    style: {
                        fontSize: '8px'
                    }
                }
              },
              yAxis: { // Primary yAxis
                labels: {
                  formatter: function (){return (this.value/1000000000)+" Milyar"},
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	},
                title: {
                  text: null,
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	}
              },
              series: [{
                name: 'Manfaat Dibayarkan',
                data: <?=$ls_graph1_bar_val;?>
              }]
            });
          }
      	);
				
      	var chart1_pie; // grafik 1 - pie - pembayaran klaim jht per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart1_pie = new Highcharts.Chart(
      			{
              chart: {
      						renderTo: 'container_graph1_pie',	 
                  type: 'pie',
                  options3d: {
                      enabled: true,
                      alpha: 45
                  }
              },
              title: {
                  text: ' '
              },
              plotOptions: {
                  pie: {
                      innerSize: 100,
                      depth: 45,
      								allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                        style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'grey'
                        }
                      }
                  }
              },							
              series:            
              [{
                name: 'Total Pembayaran Klaim JHT',
                data: [<?=$ls_graph1_pie_display?>]
              }]
            });
          }
      	);
						
				var chart2_bar; // grafik 2 - bar - pembayaran klaim jkk per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart2_bar = new Highcharts.Chart(
        		{ 
              chart: {
      						renderTo: 'container_graph2_bar',	 
                  type: 'column',
                  options3d: {
                      enabled: true,
                      alpha: 10,
                      beta: 25,
                      depth: 70
                  }
              },
              title: {
                  text: null
              },
              plotOptions: {
                  column: {
                      depth: 25
                  }
              },
              xAxis: {
              	categories: <?=$ls_graph2_bar_xAxisCategory1?>,
      					labels: {
                    skew3d: true,
										rotation: -45,
                    style: {
                        fontSize: '8px'
                    }
                }
              },
              yAxis: { // Primary yAxis
                labels: {
                  formatter: function (){return (this.value/1000000000)+" Milyar"},
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	},
                title: {
                  text: null,
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	}
              },
              series: [{
                name: 'Manfaat Dibayarkan',
                data: <?=$ls_graph2_bar_val;?>
              }]
            });
          }
      	);
      	
      	var chart2_pie; // grafik 2 - pie - pembayaran klaim jkk per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart2_pie = new Highcharts.Chart(
      			{
              chart: {
      						renderTo: 'container_graph2_pie',	 
                  type: 'pie',
                  options3d: {
                      enabled: true,
                      alpha: 45
                  }
              },
              title: {
                  text: ' '
              },
              plotOptions: {
                  pie: {
                      innerSize: 100,
                      depth: 45,
      								allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                        style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'grey'
                        }
                      }
                  }
              },							
              series:            
              [{
                name: 'Total Pembayaran Klaim JKK',
                data: [<?=$ls_graph2_pie_display?>]
              }]
            });
          }
      	);
						
				var chart3_bar; // grafik 3 - bar - pembayaran klaim jkm per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart3_bar = new Highcharts.Chart(
        		{ 
              chart: {
      						renderTo: 'container_graph3_bar',	 
                  type: 'column',
                  options3d: {
                      enabled: true,
                      alpha: 10,
                      beta: 25,
                      depth: 70
                  }
              },
              title: {
                  text: null
              },
              plotOptions: {
                  column: {
                      depth: 25
                  }
              },
              xAxis: {
              	categories: <?=$ls_graph3_bar_xAxisCategory1?>,
      					labels: {
                    skew3d: true,
										rotation: -45,
                    style: {
                        fontSize: '8px'
                    }
                }
              },
              yAxis: { // Primary yAxis
                labels: {
                  formatter: function (){return (this.value/1000000000)+" Milyar"},
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	},
                title: {
                  text: null,
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	}
              },
              series: [{
                name: 'Manfaat Dibayarkan',
                data: <?=$ls_graph3_bar_val;?>
              }]
            });
          }
      	);
      	
      	var chart3_pie; // grafik 3 - pie - pembayaran klaim jkm per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart3_pie = new Highcharts.Chart(
      			{
              chart: {
      						renderTo: 'container_graph3_pie',	 
                  type: 'pie',
                  options3d: {
                      enabled: true,
                      alpha: 45
                  }
              },
              title: {
                  text: ' '
              },
              plotOptions: {
                  pie: {
                      innerSize: 100,
                      depth: 45,
      								allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                        style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'grey'
                        }
                      }
                  }
              },							
              series:            
              [{
                name: 'Total Pembayaran Klaim JKM',
                data: [<?=$ls_graph3_pie_display?>]
              }]
            });
          }
      	);
				
				var chart4_bar; // grafik 3 - bar - pembayaran klaim jkm per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart4_bar = new Highcharts.Chart(
        		{ 
              chart: {
      						renderTo: 'container_graph4_bar',	 
                  type: 'column',
                  options3d: {
                      enabled: true,
                      alpha: 10,
                      beta: 25,
                      depth: 70
                  }
              },
              title: {
                  text: null
              },
              plotOptions: {
                  column: {
                      depth: 25
                  }
              },
              xAxis: {
              	categories: <?=$ls_graph4_bar_xAxisCategory1?>,
      					labels: {
                    skew3d: true,
										rotation: -45,
                    style: {
                        fontSize: '8px'
                    }
                }
              },
              yAxis: { // Primary yAxis
                labels: {
                  formatter: function (){return (this.value/1000000000)+" Milyar"},
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	},
                title: {
                  text: null,
                  style: {
                  	color: Highcharts.getOptions().colors[1]
                  }
              	}
              },
              series: [{
                name: 'Manfaat Dibayarkan',
                data: <?=$ls_graph4_bar_val;?>
              }]
            });
          }
      	);
      	
      	var chart4_pie; // grafik 3 - pie - pembayaran klaim jkm per kanwil ----------
        $(document).ready(
        	function() 
        	{
            chart4_pie = new Highcharts.Chart(
      			{
              chart: {
      						renderTo: 'container_graph4_pie',	 
                  type: 'pie',
                  options3d: {
                      enabled: true,
                      alpha: 45
                  }
              },
              title: {
                  text: ' '
              },
              plotOptions: {
                  pie: {
                      innerSize: 100,
                      depth: 45,
      								allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                        style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'grey'
                        }
                      }
                  }
              },							
              series:            
              [{
                name: 'Total Pembayaran Klaim JPN',
                data: [<?=$ls_graph4_pie_display?>]
              }]
            });
          }
      	); 							
			</script>
			
			<!--row pembayaran klaim jht per bulan ---------------------------------->
			<div class="row">
				<div class="col-lg-6">
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran Klaim JHT Tahun <?=$ls_tahun;?></b></h4>
                <div class="widget-chart text-center">
    							<div id='container_graph1_bar' style="width:470px;height:256px;"></div>					
                </div>
              </div>
            </div>							 
					</div>
					
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran Klaim JHT Tahun <?=$ls_tahun;?></b></h4> 
                <div class="widget-chart text-center">
    							<div id='container_graph1_pie' style="width:470px;height:257px;"></div>					
                </div>
              </div>
            </div>							 
					</div>									
				</div>
							
				<div class="col-lg-6">
          <div class="card-box">
						
						<div class="row">	 					
							<h4 class="m-t-0 header-title"><b>Data Bulanan Pembayaran Klaim JHT <?=$ls_tahun;?></b></h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 mails table-actions-bar">
									<thead>
										<tr>
                      <th style="text-align:center">Bulan</th>
                      <th  style="text-align:right">Jumlah Kasus</th>
                      <th  style="text-align:right">Manfaat Dibayarkan</th>
                    </tr>
                  </thead>
									<tbody>
										<? //rincian pembayaran jht per-bulan ----------------------					              					
                    $i=0;		
										$ln_tot_kasus_jht  =0;
										$ln_tot_pembayaran_jht =0;
                    for($i=0;$i<ExtendedFunction::count($resultArray->JHT_BLN);$i++)
                    {
                      ?>
											<tr>							
                        <td style="text-align:center">
													<?=$resultArray->JHT_BLN[$i]->KET_BULAN;?>
												</td>
                        <td style="text-align:right"><?=number_format((float)$resultArray->JHT_BLN[$i]->JML_KASUS,0,".",",");?></td>  									
                        <td style="text-align:right;"><?=number_format((float)$resultArray->JHT_BLN[$i]->NOM_PEMBAYARAN,2,".",",");?></td>						
                      </tr>
                      <?							    							
                      //$i++;//iterasi i
											$ln_tot_kasus_jht  += (float)$resultArray->JHT_BLN[$i]->JML_KASUS;
											$ln_tot_pembayaran_jht += (float)$resultArray->JHT_BLN[$i]->NOM_PEMBAYARAN;
                    }	//end while
										
                    if ($i == 0) {
                      echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
                      echo '<td colspan="3" style="text-align:center">-- Belum Ada Pembayaran Klaim JHT --</td>';
                      echo '</tr>';
                    }	
                    ?>	 
									</tbody>
									<tr>
                    <th style="text-align:right"><i>Total</i></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_kasus_jht,0,".",",");?></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_pembayaran_jht,2,".",",");?></th>
                  </tr>		 
								</table>	 
							</div>	
						</div><!-- end row -->
					</div>
				</div>
			</div>	
			<!-- end row -->							
			<!--end row pembayaran klaim jht per bulan ------------------------------>
			
			<hr>
			
			<!--end row pembayaran klaim jkk per bulan ------------------------------>
			<div class="row">
				<div class="col-lg-6">
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran Klaim JKK Tahun <?=$ls_tahun;?></b></h4>
                <div class="widget-chart text-center">
    							<div id='container_graph2_bar' style="width:470px;height:256px;"></div>					
                </div>
              </div>
            </div>							 
					</div>
					
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran Klaim JKK Tahun <?=$ls_tahun;?></b></h4> 
                <div class="widget-chart text-center">
    							<div id='container_graph2_pie' style="width:470px;height:257px;"></div>					
                </div>
              </div>
            </div>							 
					</div>									
				</div>
							
				<div class="col-lg-6">
          <div class="card-box">
						
						<div class="row">	 					
							<h4 class="m-t-0 header-title"><b>Data Bulanan Pembayaran Klaim JKK <?=$ls_tahun;?></b></h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 mails table-actions-bar">
									<thead>
										<tr>
                      <th style="text-align:center">Bulan</th>
                      <th  style="text-align:right">Jumlah Kasus</th>
                      <th  style="text-align:right">Manfaat Dibayarkan</th>
                    </tr>
                  </thead>
									<tbody>
										<? //rincian pembayaran jkk per-bulan ----------------------					              					
                    $i=0;		
										$ln_tot_kasus_jkk  =0;
										$ln_tot_pembayaran_jkk =0;
                    for($i=0;$i<ExtendedFunction::count($resultArray->JKK_BLN);$i++)
                    {
                      ?>
											<tr>							
                        <td style="text-align:center">
													<?=$resultArray->JKK_BLN[$i]->KET_BULAN;?>
												</td>
                        <td style="text-align:right"><?=number_format((float)$resultArray->JKK_BLN[$i]->JML_KASUS,0,".",",");?></td>  									
                        <td style="text-align:right;"><?=number_format((float)$resultArray->JKK_BLN[$i]->NOM_PEMBAYARAN,2,".",",");?></td>						
                      </tr>
                      <?							    							
                      //$i++;//iterasi i
											$ln_tot_kasus_jkk  += (float)$resultArray->JKK_BLN[$i]->JML_KASUS;
											$ln_tot_pembayaran_jkk += (float)$resultArray->JKK_BLN[$i]->NOM_PEMBAYARAN;
                    }	//end while
										
                    if ($i == 0) {
                      echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
                      echo '<td colspan="3" style="text-align:center">-- Belum Ada Pembayaran Klaim JKK --</td>';
                      echo '</tr>';
                    }	
                    ?>	 
									</tbody>
									<tr>
                    <th style="text-align:right"><i>Total</i></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_kasus_jkk,0,".",",");?></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_pembayaran_jkk,2,".",",");?></th>
                  </tr>		 
								</table>	 
							</div>	
						</div><!-- end row -->
					</div>
				</div>
			</div>	
			<!-- end row -->	
			<!--end row pembayaran klaim jkk per bulan ------------------------------>
			
			<hr>
			
			<!--end row pembayaran klaim jkm per bulan ------------------------------>
			<div class="row">
				<div class="col-lg-6">
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran Klaim JKM Tahun <?=$ls_tahun;?></b></h4>
                <div class="widget-chart text-center">
    							<div id='container_graph3_bar' style="width:470px;height:256px;"></div>					
                </div>
              </div>
            </div>							 
					</div>
					
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran Klaim JKM Tahun <?=$ls_tahun;?></b></h4> 
                <div class="widget-chart text-center">
    							<div id='container_graph3_pie' style="width:470px;height:257px;"></div>					
                </div>
              </div>
            </div>							 
					</div>									
				</div>
							
				<div class="col-lg-6">
          <div class="card-box">
						
						<div class="row">	 					
							<h4 class="m-t-0 header-title"><b>Data Bulanan Pembayaran Klaim JKM <?=$ls_tahun;?></b></h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 mails table-actions-bar">
									<thead>
										<tr>
                      <th style="text-align:center">Bulan</th>
                      <th  style="text-align:right">Jumlah Kasus</th>
                      <th  style="text-align:right">Manfaat Dibayarkan</th>
                    </tr>
                  </thead>
									<tbody>
										<? //rincian pembayaran jkm per-bulan ----------------------						              					
                    $i=0;		
										$ln_tot_kasus_jkm  =0;
										$ln_tot_pembayaran_jkm =0;
										for($i=0;$i<ExtendedFunction::count($resultArray->JKM_BLN);$i++)
                    {
                      ?>
											<tr>							
                        <td style="text-align:center">
													<?=$resultArray->JKM_BLN[$i]->KET_BULAN;?>
												</td>
                        <td style="text-align:right"><?=number_format((float)$resultArray->JKM_BLN[$i]->JML_KASUS,0,".",",");?></td>  									
                        <td style="text-align:right;"><?=number_format((float)$resultArray->JKM_BLN[$i]->NOM_PEMBAYARAN,2,".",",");?></td>						
                      </tr>
                      <?							    							
                      //$i++;//iterasi i
											$ln_tot_kasus_jkm  += (float)$resultArray->JKM_BLN[$i]->JML_KASUS;
											$ln_tot_pembayaran_jkm += (float)$resultArray->JKM_BLN[$i]->NOM_PEMBAYARAN;
                    }	//end while
										
                    if ($i == 0) {
                      echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
                      echo '<td colspan="3" style="text-align:center">-- Belum Ada Pembayaran Klaim JKM --</td>';
                      echo '</tr>';
                    }	
                    ?>	 
									</tbody>
									<tr>
                    <th style="text-align:right"><i>Total</i></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_kasus_jkm,0,".",",");?></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_pembayaran_jkm,2,".",",");?></th>
                  </tr>		 
								</table>	 
							</div>	
						</div><!-- end row -->
					</div>
				</div>
			</div>	
			<!-- end row -->	
			<!--end row pembayaran klaim jkm per bulan ------------------------------>
			
			<hr>
			
			<!--end row pembayaran klaim jp per bulan ------------------------------>
			<div class="row">
				<div class="col-lg-6">
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran Klaim JP Tahun <?=$ls_tahun;?></b></h4>
                <div class="widget-chart text-center">
    							<div id='container_graph4_bar' style="width:470px;height:256px;"></div>					
                </div>
              </div>
            </div>							 
					</div>
					
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran Klaim JP Tahun <?=$ls_tahun;?></b></h4> 
                <div class="widget-chart text-center">
    							<div id='container_graph4_pie' style="width:470px;height:257px;"></div>					
                </div>
              </div>
            </div>							 
					</div>									
				</div>
							
				<div class="col-lg-6">
          <div class="card-box">
						
						<div class="row">	 					
							<h4 class="m-t-0 header-title"><b>Data Bulanan Pembayaran Klaim JP <?=$ls_tahun;?></b></h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 mails table-actions-bar">
									<thead>
										<tr>
                      <th style="text-align:center">Bulan</th>
                      <th  style="text-align:right">Jumlah Kasus</th>
                      <th  style="text-align:right">Manfaat Dibayarkan</th>
                    </tr>
                  </thead>
									<tbody>
										<? //rincian pembayaran jpn per-bulan ----------------------
                    $DB->parse($sql_byr_perbln_jpn);
                    $DB->execute();							              					
                    $i=0;		
										$ln_tot_kasus_jpn  =0;
										$ln_tot_pembayaran_jpn =0;
										for($i=0;$i<ExtendedFunction::count($resultArray->JPN_BLN);$i++)
                    {
                      ?>
											<tr>							
                        <td style="text-align:center">
													<?=$resultArray->JPN_BLN[$i]->KET_BULAN;?>
												</td>
                        <td style="text-align:right"><?=number_format((float)$resultArray->JPN_BLN[$i]->JML_KASUS,0,".",",");?></td>  									
                        <td style="text-align:right;"><?=number_format((float)$resultArray->JPN_BLN[$i]->NOM_PEMBAYARAN,2,".",",");?></td>						
                      </tr>
                      <?							    							
                      //$i++;//iterasi i
											$ln_tot_kasus_jpn  += (float)$resultArray->JPN_BLN[$i]->JML_KASUS;
											$ln_tot_pembayaran_jpn += (float)$resultArray->JPN_BLN[$i]->NOM_PEMBAYARAN;
                    }	//end while
										
                    if ($i == 0) {
                      echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
                      echo '<td colspan="3" style="text-align:center">-- Belum Ada Pembayaran Klaim JP --</td>';
                      echo '</tr>';
                    }	
                    ?>	 
									</tbody>
									<tr>
                    <th style="text-align:right"><i>Total</i></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_kasus_jpn,0,".",",");?></th>
                    <th  style="text-align:right"><?=number_format((float)$ln_tot_pembayaran_jpn,2,".",",");?></th>
                  </tr>		 
								</table>	 
							</div>	
						</div><!-- end row -->
					</div>
				</div>
			</div>	
			<!-- end row -->	
			<!--end row pembayaran klaim jp per bulan ------------------------------>
			
			<hr>
			
			<!--end row pembayaran klaim jp per bulan ------------------------------>
			<div class="row">
				<div class="col-lg-8">
				</div>	
				<div class="col-lg-4" style="text-align:right">
					Last Updated on <?=$ld_last_update;?>	 
				</div> 
			</div>
										 
		</div> 
		<!-- end container --> 	 
	</div> 
	<!-- end content -->
</div>

<!-- jQuery  -->
<script src="../../adminx/assets/js/jquery.min.js"></script>
<script src="../../adminx/assets/js/popper.min.js"></script><!-- Popper for Bootstrap -->
<script src="../../adminx/assets/js/bootstrap.min.js"></script>
<!-- <script src="../../adminx/assets/js/waves.js"></script> -->
<script src="../../adminx/assets/js/jquery.slimscroll.js"></script>
<script src="../../adminx/assets/js/jquery.scrollTo.min.js"></script>
<script src="../../adminx/plugins/moment/moment.js"></script>
<script src="../../adminx/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../adminx/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../../adminx/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="../../adminx/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../adminx/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../adminx/plugins/select2/js/select2.min.js" type="text/javascript"></script>
<script src="../../adminx/plugins/bootstrap-select/js/bootstrap-select.js" type="text/javascript"></script>

<script src="../../adminx/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Counter js  -->
<script src="../../adminx/plugins/waypoints/jquery.waypoints.min.js"></script>
<script src="../../adminx/plugins/counterup/jquery.counterup.min.js"></script>

<!--C3 Chart-->
<script type="text/javascript" src="../../adminx/plugins/d3/d3.min.js"></script>
<script type="text/javascript" src="../../adminx/plugins/c3/c3.min.js"></script>

<!--Echart Chart-->
<script src="../../adminx/plugins/echart/echarts-all.js"></script>

<!-- Chart JS -->
<script src="../../adminx/plugins/chart.js/chart.min.js"></script>
<script src="../../adminx/assets/pages/jquery.chartjs.init.js"></script>
				
<!-- Dashboard init -->
<!-- <script src="../../adminx/assets/pages/jquery.dashboard.js"></script> -->
<!-- App js -->
<script src="../../adminx/assets/js/jquery.core.js"></script>
<script src="../../adminx/assets/js/jquery.app.js"></script>
<script type="text/javascript">
  jQuery('.input-datepicker').datepicker({
            autoclose: true,
            todayHighlight: true
          });
</script>
<?
include "../../includes/footer_app_nosql.php";
?>
