<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: theme.php
| Theme: Pentagon
| Author: FDTD Designer (FILON)
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

define("THEME_BULLET", "<img src='".THEME."images/soccer.png' style='border:none;vertical-align:middle;' alt='' />");
define("THEME_WIDTH", "90%");

require_once THEME."functions.php";
require_once INCLUDES."theme_functions_include.php";

if (function_exists("add_handler")) { add_handler("theme_output"); }

function render_page($license = false) {
	global $settings, $main_style, $locale, $mysql_queries_time;

	//IE special
	add_to_head("<!--[if IE]><link rel='stylesheet' href='".THEME."iexplorer.css' type='text/css' media='screen' /><![endif]-->");
	
	//Header
	echo "<div class='main-gradient'>\n<div class='pentagons'>\n";
	echo "<table cellspacing='0' cellpadding='0' class='navigation'>\n<tr>\n";
	echo "<td class='sub-rounded'></td>\n";
	echo "<td class='sub-header'>".showsublinks("")."</td>\n";
	echo "</tr>\n</table>\n";
	echo "<div class='light'></div>\n";
	echo "<table cellpadding='0' cellspacing='0' width='".THEME_WIDTH."' class='fixed-layout'>\n<tr>\n";
	echo "<td class='border-templ-left'></td>\n";
	echo "<td class='ball-templ'></td>\n";
	echo "<td class='grass-templ' valign='top'>".showbanners(1)."</td>\n";
	echo "<td class='border-templ-right'></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='border-bg-left' valign='top'><span class='grass-templ-left'></span></td>\n";
	echo "<td class='body-bg' valign='top' colspan='2'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='fix-frontpage $main_style'>\n<tr>\n";
	if (LEFT) { echo "<td class='side-border-left' valign='top'>".LEFT."</td>"; }
	echo "<td class='main-bg' valign='top'>".U_CENTER.CONTENT.L_CENTER."</td>";
	if (RIGHT) { echo "<td class='side-border-right' valign='top'>".RIGHT."</td>"; }
	echo "</tr>\n</table>\n";
	echo "<div class='info-selecting'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='info-table'>\n<tr>\n";
	echo "<td class='info-title'>Hot News</td>";
	echo "<td class='info-title'>Popular Articles</td>";
	echo "<td class='info-title'>Top Downloads</td>";
	echo "</tr>\n<tr>\n";
	echo "<td class='info-content' valign='top'>".show_popular_news("side")."</td>";
	echo "<td class='info-content' valign='top'>".show_popular_articles("side")."</td>";
	echo "<td class='info-content' valign='top'>".show_popular_downloads("side")."</td>";
	echo "</tr>\n</table>\n";
	echo "</div>\n</td>\n";
	echo "<td class='border-bg-right' valign='top'><span class='grass-templ-right'></span></td>\n";
	echo "</tr>\n</table>\n";
	echo "<div class='info-toning'>\n</div>\n";
	
	//Footer
	echo "<div class='grass-top'></div>\n";
	echo "<div class='football-line'>\n";
	if ($settings['rendertime_enabled'] == 1 || ($settings['rendertime_enabled'] == 2 && iADMIN)) {
		echo "<div class='flleft footer-line'>".showrendertime()."</div>\n";
	} else {
		echo "<div class='flleft footer-line'>".showcounter()."</div>\n";
	}
	echo "<div class='flright footer-line'>".str_replace("<br />", " ", stripslashes($settings['footer']))."</div>\n";
	echo "</div>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='grass-pattern'>\n<tr>\n";
	echo "<td class='footer-line glass fix-width'><div class='designer'><span>Pentagon theme by <strong>FDTD Designer<strong></span></div></td>\n";
	echo "<td class='footer-line glass banners' valign='bottom'>".showbanners(2)."</td>\n";
	echo "<td class='footer-line glass fix-width'>".(!$license ? showcopyright("white", true) : "")."</td>\n";
	echo "</tr>\n</table>\n";
	echo "<div class='ie-fix'>&nbsp;</div>\n";
	echo "</div>\n</div>\n";
}

function render_comments($c_data, $c_info){
	global $locale;
	
	opentable($locale['c100']);
	if (!empty($c_data)){
		echo "<div class='comments floatfix'>\n";
			$c_makepagenav = '';
			if ($c_info['c_makepagenav'] !== FALSE) { 
			echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>".$c_info['c_makepagenav']."</div>\n"; 
		}
			foreach($c_data as $data) {
			echo "<div class='tbl2'>\n";
			if ($data['edit_dell'] !== FALSE) { 
				echo "<div style='float:right' class='comment_actions'>".$data['edit_dell']."\n</div>\n";
			}
			echo "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$data['i']."</a> |\n";
			echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
			echo "<span class='small'>".$data['comment_datestamp']."</span>\n";
			echo "</div>\n<div class='tbl1 comment_message'>".$data['comment_message']."</div>\n";
		}
		echo $c_makepagenav;
		if ($c_info['admin_link'] !== FALSE) {
			echo "<div style='float:right' class='comment_admin'>".$c_info['admin_link']."</div>\n";
		}
		echo "</div>\n";
	} else {
		echo $locale['c101']."\n";
	}
	closetable();   
}

function render_news($subject, $news, $info) {
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='panel-intro'>\n<tr>\n";
	echo "<td class='panel-left'></td>\n";
	echo "<td class='panel-mid'>".$subject."</td>\n";
	echo "<td class='panel-right'></td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='99%' class='panel-info-intro ie-margin'>\n<tr>\n";
	echo "<td class='panel-info'>".newsposter($info," &middot;").newscat($info," &middot;").newsopts($info,"&middot;").itemoptions("N",$info['news_id'])."</td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='99%' class='spacer'>\n<tr>\n";
	echo "<td class='panel-body'>".$info['cat_image'].$news."</td>\n";
	echo "</tr>\n</table>\n";
}

function render_article($subject, $article, $info) {
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='panel-intro'>\n<tr>\n";
	echo "<td class='panel-left'></td>\n";
	echo "<td class='panel-mid'>".$subject."</td>\n";
	echo "<td class='panel-right'></td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='99%' class='panel-info-intro ie-margin'>\n<tr>\n";
	echo "<td class='panel-info'>".articleposter($info," &middot;").articlecat($info," &middot;").articleopts($info,"&middot;").itemoptions("A",$info['article_id'])."</td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='99%' class='spacer'>\n<tr>\n";
	echo "<td class='panel-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</td>\n";
	echo "</tr>\n</table>\n";
}

function opentable($title) {
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='panel-intro'>\n<tr>\n";
	echo "<td class='panel-left'></td>\n";
	echo "<td class='panel-mid'>".$title."</td>\n";
	echo "<td class='panel-right'></td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='99%' class='spacer ie-margin'>\n<tr>\n";
	echo "<td class='panel-body'>\n";
}

function closetable() {
	echo "</td>\n";
	echo "</tr>\n</table>\n";
}

function openside($title, $collapse = false, $state = "on") {
	global $panel_collapse; $panel_collapse = $collapse;
	
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='panel-intro'>\n<tr>\n";
	echo "<td class='panel-left'></td>\n";
	echo "<td class='panel-mid'>".$title."</td>\n";
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<td class='panel-mid' align='right'>".panelbutton($state, $boxname)."</td>\n";
	}
	echo "<td class='panel-right'></td>\n";
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='97%' class='spacer ie-margin'>\n<tr>\n";
	echo "<td class='panel-body'>\n";	
	if ($collapse == true) { echo panelstate($state, $boxname); }
}

function closeside() {
	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n"; }	
	echo "</td>\n</tr>\n</table>\n";
}
?>
