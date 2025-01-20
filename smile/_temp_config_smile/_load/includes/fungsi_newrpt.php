<?PHP
include_once "conf_global.php";
$ls_user ="sijstk";
$ls_pass="jmo1";

?>

<script language="javascript">
	function NewWindow(mypage,myname,w,h,scroll){
	  var winl = (screen.width-w)/2;
	  var wint = (screen.height-h)/2;
	  var settings  ='height='+h+',';
		  settings +='width='+w+',';
		  settings +='top='+wint+',';
		  settings +='left='+winl+',';
		  settings +='scrollbars='+scroll+',';
		  settings +='resizable=1';
		  settings +='titlebar=0';
		  settings +='location=0';
		  settings +='addressbar=0';
		  settings +='menubar=0';
	  win=window.open(mypage,myname,settings);
	  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
	  //location.replace('REPORT_SIJSTK');
	}
	function convert_tglblth(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			switch (tlg[1]) {
				case '01':
					day = "JAN";
					break;
				case '02':
					day = "FEB";
					break;
				case '03':
					day = "MAR";
					break;
				case '04':
					day = "APR";
					break;
				case '05':
					day = "MAY";
					break;
				case '06':
					day = "JUN";
					break;
				case '07':
					day = "JUL";
					break;
				case '08':
					day = "AUG";
					break;
				case '09':
					day = "SEP";
					break;
				case '10':
					day = "OCT";
					break;
				case '11':
					day = "NOV";
					break;
				case '12':
					day = "DEC";
					break;
			}
			field1.value = tlg[0]+'-'+tlg[1]+'-'+tlg[2];
		}
	}

	function convert_blth(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1]+'-'+tlg[2];
		}
	}

	function convert_blth_ojk(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1]+'/'+tlg[2];
		}
	}

	function convert_bln(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1];
		}
	}
	function convert_thn(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[2];
		}
	}
</script>

<?PHP

function convertTGLBLTH($mydate){
	$tgl = explode('-',$mydate);
	switch ($tgl[1]) {
		case '01':
			$day = "JAN";
			break;
		case '02':
			$day = "FEB";
			break;
		case '03':
			$day = "MAR";
			break;
		case '04':
			$day = "APR";
			break;
		case '05':
			$day = "MAY";
			break;
		case '06':
			$day = "JUN";
			break;
		case '07':
			$day = "JUL";
			break;
		case '08':
			$day = "AUG";
			break;
		case '09':
			$day = "SEP";
			break;
		case '10':
			$day = "OCT";
			break;
		case '11':
			$day = "NOV";
			break;
		case '12':
			$day = "DEC";
			break;
	}
	return $tgl[0].'-'.$day.'-'.$tgl[2];
}



function convert_blth_ojk($mydate) {
	if (substr_count($mydate, '/') == '1') {
		$mydate = '01/' . $mydate;
	} else {
		$mydate = $mydate;
	}
	$tgl = explode('/',$mydate);
	return $tgl[1].'/'.$tgl[2];
}

function convert01BLTH($mydate){

	if (substr_count($mydate, '/') == '1') {
		$tgl = '01/' . $mydate;
	} else {
		$tgl = $mydate;
	}

/*
	$searchchar="/";
	$count="0"; //zero
	for($i="0"; $i<strlen($mydate); $i=$i+1){
		if(substr($mydate,$i,1)==$searchchar){
		   $count=$count+1;
		}
	}

	if ($count==1){
		$tgl = '01/' . $mydate;
	} else {
		$tgl = $mydate;
	}
*/

	//$tgl = explode('/',$mydate);
	$tgl = explode('/',$tgl);
	switch ($tgl[1]) {
		case '01':
			$day = "JAN";
			break;
		case '02':
			$day = "FEB";
			break;
		case '03':
			$day = "MAR";
			break;
		case '04':
			$day = "APR";
			break;
		case '05':
			$day = "MAY";
			break;
		case '06':
			$day = "JUN";
			break;
		case '07':
			$day = "JUL";
			break;
		case '08':
			$day = "AUG";
			break;
		case '09':
			$day = "SEP";
			break;
		case '10':
			$day = "OCT";
			break;
		case '11':
			$day = "NOV";
			break;
		case '12':
			$day = "DEC";
			break;
	}
	return '01-'.$day.'-'.$tgl[2];
}
function exec_rpt($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param)
{
	global $username;
	global $gs_sid;

	$ls_pdf = '1';

	$report["link"] 	= $ls_link;
	$report["user"] 	= "sijstk";
	$report["password"]	= "jmo1";
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);


	$link = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param)
{
	global $username;
	global $gs_sid;

	$ls_pdf = '1';

	$report["link"] 	= $ls_link;
	$report["user"] 	= "sijstk";
	$report["password"]	= "jmo1";
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	//$link="http://172.28.201.109:7779/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	//$link = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	// $link = $report["link"].base64_encode("http://172.28.201.109:7779/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report["link"].base64_encode("http://172.28.201.157:7779/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]); // Aditya Permana-Report New Core ke 157
	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]); // Aditya Permana-Report New Core ke 151


	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc_tipe($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param, $tipe)
{
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	$report["link"] 	= $ls_link;
	$report["user"] 	= "sijstk";
	$report["password"]	= "jmo1";
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D". $tipe . "%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]); // Aditya Permana-Report New Core ke 151

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc_new($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	global $nc_rpt_user;
	global $nc_rpt_pass;
	global $nc_rpt_path;
	global $nc_rpt_sid;

	$path = $nc_rpt_path.$ls_modul;

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= "sijstk";
	$report["password"]	= "jmo1";
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	$link_rpt_server = "http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
	$link = $report["link"].base64_encode($link_rpt_server);
	// $link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	
	if($ls_pdf == '9') {
		return $link_rpt_server;
	}
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_drc($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;

	global $nc_rpt_link;
	global $nc_rpt_user;
	global $nc_rpt_pass;
	global $nc_rpt_path;
	global $nc_rpt_sid_drc;

	$path = $nc_rpt_path.$ls_modul;

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= 'sijstk';
	$report["password"]	= 'jmo1';
	$report["sid"] 		= 'dboltp';
	//$report["sid"] 		= 'dboltp';
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);


	//$link_rpt_server = "http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	$link_rpt_server = $ipReportServer."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];

	// juli 2020: tambahan untuk kebutuhan arsip
	if ($ls_pdf == '9') {
		return $link_rpt_server;
	}
	// end tambahan untuk kebutuhan arsip

	// $link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	$link = $report["link"].base64_encode($link_rpt_server);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_vokasi($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	$nc_rpt_user='vks';
	$nc_rpt_pass='jmo2';
	global $nc_rpt_path;
	$nc_rpt_sid='sijstkecha';

	$path = $nc_rpt_path.$ls_modul;

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}


function exec_rpt_enc_new_rsjht($parameter,$tahun)
{
	//$report["link"] = 'http://172.28.100.22:2014/DLServices/RSJHTJP/dl/';
	$tahun 			= $tahun;//date('Y',strtotime('-1 years'));
	//$link 			= 'http://172.28.100.22:2014/DLServices/RSJHTJP/dl/'.base64_encode($tahun.'|'.$parameter);
	$link 			= 'http://wstest.bpjsketenagakerjaan.go.id:2014/DLServices/RSJHTJP/dl/'.base64_encode($tahun.'|'.$parameter);
	//print_r($link);

	?>
	<!-- setelah proses eksekusi selesai buka pdf report-->
	<script language="JavaScript" type="text/javascript">
		//console.log("<?=$link;?>");
		NewWindow('<?=$link;?>','',950,600,0);
	</script>
	<?
}

function exec_rpt_enc_new_rsjht_bpu($parameter)
{
	$link 			= 'http://wstest.bpjsketenagakerjaan.go.id:2014/ReportService/RSJHTJP/dl/'.base64_encode($parameter);
	?>
	<!-- setelah proses eksekusi selesai buka pdf report-->
	<script language="JavaScript" type="text/javascript">
		NewWindow('<?=$link;?>','',950,600,0);
	</script>
	<?
}

function exec_rpt_enc_new_spo($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	$nc_rpt_user='spo';
	$nc_rpt_pass='jmo2';
	global $nc_rpt_path;
	$nc_rpt_sid='sijstkecha';

	$path = $nc_rpt_path.$ls_modul;
	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	//$link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	//$link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_oss($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $oss_rpt_link;
	global $oss_rpt_user;
	global $oss_rpt_pass;
	global $oss_rpt_path;
	global $oss_rpt_sid;

	$path = $nc_rpt_path.$ls_modul;

	$report["link"] 	= $oss_rpt_link;
	$report["user"] 	= $oss_rpt_user;
	$report["password"]	= $oss_rpt_pass;
	$report["sid"] 		= $oss_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}


function exec_rpt_enc_new_ec($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	global $nc_rpt_user;
	global $nc_rpt_pass;
	global $nc_rpt_path;
	global $nc_rpt_sid;

	$path = $nc_rpt_path.$ls_modul;

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= "EC";
	$report["password"]	= "jmo2";
	$report["sid"] 		= "sijstkecha";
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_kpi($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	global $nc_rpt_user;
	global $nc_rpt_pass;
	global $nc_rpt_path;
	global $nc_rpt_sid;

	$path = $nc_rpt_path.$ls_modul;
	// var_dump($path);
	// die();

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= 'sijstk';
	$report["password"]	= 'jmo1';
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// $link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_antrian($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	$nc_rpt_user='antrian';
	$nc_rpt_pass='jmo2';
	global $nc_rpt_path;
	$nc_rpt_sid='sijstkecha';

	$path = $nc_rpt_path.$ls_modul;
	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	//$link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	$link = $report["link"].base64_encode("http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	//$link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}


?>
