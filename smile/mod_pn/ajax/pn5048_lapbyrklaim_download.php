<?php
  session_start();
  require_once "../../includes/conf_global.php";
  require_once "../../includes/class_database.php";

  require '../../classes/phpspreadsheet/vendor/autoload.php';
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Cell\DataType;
  use PhpOffice\PhpSpreadsheet\Style\Border;
  use PhpOffice\PhpSpreadsheet\Style\Fill;

  $DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
  $username = $_SESSION["USER"];

  $ls_tipe_download = $_GET["tipe_download"];
  $ls_qtgl1         = $_GET["tgl1"];
  $ls_qtgl2         = $_GET["tgl2"];
  $ls_qtgllap       = $_GET["tgllap"];
  $ls_qktr          = $_GET["kode_kantor"];
  $ls_qprg          = $_GET["kd_prg"];
  $ls_qcarabyr      = $_GET["metode_byr"];
  $ls_qbuku         = $_GET["kode_buku"];
  $ls_bg_ul         = $_GET["bg_ul"];

  $arr = explode("/", $ls_qtgl1);
  $ls_qtgl1 = (isset($arr[2]) ? $arr[2] : "") . (isset($arr[1]) ? $arr[1] : "") . (isset($arr[0]) ? $arr[0] : "");

  $arr = explode("/", $ls_qtgl2);
  $ls_qtgl2 = (isset($arr[2]) ? $arr[2] : "") . (isset($arr[1]) ? $arr[1] : "") . (isset($arr[0]) ? $arr[0] : "");

  $arr = explode("/", $ls_qtgllap);
  $ls_qtgllap = (isset($arr[2]) ? $arr[2] : "") . (isset($arr[1]) ? $arr[1] : "") . (isset($arr[0]) ? $arr[0] : "");

  // barbar
  function bulan_indo($bulan) {
    switch ($bulan) {
      case "01":
        return "JANUARI";
      case "02":
        return "PEBRUARI";
      case "03":
        return "MARET";
      case "04":
        return "APRIL";
      case "05":
        return "MEI";
      case "06":
        return "JUNI";
      case "07":
        return "JULI";
      case "08":
        return "AGUSTUS";
      case "09":
        return "SEPTEMBER";
      case "10":
        return "OKTOBER";
      case "11":
        return "NOVEMBER";
      case "12":
        return"DESEMBER";
    }
  }

  if ($ls_tipe_download == "EXCEL") {
    // header_remove();
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=LAPORAN PEMBAYARAN JAMINAN.xls");
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
      ->setCellValue('A1', 'Kode Kantor')
      ->setCellValue('B1', 'Nama Kantor')
      ->setCellValue('C1', 'Kantor Induk')
      ->setCellValue('D1', 'Urut Kantor Induk')
      ->setCellValue('E1', 'Kode Klaim')
      ->setCellValue('F1', 'No Penetapan')
      ->setCellValue('G1', 'Kode Program')
      ->setCellValue('H1', 'Nama Program')
      ->setCellValue('I1', 'Kode Bank')
      ->setCellValue('J1', 'Kode Buku')
      ->setCellValue('K1', 'Tgl Bayar')
      ->setCellValue('L1', 'KPJ')
      ->setCellValue('M1', 'Nama TK')
      ->setCellValue('N1', 'Nama Perusahaan')
      ->setCellValue('O1', 'Pph 21')
      ->setCellValue('P1', 'Jumlah Bayar')
      ->setCellValue('Q1', 'Nama Rek. Penerima')
      ->setCellValue('R1', 'Bank Penerima')
      ->setCellValue('S1', 'No Rek. Penerima')
      ->setCellValue('T1', 'No Pointer')
      ->setCellValue('U1', 'Kode Trans Voucher')
      ->setCellValue('V1', 'NIK')
      ->setCellValue('W1', 'No Telp. Penerima');

    $styleArrayBorder = array(
      'borders' => array(
      'allborders' => array(
        'borderStyle' => Border::BORDER_THIN)
      )
    );

    $styleArrayHeader1 = array(
      'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb'=>'92d14f'),
      ),
      'font' => array(
        'bold' => false,
      )
    );

    $qry = "
    SELECT  A.KODE_KANTOR                      KD_CBG,
            C.NAMA_KANTOR                      NM_CBG,
            C.KODE_KANTOR_INDUK                KTR_INDUK,
            LPAD (C.KODE_KANTOR_INDUK, 2, '0') URUT_KTR_INDUK,
            A.KODE_KLAIM                       KD_KLAIM,
            B.NO_PENETAPAN,
            A.KD_PRG,
            (SELECT NM_PRG
                FROM SMILE.MS_PRG
              WHERE KD_PRG = A.KD_PRG)
                NM_PRG,
            A.KODE_BANK                        KD_BANK,
            A.KODE_BUKU                        KD_BUKU,
            TO_CHAR(A.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_BAYAR,
            B.KPJ                              KPJTK,
            B.NAMA_TK                          NM_TK,
            (SELECT NAMA_PERUSAHAAN
                FROM SMILE.KN_PERUSAHAAN
              WHERE KODE_PERUSAHAAN = B.KODE_PERUSAHAAN)
                NM_PRS,
            A.NOM_PPH                          PPH_21,
            A.NOM_PEMBAYARAN                   JML_BAYAR,
            D.NAMA_REKENING_PENERIMA           NM_REK_PENERIMA,
            D.BANK_PENERIMA,
            D.NO_REKENING_PENERIMA             NO_REK_PENERIMA,
            A.NO_POINTER,
            (SELECT DECODE (
                        X.STATUS_POSTING,
                        'Y', (   TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY')
                              || ' '
                              || X.KODE_BUKU
                              || ' '
                              || LPAD (X.NOMOR_TRANS, 8, 0)),
                        (TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY') || ' ' || X.ID_DOKUMEN))
                FROM SMILE.GL_VOUCHER X
              WHERE ID_DOKUMEN = A.NO_POINTER)
                KD_TRANS_VOUCHER,
            B.NOMOR_IDENTITAS                  NIK_TK,
            NVL (
                D.HANDPHONE,
                  D.TELEPON_AREA
                || '.'
                || D.TELEPON
                || '(ext. '
                || D.TELEPON_EXT
                || ')')
                NO_TELP_PENERIMA
        FROM SMILE.PN_KLAIM_PEMBAYARAN       A,
            SMILE.PN_KLAIM                  B,
            SMILE.MS_KANTOR                 C,
            SMILE.PN_KLAIM_PENERIMA_MANFAAT D
      WHERE     A.KODE_KLAIM = B.KODE_KLAIM
            AND A.KODE_KANTOR = C.KODE_KANTOR
            AND A.KODE_KLAIM = D.KODE_KLAIM
            AND A.KODE_TIPE_PENERIMA = D.KODE_TIPE_PENERIMA
            AND A.TGL_PEMBAYARAN BETWEEN TRUNC (TO_DATE (:QTGL1, 'yyyymmdd'),
                                                'dd')
                                      AND TRUNC (TO_DATE (:QTGL2, 'yyyymmdd'),
                                                'dd')
            AND NVL (TO_CHAR (A.TGL_BATAL, 'yyyymmdd'), '30001231') > :QTGLLAP
            AND A.KODE_KANTOR IN
                    (    SELECT KODE_KANTOR
                          FROM SMILE.MS_KANTOR
                    START WITH KODE_KANTOR = :QKTR
                    CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
            AND TO_CHAR (A.KD_PRG) LIKE NVL (:QPRG, '%')
            AND A.KODE_CARA_BAYAR LIKE NVL (:QCARABYR, '%')
            AND A.KODE_BUKU LIKE NVL (:QBUKU, '%')
      UNION ALL
      SELECT A.KODE_KANTOR                      KD_CBG,
            C.NAMA_KANTOR                      NM_CBG,
            C.KODE_KANTOR_INDUK                KTR_INDUK,
            LPAD (C.KODE_KANTOR_INDUK, 2, '0') URUT_KTR_INDUK,
            A.KODE_KLAIM                       KD_KLAIM,
            B.NO_PENETAPAN,
            A.KD_PRG,
            (SELECT NM_PRG
                FROM SMILE.MS_PRG
              WHERE KD_PRG = A.KD_PRG)
                NM_PRG,
            A.KODE_BANK                        KD_BANK,
            A.KODE_BUKU                        KD_BUKU,
            TO_CHAR(A.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_BAYAR,
            B.KPJ                              KPJTK,
            B.NAMA_TK                          NM_TK,
            (SELECT NAMA_PERUSAHAAN
                FROM SMILE.KN_PERUSAHAAN
              WHERE KODE_PERUSAHAAN = B.KODE_PERUSAHAAN)
                NM_PRS,
            A.NOM_PPH                          PPH_21,
            A.NOM_PEMBAYARAN                   JML_BAYAR,
            D.NAMA_REKENING_PENERIMA           NM_REK_PENERIMA,
            D.BANK_PENERIMA,
            D.NO_REKENING_PENERIMA             NO_REK_PENERIMA,
            A.NO_POINTER,
            (SELECT DECODE (
                        X.STATUS_POSTING,
                        'Y', (   TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY')
                              || ' '
                              || X.KODE_BUKU
                              || ' '
                              || LPAD (X.NOMOR_TRANS, 8, 0)),
                        (TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY') || ' ' || X.ID_DOKUMEN))
                FROM SMILE.GL_VOUCHER X
              WHERE ID_DOKUMEN = A.NO_POINTER)
                KD_TRANS_VOUCHER,
            B.NOMOR_IDENTITAS                  NIK_TK,
            NVL (
                D.HANDPHONE,
                  D.TELEPON_AREA
                || '.'
                || D.TELEPON
                || '(ext. '
                || D.TELEPON_EXT
                || ')')
                NO_TELP_PENERIMA
        FROM SMILE.PN_KLAIM_PEMBAYARAN_BERKALA A,
            SMILE.PN_KLAIM_BERKALA            E,
            SMILE.PN_KLAIM_PENERIMA_BERKALA   D,
            SMILE.PN_KLAIM                    B,
            SMILE.MS_KANTOR                   C
      WHERE     A.KODE_KLAIM = B.KODE_KLAIM
            AND A.KODE_KLAIM = E.KODE_KLAIM
            AND A.NO_KONFIRMASI = E.NO_KONFIRMASI
            AND A.KODE_KANTOR = C.KODE_KANTOR
            AND D.KODE_KLAIM = E.KODE_KLAIM
            AND D.KODE_PENERIMA_BERKALA = E.KODE_PENERIMA_BERKALA
            AND A.TGL_PEMBAYARAN BETWEEN TRUNC (TO_DATE (:QTGL1, 'yyyymmdd'),
                                                'dd')
                                      AND TRUNC (TO_DATE (:QTGL2, 'yyyymmdd'),
                                                'dd')
            AND NVL (TO_CHAR (A.TGL_BATAL, 'yyyymmdd'), '30001231') > :QTGLLAP
            AND A.KODE_KANTOR IN
                    (    SELECT KODE_KANTOR
                          FROM SMILE.MS_KANTOR
                    START WITH KODE_KANTOR = :QKTR
                    CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
            AND TO_CHAR (A.KD_PRG) LIKE NVL (:QPRG, '%')
            AND A.KODE_CARA_BAYAR LIKE NVL (:QCARABYR, '%')
            AND A.KODE_BUKU LIKE NVL (:QBUKU, '%')
      ORDER BY TGL_BAYAR,
              NO_POINTER,
              NO_PENETAPAN,
              KD_KLAIM
    ";

    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":QTGL1", $ls_qtgl1, 100);
    oci_bind_by_name($proc, ":QTGL2", $ls_qtgl2, 100);
    oci_bind_by_name($proc, ":QTGLLAP", $ls_qtgllap, 100);
    oci_bind_by_name($proc, ":QKTR", $ls_qktr, 100);
    oci_bind_by_name($proc, ":QPRG", $ls_qprg, 100);
    oci_bind_by_name($proc, ":QCARABYR", $ls_qcarabyr, 100);
    oci_bind_by_name($proc, ":QBUKU", $ls_qbuku, 100);

    $DB->execute();
    $irow = 1;
    while($data = $DB->nextrow()) {
      $objPHPSpreadsheet->setActiveSheetIndex(0)
      ->setCellValueExplicit('A' . ($irow + 1), $data['KD_CBG'], DataType::TYPE_STRING)
      ->setCellValueExplicit('B' . ($irow + 1), $data['NM_CBG'], DataType::TYPE_STRING)
      ->setCellValueExplicit('C' . ($irow + 1), $data['KTR_INDUK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('D' . ($irow + 1), $data['URUT_KTR_INDUK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('E' . ($irow + 1), $data['KD_KLAIM'], DataType::TYPE_STRING)
      ->setCellValueExplicit('F' . ($irow + 1), $data['NO_PENETAPAN'], DataType::TYPE_STRING)
      ->setCellValueExplicit('G' . ($irow + 1), $data['KD_PRG'], DataType::TYPE_STRING)
      ->setCellValueExplicit('H' . ($irow + 1), $data['NM_PRG'], DataType::TYPE_STRING)
      ->setCellValueExplicit('I' . ($irow + 1), $data['KD_BANK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('J' . ($irow + 1), $data['KD_BUKU'], DataType::TYPE_STRING)
      ->setCellValueExplicit('K' . ($irow + 1), $data['TGL_BAYAR'], DataType::TYPE_STRING)
      ->setCellValueExplicit('L' . ($irow + 1), $data['KPJTK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('M' . ($irow + 1), $data['NM_TK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('N' . ($irow + 1), $data['NM_PRS'], DataType::TYPE_STRING)
      ->setCellValueExplicit('O' . ($irow + 1), $data['PPH_21'], DataType::TYPE_STRING)
      ->setCellValueExplicit('P' . ($irow + 1), $data['JML_BAYAR'], DataType::TYPE_STRING)
      ->setCellValueExplicit('Q' . ($irow + 1), $data['NM_REK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('R' . ($irow + 1), $data['BANK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('S' . ($irow + 1), $data['NO_REK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('T' . ($irow + 1), $data['NO_POINTER'], DataType::TYPE_STRING)
      ->setCellValueExplicit('U' . ($irow + 1), $data['KD_TRANS_VOUCHER'], DataType::TYPE_STRING)
      ->setCellValueExplicit('V' . ($irow + 1), $data['NIK_TK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('W' . ($irow + 1), $data['NO_TELP_PENERIMA'], DataType::TYPE_STRING);     
      $irow++;
    }

    $objPHPSpreadsheet->getActiveSheet()->getStyle('A1:W' . ($irow + 1))->applyFromArray($styleArrayBorder);
    $objPHPSpreadsheet->getActiveSheet()->getStyle('A1:W1')->applyFromArray($styleArrayHeader1);
      
    $objPHPSpreadsheet->getActiveSheet()->setTitle('Pembayaran Jaminan');

    $objPHPSpreadsheet->setActiveSheetIndex(0);
    $objWriter = IOFactory::createWriter($objPHPSpreadsheet, 'Xls');
    $objWriter->save('php://output');
    $objPHPSpreadsheet->disconnectWorksheets();
    unset($objWriter, $objPHPSpreadsheet);
    die();
  } else if ($ls_tipe_download == "JHT_NON_TOKEN_EXCEL") {
    // header_remove();
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=LAPORAN PEMBAYARAN JAMINAN.xls");
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
      ->setCellValue('A4', 'NO')
      ->setCellValue('B4', 'A/N')
      ->setCellValue('C4', 'BANK')
      ->setCellValue('D4', 'NO. REKENING')
      ->setCellValue('E4', 'JUMLAH BAYAR')
      ->setCellValue('F4', 'TK')
      ->setCellValue('G4', 'PERUSAHAAN');

    $styleArrayBorder = array(
      'borders' => array(
      'allborders' => array(
        'borderStyle' => Border::BORDER_THIN)
      )
    );

    $styleArrayHeader1 = array(
      'fill' => array(
        'fillType' => Fill::FILL_SOLID,
        'startColor' => array('rgb'=>'92d14f'),
      ),
      'font' => array(
        'bold' => false,
      )
    );

    $qry = "
    SELECT  A.KODE_KANTOR                      KD_CBG,
            C.NAMA_KANTOR                      NM_CBG,
            C.KODE_KANTOR_INDUK                KTR_INDUK,
            LPAD (C.KODE_KANTOR_INDUK, 2, '0') URUT_KTR_INDUK,
            A.KODE_KLAIM                       KD_KLAIM,
            B.NO_PENETAPAN,
            A.KD_PRG,
            (SELECT NM_PRG
                FROM SMILE.MS_PRG
              WHERE KD_PRG = A.KD_PRG)
                NM_PRG,
            A.KODE_BANK                        KD_BANK,
            A.KODE_BUKU                        KD_BUKU,
            TO_CHAR(A.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_BAYAR,
            B.KPJ                              KPJTK,
            B.NAMA_TK                          NM_TK,
            (SELECT NAMA_PERUSAHAAN
                FROM SMILE.KN_PERUSAHAAN
              WHERE KODE_PERUSAHAAN = B.KODE_PERUSAHAAN)
                NM_PRS,
            A.NOM_PPH                          PPH_21,
            A.NOM_PEMBAYARAN                   JML_BAYAR,
            D.NAMA_REKENING_PENERIMA           NM_REK_PENERIMA,
            D.BANK_PENERIMA,
            D.NO_REKENING_PENERIMA             NO_REK_PENERIMA,
            A.NO_POINTER,
            (SELECT DECODE (
                        X.STATUS_POSTING,
                        'Y', (   TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY')
                              || ' '
                              || X.KODE_BUKU
                              || ' '
                              || LPAD (X.NOMOR_TRANS, 8, 0)),
                        (TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY') || ' ' || X.ID_DOKUMEN))
                FROM SMILE.GL_VOUCHER X
              WHERE ID_DOKUMEN = A.NO_POINTER)
                KD_TRANS_VOUCHER,
            B.NOMOR_IDENTITAS                  NIK_TK,
            NVL (
                D.HANDPHONE,
                  D.TELEPON_AREA
                || '.'
                || D.TELEPON
                || '(ext. '
                || D.TELEPON_EXT
                || ')')
                NO_TELP_PENERIMA
        FROM SMILE.PN_KLAIM_PEMBAYARAN       A,
            SMILE.PN_KLAIM                  B,
            SMILE.MS_KANTOR                 C,
            SMILE.PN_KLAIM_PENERIMA_MANFAAT D
      WHERE     A.KODE_KLAIM = B.KODE_KLAIM
            AND A.KODE_KANTOR = C.KODE_KANTOR
            AND A.KODE_KLAIM = D.KODE_KLAIM
            AND A.KODE_TIPE_PENERIMA = D.KODE_TIPE_PENERIMA
            AND A.TGL_PEMBAYARAN BETWEEN TRUNC (TO_DATE (:QTGL1, 'yyyymmdd'),
                                                'dd')
                                      AND TRUNC (TO_DATE (:QTGL2, 'yyyymmdd'),
                                                'dd')
            AND NVL (TO_CHAR (A.TGL_BATAL, 'yyyymmdd'), '30001231') > :QTGLLAP
            AND A.KODE_KANTOR IN
                    (    SELECT KODE_KANTOR
                          FROM SMILE.MS_KANTOR
                    START WITH KODE_KANTOR = :QKTR
                    CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
            AND TO_CHAR (A.KD_PRG) LIKE NVL (:QPRG, '%')
            AND A.KODE_CARA_BAYAR LIKE NVL (:QCARABYR, '%')
            AND A.KODE_BUKU LIKE NVL (:QBUKU, '%')
      UNION ALL
      SELECT A.KODE_KANTOR                      KD_CBG,
            C.NAMA_KANTOR                      NM_CBG,
            C.KODE_KANTOR_INDUK                KTR_INDUK,
            LPAD (C.KODE_KANTOR_INDUK, 2, '0') URUT_KTR_INDUK,
            A.KODE_KLAIM                       KD_KLAIM,
            B.NO_PENETAPAN,
            A.KD_PRG,
            (SELECT NM_PRG
                FROM SMILE.MS_PRG
              WHERE KD_PRG = A.KD_PRG)
                NM_PRG,
            A.KODE_BANK                        KD_BANK,
            A.KODE_BUKU                        KD_BUKU,
            TO_CHAR(A.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_BAYAR,
            B.KPJ                              KPJTK,
            B.NAMA_TK                          NM_TK,
            (SELECT NAMA_PERUSAHAAN
                FROM SMILE.KN_PERUSAHAAN
              WHERE KODE_PERUSAHAAN = B.KODE_PERUSAHAAN)
                NM_PRS,
            A.NOM_PPH                          PPH_21,
            A.NOM_PEMBAYARAN                   JML_BAYAR,
            D.NAMA_REKENING_PENERIMA           NM_REK_PENERIMA,
            D.BANK_PENERIMA,
            D.NO_REKENING_PENERIMA             NO_REK_PENERIMA,
            A.NO_POINTER,
            (SELECT DECODE (
                        X.STATUS_POSTING,
                        'Y', (   TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY')
                              || ' '
                              || X.KODE_BUKU
                              || ' '
                              || LPAD (X.NOMOR_TRANS, 8, 0)),
                        (TO_CHAR (X.TGL_TRANS, 'DD-MM-YYYY') || ' ' || X.ID_DOKUMEN))
                FROM SMILE.GL_VOUCHER X
              WHERE ID_DOKUMEN = A.NO_POINTER)
                KD_TRANS_VOUCHER,
            B.NOMOR_IDENTITAS                  NIK_TK,
            NVL (
                D.HANDPHONE,
                  D.TELEPON_AREA
                || '.'
                || D.TELEPON
                || '(ext. '
                || D.TELEPON_EXT
                || ')')
                NO_TELP_PENERIMA
        FROM SMILE.PN_KLAIM_PEMBAYARAN_BERKALA A,
            SMILE.PN_KLAIM_BERKALA            E,
            SMILE.PN_KLAIM_PENERIMA_BERKALA   D,
            SMILE.PN_KLAIM                    B,
            SMILE.MS_KANTOR                   C
      WHERE     A.KODE_KLAIM = B.KODE_KLAIM
            AND A.KODE_KLAIM = E.KODE_KLAIM
            AND A.NO_KONFIRMASI = E.NO_KONFIRMASI
            AND A.KODE_KANTOR = C.KODE_KANTOR
            AND D.KODE_KLAIM = E.KODE_KLAIM
            AND D.KODE_PENERIMA_BERKALA = E.KODE_PENERIMA_BERKALA
            AND A.TGL_PEMBAYARAN BETWEEN TRUNC (TO_DATE (:QTGL1, 'yyyymmdd'),
                                                'dd')
                                      AND TRUNC (TO_DATE (:QTGL2, 'yyyymmdd'),
                                                'dd')
            AND NVL (TO_CHAR (A.TGL_BATAL, 'yyyymmdd'), '30001231') > :QTGLLAP
            AND A.KODE_KANTOR IN
                    (    SELECT KODE_KANTOR
                          FROM SMILE.MS_KANTOR
                    START WITH KODE_KANTOR = :QKTR
                    CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
            AND TO_CHAR (A.KD_PRG) LIKE NVL (:QPRG, '%')
            AND A.KODE_CARA_BAYAR LIKE NVL (:QCARABYR, '%')
            AND A.KODE_BUKU LIKE NVL (:QBUKU, '%')
      ORDER BY TGL_BAYAR,
              NO_POINTER,
              NO_PENETAPAN,
              KD_KLAIM
    ";

    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":QTGL1", $ls_qtgl1, 100);
    oci_bind_by_name($proc, ":QTGL2", $ls_qtgl1, 100); // PAKAI TGL1: HANYA UNTUK 1 TANGGAL SAJA
    oci_bind_by_name($proc, ":QTGLLAP", $ls_qtgllap, 100);
    oci_bind_by_name($proc, ":QKTR", $ls_qktr, 100);
    oci_bind_by_name($proc, ":QPRG", $ls_qprg, 100);
    oci_bind_by_name($proc, ":QCARABYR", $ls_qcarabyr, 100);
    oci_bind_by_name($proc, ":QBUKU", $ls_qbuku, 100);

    $ls_tgl_bayar = "";
    $DB->execute();
    $irow = 4;
    $no = 1;
    while($data = $DB->nextrow()) {
      $objPHPSpreadsheet->setActiveSheetIndex(0)
      ->setCellValueExplicit('A' . ($irow + 1), $no, DataType::TYPE_STRING)
      ->setCellValueExplicit('B' . ($irow + 1), $data['NM_REK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('C' . ($irow + 1), $data['BANK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('D' . ($irow + 1), $data['NO_REK_PENERIMA'], DataType::TYPE_STRING)
      ->setCellValueExplicit('E' . ($irow + 1), $data['JML_BAYAR'], DataType::TYPE_STRING)
      ->setCellValueExplicit('F' . ($irow + 1), $data['NM_TK'], DataType::TYPE_STRING)
      ->setCellValueExplicit('G' . ($irow + 1), $data['NM_PRS'], DataType::TYPE_STRING);
      $ls_tgl_bayar = $data['TGL_BAYAR'];
      $irow++;
      $no++;
    }

    // SET TITLE
    $objPHPSpreadsheet->setActiveSheetIndex(0)->mergeCells('A1:G1');
    $objPHPSpreadsheet->setActiveSheetIndex(0)->mergeCells('A2:G2');
    $objPHPSpreadsheet->setActiveSheetIndex(0)->mergeCells('A3:G3');
    
    $arr = explode("-", $ls_tgl_bayar);
    $ls_tgl_pembayaran =  (isset($arr[0]) ? $arr[0] : "") . " " . (isset($arr[1]) ? bulan_indo($arr[1]) : "") . " " .(isset($arr[2]) ? $arr[2] : "");

    $objPHPSpreadsheet->setActiveSheetIndex(0)->setCellValueExplicit('A1', "TRANSFER JHT $ls_tgl_pembayaran", DataType::TYPE_STRING);
    $objPHPSpreadsheet->setActiveSheetIndex(0)->setCellValueExplicit('A2', "BG UL $ls_bg_ul", DataType::TYPE_STRING);

    $objPHPSpreadsheet->getActiveSheet()->getStyle('A4:G' . ($irow + 1))->applyFromArray($styleArrayBorder);
    $objPHPSpreadsheet->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArrayHeader1);
    
    foreach(range('A','G') as $columnID) {
      $objPHPSpreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    $objPHPSpreadsheet->getActiveSheet()->setTitle('Pembayaran JHT');

    $objPHPSpreadsheet->setActiveSheetIndex(0);
    $objWriter = IOFactory::createWriter($objPHPSpreadsheet, 'Xls');
    $objWriter->save('php://output');
    $objPHPSpreadsheet->disconnectWorksheets();
    unset($objWriter, $objPHPSpreadsheet);
    die();
  } else {
    echo "Tipe download tidak didukung";
  }
?>
