<?php
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

if(isset($_GET['getClientId'])){  
	// validasi kode_kantor ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_kantor")
	{			
		$ls_kode_kantor	= strtoupper($_GET['c_kode_kantor']);	
		$ls_kd_prg			= strtoupper($_GET['c_kd_prg']);
		
    $qry = "select distinct kode_buku, (select nama_rekening from sijstk.ms_rekening where kode_kantor = a.kode_kantor and kode_buku = a.kode_buku and rownum = 1) nama_rekening ". 
           "from sijstk.ms_rekening a ".
           "where kode_kantor in ".
           "( ".
           "    select kode_kantor from sijstk.ms_kantor ".
           "    start with kode_kantor = '$ls_kode_kantor' ".
           "    connect by prior kode_kantor = kode_kantor_induk ".
           ") and kd_prg like nvl('$ls_kd_prg','%') ".
           "union all ".
           "select '00000' kode_buku, 'Memorial' nama_rekening from dual ".
           "order by kode_buku";
    $DB->parse($qry);
    $DB->execute();
    $i = 1;
    while($data = $DB->nextrow())
    {
      $s_val=$data["KODE_BUKU"];
      $s_teks = $data["KODE_BUKU"]." - ".$data["NAMA_REKENING"];
      echo "formObj.kode_buku.options[$i] = new Option('$s_teks','$s_val');";
      $i++;
    }												
	}	
	// end validasi kode_kantor --------------------------------------------------	

	// validasi kd_prg ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kd_prg")
	{			
		$ls_kode_kantor	= strtoupper($_GET['c_kode_kantor']);	
		$ls_kd_prg			= strtoupper($_GET['c_kd_prg']);	
		
    $qry = "select distinct kode_buku, (select nama_rekening from sijstk.ms_rekening where kode_kantor = a.kode_kantor and kode_buku = a.kode_buku and rownum = 1) nama_rekening ". 
           "from sijstk.ms_rekening a ".
           "where kode_kantor in ".
           "( ".
           "    select kode_kantor from sijstk.ms_kantor ".
           "    start with kode_kantor = '$ls_kode_kantor' ".
           "    connect by prior kode_kantor = kode_kantor_induk ".
           ") and kd_prg like nvl('$ls_kd_prg','%') ".
           "union all ".
           "select '00000' kode_buku, 'Memorial' nama_rekening from dual ".
           "order by kode_buku";
    $DB->parse($qry);
    $DB->execute();
    $i = 1;
    while($data = $DB->nextrow())
    {
      $s_val=$data["KODE_BUKU"];
      $s_teks = $data["KODE_BUKU"]." - ".$data["NAMA_REKENING"];
      echo "formObj.kode_buku.options[$i] = new Option('$s_teks','$s_val');";
      $i++;
    }												
	}	
	// end validasi kd_prg -------------------------------------------------------																	
}
?>		