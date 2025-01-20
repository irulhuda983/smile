<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* =============================================================================
Ket : Form ini digunakan untuk tab view Agenda Klaim JP
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)			
			- 04/11/2019 : pemindahan query ke webservice 						 
------------------------------------------------------------------------------*/
?>
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">
    <fieldset><legend>Penetapan Ahli Waris</legend>																	
      <table id="mydataAwsJp" width="100%" class="table-data2">
        <thead>	
          <tr>
            <th colspan="4">&nbsp;</th>
            <th colspan="2" style="text-align: center" class="hr-single-bottom">Kondisi Akhir</th>
            <th colspan="2">&nbsp;</th>					    							
          </tr>												
          <tr class="hr-double-bottom">
            <th style="text-align:left;width:120px;">Hub. Keluarga</th>
            <th style="text-align:left;width:200px;">Nama Lengkap</th>
            <th style="text-align:center;width:80px;">Tgl Lahir</th>
            <th style="text-align:left;width:80px;">Jenis Kelamin</th>
            <th style="text-align:center;">Status</th>
            <th style="text-align:center;width:100px;">Sejak</th>
            <th style="text-align:center;width:50px;">Eligible</th>
            <th style="text-align:center;width:100px;">Action</th>					    							
          </tr>	
        </thead>	
        <tbody>												
          <?
          //get list data ahli waris -----------------------------------------
          if ($_POST["INPUT_JPN_LISTAWS"])
          {
            $ln_dtl =0;  
            for($i=0;$i<ExtendedFunction::count($_POST["INPUT_JPN_LISTAWS"]);$i++)
            {
              $ls_awsjp_nama_hubungan 				= $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_HUBUNGAN"] 				=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_HUBUNGAN"];
              $ls_awsjp_nama_lengkap 					= $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_LENGKAP"] 				=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_LENGKAP"];
              $ld_awsjp_tgl_lahir 						= $_POST["INPUT_JPN_LISTAWS"][$i]["TGL_LAHIR"] 						=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["TGL_LAHIR"];
              $ls_awsjp_nama_jenis_kelamin 		= $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_JENIS_KELAMIN"] 	=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_JENIS_KELAMIN"];
              $ls_awsjp_nama_kondisi_terakhir = $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_KONDISI_TERAKHIR"]=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["NAMA_KONDISI_TERAKHIR"];
              $ld_awsjp_tgl_kondisi_terakhir 	= $_POST["INPUT_JPN_LISTAWS"][$i]["TGL_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["TGL_KONDISI_TERAKHIR"];
              $ls_awsjp_status_layak 					= $_POST["INPUT_JPN_LISTAWS"][$i]["STATUS_LAYAK"] 				=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["STATUS_LAYAK"];
              $ls_awsjp_kode_penerima_berkala = $_POST["INPUT_JPN_LISTAWS"][$i]["KODE_PENERIMA_BERKALA"]=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["KODE_PENERIMA_BERKALA"];
              $ls_awsjp_no_urut_keluarga 			= $_POST["INPUT_JPN_LISTAWS"][$i]["NO_URUT_KELUARGA"] 		=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["NO_URUT_KELUARGA"];
              $ls_awsjp_kode_klaim 						= $_POST["INPUT_JPN_LISTAWS"][$i]["KODE_KLAIM"] 					=="null" ? "" : $_POST["INPUT_JPN_LISTAWS"][$i]["KODE_KLAIM"];
              ?>
              <tr>
                <td style="text-align:left;"><?=$ls_awsjp_nama_hubungan;?></td>	
                <td style="text-align:left;"><?=$ls_awsjp_nama_lengkap;?></td>
                <td style="text-align:center;"><?=$ld_awsjp_tgl_lahir;?></td>
                <td style="text-align:left;"><?=$ls_awsjp_nama_jenis_kelamin;?></td>
                <td style="text-align:center;"><?=$ls_awsjp_nama_kondisi_terakhir;?></td>
                <td style="text-align:center;"><?=$ld_awsjp_tgl_kondisi_terakhir;?></td>
                <td style="text-align:center;">
                	<?=($ls_awsjp_status_layak=="Y" ? "<img src=../../images/file_apply.gif>"." ".$ls_awsjp_kode_penerima_berkala : "")?>
                </td>
                <td style="text-align:center;">
									<a href="#" onClick="fl_js_jpn_ahliwaris_rinci('<?=$ls_awsjp_kode_klaim;?>', '<?=$ls_awsjp_no_urut_keluarga;?>');"><img src="../../images/user_go.png" border="0" alt="View Detil Penerima" align="absmiddle" />View</a>
                </td>
              </tr>
              <?
              //hitung total									    							
              //$i++;//iterasi i							
            }	//end while
            $ln_dtl=$i;
            
            if ($i == 0) 
            {
              echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
              echo '<td colspan="8" style="text-align:center;">-- tidak ada perekaman data ahli waris --</td>';
              echo '</tr>';
            }
          }					
          ?>									             																
        </tbody>
        <tfoot>									
          <tr>
            <td colspan="2"style="text-align:left;" ></td>
            <td style="text-align:left" colspan="5"><b><i>&nbsp;<i></b>
              <input type="hidden" id="jpnaws_kounter_dtl" name="jpnaws_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="jpnaws_count_dtl" name="jpnaws_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="jpnaws_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
            </td>										
          </tr>
        </tfoot>
      </table>
      </br>
    </fieldset>	
  </div>
</div>

<?	
//get data input klaim jp - kondisi terakhir -----------------------------------
//ambil case dan kondisi tk ------------------------------------------------
$ls_jpn_kode_klaim			 							 = $_POST["INFO_KLAIM"]["KODE_KLAIM"]  		   =="null" ? "" : $_POST["INFO_KLAIM"]["KODE_KLAIM"];																					
$ls_jpn_tk_kode_jenis_kasus 					 = $_POST["INFO_KLAIM"]["KODE_JENIS_KASUS"]  =="null" ? "" : $_POST["INFO_KLAIM"]["KODE_JENIS_KASUS"];
$ls_jpn_tk_nama_jenis_kasus 					 = $_POST["INFO_KLAIM"]["NAMA_JENIS_KASUS"]  =="null" ? "" : $_POST["INFO_KLAIM"]["NAMA_JENIS_KASUS"];
$ld_jpn_tk_tgl_jenis_kasus						 = $_POST["INFO_KLAIM"]["TGL_KEJADIAN"] 		 =="null" ? "" : $_POST["INFO_KLAIM"]["TGL_KEJADIAN"];
$ld_jpn_tk_tgl_mulai_pensiun 					 = $_POST["INFO_KLAIM"]["TGL_MULAI_PENSIUN"] =="null" ? "" : $_POST["INFO_KLAIM"]["TGL_MULAI_PENSIUN"];

//ambil kondisi terakhir tk ------------------------------------------------
$ls_jpn_tk_nama 											 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_NAMA"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_NAMA"];
$ls_jpn_tk_kode_kondisi_terakhir 			 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_KODE_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_KODE_KONDISI_TERAKHIR"];
$ls_jpn_tk_nama_kondisi_terakhir			 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_NAMA_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_NAMA_KONDISI_TERAKHIR"];	
$ld_jpn_tk_tgl_kondisi_terakhir 			 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_TGL_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_TK_TGL_KONDISI_TERAKHIR"];

//ambil nama suamis/istri --------------------------------------------------
$ls_jpn_pasangan_nama 								 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_NAMA"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_NAMA"];
$ls_jpn_pasangan_kode_kondisi_terakhir = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_KODE_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_KODE_KONDISI_TERAKHIR"];	
$ls_jpn_pasangan_nama_kondisi_terakhir = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_NAMA_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_NAMA_KONDISI_TERAKHIR"];
$ld_jpn_pasangan_tgl_kondisi_terakhir	 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_TGL_KONDISI_TERAKHIR"]	 =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_PAS_TGL_KONDISI_TERAKHIR"];

//ambil nama anak pertama --------------------------------------------------
$ls_jpn_anak1_nama 										 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_NAMA"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_NAMA"];
$ls_jpn_anak1_kode_kondisi_terakhir 	 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_KODE_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_KODE_KONDISI_TERAKHIR"];	
$ls_jpn_anak1_nama_kondisi_terakhir		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_NAMA_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_NAMA_KONDISI_TERAKHIR"];
$ld_jpn_anak1_tgl_kondisi_terakhir		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_TGL_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A1_TGL_KONDISI_TERAKHIR"];
				
//ambil nama anak kedua ----------------------------------------------------
$ls_jpn_anak2_nama 										 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_NAMA"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_NAMA"];
$ls_jpn_anak2_kode_kondisi_terakhir 	 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_KODE_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_KODE_KONDISI_TERAKHIR"];
$ls_jpn_anak2_nama_kondisi_terakhir		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_NAMA_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_NAMA_KONDISI_TERAKHIR"];
$ld_jpn_anak2_tgl_kondisi_terakhir		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_TGL_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_A2_TGL_KONDISI_TERAKHIR"];

//ambil nama orang tua -----------------------------------------------------
$ls_jpn_ortu_nama  		 								 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_NAMA"] =="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_NAMA"];
$ls_jpn_ortu_kode_kondisi_terakhir 		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_KODE_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_KODE_KONDISI_TERAKHIR"];
$ls_jpn_ortu_nama_kondisi_terakhir		 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_NAMA_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_NAMA_KONDISI_TERAKHIR"];	
$ld_jpn_ortu_tgl_kondisi_terakhir			 = $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_TGL_KONDISI_TERAKHIR"]	=="null" ? "" : $_POST["INPUT_JPN_KONDISIAKHIR"]["P_OT_TGL_KONDISI_TERAKHIR"];
//end get data input klaim jp - kondisi terakhir -------------------------------
?>

<fieldset><legend>Input Data Klaim Manfaat Pensiun</legend>
  <table id="tbljpn" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
  	<tbody>
      <tr>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="3">Peristiwa A</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="3">Peristiwa B</th>			
      </tr>	
      <tr>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:120px;"">Hub. Keluarga</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:240px;">Nama</th>	
        <th colspan="8"><hr style="border:0; border-top:1px double #8c8c8c;"/></th>	
      </tr>							
      <tr>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Jenis Peristiwa</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Tgl Kejadian</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Jenis Peristiwa</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">|</th>
        <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:110px;">Tgl Kejadian</th>					
      </tr>
      <tr>
      	<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      </tr>
      
      <!--  ----------------------- TENAGA KERJA ------------------------------>
      <tr>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Tenaga Kerja</td>
        <td>
					<input type="text" id="jpn_tk_nama" name="jpn_tk_nama" value="<?=$ls_jpn_tk_nama;?>" style="width:217px;" readonly class="disabled">
					<a href="#" onClick="fl_js_jpn_ahliwaris_rinci('<?=$ls_jpn_kode_klaim;?>', '0');"><img src="../../images/user_go.png" border="0" alt="Tambah" align="absmiddle" /></a>
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_tk_nama_jenis_kasus" name="jpn_tk_nama_jenis_kasus" value="<?=$ls_jpn_tk_nama_jenis_kasus;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_tk_kode_jenis_kasus" name="jpn_tk_kode_jenis_kasus" value="<?=$ls_jpn_tk_kode_jenis_kasus;?>">
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_tk_tgl_jenis_kasus" name="jpn_tk_tgl_jenis_kasus" value="<?=$ld_jpn_tk_tgl_jenis_kasus;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_tk_nama_kondisi_terakhir" name="jpn_tk_nama_kondisi_terakhir" value="<?=$ls_jpn_tk_nama_kondisi_terakhir;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_tk_kode_kondisi_terakhir" name="jpn_tk_kode_kondisi_terakhir" value="<?=$ls_jpn_tk_kode_kondisi_terakhir;?>">
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_tk_tgl_kondisi_terakhir" name="jpn_tk_tgl_kondisi_terakhir" value="<?=$ld_jpn_tk_tgl_kondisi_terakhir;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>	
      </tr>
      
      <!--  ---------------------- ISTRI / SUAMI ------------------------------>
      <tr>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Istri/Suami</td>
        <td><input type="text" id="jpn_pasangan_nama" name="jpn_pasangan_nama" value="<?=$ls_jpn_pasangan_nama;?>" style="width:240px;" readonly class="disabled"></td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_pasangan_nama_kondisi_terakhir" name="jpn_pasangan_nama_kondisi_terakhir" value="<?=$ls_jpn_pasangan_nama_kondisi_terakhir;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_pasangan_kode_kondisi_terakhir" name="jpn_pasangan_kode_kondisi_terakhir" value="<?=$ls_jpn_pasangan_kode_kondisi_terakhir;?>">	
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_pasangan_tgl_kondisi_terakhir" name="jpn_pasangan_tgl_kondisi_terakhir" value="<?=$ld_jpn_pasangan_tgl_kondisi_terakhir;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>						
      </tr>
      
      <!--  ---------------------- ANAK I ------------------------------------->
      <tr>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Anak I</td>
        <td><input type="text" id="jpn_anak1_nama" name="jpn_anak1_nama" value="<?=$ls_jpn_anak1_nama;?>" style="width:240px;" readonly class="disabled"></td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_anak1_nama_kondisi_terakhir" name="jpn_anak1_nama_kondisi_terakhir" value="<?=$ls_jpn_anak1_nama_kondisi_terakhir;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_anak1_kode_kondisi_terakhir" name="jpn_anak1_kode_kondisi_terakhir" value="<?=$ls_jpn_anak1_kode_kondisi_terakhir;?>">	
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_anak1_tgl_kondisi_terakhir" name="jpn_anak1_tgl_kondisi_terakhir" value="<?=$ld_jpn_anak1_tgl_kondisi_terakhir;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>							
      </tr>
      
      <!--  ---------------------- ANAK II ------------------------------------>							
      <tr>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Anak II</td>
        <td><input type="text" id="jpn_anak2_nama" name="jpn_anak2_nama" value="<?=$ls_jpn_anak2_nama;?>" style="width:240px;" readonly class="disabled"></td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_anak2_nama_kondisi_terakhir" name="jpn_anak2_nama_kondisi_terakhir" value="<?=$ls_jpn_anak2_nama_kondisi_terakhir;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_anak2_kode_kondisi_terakhir" name="jpn_anak2_kode_kondisi_terakhir" value="<?=$ls_jpn_anak2_kode_kondisi_terakhir;?>">	
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_anak2_tgl_kondisi_terakhir" name="jpn_anak2_tgl_kondisi_terakhir" value="<?=$ld_jpn_anak2_tgl_kondisi_terakhir;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>							
      </tr>
      
      <!--  ---------------------- ORANG TUA ---------------------------------->
      <tr>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">Orang Tua</td>
        <td><input type="text" id="jpn_ortu_nama" name="jpn_ortu_nama" value="<?=$ls_jpn_ortu_nama;?>" style="width:240px;" readonly class="disabled"></td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="text" id="jpn_ortu_nama_kondisi_terakhir" name="jpn_ortu_nama_kondisi_terakhir" value="<?=$ls_jpn_ortu_nama_kondisi_terakhir;?>" style="width:123px;text-align:center;" readonly class="disabled">
          <input type="hidden" id="jpn_ortu_kode_kondisi_terakhir" name="jpn_ortu_kode_kondisi_terakhir" value="<?=$ls_jpn_ortu_kode_kondisi_terakhir;?>">	
        </td>
        <td></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_ortu_tgl_kondisi_terakhir" name="jpn_ortu_tgl_kondisi_terakhir" value="<?=$ld_jpn_ortu_tgl_kondisi_terakhir;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>							
      </tr>
      <tr>
      	<td colspan="10"><hr style="border:0; border-top:1px double #8c8c8c;"/></td>	
      </tr>
      <tr>
        <td colspan="2"></td>	
        <td colspan="3" style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><i>Saat mencapai pensiun:</i></td>
        <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
        	<input type="text" id="jpn_tk_tgl_mulai_pensiun" name="jpn_tk_tgl_mulai_pensiun" value="<?=$ld_jpn_tk_tgl_mulai_pensiun;?>" style="width:90px;text-align:center;" readonly class="disabled">				
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>						
      </tr>				
      <!--  -------------------------- end ------------------------------------>																																										 
  	</tbody>	 
  </table>	
  </br>																		 	    																						  
</fieldset>

<fieldset><legend>Amalgamasi JP</legend>
	<table id="tblrincianjp2" width="95%" class="table-data2">
    <thead>	
      <tr class="hr-double-bottom">
        <th style="text-align:center;">No. Referensi</th>
        <th style="text-align:center;">NIK</th>
        <th style="text-align:center;">Nama TK</th>
        <th style="text-align:center;">Tempat Lahir</th>
        <th style="text-align:center;">Tgl Lahir</th>
        <th style="text-align:center;">Kode TK</th>
        <th style="text-align:center;">Kode TK AG</th>           		
      </tr>
    </thead>			
		<tbody>			
      <?	
      if ($_POST["INPUT_JPN_LISTAMALGAMASIJP"])
      {									         
        $ln_dtl =0;
        for($i=0;$i<ExtendedFunction::count($_POST["INPUT_JPN_LISTAMALGAMASIJP"]);$i++)
        {
          $ls_amgjp_kpj 						= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KPJ"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KPJ"];
					$ls_amgjp_nomor_identitas = $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["NOMOR_IDENTITAS"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["NOMOR_IDENTITAS"];
					$ls_amgjp_nama_tk 				= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["NAMA_TK"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["NAMA_TK"];
					$ls_amgjp_tempat_lahir 		= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["TEMPAT_LAHIR"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["TEMPAT_LAHIR"];
					$ls_amgjp_tgl_lahir 			= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["TGL_LAHIR"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["TGL_LAHIR"];
					$ls_amgjp_kode_tk 				= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KODE_TK"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KODE_TK"];
					$ls_amgjp_kode_tk_gabung 	= $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KODE_TK_GABUNG"] =="null" ? "" : $_POST["INPUT_JPN_LISTAMALGAMASIJP"][$i]["KODE_TK_GABUNG"];
					?>									
          <tr>				
            <td style="text-align:center;"><?=$ls_amgjp_kpj;?></td>
            <td style="text-align:center;"><?=$ls_amgjp_nomor_identitas;?></td>							
            <td style="text-align:center;"><?=$ls_amgjp_nama_tk;?></td>
            <td style="text-align:center;"><?=$ls_amgjp_tempat_lahir;?></td>
            <td style="text-align:center;"><?=$ls_amgjp_tgl_lahir;?></td>
            <td style="text-align:center;"><?=$ls_amgjp_kode_tk;?></td>
            <td style="text-align:center;"><?=$ls_amgjp_kode_tk_gabung;?></td>																																								
          </tr>
          <?				    							
          //$i++;//iterasi i				
        }	//end while
        $ln_dtl=$i;
        
        if ($i == 0) 
        {
          echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
          echo '<td colspan="7" style="text-align:center;"><font color="#ff0000">-- tidak ada perekaman data amalgamasi JP --</font></td>';
          echo '</tr>';
        }					
      }
      ?>																																				 
    </tbody>
		<tfoot>		
      <tr>
        <td colspan="7" style="text-align:right;">
          <input type="hidden" id="d_kounter_dtl_jp2" name="d_kounter_dtl_jp2" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_count_dtl_jp2" name="d_count_dtl_jp2" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_realdtl_showmessage_jp2" style="border-width: 0;text-align:right">				
        </td>									              
      </tr>
		</tfoot>																																												 							
  </table>
	</br>
</fieldset>

<fieldset><legend>Informasi Masa Iur, Bulan Kepesertaan dan Density Rate</legend>
	</br>													
  <table id="tblrincianjp3" width="95%" class="table-data2">
    <thead>	
      <tr class="hr-double-bottom">
        <th style="text-align:left;">No. Referensi</th>
        <th style="text-align:left;">PK/BU</th>
        <th style="text-align:left;">BLTH</th>
        <th style="text-align:left;">Tgl Bayar</th>
        <th style="text-align:right;">Upah</th>
        <th style="text-align:right;">Upah Terhitung</th>
        <th style="text-align:right;">Iuran JP</th>
        <th style="text-align:right;">Index Inflasi</th>
        <th style="text-align:right;">Upah Tertimbang</th> 
        <th style="text-align:right;">Iur htg Lumpsum</th>           		
      </tr>
    </thead>				
    <tbody>
      <?		
      if ($_POST["INPUT_JPN_LISTTKUPAH"])
      {										
        $ln_dtl =0;
        for($i=0;$i<ExtendedFunction::count($_POST["INPUT_JPN_LISTTKUPAH"]);$i++)
        {
          $ls_jpuptk_kpj 									= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["KPJ"] 								=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["KPJ"];
					$ls_jpuptk_nama_perusahaan 			= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NAMA_PERUSAHAAN"] 		=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NAMA_PERUSAHAAN"];
					$ls_jpuptk_blth 								= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["BLTH"] 								=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["BLTH"];
					$ld_jpuptk_tgl_bayar 						= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["TGL_BAYAR"] 					=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["TGL_BAYAR"];
					$ln_jpuptk_nom_upah 						= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH"] 						=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH"];
					$ln_jpuptk_nom_upah_terhitung 	= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH_TERHITUNG"] 	=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH_TERHITUNG"];
					$ln_jpuptk_nom_iuran_jp 				= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_IURAN_JP"] 				=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_IURAN_JP"];
					$ln_jpuptk_indeks_inflasi 			= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["INDEKS_INFLASI"] 			=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["INDEKS_INFLASI"];
					$ln_jpuptk_nom_upah_tertimbang 	= $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH_TERTIMBANG"] =="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_UPAH_TERTIMBANG"];
					$ln_jpuptk_nom_iuran_jp_lumpsum = $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_IURAN_JP_LUMPSUM"]=="null" ? "" : $_POST["INPUT_JPN_LISTTKUPAH"][$i]["NOM_IURAN_JP_LUMPSUM"];
					?>									
          <tr>			
            <td style="text-align:left;"><?=$ls_jpuptk_kpj;?></td>							
            <td style="text-align:left;"><?=$ls_jpuptk_nama_perusahaan;?></td>
            <td style="text-align:left;"><?=$ls_jpuptk_blth;?></td>
            <td style="text-align:left;"><?=$ld_jpuptk_tgl_bayar;?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_nom_upah,2,".",",");?></td>																
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_nom_upah_terhitung,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_nom_iuran_jp,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_indeks_inflasi,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_nom_upah_tertimbang,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_jpuptk_nom_iuran_jp_lumpsum,2,".",",");?></td>																								
          </tr>
          <?				    							
          //$i++;//iterasi i				
        }	//end while
        $ln_dtl=$i;
        
        if ($i == 0) 
        {
          echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
          echo '<td colspan="10" style="text-align:center;"><font color="#ff0000">-- tidak ada data Masa Iur, Bulan Kepesertaan dan Density Rate --</font></td>';
          echo '</tr>';
        }					
      }
      ?>																																				 
    </tbody>
    <?
    //ambil data kepesertaan	------------------------------------------------
    $ld_jp_tgl_awal_kepesertaan 	= $_POST["INPUT_JPN_DENSITYRATE"]["P_TGL_AWAL_KEPS"] 	=="null" ? "" : $_POST["INPUT_JPN_DENSITYRATE"]["P_TGL_AWAL_KEPS"];
    $ld_jp_tgl_akhir_kepesertaan 	= $_POST["INPUT_JPN_DENSITYRATE"]["P_TGL_AKHIR_KEPS"] =="null" ? "" : $_POST["INPUT_JPN_DENSITYRATE"]["P_TGL_AKHIR_KEPS"];
    $ln_jp_jml_bln_masa_keps 			= $_POST["INPUT_JPN_DENSITYRATE"]["P_CNT_BLN_KEPS"] 	=="null" ? "" : $_POST["INPUT_JPN_DENSITYRATE"]["P_CNT_BLN_KEPS"];
    $ln_jp_jml_bln_masa_iur 			= $_POST["INPUT_JPN_DENSITYRATE"]["P_CNT_BLN_IUR"] 		=="null" ? "" : $_POST["INPUT_JPN_DENSITYRATE"]["P_CNT_BLN_IUR"];
    $ln_jp_density_rate 					= $_POST["INPUT_JPN_DENSITYRATE"]["P_DENSITY_RATE"] 	=="null" ? "" : $_POST["INPUT_JPN_DENSITYRATE"]["P_DENSITY_RATE"];
    ?>
    <tfoot>
      <tr>
        <td style="text-align:center;" colspan="2"><i>Masa Kepesertaan :<i></td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$ld_jp_tgl_awal_kepesertaan;?> s/d <?=$ld_jp_tgl_akhir_kepesertaan;?> (<?=$ln_jp_jml_bln_masa_keps;?> Bulan)</td>								        
      </tr>	
      <tr>
        <td style="text-align:center;" colspan="2"><i>Masa Iur :<i></td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$ln_jp_jml_bln_masa_iur;?> Bulan</td>								        
      </tr>
      <tr>
        <td style="text-align:center;" colspan="2"><i>Density Rate :<i></td>	  									
        <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="8"><?=$ln_jp_density_rate;?> %</td>								        
      </tr>	
		</tfoot>																																																	 							
	</table>
	</br>
</fieldset>

<script language="javascript">
	function fl_js_jpn_ahliwaris_rinci(p_kode_klaim, p_no_urut_keluarga)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_ahliwarisdetil.php?kode_klaim='+p_kode_klaim+'&no_urut_keluarga='+p_no_urut_keluarga+'&mid='+c_mid+'','',980,610,'yes');
	}
</script>		

