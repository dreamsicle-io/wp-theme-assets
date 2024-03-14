<?php
/**
 * WP Theme SEO
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme SEO
 *
 * @since 0.0.1
 */
class WP_Theme_SEO {

	/**
	 * Max Characters
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public $max_characters = 155;

	/**
	 * Image Size
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public $image_size = 'medium';

	/**
	 * Related Count
	 *
	 * @since 0.0.1
	 * @var string
	 */
	public $related_count = 6;

	/**
	 * Init
	 *
	 * @since 0.0.1
	 */
	public function init() {
		add_action( 'wp_head', array( $this, 'manage_meta_tags' ), 0 );
	}

	/**
	 * Manage meta tags
	 *
	 * @since 0.0.1
	 */
	public function manage_meta_tags() {
		$domain                = $this->get_domain();
		$site_name             = $this->get_site_name();
		$url                   = $this->get_current_url();
		$description           = $this->get_description();
		$og_type               = $this->get_og_type();
		$title                 = $this->get_title();
		$image_url             = $this->get_image_url();
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
			$user_last_name  = $this->get_user_last_name();
			$user_username   = $this->get_user_username(); ?>
			<meta property="profile:first_name" content="<?php echo esc_attr( $user_first_name ); ?>" />
			<meta property="profile:last_name" content="<?php echo esc_attr( $user_last_name ); ?>" />
			<meta property="profile:username" content="<?php echo esc_attr( $user_username ); ?>" />
		<?php } elseif ( $og_type === 'article' ) {
			$published_date = $this->get_published_date();
			$modified_date  = $this->get_modified_date();
			$author_url     = $this->get_author_url();
			$categories     = $this->get_terms( 'category' );
			$tags           = $this->get_terms( 'post_tag' ); ?>
			<meta property="article:published_time" content="<?php echo esc_attr( $published_date ); ?>" />
			<meta property="article:modified_time" content="<?php echo esc_attr( $modified_date ); ?>" />
			<meta property="article:author" content="<?php echo esc_url( $author_url ); ?>" />
			<?php foreach ( $categories as $category ) { ?>
				<meta property="article:section" content="<?php echo esc_attr( $category->name ); ?>" />
			<?php } ?>
			<?php foreach ( $tags as $tag ) { ?>
				<meta property="article:tag" content="<?php echo esc_attr( $tag->name ); ?>" />
			<?php } ?>
		<?php } ?>
		<!-- Twitter Meta Tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="<?php echo esc_attr( $site_twitter_username ); ?>">
		<meta property="twitter:domain" content="<?php echo esc_attr( $domain ); ?>" />
		<meta property="twitter:url" content="<?php echo esc_url( $url ); ?>" />
		<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
		<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
		<meta name="twitter:image" content="<?php echo esc_url( $image_url ); ?>" />
		<meta name="twitter:creator" content="<?php echo esc_attr( $site_twitter_username ); ?>" />
	<?php }

	/**
	 * Trim Description
	 *
	 * @since 0.0.1
	 * @param string $text The text to be trimmed.
	 */
	public function trim_description( string $text = '' ): string {
		$text = strip_shortcodes( $text );
		$text = wp_strip_all_tags( $text );
		$text = trim( $text );
		$text = mb_strimwidth( $text, 0, $this->max_characters, '...' );
		return $text;
	}

	/**
	 * Get Site Name
	 *
	 * @since 0.0.1
	 */
	public function get_site_name(): string {
		$site_name = get_bloginfo( 'name' );
		return $site_name;
	}

	/**
	 * Get Domain
	 *
	 * @since 0.0.1
	 */
	public function get_domain(): string {
		$url_parts = wp_parse_url( $this->get_home_url() );
		$domain    = $url_parts['host'];
		return $domain;
	}

	/**
	 * Get Home URL
	 *
	 * @since 0.0.1
	 */
	public function get_home_url(): string {
		$url = home_url( '/' );
		return $url;
	}

	/**
	 * Get URL
	 *
	 * @since 0.0.1
	 */
	public function get_current_url(): string {
		global $wp;
		$url = home_url( $wp->request );
		return $url;
	}

	/**
	 * Get Description
	 *
	 * @since 0.0.1
	 */
	public function get_description(): string {
		$site_description = get_bloginfo( 'description' );
		$description      = '';
		if ( is_singular() ) {
			global $post;
			$description = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
		} elseif ( is_archive() ) {
			$description = get_the_archive_description();
		} elseif ( is_search() ) {
			$description = sprintf(
				/* translators: 1: search query. */
				__( 'A search containing all matches for the term: %1$s.', 'wp-theme' ),
				esc_attr( get_search_query() ),
			);
		}
		return $this->trim_description( $description ? $description : $site_description );
	}

	/**
	 * Get OG Type
	 *
	 * @since 0.0.1
	 */
	public function get_og_type(): string {
		$site_og_type = 'website';
		$og_type      = '';
		if ( is_author() ) {
			$og_type = 'profile';
		} elseif ( is_single() ) {
			$og_type = 'article';
		}
		return $og_type ? $og_type : $site_og_type;
	}

	/**
	 * Get Title
	 *
	 * @since 0.0.1
	 */
	public function get_title(): string {
		$title = wp_get_document_title();
		return $title;
	}

	/**
	 * Get Share Image ID
	 *
	 * @since 0.0.1
	 */
	public function get_share_image_id(): int {
		$id    = 0;
		$db_id = get_option( 'wp_theme_share_image' );
		if ( is_array( $db_id ) ) {
			$id = isset( $db_id[0] ) ? absint( $db_id[0] ) : 0;
		} else {
			$id = absint( $db_id );
		}
		return $id;
	}

	/**
	 * Get Image URL
	 *
	 * @since 0.0.1
	 */
	public function get_image_url(): string {
		$site_image_id  = $this->get_share_image_id();
		$site_image_url = ( $site_image_id > 0 ) ? wp_get_attachment_image_url( $site_image_id, $this->image_size, false ) : '';
		$image_url      = '';
		if ( is_singular( 'attachment' ) ) {
			global $post;
			$thumbnail_url        = get_the_post_thumbnail_url( $post->ID, $this->image_size );
			$attachment_image_url = wp_get_attachment_image_url( $post->ID, $this->image_size, true );
			$image_url            = $thumbnail_url ? $thumbnail_url : $attachment_image_url;
		} elseif ( is_singular() ) {
			global $post;
			$image_url = get_the_post_thumbnail_url( $post->ID, $this->image_size );
		}
		return $image_url ? $image_url : $site_image_url;
	}

	/**
	 * Get User First Name
	 *
	 * @since 0.0.1
	 */
	public function get_user_first_name(): string {
		$user_first_name = '';
		if ( is_author() ) {
			$user            = get_queried_object();
			$user_first_name = $user->first_name;
		}
		return $user_first_name;
	}

	/**
	 * Get User Last Name
	 *
	 * @since 0.0.1
	 */
	public function get_user_last_name(): string {
		$user_last_name = '';
		if ( is_author() ) {
			$user           = get_queried_object();
			$user_last_name = $user->last_name;
		}
		return $user_last_name;
	}

	/**
	 * Get User Username
	 *
	 * @since 0.0.1
	 */
	public function get_user_username(): string {
		$user_username = '';
		if ( is_author() ) {
			$user          = get_queried_object();
			$user_username = sprintf( '@%1$s', $user->user_login );
		}
		return $user_username;
	}

	/**
	 * Get Publushed Date
	 *
	 * @since 0.0.1
	 */
	public function get_published_date(): string {
		$published_date = '';
		if ( is_singular() ) {
			global $post;
			$published_date = $post->post_date_gmt;
		}
		return $published_date;
	}

	/**
	 * Get Modified Date
	 *
	 * @since 0.0.1
	 */
	public function get_modified_date(): string {
		$modified_date = '';
		if ( is_singular() ) {
			global $post;
			$modified_date = $post->post_modified_gmt;
		}
		return $modified_date;
	}

	/**
	 * Get Author URL
	 *
	 * @since 0.0.1
	 */
	public function get_author_url(): string {
		$author_url = '';
		if ( is_singular() ) {
			global $post;
			$author_url = get_author_posts_url( $post->post_author );
		}
		return $author_url;
	}

	/**
	 * Get Terms
	 *
	 * @since 0.0.1
	 * @param string $taxonomy The name of the taxonomy.
	 */
	public function get_terms( string $taxonomy ): array {
		$terms = array();
		if ( is_singular() ) {
			global $post;
			$terms = wp_get_post_terms( $post->ID, $taxonomy );
		}
		return is_array( $terms ) ? $terms : array();
	}

	/**
	 * Get Twitter Username
	 *
	 * @since 0.0.1
	 */
	public function get_site_twitter_username(): string {
		$username = get_option( 'wp_theme_twitter_handle', '' );
		return $username ? sprintf( '@%1$s', $username ) : '';
	}

}
