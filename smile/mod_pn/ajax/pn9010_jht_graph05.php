<?php
$pagetype = "report";
$gs_pagetitle = "Dashboard Pelayanan - Pembayaran Klaim JHT Nasional Berdasarkan Usia";
require_once "../../includes/header_app.php"; 
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$DB3 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_user 	= $_SESSION["USER"];

$ls_sender 				= !isset($_POST['sender']) ? $_GET['sender'] : $_POST['sender'];
$ls_sender_mid 		= !isset($_POST['sender_mid']) ? $_GET['sender_mid'] : $_POST['sender_mid'];
$ls_kode_segmen 	= !isset($_POST['kode_segmen']) ? $_GET['kode_segmen'] : $_POST['kode_segmen'];
$ls_kode_kantor 	= !isset($_POST['kode_kantor']) ? $_GET['kode_kantor'] : $_POST['kode_kantor'];
$ld_tgl 					= !isset($_POST['tgl']) ? $_GET['tgl'] : $_POST['tgl'];
$ld_tgl_mmddyyyy  = !isset($_POST['tgl_mmddyyyy']) ? $_GET['tgl_mmddyyyy'] : $_POST['tgl_mmddyyyy'];
$ls_jenis_grafik  = !isset($_POST['jenis_grafik']) ? $_GET['jenis_grafik'] : $_POST['jenis_grafik'];

if ($ls_jenis_grafik=="")
{
 	$ls_jenis_grafik = "graph05"; 
}

//graph01 : Pembayaran Klaim JHT Per Kantor Wilayah
//graph02 : Pembayaran Klaim JHT Per Sebab Klaim
//graph03 : Pembayaran Klaim JHT Berdasarkan Masa Kepesertaan
//graph04 : Pembayaran Klaim JHT Berdasarkan Usia
//graph05 : Pembayaran Klaim JHT Berdasarkan Jenis Kelamin
//graph21 : Tingkat Pengembangan JHT-JP
														
if ($ls_jenis_grafik=="graph01")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_nasional.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";	
}elseif ($ls_jenis_grafik=="graph02")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph02.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";		
}elseif ($ls_jenis_grafik=="graph03")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph03.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";	
}elseif ($ls_jenis_grafik=="graph04")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph04.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";
}elseif ($ls_jenis_grafik=="graph06")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph06.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";
}elseif ($ls_jenis_grafik=="graph07")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph07.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";
}elseif ($ls_jenis_grafik=="graph08")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph08.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";	
}elseif ($ls_jenis_grafik=="graph09")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph09.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";			
}elseif ($ls_jenis_grafik=="graph21")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph21.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";	
}elseif ($ls_jenis_grafik=="graph22")
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../ajax/pn9010_jht_graph22.php?task=view&kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=pn9010.php&sender_mid=$mid');";
  echo "</script>";	
}

$ls_nama_segmen = "";
if ($ls_kode_segmen!="")
{
  $sql = "select 'SEGMEN '||nama_segmen nama_segmen from kn.kn_kode_segmen where kode_segmen='$ls_kode_segmen' ";		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_nama_segmen  = $row["NAMA_SEGMEN"];
}

$ls_nama_kantor = "";
if ($ls_kode_kantor!="")
{
  $sql = "select kode_tipe, decode('$ls_kode_kantor','0','NASIONAL',replace(nama_kantor,'GROUP','')) nama_kantor from ms.ms_kantor where kode_kantor ='$ls_kode_kantor' ";		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_nama_kantor  = $row["NAMA_KANTOR"];
	$ls_tipe_kantor  = $row["KODE_TIPE"];
}

if ($ld_tgl!="")
{
	$sql = "select to_char(to_date('$ld_tgl','dd/mm/yyyy'),'DD')||' '||(select upper(nama_periode) from lk.gl_kode_periode where kode_periode = to_char(to_date('$ld_tgl','dd/mm/yyyy'),'mm'))||' '||to_char(to_date('$ld_tgl','dd/mm/yyyy'),'YYYY') ket_tgl, ".
					"				to_char(to_date('$ld_tgl','dd/mm/yyyy'),'yyyy') tahun ".
					"from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
	$ls_kettgl  = $row["KET_TGL"];
	$ls_tahun  = $row["TAHUN"];
}

//hitung total pembayaran klaim jht berdasarkan jenis kelamin ------------------
//get data from WS -----------------------------------------------------------
/*
global $wsIp;
$ipDev  = $wsIp."/JSPN9010/GetDataJHTGraph05";
$url    = $ipDev;
$chId   = 'CORE';

// set HTTP header -----------------------------------------------------------
$headers = array(
  'Content-Type'=> 'application/json',
'Accept'=> 'application/json',
	'X-Forwarded-For'=> $ipfwd,
);

// set POST params -----------------------------------------------------------
$data = array(
    'chId'				 => $chId,
    'reqId'				 => $ls_user,
    'KODE_KANTOR'	 => $ls_kode_kantor,    
    'P_TGL'		 		 => $ld_tgl,  
    'KODE_SEGMEN'	 => $ls_kode_segmen
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
//var_dump($result);
$resultArray = json_decode(utf8_encode($result));

$ln_kasus_laki2 			= $resultArray->JHT_GRAPH1->KASUS_LAKI2;
//$ln_kasus_perempuan 	= $resultArray->JHT_GRAPH1->KASUS_PEREMPUAN;
//$ln_nom_byr_laki2 		= $resultArray->JHT_GRAPH1->NOM_BYR_LAKI2;
//$ln_nom_byr_perempuan = $resultArray->JHT_GRAPH1->NOM_BYR_PEREMPUAN;	

echo "n_kasus_laki2=".$ln_kasus_laki2;
echo "n_kasus_perempuan=".$ln_kasus_perempuan;
echo "n_nom_byr_laki2=".$ln_nom_byr_laki2;
echo "n_nom_byr_perempuan=".$ln_nom_byr_perempuan;
*/


$sql = "select 
            kolom_01 kd_prg, 
            ltrim(rtrim(to_char(to_number(kolom_02),'999,999,999,999,990'))) kasus_laki2, 
            ltrim(rtrim(to_char(to_number(kolom_03),'999,999,999,999,990'))) kasus_perempuan,
            ltrim(rtrim(to_char(to_number(kolom_04),'999,999,999,999,990.00'))) nom_byr_laki2, 
            ltrim(rtrim(to_char(to_number(kolom_05),'999,999,999,999,990.00'))) nom_byr_perempuan
        from table
        (
            PN.P_PN_REPORT.F_PN9002(
                'DSH_TOTBYRKLAIM_JHT_BYJNSKELAMIN',
                '$ls_kode_kantor', 
								'$ls_kode_segmen', 
								trunc(to_date('$ld_tgl','dd/mm/yyyy'),'yyyy'), 
								to_date('$ld_tgl','dd/mm/yyyy'), 
								'$username'
            )
        )";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ln_kasus_laki2 			= $row["KASUS_LAKI2"];
$ln_kasus_perempuan 	= $row["KASUS_PEREMPUAN"];
$ln_nom_byr_laki2 		= $row["NOM_BYR_LAKI2"];
$ln_nom_byr_perempuan = $row["NOM_BYR_PEREMPUAN"];	
		 
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

<script type="text/javascript">
  function fl_js_backToHome() 
  {																						
    <?php	
    	echo "window.location.replace('../form/$ls_sender?kode_kantor=$ls_kode_kantor&kode_segmen=$ls_kode_segmen&tgl=$ld_tgl&tgl_mmddyyyy=$ld_tgl_mmddyyyy&sender=$ls_sender&mid=$ls_sender_mid');";		
    ?>	
  }	
</script>	

<?
//1. grafik pembayaran klaim jht jenis kelamin laki2 berdasarkan kelompok usia ------------------------------------
$ls_CategoryList = "";
$ls_link_call_page2 = "x.php?";
$sql_perkanwil_bynom_laki2 = "select 
                                  kolom_01 kd_prg, 
                                  kolom_02 kode_kelompok_usia,
                                  kolom_03 nama_kelompok_usia, 
                                  kolom_04 ket_kelompok_usia,
                                  to_number(kolom_05) jml_kasus, 
                                  to_number(kolom_06) nom_pembayaran
                              from table
                              (
                                  PN.P_PN_REPORT.F_PN9002(
                                      'DSH_TOTBYRKLAIM_JHT_BYUSIA_JNSKELAMIN', 
                                      '$ls_kode_kantor', 
                                      '$ls_kode_segmen',
                                      trunc(to_date('$ld_tgl','dd/mm/yyyy'),'yyyy'), 
                                      to_date('$ld_tgl','dd/mm/yyyy'), 
                                      '$username',
																			null,
																			'L'
                                  )
                              )";				 
$DB->parse($sql_perkanwil_bynom_laki2);
$DB->execute();

$data1_param_rataan="";
$data1_param_rataan .= "{ name: 'Manfaat Dibayarkan', type: 'column',data: [";
$data1_param2_rataan = "";
$data1_x_axis_rataan = "";

$data2_param_rataan="";
$data2_param_rataan .= "{ name: 'Jml Kasus', type: 'spline',yAxis: 1, data: [";
$data2_param2_rataan = "";
$data2_x_axis_rataan = "";

$ls_graph_pie_val_temp .= "";
$ls_graph_pie_val = "";

while ($row = $DB->nextrow()) 
{
  //grafik bar -----------------------------------------------------------------
  $val1_x_asis_rataan = $row["KODE_KELOMPOK_USIA"];
  $val1_x_rataan = $row["KET_KELOMPOK_USIA"];
  $val1_y_rataan = $row["NOM_PEMBAYARAN"];      				  
  
  $data1_param2_rataan .= "['{$val1_x_rataan}', {$val1_y_rataan}],";
  $data1_x_axis_rataan .= "['{$val1_x_rataan}'],";
	
  $val2_x_asis_rataan = $row["KODE_KELOMPOK_USIA"];
  $val2_x_rataan = $row["KET_KELOMPOK_USIA"];
  $val2_y_rataan = $row["JML_KASUS"];      				  
  
  $data2_param2_rataan .= "['{$val2_x_rataan}', {$val2_y_rataan}],";
  $data2_x_axis_rataan .= "['{$val2_x_rataan}'],";
	
	//grafik pie -----------------------------------------------------------------
	$ls_graph_pie_val_x1 = $row["KODE_KELOMPOK_USIA"];
  $ls_graph_pie_val_x2 = $row["KET_KELOMPOK_USIA"];
  $ln_graph_pie_val_x3 = $row["NOM_PEMBAYARAN"];
  $ls_graph_pie_val_temp .= "{ name: '{$ls_graph_pie_val_x2}', y: {$ln_graph_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&kode_kelompok_usia={$ls_graph_pie_val_x1}' },";

}
$ls_graph_pie_val_temp = substr($ls_graph_pie_val_temp, 0, -1);
$ls_graph_pie_val = $ls_graph_pie_val.$ls_graph_pie_val_temp.",";
$ls_graph_pie_display = substr($ls_graph_pie_val, 0, -1);

//grafik nominal dan jumlah kasus utk peserta laki -----------------------------
$data1_param2_rataan = substr($data1_param2_rataan, 0, -1);
$data1_param_rataan = $data1_param_rataan.$data1_param2_rataan."] },";

$data2_param2_rataan = substr($data2_param2_rataan, 0, -1);
$data2_param_rataan = $data2_param_rataan.$data2_param2_rataan."] },";

$data_param_rataan  = $data1_param_rataan.$data2_param_rataan;
$data_x_axis_rataan = $data2_x_axis_rataan;
		
$data_param_rataan = substr($data_param_rataan, 0, -1);	
$data_x_axis_rataan = substr($data_x_axis_rataan, 0, -1);		
?>
<script type="text/javascript">
	var chart1_bar; // globally available
      $(document).ready(function() {
        chart1_bar = new Highcharts.Chart({
          chart: {
            renderTo: 'container_graph1_bar_laki2',
            zoomType: 'xy'
          },  
          title: {
            text: ' ',
            style: {
              color: Highcharts.getOptions().colors[1],
              fontSize:'15px'
            }						
          },
					tooltip: {
            	pointFormat: '{series.name}: <b>{point.y}</b>'
            },
          xAxis: {
          	categories: [<?=$data_x_axis_rataan;?>],
						labels: {
                skew3d: true,
  							rotation: -45,
                style: {
                    fontSize: '8px'
                }
            }
          },
          yAxis: [
						{ // Primary yAxis
              labels: {
                text: 'Rupiah',
								formatter: function (){return (this.value/1000000000)+" Milyar"},
                style: {
                	color: Highcharts.getOptions().colors[0]
                }
              },
              title: {
                text: 'Rupiah',
                style: {
                	color: Highcharts.getOptions().colors[0]
                }
              }
            }, 
						{ // Secondary yAxis
              title: {
                text: 'Kasus',
                style: {
                	color: Highcharts.getOptions().colors[1]
                }
              },
              labels: {
                text: 'Kasus',
                style: {
                	color: Highcharts.getOptions().colors[1]
                }
              },
              opposite: true
          	}
					],				 
          legend: {
            enabled: true,
            layout: 'vertical',
            align: 'center',
            x: 60,
            verticalAlign: 'center',
            y: 2,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
          },							 
          series:            
          [ <?=$data_param_rataan;?> ]
        });
      }); 
	
	var chart1_pie; // grafik 1 - pie - pembayaran klaim jht per kanwil ----------
  $(document).ready(
  	function() 
  	{
      chart1_pie = new Highcharts.Chart(
			{
        chart: {
						renderTo: 'container_graph1_pie_laki2',	 
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
          data: [<?=$ls_graph_pie_display?>]
        }]
      });
    }
	);
</script>		
<!-- end 1. grafik pembayaran klaim jht jenis kelamin laki-laki berdasarkan kelompok usia ---------------------------->

<?
//1. grafik pembayaran klaim jht jenis kelamin perempuan berdasarkan kelompok usia ------------------------------------
$ls_CategoryList = "";
$ls_link_call_page2 = "x.php?";
$sql_perkanwil_bynom_perempuan = "select 
                                      kolom_01 kd_prg, 
                                      kolom_02 kode_kelompok_usia,
                                      kolom_03 nama_kelompok_usia, 
                                      kolom_04 ket_kelompok_usia,
                                      to_number(kolom_05) jml_kasus, 
                                      to_number(kolom_06) nom_pembayaran
                                  from table
                                  (
                                      PN.P_PN_REPORT.F_PN9002(
                                          'DSH_TOTBYRKLAIM_JHT_BYUSIA_JNSKELAMIN', 
                                          '$ls_kode_kantor', 
                                          '$ls_kode_segmen',
                                          trunc(to_date('$ld_tgl','dd/mm/yyyy'),'yyyy'), 
                                          to_date('$ld_tgl','dd/mm/yyyy'), 
                                          '$username',
    																			null,
    																			'P'
                                      )
                                  )";				 
$DB->parse($sql_perkanwil_bynom_perempuan);
$DB->execute();

$datap1_param_rataan="";
$datap1_param_rataan .= "{ name: 'Manfaat Dibayarkan', type: 'column',data: [";
$datap1_param2_rataan = "";
$datap1_x_axis_rataan = "";

$datap2_param_rataan="";
$datap2_param_rataan .= "{ name: 'Jml Kasus', type: 'spline',yAxis: 1, data: [";
$datap2_param2_rataan = "";
$datap2_x_axis_rataan = "";

$ls_pr_graph_pie_val_temp .= "";
$ls_pr_graph_pie_val = "";
while ($row = $DB->nextrow()) 
{
  //grafik bar -----------------------------------------------------------------
  $valp1_x_asis_rataan = $row["KODE_KELOMPOK_USIA"];
  $valp1_x_rataan = $row["KET_KELOMPOK_USIA"];
  $valp1_y_rataan = $row["NOM_PEMBAYARAN"];      				  
  
  $datap1_param2_rataan .= "['{$valp1_x_rataan}', {$valp1_y_rataan}],";
  $datap1_x_axis_rataan .= "['{$valp1_x_rataan}'],";
	
  $valp2_x_asis_rataan = $row["KODE_KELOMPOK_USIA"];
  $valp2_x_rataan = $row["KET_KELOMPOK_USIA"];
  $valp2_y_rataan = $row["JML_KASUS"];      				  
  
  $datap2_param2_rataan .= "['{$valp2_x_rataan}', {$valp2_y_rataan}],";
  $datap2_x_axis_rataan .= "['{$valp2_x_rataan}'],";
	
	//grafik pie -----------------------------------------------------------------
	$ls_pr_graph_pie_val_x1 = $row["KODE_KELOMPOK_USIA"];
  $ls_pr_graph_pie_val_x2 = $row["KET_KELOMPOK_USIA"];
  $ln_pr_graph_pie_val_x3 = $row["NOM_PEMBAYARAN"];
  $ls_pr_graph_pie_val_temp .= "{ name: '{$ls_pr_graph_pie_val_x2}', y: {$ln_pr_graph_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&kode_kelompok_usia={$ls_pr_graph_pie_val_x1}&jenis_kelamin=p' },";
}
$ls_pr_graph_pie_val_temp = substr($ls_pr_graph_pie_val_temp, 0, -1);
$ls_pr_graph_pie_val = $ls_pr_graph_pie_val.$ls_pr_graph_pie_val_temp.",";
$ls_pr_graph_pie_display = substr($ls_pr_graph_pie_val, 0, -1);

//grafik nominal dan jumlah kasus utk peserta laki -----------------------------
$datap1_param2_rataan = substr($datap1_param2_rataan, 0, -1);
$datap1_param_rataan = $datap1_param_rataan.$datap1_param2_rataan."] },";

$datap2_param2_rataan = substr($datap2_param2_rataan, 0, -1);
$datap2_param_rataan = $datap2_param_rataan.$datap2_param2_rataan."] },";

$datap_param_rataan  = $datap1_param_rataan.$datap2_param_rataan;
$datap_x_axis_rataan = $datap2_x_axis_rataan;
		
$datap_param_rataan = substr($datap_param_rataan, 0, -1);	
$datap_x_axis_rataan = substr($datap_x_axis_rataan, 0, -1);
?>
<script type="text/javascript">
	var chart1_bar_pr; // globally available
      $(document).ready(function() {
        chart1_bar_pr = new Highcharts.Chart({
          chart: {
            renderTo: 'container_graph1_bar_perempuan',
            zoomType: 'xy'
          },  
          title: {
            text: ' ',
            style: {
              color: Highcharts.getOptions().colors[1],
              fontSize:'15px'
            }						
          },
					tooltip: {
            	pointFormat: '{series.name}: <b>{point.y}</b>'
            },
          xAxis: {
          	categories: [<?=$datap_x_axis_rataan;?>],
						labels: {
                skew3d: true,
  							rotation: -45,
                style: {
                    fontSize: '8px'
                }
            }
          },
          yAxis: [
						{ // Primary yAxis
              labels: {
                text: 'Rupiah',
								formatter: function (){return (this.value/1000000000)+" Milyar"},
                style: {
                	color: Highcharts.getOptions().colors[0]
                }
              },
              title: {
                text: 'Rupiah',
                style: {
                	color: Highcharts.getOptions().colors[0]
                }
              }
            }, 
						{ // Secondary yAxis
              title: {
                text: 'Kasus',
                style: {
                	color: Highcharts.getOptions().colors[1]
                }
              },
              labels: {
                text: 'Kasus',
                style: {
                	color: Highcharts.getOptions().colors[1]
                }
              },
              opposite: true
          	}
					],				 
          legend: {
            enabled: true,
            layout: 'vertical',
            align: 'center',
            x: 60,
            verticalAlign: 'center',
            y: 2,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
          },							 
          series:            
          [ <?=$datap_param_rataan;?> ]
        });
      }); 
			
	var chart1_pie_pr; // grafik 1 - pie - pembayaran klaim jht kpd peserta perempuan berdasarkan usia ----------
  $(document).ready(
  	function() 
  	{
      chart1_pie_pr = new Highcharts.Chart(
			{
        chart: {
						renderTo: 'container_graph1_pie_perempuan',	 
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
          data: [<?=$ls_pr_graph_pie_display?>]
        }]
      });
    }
	);
</script>		
<!-- end 1. grafik pembayaran klaim jht kpd peserta perempuan berdasarkan usia ---------------------------->
								
<div class="content-page">
	<input type="hidden" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>">
	<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">
	<input type="hidden" id="tgl" name="tgl" value="<?=$ld_tgl;?>">
	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
	<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
	<input type="hidden" id="tgl_mmddyyyy" name="tgl_mmddyyyy" value="<?=$ld_tgl_mmddyyyy;?>">
	
	<!-- start content -->
	<div class="content">
		<div class="container">

			<div class="row">
				<div class="col-11">
					<div class="page-title-box">
            <h5 class="page-title float-center">DASHBOARD PEMBAYARAN KLAIM JHT <?=$ls_nama_kantor;?> <?=$ls_nama_segmen;?> S/D <?=$ls_kettgl;?></h5>
          </div>	 
				</div>
				<div class="col-1">
					<div class="page-title-box">
            <a href="#" onClick="fl_js_backToHome();"><img src="../../images/house.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Home</a>
          </div>	 
				</div>	
			</div>
			<!-- end row -->
			
			<div class="row">
				<div class="col-6">
				</div>
				 
        <div class="col-6">
          <div class="form-group m-b-20">
            <!--<label>Kantor :</label>-->
            <div>
              <select class="selectpicker show-tick" id="jenis_grafik" name="jenis_grafik" data-style="btn-dafault" onchange="this.form.submit()">
              <option value="">-- detil dashboard --</option>
              <?
              switch($ls_jenis_grafik)
              {
              case 'graph01' : $graph01="selected"; break;
              case 'graph02' : $graph02="selected"; break;
              case 'graph03' : $graph03="selected"; break;
              case 'graph04' : $graph04="selected"; break;
              case 'graph05' : $graph05="selected"; break;
              case 'graph08' : $graph08="selected"; break; 
              case 'graph09' : $graph09="selected"; break;
              case 'graph06' : $graph06="selected"; break;
              case 'graph07' : $graph07="selected"; break; 
              case 'graph21' : $graph21="selected"; break;
              case 'graph22' : $graph22="selected"; break; 										
              }
              ?>
              <option value="graph01" <?=$graph01;?>>
              <?
              if ($ls_tipe_kantor=="0")
              {
              ?>
              Pembayaran Klaim JHT Per Kantor Wilayah
              <? 
              }else
              {
              ?>
              Pembayaran Klaim JHT Per Kantor Cabang/KCP
              <? 
              }
              ?>				
              </option>
              <option value="graph02" <?=$graph02;?>>Pembayaran Klaim JHT Berdasarkan Sebab Klaim</option>	
              <option value="graph03" <?=$graph03;?>>Pembayaran Klaim JHT Berdasarkan Masa Kepesertaan</option>
              <option value="graph04" <?=$graph04;?>>Pembayaran Klaim JHT Berdasarkan Usia</option>
              <option value="graph05" <?=$graph05;?>>Pembayaran Klaim JHT Berdasarkan Jenis Kelamin</option>
              <option value="graph08" <?=$graph08;?>>Pembayaran Klaim JHT Berdasarkan Nominal</option>
              <option value="graph09" <?=$graph09;?>>Pembayaran Klaim JHT Berdasarkan Upah</option>
              <option value="graph06" <?=$graph06;?>>JHT Kurang Bayar</option>	
              <option value="graph07" <?=$graph07;?>>JHT Siap Bayar</option>
              <option value="graphx2" <?=$graphx2;?>>----------------------------------------------------</option>							
              <option value="graph21" <?=$graph21;?>>Tingkat Pengembangan Bulanan JHT-JP</option>
              <option value="graph22" <?=$graph22;?>>Tingkat Pengembangan Tahunan JHT-JP</option>
              </select>
            </div>
          </div>
        </div>				
			</div>
			<!-- end row -->
			
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<!-- <i class="mdi mdi-cash-multiple widget-two-icon"></i> -->
            <img src="../../images/male.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">PEMBAYARAN KLAIM JHT KEPADA PESERTA LAKI-LAKI</p>
							<h3 class="">
								Rp. <span data-plugin="counterup"><?=$ln_nom_byr_laki2;?></span>
								<p class="m-0"><?=$ln_kasus_laki2;?> kasus</p>
							</h3> 
						</div>	 
					</div>	 
				</div><!-- end col -->
				
				<div class="col-lg-6 col-md-6">
					<div class="card-box widget-box-two widget-two-custom">
						<!-- <i class="mdi mdi-cash-multiple widget-two-icon"></i> -->
            <img src="../../images/female.png" alt="logo" class="widget-two-icon">
						<div class="wigdet-two-content">
							<p class="m-0 text-uppercase font-bold font-secondary text-overflow" title="Statistics">PEMBAYARAN KLAIM JHT KEPADA PESERTA PEREMPUAN</p>
							<h3 class="">Rp. <span data-plugin="counterup"><?=$ln_nom_byr_perempuan;?></span></h3>
							<p class="m-0"><?=$ln_kasus_perempuan;?> kasus</p>
						</div>	 
					</div>	 
				</div><!-- end col -->
				
			</div>
			<!-- end row -->
			
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<!-- laki2 ---------------------------------------------------------->
					<div class="col-lg-12">
            <div class="row">
      				<div class="col-lg-12">
                <div class="card-box">
      						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran JHT - Laki-Laki Berdasarkan Usia</b></h4>
                  <div class="widget-chart text-center">
      							<div id='container_graph1_bar_laki2' style="width:420px;height:343px;"></div>					
                  </div>
                </div>
              </div>							 
  					</div><!-- end row -->
  					
            <div class="row">
      				<div class="col-lg-12">
                <div class="card-box">
      						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran JHT - Laki-Laki Berdasarkan Usia</b></h4> 
                  <div class="widget-chart text-center">
      							<div id='container_graph1_pie_laki2' style="width:420px;height:343px;"></div>					
                  </div>
                </div>
              </div>							 
  					</div><!-- end row -->
						
						<div class="row">
      				<div class="col-lg-12">
                <div class="card-box">						 
    							<h4 class="m-t-0 header-title"><b>Data Pembayaran JHT - Laki-Laki Berdasarkan Usia</b></h4>
    							<div class="table-responsive">
    								<table class="table table-hover m-0 mails table-actions-bar">
    									<thead>
    										<tr>
                          <th style="text-align:center">Usia</th>
                          <th  style="text-align:center">Kasus</th>
                          <th  style="text-align:center">Dibayarkan</th>
                        </tr>
                      </thead>
    									<tbody>
    										<? //rincian pembayaran jht laki2 berdasarkan usia ----------------------
                        $DB->parse($sql_perkanwil_bynom_laki2);
                        $DB->execute();							              					
                        $i=0;		
    										$ln_tot_kasus_jht  =0;
    										$ln_tot_pembayaran_jht =0;				
                        while ($row = $DB->nextrow())
                        {
                          ?>
    											<tr>							
                            <td style="text-align:center"><?=$row["KET_KELOMPOK_USIA"];?></td>
                            <td style="text-align:right"><?=number_format((float)$row["JML_KASUS"],0,".",",");?></td>  									
                            <td style="text-align:right;"><?=number_format((float)$row["NOM_PEMBAYARAN"],2,".",",");?></td>						
                          </tr>
                          <?							    							
                          $i++;//iterasi i
    											$ln_tot_kasus_jht  += $row["JML_KASUS"];
    											$ln_tot_pembayaran_jht += $row["NOM_PEMBAYARAN"];
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
								</div>
              </div>						 
  					</div><!-- end row -->
													 
					</div><!-- end col --> 
				</div><!-- end row -->	
				  
					
				<div class="col-lg-6 col-md-6">
					<!-- perempuan ------------------------------------------------------>
					<div class="col-lg-12">
            <div class="row">
      				<div class="col-lg-12">
                <div class="card-box">
      						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran JHT - Perempuan Berdasarkan Usia</b></h4>
                  <div class="widget-chart text-center">
      							<div id='container_graph1_bar_perempuan' style="width:420px;height:343px;"></div>					
                  </div>
                </div>
              </div>							 
  					</div><!-- end row -->
  					
            <div class="row">
      				<div class="col-lg-12">
                <div class="card-box">
      						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran JHT - Perempuan Berdasar Usia</b></h4> 
                  <div class="widget-chart text-center">
      							<div id='container_graph1_pie_perempuan' style="width:420px;height:343px;"></div>					
                  </div>
                </div>
              </div>							 
  					</div><!-- end row -->
						
						<div class="row">
      				<div class="col-lg-12">
                <div class="card-box">						 
    							<h4 class="m-t-0 header-title"><b>Data Pembayaran JHT - Perempuan Berdasarkan Usia</b></h4>
    							<div class="table-responsive">
    								<table class="table table-hover m-0 mails table-actions-bar">
    									<thead>
    										<tr>
                          <th style="text-align:center">Usia</th>
                          <th  style="text-align:center">Kasus</th>
                          <th  style="text-align:center">Dibayarkan</th>
                        </tr>
                      </thead>
    									<tbody>
    										<? //rincian pembayaran jht laki2 berdasarkan usia ----------------------
                        $DB->parse($sql_perkanwil_bynom_perempuan);
                        $DB->execute();							              					
                        $i=0;		
    										$ln_tot_kasus_jht  =0;
    										$ln_tot_pembayaran_jht =0;				
                        while ($row = $DB->nextrow())
                        {
                          ?>
    											<tr>							
                            <td style="text-align:center"><?=$row["KET_KELOMPOK_USIA"];?></td>
                            <td style="text-align:right"><?=number_format((float)$row["JML_KASUS"],0,".",",");?></td>  									
                            <td style="text-align:right;"><?=number_format((float)$row["NOM_PEMBAYARAN"],2,".",",");?></td>						
                          </tr>
                          <?							    							
                          $i++;//iterasi i
    											$ln_tot_kasus_jht  += $row["JML_KASUS"];
    											$ln_tot_pembayaran_jht += $row["NOM_PEMBAYARAN"];
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
								</div>
              </div>						 
  					</div><!-- end row -->										 
				</div><!-- end col -->	 
			</div><!-- end row -->
			
		</div><!-- end container -->
	</div><!-- end content -->	
</div>

<!-- jQuery  -->
<script src="../../adminx/assets/js/jquery.min.js"></script>
<script src="../../adminx/assets/js/popper.min.js"></script>
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
