<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JKP</a></li>	
	<li><a href="#tab2" id="t2">Penetapan Manfaat Biaya dan Santunan</a></li>	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";
  include "../ajax/pn5040_view_jkp.php";

  echo '<div id="formKiri" style="width:955px;">';
  include "../ajax/pn5040_view_profile_aktivitas_jkp.php";
  echo '</div>';
  
  include "../ajax/pn5040_view_tabadministrasi.php";
  ?>											
  </div>
</div>
		
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
	if ($ls_status_submit_penetapan=="Y")
	{
    //------------- view penetapan manfaat --------------------------------------------
    include "../ajax/pn5041_view_penetapanmanfaat.php";	
	}else
	{
    //------------- penetapan manfaat --------------------------------------------
    include "../ajax/pn5041_penetapanmanfaat.php";
	}		
  ?>
  </div>
</div>		