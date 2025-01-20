<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JP</a></li>	
		<?
		//jika menerima manfaat berkala --------------------------------------------
  	if ($ls_flag_berkala == "Y")
    {
      ?>
      <li><a href="#tab2" id="t2">Penetapan Manfaat Berkala</a></li>
      <?
    }
  	//jika menerima manfaat lumpsum --------------------------------------------
  	if ($ls_flag_lumpsum == "Y")
    {
      ?>
      <li><a href="#tab3" id="t3">Penetapan Manfaat Lumpsum</a></li>
      <?
    }
  	?>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5001_view_tabinfoklaim.php";
  include "../ajax/pn5001_view_jpn_inputdataklaim.php";
  include "../ajax/pn5001_tabadministrasi.php";
  ?>											
  </div>
</div>

<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //jika menerima manfaat berkala ------------------------------------------
  if ($ls_flag_berkala == "Y")
  {
   	 //include "../ajax/pn5001_view_jpn_manfaatberkala.php";
		 include "../ajax/pn5001_agenda_jpn_manfaatberkala.php";
  }
  ?>
  </div>
</div>

<div style="display: none;" id="tab3" class="tab_konten">
  <div id="konten">
  <?
  //jika menerima manfaat lumpsum ------------------------------------------
  if ($ls_flag_lumpsum == "Y")
  {
   	 //include "../ajax/pn5001_view_jpn_manfaatlumpsum.php";
		 include "../ajax/pn5001_agenda_jpn_manfaatlumpsum.php";
  }
  ?>
  </div>
</div>		