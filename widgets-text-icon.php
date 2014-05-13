<?php
/*
Plugin Name: Easy FontAwesome Icon + Text Widget
Description: A simple widget which creates FontAwesome icon + text area.
Originally forked from https://github.com/wp-plugins/widget-text-icon by Arya Prakasa.
Author: Tarei King
Author URI: http://tarei.me/

Version: 0.1

License: GNU General Public License v2.0 (or later)
License URI: http://www.opensource.org/licenses/gpl-license.php
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
		 * Icon sizes.
		 */
		$this->sizes = array( '14', '16', '24', '32', '48', '64' );

		$widget_ops = array(
			'classname'	  => 'widget-text-icon',
			'description' => __( 'Displays icon from FontAwesome before widget title.', 'wti' ),
		);

		$control_ops = array(
			'id_base' => 'widget-text-icon',
			'width'   => 400,
			#'height'  => 200,
		);

		$this->WP_Widget( 'widget-text-icon', __( 'Widget Text Icons', 'wti' ), $widget_ops, $control_ops );

		/** Load font-awesome.css  */
		add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 8 );

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

		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wti' ); ?>:</label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Choose Icon', 'wti' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
				<?php
				foreach ( ayo_fontawesome() as $icons => $icon ) {
					printf( '<option value="%s" %s>%s</option>', $icon, selected( $icon, $instance['icon'], 0 ), $icon );
				}
				?>
			</select>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size', 'wti' ); ?>:</label>
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

		$size = ( ! empty( $instance['size'] ) ) ? 'font-size: '. $instance['size'] .'px;' : '' ;
		$icon = ( ! empty( $instance['icon'] ) ) ? '<i class="icon-'. $instance['icon'] .'" style="line-height:1em;margin-right:10px;'. $size .'" ></i>' : '';

		echo $before_widget;

		if ( ! empty( $title ) )
			echo $before_title . $icon . $title . $after_title;
		?>

		<div class="textwidget-icon"><?php echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>

		<?php  echo $after_widget;

	}

	/** Load font-awesome.css if widget active */
	function css() {
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$browser = substr( "$browser", 25, 8);

	    /** Register Fontawesome v.3.0.2 */
		if ( ! wp_style_is( "fontawesome", "registered" ) )
	    	wp_register_style( "fontawesome", "//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css", array(), "3.0.2", "all" );
		if ( ! wp_style_is( "fontawesome-ie7", "registered" ) )
			wp_register_style( "fontawesome-ie7", "//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome-ie7.css", array(), "3.0.2", "all" );
		
		if ( ! is_admin() && is_active_widget( false, false, $this->id_base, true ) ) {
			wp_enqueue_style( 'fontawesome' );
		} elseif ( ! is_admin() && is_active_widget( false, false, $this->id_base, true ) && $browser == "MSIE 7.0" ) {
			wp_enqueue_style( 'fontawesome-ie7' );
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

if ( ! function_exists( 'ayo_fontawesome' ) ) :
/**
 * This function is an array for listing fontawesome classes
 *
 * @since 		0.2.2
 * @var 		array
 * @link 		http://fortawesome.github.com/Font-Awesome/
 * @license  	https://github.com/FortAwesome/Font-Awesome#license
 * @version 	3.0.2
 */
function ayo_fontawesome(){

	$ayo_fontawesome = array(
		""						=> __( '- Select Icon -', 'wti' ),
		"glass"					=> "glass",
		"music"					=> "music",
		"search"				=> "search",
		"envelope"				=> "envelope",
		"heart"					=> "heart",
		"star"					=> "star",
		"star-empty"			=> "star-empty",
		"user"					=> "user",
		"film"					=> "film",
		"th-large"				=> "th-large",
		"th"					=> "th",
		"th-list"				=> "th-list",
		"ok"					=> "ok",
		"remove"				=> "remove",
		"zoom-in"				=> "zoom-in",
		"zoom-out"				=> "zoom-out",
		"off"					=> "off",
		"signal"				=> "signal",
		"cog"					=> "cog",
		"trash"					=> "trash",
		"home"					=> "home",
		"file"					=> "file",
		"time"					=> "time",
		"road"					=> "road",
		"download-alt"			=> "download-alt",
		"download"				=> "download",
		"upload"				=> "upload",
		"inbox"					=> "inbox",
		"play-circle"			=> "play-circle",
		"repeat"				=> "repeat",
		"refresh"				=> "refresh",
		"list-alt"				=> "list-alt",
		"lock"					=> "lock",
		"flag"					=> "flag",
		"headphones"			=> "headphones",
		"volume-off"			=> "volume-off",
		"volume-down"			=> "volume-down",
		"volume-up"				=> "volume-up",
		"qrcode"				=> "qrcode",
		"barcode"				=> "barcode",
		"tag"					=> "tag",
		"tags"					=> "tags",
		"book"					=> "book",
		"bookmark"				=> "bookmark",
		"print"					=> "print",
		"camera"				=> "camera",
		"font"					=> "font",
		"bold"					=> "bold",
		"italic"				=> "italic",
		"text-height"			=> "text-height",
		"text-width"			=> "text-width",
		"align-left"			=> "align-left",
		"align-center"			=> "align-center",
		"align-right"			=> "align-right",
		"align-justify"			=> "align-justify",
		"list"					=> "list",
		"indent-left"			=> "indent-left",
		"indent-right"			=> "indent-right",
		"facetime-video"		=> "facetime-video",
		"picture"				=> "picture",
		"pencil"				=> "pencil",
		"map-marker"			=> "map-marker",
		"adjust"				=> "adjust",
		"tint"					=> "tint",
		"edit"					=> "edit",
		"share"					=> "share",
		"check"					=> "check",
		"move"					=> "move",
		"step-backward"			=> "step-backward",
		"fast-backward"			=> "fast-backward",
		"backward"				=> "backward",
		"play"					=> "play",
		"pause"					=> "pause",
		"stop"					=> "stop",
		"forward"				=> "forward",
		"fast-forward"			=> "fast-forward",
		"step-forward"			=> "step-forward",
		"eject"					=> "eject",
		"chevron-left"			=> "chevron-left",
		"chevron-right"			=> "chevron-right",
		"plus-sign"				=> "plus-sign",
		"minus-sign"			=> "minus-sign",
		"remove-sign"			=> "remove-sign",
		"ok-sign"				=> "ok-sign",
		"question-sign"			=> "question-sign",
		"info-sign"				=> "info-sign",
		"screenshot"			=> "screenshot",
		"remove-circle"			=> "remove-circle",
		"ok-circle"				=> "ok-circle",
		"ban-circle"			=> "ban-circle",
		"arrow-left"			=> "arrow-left",
		"arrow-right"			=> "arrow-right",
		"arrow-up"				=> "arrow-up",
		"arrow-down"			=> "arrow-down",
		"share-alt"				=> "share-alt",
		"resize-full"			=> "resize-full",
		"resize-small"			=> "resize-small",
		"plus"					=> "plus",
		"minus"					=> "minus",
		"asterisk"				=> "asterisk",
		"exclamation-sign"		=> "exclamation-sign",
		"gift"					=> "gift",
		"leaf"					=> "leaf",
		"fire"					=> "fire",
		"eye-open"				=> "eye-open",
		"eye-close"				=> "eye-close",
		"warning-sign"			=> "warning-sign",
		"plane"					=> "plane",
		"calendar"				=> "calendar",
		"random"				=> "random",
		"comment"				=> "comment",
		"magnet"				=> "magnet",
		"chevron-up"			=> "chevron-up",
		"chevron-down"			=> "chevron-down",
		"retweet"				=> "retweet",
		"shopping-cart"			=> "shopping-cart",
		"folder-close"			=> "folder-close",
		"folder-open"			=> "folder-open",
		"resize-vertical"		=> "resize-vertical",
		"resize-horizontal"		=> "resize-horizontal",
		"bar-chart"				=> "bar-chart",
		"twitter-sign"			=> "twitter-sign",
		"facebook-sign"			=> "facebook-sign",
		"camera-retro"			=> "camera-retro",
		"key"					=> "key",
		"cogs"					=> "cogs",
		"comments"				=> "comments",
		"thumbs-up"				=> "thumbs-up",
		"thumbs-down"			=> "thumbs-down",
		"star-half"				=> "star-half",
		"heart-empty"			=> "heart-empty",
		"signout"				=> "signout",
		"linkedin-sign"			=> "linkedin-sign",
		"pushpin"				=> "pushpin",
		"external-link"			=> "external-link",
		"signin"				=> "signin",
		"trophy"				=> "trophy",
		"github-sign"			=> "github-sign",
		"upload-alt"			=> "upload-alt",
		"lemon"					=> "lemon",
		"phone"					=> "phone",
		"check-empty"			=> "check-empty",
		"bookmark-empty"		=> "bookmark-empty",
		"phone-sign"			=> "phone-sign",
		"twitter"				=> "twitter",
		"facebook"				=> "facebook",
		"github"				=> "github",
		"unlock"				=> "unlock",
		"credit-card"			=> "credit-card",
		"rss"					=> "rss",
		"hdd"					=> "hdd",
		"bullhorn"				=> "bullhorn",
		"bell"					=> "bell",
		"certificate"			=> "certificate",
		"hand-right"			=> "hand-right",
		"hand-left"				=> "hand-left",
		"hand-up"				=> "hand-up",
		"hand-down"				=> "hand-down",
		"circle-arrow-left"		=> "circle-arrow-left",
		"circle-arrow-right"	=> "circle-arrow-right",
		"circle-arrow-up"		=> "circle-arrow-up",
		"circle-arrow-down"		=> "circle-arrow-down",
		"globe"					=> "globe",
		"wrench"				=> "wrench",
		"tasks"					=> "tasks",
		"filter"				=> "filter",
		"briefcase"				=> "briefcase",
		"fullscreen"			=> "fullscreen",
		"group"					=> "group",
		"link"					=> "link",
		"cloud"					=> "cloud",
		"beaker"				=> "beaker",
		"cut"					=> "cut",
		"copy"					=> "copy",
		"paper-clip"			=> "paper-clip",
		"save"					=> "save",
		"sign-blank"			=> "sign-blank",
		"reorder"				=> "reorder",
		"list-ul"				=> "list-ul",
		"list-ol"				=> "list-ol",
		"strikethrough"			=> "strikethrough",
		"underline"				=> "underline",
		"table"					=> "table",
		"magic"					=> "magic",
		"truck"					=> "truck",
		"pinterest"				=> "pinterest",
		"pinterest-sign"		=> "pinterest-sign",
		"google-plus-sign"		=> "google-plus-sign",
		"google-plus"			=> "google-plus",
		"money"					=> "money",
		"caret-down"			=> "caret-down",
		"caret-up"				=> "caret-up",
		"caret-left"			=> "caret-left",
		"caret-right"			=> "caret-right",
		"columns"				=> "columns",
		"sort"					=> "sort",
		"sort-down"				=> "sort-down",
		"sort-up"				=> "sort-up",
		"envelope-alt"			=> "envelope-alt",
		"linkedin"				=> "linkedin",
		"undo"					=> "undo",
		"legal"					=> "legal",
		"dashboard"				=> "dashboard",
		"comment-alt"			=> "comment-alt",
		"comments-alt"			=> "comments-alt",
		"bolt"					=> "bolt",
		"sitemap"				=> "sitemap",
		"umbrella"				=> "umbrella",
		"paste"					=> "paste",
		"lightbulb"				=> "lightbulb",
		"exchange"				=> "exchange",
		"cloud-download"		=> "cloud-download",
		"cloud-upload"			=> "cloud-upload",
		"user-md"				=> "user-md",
		"stethoscope"			=> "stethoscope",
		"suitcase"				=> "suitcase",
		"bell-alt"				=> "bell-alt",
		"coffee"				=> "coffee",
		"food"					=> "food",
		"file-alt"				=> "file-alt",
		"building"				=> "building",
		"hospital"				=> "hospital",
		"ambulance"				=> "ambulance",
		"medkit"				=> "medkit",
		"fighter-jet"			=> "fighter-jet",
		"beer"					=> "beer",
		"h-sign"				=> "h-sign",
		"plus-sign-alt"			=> "plus-sign-alt",
		"double-angle-left"		=> "double-angle-left",
		"double-angle-right"	=> "double-angle-right",
		"double-angle-up"		=> "double-angle-up",
		"double-angle-down"		=> "double-angle-down",
		"angle-left"			=> "angle-left",
		"angle-right"			=> "angle-right",
		"angle-up"				=> "angle-up",
		"angle-down"			=> "angle-down",
		"desktop"				=> "desktop",
		"laptop"				=> "laptop",
		"tablet"				=> "tablet",
		"mobile-phone"			=> "mobile-phone",
		"circle-blank"			=> "circle-blank",
		"quote-left"			=> "quote-left",
		"quote-right"			=> "quote-right",
		"spinner"				=> "spinner",
		"circle"				=> "circle",
		"reply"					=> "reply",
		"github-alt"			=> "github-alt",
		"folder-close-alt"		=> "folder-close-alt",
		"folder-open-alt"		=> "folder-open-alt",
	);

	asort( $ayo_fontawesome );
	return apply_filters( 'ayo_fontawesome', $ayo_fontawesome );
	
}
endif; /** end conditional statement for ayo_fontawesome() */