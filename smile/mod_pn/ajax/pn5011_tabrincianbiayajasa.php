<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Rincian Biaya Promotif/Preventif
Hist: - 11/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<script language="javascript">
  function fl_js_lov_d_realdtl_promotif(i)
  {		 	
    var d_realdtl_kode_promotif 	= 'd_realdtl_kode_promotif'+i;
    var d_realdtl_kode_perusahaan = 'd_realdtl_kode_perusahaan'+i;
    var d_realdtl_nama_perusahaan = 'd_realdtl_nama_perusahaan'+i;
    var d_realdtl_bentuk_kegiatan = 'd_realdtl_bentuk_kegiatan'+i;
    var v_realdtl_kode_promotif 	= document.getElementById(d_realdtl_kode_promotif).value;
    var v_realdtl_kode_perusahaan = document.getElementById(d_realdtl_kode_perusahaan).value;	
    var v_realdtl_nama_perusahaan	= document.getElementById(d_realdtl_nama_perusahaan).value;	
    var v_kode_promotif_header 		= document.getElementById('kode_promotif').value;
    var v_kode_kegiatan_header 		= document.getElementById('kode_kegiatan').value;
		var v_bentuk_kegiatan_header 	= document.getElementById('bentuk_kegiatan').value;
    
    bkwindow("../ajax/pn5011_lov_dtlkodepromotif.php?p=pn5011.php&a=formreg&b=d_realdtl_kode_promotif"+i+"&c=d_realdtl_kode_perusahaan"+i+"&d=d_realdtl_nama_perusahaan"+i+"&e="+v_kode_promotif_header+"&f="+v_kode_kegiatan_header+"&g=d_realdtl_bentuk_kegiatan"+i+"&h="+v_bentuk_kegiatan_header+"","",800,500,1);		 
  }

  function fl_js_val_d_realdtl_kodetk(i)
  {		 	
    var d_realdtl_kode_promotif 	= 'd_realdtl_kode_promotif'+i;
    var v_realdtl_kode_promotif 	= document.getElementById(d_realdtl_kode_promotif).value;
    
    bkwindow("../ajax/pn5011_lov_dtlkodetk.php?p=pn5011.php&a=formreg&b=d_realdtl_kode_tk"+i+"&c=d_realdtl_kpj"+i+"&d=d_realdtl_nama_tk"+i+"&e="+v_realdtl_kode_promotif+"","",800,500,1);		 
  }
	
  function fl_js_d_realdtl_hitung_nom_diajukan(i)
  {		
    var v_realdtl_nom_pokok 			= parseFloat(removeCommas(document.getElementById('d_realdtl_nom_pokok'+i).value),2);
    var v_realdtl_nom_ppn 				= parseFloat(removeCommas(document.getElementById('d_realdtl_nom_ppn'+i).value),2);
		var v_nom_diajukan;
		
		if (isNaN(v_realdtl_nom_pokok))
			 v_realdtl_nom_pokok = 0;
			 
		if (isNaN(v_realdtl_nom_ppn))
			 v_realdtl_nom_ppn = 0;
			 
		v_nom_diajukan = (v_realdtl_nom_pokok + v_realdtl_nom_ppn);
			 
		window.document.getElementById('d_realdtl_nom_diajukan'+i).value = format_uang(v_nom_diajukan);	
			 	
		fl_js_d_realdtl_hitung_total();						 
  }	
		
	function fl_js_d_realdtl_hitung_total()
	{
     	var form = document.formreg;
    	var n_nom_pokok, n_nom_ppn, n_nom_diajukan,n_tot_pokok, n_tot_ppn, n_tot_diajukan;
    	var tbl = document.getElementById('tblrincian2');
    	var lastRow = tbl.rows.length;
  
    	var ln_dtl = parseFloat(document.getElementById('d_realdtl_kounter_dtl').value);
  		
  		n_tot_pokok = 0;
			n_tot_ppn = 0;
			n_tot_diajukan = 0;		  	
    	for (i=0; i<=parseFloat(lastRow-3); i++) 
    	{
    		//hitung total pokok ---------------------------------------------------
				if (document.getElementById('d_realdtl_nom_pokok'+i))
    		{						
  				n_nom_pokok = parseFloat(removeCommas(document.getElementById('d_realdtl_nom_pokok'+i).value),2);				
										
      		if (isNaN(n_nom_pokok)||n_nom_pokok=="")
      			n_nom_pokok = 0;
     
      		n_tot_pokok += parseFloat(n_nom_pokok);
      		document.getElementById('d_realdtl_tot_pokok').value = format_uang(n_tot_pokok);
    		}
				     
				//hitung total ppn -----------------------------------------------------
    		if (document.getElementById('d_realdtl_nom_ppn'+i))
    		{						
  				n_nom_ppn = parseFloat(removeCommas(document.getElementById('d_realdtl_nom_ppn'+i).value),2);				
  																	
      		if (isNaN(n_nom_ppn)||n_nom_ppn=="")
      			n_nom_ppn = 0;
     
      		n_tot_ppn += parseFloat(n_nom_ppn);
      		document.getElementById('d_realdtl_tot_ppn').value = format_uang(n_tot_ppn);
    		}		
							
    		//hitung total diajukan -----------------------------------------------------
    		if (document.getElementById('d_realdtl_nom_diajukan'+i))
    		{						
  				n_nom_diajukan = 0;					
					n_nom_diajukan = parseFloat(removeCommas(document.getElementById('d_realdtl_nom_diajukan'+i).value),2);				
  																	
      		if (isNaN(n_nom_diajukan)||n_nom_diajukan=="")
      			n_nom_diajukan = 0;

      		n_tot_diajukan += parseFloat(n_nom_diajukan);
      		document.getElementById('d_realdtl_tot_diajukan').value = format_uang(n_tot_diajukan);
    		}				
    	}	//end loop
	}
			
  function addRowInnerHTML2(tblId)
  {
    var form = document.adminForm;
    var tblBody = document.getElementById('tblrincian2').tBodies[0];
    var lastRow = tblBody.rows.length;
    var iteration = lastRow;
    
    var iteration = parseFloat(window.document.getElementById('d_realdtl_kounter_dtl').value);
    
    //create cell baru
    document.getElementById('d_realdtl_kounter_dtl').value = iteration+1;
    var newRow = tblBody.insertRow(-1);
    var newCell0 = newRow.insertCell(0);
    var newCell1 = newRow.insertCell(1);
    var newCell2 = newRow.insertCell(2);
    var newCell3 = newRow.insertCell(3);
    var newCell4 = newRow.insertCell(4);
    var newCell5 = newRow.insertCell(5);
		var newCell6 = newRow.insertCell(6);
		var newCell7 = newRow.insertCell(7);
    
    newCell0.innerHTML = '<input type="text" readonly class=disabled id='+'d_realdtl_no_realisasi'+iteration+' name='+'d_realdtl_no_realisasi'+iteration+' size="2" maxlength="5" style="border-width: 1;text-align:left">';
    newCell1.innerHTML = '<input type="text" id='+'d_realdtl_nama_perusahaan'+iteration+' name='+'d_realdtl_nama_perusahaan'+iteration+' readonly class=\'disabled\' size="15"><input type="hidden" id='+'d_realdtl_kode_promotif'+iteration+' name='+'d_realdtl_kode_promotif'+iteration+'><input type="hidden" id='+'d_realdtl_kode_perusahaan'+iteration+' name='+'d_realdtl_kode_perusahaan'+iteration+'>&nbsp;<a href="#" onclick="fl_js_lov_d_realdtl_promotif('+iteration+');"><img src="../../images/help.png" alt="Cari Data Perusahaan" border="0" align="absmiddle"></a><input type="hidden" readonly id='+'d_realdtl_bentuk_kegiatan'+iteration+' name='+'d_realdtl_bentuk_kegiatan'+iteration+' size="7" style="border-width: 1;text-align:left;" maxlength="30">';
    newCell2.innerHTML = '<input type="text" id='+'d_realdtl_nama_tk'+iteration+' name='+'d_realdtl_nama_tk'+iteration+' readonly class=\'disabled\' size="15">&nbsp;<a href="#" onclick="fl_js_val_d_realdtl_kodetk('+iteration+');"><img src="../../images/help.png" alt="Cari Data TK" border="0" align="absmiddle"></a><input type="hidden" id='+'d_realdtl_kode_tk'+iteration+' name='+'d_realdtl_kode_tk'+iteration+'><input type="hidden" id='+'d_realdtl_kpj'+iteration+' name='+'d_realdtl_kpj'+iteration+'><input type="hidden" id='+'d_realdtl_nama_jenis_barang'+iteration+' name='+'d_realdtl_nama_jenis_barang'+iteration+'><input type="hidden" id='+'d_realdtl_kode_jenis_barang'+iteration+' name='+'d_realdtl_kode_jenis_barang'+iteration+'><input type="hidden" id='+'d_realdtl_nama_barang'+iteration+' name='+'d_realdtl_nama_barang'+iteration+'><input type="hidden" id='+'d_realdtl_jml_paket_barang'+iteration+' name='+'d_realdtl_jml_paket_barang'+iteration+'><input type="hidden" id='+'d_realdtl_harga_perpaket_barang'+iteration+' name='+'d_realdtl_harga_perpaket_barang'+iteration+'>';		
		newCell3.innerHTML = '<input type="text" id='+'d_realdtl_nom_pokok'+iteration+' name='+'d_realdtl_nom_pokok'+iteration+' size="12" maxlength="20" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value);fl_js_d_realdtl_hitung_nom_diajukan('+iteration+');">';
		newCell4.innerHTML = '<input type="text" id='+'d_realdtl_nom_ppn'+iteration+' name='+'d_realdtl_nom_ppn'+iteration+' size="10" maxlength="20" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value);fl_js_d_realdtl_hitung_nom_diajukan('+iteration+');">';
		newCell5.innerHTML = '<input type="text" readonly class=disabled id='+'d_realdtl_nom_diajukan'+iteration+' name='+'d_realdtl_nom_diajukan'+iteration+' size="12" maxlength="20" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value);">';
    newCell6.innerHTML = '<input type="text" id='+'d_realdtl_keterangan'+iteration+' name='+'d_realdtl_keterangan'+iteration+' size="10" maxlength="300" style="border-width: 1;text-align:left">'; 
    newCell7.innerHTML = '<a href="#" onclick="removeperbrs2(this.parentNode.parentNode.rowIndex)" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Delete</a>';		
  }
  
  function deleteRow2(i)
  {
    alert(i);
    document.getElementById('tblrincian2').deleteRow(i);
  }
  
  function removeperbrs2(obj)
  {
    if (confirm("Anda Yakin untuk menghapus baris ini?")) 
    { 
    	document.getElementById('tblrincian2').deleteRow(obj);
			fl_js_d_realdtl_hitung_total();
    }else
    {
      //alert('nda yakin bah');
    }
  }

					
</script>
<div id="formKiri" style="width:900px;">												 
  <table id="tblrincian2" width="85%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
    <tbody>
      <tr>
      	<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
      </tr>	
      <tr>
        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Perusahaan</th>                   
        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tenaga Kerja</th>
				<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Biaya Pokok</th>
				<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">PPN</th>
				<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Biaya Diajukan</th>
        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ket</th>
				<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>					
      </tr>
      <tr>
      	<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      </tr>
      <?
      if ($ln_dtl=="")
      {
       	 $ln_dtl = "0";
      }		
      if ($ls_kode_realisasi!="")
      {									
        $sql = "select 
                   a.kode_realisasi, a.no_realisasi, a.kode_promotif, a.kode_perusahaan,
									 (select nama_perusahaan from sijstk.pn_promotif where kode_promotif = a.kode_promotif) nama_perusahaan,
                   a.bentuk_kegiatan, a.kode_tk,
                   (select nama_tk from sijstk.pn_promotif_detil where kode_promotif = a.kode_promotif and kode_tk = a.kode_tk) nama_tk,
                   a.kode_jenis_barang, 
                   ( select keterangan from sijstk.ms_lookup 
                     where tipe = decode(b.kode_kegiatan,'PROMOTIF','JBRGPROM','PREVENTIF','JBRGPREV',b.kode_kegiatan)
                     and kode = a.kode_jenis_barang
                    ) nama_jenis_barang,     
                   a.nama_barang, a.jml_paket_barang, 
                   a.harga_perpaket_barang, a.nom_pokok, a.nom_ppn, 
                   a.nom_diajukan, a.keterangan
                from pn.pn_promotif_realisasi_detil a, sijstk.pn_promotif_realisasi b
                where a.kode_realisasi = b.kode_realisasi
                and a.kode_realisasi = '$ls_kode_realisasi'
                order by a.no_realisasi";
        $DB->parse($sql);
        $DB->execute();							              					
        $i=0;		
        $ln_dtl =0;										
				//$ln_tot_pokok  = 0;
				//$ln_tot_ppn  	 = 0;
				$ln_tot_diajukan  = 0;
        while ($row = $DB->nextrow())
        {
          ?>											
          	<?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
            <td>
            	<input type="text" id="d_realdtl_no_realisasi<?=$i;?>" name="d_realdtl_no_realisasi<?=$i;?>" size="2" maxlength="5" value="<?=$row['NO_REALISASI'];?>" style="border-width: 1;text-align:left" readonly class="disabled"></td> 
            </td>					
            <td>								
              <input type="text" id="d_realdtl_nama_perusahaan<?=$i;?>" name="d_realdtl_nama_perusahaan<?=$i;?>" value="<?=$row['NAMA_PERUSAHAAN'];?>" size="15" readonly class="disabled">
							<input type="hidden" id="d_realdtl_kode_promotif<?=$i;?>" name="d_realdtl_kode_promotif<?=$i;?>" value="<?=$row['KODE_PROMOTIF'];?>">
              <input type="hidden" id="d_realdtl_kode_perusahaan<?=$i;?>" name="d_realdtl_kode_perusahaan<?=$i;?>" value="<?=$row['KODE_PERUSAHAAN'];?>">              													
              <a href="#" onclick="fl_js_lov_d_realdtl_promotif(<?=$i;?>);"><img src="../../images/help.png" alt="Cari Data Perusahaan" border="0" align="absmiddle"></a>            			 
              <input type="hidden" id="d_realdtl_bentuk_kegiatan<?=$i;?>" name="d_realdtl_bentuk_kegiatan<?=$i;?>" size="7" style="border-width: 1;text-align:left;" maxlength="30" value="<?=$row['BENTUK_KEGIATAN'];?>" readonly>              						          			 
            </td>
            <td>
              <input type="text" id="d_realdtl_nama_tk<?=$i;?>" name="d_realdtl_nama_tk<?=$i;?>" value="<?=$row['NAMA_TK'];?>" size="15" readonly class="disabled">													
              <a href="#" onclick="fl_js_val_d_realdtl_kodetk(<?=$i;?>);"><img src="../../images/help.png" alt="Cari Data TK" border="0" align="absmiddle"></a>            			 
            	<input type="hidden" id="d_realdtl_kode_tk<?=$i;?>" name="d_realdtl_kode_tk<?=$i;?>" value="<?=$row['KODE_TK'];?>">
              <input type="hidden" id="d_realdtl_kpj<?=$i;?>" name="d_realdtl_kpj<?=$i;?>" value="<?=$row['KPJ'];?>">
							<input type="hidden" id="d_realdtl_nama_jenis_barang<?=$i;?>" name="d_realdtl_nama_jenis_barang<?=$i;?>" value="<?=$row['NAMA_JENIS_BARANG'];?>">
							<input type="hidden" id="d_realdtl_kode_jenis_barang<?=$i;?>" name="d_realdtl_kode_jenis_barang<?=$i;?>" value="<?=$row['KODE_JENIS_BARANG'];?>">
							<input type="hidden" id="d_realdtl_nama_barang<?=$i;?>" name="d_realdtl_nama_barang<?=$i;?>" value="<?=$row['NAMA_BARANG'];?>">
							<input type="hidden" id="d_realdtl_jml_paket_barang<?=$i;?>" name="d_realdtl_jml_paket_barang<?=$i;?>" value="<?=$row['JML_PAKET_BARANG'];?>">
							<input type="hidden" id="d_realdtl_harga_perpaket_barang<?=$i;?>" name="d_realdtl_harga_perpaket_barang<?=$i;?>" value="<?=$row['HARGA_PERPAKET_BARANG'];?>">           
						</td>
						<td>							
							<input type="text" id="d_realdtl_nom_pokok<?=$i;?>" name="d_realdtl_nom_pokok<?=$i;?>" size="12" maxlength="20" value="<?=number_format((float)$row['NOM_POKOK'],2,".",",");?>" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value); fl_js_d_realdtl_hitung_nom_diajukan(<?=$i;?>);">
            </td>
						<td>
					  	<input type="text" id="d_realdtl_nom_ppn<?=$i;?>" name="d_realdtl_nom_ppn<?=$i;?>" size="10" maxlength="20" value="<?=number_format((float)$row['NOM_PPN'],2,".",",");?>" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value); fl_js_d_realdtl_hitung_nom_diajukan(<?=$i;?>);">
            </td>											
            <td>
            	<input type="text" readonly class="disabled" id="d_realdtl_nom_diajukan<?=$i;?>" name="d_realdtl_nom_diajukan<?=$i;?>" size="12" maxlength="20" value="<?=number_format((float)$row['NOM_DIAJUKAN'],2,".",",");?>" style="border-width: 1;text-align:right" onblur="this.value=format_uang(this.value);fl_js_d_realdtl_hitung_nom_diajukan(<?=$i;?>);">
            </td>																																																
            <td>
            	<input type="text" id="d_realdtl_keterangan<?=$i;?>" name="d_realdtl_keterangan<?=$i;?>" size="10" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
            </td> 										       																			        											
            <td style="text-align:center;">
              <?
              if ($ls_status_klaim =="AGENDA")
              {
							 	?> 
               	<a href="#" onclick="removeperbrs2(this.parentNode.parentNode.rowIndex);" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Delete </a>
								<?	               
							}
							?>								            	
            </td>																									
          </tr>
          <?				    							
          $i++;//iterasi i
					$ln_tot_pokok  += $row["NOM_POKOK"];
					$ln_tot_ppn  	 += $row["NOM_PPN"];
					$ln_tot_diajukan  += $row["NOM_DIAJUKAN"];
        }	//end while
        $ln_dtl=$i;
      }
      ?>																																				 
    </tbody>
    <tr>
      <td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>											
      </td>
		</tr>
    <tr>
      <td colspan="3" style="text-align:right;"><i>Total Keseluruhan: &nbsp;&nbsp;&nbsp;</i></td>
      </td>
      <td>
      	<input type="text" id="d_realdtl_tot_pokok" name="d_realdtl_tot_pokok" size="12" maxlength="20" value="<?=number_format((float)$ln_tot_pokok,2,".",",");?>" style="border-width: 1;text-align:right" readonly class="disabled" onblur="this.value=format_uang(this.value);">
      </td>		
      <td>
      	<input type="text" id="d_realdtl_tot_ppn" name="d_realdtl_tot_ppn" size="10" maxlength="20" value="<?=number_format((float)$ln_tot_ppn,2,".",",");?>" style="border-width: 1;text-align:right" readonly class="disabled" onblur="this.value=format_uang(this.value);">
      </td>																			
      <td>
      	<input type="text" id="d_realdtl_tot_diajukan" name="d_realdtl_tot_diajukan" size="12" maxlength="20" value="<?=number_format((float)$ln_tot_diajukan,2,".",",");?>" style="border-width: 1;text-align:right" readonly class="disabled" onblur="this.value=format_uang(this.value);">
      </td>				
      <td>
				<input type="hidden" id="d_realdtl_kounter_dtl" name="d_realdtl_kounter_dtl" value="<?=$ln_dtl;?>">
        <input type="hidden" id="d_realdtl_count_dtl" name="d_realdtl_count_dtl" value="<?=$ln_countdtl;?>">
        <input type="hidden" name="d_realdtl_showmessage" style="border-width: 0;text-align:right" readonly size="30">			
			</td>
			<td>
        <?
        if ($ls_status_klaim =="AGENDA")
        {
         	 ?> 
        	 <a href="#" onclick="addRowInnerHTML2('tblrincian2');"><img src="../../images/plus.png" border="0" align="absmiddle"> Entry</a>
        	 <?	               
        }
        ?>				
			</td>									              
    </tr>
    <tr>
      <td colspan="10">
					<hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>											
      </td>										              
    </tr>					 							
  </table>	  																						  								 
</div>	<!-- end div id="formKiri"-->	
