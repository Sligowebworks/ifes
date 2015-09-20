<?php
class Table extends Dbtable{

    function Table() {
        parent::Dbtable($dbhost=false, $dbuser=false, $dbpassword=false, $dbname=false);
    }

    function set_options() {
        $this->setProperty("tableId", 'dbtable');
        $this->setProperty("tableClass", 'ruler');
        $this->setProperty("imagepath","images/");
        $this->setProperty("showPagination", 'bottom');
        $this->setProperty("paginationid", 'pagination');
        $this->setProperty("setresultpages",20);
        $this->setProperty("showresults",TRUE);
    }

    function set_int_options() {
        $this->setProperty("tableId", 'inttable');
        $this->setProperty("tableClass", 'ruler');
        $this->setProperty("imagepath","images/");
        $this->setProperty("activeheaders", FALSE);
        $this->setProperty("setresultpages", FALSE);
        $this->setProperty("showtotalrows",FALSE);
    }
    
    function set_search_options() {
        $this->setProperty("tableId", 'sdbtable');
        //$this->setProperty("tableClass", 'ruler');
        $this->setProperty("imagepath","images/");
        $this->setProperty("setresultpages", FALSE);
        $this->setProperty("activeheaders", FALSE);
        $this->setProperty("altrowclasses", 'row1,row2');
        $this->setProperty("showresults",TRUE);
    }

    function set_user_options() {
        $this->setProperty("tableId", 'election-calendar');
        //$this->setProperty("tableClass", 'ruler');
        $this->setProperty("imagepath","images/");
        $this->setProperty("setresultpages", FALSE);
        $this->setProperty("showtotalrows",FALSE);
        //$this->setProperty("activeheaders", FALSE);
        //$this->setProperty("altrowclasses", 'row1,row2');
    }
}
?>
