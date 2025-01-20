<?php
session_start();

require '../../classes/phpspreadsheet/vendor/autoload.php';
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Chart\Chart;
use \PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use \PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use \PhpOffice\PhpSpreadsheet\Chart\Title;
use \PhpOffice\PhpSpreadsheet\Chart\Legend;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Style\Fill;
use \PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;


require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$tipe = $_GET["tipe"];
$ls_search_by = $_GET["search_by"];
$ls_search_by2 = $_GET["search_by2"];
$ls_search_txt = $_GET["search_txt"];
$ls_keyword2a = $_GET["keyword2a"];
$ls_keyword2b = $_GET["keyword2b"];
$ls_keyword2c = $_GET["keyword2c"];
$ls_keyword2d = $_GET["keyword2d"];
$ls_search_tgl = $_GET["search_tgl"];
$ls_tglakhirdisplay = $_GET['tglakhirdisplay'];
$ls_tglawaldisplay = $_GET['tglawaldisplay'];
$ls_kode_kantor =$_GET['kode_kantor'];
  
$USER = $_SESSION["USER"];
$KD_KANTOR = $_SESSION['kdkantorrole'];

$condition = "";
if ($ls_search_txt != ""){
  if ($ls_search_by == "sc_kode_klaim"){
    $condition = " where kode_klaim like '%{$ls_search_txt}%' ";
  } else if ($ls_search_by == "sc_no_kpj"){
    $condition = " where KPJ like '%{$ls_search_txt}%' ";
  } else if ($ls_search_by == "sc_nama_tk"){
    $condition = " where nama_tk like '%{$ls_search_txt}%' ";
  } 
  // else if ($ls_search_by == "sc_kd_kc"){
  //   $condition = " where kode_kantor like '%{$ls_search_txt}%' ";
  // } else if ($ls_search_by == "sc_kd_kw"){
  //   $condition = " where kode_wilayah like '%{$ls_search_txt}%' ";
  // }
}
else{
   $condition = "WHERE 1=1 ";
}
$condition2 = "";
if ($ls_search_by2 != ""){
  if ($ls_search_by2 == "KODE_SEGMEN"){
    $condition2 = " and kode_segmen = '{$ls_keyword2c}' ";
  } else if ($ls_search_by2 == "KODE_TIPE_KLAIM"){
    $condition2 = " and kode_tipe_klaim = '{$ls_keyword2a}' ";
  } else if ($ls_search_by2 == "KODE_KONDISI_TERAKHIR"){
    $condition2 = " and kode_kondisi_terakhir = '{$ls_keyword2b}' ";
  } else if ($ls_search_by2 == "KODE_STATUS_TINDAK_LANJUT"){
    $condition2 = " and status_tindak_lanjut = '{$ls_keyword2d}' ";
  } 
  // else if ($ls_search_by == "sc_kd_kc"){
  //   $condition = " where kode_kantor like '%{$ls_search_txt}%' ";
  // } else if ($ls_search_by == "sc_kd_kw"){
  //   $condition = " where kode_wilayah like '%{$ls_search_txt}%' ";
  // }
}

if($ls_kode_kantor != '' || $ls_kode_kantor != null){
  $filter_kode_kantor = "and kode_kantor = '{$ls_kode_kantor}'
                      ";
}else{
  $filter_kode_kantor = "and kode_kantor in (select kode_kantor
                                             from ms.ms_kantor
                                             where aktif = 'Y'
                                                  and status_online = 'Y'
                                                  and kode_tipe not in ('1', '2')
                                                  start with kode_kantor = '{$KD_KANTOR}'
                                                  connect by prior kode_kantor = kode_kantor_induk)
                      ";
}



$ls_filter_tgl = " and tgl_kejadian >= TO_DATE ('{$ls_tglawaldisplay}','dd-mm-rrrr') and tgl_kejadian <= TO_DATE ( '{$ls_tglakhirdisplay}','dd-mm-rrrr')";


// var_dump($ls_tglawaldisplay);die();
 /*
 $filter_kode_kantor = "and kode_kantor in (select kode_kantor
                                               from ms.ms_kantor
                                               where aktif = 'Y'
                                                    and status_online = 'Y'
                                                    and kode_tipe not in ('1', '2')
                                                    start with kode_kantor = '{$KD_KANTOR}'
                                                    connect by prior kode_kantor = kode_kantor_induk)
                        ";
  */
  
  //$ls_filter_tgl = " and tgl_kejadian >= TO_DATE ('{$ls_tglawaldisplay}','dd-mm-rrrr') and tgl_kejadian <= TO_DATE ( '{$ls_tglakhirdisplay}','dd-mm-rrrr')";




if ( $tipe = "XLS") {
   // header_remove();
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=DATA_MONITORING_MANFAAT_BEASISWA_KANTOR_".$KD_KANTOR.".xls");
    header("Content-Transfer-Encoding: binary ");

    $objPHPSpreadsheet = new Spreadsheet();
    $objPHPSpreadsheet->getProperties()
      ->setCreator("New Core System")
      ->setLastModifiedBy("New Core System Team")
      ->setTitle("Office 2007 XLSX Test Document")
      ->setSubject("Office 2007 XLSX Test Document")
      ->setDescription("File document for Office 2007 XLSX, generated using PHP Class.")
      ->setKeywords("office 2007 openxml")
      ->setCategory("New Core System");
    $objPHPSpreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'MONITORING PENGECEKAN MANFAAT BEASISWA PP NOMOR 82 TAHUN 2019 BERDASARKAN TANGGAL BAYAR KLAIM INDUK')
      ->setCellValue('B1', '')
      ->setCellValue('C1', '')
      ->setCellValue('D1', '')
      ->setCellValue('E1', '')
      ->setCellValue('F1', '')
      ->setCellValue('G1', '')
      ->setCellValue('H1', '')
      ->setCellValue('I1', '')
      ->setCellValue('J1', '')
      ->setCellValue('K1', '')
      ->setCellValue('L1', '')
      ->setCellValue('M1', '')
      ->setCellValue('N1', '')
      ->setCellValue('O1', '')
      ->setCellValue('P1', '')
	  ->setCellValue('Q1', '')
	  ->setCellValue('R1', '')
      ->mergeCells('A1:O1');
    $objPHPSpreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A2', 'KODE WILAYAH')
      ->setCellValue('B2', 'KODE KANTOR CABANG')
      ->setCellValue('C2', 'NAMA KANTOR CABANG')
      ->setCellValue('D2', 'JENIS KLAIM')
      ->setCellValue('E2', 'SEGMEN KEPESERTAAN')
      ->setCellValue('F2', 'NAMA TK')
      ->setCellValue('G2', 'NOMOR KPJ')
      ->setCellValue('H2', 'NIK TK')
      ->setCellValue('I2', 'TANGGAL KEJADIAN')
      ->setCellValue('J2', 'KODE KLAIM INDUK')
      ->setCellValue('K2', 'TANGGAL BAYAR KLAIM')
      ->setCellValue('L2', 'NO. HP PENERIMA MANFAAT')
      ->setCellValue('M2', 'EMAIL PENERIMA MANFAAT')
      ->setCellValue('N2', 'HASIL PENGECEKAN BEASISWA')
      ->setCellValue('O2', 'JUMLAH ANAK PENERIMA BEASISWA')
      ->setCellValue('P2', 'STATUS TINDAK LANJUT')
	  ->setCellValue('Q2', 'TGL_TINDAK_LANJUT')
	  ->setCellValue('R2', 'KETERANGAN');


    $styleArrayHeader0 = array(
      'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'color' => array('rgb'=>'92d14f'),
      ),
      'font' => array(
        'bold' => true,
      ),
      'alignment' => array(
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        )
    );

    $styleArrayBorder = array(
      'borders' => array(
      'allborders' => array(
        'borderStyle' => Border::BORDER_THIN)
      )
    );

    $styleArrayHeader1 = array(
      'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'color' => array('rgb'=>'92d14f'),
      ),
      'font' => array(
        'bold' => false,
      )
    );

    $styleArrayHeader2 = array(
      'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'color' => array('rgb'=>'9bc1e6'),
      ),
      'font' => array(
        'bold' => true,
      )
    );
    

    $sql = "
           SELECT ROWNUM NO, Z.*
     FROM  
    (
         select 
          kode_kantor,
          (select nama_kantor from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_kantor,
           kode_wilayah,
          (select nama_wilayah from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_wilayah,
         substr(kode_tipe_klaim,1,3) jenis_klaim,
          kode_segmen, 
          nama_tk,
          kpj,
          nomor_identitas nik_tk,
          to_char(tgl_kejadian,'dd/mm/yyyy')  tgl_kejadian,
          kode_klaim kode_klaim_induk,
          to_char(tgl_bayar_klaim,'dd/mm/yyyy')  tgl_bayar_klaim_induk, 
          no_hp_penerima_manfaat,
          email_penerima_manfaat,
          decode(flag_dapat_beasiswa,'T','Tidak Dapat Beasiswa', 'Y', 'Dapat Beasiswa') flag_dapat_beasiswa,
          jml_anak_penerima_beasiswa,
          keterangan,
          tgl_rekam,
		  to_char(tgl_tindak_lanjut,'dd/mm/yyyy')  tgl_tindak_lanjut,
          decode(status_tindak_lanjut,'T','Belum Ditindaklanjuti','Sudah Ditindaklanjuti') status_tindak_lanjut
        from
         pn.pn_klaim_monitoring_beasiswa a
         {$condition}
         {$condition2}
            {$filter_kode_kantor}    
            {$ls_filter_tgl}   
    )  Z  

 "
 ;

//echo $sql;exit;
        
    $DB->parse($sql);
    $DB->execute();

    $irow = 1;
    while($data = $DB->nextrow()) {
      $objPHPSpreadsheet->setActiveSheetIndex(0)
      ->setCellValueExplicit('A' . ($irow + 2), $data['KODE_WILAYAH'], DataType::TYPE_STRING)
      ->setCellValueExplicit('B' . ($irow + 2), $data['KODE_KANTOR'], DataType::TYPE_STRING)
      ->setCellValueExplicit('C' . ($irow + 2), $data['NAMA_KANTOR'], DataType::TYPE_STRING)
      ->setCellValueExplicit('D' . ($irow + 2), $data['JENIS_KLAIM'], DataType::TYPE_STRING)
      ->setCellValueExplicit('E' . ($irow + 2), $data['KODE_SEGMEN'], DataType::TYPE_STRING)
      ->setCellValueExplicit('F' . ($irow + 2), $data['NAMA_TK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('G' . ($irow + 2), $data['KPJ'], DataType::TYPE_STRING)
      ->setCellValueExplicit('H' . ($irow + 2), $data['NIK_TK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('I' . ($irow + 2), $data['TGL_KEJADIAN'], DataType::TYPE_STRING)
      ->setCellValueExplicit('J' . ($irow + 2), $data['KODE_KLAIM_INDUK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('K' . ($irow + 2), $data['TGL_BAYAR_KLAIM_INDUK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('L' . ($irow + 2), $data['NO_HP_PENERIMA_MANFAAT'], DataType::TYPE_STRING)
      ->setCellValueExplicit('M' . ($irow + 2), $data['EMAIL_PENERIMA_MANFAAT'], DataType::TYPE_STRING)
      ->setCellValueExplicit('N' . ($irow + 2), $data['FLAG_DAPAT_BEASISWA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('O' . ($irow + 2), $data['JML_ANAK_PENERIMA_BEASISWA'], DataType::TYPE_STRING)
	  ->setCellValueExplicit('P' . ($irow + 2), $data['STATUS_TINDAK_LANJUT'], DataType::TYPE_STRING)
      ->setCellValueExplicit('Q' . ($irow + 2), $data['TGL_TINDAK_LANJUT'], DataType::TYPE_STRING)
	  ->setCellValueExplicit('R' . ($irow + 2), $data['KETERANGAN'], DataType::TYPE_STRING);
      $irow++;
    }

    $objPHPSpreadsheet->getActiveSheet()->getStyle('A1:R' . ($irow + 1))->applyFromArray($styleArrayBorder);
    $objPHPSpreadsheet->getActiveSheet()->getStyle('A1:R1')->applyFromArray($styleArrayHeader0);
    $objPHPSpreadsheet->getActiveSheet()->getStyle('A2:R2')->applyFromArray($styleArrayHeader2);
      
    $objPHPSpreadsheet->getActiveSheet()->setTitle('Data Monitoring Beasiswa ' . $KD_KANTOR);

    $objPHPSpreadsheet->setActiveSheetIndex(0);
    $objWriter = IOFactory::createWriter($objPHPSpreadsheet, 'Xlsx');
    $objWriter->save('php://output');
    $objPHPSpreadsheet->disconnectWorksheets();
    unset($objWriter, $objPHPSpreadsheet);
    exit;
}elseif($tipe = "PDF")
{
	$ls_nama_rpt = "PNR5059";
	$ls_modul      = "pn";
	$ls_user_param =" P_PERIODE1='".$ls_tglawaldisplay."' P_PERIODE2='".$ls_tglakhirdisplay."' P_KODE_KANTOR='".$ls_kode_kantor."' P_SEARCHBY='".$ls_search_by."'  P_SEARCHBY2='".$ls_search_by2."'  P_KEYWORD2A='".$ls_keyword2a."'  P_KEYWORD2B='".$ls_keyword2b."'  P_KEYWORD2C='".$ls_keyword2c."'  P_KEYWORD2D='".$ls_keyword2d."' P_SEARCHTEXT='".$ls_search_txt."'  ";

	exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
	exit;
}

?>
