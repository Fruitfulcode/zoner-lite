<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Zoner_Theme
 * @since Zoner Theme 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area comments">
	<?php if ( have_comments() ) : ?>
	
	<header>
		<h2 class="no-border"><?php printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'zoner' ), number_format_i18n(get_comments_number())); ?></h2>
	</header>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'zoner' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'zoner' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'zoner' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>
	
	<ul class="comment-list">
		<?php
			wp_list_comments( array(
				'callback' => 'zoner_custom_comments', 
				'type' 	   => 'comment', 
				'style'       => 'ul',
				'avatar_size' => 86
			) );
		?>
	</ul><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'zoner' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'zoner' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'zoner' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>
				
	<?php 
		$commenter = wp_get_current_commenter();
		$req 	   = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
  
		$args = array(
					'id_form' => 'form-blog-reply',
					'label_submit' => __( 'Leave a Reply', 'zoner' ),
					'class_submit' => 'btn pull-right btn-default',
					'fields'  => apply_filters( 'comment_form_default_fields', 
						 array(
							'author'  => '
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="author">'. __('Your Name', 'zoner') .'<em>*</em></label>
														<input type="text" class="form-control" id="author" name="author" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
													</div><!-- /.form-group -->
												</div><!-- /.col-md-6 -->
										 ',
							'email'   => '
												<div class="col-md-6">
													<div class="form-group">
														<label for="email">' . __('Your Email', 'zoner') .'<em>*</em></label>
														<input type="email" class="form-control" id="email" name="email" ' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" '. $aria_req .' />
													</div><!-- /.form-group -->
												</div><!-- /.col-md-6 -->
											</div><!-- /.row -->
										',
						)	
					),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'comment_field' =>  '
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label for="comment">'. __('Your Message', 'zoner') .'<em>*</em></label>
														<textarea class="form-control" id="comment" cols="45" rows="8" name="comment" '. $aria_req .'></textarea>
													</div><!-- /.form-group -->
												</div><!-- /.col-md-12 -->
											</div><!-- /.row -->
					',					
				);	
		zoner_comment_form($args); 	
	?>

</section><!-- #comments -->
