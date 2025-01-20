<?
if ($ls_kode_sebab_klaim == "SKK11")
{
 	 //gagal berangkat -----------------------------------------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk11.php";
}elseif ($ls_kode_sebab_klaim == "SKK22")
{
 	 //gagal ditempatkan ---------------------------------------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk22.php";
}elseif ($ls_kode_sebab_klaim == "SKK18" || $ls_kode_sebab_klaim == "SKK26")
{
 	 //kerugian atas tindakan pihak lain (kehilangan)-----------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk18.php";
}elseif ($ls_kode_sebab_klaim == "SKK21")
{
 	 //pemulangan tki bermasalah -------------------------------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk21.php";
}elseif ($ls_kode_sebab_klaim == "SKK27")
{
 	 //pemulangan tki bermasalah -------------------------------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk27.php";
}
elseif ($ls_kode_sebab_klaim == "SKK28")
{
 	 // phk bukan akibat kecelakaan kerja ----------------------------------------
	 include_once "../ajax/pn5040_agenda_jkk_1tahap_skk28.php";
}
?>
