<?php
/*
Plugin Name: Widgets Text Icon
Plugin URI: https://github.com/aryaprakasa/widget-text-icon
Description: Basically is a text widget but with icon selector based on font-awesome.
Author: Arya Prakasa
Author URI: http://prakasa.me/

Version: 0.1

License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

class Widget_Text_Icon extends WP_Widget {

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $icon_type;

	/**
	 * Default widget values.
	 *
	 * @var array
	 */
	protected $sizes;

	/**
	 * Constructor method.
	 *
	 * Set some global values and create widget.
	 */
	function __construct() {

		/**
		 * Default widget option values.
		 */
		$this->defaults = array(
			'title'		=> '',
			'icon'		=> '',
			'size'		=> '16',
			'text'		=> '',
			'filter'	=> 0
		);

		/**
		 * Icon type.
		 */
		$this->icon_type = array( 'icon-glass', 'icon-music', 'icon-search', 'icon-envelope', 'icon-heart', 'icon-star', 'icon-star-empty', 'icon-user', 'icon-film', 'icon-th-large', 'icon-th', 'icon-th-list', 'icon-ok', 'icon-remove', 'icon-zoom-in', 'icon-zoom-out', 'icon-off', 'icon-signal', 'icon-cog', 'icon-trash', 'icon-home', 'icon-file', 'icon-time', 'icon-road', 'icon-download-alt', 'icon-download', 'icon-upload', 'icon-inbox', 'icon-play-circle', 'icon-repeat', 'icon-refresh', 'icon-list-alt', 'icon-lock', 'icon-flag', 'icon-headphones', 'icon-volume-off', 'icon-volume-down', 'icon-volume-up', 'icon-qrcode', 'icon-barcode', 'icon-tag', 'icon-tags', 'icon-book', 'icon-bookmark', 'icon-print', 'icon-camera', 'icon-font', 'icon-bold', 'icon-italic', 'icon-text-height', 'icon-text-width', 'icon-align-left', 'icon-align-center', 'icon-align-right', 'icon-align-justify', 'icon-list', 'icon-indent-left', 'icon-indent-right', 'icon-facetime-video', 'icon-picture', 'icon-pencil', 'icon-map-marker', 'icon-adjust', 'icon-tint', 'icon-edit', 'icon-share', 'icon-check', 'icon-move', 'icon-step-backward', 'icon-fast-backward', 'icon-backward', 'icon-play', 'icon-pause', 'icon-stop', 'icon-forward', 'icon-fast-forward', 'icon-step-forward', 'icon-eject', 'icon-chevron-left', 'icon-chevron-right', 'icon-plus-sign', 'icon-minus-sign', 'icon-remove-sign', 'icon-ok-sign', 'icon-question-sign', 'icon-info-sign', 'icon-screenshot', 'icon-remove-circle', 'icon-ok-circle', 'icon-ban-circle', 'icon-arrow-left', 'icon-arrow-right', 'icon-arrow-up', 'icon-arrow-down', 'icon-share-alt', 'icon-resize-full', 'icon-resize-small', 'icon-plus', 'icon-minus', 'icon-asterisk', 'icon-exclamation-sign', 'icon-gift', 'icon-leaf', 'icon-fire', 'icon-eye-open', 'icon-eye-close', 'icon-warning-sign', 'icon-plane', 'icon-calendar', 'icon-random', 'icon-comment', 'icon-magnet', 'icon-chevron-up', 'icon-chevron-down', 'icon-retweet', 'icon-shopping-cart', 'icon-folder-close', 'icon-folder-open', 'icon-resize-vertical', 'icon-resize-horizontal', 'icon-bar-chart', 'icon-twitter-sign', 'icon-facebook-sign', 'icon-camera-retro', 'icon-key', 'icon-cogs', 'icon-comments', 'icon-thumbs-up', 'icon-thumbs-down', 'icon-star-half', 'icon-heart-empty', 'icon-signout', 'icon-linkedin-sign', 'icon-pushpin', 'icon-external-link', 'icon-signin', 'icon-trophy', 'icon-github-sign', 'icon-upload-alt', 'icon-lemon', 'icon-phone', 'icon-check-empty', 'icon-bookmark-empty', 'icon-phone-sign', 'icon-twitter', 'icon-facebook', 'icon-github', 'icon-unlock', 'icon-credit-card', 'icon-rss', 'icon-hdd', 'icon-bullhorn', 'icon-bell', 'icon-certificate', 'icon-hand-right', 'icon-hand-left', 'icon-hand-up', 'icon-hand-down', 'icon-circle-arrow-left', 'icon-circle-arrow-right', 'icon-circle-arrow-up', 'icon-circle-arrow-down', 'icon-globe', 'icon-wrench', 'icon-tasks', 'icon-filter', 'icon-briefcase', 'icon-fullscreen', 'icon-group', 'icon-link', 'icon-cloud', 'icon-beaker', 'icon-cut', 'icon-copy', 'icon-paper-clip', 'icon-save', 'icon-sign-blank', 'icon-reorder', 'icon-list-ul', 'icon-list-ol', 'icon-strikethrough', 'icon-underline', 'icon-table', 'icon-magic', 'icon-truck', 'icon-pinterest', 'icon-pinterest-sign', 'icon-google-plus-sign', 'icon-google-plus', 'icon-money', 'icon-caret-down', 'icon-caret-up', 'icon-caret-left', 'icon-caret-right', 'icon-columns', 'icon-sort', 'icon-sort-down', 'icon-sort-up', 'icon-envelope-alt', 'icon-linkedin', 'icon-undo', 'icon-legal', 'icon-dashboard', 'icon-comment-alt', 'icon-comments-alt', 'icon-bolt', 'icon-sitemap', 'icon-umbrella', 'icon-paste', 'icon-user-md' );

		/**
		 * Icon sizes.
		 */
		$this->sizes = array( '16', '24', '32', '48' );

		$widget_ops = array(
			'classname'	  => 'widget-text-icon',
			'description' => __( 'Displays icon before widget title.', 'wti' ),
		);

		$control_ops = array(
			'id_base' => 'widget-text-icon',
			'width'   => 400,
			#'height'  => 200,
		);

		$this->WP_Widget( 'widget-text-icon', __( 'Widget Text Icons', 'wti' ), $widget_ops, $control_ops );

		/** Load font-awesome.css  */
		add_action( 'wp_head', array( $this, 'css' ), 8 );

	}

	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 */
	function form( $instance ) {

		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		/** sort the array */
		$icons = (array) $this->icon_type;
		sort( $icons );

		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Choose icon', 'ssiw' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
				<?php
				foreach ( $icons as $icon ) {
					printf( '<option value="%s" %s>%s</option>', $icon, selected( $icon, $instance['icon'], 0 ), $icon );
				}
				?>
			</select>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size', 'ssiw' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
				<?php
				foreach ( (array) $this->sizes as $size ) {
					printf( '<option value="%d" %s>%dpx</option>', (int) $size, selected( $size, $instance['size'], 0 ), (int) $size );
				}
				?>
			</select>
		</p>

		<textarea class="widefat" rows="14" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>

		<?php

	}

	/**
	 * Form validation and sanitization.
	 *
	 * Runs when you save the widget form. Allows you to validate or sanitize widget options before they are saved.
	 *
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['icon'] = $new_instance['icon'];
		$instance['size'] = $new_instance['size'];

		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
		$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	/**
	 * Widget Output.
	 *
	 * Outputs the actual widget on the front-end based on the widget options the user selected.
	 *
	 */
	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

		$size = ( !empty ($instance['size']) ) ? 'font-size: '.$instance['size'].'px;' : '' ;

		$icon = '<i class="'.$instance['icon'].'" style="padding-right:10px;'.$size.'text-align:center;" ></i>';
		
		echo $before_widget;

		if ( !empty( $title ) ) { echo $before_title . $icon . $title . $after_title; }?>

		<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>

		<?php  echo $after_widget;

	}

	/** Load font-awesome.css if widget active */
	function css() {
		global $is_IE;

		wp_register_style( 'font-awesome', plugin_dir_url( __FILE__ ) .'css/font-awesome.css', array(), '0.1', 'all' );
		wp_register_style( 'font-awesome-ie7', plugin_dir_url( __FILE__ ) .'css/font-awesome-ie7.css', array(), '0.1', 'all' );

		if ( !is_admin() && is_active_widget( false, false, $this->id_base, true ) ) {
			wp_enqueue_style( 'font-awesome' );
		} elseif ( !is_admin() && is_active_widget( false, false, $this->id_base, true ) && $is_IE ) {
			wp_enqueue_style( 'font-awesome' );
			wp_enqueue_style( 'font-awesome-ie7' );
		}
	}

}

add_action( 'widgets_init', 'wti_load_widget' );
/**
 * Widget Registration.
 *
 * Register Widget Text Icon.
 *
 */
function wti_load_widget() {

	register_widget( 'Widget_Text_Icon' );

}