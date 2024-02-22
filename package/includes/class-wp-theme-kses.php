<?php
/**
 * WP Theme KSES
 *
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Theme KSES
 *
 * @link  https://developer.wordpress.org/reference/functions/wp_kses/ wp_kses()
 * @since 0.0.1
 */
class WP_Theme_KSES {

	/**
	 * KSES Title
	 *
	 * @since 0.0.1
	 * @var array $kses_title KSES configuration for titles.
	 */
	protected $kses_title = array(
		'span'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'a'      => array(
			'id'     => true,
			'class'  => true,
			'style'  => true,
			'href'   => true,
			'title'  => true,
			'target' => true,
			'rel'    => true,
		),
		'em'     => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strong' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'u'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'code'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'i'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sub'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sup'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strike' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'time'   => array(
			'id'       => true,
			'class'    => true,
			'style'    => true,
			'datetime' => true,
		),
		'br'     => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
	);

	/**
	 * KSES Description
	 *
	 * @since 0.0.1
	 * @var array $kses_title KSES configuration for descriptions.
	 */
	protected $kses_description = array(
		'span'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'a'      => array(
			'id'     => true,
			'class'  => true,
			'style'  => true,
			'href'   => true,
			'title'  => true,
			'target' => true,
			'rel'    => true,
		),
		'em'     => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strong' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'u'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'code'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'i'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sub'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sup'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strike' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'time'   => array(
			'id'       => true,
			'class'    => true,
			'style'    => true,
			'datetime' => true,
		),
		'br'     => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
	);

	/**
	 * KSES Link
	 *
	 * @since 0.0.1
	 * @var array $kses_button KSES configuration for buttons and links.
	 */
	protected $kses_link = array(
		'span'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'em'     => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strong' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'u'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'code'   => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'i'      => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sub'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'sup'    => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'strike' => array(
			'id'    => true,
			'class' => true,
			'style' => true,
		),
		'time'   => array(
			'id'       => true,
			'class'    => true,
			'style'    => true,
			'datetime' => true,
		),
	);

	/**
	 * Init
	 *
	 * @since 0.0.1
	 */
	public function init() {
		add_filter( 'wp_kses_allowed_html', array( $this, 'manage_kses' ), 10, 2 );
	}

	/**
	 * Manage KSES
	 *
	 * @since 0.0.1
	 * @param array  $allowed A KSES definition list.
	 * @param string $context The KSES context identifier.
	 * @return array A filtered KSES definition list.
	 */
	public function manage_kses( array $allowed, string $context ) {
		switch ( $context ) {
			case 'wp_theme_title':
				return $this->kses_title;
			case 'wp_theme_description':
				return $this->kses_description;
			case 'wp_theme_link':
				return $this->kses_link;
		}
		return $allowed;
	}
}
