<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

/* This file is automatically generated by reg8log system */

return <<<'REG8LOG_CONFIG_VARS'
a:105:{s:8:"var_name";s:10:"debug_mode";s:6:"method";s:4:"sess";s:10:"cache_file";s:67:"D:/Programs/EasyPHP-5.3.8.0/www/reg8log/file_store/config_cache.php";s:40:"show_statistics_in_admin_operations_page";b:1;s:33:"admin_operations_require_password";i:1;s:22:"admin_error_log_access";i:2;s:25:"account_captcha_threshold";i:3;s:23:"account_block_threshold";i:6;s:20:"account_block_period";i:43200;s:31:"admin_account_captcha_threshold";i:3;s:29:"admin_account_block_threshold";i:6;s:26:"admin_account_block_period";i:43200;s:27:"block_bypass_system_enabled";i:3;s:33:"block_bypass_max_incorrect_logins";i:6;s:33:"block_bypass_system_also4ip_block";b:1;s:23:"max_block_bypass_emails";i:10;s:26:"allow_users2disable_blocks";i:0;s:20:"ip_captcha_threshold";i:7;s:18:"ip_block_threshold";i:14;s:15:"ip_block_period";i:43200;s:26:"admin_ip_captcha_threshold";i:7;s:24:"admin_ip_block_threshold";i:14;s:21:"admin_ip_block_period";i:43200;s:25:"ch_pswd_captcha_threshold";i:3;s:21:"ch_pswd_max_threshold";i:6;s:14:"ch_pswd_period";i:43200;s:19:"cleanup_probability";d:0.01;s:29:"max_nonexistent_users_records";i:100000;s:30:"max_ip_incorrect_login_records";i:1000000;s:36:"max_ip_incorrect_logins_decs_records";i:100000;s:25:"max_security_logs_records";i:10000;s:32:"max_ajax_check_usernames_records";i:100000;s:38:"max_block_alert_emails_history_records";i:10000;s:46:"max_registeration_alert_emails_history_records";i:10000;s:34:"max_registerations_history_records";i:10000;s:10:"debug_mode";b:1;s:4:"lang";s:2:"en";s:17:"admin_emails_lang";s:0:"";s:10:"log_errors";i:30719;s:23:"error_log_file_max_size";i:500000;s:32:"config_cache_validation_interval";i:900;s:20:"config_cache_version";i:1;s:18:"bcrypt_hash_rounds";i:11;s:6:"pepper";s:22:"89JPa36HW7Uiq348dX10ks";s:30:"encrypt_session_files_contents";b:0;s:33:"store_request_entropy_probability";d:0.10000000000000001;s:9:"dbms_info";a:4:{s:4:"host";s:9:"localhost";s:4:"user";s:4:"root";s:4:"pass";s:0:"";s:2:"db";s:7:"reg8log";}s:16:"identify_structs";a:2:{s:16:"autologin_cookie";a:3:{s:15:"value_seperator";s:1:"-";i:0;s:3:"uid";i:1;s:13:"autologin_key";}s:7:"session";a:2:{s:9:"save_path";s:0:"";s:14:"gc_maxlifetime";s:0:"";}}s:31:"change_autologin_key_upon_login";i:1;s:37:"admin_change_autologin_key_upon_login";i:2;s:32:"change_autologin_key_upon_logout";b:1;s:38:"admin_change_autologin_key_upon_logout";b:1;s:12:"tie_login2ip";i:0;s:28:"tie_login2ip_option_at_login";b:0;s:17:"log_last_activity";b:1;s:15:"log_last_logout";b:1;s:14:"log_last_login";b:1;s:33:"allow_manual_autologin_key_change";b:1;s:82:"dont_enforce_autoloign_age_sever_side_when_change_autologin_key_upon_login_is_zero";i:0;s:25:"max_session_autologin_age";i:43200;s:14:"autologin_ages";a:11:{i:0;i:0;i:1;i:300;i:2;i:1200;i:3;i:3600;i:4;i:28800;i:5;i:86400;i:6;i:259200;i:7;i:604800;i:8;i:2592000;i:9;i:7776000;i:10;i:31536000;}s:20:"admin_autologin_ages";a:11:{i:0;i:0;i:1;i:300;i:2;i:1200;i:3;i:3600;i:4;i:28800;i:5;i:86400;i:6;i:259200;i:7;i:604800;i:8;i:2592000;i:9;i:7776000;i:10;i:31536000;}s:25:"max_password_reset_emails";i:10;s:21:"password_reset_period";i:86400;s:38:"change_autologin_key_upon_new_password";b:1;s:44:"admin_change_autologin_key_upon_new_password";b:1;s:21:"registeration_enabled";b:1;s:15:"password_refill";i:2;s:25:"email_verification_needed";b:0;s:25:"admin_confirmation_needed";b:0;s:23:"email_verification_time";i:86400;s:23:"admin_confirmation_time";i:2592000;s:21:"max_activation_emails";i:10;s:34:"can_notify_user_about_admin_action";b:1;s:19:"login_upon_register";b:1;s:23:"login_upon_register_age";i:0;s:32:"alert_admin_about_registerations";i:3;s:24:"registeration_alert_type";i:3;s:30:"registerations_alert_threshold";i:1;s:37:"registerations_alert_threshold_period";i:0;s:39:"registeration_alert_emails_min_interval";i:0;s:30:"max_registeration_alert_emails";i:24;s:37:"max_registeration_alert_emails_period";i:86400;s:19:"ajax_check_username";b:1;s:24:"max_ajax_check_usernames";i:20;s:31:"max_ajax_check_usernames_period";i:3600;s:48:"reset_clients_ajax_check_usernames_upon_register";b:1;s:37:"email_change_needs_email_verification";i:2;s:43:"admin_email_change_needs_email_verification";i:0;s:23:"max_change_email_emails";i:-1;s:30:"change_email_verification_time";i:0;s:6:"fields";a:5:{s:7:"captcha";a:7:{s:9:"minlength";i:5;s:9:"maxlength";i:5;s:6:"php_re";s:36:"/^[2345679ACEFGHJKLMNPRSTUVWXYZ]*$/i";s:5:"js_re";b:1;s:6:"unique";b:0;s:5:"value";b:0;s:15:"client_validate";b:1;}s:8:"username";a:7:{s:9:"minlength";i:1;s:9:"maxlength";i:30;s:6:"php_re";s:375:"/^([\x{067E}\x{0622}\x{0627}\x{0628}\x{062A}-\x{063A}\x{0641}-\x{064A}\x{0698}\x{06A9}\x{06AF}\x{06C1}\x{06CC}]|[a-zA-Z0-9])([\x{067E}\x{0622}\x{0627}\x{0628}\x{062A}-\x{063A}\x{0641}-\x{064A}\x{0698}\x{06A9}\x{06AF}\x{06C1}\x{06CC}]|[a-zA-Z0-9]|\s([\x{067E}\x{0622}\x{0627}\x{0628}\x{062A}-\x{063A}\x{0641}-\x{064A}\x{0698}\x{06A9}\x{06AF}\x{06C1}\x{06CC}]|[a-zA-Z0-9]))*$/u";s:5:"js_re";s:296:"/^([\u067E\u0622\u0627\u0628\u062A-\u063A\u0641-\u064A\u0698\u06A9\u06AF\u06C1\u06CC]|[a-zA-Z0-9])([\u067E\u0622\u0627\u0628\u062A-\u063A\u0641-\u064A\u0698\u06A9\u06AF\u06C1\u06CC]|[a-zA-Z0-9]|\s([\u067E\u0622\u0627\u0628\u062A-\u063A\u0641-\u064A\u0698\u06A9\u06AF\u06C1\u06CC]|[a-zA-Z0-9]))*$/";s:6:"unique";b:1;s:5:"value";s:0:"";s:15:"client_validate";b:1;}s:8:"password";a:7:{s:9:"minlength";i:6;s:9:"maxlength";i:128;s:6:"php_re";s:0:"";s:5:"js_re";b:0;s:6:"unique";b:0;s:5:"value";b:0;s:15:"client_validate";b:1;}s:5:"email";a:7:{s:9:"minlength";i:6;s:9:"maxlength";i:60;s:6:"php_re";s:48:"/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i";s:5:"js_re";b:1;s:6:"unique";b:1;s:5:"value";s:0:"";s:15:"client_validate";b:1;}s:6:"gender";a:7:{s:9:"minlength";i:1;s:9:"maxlength";i:1;s:6:"php_re";s:10:"/^[mfn]$/i";s:5:"js_re";b:0;s:6:"unique";b:0;s:5:"value";s:0:"";s:15:"client_validate";b:0;}}s:34:"keep_expired_block_log_records_for";i:0;s:32:"log_non_existent_accounts_blocks";b:1;s:32:"alert_admin_about_account_blocks";i:3;s:30:"account_blocks_alert_threshold";i:1;s:37:"account_blocks_alert_threshold_period";i:86400;s:27:"alert_admin_about_ip_blocks";i:3;s:25:"ip_blocks_alert_threshold";i:1;s:32:"ip_blocks_alert_threshold_period";i:86400;s:25:"alert_emails_min_interval";i:0;s:16:"max_alert_emails";i:24;s:23:"max_alert_emails_period";i:86400;s:38:"exempt_admin_account_from_alert_limits";b:1;s:38:"password_required4viewing_account_info";b:1;}
REG8LOG_CONFIG_VARS;

?>