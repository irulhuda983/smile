<?php
$pagetype = "report";
$gs_pagetitle = "Dashboard Pelayanan - Pembayaran Klaim JP Nasional Per Kantor Wilayah";
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
$ls_kode_sebab_klaim  = !isset($_POST['kode_sebab_klaim']) ? $_GET['kode_sebab_klaim'] : $_POST['kode_sebab_klaim'];
if ($ls_kode_sebab_klaim!="")
{
	$sql = "select nama_jenis_kasus from pn.pn_kode_jenis_kasus where kode_jenis_kasus = '$ls_kode_sebab_klaim' "; 		
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
	$ls_nama_sebab_klaim  = $row["NAMA_JENIS_KASUS"]; 
	$ls_keyword_sebab_klaim  = $row["NAMA_JENIS_KASUS"]; 
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
<script language="javascript">
    function NewWindow4(mypage,myname,w,h,scroll){
    		var openwin = window.parent.Ext.create('Ext.window.Window', {
    		title: myname,
    		collapsible: true,
    		animCollapse: true,
    		
    		maximizable: true,
    		width: w,
    		height: h,
    		minWidth: 600,
    		minHeight: 400,
    		layout: 'fit',
    		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    		dockedItems: [{
      			xtype: 'toolbar',
      			dock: 'bottom',
      			ui: 'footer',
      			items: [
      				{ 
      					xtype: 'button',
      					text: 'Tutup',
      					handler : function(){
      						openwin.close();
      					}
      				}
      			]
      		}]
    	});
    	openwin.show();
    }		
</script>	
<?
//1. grafik pembayaran klaim jht per kanwil per sebab klaim --------------------
//get data from WS -----------------------------------------------------------
global $wsIp;
$ipDev  = $wsIp."/JSPN9010/GetDataJPNGraph02";
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
    'KODE_SEGMEN'	 => $ls_kode_segmen,
		'KODE_SEBAB_KLAIM' => $ls_kode_sebab_klaim
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

$ls_CategoryList = "";
$ls_link_call_page2 = "x.php?";

$ls_graph1_bar_xAxisCategory1_temp = "[";
$ls_graph1_bar_val_temp = "[";
$ln_graph1_bar_val_tot  = 0;			
$ln_graph1_bar_val = 0;

$ls_graph_pie_val_temp .= "";
$ls_graph_pie_val = "";
for($i=0;$i<ExtendedFunction::count($resultArray->JPN_GRAPH02);$i++)
{
  //grafik bar -----------------------------------------------------------------
	$ln_graph1_bar_val = 0;
  $ln_graph1_bar_val = $resultArray->JPN_GRAPH02[$i]->NOM_PEMBAYARAN == "" ? "0" : $resultArray->JPN_GRAPH02[$i]->NOM_PEMBAYARAN;
  $ln_graph1_bar_val_tot = $ln_graph1_bar_val_tot + (float)$ln_graph1_bar_val;
  $ls_graph1_bar_xAxisCategory1_temp .= "'".$resultArray->JPN_GRAPH02[$i]->NAMA_KANTOR_SINGKAT."',";
  $ls_graph1_bar_val_temp .= $ln_graph1_bar_val.",";
	
	//grafik pie -----------------------------------------------------------------
	$ls_graph_pie_val_x1 = $resultArray->JPN_GRAPH02[$i]->KODE_KANTOR;
  $ls_graph_pie_val_x2 = $resultArray->JPN_GRAPH02[$i]->NAMA_KANTOR_SINGKAT;
  $ln_graph_pie_val_x3 = $resultArray->JPN_GRAPH02[$i]->NOM_PEMBAYARAN;
  $ls_graph_pie_val_temp .= "{ name: '{$ls_graph_pie_val_x2}', y: {$ln_graph_pie_val_x3}, url: '{$ls_link_call_page2}kode_segmen={$ls_kode_segmen}&tgl={$ld_tgl}&kode_kantor={$ls_graph_pie_val_x1}' },";
}
//var_dump($xAxisCategory1_temp);
$ls_graph1_bar_xAxisCategory1 = $ls_graph1_bar_xAxisCategory1_temp == "[" ? "[]" : substr($ls_graph1_bar_xAxisCategory1_temp, 0, -1)."]";
$ls_graph1_bar_val = $ls_graph1_bar_val_temp == "[" ? "[]" : substr($ls_graph1_bar_val_temp, 0, -1)."]";

$ls_graph_pie_val_temp = substr($ls_graph_pie_val_temp, 0, -1);
$ls_graph_pie_val = $ls_graph_pie_val.$ls_graph_pie_val_temp.",";
$ls_graph_pie_display = substr($ls_graph_pie_val, 0, -1);
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
          name: 'Total Pembayaran Klaim JP',
          data: [<?=$ls_graph_pie_display?>]
        }]
      });
    }
	);
</script>		
<!-- end 1. grafik pembayaran klaim jht per kanwil ---------------------------->
				
<div class="content-page">
	<input type="hidden" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>">
	<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">
	<input type="hidden" id="tgl" name="tgl" value="<?=$ld_tgl;?>">
	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
	<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
	<input type="hidden" id="tgl_mmddyyyy" name="tgl_mmddyyyy" value="<?=$ld_tgl_mmddyyyy;?>">
	<input type="hidden" id="kode_sebab_klaim" name="kode_sebab_klaim" value="<?=$ls_kode_sebab_klaim;?>">
	
	<!-- start content -->
	<div class="content">
		<div class="container">

			<div class="row">
				<div class="col-12">
					<div class="page-title-box">
            <h5 class="page-title float-center">DASHBOARD PEMBAYARAN KLAIM JP <?=$ls_nama_kantor;?> <?=$ls_nama_segmen;?> S/D <?=$ls_kettgl;?></h5>
          </div>	 
				</div>
			</div>
			<!-- end row -->

			<div class="row">
				<div class="col-12">
					<!--<div class="page-title-box">-->
            <h5 class="page-title float-center"><b>KATEGORI PENGAMBILAN JP : <?=$ls_nama_sebab_klaim;?></b></h5>
          <!--</div>-->	 
				</div>
			</div>
			<!-- end row -->
						
			<div class="row">
				<div class="col-lg-7">
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Pembayaran Klaim JP <?=$ls_keyword_sebab_klaim;?> Per Kantor Wilayah</b></h4>
                <div class="widget-chart text-center">
    							<div id='container_graph1_bar' style="width:560px;height:234px;"></div>					
                </div>
              </div>
            </div>							 
					</div>
					
          <div class="row">
    				<div class="col-lg-12">
              <div class="card-box">
    						<h4 class="header-title m-t-0 m-b-30"><b>Komposisi Pembayaran Klaim JP <?=$ls_keyword_sebab_klaim;?> Kantor Wilayah</b></h4> 
                <div class="widget-chart text-center">
    							<div id='container_graph1_pie' style="width:560px;height:234px;"></div>					
                </div>
              </div>
            </div>							 
					</div>									
				</div>
							
				<div class="col-lg-5">
          <div class="card-box">

						<div class="row">	 					
							<h4 class="m-t-0 header-title"><b>Data Pembayaran Klaim JP <?=$ls_keyword_sebab_klaim;?></b></h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 mails table-actions-bar">
									<thead>
										<tr>
                      <th style="text-align:center">
													<?
                          if ($ls_tipe_kantor=="0")
                          {
                            ?>
                            Kantor Wilayah
                            <? 
                          }else
                          {
                            ?>
                            Kantor Cabang/KCP
                            <? 
                          }
                          ?>
													
											</th>
                      <th  style="text-align:center">Kasus</th>
                      <th  style="text-align:center">Dibayarkan</th>
                    </tr>
                  </thead>
									<tbody>
										<? //rincian pembayaran jht per-bulan ----------------------						              					
                    $i=0;		
										$ln_tot_kasus_jht  =0;
										$ln_tot_pembayaran_jht =0;	
										for($i=0;$i<ExtendedFunction::count($resultArray->JPN_GRAPH02);$i++)			
                    {
                      ?>
											<tr>							
                        <td style="text-align:left">
													<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9010_jpn_graph02dtl.php?task=view&kode_kantor=<?=$resultArray->JPN_GRAPH02[$i]->KODE_KANTOR;?>&kode_segmen=<?=$ls_kode_segmen;?>&tgl=<?=$ld_tgl;?>&tgl_mmddyyyy=<?=$ld_tgl_mmddyyyy;?>&kode_sebab_klaim=<?=$ls_kode_sebab_klaim;?>&sender=pn9010.php&sender_mid=<?=$mid;?>','Drilldown',1250,680,'yes');" href="javascript:void(0);">
          									 <?=$resultArray->JPN_GRAPH02[$i]->NAMA_KANTOR_SINGKAT2;?>
          								</a>
												</td>
                        <td style="text-align:right"><?=number_format((float)$resultArray->JPN_GRAPH02[$i]->JML_KASUS,0,".",",");?></td>  									
                        <td style="text-align:right;"><?=number_format((float)$resultArray->JPN_GRAPH02[$i]->NOM_PEMBAYARAN,2,".",",");?></td>						
                      </tr>
                      <?							    							
                      //$i++;//iterasi i
											$ln_tot_kasus_jht  += (float)$resultArray->JPN_GRAPH02[$i]->JML_KASUS;
											$ln_tot_pembayaran_jht += (float)$resultArray->JPN_GRAPH02[$i]->NOM_PEMBAYARAN;
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
					
		</div>
		<!-- end container -->
	</div>
	<!-- end content -->	
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
