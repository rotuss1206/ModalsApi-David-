<?php
/*  Plugin name: Modals-Api
    Description: 
*/

 
add_action( 'admin_menu', 'Api_Link' );
 
define( 'API_DIR', plugin_dir_path( __FILE__ ) );



wp_enqueue_style( 'modalsapi_css-style', plugin_dir_url( __FILE__ ) .'css/style_popup-3.css?version=2', array(), null);


function admin_style() {
    // wp_enqueue_style( 'codemirror-style', plugin_dir_url( __FILE__ ) .'css/codemirror.css', array(), null);
    // wp_enqueue_script('codemirror-script', plugin_dir_url( __FILE__ ) .'js/codemirror.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'admin_style');

wp_enqueue_script('aff-script', plugin_dir_url( __FILE__ ) .'js/aff.js', array('jquery'), null, true);
wp_enqueue_script('modalsapi_js-script', plugin_dir_url( __FILE__ ) .'js/script_popup.js', array('jquery'), null, true);



// Добавляем новую ссылку в меню Админ Консоли
function Api_Link()
{
 add_menu_page(
 'Modals-Api',
 'Modals-Api', 
 'manage_options',
 'ModalsApi/entrance.php' 
 );
}


require API_DIR. 'functions.php';