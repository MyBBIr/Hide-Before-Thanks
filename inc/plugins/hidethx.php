<?php
/**
 * MyBB 1.6
 * Copyright 2014 My-BB.Ir Group, All Rights Reserved
 *
 * Website: http://my-bb.ir
 *
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}


$plugins->add_hook("postbit", "hidethx");

function hidethx_info()
{

	return array(
		"name"			=> "پنهان کردن محتوا قبل از سپاس",
		"description"	=> "'",
		"website"		=> "http://my-bb.ir",
		"author"		=> "AliReza_Tofighi",
		"authorsite"	=> "http://my-bb.ir",
		"version"		=> "1.0",
		"guid" 			=> "",
		"compatibility" => "*"
	);
}


function hidethx(&$post)
{
	global $mybb, $thread, $db, $cache;
	$pcache = $cache->read("plugins");
	if (isset($pcache['active']['thankyoulike']))
	{
		$query = $db->simple_select("g33k_thankyoulike_thankyoulike", "*", "uid = '{$mybb->user['uid']}' And pid = '{$post['pid']}'");
	}
	elseif (isset($pcache['active']['thx']))
	{
		$query = $db->simple_select("thx", "*", "uid = '{$mybb->user['uid']}' And pid = '{$post['pid']}'");
	}
	else
	{
		return NULL;
	}
	
	$numr = $db->num_rows($query);
	if($numr || $post['uid'] == $mybb->user['uid'] || $mybb->usergroup['cancp'])
	{
		
		$post['message'] = preg_replace('#\[hide\](.*?)\[/hide\]#si', '$1', $post['message']);
	}
	else
	{
		$post['message'] = preg_replace('#\[hide\](.*?)\[/hide\]#si', '<span style="background:red;color:white"> این محتوا پنهان شده‌است، برای دیدن محتوا باید سپاس کنید</span>', $post['message']);
	}
	if(defined("HIDETHX") && $post['pid'] == $mybb->input['pid'])
	{
		echo $post['message'];
		exit;
	}
	return $post;
}
?>