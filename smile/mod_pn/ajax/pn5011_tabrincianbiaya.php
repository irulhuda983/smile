<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Rincian Biaya Promotif/Preventif
Hist: - 11/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$sql = "select bentuk_kegiatan, kategori_pelaksana from sijstk.pn_promotif_realisasi x ".
       "where x.kode_realisasi = '$ls_kode_realisasi' ";       
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_bentuk_kegiatan 	 = $row['BENTUK_KEGIATAN'];
$ls_kategori_pelaksana = $row['KATEGORI_PELAKSANA'];

if ($ls_kategori_pelaksana=="PR")
{
  if ($ls_bentuk_kegiatan == "JASA")
  {	 
  	 include "../ajax/pn5011_tabrincianbiayajasaprs.php";	 
  }else if ($ls_bentuk_kegiatan == "BARANG")
  {	 
  	 include "../ajax/pn5011_tabrincianbiayabarangprs.php";	 
  }
}else
{
  if ($ls_bentuk_kegiatan == "JASA")
  {	 
  	 include "../ajax/pn5011_tabrincianbiayajasa.php";	 
  }else if ($ls_bentuk_kegiatan == "BARANG")
  {	 
  	 include "../ajax/pn5011_tabrincianbiayabarang.php";	 
  }
}
?>	
