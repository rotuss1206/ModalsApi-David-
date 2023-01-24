<?php
// $content1 = file_get_contents('./wp-content/plugins/ModalsApi/content.php');
// update_option( 'textarea_opt', $content1);

// $content2 = file_get_contents('./wp-content/plugins/ModalsApi/content2.php');
// update_option( 'textarea_opt2', $content2);

// $show_form = get_option('show_form');
	
// add_filter( 'wp_footer', 'wp_usage' );        
// function wp_usage(){

// 	$label = get_option('labeg_germ');
	
// 	echo '<div class="modal_backgroud hidden">
// 		<form id="convertkit_form" method="POST">
// 			<div class="modals">
// 			'.get_option('textarea_opt').get_option('textarea_opt2').'
			
// 			</div>
// 		</form>
// 	</div>
// 	';
// }

// <-- Creating a shortcode -->

add_shortcode('modal_form1','modals1');
function modals1(){
	ob_start();
	$url = $_SERVER["REQUEST_URI"];
	$url_location = parse_url($url, PHP_URL_PATH);
	echo '<div class="modal_backgroud hidden">
		<form id="convertkit_form" method="POST">
			<div class="modals">';

	require_once API_DIR . '/content.php';

	echo '</div>
		</form>
	</div>
	';

	return ob_get_clean();
}

add_shortcode('modal_form2','modals2');
function modals2(){
	ob_start();

	echo '<div class="modal_backgroud hidden">
		<form id="convertkit_form" method="POST">
			<div class="modals">';

	require_once API_DIR . '/content2.php';

	echo '</div>
		</form>
	</div>
	';
	
	return ob_get_clean();
}

add_shortcode('modal_form3','modals3');
function modals3(){
	ob_start();

	echo '<div class="modal_backgroud hidden">
		<form id="convertkit_form" method="POST">
			<div class="modals">';

	require_once API_DIR . '/content3.php';

	echo '</div>
		</form>
	</div>
	';
	
	return ob_get_clean();
}

// <-- Creating a shortcode end -->


function save_opt(){
  	// $text = $_POST['textarea'];
  	// $textarea = fixed($text);
  	// $textarea = trim($textarea);
  	// update_option( 'textarea_opt', $textarea);
  	update_option( 'show_form', $_POST['show_form']);
  	update_option( 'api_secret', $_POST['api_secret']);
  	update_option( 'form_id', $_POST['form_id']);
  	update_option( 'api_key', $_POST['api_key']);
    $result['status'] = 'ok';

    echo json_encode($result, 320);
    wp_die();

} //endfunction
add_action('wp_ajax_chekoptions', 'save_opt');
add_action('wp_ajax_nopriv_chekoptions', 'save_opt');

function add_aff(){
  	update_option( 'aff', $_POST['aff']);
    $result['aff'] = $_POST['aff'];

    echo json_encode($result, 320);
    wp_die();
} //endfunction
add_action('wp_ajax_add_aff', 'add_aff');
add_action('wp_ajax_nopriv_add_aff', 'add_aff');

function show_forms(){
	$api_key = trim($_POST['api_key']);
	$ch = curl_init('https://api.convertkit.com/v3/forms?api_key='.$api_key);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);
    $result['forms'] = fixed($html);

    echo json_encode($result, 320);
    wp_die();

} //endfunction
add_action('wp_ajax_show_forms', 'show_forms');
add_action('wp_ajax_nopriv_show_forms', 'show_forms');

function add_subscrider(){
	$tags = $_POST['all_tags'];
	$aff = get_option( 'aff' );
	if(get_option( 'aff' ) && $aff != ''){
		create_aff();
	}
	// if($_POST['url_path']){
	// 	create_url_path();
	// }
	$url_path = trim($_POST['url_path']);

	$tags_id = get_tagid($tags);
	$name =	trim($_POST['name']);
	$name_arr = explode(" ", $name);
	$email = trim($_POST['email']);
	$subscriber = subscriber($url_path, $tags_id, $name_arr[0], $email, $aff);
	update_option( 'aff', '');

    $result['subscriber'] = $subscriber;

    echo json_encode($result, 320);
    wp_die();

} //endfunction
add_action('wp_ajax_add_subscrider', 'add_subscrider');
add_action('wp_ajax_nopriv_add_subscrider', 'add_subscrider');

function fixed($json){
    return str_replace('`', '"', preg_replace(
            '/`([^`]+)`(?=`)/', 
            '\\\"$1\"', 
            str_replace(['\"', '"'], '`', $json))
    );
}

function create_aff(){
	$arr_post = [
		'api_key' => get_option('api_secret'), 
		'label' => 'aff'
	];
	$arr_post = json_encode($arr_post);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.convertkit.com/v3/custom_fields');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return json_decode($result, true);
}

function create_url_path(){
	$arr_post = [
		'api_key' => get_option('api_secret'), 
		'label' => 'urlpath'
	];
	$arr_post = json_encode($arr_post);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.convertkit.com/v3/custom_fields');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return json_decode($result, true);
}

function subscriber($url_path = '', $tags_id = NULL,  $name = '', $email = '', $aff = ''){

	$fields = [
			'url_path' => $url_path,
			'aff' => $aff
	];
	$tags_id = implode(",", $tags_id);
	$arr_post = [
				'api_key' => get_option('api_key'), 
				'first_name' => $name, 
				'email' => $email, 
				'tags' => '[3257302,'.$tags_id.']', 
				'fields' => $fields
			];

	$arr_post = json_encode($arr_post);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.convertkit.com/v3/forms/'.get_option('form_id').'/subscribe');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post);

	$headers = array();
	$headers[] = 'Content-Type: application/json; charset=utf-8';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return json_decode($result, true);
}


function get_tagid($tags){
	$tags = explode(";", $tags);
	$tags_arr = [];
	$api_key = get_option('api_key');
	foreach ($tags as $key => $tag) {
		if($tag != ''){
			$tag = explode(",", $tag);
			foreach($tag as $value){
				array_push($tags_arr, trim($value));
			}
		}
	}
	// var_dump($tags_arr);
	create_tags($tags_arr);
	$tags_ids = get_tags_ids($tags_arr,$api_key);
	return $tags_ids;
}


function get_tags_ids($tags_arr = NULL, $api_key = ''){
	// var_dump($api_key);
	$ch = curl_init('https://api.convertkit.com/v3/tags?api_key='.$api_key);
	$tag_ids =[];
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);
	$arr = json_decode($html, true);
	foreach($tags_arr as $value){
		foreach($arr['tags'] as $tag){
			if (in_array($value, $tag)) {
				    array_push($tag_ids, $tag['id']);
			}
		}
	}
	return $tag_ids;
}

function create_tags($tags = NULL){
	// Get tags
	$ch = curl_init('https://api.convertkit.com/v3/tags?api_key='.get_option('api_key'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$html = curl_exec($ch);
	curl_close($ch);
	$arr = json_decode($html, true);
	
	$tags_to_create = [];
	$tags_names = [];
	foreach($arr['tags'] as $value){
			array_push($tags_names, trim($value['name']));
		}

	foreach($tags as $tag){
		$tag = trim($tag);
		if (!in_array($tag, $tags_names)) {
			array_push($tags_to_create, array('name' => $tag));
		}

	}
	
	$arr_post = [
				'api_secret' => get_option('api_secret'),
				'tag' => $tags_to_create
			];

	$arr_post = json_encode($arr_post);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.convertkit.com/v3/tags');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $arr_post);

	$headers = array();
	$headers[] = 'Content-Type: application/json; charset=utf-8';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return json_decode($result, true);
}
