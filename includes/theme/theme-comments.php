<?php 
	if (!function_exists("zoner_custom_comments")) {
		function zoner_custom_comments( $comment, $args, $depth ) {
			global $zoner_config, $zoner_prefix;
			$GLOBALS['comment'] = $comment; 
			?>
			<li id="comment-<?php echo $comment->comment_ID; ?>" <?php comment_class(); ?>>
				<?php 
					$avatar_img = $avatar = '';
					$has_valid_gravatar = false;
					
					$hash = md5(strtolower(trim($comment->comment_author_email)));
					$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
					$headers = @get_headers(esc_url($uri));
					if (!preg_match("|200|", $headers[0])) {
						$has_valid_gravatar = false;
					} else {
						$has_valid_gravatar = true;
					}
					
					if (!$has_valid_gravatar) {
						$all_meta_for_user = get_user_meta( $comment->user_id );
						if (!empty($all_meta_for_user)) {
							if (!empty($all_meta_for_user[$zoner_prefix.'avatar'])) {
								$avatar     = $all_meta_for_user[$zoner_prefix.'avatar'];
							}
						}
						if (!empty($avatar)){
							$avatar_img = '<img width="100%" class="avatar photo" src="'.current($avatar).'" alt="" />';
						} else {
							$avatar_img = '<img width="100%" class="img-responsive" data-src="holder.js/86x86?text='. __('Author', 'zoner-lite') .'" alt="" />';
						}
					} else {
						$avatar_img = get_avatar( $comment,  70 );
					}
				?>
				
				<figure>
					<div class="image">
						<?php echo $avatar_img;  ?>
					</div>
				</figure>
				<div class="comment-wrapper">
					<div class="name"><?php comment_author_link(); ?></div>
					<span class="date"><span class="fa fa-calendar" aria-hidden="true"></span><?php echo get_comment_date(get_option( 'date_format' )) ?> <?php _e('at', 'zoner-lite'); ?> <?php echo get_comment_time(get_option( 'time_format' )); ?></span>
					
					<?php comment_text() ?>
					<?php $myclass = 'reply';
						  echo preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $myclass, get_comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<span class="fa fa-reply" aria-hidden="true"></span>' . __('Reply', 'zoner-lite')))), 1 );
					?>	  
					<?php edit_comment_link(__('Edit this comment', 'zoner-lite'), '', ''); ?>
					<?php if ($comment->comment_approved == '0') { ?>
						<p class='unapproved'><?php _e('Your comment is awaiting moderation.', 'zoner-lite'); ?></p>
					<?php } ?>
					<hr>
				</div><!-- /.comment-content -->
			<?php
		}
	}