<ul id="nav">
	<li><a href="#tab1" id="t1" class="active">Informasi Klaim</a></li>
	<li><a href="#tab2" id="t2" style="background:none;!important;"></a></li>

</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <? 
  //------------- informasi antrian ------------------------------------
  include "../ajax/pn5037_tabinfoantrian.php";
  // -------- end informasi antrian ------------------------------------
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5037_tabinfoklaim.php";
  ?>						
  </div>
</div>
						

