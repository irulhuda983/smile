<ul id="nav"">					
  <li><a href="#tab1" id="t1">Informasi Agenda Klaim JP</a></li>	
		<?
		//jika menerima manfaat berkala --------------------------------------------
  	if ($ls_flag_berkala == "Y")
    {
      ?>
      <li><a href="#tab2" id="t2">Penetapan Manfaat Berkala</a></li>
			<li><a href="#tab5" id="t5">Konfirmasi Berkala</a></li>
			<li><a href="#tab6" id="t6">Pembayaran Berkala</a></li>
      <?
    }
  	//jika menerima manfaat lumpsum --------------------------------------------
  	if ($ls_flag_lumpsum == "Y")
    {
      ?>
      <li><a href="#tab3" id="t3">Penetapan Manfaat Lumpsum</a></li>
			<li><a href="#tab4" id="t4">Pembayaran Lumpsum</a></li>
      <?
    }
  	?>
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";
	include "../ajax/pn5040_view_jpn_ahliwaris.php";
  include "../ajax/pn5040_view_jpn_inputdataklaim.php";
  include "../ajax/pn5040_view_tabadministrasi.php";
  ?>											
  </div>
</div>

<?
//jika menerima manfaat berkala --------------------------------------------
if ($ls_flag_berkala == "Y")
{
?>
  <div style="display: none;" id="tab2" class="tab_konten">
    <div id="konten">
    <?
  		 include "../ajax/pn5040_view_jpn_manfaatberkala.php";
    ?>
    </div>
  </div>	
	
  <div style="display: none;" id="tab5" class="tab_konten">
    <div id="konten">
    <?
  		 include "../ajax/pn5040_view_jpn_konfirmasiberkala.php";
    ?>
    </div>
  </div>		

  <div style="display: none;" id="tab6" class="tab_konten">
    <div id="konten">
    <?
  		 include "../ajax/pn5040_view_jpn_pembayaranberkala.php";
    ?>
    </div>
  </div>		
<?
}

//jika menerima manfaat lumpsum --------------------------------------------
if ($ls_flag_lumpsum == "Y")
{
?>
  <div style="display: none;" id="tab3" class="tab_konten">
    <div id="konten">
    <?
  			include "../ajax/pn5040_view_jpn_manfaatlumpsum.php";
    ?>
    </div>
  </div>		
	
  <div style="display: none;" id="tab4" class="tab_konten">
    <div id="konten">
    <?
  			include "../ajax/pn5043_view_pembayaran_grid.php";
    ?>
    </div>
  </div>		
<?
}
?>
		


	