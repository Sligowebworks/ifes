<?php
echo '<ul id="nav-secondary">
<li><a href="country.php?ID='.$id.'"'.(($page=="overview")?' class="current"':'').'>Overview</a></li>
<li><a href="country-events.php?ID='.$id.'"'.(($page=="events")?' class="current"':'').'>Elections</a></li>
<li><a href="country-news.php?ID='.$id.'"'.(($page=="news")?' class="current"':'').'>News</a></li>
<li'.($data['region']==0?' class="last"':'').'><a href="country-links.php?ID='.$id.'"'.(($page=="links")?' class="current"':'').'>Links</a></li>';
if ($data['region']!=0)
    echo '<li class="last"><a href="region.php?ID='.$data['region'].'"'.(($page=="region")?' class="current"':'').'>Region</a></li>';
echo '</ul>';
?>
