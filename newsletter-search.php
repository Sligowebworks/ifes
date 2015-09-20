<?php
    include('includes/conf.php');
	$section = "search";
	$page = "news";
	sess::set('keyword', ($_REQUEST['keyword']!='' ? $_REQUEST['keyword'] : ''));
	sess::set('country', ($_REQUEST['country']!='' ? $_REQUEST['country'] : ''));
	sess::set('year', ($_REQUEST['year']!='' ? $_REQUEST['year'] : ''));
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - News Search</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>

<body id="body-<?php echo $section; ?>">

	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			 <?php include( "includes/nav-search.php" ); ?>
				<h2>News Search:</h2>
				<p>Election Guide posts international news from RSS feeds of major outlets such as Reuters, BBC, CNN, and All Africa.com as well as original news briefs written by Election Guide staff. Election Guide news focuses on election-related news including results, legal changes, election date announcements, and major incidents of violence or alleged fraud. You can search our archives for stories through the filters below. Your results will display full text of Election Guide stories and links to external stories.</p>
				<form action="news-search.php" method="get">
				<table id="country-meta"><tr><td>
                <label for="country">By Country:</label><br />
        		<?php
        		include('includes/search_countries.php');
        		$countriesfinal = array(''=>'Any');
                foreach($countries as $key=>$value)
        		  $countriesfinal[$key] = $value;
                echo Common::select_item("country", $countriesfinal, sess::get('country'), ' style="width:200px;"'); ?>
                </td><td>
        		<label for="year">By Year:</label><br />
        		<?php
                $years['any'] = 'Any';
                foreach(range(1998, date('Y')+5) as $value)
                    $years[$value] = $value;
                echo Common::select_item('year', $years, sess::get('year'));
                ?>
                </td><td>
                <label for="keyword">By Keyword: </label><br />
                <input type="text" name="keyword" id="keyword" size="10" value="<?php echo sess::get('keyword'); ?>" />
                </td><td><br />
                <input type="hidden" name="submitted" value="1" />
  		        <input type="image" src="images/button-search.gif" name="submit" id="submit-search" value="Search" alt="Search" />
                </td></tr></table>
                </form>
                <br />
                	
                	<?php
                	if (isset($_GET['submitted']) && (trim($_GET['keyword'])!='' || trim($_GET['country'])!='' || trim($_GET['year'])!='any')) {
                        $db = new Db();
                       
                        $sql = "SELECT ext_id,
                        country.id as country_id,country.country_name,
                        news_title,news_date,news_link,news_content,is_external,
                        external_news.rss_title
                        FROM news_items
                        LEFT JOIN external_news ON external_news.id=news_items.ext_id
                        LEFT JOIN country ON country.id=news_items.country
                        WHERE (news_title LIKE '%".sess::get('keyword')."%' OR news_content LIKE '%".sess::get('keyword')."%')
                        AND news_items.is_active=1 ";
                        if (sess::get('year')!='' && sess::get('year')!='any')
                            $sql .= " AND YEAR(news_items.news_date)=".sess::get('year');

                        if (sess::get('country')!='')
                            $sql .= " AND country.id=".sess::get('country');

                        $sql .= " ORDER BY news_items.date_updated DESC";

                        $db->Query($sql);
                        $rows = $db->GetAffectedRows();
                        if ($rows>0) {
                            echo '<p>Found '.$rows.' results matching your search criteria.</p>';
                            while($data = $db->fetchAssoc()) {
                                $news_date = date('m/d/Y', strtotime($data['news_date']));
                                $cont = strip_tags($data['news_content']);
                                $title = html_entity_decode(strip_tags($data['news_title']));
                                $kw = trim($_GET['keyword']);
                                echo '<h3><a href="country.php?ID='.$data['country_id'].'">'.trim($data['country_name']).'</a></h3>
                                <p style="margin-bottom:0;">Posted: '.$news_date.'<br />'.ucfirst($cont).'</p>
                                <p style="padding:0;margin:0;">';
                                
                                if ($data['ext_id']!=0)
                                    echo '<a href="'.$data['news_link'].'" target="_blank">Read full story</a>.';
                                else
                                    echo '<a href="newsletter.php">Read more ElectionGuide News</a>.';
                                echo ' Source: '.$data['rss_title'].'</p>';
                            }
                        } else {
                            echo '<p>Nothing found matching your search criteria.</p>';
                        }
                    } else if (isset($_GET['submitted'])) {
                        echo '<p>Please enter a search term.</p>';
                    }
                	?>
			</div>
			<hr />
			<div id="sidebar-wrapper">
				<?php
					include( "includes/sidebar-search.php" );
					include( "includes/sidebar-elections.php" );
					include( "includes/sidebar-news.php" );
				?>
			</div>
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>
</body>
</html>
