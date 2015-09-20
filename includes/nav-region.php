<?php
echo '<ul id="nav-secondary">
    <li><a href="region.php?ID='.$id.'"'.(($page=="overview")?' class="current"':'').'>Overview</a></li>
    <li><a href="region-events.php?ID='.$id.'"'.(($page=="events")?' class="current"':'').'>Elections</a></li>
    <li class="last"><a href="region-links.php?ID='.$id.'"'.(($page=="links")?' class="current"':'').'>Related Links</a></li>
</ul>';
?>
