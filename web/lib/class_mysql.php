<?php
if(!$connect||empty($connect)){
	$connect = mysql_connect($MYSQLDBVALUE['host'], $MYSQLDBVALUE['user'],$MYSQLDBVALUE['pass']);
	$MYSQLCONNSTATUS = mysql_select_db($MYSQLDBVALUE['dbname'], $connect);

	if(!$MYSQLCONNSTATUS){
		$MYSQLERRNO = mysql_errno($connect);
		$MYSQLERRMSG = mysql_error($connect);

		echo("데이터베이스 연결 실패<br>");
		echo("에러코드 $MYSQLERRNO : $MYSQLERRMSG<br>");
		exit;
	}
	mysql_query("SET NAMES utf8");
}

Class dbMySql {

	var $dbConn;
	var $sqlResult;
	var $fldInfo;

	Function dbMySql($connect){
		$this->dbConn = $connect;
	}

	Function dbClose(){
		return mysql_close($this->dbConn);
	}

	function dbQuery($strQuery){
		return @mysql_query($strQuery,$this->dbConn);
	}

	Function dbIdxNo(){
		return @mysql_insert_id();
	}

	Function dbError(){
		return mysql_error();
	}

	Function dbFetchResult($strQuery){
		$this->sqlResult = $this->DBQuery($strQuery);
		$sqlValue = @mysql_fetch_array($this->sqlResult);
		@mysql_free_result($this->sqlResult);
		return $sqlValue;
	}

	Function dbOneResult($strQuery){
		$this->sqlResult = $this->DBQuery($strQuery);
		$sqlValue = @mysql_result($this->sqlResult,0,0);
		@mysql_free_result($this->sqlResult);
		return $sqlValue;
	}

	Function dbSingle($tblname,$fldname,$where=''){
		$strQuery = "SELECT {$fldname} FROM {$tblname} {$where}";
		echo $strQuery."<br><br>";
		return $this->dbOneResult($strQuery);
	}

	Function dbVarious($tblname,$fldname,$where=''){
		$strQuery = "SELECT {$fldname} FROM {$tblname} {$where}";
		//echo $strQuery."<br><br>";
		return $this->dbFetchResult($strQuery);
	}

	Function dbCnt($tblname,$fldname,$where=''){
		$strQuery = "SELECT COUNT({$fldname}) FROM {$tblname} {$where}";
		//echo "{$strQuery} <br><br>";
		return $this->dbOneResult($strQuery);
	}

	Function dbMax($tblname,$fldname,$where){
		$strQuery = "SELECT MAX({$fldname}) FROM {$tblname} {$where} ";
		// echo $strQuery."<br><br>";
		$sqlValue = $this->dbOneResult($strQuery);
		if($sqlValue){
			return $sqlValue;
		}else{
			return '0';
		}	
	}

	Function dbSum($tblname,$fldname,$where){
		$strQuery = "SELECT SUM({$fldname}) FROM {$tblname} {$where} ";
		//echo $strQuery."<br><br>";
		$sqlValue = $this->dbOneResult($strQuery);
		if($sqlValue){
			return $sqlValue;
		}else{
			return '0';
		}	
	}

	Function dbRownum($strQuery){
		$this->sqlResult = $this->DBQuery($strQuery);
		$sqlValue = @mysql_num_rows($this->sqlResult);
		@mysql_free_result($this->sqlResult);
		if($sqlValue){
			return $sqlValue;
		}else{
			return '0';
		}	
	}

	Function dbFld($tblname){ 
		$strQuery = "SELECT * FROM {$tblname}";
		$sqlValue = $this->dbQuery($strQuery);
		$cnt = mysql_num_fields($sqlValue);

		for($i=0; $i<$cnt; $i++){
			$fld_name = mysql_field_name($sqlValue,$i);

			$this->fldInfo['fld_type'][$fld_name] = mysql_field_type($sqlValue,$i);
		}
		return $this->fldInfo;
	}

	Function dbFldChk($fldtype,$type){
		$arrayFldType = array(
		'txt'=>array('VARCHAR','TEXT','CHAR','TINYBLOB','TINYTEXT','BLOB','MEDIUMBLOB','MEDIUMTEXT','LONGBLOB','LONGTEXT')
		,'num'=>array('TINYINT','SMALLINT','MEDIUMINT','INT','BIGINT','FLOAT','DOUBLE','DECIMAL')
		);

		if(in_array(strtoupper($fldtype),$arrayFldType[$type])){
			$rtn = true;
		}else{
			$rtn = false;
		}
		
		return $rtn;
	}

	Function dbSetSql($tblname,$dbfldvalue,$type,$where=''){
		
		$strQuery = "";
		
		// update
		if(isset($where)&&$type=='update'){
			 $strQuery = "UPDATE ";
		
			$strQuery .= " {$tblname} SET ";
			$fldInfo = $this->dbFld($tblname);
			
			$k=0;
			foreach($dbfldvalue as $key => $value){
				$numchk = $this->dbFldChk($fldInfo['fld_type'][$key],'num');
				
				if($k>0) $strQuery .= ",";
				$strQuery .= "{$key} =";
				if($numchk==false) {
						$strQuery .="'";
				}
				
				if($this->dbFldChk($fldInfo['fld_type'][$key],'txt')==true) {
					$strQuery .= addslashes(trim($value));
				}else{
					$strQuery .= trim($value);
				}
				
				if($numchk==false) {
						$strQuery .="'";
				}
	
				$k++;
			}
		// insert
		} else {
			$strQuery = "INSERT INTO ";
			$strQuery .= " {$tblname} (";
			$fldInfo = $this->dbFld($tblname);
			
			$k=0;
			foreach($dbfldvalue as $key => $value){				
				if($k>0) $strQuery .= ",";
				$strQuery .= "{$key} ";
				$k++;
			}
			$strQuery .= " ) values (";
			
			$k=0;
			foreach($dbfldvalue as $key => $value){
				$numchk = $this->dbFldChk($fldInfo['fld_type'][$key],'num');
				
				if($k>0) $strQuery .= ",";
				if($numchk==false) { 	
						$strQuery .="'";
				}
				
				if($this->dbFldChk($fldInfo['fld_type'][$key],'txt')==true) {
					$strQuery .= addslashes(trim($value));
				}else{
					$strQuery .= trim($value);
				}
				
				if($numchk==false) {
						$strQuery .="'";
				}
	
				$k++;
			}
			$strQuery .= " ) ";
		}

		if(isset($where)&&$type=='update') $strQuery .= $where;
		// echo $strQuery .'<br><br>';
		$result = $this->dbQuery($strQuery);
		return $result;
	}

	Function dbDelSql($tblname,$where){
		$strQuery ="DELETE FROM {$tblname} {$where}";
		//echo $strQuery .'<br><br>';
		$this->dbQuery($strQuery);
	}

}

?>