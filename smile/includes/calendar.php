<?php

function dtpicker($id, $value){
	echo "<input type=text name='$id' id='$id' maxlength='10' size='11' onblur='convert_date(this)' value='$value' />".
	'<input type=image src="../images/calendar.gif" onclick="showCalendar(\''.$id.'\');return false;" />';
}

function YMDformat($string=null) {
	$arr_MMM	= array('JAN'=>'01', 
						'FEB'=>'02', 
						'MAR'=>'03', 
						'APR'=>'04', 
						'MAY'=>'05', 
						'JUN'=>'06', 
						'JUL'=>'07', 
						'AUG'=>'08',
						'SEP'=>'09', 
						'OCT'=>'10', 
						'NOV'=>'11', 
						'DEC'=>'12');
	if(isset($string)) {
		if(is_YMDformat($string)){
			return substr($string, 0, 10);
		}else
		if(check_dateFormat($string, 'YYYY/MM/DD')){
			return str_replace('/', '-', $string);
		}else
		if(check_dateFormat($string, 'DD/MM/YYYY')){
			$arr_date = explode('/', substr($string,0,10));
			return $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'DD-MM-YYYY')){
			$arr_date = explode('-', substr($string,0,10));
			return $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'DD/MMM/YYYY')){
			$arr_date = explode('/', substr($string,0,11));
			return $arr_date[2].'-'.$arr_MMM[strtoupper($arr_date[1])].'-'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'DD/MMM/YY')){
			$arr_date = explode('/', substr($string,0,9));
			return '20'.$arr_date[2].'-'.$arr_MMM[strtoupper($arr_date[1])].'-'.$arr_date[0];
		}
		else
		if(check_dateFormat($string, 'DD-MMM-YYYY')){
			$arr_date = explode('-', substr($string,0,11));
			return $arr_date[2].'-'.$arr_MMM[strtoupper($arr_date[1])].'-'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'DD-MMM-YY')){
			$arr_date = explode('-', substr($string,0,9));
			return '20'.$arr_date[2].'-'.$arr_MMM[strtoupper($arr_date[1])].'-'.$arr_date[0];
		}else{
			return $string;
		}
		
	}
	/*if(isset($string)) {
		if(is_YMDformat($string)){
			return substr($string, 0, 10);
		}else
		if($string!='' && strlen($string)>9 && strstr($string, '/')!=''){
			$arr_date = explode('/', substr($string,0,10));
			return $arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0];
		}
	}*/
}

function check_dateFormat($date='', $format = 'YYYY-MM-DD') {
	if(!isset($date)) $date = '';
	//$arr_MMM	= array('JAN', 'FEB', 'MAR', 'APR');
	if($format == 'YYYY-MM-DD'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'-')!='') {
			$arr_temp	= explode('-', substr($date,0,10));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[0];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[2];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==2 && is_numeric($bulan) && $bulan>0 && $bulan<=12 &&
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'YYYY/MM/DD'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'/')!='') {
			$arr_temp	= explode('/', substr($date,0,10));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[0];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[2];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==2 && is_numeric($bulan) && $bulan>0 && $bulan<=12 &&
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD/MM/YYYY'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'/')!='') {
			$arr_temp	= explode('/', substr($date,0,10));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[2];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[0];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==2 && is_numeric($bulan) && $bulan>0 && $bulan<=12 &&
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD-MM-YYYY'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'-')!='') {
			$arr_temp	= explode('-', substr($date,0,10));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[2];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[0];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==2 && is_numeric($bulan) && $bulan>0 && $bulan<=12 &&
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD/MMM/YYYY'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'/')!='') {
			$arr_temp	= explode('/', substr($date,0,11));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[2];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[0];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==3 && !is_numeric($bulan) && 
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD/MMM/YY'){
		$valid	= false;
		if(isset($date) && strlen($date)>8 && strstr($date,'/')!='') {
			$arr_temp	= explode('/', substr($date,0,9));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[2];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[0];
				if(	strlen($tahun)==2 && is_numeric($tahun) && 
					strlen($bulan)==3 && !is_numeric($bulan) && 
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD-MMM-YYYY'){
		$valid	= false;
		if(isset($date) && strlen($date)>9 && strstr($date,'-')!='') {
			$arr_temp	= explode('-', substr($date,0,11));
			if(count($arr_temp)==3){
				$tahun	= $arr_temp[2];
				$bulan	= $arr_temp[1];
				$tgl	= $arr_temp[0];
				if(	strlen($tahun)==4 && is_numeric($tahun) && 
					strlen($bulan)==3 && !is_numeric($bulan) && 
					strlen($tgl)==2 && is_numeric($tgl) && $tgl>0 && $tgl<=31) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
	
	if($format == 'DD-MMM-YY'){
		$valid	= false;
		
		if(isset($date) && strlen($date)>8 && strstr($date,'-')!='') {
			
			$arr_temp	= explode('-', substr($date,0,9));
			if(count($arr_temp)==3){
				$tahun	= strval($arr_temp[2]);
				$bulan	= strval($arr_temp[1]);
				$tgl	= strval($arr_temp[0]); 
				if(	strlen($tahun)==2 && is_numeric($tahun) && 
					strlen($bulan)==3 && !is_numeric($bulan) && 
					strlen($tgl)==2 && is_numeric($tgl) && intval($tgl>0) && intval($tgl<=31)) {
						$valid = true;
				}
					
			}
			unset($arr_temp);
		}
		return $valid;
	}
}

function is_YMDformat($string=null) {
	return check_dateFormat($string, 'YYYY-MM-DD'); /*
	$res = false;
	if(isset($string)) {
		if($string!='' && strlen($string)>9 && strstr($string, '-')!=''){
			$arr_date = explode('-', substr($string,0,10));
			if(count($arr_date)==3) {
				if(strlen($arr_date[0])==4 && strlen($arr_date[1])==2 && strlen($arr_date[2])==2){
					$res = true;
				}
			}
		}
	}
	
	return $res;*/
}

function DMYformat($string=null, $namabulan=false) {
	$arr_MMM	= array('JAN'=>'01', 
						'FEB'=>'02', 
						'MAR'=>'03', 
						'APR'=>'04', 
						'MAY'=>'05', 
						'JUN'=>'06', 
						'JUL'=>'07', 
						'AUG'=>'08',
						'SEP'=>'09', 
						'OCT'=>'10', 
						'NOV'=>'11', 
						'DEC'=>'12');
	if(isset($string)) {
		//$string = YMDformat($string);
		if(is_DMYformat($string)){
			$result = substr($string, 0, 10);
		}else
		if(check_dateFormat($string, 'DD-MM-YYYY')){
			$result = str_replace('-', '/', $string);
		}else
		if(check_dateFormat($string, 'YYYY/MM/DD')){
			$arr_date = explode('/', substr($string,0,10));
			$result = $arr_date[2].'/'.$arr_date[1].'/'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'YYYY-MM-DD')){
			$arr_date = explode('-', substr($string,0,10));
			$result = $arr_date[2].'/'.$arr_date[1].'/'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'YYYY/MMM/DD')){
			$arr_date = explode('/', substr($string,0,11));
			$result = $arr_date[2].'/'.$arr_MMM[strtoupper($arr_date[1])].'/'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'YY/MMM/DD')){
			$arr_date = explode('/', substr($string,0,9));
			$result = '20'.$arr_date[2].'/'.$arr_MMM[strtoupper($arr_date[1])].'/'.$arr_date[0];
		}
		else
		if(check_dateFormat($string, 'YYYY-MMM-DD')){
			$arr_date = explode('-', substr($string,0,11));
			$result = $arr_date[2].'/'.$arr_MMM[strtoupper($arr_date[1])].'/'.$arr_date[0];
		}else
		if(check_dateFormat($string, 'YY-MMM-DD')){
			$arr_date = explode('-', substr($string,0,9));
			$result = '20'.$arr_date[2].'/'.$arr_MMM[strtoupper($arr_date[1])].''.$arr_date[0];
		}else
		if(check_dateFormat($string, 'DD/MMM/YY')){
			$arr_date = explode('/', substr($string,0,9));
			$result = $arr_date[0].'/'.$arr_MMM[strtoupper($arr_date[1])].'/'.'20'.$arr_date[2];
		}
		else
		if(check_dateFormat($string, 'DD-MMM-YY')){
			$arr_date = explode('-', substr($string,0,9));
			$result = $arr_date[0].'/'.$arr_MMM[strtoupper($arr_date[1])].'/'.'20'.$arr_date[2];
		}else{
			$result = $string;
		}
		
		
		if($namabulan){
			$arr_date = explode('/', substr($result,0,10));
			return $arr_date[0].' '.nama_bln($arr_date[1]).' '.$arr_date[2];
		}else{
			return $result;
		}
	}/*
	if(is_DMYformat($string)) $string = YMDformat($string);
	
	if(is_YMDformat($string)) {
		$arr_date = explode('-', substr($string,0,10));
		
		if($namabulan) {
			return $arr_date[2].' '.nama_bln($arr_date[1]).' '.$arr_date[0];
		}else {
			return $arr_date[2].'/'.$arr_date[1].'/'.$arr_date[0];
		}
	}*/
}

function MYformat($string=null, $namabulan=false) {
	if(isset($string)) {
		if(strlen($string)==7){
			$result = substr($string, 0, 7);
		}
		
		if($namabulan){
			$arr_date = explode('/', substr($result,0,7));
			return nama_bln($arr_date[0]).' '.$arr_date[1];
		}else{
			return $result;
		}
	}
}

function is_DMYformat($string=null) {
	return check_dateFormat($string, 'DD/MM/YYYY'); /*
	$res = false;
	if(isset($string)) {
		if($string!='' && strlen($string)>9 && strstr($string, '/')!=''){
			$arr_date = explode('/', substr($string,0,10));
			if(count($arr_date)==3) {
				if(strlen($arr_date[2])==4 && strlen($arr_date[1])==2 && strlen($arr_date[0])==2){
					$res = true;
				}
			}
		}
	}
	
	return $res;*/
}

/*$tgl = '08-AUG-47';
echo '<hr />Tgl gelo : '.$tgl.' ; Tgl Bener : '.YMDformat($tgl,true).'<hr />';*/

function nama_bln($number, $lang='id') {
	if($lang == 'id') {
		$arr_namabulan = array('', 'Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
	}else {
		$arr_namabulan = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	}
	
	$number = ((strlen($number)==2 && substr($number,0,1)=='0') ? substr($number,1,1) : $number);
	return $arr_namabulan[intval($number)];
	
}
?>

