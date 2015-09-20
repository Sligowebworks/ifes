<?
/** 
 * Auto Database Table Class (Extended from Class DB)
 *
 * @author E-Site Marketing
 * @version 1.0
 * @since 1.0
 * @access public
 * @copyright E-Site Marketing, LLC 2003
 *
 */
class Dbtable extends Db{

	var $_tableExtraCols = array();	
	var $_tableHeaders = array();
	var $_tableAlterColumnValue = array();		
	var $_tableFormatColumn = array();	
	var $_clickable = TRUE;
	var $_PaginateResults = 20;
	var $_tableDisplayPageLinks = TRUE;
	var $_tablePageLinkBreakAfter = 20;
	var $_tableCaption;
	var $_uniqid;		
	var $queryString = '';
	var $_dir = '';
	var $_orderby = '';
	var $_uid = '';
	var $_p = '';
	var $_addfooter = FALSE;
	var $_trows = '';
	var $_jsMouseOvers = FALSE;
	var $_imagePath = 'images/';
    var $_useRuler = TRUE;
	var $_rulerlocation = 'js/table_ruler.js';
	var $_norowsmsg = '';
	var $_customDb = FALSE;
    var $debugQueryA = '';
    var $_numPageLinks = 10;
    var $_previous = 'previous';
	var $_next = 'next';
  	var $_start = 'start';
	var $_end = 'end';
	var $_startChar = ' ';
	var $_endChar = ' ';
	var $_paginationId = 'pagination';
	var $_paginationClass;
    var $_showPagination = 'both';
    var $_showtotalRows  = TRUE;

	function Dbtable($dbhost=false, $dbuser=false, $dbpassword=false, $dbname=false){
		//	Option to pass on different database params than the parent DB class;
		if($dbhost){
			$this->_customDb = true;
			$this->dbhost = $dbhost;
			$this->dbuser = $dbuser;
			$this->dbpassword = $dbpassword;
			$this->dbname = $dbname;
		}
		$this->qryStr   = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : NULL;
		parse_str($this->qryStr, $this->qryCurr); //current url
		$this->qryUrl = $this->qryCurr;
		$this->imagePath = array();
	}

    function setProperty($property,$value){
		$property = strtolower($property);

		switch($property){
			//from DB class;
			case "user":
				$this->db_user=$value;
			break;
			
			case "password":
				$this->db_password=$value;
			break;
			
			case "database":
				$this->db_name=$value;
			break;
			
			case "host":
				$this->db_host=$value;
			break;
				
			case "queryerrors":
				$this->showDatabaseErrors=$value;
			break;
			
			case "connectionerrors":
				$this->connectionErrors=$value;
			break;
			
			case "errorcontacts":
				$this->errorContacts=$value;
			break;
			
			case "persistent":
				$this->persistent=$value;
			break;
			
			case "errormessagedisplay":
				$this->errorMessageDisplay=$value;
			break;
			
			//specific to this class;
			
			case "headeritem":
				$tempval = explode(",", $value);
				$this->_tableHeaders[$tempval[0]] = $tempval[1];
			break;
				
			case "headers":
				$tempval = explode(",", $value);
				$this->_tableHeaders = explode(",", $value);
			break;
			
			case "altercolumns":
			    $tempval = explode(",",trim($value));
				$this->_alterColValue[$tempval[0]] = $tempval;
			break;
			
			case "orderbyoptions":
				$this->_orderbyopts = $value;
			break;

			case "setresultpages":
				$this->_PaginateResults = $value;
			break;
				
			case "tablecaption":
				$this->_tableCaption = $value;
			break;

			case "uniqid":
				$this->_uniqid = $value;
			break;
			
			case "activeheaders":
				$this->_clickable = $value;
			break;
			
			case "showpagelinks":
				$this->_tableDisplayPageLinks = $value;
			break;
			
			case "showtotalrows":
				$this->_showtotalRows = $value;
			break;
			
			case "imagepath":
				$this->_imagePath = $value;
			break;
	
			case "norowsmessage":
				$this->_norowsmsg = $value;
			break;


            //set tableid , class, summary
            case "tableid":
                $this->_tableid = $value;
            break;

            case "tableclass":
                $this->_tableclass = $value;
            break;

            case "tablesummary":
                $this->_tablesummary = $value;
            break;

            // set colgroup classes
            case "colgroup":
                $this->_colgroup = explode(',', $value);
            break;

            // ids and classes for rows: tr
            case "trids":
                $this->_trid = explode(',', $value);
            break;

            case "altrowclasses":
                $this->_alt_rowclasses = explode(',', $value);
            break;
			
            // id and classes for header row: th
            case "thids":
                $this->_thid = explode(',', $value);
            break;
            case "thclasses":
                $this->_thclass = explode(',', $value);
            break;
			
            // ids and classes for data cells: td
            case "tdids":
                $this->_tdid = explode(',', $value);
            break;
            case "tdclasses":
                $this->_tdclass = explode(',', $value);
            break;

            // classes for tfoot row: td
            case "tfootclasses":
                $this->_tfootclass = explode(',', $value);
            break;

            // use js for row highlighting
            case "useruler":
                $this->_useRuler = $value;
            break;
            case "rulerlocation":
                $this->_rulerlocation = $value;
            break;

            // number of links in the slider
            case "numpagelinks":
                $this->_numPageLinks = $value;
            break;

            // show pagination top/bottom/both
            case "showpagination":
                $this->_showPagination = $value;
            break;
			
            // pagination decoration
            case "paginationid":
                $this->_paginationId = $value;
            break;

            case "paginationclass":
                $this->_paginationClass = $value;
            break;
		}
	}
	
	function paginate () {
		if($this->_PaginateResults) {
			
            $pageid = ($this->_paginationId)   ? ' id='.$this->_paginationId : '';
			$pageclass = ($this->_paginationClass)   ? ' class='.$this->_paginationClass : '';
            $params = array(
        	    'totalItems' => $this->totalRows,
        	    'perPage' => $this->_PaginateResults,
        	    'delta' => 5,
        	    'append' => true,
        	    'spacesBeforeSeparator' => 1,
        	    'spacesAfterSeparator' => 1,
        	    'clearIfVoid' => TRUE,
        	    'urlVar' => 'p',
        	    'useSessions' => true,
        	    'closeSession' => true,
        	    'mode' => 'Sliding');
        	    
			$pager = & Pager::factory($params);
			$page_data = $pager->getPageData();
			$links = $pager->getLinks();
			
			$top = ceil($this->totalRows/$this->_PaginateResults);
            $totpages = ($top==1) ? 0 : $top;
            $str = ($top>1) ? $top.' pages.' : '';

			list($from, $to) = $pager->getOffsetByPageId();
            $ret .= '<div '.$pageid.$pageclass.'>Displaying ['.$from.' - '.$to.'] of '.$pager->_totalItems.' results. '.$str.'<br />
			 '.$links['back'].' '.$links['pages'].' '.$links['next'].' </div>';
			return $ret;
		}	
	}
	
	function Output($query) {
		if ($this->getDriver() == 'odbc')
			$this->_PaginateResults = false;
		
		$this->_fullQuery = true;
		$this->str = '';
        ($this->_customDb ? $this->SetConnect( $this->dbuser, $this->dbpassword, $this->dbhost, $this->dbname ) : $this->Connect());

		if($this->_PaginateResults){
            if (!in_array('_trows', array_keys($this->qryUrl))) {
				if(!strchr(strtoupper($query), "ORDER BY") && in_array('_dir', array_keys($this->qryUrl)) && $this->qryUrl['_uid']==$this->qryUrl['_uniqid']) {
					$query .= " ORDER BY ".$this->qryUrl['_orderby']." ".$this->qryUrl['_dir'];
				}

				$this->Query($query);
                //$this->debugQueryA = $query;
				$this->totalRows = $this->GetTotalRows();
				$this->qryUrl['_trows'] = $this->totalRows;
		   	} else {
                $this->totalRows = $this->qryUrl['_trows'];
		   	}
		}

		// :::::: Begin table;
		$this->str .= $this->_tableCaption;
		
        if ($this->_showPagination == 'top' || $this->_showPagination == 'both')
		    $this->str .= $this->paginate();
        
		//print_r($this->qryUrl);

        $tableid 	= ($this->_tableid == '') 		? '' : ' id="'.$this->_tableid.'"';
        $tableclass = ($this->_tableclass == '') 	? '' : ' class="'.$this->_tableclass.'"';
        $tablesummary = ($this->_tablesummary == '')? '' : ' summary="'.$this->_tablesummary.'"';
        $this->str .= "\n<table".$tableid.$tableclass.$tablesummary.">\n";

		// :::::: Connect to database;
		if(!strchr(strtoupper($query), "ORDER BY") && in_array('_dir', array_keys($this->qryUrl)) && $this->qryUrl['_uid']==$this->qryUrl['_uniqid'])
			$query.= " ORDER BY ".$this->qryUrl['_orderby']." ".$this->qryUrl['_dir'];

		if($this->_PaginateResults) {
            $limit = ($this->qryUrl['p']==1) ? 0 : ($this->qryUrl['p']*$this->_PaginateResults)-$this->_PaginateResults;
            $limit = ($limit<0) ? 0: $limit;
            $query .= " LIMIT ".$limit.", ".$this->_PaginateResults;
		}
		
        $this->Query($query,true);
        while($data = $this->fetchAssoc())
        	$this->data[] = $data;
        if(!$this->_PaginateResults)
       		$this->totalRows = count($this->data);
		$columns = implode(',', $this->GetQueryColumnsPrefixed());
		$ncolumns = explode(',', $columns);

        if ($this->_tableCaption)
            $this->str .= "<caption>".$this->_tableCaption."</caption>\n";
        if (count($this->_colgroup) > 0) {
            $this->str .= "<colgroup>\n";
			if (count($this->_colgroup) == 1) {
				foreach($ncolumns as $data)
					$this->str .= "\t".'<col class="'.trim($this->_colgroup).'" />'."\n";
			} else {
				foreach($this->_colgroup as $data)
					$this->str .= ($data == '') ? "\t<col />" : "\t".'<col class="'.trim($data).'" />'."\n";
			}
            $this->str .= "</colgroup>\n";
        }
		$this->str .= "<thead><tr>\n";

        $this->qryUrl['_dir'] = (!in_array('_dir', array_keys($this->qryUrl)) || $this->qryUrl['_dir']=='DESC') ? 'ASC' :'DESC';
		// :::::: Loop through columns and create table cells;
		$x=0;
		for($x=0; $x<count($ncolumns); $x++) {
			if(count($this->_tableHeaders) > 0
                && (trim($this->_tableHeaders[$x]) == 'null' || trim($this->_tableHeaders[$x]) == '')
                && (trim($this->_alterColValue[$x]) == '' || trim($this->_alterColValue[$x]) == 'null')) {
			} else {
				$img_arrow = '';
                if ($this->_orderbyopts!='')
                    $opts = explode(',', $this->_orderbyopts);
                else
                    $opts = $ncolumns;
                    
                    if($this->_clickable) {
        				if($this->qryCurr['_orderby'] == $opts[$x])
        					$img_arrow = '</a> <img src="'.$this->_imagePath.'_table_'.$this->qryCurr['_dir'].'.gif" border="0" alt="'.$this->qryUrl['_dir'].'" style="border:0" />';

                        $this->qryUrl['_orderby'] = urlencode($opts[$x]);
                        $qry = http_build_query($this->qryUrl);
    					$argStr = '<a href="'.$_SERVER['PHP_SELF'].'?'.$qry.'">';
    				} else {
    					$argStr = '';
    				}

				if(isset($this->_tableHeaders[$x]) && $this->_tableHeaders[$x] != '') {
					$mvalue = $this->_tableHeaders[$x];
				} else {
					$mvalue = substr(strtoupper($ncolumns[$x]),0,1).substr($ncolumns[$x],1,strlen($ncolumns[$x]));
				}

				if($this->_clickable && in_array('_orderby', array_keys($this->qryUrl))){
					$argStr2 = '</a>';
				} else if ($this->_clickable && !in_array('_orderby', array_keys($this->qryUrl))) {
					$argStr2 = '</a>';
				} else {
					$argStr2 = '';
				}
                $theid = ($this->_thid[$x]== '') ? '' : ' id="'.trim($this->_thid[$x]).'_'.$x.'"';
				
				if (count($this->_thclass)==1)
					$class = ' class="'.trim($this->_thclass[0]).'"';
				else
                	$class = ($this->_thclass[$x]== '') ? '' : ' class="'.trim($this->_thclass[$x]).'"';
					
				$this->str .= "\t".'<th scope="col"'.$theid.$class.'>'.$argStr.$mvalue.$argStr2.$img_arrow."</th>\n";
			}
		}

        $this->str .= "</tr></thead>\n";
        
        if ($this->_addfooter)
            $this->str .=$this->add_footer();
            
        $this->str .= "<tbody>\n";

        if (is_array($this->data)) {
        foreach($this->data as $xv => $values){
			if ($this->aRows == (count($this->_alt_rowclasses)-1) || !isset($this->aRows)) {
				$this->aRows = 0;
			} else {
				$this->aRows++;
			}

            //$xv++;
            $thestring =  (count($this->_alt_rowclasses)>0) ? ' class="'.$this->_alt_rowclasses[$this->aRows].'"' : '';
            //$rowid =  (count($this->_alt_rowclasses)>0) ? ' id="'.$this->_alt_rowclasses[$this->aRows].'"' : '';
            $treid = ($this->_trid[$xv]== '') ? '' : ' id="'.trim($this->_trid[$xv]).'_'.$xv.'"';
            $this->str .= "<tr".$thestring.$treid.">\n";

            foreach($ncolumns as $x => $colheader){
            	$parts = explode('.', $colheader);
            	$header = (count($parts)==2) ? $parts[1] : $colheader;
				if(count($this->_tableHeaders)>0 && ($this->_tableHeaders[$x]=='null' || trim($this->_tableHeaders[$x])=='') && ($this->_alterColValue[$x]==""||$this->_alterColValue[$x]=="null")){
					// :::::: hidden column ends up here;
				} else {
					if (isset($this->_alterColValue[$x]) && $this->_alterColValue[$x]!="") {
                        $params[$x] = $this->_alterColValue[$x];
                        $col[$x] = array_shift($params[$x]);
                        $callback[$x] = array_shift($params[$x]);

                        $retarray = array();
                        foreach($values as $key => $value) {
	                        if (in_array($key, $params[$x]))
	                        	$retarray[$key] = $value;
                        }
                        $left = array_diff($params[$x],array_keys($retarray));
                        if(count($left)>0) {
                            foreach($left as $addval) {
                                list($key,$value) = explode('=',$addval);
                                $retarray[$key] = $value;
                            }
                        }
						$ival = call_user_func_array($callback[$x], array($retarray));
                    } else {
						$ival = $values[$header];
					}

                    if (count($this->_tdclass)==1)
                        $class = ' class="'.trim($this->_tdclass[0]).'"';
                    else
                        $class = ($this->_tdclass[$x]== '') ? '' : ' class="'.trim($this->_tdclass[$x]).'"';

                    $theid = ($this->_tdid[$x]== '') ? '' : ' id="'.trim($this->_tdid[$x]).'_'.$xv.'_'.$x.'"';
                    $this->str .= "\t<td".$theid.$class.">".$ival."</td>\n";
				}
			}
			$this->str .= "</tr>\n";
		}
		}
		$this->str .= "</tbody></table>\n";
		
		if ($this->_PaginateResults) {
            if ($this->_showPagination == 'bottom' || $this->_showPagination == 'both')
                $this->str .= $this->paginate();
			//$this->str .= "<p class=\"results\">".(ceil($this->qryUrl['_pcnt']/$this->_PaginateResults)+1)." out of ".ceil(($this->totalRows/$this->_PaginateResults))." Pages. Total ".$this->totalRows." Results.</p>\n";
		} else if($this->totalRows && $this->_showtotalRows) {
			$this->str .= "<p class=\"results\">".$this->totalRows." Records.</p>\n";
		}

		$this->debugQueryB = (func_num_args() <= 1 ? $query : "SELECT ".$columns." FROM ".$tables." ".$constraint);

		if ($this->totalRows <1 && $this->_norowsmsg != '') {
			$this->str = "\n<p>".$this->_norowsmsg."</p>";
		}

		return $this->str;
	}
	
	function add_footer() {
        $str = "<tfoot><tr>\n";
        foreach($this->_tableHeaders as $key => $data) {
			if (count($this->_tfootclass) == 1)
				$class = ' class="'.trim($this->_tfootclass[0]).'"';
			else
            	$class = ($this->_tfootclass[$key] == '') ? '' : ' class="'.trim($this->_tfootclass[$key]).'"';
            $str .= "\t".'<td scope="row"'.$class.'>&nbsp;</td>'."\n";
        }
        $str .= "</tr></tfoot>\n";
        return $str;
    }
}
?>
