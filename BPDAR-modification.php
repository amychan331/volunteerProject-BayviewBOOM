<?php
// Title: Bayview BOOM modifications for BP-DAR
// Author: Amy Yuen Ying Chan, John Weiss
// Description: Modification done to BP Disable Activation Plugin so it will:
//   1) Redirect to Buddypress profile pages.
//   2) Use a selector instead of input box in its Wordpress setting.

// File: bp-disable-activation-loader.php
// Location: Line 207, after "do_action('bp_disable_activation_after_login');"
		$redirection = apply_filters('dar_redirection_url',$options['redirection']);
		
		if( $redirection != '' )
		{
			if ( $redirection === 'memberTypeEditor') {
				// redirect to BP edit profile page if Member Type Editor, which has an option value of memberTypeEditor, is the selected choice.
				// start by getting the field group id for the member type that user selected.
				$FGName = bp_get_member_type($user_id);
				function bp_Get_FieldgroupID($FieldgroupName){
     					global $wpdb;
     					// will want to dynamically find table name for future wordpress installs. 
     					$query = "SELECT ID FROM " . $wpdb->prefix . "bp_xprofile_groups WHERE name = '$FieldgroupName'";
     					return $wpdb->get_var($query);
				}
				$FieldgroupID = bp_Get_FieldgroupID($FGName);
				// redirect according to field group id.
				wp_safe_redirect("../members/$user_info->user_login/profile/edit/group/$FieldgroupID/");
			} elseif ( $redirection === 'profile') {
				wp_safe_redirect("../members/$user_info->user_login/profile");
			} else {
				// otherwise, assume user is requesting a normal Wordpress Page.
				wp_safe_redirect("../$redirection");
			}
			die();
		}

// File: admin/fields.php
// Line location: Line 20, after "$pages = get_pages();"
  	foreach ( $pages as $page ) {
  		$option = array( 
			$page->post_title => __($page->post_title, $this->WPB_PREFIX));
//			'true'            => __( 'Enabled' , $this->WPB_PREFIX)
// 		$option = $page->post_title;
		array_push($redirarray, $option);
		}

                // prepend 'profile' to pages array
                // array_unshift($redirchoices, 'profile');
	
	$this->settings['redirection'] = array(
		'title'   => __( 'Redirection Page' , $this->WPB_PREFIX),
		'desc'    => __( 'Redirect user to this page after activation. Leave empty to disable redirection' , $this->WPB_PREFIX),
		'type'    => 'select',

                // register as choices
		// UNCOMMENT FOR PICKLIST
                //'choices' => $redirarray,
		'choices' => array(
			'memberTypeEditor' => __( 'Member Type Editor' , $this->WPB_PREFIX),
			'profile' => __( 'Profile' , $this->WPB_PREFIX)
			),
		'multiple' => FALSE,
		'std' => 'standard',
		'placeholder' => __( 'Select an Item', 'rwmb' ),
		'section' => 'general'
	);