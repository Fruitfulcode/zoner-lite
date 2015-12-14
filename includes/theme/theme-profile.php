<?php 
if ( ! function_exists( 'zoner_generate_profile_tabs' ) ) {				
	function zoner_generate_profile_tabs() {
		global $zoner_config, $zoner;
		
		$tab_val = array('name' => __( 'Profile', 		'zoner-lite' ), 'icon' => 'fa-user');
		
		?>
		<div class="col-md-3 col-sm-2">
			<section id="sidebar" class="sidebar">
				<header><h3 class="widget-title"><?php _e('Account', 'zoner-lite'); ?></h3></header>
					<aside>
						<ul class="sidebar-navigation">
							<li class="active">
								<i class="fa <?php echo $tab_val['icon']; ?>"></i>
								<span><?php echo $tab_val['name']; ?></span>
							</li>		
						</ul>
					</aside>
			</section>
		</div>
	<?php 	
	}
}	

if ( ! function_exists( 'zoner_get_profile_avartar' ) ) {				
	function zoner_get_profile_avartar($userID = '') {
		global $zoner_config, $zoner_prefix, $zoner;
		if ($userID == '') $userID = get_current_user_id();
		
		$all_meta_for_user = get_user_meta( $userID );
		$avatar = '';
		
		$img_url = get_template_directory_uri() . '/includes/theme/profile/res/avatar.jpg';

		if (!empty($all_meta_for_user[$zoner_prefix.'avatar']))
			$avatar = $all_meta_for_user[$zoner_prefix.'avatar'];
		if (!empty($all_meta_for_user[$zoner_prefix.'avatar_id']))
			$avatar_id = $all_meta_for_user[$zoner_prefix.'avatar_id'];
			
		if (is_array($avatar)) { 
				$avatar = array_filter($avatar);
				if (!empty($avatar_id)) {
					$avatar_icon = wp_get_attachment_image_src( current($avatar_id), 'zoner-avatar-ceo' );
					if (!empty($avatar_icon)) $img_url = $avatar_icon[0];
				} else {
					if (!empty($avatar)) $img_url = current($avatar);
				}			
		}	
		
		return '<img alt="" id="avatar-image" class="image" src="'.esc_url($img_url).'">';
	}
}	


if ( ! function_exists( 'zoner_get_user_id' ) ) {				
	function zoner_get_user_id() {
		global $zoner_config, $zoner_prefix, $wp_query, $zoner;
				
		$user_id = get_current_user_id();
		if( array_key_exists('author_name', $wp_query->query_vars) && !empty($wp_query->query_vars['author_name'])) {
			$user_data = $wp_query->get_queried_object('WP_User');
			if ($user_id != $user_data->ID) $user_id = $user_data->ID;
		}
		
		return $user_id;
	}
}	

if ( ! function_exists( 'zoner_get_author_content' ) ) {				
	function zoner_get_author_content() {
		global $zoner_config, $zoner, $zoner_prefix;
		
		$query_author  = get_queried_object();
		$query_user_id = $query_author->ID;
		$curr_user_id  = get_current_user_id();
		
		if (is_author() && is_user_logged_in() && ($curr_user_id == $query_user_id)) {	
			zoner_generate_profile_tabs();
			zoner_generate_profile_info();
		} elseif (is_author() || ($curr_user_id != $query_user_id)) {
			zoner_get_author_information();
		}
	}		
}

if ( ! function_exists( 'zoner_generate_profile_info' ) ) {				
	function zoner_generate_profile_info() {
		global $zoner_config, $zoner_prefix, $zoner;
		
		$avatar = '';
		$avatar_id = -1;
		$userID = zoner_get_user_id();
		$all_meta_for_user = get_user_meta( $userID );
		
		if (!empty($all_meta_for_user[$zoner_prefix.'avatar']))
		$avatar    = current($all_meta_for_user[$zoner_prefix.'avatar']);
		
		if (!empty($all_meta_for_user[$zoner_prefix.'avatar_id']))
		$avatar_id = current($all_meta_for_user[$zoner_prefix.'avatar_id']);
		
		
	?>
		<div class="col-md-9 col-sm-10">
			<section id="profile">
				<header><h1><?php _e('Profile', 'zoner-lite'); ?></h1></header>
					<div class="account-profile">
						<div class="row">
							<form role="form" id="form-account-profile" class="form-account-profile" method="post" action="" enctype="multipart/form-data">
								<?php wp_nonce_field( 'zoner_save_profile', 'save_profile', true, true ); ?>
								<input type="hidden" id="form-account-avatar" 	 name="form-account-avatar"    value="<?php echo $avatar;   ?>" />
								<input type="hidden" id="form-account-avatar-id" name="form-account-avatar-id" value="<?php echo $avatar_id; ?>" />
									
								<div class="col-md-3 col-sm-3">
									<div class="avatar-wrapper">
										<?php if ($avatar_id != -1) { ?>
												<span class="remove-btn"><i class="fa fa-trash-o"></i></span>
										<?php } ?>
										<?php echo zoner_get_profile_avartar($userID); ?>
									</div>
									<div class="form-group tool-tip-info"  data-original-title="<?php _e('image size has to be less than 1 MB', 'zoner-lite'); ?>">
										<input id="form-account-avatar-file" name="form-account-avatar-file" class="file-inputs" type="file" title="<?php _e('Upload Avatar', 'zoner-lite'); ?>" data-filename-placement="inside" value="">
									</div>
								</div>
								
								<div class="col-md-9 col-sm-9">
									<section id="contact">
										<h3><?php _e('Contact', 'zoner-lite'); ?></h3>
										<dl class="contact-fields">
											<dt><label for="form-account-fname"><?php _e('First Name', 'zoner-lite'); ?>:</label></dt>
											<dd><div class="form-group">
												<input type="text" class="form-control" id="form-account-fname" name="form-account-fname" required value="<?php the_author_meta( 'first_name', $userID ); ?>">
											</div><!-- /.form-group --></dd>
											
											<dt><label for="form-account-lname"><?php _e('Last Name', 'zoner-lite'); ?>:</label></dt>
											<dd><div class="form-group">
												<input type="text" class="form-control" id="form-account-lname" name="form-account-lname" required value="<?php the_author_meta( 'last_name', $userID ); ?>">
											</div><!-- /.form-group --></dd>
											
											<dt><label for="form-account-email"><?php _e('Email', 'zoner-lite');?>:</label></dt>
											<dd><div class="form-group">
												<input type="text" class="form-control" id="form-account-email" name="form-account-email" value="<?php the_author_meta( 'user_email', $userID ); ?>" disabled="disabled">
											</div><!-- /.form-group --></dd>
										</dl>
									</section>
									<section id="about-me">
										<h3><?php _e('About Me', 'zoner-lite'); ?></h3>
										<div class="form-group">
											<label class="screen-reader-text" for="form-contact-agent-message"><?php _e('About me', 'zoner-lite'); ?>:</label>
											<textarea class="form-control" id="form-contact-agent-message" rows="5" name="form-contact-agent-message"><?php the_author_meta( 'description', $userID ); ?></textarea>
										</div><!-- /.form-group -->
										<div class="form-group clearfix">
											<button type="submit" class="btn pull-right btn-default" id="account-submit"><?php _e('Save Changes', 'zoner-lite'); ?></button>
										</div><!-- /.form-group -->
									</section>
								</div>
							</form><!-- /#form-contact -->
						
						
						<div class="col-md-offset-3 col-md-9 col-sm-10">			
							<section id="change-password">
								<header><h2><?php _e('Change Your Password', 'zoner-lite'); ?></h2></header>
								<div class="row">
									<div class="col-md-6 col-sm-6">
										<form role="form" id="form-account-password" class="form-account-password" method="post" action="">
											<?php wp_nonce_field( 'zoner_change_password', 'change_password', true, true ); ?>
											<div class="form-group">
												<label for="form-account-password-current"><?php _e('Current Password', 'zoner-lite'); ?></label>
												<input type="password" class="form-control" id="form-account-password-current" name="form-account-password-current" required>
											</div><!-- /.form-group -->
											<div class="form-group">
												<label for="form-account-password-new"><?php _e('New Password','zoner-lite'); ?> </label>
												<input type="password" class="form-control" id="form-account-password-new" name="form-account-password-new" required>
											</div><!-- /.form-group -->
											<div class="form-group">
												<label for="form-account-password-confirm-new"><?php _e('Confirm New Password', 'zoner-lite'); ?></label>
												<input type="password" class="form-control" id="form-account-password-confirm-new" name="form-account-password-confirm-new" required>
											</div><!-- /.form-group -->
											<div class="form-group clearfix">
												<button type="submit" class="btn btn-default" id="form-account-password-submit"><?php _e('Change Password', 'zoner-lite');?></button>
											</div><!-- /.form-group -->
										</form><!-- /#form-account-password -->
									</div>
									
									<div class="col-md-6 col-sm-6">
										<strong><?php _e('Hint', 'zoner-lite'); ?>:</strong>
										<p><?php _e('Be careful. After you change the password, the password is automatically applied.', 'zoner-lite'); ?></p>
										<p><?php _e("If you don't have a current password you can sign out and reset your password at sign in page.", 'zoner-lite'); ?></p>
									</div>
								</div>
							</section>
						</div><!-- /.col-md-9 -->
					</div><!-- /.row -->
				</div><!-- /.account-profile -->
			</section><!-- /#profile -->
		</div><!-- /.col-md-9 -->
	<?php 			
	}
}	

if ( ! function_exists( 'zoner_process_save_profile' ) ) {				
	function zoner_process_save_profile() {
		global $zoner_config, $zoner_prefix, $zoner;
			   $userID = zoner_get_user_id();
			   $attach_id = -1;

		if ( is_author()  && isset($_POST['save_profile']) && wp_verify_nonce($_POST['save_profile'], 'zoner_save_profile')) {
			update_user_meta( $userID, 'first_name', 			 $_POST['form-account-fname']);
			update_user_meta( $userID, 'last_name',  			 $_POST['form-account-lname']);
			update_user_meta( $userID, 'user_email', 			 sanitize_email($_POST['form-account-email']));
			update_user_meta( $userID, 'description', 			 $_POST['form-contact-agent-message']);
			
			if (empty($_POST['form-account-avatar-id']) && empty($_POST['form-account-avatar'])) {
				delete_user_meta( $userID,  $zoner_prefix.'avatar' );
				delete_user_meta( $userID,  $zoner_prefix.'avatar_id');
			}
		}
		
		if (!empty($_FILES['form-account-avatar-file']['name'])) {
			
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
			
			foreach ($_FILES as $file => $array) {
				if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) __return_false();
				$attach_id = media_handle_upload( $file, 0 );
			}   
			
			if ($attach_id != -1) {
				update_user_meta( $userID,  $zoner_prefix.'avatar', 	 wp_get_attachment_url( $attach_id ));
				update_user_meta( $userID,  $zoner_prefix.'avatar_id', $attach_id);
			}	
		} 
		
		if (!empty($_POST) && isset($_POST['save_profile'])) wp_safe_redirect( get_author_posts_url($userID) );
	}
}


/*Actions*/
if ( ! function_exists( 'zoner_check_user_password_act' ) ) {				
	function zoner_check_user_password_act() {
		$user = '';
		if (isset($_POST) && ($_POST['action'] == 'zoner_check_user_password')) {
			$user = get_user_by( 'id', get_current_user_id() );
			$pass = $_POST['form-account-password-current'];
			if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID) )
				echo 'true';
			else
				echo 'false';
		}	
		die();	
	}
}	

if ( ! function_exists( 'zoner_change_user_pass_act' ) ) {
	function zoner_change_user_pass_act() {
		$user_id = get_current_user_id();	
		if (isset($_POST) && isset($_POST['change_password']) && wp_verify_nonce($_POST['change_password'], 'zoner_change_password')) {
			$pass = '';
			if (isset($_POST['form-account-password-confirm-new']))
			  $pass = $_POST['form-account-password-confirm-new'];
		  	  wp_set_password( $pass, get_current_user_id());
			  wp_set_auth_cookie( $user_id, false, is_ssl() );
			  
		}
		
		if (!empty($_POST) && isset($_POST['change_password'])) wp_safe_redirect( get_author_posts_url($user_id) );
	}
}
