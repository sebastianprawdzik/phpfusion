<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: arioobarzan_jquery_news_slider_panel.php
| Author : AriooBarzan
| Email: arioobarzan@hotmail.com
| Web: http://www.trl.ir/
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }
if (file_exists(INFUSIONS."arioobarzan_jquery_news_slider_panel/locale/".LOCALESET."locale.php")) {
	require_once INFUSIONS."arioobarzan_jquery_news_slider_panel/locale/".LOCALESET."locale.php";
} else {
	require_once INFUSIONS."arioobarzan_jquery_news_slider_panel/locale/English/locale.php";
}
$dir = "rtl";//direction (rtl || trl)
$num  = "5";//how many news will be shown

add_to_head("
    <script type='text/javascript' src='".INFUSIONS."arioobarzan_jquery_news_slider_panel/js/jquery-1.3.2.min.js'></script>
    <script type='text/javascript' src='".INFUSIONS."arioobarzan_jquery_news_slider_panel/js/jQuery.arioobarzan.js'></script>
    <link media='all' rel='stylesheet' type='text/css' href='".INFUSIONS."arioobarzan_jquery_news_slider_panel/css/jQuery.arioobarzan.css'>

");
opentable($locale['latestnews']);

echo "<div id='BigTeaser' style='left: center; height: 210px;'>
       <div class='hometeaser' dir='".$dir."'>
";
$antalt = "100"; // how many signs will be shown in every news title
$antald = "500"; // how many signs will be shown in every news intro
$result = dbquery("SELECT * FROM ".DB_PREFIX."news WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC limit 0,".$num);
while ($data = dbarray($result)) {
$res = dbarray(dbquery("SELECT * FROM ".DB_NEWS_CATS." WHERE news_cat_id=".$data['news_cat']));
$title = substr(stripslashes($data['news_subject']), 0, $antalt);
$intro = $data['news_news'];
if ($data['news_breaks'] == "y") $intro = nl2br($intro);
$intro = stripslashes($intro);
$intro = substr($intro,0,$antald) . " ";
$n_img = $data['news_image_t2'];
$n_id = $data['news_id'];
$v_id = $data['news_reads'];
$date = $data['news_datestamp'];
$c_img = $res['news_cat_image'];
$c_name = $res['news_cat_name'];
echo "<div><div class='text'>
<table width='100%' border='0px'>
<tr valign='top'>
<td width='100px'>


<a href='".BASEDIR."news.php?readmore=".$n_id."'>
<img alt='AriooBarzan News Slider' width='100px' height='100px' src='".IMAGES."news/thumbs/".$n_img."' />
</a><center>".$locale['incat'].":<br />".$c_name."<br />
<img alt='AriooBarzan News Slider' width='50px' height='50px' src='".IMAGES."news_cats/".$c_img."' />
</center>
</td>
<td width='100%'>
<h2>
<a href='".BASEDIR."news.php?readmore=".$n_id."'>".$title."</a>
</h2><hr>
                    <span class='black'>".$intro."<a href='".BASEDIR."news.php?readmore=$n_id'>".$locale['readmore']."</a></span>

				<p align='left' class='small2'>".showdate("longdate", $date)."</p>

</td></tr></table>
						</div>
            </div>
";
}

echo "        </div>
    </div>

    
    <script type='text/javascript'>
        jQuery('#BigTeaser').xparoSlider();
    </script>";
closetable();

?>