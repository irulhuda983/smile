<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<li><a href="#tab2" id="t2">Penetapan Ahli Waris</a></li>
	<?	
	if ($ln_cnt_penerima_berkala > "0")
  {
  	?>
		<li><a href="#tab3" id="t3">Input Data Klaim</a></li>
		<?
		//jika menerima manfaat berkala --------------------------------------------
  	if ($ls_flag_berkala == "Y")
    {
      ?>
      <li><a href="#tab5" id="t5">Manfaat Berkala</a></li>
      <?
    }
  	//jika menerima manfaat lumpsum --------------------------------------------
  	if ($ls_flag_lumpsum == "Y")
    {
      ?>
      <li><a href="#tab6" id="t6">Manfaat Lumpsum</a></li>
      <?
    }
  	?>
  	<li><a href="#tab11" id="t11">Administrasi</a></li>
		<?
	}
	?>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi antrian ------------------------------------
  include "../ajax/pn5040_tabinfoantrian.php";
  // -------- end informasi antrian ------------------------------------
  
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_tabinfoklaim.php";
  ?>						
  </div>
</div>

<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
  <?
  //------------- penetapan ahli waris -----------------------------------------
  include "../ajax/pn5040_agenda_jpn_ahliwaris.php";
  ?>
  </div>
</div>

<?	
if ($ln_cnt_penerima_berkala > "0")
{
	?>
  <div style="display: none;" id="tab3" class="tab_konten">
    <div id="konten">
    <?
    //------------- input data klaim ---------------------------------------------
    include "../ajax/pn5040_agenda_jpn_inputdataklaim.php";
    ?>						
    </div>
  </div>
	 
  <?
  //jika menerima manfaat berkala ----------------------------------------------
  if ($ls_flag_berkala == "Y")
  {
    ?>
    <div style="display: none;" id="tab5" class="tab_konten">
      <div id="konten">
      <?
      //------------- input data klaim ---------------------------------------------
      include "../ajax/pn5040_agenda_jpn_manfaatberkala.php";
      ?>						
      </div>
    </div>
    <?
  }
  ?>
  
  <?	
  //jika menerima manfaat lumpsum ----------------------------------------------
  if ($ls_flag_lumpsum == "Y")
  {
    ?>
    <div style="display: none;" id="tab6" class="tab_konten">
      <div id="konten">
      <?
      //------------- input data klaim -----------------------------------------
      include "../ajax/pn5040_agenda_jpn_manfaatlumpsum.php";
      ?>						
      </div>
    </div>
    <?
  }
  ?>
  
  <div style="display: none;" id="tab11" class="tab_konten">
    <div id="konten" style="width: 1200px;">
    <?
    //------------- kelengkapan administrasi -----------------------------------
    include "../ajax/pn5040_tabadministrasi.php";
    ?>						
    </div>
  </div>
	
<?
}
?>
