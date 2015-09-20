<div id="news-wrapper">
    <h2><a href="news.php">Election Updates &amp; News:</a></h2>
    <dl>
    <?php
    require_once('includes/ElectionList.Class.php');
    if (strstr($_SERVER['PHP_SELF'], 'country'))
        ElectionList::country_news($_REQUEST['ID']);
    else if (strstr($_SERVER['PHP_SELF'], 'region'))
        ElectionList::region_news($_REQUEST['ID']);
    else
        ElectionList::generic_news();
    ?></dl>
    <p style="text-align:right;margin: 0 0 5px 0;font-size: 1.2em;"><a href="news.php">More News</a></p>
    <p>
        <a href="rss/news.xml" target="_blank"><img src="images/icons/xml.gif" alt="XML" /></a>
        <a href="rss/news.xml" target="_blank" style="text-align:right;margin: 0 0 5px 0;font-size: 1.2em;">Global elections news</a>
    </p>
    <p>
        <a href="/calendar.xml" target="_blank"><img src="images/icons/xml.gif" alt="XML" /></a>
        <a href="/calendar.xml" target="_blank" style="text-align:right;margin: 0 0 5px 0;font-size: 1.2em;">Election calendar</a>
    </p>
</div>
