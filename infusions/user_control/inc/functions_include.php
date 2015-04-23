<?php

if (!checkrights("USCT") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

define("USER_CONT", INFUSIONS."user_control/");
define("USER_IMGS", INFUSIONS."user_control/imgs/");
include USER_CONT."infusion_db.php";
include USER_CONT."inc/geoiploc.php";
include USER_CONT."locale/country_locales.php";

if (file_exists(USER_CONT."locale/".$settings['locale'].".php")) {
	include USER_CONT."locale/".$settings['locale'].".php";
} else { include USER_CONT."locale/English.php"; }

$uc_globalsettings = dbarray(dbquery("SELECT * FROM ".DB_UC_SETTINGS));
$post_threshold = $uc_globalsettings['uc_post_num'];
$show_icons = $uc_globalsettings['uc_show_icons'];
$time = time();

if( mysql_num_rows( mysql_query("SHOW TABLES LIKE '".DB_SHOUTBOX."'"))) { $shoutbox = true; } else { $shoutbox = false; }

add_to_head("<script type='text/javascript'>
function ProcessUsers() {
		return confirm('".$locale['uc060']."');
}
</script>");

// Images
$img_home = "             <img src='".USER_IMGS."home.png' border='0' alt='".$locale['uctitle']."' style='vertical-align:middle' />";
$img_logo = "             <img src='".USER_IMGS."logo.png' border='0' alt='".$locale['uctitle']."' style='vertical-align:middle' />";
$img_lookup = "           <img src='".USER_IMGS."lookup.png' border='0' alt='".$locale['uc015']." ".$locale['uc024']."' title='".$locale['uc015']." ".$locale['uc024']."' style='vertical-align:middle' />";
$img_hidden = "           <img src='".USER_IMGS."email_hidden.gif' border='0' alt='".$locale['uc013']." - ".$locale['uc041']."' style='vertical-align:middle' />";
$img_public = "           <img src='".USER_IMGS."email_public.gif' border='0' alt='".$locale['uc013']." - ".$locale['uc025']."' style='vertical-align:middle' />";
$img_email_blacklisted = "<img src='".USER_IMGS."email_blacklisted.png' border='0' alt='".$locale['uc014']." - ".$locale['uc032']."' title='".$locale['uc014']." - ".$locale['uc032']."' style='vertical-align:middle' />";
$img_email_clear = "      <img src='".USER_IMGS."email_clear.png' border='0' alt='".$locale['uc014']." - ".$locale['uc033']."' title='".$locale['uc014']." - ".$locale['uc033']."' style='vertical-align:middle' />";
$img_spam = "             <img src='".USER_IMGS."spam.png' border='0' alt='".$locale['uc049'].$locale['uc037']."' title='".$locale['uc049'].$locale['uc037']."' style='vertical-align:middle' />";
$img_right = "            <img src='".USER_IMGS."right.png' border='0' alt='".$locale['uc024']."' title='".$locale['uc024']."' style='vertical-align:middle' />";
$img_tick = "             <img src='".USER_IMGS."tick.png' border='0' alt='' title='' style='vertical-align:middle' />";
$img_content = "          <img src='".USER_IMGS."content.png' border='0' alt='".$locale['uc062']."' title='".$locale['uc062']."' style='vertical-align:middle' />";
$img_user_clear = "       <img src='".USER_IMGS."user_clear.png' border='0' alt='".$locale['uc066']."' title='".$locale['uc066']."' style='vertical-align:middle' />";
$img_user_banned = "      <img src='".USER_IMGS."user_banned.png' border='0' alt='".$locale['uc067']."' title='".$locale['uc067']."' style='vertical-align:middle' />";
$img_ip_blacklisted = "   <img src='".USER_IMGS."ip_blacklisted.png' border='0' alt='".$locale['uc068']."' title='".$locale['uc068']."' style='vertical-align:middle' />";
$img_ip_clear = "         <img src='".USER_IMGS."ip_clear.png' border='0' alt='".$locale['uc072']."' title='".$locale['uc072']."' style='vertical-align:middle' />";
$img_user_content = "     <img src='".USER_IMGS."user_content.png' border='0' alt='".$locale['uc073']."' title='".$locale['uc073']."' style='vertical-align:middle' />";
$img_user_web = "         <img src='".USER_IMGS."user_web.png' border='0' alt='".$locale['uc012']."' style='vertical-align:middle' />";
$img_rights_view = "      <img src='".USER_IMGS."view_rights.png' border='0' alt='".$locale['uc079']."' style='vertical-align:middle' />";
$img_member_view = "      <img src='".USER_IMGS."member_view.png' border='0' alt='".$locale['uc047']."' style='vertical-align:middle' />";
$img_hs = "               <img src='".USER_IMGS."hs_lwr.gif' border='0' alt='hobbysites.net' style='vertical-align:middle' />";
$img_shield = "           <img src='".USER_IMGS."shield.png' border='0' alt='".$locale['uctitle']."' style='vertical-align:middle' />";
$img_sfs = "              <img src='".USER_IMGS."sfs.png' border='0' alt='sfs' style='vertical-align:middle' />";

If (FUSION_SELF != "user_control.php") {
$img_user_control = "     <img src='".USER_IMGS."user_control.png' border='0' alt='".$locale['uctitle']."' title='".$locale['uctitle']."' />";
} else { 
$img_user_control = "     <img src='".USER_IMGS."user_control_off.png' border='0' alt='".$locale['uctitle']."' title='".$locale['uctitle']."' />"; }
If (FUSION_SELF != "group_by_ip.php") {
$img_group_by_ip = "      <img src='".USER_IMGS."group_by_ip.png' border='0' alt='".$locale['uc028']."' title='".$locale['uc028']."' />";
} else {
$img_group_by_ip = "      <img src='".USER_IMGS."group_by_ip_off.png' border='0' alt='".$locale['uc028']."' title='".$locale['uc028']."' />"; }
If (FUSION_SELF != "multi_ip_lookup.php") {
$img_ip_lookup = "        <img src='".USER_IMGS."ip_lookup.png' border='0' alt='".$locale['uc029']."' title='".$locale['uc029']."' />";
} else {
$img_ip_lookup = "        <img src='".USER_IMGS."ip_lookup_off.png' border='0' alt='".$locale['uc029']."' title='".$locale['uc029']."' />"; }
If (FUSION_SELF != "members_ips.php") {
$img_members_ips = "        <img src='".USER_IMGS."members_ips.png' border='0' alt='".$locale['uc050']."' title='".$locale['uc050']."' />";
} else {
$img_members_ips = "        <img src='".USER_IMGS."members_ips_off.png' border='0' alt='".$locale['uc050']."' title='".$locale['uc050']."' />"; }
If (FUSION_SELF != "user_lookup.php") {
$img_user_id_lookup = "   <img src='".USER_IMGS."user_id_lookup.png' border='0' alt='".$locale['uc030']." ".$locale['uc024']."' title='".$locale['uc030']." ".$locale['uc024']."' />";
} else {
$img_user_id_lookup = "   <img src='".USER_IMGS."user_id_lookup_off.png' border='0' alt='".$locale['uc030']." ".$locale['uc024']."' title='".$locale['uc030']." ".$locale['uc024']."' />"; }
If (FUSION_SELF != "user_inactive.php") {
$img_inactive_user  = "   <img src='".USER_IMGS."inactive_user.png' border='0' alt='".$locale['iu003']."' title='".$locale['iu003']."' />";
} else {
$img_inactive_user  = "   <img src='".USER_IMGS."inactive_user_off.png' border='0' alt='".$locale['iu003']."' title='".$locale['iu003']."' />"; }
If (FUSION_SELF != "admin_rights.php" && iSUPERADMIN) {
$img_admin_rights  = "    <img src='".USER_IMGS."admin_rights.png' border='0' alt='".$locale['uc079']."' title='".$locale['uc079']."' />";
} else {
$img_admin_rights  = "    <img src='".USER_IMGS."admin_rights_off.png' border='0' alt='".$locale['uc079']."' title='".$locale['uc079']."' />"; }
If (FUSION_SELF != "legend.php") {
$img_legend  = "          <img src='".USER_IMGS."legend.png' border='0' alt='".$locale['uc063']."' title='".$locale['uc063']."' />";
} else {
$img_legend  = "          <img src='".USER_IMGS."legend_off.png' border='0' alt='".$locale['uc063']."' title='".$locale['uc063']."' />"; }
If (FUSION_SELF != "settings.php" && iSUPERADMIN) {
$img_settings  = "        <img src='".USER_IMGS."settings.png' border='0' alt='".$locale['uc080']."' title='".$locale['uc080']."' />";
} else {
$img_settings  = "        <img src='".USER_IMGS."settings_off.png' border='0' alt='".$locale['uc080']."' title='".$locale['uc080']."' />"; }
If (FUSION_SELF != "month_stats.php") {
$img_month_stats  = "     <img src='".USER_IMGS."month_stats.png' border='0' alt='".$locale['uc113']."' title='".$locale['uc113']."' />";
} else {
$img_month_stats  = "     <img src='".USER_IMGS."month_stats_off.png' border='0' alt='".$locale['uc113']."' title='".$locale['uc113']."' />"; }

// Footer
$data_v = dbarray(dbquery("SELECT inf_title, inf_version FROM ".DB_INFUSIONS." WHERE inf_title='".$locale['uctitle']."'"));	
$version = $data_v['inf_version'];
$time = time();
$uc_footer = "<br /><div class='tbl-border' align='center'><b>User Control v".$version."</b> by <a target='_blank' href='http://www.hobbysites.net/' title='HobbyMan'>HobbyMan</a><br />
<span class='small'>Contributing code by <a href='http://www.php-fusion.co.uk/profile.php?lookup=5527' title='Kneekoo'>Kneekoo</a> | <a href='http://www.php-fusion.co.uk/profile.php?lookup=10498' title='Yxos'>Yxos</a> | <a target='_blank' href='http://www.php-fusion.co.uk/profile.php?lookup=7445'>digifredje</a>
<br />GeoIPLocation Library by <a target='_blank' href='http://chir.ag/projects/geoiploc'>Chirag Mehta</a> released under the <a target='_blank' href='http://creativecommons.org/licenses/by/2.5/'>Creative Commons License: Attribution 2.5</a></span><br /><br />&copy hobbysites.net ".date("Y")."<br /><br /><a target='_blank' href='http://www.hobbysites.net/' title='HobbyMan'>".$img_hs."</a></div>\n";

// Spam Rating
if (!function_exists("spam_rating")) {
function spam_rating($text) {
	global $settings;
	$hits = 0;
	$word_list = array("href=", "url=", "[url]", "buy", "viagra", "cialis", "granite", "sex", "amateur", "hire", "outdoor", "online", "estate", "realtor", "alcohol", "drug", "pizza", "trade", "trading", "lawyer", "grow", "backlink", "internet", "build", "packet", "rapid", "sell", "sale", "market", "loan", "credit", "mortgage", "degree", "diploma", "consult", "affiliat", "download", "advert", "promo", "prosper", "poker");
	if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
		foreach (explode("\r\n", $settings['bad_words']) as $word)
			$word_list[] = $word;
	}
	$count = count($word_list);
	for ($i=0; $i < $count; $i++) {
		preg_match_all("#".$word_list[$i]."#i", $text, $matches);
		$hits += count($matches[0]);
	 }
	return $hits;
  }
}

// Stripinput Fix
if (!function_exists("stripinput_fix")) {
function stripinput_fix($text) {
	if (!is_array($text)) {
		if (QUOTES_GPC) $text = stripslashes($text);
		$search = array("&", "\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
		$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
		$text = str_replace($search, $replace, $text);
	} else {
		while (list($key, $value) = each($text)) {
			$text[$key] = stripinput($value);
		}
	  }
	return $text;
  }
}

$view_by = array(
               0 => "user_id",
               1 => "user_joined",
               2 => "user_lastvisit"
);

$sortby = $view_by[$uc_globalsettings['uc_view']];

// Submission Types
if (file_exists(INFUSIONS."addondb/index.php")) {
$submit_type = array(
               "a" => $locale['uc092'],
               "p" => $locale['uc093'],
               "n" => $locale['uc094'],
               "l" => $locale['uc095'],
               "m" => $locale['uc096']
);
} else { 
$submit_type = array(
               "a" => $locale['uc092'],
               "p" => $locale['uc093'],
               "n" => $locale['uc094'],
               "l" => $locale['uc095']
);
}

?>