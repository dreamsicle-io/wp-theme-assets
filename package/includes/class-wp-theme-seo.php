<?php
/**
 * WP Theme SEO
 *
 * @since       0.0.1
 * @package     wp-theme
 * @subpackage  includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Theme_SEO {

	public $max_description_characters = 155;
	public $image_size = 'medium';

	public function init() {
		add_action( 'wp_head', array( $this, 'manage_meta_tags' ), 0 );
    }

	public function manage_meta_tags() {
		$domain = $this->get_domain();
		$site_name = $this->get_site_name();
		$url = $this->get_url();
		$description = $this->get_description();
		$og_type = $this->get_og_type();
		$title = $this->get_title();
		$image_url = $this->get_image_url();
		$site_twitter_username = $this->get_site_twitter_username(); ?>
		<!-- HTML Meta Tags -->
		<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
		<!-- OG Meta Tags -->
		<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>" />
		<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
		<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
		<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
		<meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
		<meta property="og:image" content="<?php echo esc_url( $image_url ); ?>" />
		<?php if ( $og_type === 'profile' ) {
			$user_first_name = $this->get_user_first_name();
			$user_last_name = $this->get_user_last_name();
			$user_username = $this->get_user_username(); ?>
			<meta property="profile:first_name" content="<?php echo esc_attr( $user_first_name ); ?>" />
			<meta property="profile:last_name" content="<?php echo esc_attr( $user_last_name ); ?>" />
			<meta property="profile:username" content="<?php echo esc_attr( $user_username ); ?>" />
		<?php } elseif ( $og_type === 'article' ) {
			$published_date = $this->get_published_date();
			$modified_date = $this->get_modified_date();
			$author_url = $this->get_author_url();
			$categories = $this->get_terms( 'category' );
			$tags = $this->get_terms( 'post_tag' ); ?>
			<meta property="article:published_time" content="<?php echo esc_attr( $published_date ); ?>" />
			<meta property="article:modified_time" content="<?php echo esc_attr( $modified_date ); ?>" />
			<meta property="article:author" content="<?php echo esc_url( $author_url ); ?>" />
			<?php foreach( $categories as $category ) { ?>
				<meta property="article:section" content="<?php echo esc_attr( $category->name ); ?>" />
			<?php } ?>
			<?php foreach( $tags as $tag ) { ?>
				<meta property="article:tag" content="<?php echo esc_attr( $tag->name ); ?>" />
			<?php } ?>
		<?php } ?>
		<!-- Twitter Meta Tags -->
		<?php if ( $site_twitter_username ) { ?>
			<meta name="twitter:site" content="<?php echo esc_attr( $site_twitter_username ); ?>">
			<meta name="twitter:creator" content="<?php echo esc_attr( $site_twitter_username ); ?>" />
		<?php } ?>
		<meta name="twitter:card" content="summary_large_image">
		<meta property="twitter:domain" content="<?php echo esc_attr( $domain ); ?>" />
		<meta property="twitter:url" content="<?php echo esc_url( $url ); ?>" />
		<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
		<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
		<meta name="twitter:image" content="<?php echo esc_url( $image_url ); ?>" />
	<?php }

	public function trim_description( $text = '' ) {
		$text = strip_shortcodes( $text );
		$text = strip_tags( $text );
		$text = trim( $text );
		$text = mb_strimwidth( $text, 0, $this->max_description_characters, '...' );
		return $text;
	}

	public function get_site_name() {
		$site_name = get_bloginfo( 'name' );
		return $site_name;
	}

	public function get_domain() {
		$domain = $_SERVER['HTTP_HOST'];
		return $domain;
	}

	public function get_home_url() {
		$url = home_url('/');
		return $url;
	}

	public function get_url() {
		$url = wp_theme_get_current_url();
		return $url;
	}

	public function get_description() {
		$site_description = get_bloginfo( 'description' );
		$description = '';
		if ( is_singular() ) {
			global $post;
			$description = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
		} elseif ( is_archive() ) {
			$description = get_the_archive_description();
		} elseif ( is_search() ) {
			$description = sprintf(
				_x( 'A search containing all matches for the term: %1$s.', 'search meta description', 'wp-theme' ),
				esc_attr( get_search_query() ), 
			);
		}
		return $this->trim_description( $description ? $description : $site_description );
	}

	public function get_og_type() {
		$site_og_type = 'website';
		$og_type = '';
		if ( is_author() ) {
			$og_type = 'profile';
		} elseif ( is_single() ) {
			$og_type = 'article';
		}
		return $og_type ? $og_type : $site_og_type;
	}

	public function get_title() {
		$title = wp_get_document_title();
		return $title;
	}

	public function get_image_url() {
		$site_image_id = get_option( 'wp_theme_share_image', '' );
		$site_image_url = ! empty( $site_image_id ) ? wp_get_attachment_image_url( $site_image_id[0], $this->image_size, false ) : '';
		$image_url = '';
		if ( is_singular( 'attachment' ) ) {
			global $post;
			$thumbnail_url = get_the_post_thumbnail_url( $post->ID, $this->image_size );
			$attachment_image_url = wp_get_attachment_image_url( $post->ID, $this->image_size, true );
			$image_url = $thumbnail_url ? $thumbnail_url : $attachment_image_url;
		} elseif ( is_singular() ) {
			global $post;
			$image_url = get_the_post_thumbnail_url( $post->ID, $this->image_size );
		} 
		return $image_url ? $image_url : $site_image_url;
	}

	public function get_user_first_name() {
		$user_first_name = '';
		if ( is_author() ) {
			$user = get_queried_object();
			$user_first_name = $user->first_name;
		}
		return $user_first_name;
	}
	
	public function get_user_last_name() {
		$user_last_name = '';
		if ( is_author() ) {
			$user = get_queried_object();
			$user_last_name = $user->last_name;
		}
		return $user_last_name;
	}
	
	public function get_user_username() {
		$user_username = '';
		if ( is_author() ) {
			$user = get_queried_object();
			$user_username = sprintf( '@%1$s', $user->user_login );
		}
		return $user_username;
	}

	public function get_published_date() {
		$published_date = '';
		if ( is_singular() ) {
			global $post;
			$published_date = $post->post_date_gmt;
		}
		return $published_date;
	}

	public function get_modified_date() {
		$modified_date = '';
		if ( is_singular() ) {
			global $post;
			$modified_date = $post->post_modified_gmt;
		}
		return $modified_date;
	}

	public function get_author_url() {
		$author_url = '';
		if ( is_singular() ) {
			global $post;
			$author_url = get_author_posts_url( $post->post_author );
		}
		return $author_url;
	}

	public function get_terms( $taxonomy ) {
		$terms = array();
		if ( is_singular() ) {
			global $post;
			$terms = wp_get_post_terms( $post->ID, $taxonomy );
		}
		return is_array( $terms ) ? $terms : array();
	}

	public function get_site_twitter_username() {
		$site_twitter_username = get_option( 'wp_theme_twitter_handle', '' );
		return $site_twitter_username ? sprintf( '@%1$s', $site_twitter_username ) : '';
	}

}
