 <!--the ad rotator code starts here -->

        <?php
$Img1 = "http://www.electionguide.org/images/ads/LGS_IFESbanner.gif";
$Alt1 = "Lantrade";
$Url1 = "http://lantrade.com/";

$Img2 ="http://www.electionguide.org/images/ads/CODE-ElectionGuide-home-pg.gif";
$Alt2 = "CODE";
$Url2 = "http://www.codeinc.com/";

$num = rand (1,2);

$Image = ${'Img'.$num};
$Alt = ${'Alt' .$num};
$URL = ${'Url'.$num};
?>
 <div class="ad banner">
 <?php
Print "<a href=\"".$URL."\"><img src=\"".$Image."\" alt=\"".$Alt."\" /</a>"; 
?> 
</div>
        
        <!--the rotator code ends here -->