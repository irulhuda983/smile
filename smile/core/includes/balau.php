<?php
include "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
include "../../includes/class_database.php"; 
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

//under construction
function paging($ls_sql){
		// Explode SQL Statement into array [SELECT, FROM, WHERE, ORDER BY]
		$tmp = ''; 
		$itter_after_reset	= 0;
		$itter_bracket		= 0;
		$keyword_position	= '';
		$arr_word			= array();
		$arr_keyword_query	= array('SELECT' => '', 'FROM' => '', 'WHERE' => '', 'ORDER BY' => '');
		$st_cutword			= false;
		for($i=0,$max_i=strlen($ls_sql); $i<=$max_i; $i++){
			$char = ($i==$max_i ? ' ' : substr($ls_sql, $i, 1));
			if($char == '('){ $itter_bracket++; }else if($char==')'){ $itter_bracket--; }
			
			if(in_array($char, array(' ','	',chr(10),chr(13)))){
				if($itter_after_reset == 0){ $word = $tmp; $arr_word = array_merge($arr_word, array($word)); }
				$itter_after_reset++; $tmp = '';
				$st_cutword = true;
			}else{
				$word = '';
				$tmp .= $char;
				$itter_after_reset = 0;
				$st_cutword = false;
			}
			
			
			if(strtoupper($word)=='SELECT' && $itter_bracket==0){ $keyword_position = 'SELECT'; }
			else if(strtoupper($word)=='FROM' && $itter_bracket==0){ $keyword_position = 'FROM'; }
			else if(strtoupper($word)=='WHERE' && $itter_bracket==0){ $keyword_position = 'WHERE'; }
			else if(strtoupper($word)=='BY' && $arr_word[count($arr_word)-2] == 'ORDER' && $itter_bracket==0){ $keyword_position = 'ORDER BY'; }
			
			
			if($keyword_position=='SELECT'){
				$arr_keyword_query['SELECT'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='FROM'){
				$arr_keyword_query['FROM'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='WHERE'){
				$arr_keyword_query['WHERE'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='ORDER BY'){
				$arr_keyword_query['ORDER BY'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}
		}
		$arr_keyword_query['SELECT'] = trim($arr_keyword_query['SELECT']);
		$arr_keyword_query['FROM'] = trim($arr_keyword_query['FROM']);
		$arr_keyword_query['WHERE'] = trim($arr_keyword_query['WHERE']);
		$arr_keyword_query['ORDER BY'] = trim($arr_keyword_query['ORDER BY']);
		
		$arr_keyword_query['SELECT'] = trim(substr($arr_keyword_query['SELECT'],0,strlen($arr_keyword_query['SELECT'])-4));
		if($arr_keyword_query['WHERE'] != ''){
			$arr_keyword_query['FROM'] = trim(substr($arr_keyword_query['FROM'],0,strlen($arr_keyword_query['FROM'])-5));
		}
		if($arr_keyword_query['WHERE'] != '' && $arr_keyword_query['ORDER BY'] != ''){
			$arr_keyword_query['WHERE'] = trim(substr($arr_keyword_query['WHERE'],0,strlen($arr_keyword_query['WHERE'])-8));
		}
		
		// Get Necessary Variable For Paging
		
		$perpage	= isset($_POST['page']) ? intval($_POST['page']) : 1;
	
	$sql_total = ("SELECT COUNT(1) X FROM(".$ls_sql.") T");
echo '<br>';	echo $sql_total;	echo '<br>';
		$DB->parse($sql_total);
		$DB->execute();
		$row 			= $DB->nextrow();
		$total		= $row['X'];
		
		echo $total;
		$cur_page = (($_POST['page']> ceil($total/$perpage)) ? ceil($total/$perpage) : ($_POST['page'] < 1 ? 1 : $_POST['page']));
		if($cur_page < 1) $cur_page = 1;
		
		
		// Set Start & End Rows
		$ln_start	= (($cur_page - 1) * $perpage) + 1;
		$ln_end		= $ln_start + $perpage - 1;
		echo 'dddd';
		// Patching For oracle
		$arr_keyword_query['SELECT']	= (trim($arr_keyword_query['SELECT']) == '*' ? $arr_keyword_query['FROM'].'.' : '') . $arr_keyword_query['SELECT'];
		
		// Create New SQL with Paging
		$ls_sql_with_paging	= "	SELECT * 
								FROM (
										SELECT ".$arr_keyword_query['SELECT'].", ROW_NUMBER() OVER (ORDER BY ".$arr_keyword_query['ORDER BY'].") AS xrownum
										FROM ".$arr_keyword_query['FROM']." 
										".($arr_keyword_query['WHERE']!="" ? "WHERE ".$arr_keyword_query['WHERE'] : "").") tbl
								WHERE	xrownum BETWEEN ".$ln_start." AND ".$ln_end."
								ORDER BY xrownum";
								
		$DB->parse($ls_sql_with_paging);
		$DB->execute();
		$arr_data = array();
		while($row = $DB->nextrow()) {
			array_push($arr_data, $row);
		}
		
		// Create Array Response For Datagrid
		$arr_response = array(	'rows'=> $arr_data, 
								'total' => $total, 
								'page'=>$cur_page);
								
		return $arr_response;
	}
	
	
?>