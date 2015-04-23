<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: functions.php
| Author: FDTD Designer (FILON)
| Co-author: Johan Wilson
| Theme: Pentagon
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

// Variables
set_image("panel_on", THEME."images/panel_on.png");
set_image("panel_off", THEME."images/panel_off.png");
set_image("up", THEME."images/panel_off.png");
set_image("down", THEME."images/panel_on.png");
set_image("left", THEME."images/go-left.png");
set_image("right", THEME."images/go-right.png");
set_image("printer", THEME."images/printer.png");
set_image("edit", THEME."images/pencil.png");
set_image("pollbar", THEME."images/pollbar.png");

set_image("folder", THEME."forum/thread_normal.png");
set_image("foldernew", THEME."forum/thread_new.png");
set_image("folderhot", THEME."forum/thread_hot.png");
set_image("folderlock", THEME."forum/thread_closed.png");
set_image("stickythread", THEME."forum/thread_sticky.png");

set_image("reply", "reply");
set_image("newthread", "newthread");
set_image("web", "web");
set_image("pm", "pm");
set_image("quote", "quote");
set_image("forum_edit", "forum_edit");

// Functions
function show_popular_news($class="") {
	$news_feed = "";
	$result = dbquery(
		"SELECT news_id, news_subject, news_reads FROM ".DB_NEWS." 
		WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().")
		AND (news_end='0'||news_end>=".time().") AND news_draft='0'
		ORDER BY news_reads DESC, news_datestamp DESC LIMIT 0,9"
	);
	if (dbrows($result)) {
		while ($data = dbarray($result)) {
			$news_feed .= "<div class='info-feed'><div class='flright'>".$data['news_reads']."</div>".THEME_BULLET." <a href='".BASEDIR."news.php?readmore=".$data['news_id']."' title='".$data['news_subject']."'".($class != "" ? " class='".$class."'" : "").">".trimlink($data['news_subject'], 40)."</a></div>";
		}
	} else {
		$news_feed .= "<div style='text-align:center;'>No news found!</div>\n";
	}
	return $news_feed;
}

function show_popular_articles($class="side") {
	$news_feed = "";
	$result = dbquery(
		"SELECT article_id, article_subject, article_reads FROM ".DB_ARTICLES." 
		WHERE article_draft='0'
		ORDER BY article_reads DESC, article_datestamp DESC LIMIT 0,9"
	);
	if (dbrows($result)) {
		while ($data = dbarray($result)) {
			$news_feed .= "<div class='info-feed'><div class='flright'>".$data['article_reads']."</div>".THEME_BULLET." <a href='".BASEDIR."articles.php?article_id=".$data['article_id']."' title='".$data['article_subject']."'".($class != "" ? " class='".$class."'" : "").">".trimlink($data['article_subject'], 40)."</a></div>";
		}
	} else {
		$news_feed .= "<div style='text-align:center;'>No articles found!</div>\n";
	}
	return $news_feed;
}

function show_popular_downloads($class="side") {
	$news_feed = "";
	$result = dbquery(
		"SELECT download_id, download_title, download_count FROM ".DB_DOWNLOADS." 
		ORDER BY download_count DESC, download_datestamp DESC LIMIT 0,9"
	);
	if (dbrows($result)) {
		while ($data = dbarray($result)) {
			$news_feed .= "<div class='info-feed'><div class='flright'>".$data['download_count']."</div>".THEME_BULLET." <a href='".BASEDIR."articles.php?article_id=".$data['download_id']."' title='".$data['download_title']."'".($class != "" ? " class='".$class."'" : "").">".trimlink($data['download_title'], 40)."</a></div>";
		}
	} else {
		$news_feed .= "<div style='text-align:center;'>No downloads found!</div>\n";
	}
	return $news_feed;
}

// Lines by Johan Wilson
function theme_output($output) {
	$search = array(
		"@><img src='reply' alt='(.*?)' style='border:0px' />@si",
		"@><img src='newthread' alt='(.*?)' style='border:0px;?' />@si",
		"@><img src='web' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='pm' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='quote' alt='(.*?)' style='border:0px;vertical-align:middle' />@si",
		"@><img src='forum_edit' alt='(.*?)' style='border:0px;vertical-align:middle' />@si"
	);
	$replace = array(
		' class="submit white">$1',
		' class="submit white">$1',
		' class="submit white" rel="nofollow" title="$1">Web',
		' class="submit white" title="$1">PM',
		' class="submit white" title="$1">$1',
		' class="submit white" title="$1">$1'
	);
	$output = preg_replace($search, $replace, $output);

	return $output;
}
?>
