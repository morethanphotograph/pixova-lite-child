<?php
add_action( 'wp_enqueue_scripts', 'pixova_lite_child_theme_enqueue_styles' );
function pixova_lite_child_theme_enqueue_styles() {
wp_enqueue_style( 'pixova-lite', get_template_directory_uri() . '/style.css' );
wp_enqueue_style( 'pixova-lite-child-theme', get_stylesheet_uri() );
}
/* TGM Plugin Activation */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'my_plugin_activation' );
function my_plugin_activation() {
 
    $plugins = array(
            // Gọi một plugin trong thư viện WordPress.org/plugins
            array(
                'name'      => 'Better WordPress Minify',
                'slug'      => 'bwp-minify', //Tên slug của plugin trên URL
                'required'  => true,
            ),
	array(
                'name'      => 'Speed Booster Pack',
                'slug'      => 'speed-booster-pack', //Tên slug của plugin trên URL
                'required'  => true,
            ),
	array(
                'name'      => 'WP Super Cache',
                'slug'      => 'wp-super-cache', //Tên slug của plugin trên URL
                'required'  => true,
            ),	
	array(
                'name'      => 'Tawk.to Live Chat',
                'slug'      => 'tawkto-live-chat', //Tên slug của plugin trên URL
                'required'  => true,
            ),
	array(
                'name'      => 'Redux Framework',
                'slug'      => 'redux-framework', //Tên slug của plugin trên URL
                'required'  => true,
            ),		
 
        ); // end $plugins
 
    $config = array(
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Có hiển thị thông báo hay không
        'dismissable'  => true,                    // Nếu đặt false thì người dùng không thể hủy thông báo cho đến khi cài hết plugin.
        'dismiss_msg'  => '',                      // Nếu 'dismissable' là false, thì tin nhắn ở đây sẽ hiển thị trên cùng trang Admin.
        'is_automatic' => false,                   // Nếu là false thì plugin sẽ không tự động kích hoạt khi cài xong.
        'message'      => '',
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    ); // end $config
    tgmpa( $plugins, $config );
} 



function theme_pre_set_transient_update_theme ( $transient ) {
 if( empty( $transient->checked[‘pixova-lite-child’] ) )
    return $transient;

  $ch = curl_init();
 
  curl_setopt($ch, CURLOPT_URL, 'http://localhost:88/update/update.json' );
 
 // 3 second timeout to avoid issue on the server
 curl_setopt($ch, CURLOPT_TIMEOUT, 3 ); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

 $result = curl_exec($ch);
 curl_close($ch);

 // make sure that we received the data in the response is not empty
 if( empty( $result ) )
   return $transient;

 //check server version against current installed version
 if( $data = json_decode( $result ) ){
   if( version_compare( $transient->checked['theme-name'], $data->new_version, '<' ) )
 $transient->response['theme-name'] = (array) $data;
 }
 
 return $transient;

} 

