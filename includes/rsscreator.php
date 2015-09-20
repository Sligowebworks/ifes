<?php
//require_once('includes/conf.php');
class RSS {
    function get_rsscontent($newslimit=20) {
        $str = '<?xml version="1.0" encoding="utf-8" ?>
        <rss version="0.91">
        <channel>
        <title>News from ElectionGuide.org</title>
        <link>http://www.electionguide.org</link>
        <description>News from ElectionGuide.org</description>
        <language>en-us</language>';

        $sql = "SELECT news_items.id, news_items.ext_id, news_items.event,
        IF(news_items.region=0, '', regions.region) as region,
        IF(news_items.country=0, '', country.country_name) as country,
        news_title,
        DATE_FORMAT(news_date, '%m/%d/%Y') AS formatted,
        IF(is_external=1, news_link, CONCAT('http://www.electionguide.org/news.php#', news_items.id)) as news_link,
        news_content,
        IF(is_external=1, external_news.rss_title, 'ElectionGuide') as news_source,
        is_external,
        news_items.date_updated
        FROM news_items
        LEFT JOIN regions on regions.id=news_items.region
        LEFT JOIN country on country.id=news_items.country
        LEFT JOIN external_news ON external_news.id=news_items.ext_id
        WHERE news_items.is_active=1
        ORDER BY news_date DESC LIMIT 0, ".$newslimit;

        $db = new Db();
        $db->Query($sql);
        if ($db->GetAffectedRows()>0) {
            while($data = $db->fetchAssoc()) {
                $urlparts = parse_url($data['news_link']);
                $link = (in_array('scheme', array_keys($urlparts))) ? $data['news_link'] : 'http://'.$data['news_link'];

                $str .= '<item>
                    <title>'.htmlspecialchars(trim($data['news_title'])).'</title>
                    <link>'.$link.'</link>
                    <description>'.htmlspecialchars(trim($data['news_content'])).'</description>
                </item>';
            }
        } else {
            $str .= '<item>
                <title>Nothing found matching your search criteria.</title>
                <link>http://www.electionguide.org</link>
                <description>Nothing found matching your search criteria.</description>
            </item>';
        }

        $str .= '</channel></rss>';
        return $str;
    }
}
?>
