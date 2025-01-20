<?php
header('Content-Type: application/json');
include('includes/connsql.php');
	function formatTgl($date){
		$show = explode('/', $date);
		$result = $show[2].'-'.$show[1].'-'.$show[0];
		return $result;	
	}
	if(isset($_REQUEST["kodekantor"])){
		$kode_kantor  		= $_REQUEST["kodekantor"];
		$tgl_trans_awal		= formatTgl($_REQUEST["startdate"]);
		$tgl_trans_akhir	= formatTgl($_REQUEST["enddate"]);
		$id_dokumen			= '%';
		$offset 			= $_REQUEST['start'];
		$limit 				= $_REQUEST['limit'];
		$con1 		=  $wslistmppa->execute(array('RequestInfo'=>
									array('RequestID'=>'0',
										  'RequestSource'=>$chId,
										  'RequestDate'=>date('Y-m-d'),
										  'RequestUser'=>$_SESSION["USER"]),
										  'Input' => array('PKantor'=>$kode_kantor,
														  'IdDokumen'=>$id_dokumen,
														  'FromTgl'=>$tgl_trans_awal,
														  'Totgl'=>$tgl_trans_akhir,
														  'FromBrs'=>$offset,
														  'ToBrs'=>$limit)));
		$getData 	= get_object_vars($con1);
		if($getData['Status']->Status == 'SUCCESS'){
			if(count($getData['Output']->MPPAList->MPPA) > 1){
				for ($i = 0; $i < count($getData['Output']->MPPAList->MPPA); $i++) {
					$arr[] = "{ iddokumen:'".$getData['Output']->MPPAList->MPPA[$i]->IdDokumen."', kantor:'".$getData['Output']->MPPAList->MPPA[$i]->KdKantor."', prog:'".$getData['Output']->MPPAList->MPPA[$i]->KdProduk."', tglajuan:'".$getData['Output']->MPPAList->MPPA[$i]->TglPencairan."', jenis:'".$getData['Output']->MPPAList->MPPA[$i]->KdPencairan."', mataanggaran:'', jmlajuan:'".$getData['Output']->MPPAList->MPPA[$i]->NomPencairan."', keterangan:'' }";
				}
			} else {
			$arr[] = "{ iddokumen:'".$getData['Output']->MPPAList->MPPA->IdDokumen."', kantor:'".$getData['Output']->MPPAList->MPPA->KdKantor."', prog:'".$getData['Output']->MPPAList->MPPA->KdProduk."', tglajuan:'".$getData['Output']->MPPAList->MPPA->TglPencairan."', jenis:'".$getData['Output']->MPPAList->MPPA->KdPencairan."', mataanggaran:'', jmlajuan:'".$getData['Output']->MPPAList->MPPA->NomPencairan."', keterangan:'' }";
			}
			echo "{ success: true, total: ".count($getData['Output']->MPPAList->MPPA).", rows: [" . join($arr, ', ') . "] }";
		} else {
			$getData['Status']->ErrorDescription;
			echo "{ success: true, total: 0, rows: [] }";
			//echo "{ success: false, errors:'".$getData['Status']->ErrorDescription."'}";
		}
		
	} else {
		echo "{ success: true, total: 0, rows: [] }";
	}
?>