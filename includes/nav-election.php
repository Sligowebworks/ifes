<ul id="nav-secondary">
	<li><a href="election.php?ID=<?php echo $id; ?>"<?php echo ($page=="overview")?' class="current"':''; ?>>Overview</a></li>
	<li><a href="results.php?ID=<?php echo $id; ?>"<?php echo ($page=="results")?' class="current"':''; ?>>Results</a></li>
	<li class="last"><a href="interest.php?ID=<?php echo $id; ?>"<?php echo ($page=="links")?' class="current"':''; ?>>Of Interest/Links</a></li>
</ul>