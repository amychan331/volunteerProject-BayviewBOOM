<?php
// Description: Simple function to correctly redirect users to the profile page
//   when both the plugin Buddypress and BP Disable Activation Reloaded (BP-DAR) is activated
//   since the later one does not redirect user to the desired page.

//Required conditions:
//  1) A page with permalink ending in '/profile' was created and would redirect to the correct Buddypress Profile page, and
//  2) Plugin BP-DAR has its Redirection Url set to '../profile' - yes, turn out that the BP-DAR plugin uses relative path.
function bp_redirectProfile(){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_user_logged_in()){
        if ( strstr($_SERVER['REQUEST_URI'], 'profile') && is_plugin_active('buddypress/bp-loader.php')) {
            global $current_user;
            wp_redirect( bp_loggedin_user_domain() . "/profile/" ); 
            exit();
        }
    }
}
add_action('init', 'bp_redirectProfile');

?>