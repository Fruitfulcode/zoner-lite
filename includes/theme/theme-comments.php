<?php 
	if (!function_exists("zoner_custom_comments")) {
		function zoner_custom_comments( $comment, $args, $depth ) {
			global $zoner_config, $zoner_prefix;
			$GLOBALS['comment'] = $comment; 
			?>
			<li id="comment-<?php echo $comment->comment_ID; ?>" <?php comment_class(); ?>>
				<figure>
					<div class="image">
						<?php echo get_avatar( $comment,  70 );  ?>
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