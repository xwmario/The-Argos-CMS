<?php
		define('IN_PHPBB', true);
		$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $forum_path;
		$phpEx = substr(strrchr(__FILE__, '.'), 1);
		require($phpbb_root_path . 'common.' . $phpEx);
		require($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();
		$request->enable_super_globals();
		
		//we need it for db connection
		require($forum_path.'config.php');
		$bb_db = $dbname;
		$bb_prefix = preg_replace("/[^A-Za-z0-9 ]/", '', $table_prefix);
		
		//global vars for 3.1
		$bb_is_anonymous = ($user->data['user_id']==ANONYMOUS);
		$bb_session_id = $user->session_id;
		$bb_user_id = $user->data['user_id'];
		$bb_is_bot = $user->data['is_bot'];
		$bb_mail = $user->data['user_email'];
		$bb_username = $user->data['username'];
		$bb_is_admin = $auth->acl_get('a_user');
		$bb_user_ip = $user->ip;
		$bb_user_sig = $user->data['user_sig'];
		$bb_bbcode_uid = $user->data['user_sig_bbcode_uid'];
        $bb_bbcode_bitfield = $user->data['user_sig_bbcode_bitfield'];
		$bb_user_last_visit = $user->data['user_lastvisit'];
		$bb_user_color = $user->data['user_colour'];
		$bb_current_page = $user->page['page'];
		$bb_new_pm = $user->data['user_new_privmsg'];
		$bb_unread_pm = $user->data['user_unread_privmsg'];
		$bb_user_posts = $user->data['user_posts'];
		$bb_user_ava = $user->data['user_avatar'];
		$bb_group_id = $user->data['group_id'];
		$bb_ava_type = $user->data['user_avatar_type'];
		switch($bb_ava_type) {
			case '':{
				$bb_ava_type = 0;
				break;
			}
			case 'avatar.driver.upload':{
				$bb_ava_type = 1;
				break;
		    }
			case 'avatar.driver.remote':{
				$bb_ava_type = 2;
				break;
		    }
			case 'avatar.driver.local': {
			    $bb_ava_type = 3;
				break;
			}
			case 'avatar.driver.gravatar': {
				$bb_ava_type = 4;
				break;
			}
		}
		$bb_user_warns = $user->data['user_warnings'];
		//end 