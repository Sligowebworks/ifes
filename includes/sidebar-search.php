<div id="search-wrapper">
    <h2>Quick Search</h2>
    <form action="search-results.php" method="get">
        <label for="type">By Election Type:</label><br />
        <?php echo Common::select_item("type", Common::get_election_types(TRUE), sess::get('type')); ?><br />
        
		<label for="country">By Country:</label><br />
		<?php
		include('includes/search_countries.php');
		$countriesfinal = array(''=>'Any');
        foreach($countries as $key=>$value)
		  $countriesfinal[$key] = $value;
        echo Common::select_item("country", $countriesfinal, sess::get('country')); ?><br />
		
		<label for="year">By Year:</label><br />
		<?php
		$year = ($_REQUEST['search_year']) ? $_REQUEST['search_year']: sess::get('search_year');
        $years['any'] = 'Any';
        foreach(range(1998, date('Y')+5) as $value)
            $years[$value] = $value;
        echo Common::select_item('search_year', $years, $year);
        ?><br />
        <input type="hidden" name="submitted" value="1" />
		<input type="image" src="images/button-search.gif" name="submit" id="submit-search" value="Search" alt="Search" />
        <a href="advanced-search.php"><img src="images/button-advanced-search.gif" alt="Advanced Search" /></a>
	</form>
     <p><a href="voter-turnout.php">Quick Link to Voter Turnout</a></p>
</div>
