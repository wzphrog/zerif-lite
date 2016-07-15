<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 */

if ( ! function_exists( 'zerif_paging_nav' ) ) :

/**
 * Display navigation to next/previous set of posts when applicable.
 */

function zerif_paging_nav() {

	echo '<div class="clear"></div>';

	?>

	<nav class="navigation paging-navigation">

		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'zerif-lite' ); ?></h2>

		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>

			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'zerif-lite' ) ); ?></div>

			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>

			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'zerif-lite' ) ); ?></div>

			<?php endif; ?>

		</div><!-- .nav-links -->

	</nav><!-- .navigation -->

	<?php

}

endif;

if ( ! function_exists( 'zerif_post_nav' ) ) :

/**
* Display navigation to next/previous post when applicable.
*/

function zerif_post_nav() {

	// Don't print empty markup if there's nowhere to navigate.

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );

	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {

		return;

	}

	?>

	<nav class="navigation post-navigation">

		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'zerif-lite' ); ?></h2>

		<div class="nav-links">

			<?php

				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'zerif-lite' ) );

				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'zerif-lite' ) );

			?>

		</div><!-- .nav-links -->

	</nav><!-- .navigation -->

	<?php

}

endif;

if ( ! function_exists( 'zerif_posted_on' ) ) :

/**

 * Prints HTML with meta information for the current post-date/time and author.

 */

function zerif_posted_on() {

	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {

		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';

	}

	$time_string = sprintf( $time_string,

		esc_attr( get_the_date( 'c' ) ),

		esc_html( get_the_date() ),

		esc_attr( get_the_modified_date( 'c' ) ),

		esc_html( get_the_modified_date() )

	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'zerif-lite' ),

		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',

			esc_url( get_permalink() ),

			$time_string

		),

		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',

			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),

			esc_html( get_the_author() )

		)

	);

}

endif;

/**

 * Returns true if a blog has more than 1 category.

 *

 * @return bool

 */

function zerif_categorized_blog() {

	if ( false === ( $all_the_cool_cats = get_transient( 'zerif_categories' ) ) ) {

		// Create an array of all the categories that are attached to posts.

		$all_the_cool_cats = get_categories( array(

			'fields'     => 'ids',

			'hide_empty' => 1,



			// We only need to know if there is more than one category.

			'number'     => 2,

		) );



		// Count the number of categories that are attached to the posts.

		$all_the_cool_cats = count( $all_the_cool_cats );



		set_transient( 'zerif_categories', $all_the_cool_cats );

	}



	if ( $all_the_cool_cats > 1 ) {

		// This blog has more than 1 category so zerif_categorized_blog should return true.

		return true;

	} else {

		// This blog has only 1 category so zerif_categorized_blog should return false.

		return false;

	}

}

/**
 * Flush out the transients used in zerif_categorized_blog.
 */

function zerif_category_transient_flusher() {

	delete_transient( 'zerif_categories' );

}

add_action( 'edit_category', 'zerif_category_transient_flusher' );

add_action( 'save_post',     'zerif_category_transient_flusher' );

/********************************/
/*********** HOOKS **************/
/********************************/

function zerif_404_title_function() {

	?><h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'zerif-lite' ); ?></h1><?php

}

function zerif_404_content_function() {

	?><p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'zerif-lite' ); ?></p><?php

}

function zerif_page_header_function() {

	?><h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1><?php

}

function zerif_page_header_title_archive_function() {
	?>
	<h1 class="page-title">

		<?php

		if ( is_category() ) :

			single_cat_title();

		elseif ( is_tag() ) :

			single_tag_title();

		elseif ( is_author() ) :

			printf( __( 'Author: %s', 'zerif-lite' ), '<span class="vcard">' . get_the_author() . '</span>' );

		elseif ( is_day() ) :

			printf( __( 'Day: %s', 'zerif-lite' ), '<span>' . get_the_date() . '</span>' );

		elseif ( is_month() ) :

			printf( __( 'Month: %s', 'zerif-lite' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'zerif-lite' ) ) . '</span>' );

		elseif ( is_year() ) :

			printf( __( 'Year: %s', 'zerif-lite' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'zerif-lite' ) ) . '</span>' );

		elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :

			_e( 'Asides', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :

			_e( 'Galleries', 'zerif-lite');

		elseif ( is_tax( 'post_format', 'post-format-image' ) ) :

			_e( 'Images', 'zerif-lite');

		elseif ( is_tax( 'post_format', 'post-format-video' ) ) :

			_e( 'Videos', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :

			_e( 'Quotes', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-link' ) ) :

			_e( 'Links', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-status' ) ) :

			_e( 'Statuses', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :

			_e( 'Audios', 'zerif-lite' );

		elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :

			_e( 'Chats', 'zerif-lite' );

		else :

			_e( 'Archives', 'zerif-lite' );

		endif;

		?>

	</h1>
	<?php
}

function zerif_page_term_description_archive_function() {
	$term_description = term_description();

	if ( ! empty( $term_description ) ) :

		printf( '<div class="taxonomy-description">%s</div>', $term_description );

	endif;
}