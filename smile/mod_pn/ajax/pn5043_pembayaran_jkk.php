<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JKK</a></li>	
<li><a href="#tab2" id="t2">Pembayaran Manfaat</a></li>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";

	if ($ls_kode_segmen=="JAKON")
	{
	 	 include "../ajax/pn5040_view_jakon_tk.php";
	}	
  if ($ls_kode_segmen=="TKI" && $ls_kode_perlindungan == "ONSITE")
	{
	 	 include "../ajax/pn5040_view_jkk_tkionsite.php";
	}else
	{
     include "../ajax/pn5040_view_jkk_tahap1.php";
     include "../ajax/pn5040_view_jkk_pengajuan.php";
     include "../ajax/pn5040_view_jkk_tahap2.php";
	}	
	include "../ajax/pn5041_view_penetapanmanfaat.php";
  include "../ajax/pn5040_view_tabadministrasi.php";
	?>										
  </div>
</div>

<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  include "../ajax/pn5043_pembayaran_grid.php";
  ?>	
  </div>
</div>
		