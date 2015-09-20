<?
/**
 * Base Database Management Class
 *
 * @author E-Site Marketing
 * @updated 04/15/04
 * @version 2.0
 * @since 1.0
 * @access public
 * @copyright E-Site Marketing, LLC 2004
 *
 */

class Db{
	 
	// Properties;
	var $db_user=DB_USER;
	var $db_pass=DB_PASS;
	var $db_host=DB_HOST;
	var $db_name=DB_NAME;
	var $persistent=false;
	var $connectionErrors=true;
	var $connectionErrorMessage=true;
	var $SQL;
	var $query;
	var $rlink;
	var $iterator=false;
	//var $errorContacts = "sgantt@esitemarketing.com;rdawson@esitemarketing.com";
	var $errorContacts = "jsantucci@ifes.org;daryabeygi@yahoo.com";
	var $errorMessageDisplay = "There was an error connecting";		
	var $showDatabaseErrors=true;
	var $cols=array();		
	var $error=false;
	var $propertyArray = array();
	var $_driver="mysql";

	function setDriver($driver){
		$this->_driver=$driver;
	}
	
	function getDriver(){
		return $this->_driver;
	}	

/**
* This is the construstor for the DB class.
*
* @param string $dbhost = host
* @param string $dbuser = dbuser
* @param string $dbpassword  = password
* @param string $dbname = database name 
* @access public
* @returns void
*/
	function Db( $dbhost=false, $dbuser=false, $dbpassword=false, $dbname=false ) {
		$this->SetConnect( $dbuser, $dbpassword, $dbhost, $dbname );
	}


// ___________________________________________________________________________________;	
// 								Public Methods;
// ___________________________________________________________________________________;

/**
* This function allows setting of public class properties.
*
* @param string $value Value to be set	  
* @param string $property property to access include:
	"user" = database user
	"password" = database password
	"host" = database host	  
	"database" = database name
	"queryErrors" (boolean) = show database query errors
	"connectionErrors" (boolean) = alert people in error contact list if database can not be connected to.
	"errorContacts" = list of people to be contacted if the database cannot be connected to.
	"errorMessageDisplay" = message to display when database cannot connect (only shows is connectionErrors is TRUE)	  
* @access public
*/
    function SetProperty($property,$value){
		$property = strtolower($property);
		$this->propertyArray = explode(",",strtolower("user,password,database,host,queryErrors,connectionErrors,errorContacts,persistent,errorMessageDisplay,imagePath"));
		if(!in_array($property,$this->propertyArray) ? die("<P ALIGN='CENTER'><B>DB CLASS Error:</B> Sorry, the <I>SetProperty()</I> method the DB Class does not accept the parameter '<em><strong>".$property."</strong></em>'</P>"):"");
				
		switch($property){
			case"user":
				$this->db_user=$value;
			break;
			case"password":
				$this->db_password=$value;
			break;
			case"database":
				$this->db_name=$value;
			break;
			case"host":
				$this->db_host=$value;
			break;		
			case"queryerrors":
				$this->showDatabaseErrors=$value;
			break;
			case"connectionerrors":
				$this->connectionErrors=$value;
			break;	
			case"errorcontacts":
				$this->errorContacts=$value;
			break;		
			case"persistent":
				$this->persistent=$value;
			break;		
			case"errormessagedisplay":
				$this->errorMessageDisplay=$value;
			break;
		}		
	}
	
/**
* This function is used to connect to the database.
*
* @access public
*/
	function Connect(){
		if($this->persistent){
			$this->rlink = mysql_pconnect($this->db_host,$this->db_user,$this->db_pass);// OR die("Error: ".mysql_error());
		}else{
			$this->rlink = mysql_connect($this->db_host,$this->db_user,$this->db_pass);// OR die("Error with ".$this->getcf()." ".mysql_error());
		}
		
		if(!$this->HandleConnectionError()){
			if(!mysql_select_db ($this->db_name,$this->rlink))die("Could Not use database ".$this->db_name);	
		}	
	}
/**
* This function is used to disconnect from the database.
*
* @access public
*/
	function Disconnect(){
		mysql_close($this->rlink);
	}
/**
* Get the value of the Database link (this can be used for record set caching).
*  
* @returns String  
* @access public
*/		
	function GetLink(){
		return ($this->rlink!="" ? $this->rlink : false);
	}

/**
* Used to override username,password,database name, and host string for the database. Note: uses the standard Connect() method.
*
* @param string $user The new username
* @param string $pass The new password
* @param string $host The new database host
* @param string $database the new database name  	  	  
* @access public
* @see Connect()
*/	  
	function SetConnect($user,$pass,$host,$database){
		$this->db_host=($host!="" ? $host : $this->db_host);
		$this->db_user=($user!="" ? $user : $this->db_user);
		$this->db_pass=($pass!="" ? $pass : $this->db_pass);
		$this->db_name=($database!="" ? $database : $this->db_name);
		$this->Connect();
	}


/**
* Send the query to the database. If showDatabaseErrors is TRUE, then erorrs will be displayed on the page if there are any.
*
* @param string $SQL = The SQL String to be sent to the database. Uset getRows method the then loop through and get the result sets.
* @param string $robust = Robust is used to tell the query to return additional column name information
* @access public
* ($return_affected == true) returns GetAffectedRows();
* otherwise, return the raw result set for later use
*/
	function Query($SQL, $robust=false, $return_affected = true){
		$this->Connect();
		$this->query=mysql_query($SQL);
		if(!$this->query)
			$this->error = mysql_error();
		if($robust){
			for ($i=0;$i < mysql_num_fields($this->query);$i++){
				$t = mysql_fetch_field($this->query);
				if($t->name!==NULL) $this->QueryColumns[$i]=$t->name;
				if($t->name!==NULL && $t->table!==NULL) $this->QueryColumnsPrefixed[$i] = (strlen($t->table)>1 ? $t->table."." :"" ).$t->name;
			}
		}
		if($this->error && $this->showDatabaseErrors){
			die("Query Error: ".$this->error."<br />SQL: ".$SQL);
		}
		elseif($return_affected){
			return $this->GetAffectedRows();
		}
		else{
			return $this->query;
		}
	}

/**
* Return mysql_fetch_assoc on a supplied result set.
* This permits you to save multiple result sets as local variabls in your script
* and then grab the arrays later
*/
	function fetchAssoc($result = NULL)
	{
		return ($result) ? @mysql_fetch_assoc($result) : @mysql_fetch_assoc($this->query);
	}
/**
* Send the multiple queries to the database.
*
* @param string $SQL = The SQL String to be sent to the database. Seperate statements with ";"
* @access public
*/	
	function MultiQuery($SQL){
		$sql = explode(";",$SQL);
		for($x=0;$x<count($sql);$x++)$this->Query($sql[$x]);
	}

/**
* Loops through the Result Set and returns the result set data
* @access public
*/

	function GetRows($assoc = false){
		$this->moreRows= ($assoc) ? @mysql_fetch_assoc($this->query) : @mysql_fetch_array($this->query);
		if($this->moreRows<1){
			@mysql_close($this->rlink);
		}
		return $this->moreRows;
	}


/**
* The number of rows that were affected by a query
* @access public
* @see GetRows()
*/
	function GetAffectedRows(){
		$this->affectedRows=@mysql_affected_rows($this->rlink);
        if ($this->affectedRows == -1)
            return FALSE;
        else
		    return $this->affectedRows;
	}

/**
 * The number of rows that were affected by a query (Select Statements Only) 
 * @returns int	  
 * @access public
 */
	function GetTotalRows(){
		$this->totalRows=@mysql_num_rows($this->query);
		return $this->totalRows;
	}
			
/**
* Get the mysql result set back from the database using mysql_fetch_array.
*
* @access public
* @returns mysql_fetch_array()
* @see Query()
*/			
	function Iterator($SQL){
		if($this->iteratorSql!=$SQL)$this->ITERATOR_isActive=false;
		if(!$this->ITERATOR_isActive){
			$this->Query($SQL);
			$this->iteratorSql = $SQL;
			$this->ITERATOR_isActive=true;
		}	
		$this->moreRows=@mysql_fetch_array($this->query);
		if($this->moreRows<1)@mysql_close($this->rlink);
		return $this->moreRows;
	}
	
	function getProperty($property){
		$value = is_null($this->Row($property));
		switch ($property){
			case (!$value):
				return $this->Row($property);
			break;
		}
	}
				
/**
* Get the column value from the result set array (after using getRows Method).
*
* @param string $column the name of the column	  
* @access public
* @see GetRows()
*/		
	function Row($column){
		return $this->moreRows[$column];
	}
				
/**
* Get the column names found in a result set i.e. [column].
*
* @returns array  
* @access public
*/				
	function GetQueryColumns(){
		return $this->QueryColumns;
	}
			
/**
* Get the column names AND their table prefixes from a result set i.e. [table].[column].
*
* @returns array  
* @access public
*/				
	function GetQueryColumnsPrefixed(){
		return $this->QueryColumnsPrefixed;
	}

/**
* Get the last inserted auto incrementing key NOTE: this is NOT transaction-safe;
*
* @returns array  
* @access public
*/		
	function GetLastId(){
		return mysql_insert_id($this->rlink);
	}
	
/**
* Set a local (or any other variable in the $_POST array so that it can be used by AutoInsert or AutoUpdate;
*
* @param string $name = name of the variable or column to be set;	
* @param string $value = value of the variable to be set;	
* @returns boolean  
* @access public
*/		
	function setColumnValue($name,$value){
		$_POST[$name]=$value;
		return (is_null($_POST[$name]) ? false : true);
	}
	
/**
* Will retrieve a list of the columns and columns types in the table. Column names are stored in $this->cols Array, and Column Types are stored in $this->columnTypes Array.
  Mulitple tables canbe listed seperated with a comma. "*" and aliasing is also supported on a limited basis.
*
* @param string $table Table names seperated by commas
* @access public
*/					
	function GetColumns($table){
		$this->cols = array();
		$this->columnTypes = array();
		$this->columnFlags = array();
		$this->fields = "";
		$this->columns = "";
		
		if(!strchr($table,",")){	
			if(strchr($table," as ")){
				$table=join("",$this->CheckAssociations($table));
			}
			$this->fields = mysql_list_fields($this->db_name,$table);
			$this->columns = mysql_num_fields($this->fields);
			for ($i = 0; $i < $this->columns; $i++) {
			    $this->cols[]=mysql_field_name($this->fields, $i);				
				$this->columnTypes[]=mysql_field_type($this->fields, $i);
				$this->columnFlags[]=mysql_field_flags($this->fields, $i);
			}	
			return $this->cols;
		}else{
			$table=$this->CheckAssociations($table);
			$x=0;
			while($x<count($table)){
				$this->GetColumns($table[$x]);
				$tempCols.=",".join(",",$this->cols);
				$tempcolumnTypes.=",".join(",",$this->columnTypes);
				$x++;
			}
			$this->cols=split(",",$tempCols);
			$this->columnTypes=split(",",$tempcolumnTypes);
		}	
	}
		
/**
* Will use Table metadata to loop thorugh a list of column values and match them up with the appropriate GLOBAL, POST or GET value to create an auto-insert SQl String.
*
* @param string $table Table Name
* @access public
* @returns String	  
*/			
	function AutoInsert($table){
		$x=0;
		$sqlString_temp=array();
		$sqlString_temp2=array();
		$sqlString="Insert Into $table (";
		$this->GetColumns($table);

		while($x<count($this->cols)){
			if($this->cols[$x]!="ID" && $this->columnTypes[$x]!="timestamp" && !strchr($this->columnFlags[$x],"auto_increment")){
				$sqlString_temp[].=$this->cols[$x];
			}
			$x++;
		}

		$x=0;
		$sqlString.=join(",",$sqlString_temp);
		$sqlString.=") VALUES (";
		
		while($x<count($this->cols)){
			if($this->cols[$x]!="ID" && $this->columnTypes[$x]!="timestamp" && !strchr($this->columnFlags[$x],"auto_increment")){
				$tempValue ="";
				if(isset($_POST[$this->cols[$x]]))$tempValue = $_POST[$this->cols[$x]];	
				$sqlString_temp2[].=$this->AddQuotes($this->columnTypes[$x]).$this->CheckValues($this->columnTypes[$x],$_POST[$this->cols[$x]]).$this->AddQuotes($this->columnTypes[$x]);
			}
			$x++;
		}	
		$sqlString.=join(",",$sqlString_temp2);
		$sqlString.=")";	
		$this->Query($sqlString);
		return $sqlString;
	}
		
/**
* Will use Table metadata to loop thorugh a list of column values and match them up with the appropriate GLOBAL, POST or GET value to create an auto-update SQl String.
  NOTE: If a POST value is received as NULL, no update is made.
*
* @param string $table Table Name
* @param string $constraint SQl WHERE clause and any other SQL Statements  
* @access public
* @returns String	  
*/
	function AutoUpdate($table,$constraint){
		$x=0;
		$sqlString="UPDATE $table SET ";
		$sqlString_temp=array();		
		$this->GetColumns($table);
		while($x<count($this->cols)){
			if($this->cols[$x]!="ID" && $this->columnTypes[$x]!="timestamp" && isset($_POST[$this->cols[$x]]) && !strchr($this->columnFlags[$x],"auto_increment")){
				$sqlString_temp[].=$this->cols[$x]."=".$this->AddQuotes($this->columnTypes[$x]).$this->CheckValues($this->columnTypes[$x],$_POST[$this->cols[$x]]).$this->AddQuotes($this->columnTypes[$x]);
			}
			$x++;
		}
		$sqlString.=join(", ",$sqlString_temp);
		$sqlString.=" $constraint";	
		$this->Query($sqlString);
		return $sqlString;
	}
			
/**
* Will validate if a value exists in the specified table. Will also return one or more recordset values as a nested array based on the specified 'Return Value'
*
* @param string $query = SQL Query  
* @param string $returnArray = Build a nested, associative array out of the record set, and return it as $this->returnValue 	  
* @access public
* @returns boolean
*/
	function InTable($query,$returnArray=true){
		$this->returnValue = array();
		if(func_num_args()==3){
			$returnCols=func_get_arg(0);
			$table=func_get_arg(1);
			$constraint=func_get_arg(2);						
			if($returnCols=="*"){
				$this->GetColumns($table);
				$tempcols = $this->cols;
			}else{
				$tempcols = explode(",",$returnCols);
			}						
			$query = "SELECT ".$returnCols." FROM ".$table." ".$constraint;
		}
			$temp = $this->Query($query,true);
			$tempcols = $this->GetQueryColumns();
		$xz=0;
		
		if($returnArray){	
			while($this->GetRows()){			
				$c=0;			
				while($c<count($tempcols)){
					$this->returnValue[$xz][$tempcols[$c]]=$this->Row($tempcols[$c]);
					$c++;
				}
				$xz++;			
			}	
			return ($xz>=1 ? true : false);	
		}else{
			return $this->GetAffectedRows();
		}		
	}
			
/**
* Will take collumns specified in 'returnCols' Param retreive the values from the database and set them to the scope of the assigned variable as a nested array.
*
* @param string $query = SQL Query
* @access public
* @returns Array
* @see GetTableVars()
*/					
	function GetTableVarsLocal($query){
		if(func_num_args()>1){
			$returnCols=func_get_arg(0);
			$table=func_get_arg(1);
			$constraint=func_get_arg(2);						
			if($returnCols=="*"){
				$this->GetColumns($table);
				$tempcols = $this->cols;
			}else{
				$tempcols = explode(",",$returnCols);
			}						
			$this->Query("SELECT ".$returnCols." FROM ".$table." ".$constraint);
		}else{
			$this->Query($query,true);
			$tempcols = $this->GetQueryColumns();
		}
		$xz=0;
		while($this->GetRows()){
			$c=0;
			while($c<count($tempcols)){
				$this->returnValue[$xz][$tempcols[$c]]=$this->Row($tempcols[$c]);
				$c++;
			}
			$xz++;
		}
		return $this->returnValue;
	}
		
/**
* Will take collumns specified in 'returnCols' Param retreive the values from the database and set them to the GLOBAL page scope.
*
* @param string $query = SQL Query
* @access public
* @type GLOBAL vars
* @see GetTableVarsLocal()
*/
	function GetTableVars($query){
		if(func_num_args()>1){		
			$returnCols=func_get_arg(0);
			$table=func_get_arg(1);
			$constraint=func_get_arg(2);			
			if($this->InTable($returnCols,$table,$constraint)){
			 	$n=array_keys($this->returnValue[0]);
			 	for($x=0;$x<count($this->returnValue[0]);$x++){
					$GLOBALS[$n[$x]]=$this->returnValue[0][$n[$x]];			
				}
			 }		
		}else{
			if($this->InTable($query)){
			 	$n=array_keys($this->returnValue[0]);
			 	for($x=0;$x<count($this->returnValue[0]);$x++){
					$GLOBALS[$n[$x]]=$this->returnValue[0][$n[$x]];
				}
			 }		
		}		
	}

/**
*  Allows you to connect to current or other database host, execute a query and build a dump file - works just like SELECT INTO OUTFILE - but allows you to create local dumps of remote data.
*
* @param string $sql = SQL Query
* @param string $file = File to be created
* @param array $connectionArray= Array of database conection info (host,user,password,db) - NOTE: This param is optional
* @param boolean $headers = include column headers (optional)
* @param boolean $fieldSep = specify field seperators - \t is default (optional)
* @param boolean $rowSep = specify row seperators - \n is defualt (optional)
* @param boolean $textQual = use a text qualifier, null is default (optional)
* @access public
* @returns void
*/
        function QueryToFile($sql,$file,$connectionArray = Array("host"=>"","user"=>"","password"=>"","db"=>""),$headers=false,$fieldSep=false,$rowSep=false,$textQual=false){
           $sameServer = ($connectionArray['host']!=$this->db_host || $connectionArray['db']!=$this->db_name ? false : true);
           if(!$sameServer){
                $oldHost = $this->db_host;
                $oldUser = $this->db_user;
                $oldPass = $this->db_pass;
                $oldDb   = $this->db_name;
                $this->SetConnect( $connectionArray['user'], $connectionArray['password'], $connectionArray['host'], $connectionArray['db']);
           }
           $this->Query($sql,true);
           $tempcols = $this->GetQueryColumns();
           if($headers){
               for($c=0;$c<count($tempcols);$c++){
                    $temp[]=(!$textQual ? null : $textQual) . $tempcols[$c] . (!$textQual ? null : $textQual);
               }
               $content .=implode((!$fieldSep ? "\t" : $fieldSep),$temp).(!$rowSep ? "\n" : $rowSep);
               $temp=array();
           }
           while($this->GetRows()){
               for($c=0;$c<count($tempcols);$c++){
                    $temp[]=(!$textQual ? null : $textQual) . trim($this->Row($tempcols[$c])) . (!$textQual ? null : $textQual);
               }
                    $content .=implode((!$fieldSep ? "\t" : $fieldSep),$temp).(!$rowSep ? "\n" : $rowSep);
               $temp=array();
               $xz++;
           }

           $con = fopen($file, "w");
           fwrite($con,$content);
           fclose($con);
        }


// ___________________________________________________________________________________;	
// 								Private Methods;
// ___________________________________________________________________________________;

/**
* This method handles any errors resulting from the inability to connect, sending out email warnings and switching database servers.
  NOTE: THis function needs a little work.
*	  
* @access private
* @returns boolean
*/
	  function HandleConnectionError(){
		  if(!$this->rlink){
		  	if($this->$connectionErrors){	
			   if(isset($this->errorContacts))SendMessage($this->errorContacts, "a test", "sgantt@esitemarketing.com", "(Client Name) E-mail Club Error Message", "html", "An ERROR has occurred on the (Client Name) website, page <b>$PHP_SELF</b>.<br \><br \>The marketing tool is having trouble connecting to the database at: ".$this->db_host." with database name: ".$this->db_name.".", "" );	 			
			   if(isset($this->connectionErrorMessage))echo $this->errorMessageDisplay; 						   
			}
			return true;
		  }else{
		  	return false;
		  }
	  }
	  	
/**
* This method looks for and validates strings regarding tables anmes using table aliases.
*
* @param string $items 	  
* @access private
* @returns Array
*/			
	function CheckAssociations($items){
			$item = explode(",",$items);
			$x=0;
			while($x<count($item)){
				if(strchr($item[$x]," as ")){
					$tcol = explode(" as ",$item[$x]);
					$itemS[$x]=trim($tcol[0]);
				}else{		
					$itemS[$x]=$item[$x];
				}	
				$x++;
			}
			return $itemS;		
	}
		
/**
* This method looks for and validates strings regarding column names and column aliasing in an  isolated part of an SQL String.
*
* @param string $items 
* @param string $direction	  	  
* @access private
* @returns Array
*/			
	function CheckColumnAssociations($items,$direction){
		if($direction){
			if(strchr($items,"CONCAT(")){
				$temp1 = strchr($items,"CONCAT(");
				$temp2 = strpos($temp1,")");
				$concat = substr($temp1,0,$temp2);
				$items = str_replace($concat,"",$items);
			}
			$item = explode(",",$items);
			
			$x=0;
			while($x<count($item)){
				if(strchr($item[$x]," as ")){
					$tcol = explode(" as ",$item[$x]);
					$itemS[$x]=trim($tcol[1]);	
				}else{		
					$itemS[$x]=$item[$x];
				}	
				$x++;
			}
			return $itemS;
		}else{
			$item = explode(",",$items);
			$x=0;
			while($x<count($item)){
				if(strchr($item[$x]," as ")){
					$tcol = explode(" as ",$item[$x]);
					$itemS[$x]=trim($tcol[0]);				
				}else{
					$itemS[$x]=$item[$x];
				}	
				$x++;
			}
			return $itemS;		
		}
	}
		
/**
* This method looks for aliasing in an isolated part of the SQL string and replaces it with the original table name.
*
* @param string $tables 
* @param string $str  	  
* @access private
* @returns String
*/					
	function RepairColumnAssociations($tables,$str){
		$mytables = explode(",",$tables);
		for($x=0;$x<count($mytables);$x++){
			if(strchr($mytables[$x],"as ")){
				$str = str_replace(trim(substr(strchr($mytables[$x],"as "),3,strlen($mytables[$x]))).".",trim(substr($mytables[$x],0,strpos($mytables[$x]," as"))).".",$str);
			}
		}
		return $str;
	}		
/**
* This method looks for and validates columns and tables when the '*' is used in and isolated part of an SQl String.
*
* @param string $items	  
* @access private
* @returns String
*/			
	function CheckTableStar($items){
		$item = split(",",$items);
		$Nitems="";
		$Nitems2=array();
		for($x=0;$x<count($item);$x++){
				if(strchr($item[$x],".*")){
					$tcol = explode(".*",$item[$x]);
					$this->GetColumns($tcol[0]);
					$Nitems.= join(",",$this->cols);
				}else{
					$Nitems2[]=trim($item[$x]);
				}
		}
		$rsting="";
		if($Nitems!="")$rsting .= $Nitems;
		if(count($Nitems2)>0){
			if($rsting!=""){
				$rsting = join(",",$Nitems2).",".$rsting;
			}else{
				$rsting = join(",",$Nitems2);
			}
		}
		return $rsting;
	}		
/**
* Method checks the field value during an Auto Insert or an Auto Update and replaces with either quotes or no quotes depending on the column type.
  Supports String, Blob (all), Enum, Text (all), set, date and datetime, and time.
*
* @param string $cval	  
* @access private
* @returns String
*/				
	function AddQuotes($cval){
		if($cval=="string" || strchr($cval,"blob") || strchr($cval,"text") || $cval=="enum" || $cval=="set" || strchr($cval,"date") || $cval=="time"){
			return "'";
		}
	}			
/**
* Method checks the field value during an Auto Insert or an Auto Update and replaces with either quotes or no quotes depending on the value.
*
* @param string $cval	  
* @param string $value	  
* @access private
* @returns String
*/				
	function CheckValues($cval,$value){
		if((!$this->AddQuotes($cval)=="'" && $value=="") || (!$this->AddQuotes($cval)=="'" && is_null($value))){
			return 0;
		}else{
			return $value;
		}
	}
}
?>