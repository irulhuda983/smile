<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* =============================================================================
Ket : Form ini digunakan untuk tab Input Data Pengajuan Klaim JKK
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)		
			- 04/11/2019 : pengalihaan query sql ke webservice						 						 
------------------------------------------------------------------------------*/
$ls_tapjkk1_kode_jenis_kasus 		 = $_POST["KODE_JENIS_KASUS"]			=="null" ? "" : $_POST["KODE_JENIS_KASUS"];
$ls_tapjkk1_nama_jenis_kasus 		 = $_POST["NAMA_JENIS_KASUS"]			=="null" ? "" : $_POST["NAMA_JENIS_KASUS"];
$ls_tapjkk1_kode_tindakan_bahaya = $_POST["KODE_TINDAKAN_BAHAYA"]	=="null" ? "" : $_POST["KODE_TINDAKAN_BAHAYA"];
$ls_tapjkk1_nama_tindakan_bahaya = $_POST["NAMA_TINDAKAN_BAHAYA"]	=="null" ? "" : $_POST["NAMA_TINDAKAN_BAHAYA"];
$ls_tapjkk1_kode_kondisi_bahaya	 = $_POST["KODE_KONDISI_BAHAYA"]	=="null" ? "" : $_POST["KODE_KONDISI_BAHAYA"];
$ls_tapjkk1_nama_kondisi_bahaya	 = $_POST["NAMA_KONDISI_BAHAYA"]	=="null" ? "" : $_POST["NAMA_KONDISI_BAHAYA"];
$ls_tapjkk1_kode_corak					 = $_POST["KODE_CORAK"]						=="null" ? "" : $_POST["KODE_CORAK"];
$ls_tapjkk1_nama_corak					 = $_POST["NAMA_CORAK"]						=="null" ? "" : $_POST["NAMA_CORAK"];
$ls_tapjkk1_kode_sumber_cedera	 = $_POST["KODE_SUMBER_CEDERA"]		=="null" ? "" : $_POST["KODE_SUMBER_CEDERA"];
$ls_tapjkk1_nama_sumber_cedera	 = $_POST["NAMA_SUMBER_CEDERA"]		=="null" ? "" : $_POST["NAMA_SUMBER_CEDERA"];
$ls_tapjkk1_kode_bagian_sakit		 = $_POST["KODE_BAGIAN_SAKIT"]		=="null" ? "" : $_POST["KODE_BAGIAN_SAKIT"];
$ls_tapjkk1_nama_bagian_sakit		 = $_POST["NAMA_BAGIAN_SAKIT"]		=="null" ? "" : $_POST["NAMA_BAGIAN_SAKIT"];
$ls_tapjkk1_kode_akibat_diderita = $_POST["KODE_AKIBAT_DIDERITA"]	=="null" ? "" : $_POST["KODE_AKIBAT_DIDERITA"];
$ls_tapjkk1_nama_akibat_diderita = $_POST["NAMA_AKIBAT_DIDERITA"]	=="null" ? "" : $_POST["NAMA_AKIBAT_DIDERITA"];		
$ls_tapjkk1_kode_lama_bekerja 	 = $_POST["KODE_LAMA_BEKERJA"]		=="null" ? "" : $_POST["KODE_LAMA_BEKERJA"];
$ls_tapjkk1_nama_lama_bekerja 	 = $_POST["NAMA_LAMA_BEKERJA"]		=="null" ? "" : $_POST["NAMA_LAMA_BEKERJA"];
$ls_tapjkk1_kode_penyakit_timbul = $_POST["KODE_PENYAKIT_TIMBUL"]	=="null" ? "" : $_POST["KODE_PENYAKIT_TIMBUL"];
$ls_tapjkk1_nama_penyakit_timbul = $_POST["NAMA_PENYAKIT_TIMBUL"]	=="null" ? "" : $_POST["NAMA_PENYAKIT_TIMBUL"];	
$ls_nama_tk											 = $_POST["NAMA_TK"]							=="null" ? "" : $_POST["NAMA_TK"];	
$ls_kode_segmen									 = $_POST["KODE_SEGMEN"]					=="null" ? "" : $_POST["KODE_SEGMEN"];
$ls_kode_klaim									 = $_POST["KODE_KLAIM"]						=="null" ? "" : $_POST["KODE_KLAIM"];
//------------------------------------------------------------------------------
?>

<div class="div-row" >
  <div class="div-col" style="width:49%; max-height: 100%;">
  	<input type="hidden" id="tapjkk1_kode_jenis_kasus" name="tapjkk1_kode_jenis_kasus" value="<?=$ls_tapjkk1_kode_jenis_kasus;?>">
		<input type="hidden" id="tapjkk1_kode_segmen" name="tapjkk1_kode_segmen" value="<?=$ls_kode_segmen;?>">	
		<input type="hidden" id="tapjkk1_kode_klaim" name="tapjkk1_kode_klaim" value="<?=$ls_kode_klaim;?>">
		
		<fieldset><legend>Informasi Pengajuan Klaim JKK Tahap I &nbsp;<?=$ls_nama_tk;?></legend>
      </br></br>				
      <span id="span_kecelakaan_kerja" style="display:none;">						
        <div class="form-row_kiri">
        <label style = "text-align:right;">Tindakan Bahaya &nbsp;</label>		 	    				
          <input type="text" id="tapjkk1_nama_tindakan_bahaya" name="tapjkk1_nama_tindakan_bahaya" value="<?=$ls_tapjkk1_nama_tindakan_bahaya;?>" style="width:320px;" readonly class="disabled"> 
          <input type="hidden" id="tapjkk1_kode_tindakan_bahaya" name="tapjkk1_kode_tindakan_bahaya" value="<?=$ls_tapjkk1_kode_tindakan_bahaya;?>">
        </div>																																										
        <div class="clear"></div>							
        
        <div class="form-row_kiri">
        <label style = "text-align:right;">Kondisi Bahaya &nbsp;</label>
          <input type="text" id="tapjkk1_nama_kondisi_bahaya" name="tapjkk1_nama_kondisi_bahaya" value="<?=$ls_tapjkk1_nama_kondisi_bahaya;?>" style="width:320px;" readonly class="disabled"> 
          <input type="hidden" id="tapjkk1_kode_kondisi_bahaya" name="tapjkk1_kode_kondisi_bahaya" value="<?=$ls_tapjkk1_kode_kondisi_bahaya;?>">
        </div>																																										
        <div class="clear"></div>
        
        </br>
        <div class="form-row_kiri">
        <label style = "text-align:right;">Corak &nbsp;</label>
          <input type="text" id="tapjkk1_kode_corak" name="tapjkk1_kode_corak" value="<?=$ls_tapjkk1_kode_corak;?>" style="width:40px;" readonly class="disabled"> 
          <input type="text" id="tapjkk1_nama_corak" name="tapjkk1_nama_corak" value="<?=$ls_tapjkk1_nama_corak;?>" style="width:270px;" readonly class="disabled">													
        </div>																																										
        <div class="clear"></div>
        
        <div class="form-row_kiri">
        <label style = "text-align:right;">Sumber Cedera &nbsp;</label>
          <input type="text" id="tapjkk1_kode_sumber_cedera" name="tapjkk1_kode_sumber_cedera" value="<?=$ls_tapjkk1_kode_sumber_cedera;?>" style="width:40px;" readonly class="disabled"> 
          <input type="text" id="tapjkk1_nama_sumber_cedera" name="tapjkk1_nama_sumber_cedera" value="<?=$ls_tapjkk1_nama_sumber_cedera;?>" style="width:270px;" readonly class="disabled">													
        </div>																																										
        <div class="clear"></div>
        
        <div class="form-row_kiri">
        <label style = "text-align:right;">Bagian yang Sakit &nbsp;</label>
          <input type="text" id="tapjkk1_kode_bagian_sakit" name="tapjkk1_kode_bagian_sakit" value="<?=$ls_tapjkk1_kode_bagian_sakit;?>" style="width:40px;" readonly class="disabled"> 
          <input type="text" id="tapjkk1_nama_bagian_sakit" name="tapjkk1_nama_bagian_sakit" value="<?=$ls_tapjkk1_nama_bagian_sakit;?>" style="width:270px;" readonly class="disabled">												
        </div>																																										
        <div class="clear"></div>
        
        <div class="form-row_kiri">
        <label style = "text-align:right;">Akibat yg Diderita *</label>
          <input type="text" id="tapjkk1_kode_akibat_diderita" name="tapjkk1_kode_akibat_diderita" value="<?=$ls_tapjkk1_kode_akibat_diderita;?>" style="width:40px;" readonly class="disabled">
          <input type="text" id="tapjkk1_nama_akibat_diderita" name="tapjkk1_nama_akibat_diderita" value="<?=$ls_tapjkk1_nama_akibat_diderita;?>" style="width:250px;" readonly class="disabled">										
        </div>																																										
        <div class="clear"></div>																																																	
      </span>	<!-- end span "span_kecelakaan_kerja" -->	
      
      <span id="span_penyakit_akibat_kerja" style="display:none;">
        </br></br>		
        <div class="form-row_kiri">
        <label style = "text-align:right;">Lama Bekerja (Thn) &nbsp;</label>
          <input type="text" id="tapjkk1_nama_lama_bekerja" name="tapjkk1_nama_lama_bekerja" value="<?=$ls_tapjkk1_nama_lama_bekerja;?>" style="width:320px;" readonly class="disabled"> 
          <input type="hidden" id="tapjkk1_kode_lama_bekerja" name="tapjkk1_kode_lama_bekerja" value="<?=$ls_tapjkk1_kode_lama_bekerja;?>">
        </div>																																										
        <div class="clear"></div>								
        
        <div class="form-row_kiri">
        <label style = "text-align:right;">Penyakit yg Timbul &nbsp;</label>
          <input type="text" id="tapjkk1_kode_penyakit_timbul" name="tapjkk1_kode_penyakit_timbul" value="<?=$ls_tapjkk1_kode_penyakit_timbul;?>" style="width:40px;" readonly class="disabled">
          <input type="text" id="tapjkk1_nama_penyakit_timbul" name="tapjkk1_nama_penyakit_timbul" value="<?=$ls_tapjkk1_nama_penyakit_timbul;?>" style="width:270px;" readonly class="disabled">													
        </div>																																										
        <div class="clear"></div>										
      </span>						
    </fieldset>	
  </div>
	
	<div class="div-col" style="width:1%;">
  </div>
	
  <div class="div-col-right" style="width:50%;">
    <div class="div-row" >
      <div class="div-col" style="width:100%; max-height: 100%;">
        <?
        $ls_tapjkk1_kode_tempat_perawatan = $_POST["KODE_TEMPAT_PERAWATAN"]			=="null" ? "" : $_POST["KODE_TEMPAT_PERAWATAN"];
				$ls_tapjkk1_nama_tempat_perawatan = $_POST["NAMA_TEMPAT_PERAWATAN"]			=="null" ? "" : $_POST["NAMA_TEMPAT_PERAWATAN"];
        $ls_tapjkk1_kode_berobat_jalan		= $_POST["KODE_BEROBAT_JALAN"]				=="null" ? "" : $_POST["KODE_BEROBAT_JALAN"];
				$ls_tapjkk1_nama_berobat_jalan		= $_POST["NAMA_BEROBAT_JALAN"]				=="null" ? "" : $_POST["NAMA_BEROBAT_JALAN"];
        $ls_tapjkk1_kode_ppk							= $_POST["KODE_PPK"]									=="null" ? "" : $_POST["KODE_PPK"];
        $ls_tapjkk1_nama_ppk							= $_POST["NAMA_PPK"]									=="null" ? "" : $_POST["NAMA_PPK"];
        $ls_tapjkk1_nama_faskes_reimburse	= $_POST["NAMA_FASKES_REIMBURSE"]			=="null" ? "" : $_POST["NAMA_FASKES_REIMBURSE"];												 
        ?>			
        <fieldset>
          <legend>Tempat Tenaga Kerja Dirawat</legend>
            <div class="div-row">
              </br>				
              <div class="form-row_kiri">
              <label style = "text-align:right;">Tempat Perawatan &nbsp;</label>		 	    				
                <input type="text" id="tapjkk1_nama_tempat_perawatan" name="tapjkk1_nama_tempat_perawatan" value="<?=$ls_tapjkk1_nama_tempat_perawatan;?>" style="width:320px;" readonly class="disabled"> 
          			<input type="hidden" id="tapjkk1_kode_tempat_perawatan" name="tapjkk1_kode_tempat_perawatan" value="<?=$ls_tapjkk1_kode_tempat_perawatan;?>">
              </div>																																											
              <div class="clear"></div>
              
              <div class="form-row_kiri">
              <label style = "text-align:right;">Berobat Jalan &nbsp;</label>		 	    				
                <input type="text" id="tapjkk1_nama_berobat_jalan" name="tapjkk1_nama_berobat_jalan" value="<?=$ls_tapjkk1_nama_berobat_jalan;?>" style="width:320px;" readonly class="disabled"> 
          			<input type="hidden" id="tapjkk1_kode_berobat_jalan" name="tapjkk1_kode_berobat_jalan" value="<?=$ls_tapjkk1_kode_berobat_jalan;?>">	
              </div>																																												
              <div class="clear"></div>			
              
              <span id="span_faskes" style="display:none;">
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Fasilitas Kesehatan</label>
                  <input type="text" id="tapjkk1_kode_ppk" name="tapjkk1_kode_ppk" value="<?=$ls_tapjkk1_kode_ppk;?>" style="width:65px;" readonly class="disabled">
                  <input type="text" id="tapjkk1_nama_ppk" name="tapjkk1_nama_ppk" value="<?=$ls_tapjkk1_nama_ppk;?>" style="width:246px;" readonly class="disabled">
                </div>	
                <div class="clear"></div>
              </span>
              
              <span id="span_reimburse" style="display:none;">						
                <div class="form-row_kiri">
                <label style = "text-align:right;">Reimburse</label>		 	    				
                	<input type="text" id="tapjkk1_nama_faskes_reimburse" name="tapjkk1_nama_faskes_reimburse" value="<?=$ls_tapjkk1_nama_faskes_reimburse;?>" style="width:320px;" readonly class="disabled">
                </div>																																														
                <div class="clear"></div>
              </span>             
            </div>
				</fieldset>	    	
    	</div>
    </div>	

    <div class="div-row" >
      <div class="div-col" style="width:100%; max-height: 100%;">
        <?
        $ls_tapjkk1_kode_tipe_upah 		 = $_POST["KODE_TIPE_UPAH"]								=="null" ? "" : $_POST["KODE_TIPE_UPAH"];
				$ls_tapjkk1_nama_tipe_upah 		 = $_POST["NAMA_TIPE_UPAH"]								=="null" ? "" : $_POST["NAMA_TIPE_UPAH"];
        $ln_tapjkk1_nom_upah_terakhir  = $_POST["NOM_UPAH_TERAKHIR"]						=="null" ? "" : $_POST["NOM_UPAH_TERAKHIR"];
        $ld_tapjkk1_blth_upah_terakhir = $_POST["BLTH_UPAH_TERAKHIR"]						=="null" ? "" : $_POST["BLTH_UPAH_TERAKHIR"];
        ?>        
        <span id="tapjkk1_span_upah_tk" style="display:none;">
          <fieldset style="height:90px;"><legend><b><i><font color="#009999">Upah yang Diterima oleh Tenaga Kerja</font></i></b></legend>								 										 
            </br>
						
            <div class="form-row_kiri">
            <label style = "text-align:right;">Tipe Upah &nbsp;</label>		 	    				
              <input type="text" id="tapjkk1_nama_tipe_upah" name="tapjkk1_nama_tipe_upah" value="<?=$ls_tapjkk1_nama_tipe_upah;?>" style="width:250px;" readonly class="disabled"> 
          		<input type="hidden" id="tapjkk1_kode_tipe_upah" name="tapjkk1_kode_tipe_upah" value="<?=$ls_tapjkk1_kode_tipe_upah;?>">
            </div>																																											
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Upah Terakhir&nbsp; *</label>		 	    				
              <input type="text" id="tapjkk1_nom_upah_terakhir" name="tapjkk1_nom_upah_terakhir" value="<?=number_format((float)$ln_tapjkk1_nom_upah_terakhir,2,".",",");?>" style="width:250px;" onblur="this.value=format_uang(this.value);" readonly class="disabled">
              <input type="hidden" id="tapjkk1_blth_upah_terakhir" name="tapjkk1_blth_upah_terakhir" value="<?=$ld_tapjkk1_blth_upah_terakhir;?>">
            </div>																																											
            <div class="clear"></div>
            
            <?
            if ($ls_kode_segmen=="JAKON")
            {
             	?>
              <div class="form-row_kiri">
              <label style = "text-align:right;">&nbsp;</label>		 	    				
              	<i><font color="#009999">(* Utk Jakon Upah Bulanan = Upah Harian x 25 hari</font></i>		
              </div>																																											
              <div class="clear"></div>		
            	<?
            }
            ?>								  
        	</fieldset>
        </span>		    	
    	</div>
    </div>	
					 
  </div>
</div>
		
<div class="div-row" >
  <div class="div-col" style="width:100%; max-height: 100%;">
		<fieldset><legend>Diagnosa</legend>						  	
			</br>	
			<table id="tblrincian_diag" width="100%" class="table-data2">
				<thead>												
          <tr class="hr-double-bottom">
            <th>Nama Tenaga Medis</th>
            <th>Detil Diagnosa</th>
            <th>Keterangan</th>			    							
          </tr>
        </thead>
        <tbody id="data_list_diagnosa">
          <tr class="nohover-color">
          	<td colspan="3" style="text-align: center;">-- tidak ada data diagnosa --</td>
          </tr>
        </tbody>
				<tfoot>
          <tr>
            <td colspan="3"><input type="hidden" id="tapjkk1_dtldiag_kounter_dtl" name="tapjkk1_dtldiag_kounter_dtl" value="<?=$ln_dtl;?>">
            <input type="hidden" id="tapjkk1_dtldiag_count_dtl" name="tapjkk1_dtldiag_count_dtl" value="<?=$ln_countdtl;?>">
            <input type="hidden" name="tapjkk1_dtldiag_showmessage" style="border-width: 0;text-align:right" readonly size="30">
            </td>									              
          </tr>
				</tfoot>												 							
      </table>
			
			</br>																						  
    </fieldset>		
	</div>
</div>		

<div class="div-row" >
  <div class="div-col" style="width:100%; max-height: 100%;">
    <fieldset><legend>Aktivitas Pelaporan</legend>						 	
			</br>	
			<table id="tblrincian_aktlap" width="100%" class="table-data2">
				<thead>												
          <tr class="hr-double-bottom">
            <th>Tgl</th>
            <th>Aktivitas</th>
            <th>Narasumber</th>
            <th>Profesi</th>
            <th>Alamat</th>
            <th>No Telp</th>
            <th>Ket</th>									    							
          </tr>
        </thead>
				<tbody id="data_list_aktivitaslap">
          <tr class="nohover-color">
          	<td colspan="7" style="text-align: center;">-- tidak ada data aktivitas pelaporan --</td>
          </tr>
        </tbody>
				<tfoot>
          <tr>
            <td colspan="7">
              <input type="hidden" id="tapjkk1_dtlaktvpelap_kounter_dtl" name="tapjkk1_dtlaktvpelap_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="tapjkk1_dtlaktvpelap_count_dtl" name="tapjkk1_dtlaktvpelap_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="tapjkk1_dtlaktvpelap_showmessage" style="border-width: 0;text-align:right" readonly size="30">		
            </td>									              
          </tr>
				</tfoot>												 							
      </table> 
			
			</br> 																						  
    </fieldset>		
	</div>
</div>

<script language="javascript">
	//set span by jenis kasus ----------------------------------------------------			
  function fl_js_tapjkk1_kode_jenis_kasus() 
  { 
  	var v_kode_jenis_kasus = window.document.getElementById('tapjkk1_kode_jenis_kasus').value;
  	
  	if (v_kode_jenis_kasus =="KS01") //kecelakaan kerja --------------------------
    {
      window.document.getElementById("span_kecelakaan_kerja").style.display = 'block';
      window.document.getElementById("span_penyakit_akibat_kerja").style.display = 'none';	
    }else if (v_kode_jenis_kasus =="KS02") //penyakit akibat kerja ---------------
    {
      window.document.getElementById("span_kecelakaan_kerja").style.display = 'none';
      window.document.getElementById("span_penyakit_akibat_kerja").style.display = 'block';
    } 	
  }
	//end set span by jenis kasus ------------------------------------------------	
	
  function fl_js_tapjkk1_set_field_upahtk() 
  { 
    var v_kode_segmen = window.document.getElementById('tapjkk1_kode_segmen').value;
		
		if (v_kode_segmen =="TKI") //TKI tidak ada input upah --------------
    {    
      window.document.getElementById("tapjkk1_span_upah_tk").style.display = 'none';							
    }else // selain TKI ----------------
    {
     	window.document.getElementById("tapjkk1_span_upah_tk").style.display = 'block';
    } 	
  }	

  function fl_js_tapjkk1_kode_tempat_perawatan() 
  { 
    var v_tempat_perawatan = window.document.getElementById('tapjkk1_kode_tempat_perawatan').value;

    if (v_tempat_perawatan =="TR01") //faskes/trauma center
    {
      window.document.getElementById("span_faskes").style.display = 'block';
      window.document.getElementById("span_reimburse").style.display = 'none';
    }else
    {
      window.document.getElementById("span_reimburse").style.display = 'block';
      window.document.getElementById("span_faskes").style.display = 'none';  									 		 
    } 	
  }
												
	//function load data diagnosa dan aktivitas pelaporan ------------------------
	function fl_js_tapjkk1_loaddt_diagnosa_aktivlap(v_kode_klaim, fn)
	{
		if (v_kode_klaim == '') {
			return alert('Kode Klaim tidak boleh kosong');
		}

		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatadiagnosajkkbykodeklaim',
				v_kode_klaim: v_kode_klaim
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
						//------------------------ diagnosa --------------------------------
  					if (jdata.dataDiagnosa.DATA)
  					{ 
  						var html_data_diag = '';
							for(var i = 0; i < jdata.dataDiagnosa.DATA.length; i++)
  						{								
                html_data_diag += '<tr>';
                html_data_diag += '<td style="text-align: left;">' + getValue(jdata.dataDiagnosa.DATA[i].NAMA_TENAGA_MEDIS) + '</td>';
                html_data_diag += '<td style="text-align: left;">' + getValue(jdata.dataDiagnosa.DATA[i].NAMA_DIAGNOSA_DETIL) + '</td>';
                html_data_diag += '<td style="text-align: left;">' + getValue(jdata.dataDiagnosa.DATA[i].KETERANGAN) + '</td>';
                html_data_diag += '<tr>';						
							}
    															
              if (html_data_diag == "") {
                html_data_diag += '<tr class="nohover-color">';
                html_data_diag += '<td colspan="3" style="text-align: center;">-- tidak ada data data diagnosa --</td>';
                html_data_diag += '</tr>';
              }
              $("#data_list_diagnosa").html(html_data_diag);
  					}
						//----------------------- end diagnosa -----------------------------

						//------------------------ aktivitas pelaporan ---------------------
  					if (jdata.dataAktivLap.DATA)
  					{ 
  						var html_data_aktv = '';
							for(var i = 0; i < jdata.dataAktivLap.DATA.length; i++)
  						{								
                html_data_aktv += '<tr>';
                html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].TGL_AKTIVITAS) + '</td>';
                html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].NAMA_AKTIVITAS) + '</td>';
                html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].NAMA_SUMBER) + '</td>';
								html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].PROFESI_SUMBER) + '</td>';
								html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].ALAMAT) + '</td>';
								html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].TELEPON_AREA)+'-'+ getValue(jdata.dataAktivLap.DATA[i].TELEPON)+ '</td>';
								html_data_aktv += '<td style="text-align: left;">' + getValue(jdata.dataAktivLap.DATA[i].KETERANGAN) + '</td>';
                html_data_aktv += '<tr>';						
							}
							
              if (html_data_aktv == "") {
                html_data_aktv += '<tr class="nohover-color">';
                html_data_aktv += '<td colspan="7" style="text-align: center;">-- tidak ada data aktivitas pelaporan --</td>';
                html_data_aktv += '</tr>';
              }
              $("#data_list_aktivitaslap").html(html_data_aktv);
  					}
						//--------------------- end aktivitas pelaporan --------------------																											
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});						
	}
	//end function load data diagnosa dan aktivitas pelaporan --------------------		
	
  $(document).ready(function() {
  	fl_js_tapjkk1_kode_jenis_kasus();
		fl_js_tapjkk1_set_field_upahtk();
		fl_js_tapjkk1_kode_tempat_perawatan();
		
		let v_kode_klaim = $('#tapjkk1_kode_klaim').val();
		fl_js_tapjkk1_loaddt_diagnosa_aktivlap(v_kode_klaim, null);												 		
  });			
</script>
	