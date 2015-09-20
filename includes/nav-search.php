<?php
echo '<ul id="nav-secondary">
<li><a href="search-results.php"'.(($page=="overview")?' class="current"':'').'>Search</a></li>
<li><a href="advanced-search.php"'.(($page=="advanced")?' class="current"':'').'>Advanced</a></li>
<li><a href="news-search.php"'.(($page=="news")?' class="current"':'').'>News Search</a></li>
<li class="last"><a href="reports.php"'.(($page=="reports")?' class="current"':'').'>Reports</a></li>
</ul>';
?>
