<?php  defined('BASEPATH') or exit('No direct script access allowed');

/*
$route['bubble/admin/category'] = 'admin/category';
$route['bubble/admin/category/create'] = 'admin/create_category';
$route['bubble/admin/category/edit(/:any)?'] = 'admin/edit_category$1';
$route['bubble/admin/category/delete(/:any)?'] = 'admin/delete_category$1';
$route['bubble/admin/upload(/:any)?'] = 'upload$1';
$route['bubble/admin/image(/:any)?'] = 'image$1';
$route['bubble/admin/banner(/:any)?'] = 'admin_banner$1';
$route['bubble/admin/trainer-title(/:any)?'] = 'admin_trainer_title$1';
*/
$route['search_engine/admin/black_list_words(/:any)?'] = 'admin_black_list_words$1';
$route['search_engine/admin/master_list_name(/:any)?'] = 'admin_master_list_words$1';
$route['search_engine/admin/custom_data(/:any)?'] = 'admin_manual_add_data$1';
$route['search_engine/admin(/:any)?'] = 'admin$1';
$route['search_engine/search_content(/:any)?'] = 'search_content$1';
//$route['search_engine/clear_video_crawl(/:any)?'] = 'clear_video_crawl$1';
/*
// public
$route['bubble(/:any)'] = 'bubble/index$1';
$route['(bubble)(/:any)?']   = 'bubble$2';
$route['bubble/view(/:num)?'] = 'bubble/view$1';
$route['(bubble)(/:any)?']   = 'bubble$2';
*/