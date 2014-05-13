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

		var_dump( $instance );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );

		$size = ( ! empty( $instance['size'] ) ) ? 'font-size: '. $instance['size'] .'px;' : '' ;
		$icon = ( ! empty( $instance['icon'] ) ) ? '<i class="fa fa-'. $instance['icon'] .'" style="line-height:1em;margin-right:10px;'. $size .'" ></i>' : '';

		echo $before_widget;

		if ( ! empty( $title ) ) {
			echo $before_title . $icon . $title . $after_title;
		} else {
			echo $icon;
		}

		?>



		<div class="textwidget-icon"><?php echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>

		<?php  echo $after_widget;

	}

	/** Load font-awesome.css if widget active */
	function css() {

		/** Register Fontawesome v.3.0.2 */
		if ( ! wp_style_is( "fontawesome", "registered" ) ) {
			wp_register_style( "fontawesome", "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css", array(), "3.0.2", "all" );
		}

		if ( ! is_admin() && is_active_widget( false, false, $this->id_base, true ) ) {
			wp_enqueue_style( 'fontawesome' );
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
 * @link 		https://github.com/FortAwesome/Font-Awesome
 * @license  	https://github.com/FortAwesome/Font-Awesome#license
 * @version 	4.0.3
 */
function ayo_fontawesome(){

		$ayo_fontawesome = array(
		"glass"                => "glass",
		"music"                => "music",
		"search"               => "search",
		"envelope-o"           => "envelope-o",
		"heart"                => "heart",
		"star"                 => "star",
		"star-o"               => "star-o",
		"user"                 => "user",
		"film"                 => "film",
		"th-large"             => "th-large",
		"th"                   => "th",
		"th-list"              => "th-list",
		"check"                => "check",
		"times"                => "times",
		"search-plus"          => "search-plus",
		"search-minus"         => "search-minus",
		"power-off"            => "power-off",
		"signal"               => "signal",
		"cog"                  => "cog",
		"trash-o"              => "trash-o",
		"home"                 => "home",
		"file-o"               => "file-o",
		"clock-o"              => "clock-o",
		"road"                 => "road",
		"download"             => "download",
		"arrow-circle-o-down"  => "arrow-circle-o-down",
		"arrow-circle-o-up"    => "arrow-circle-o-up",
		"inbox"                => "inbox",
		"play-circle-o"        => "play-circle-o",
		"repeat"               => "repeat",
		"refresh"              => "refresh",
		"list-alt"             => "list-alt",
		"lock"                 => "lock",
		"flag"                 => "flag",
		"headphones"           => "headphones",
		"volume-off"           => "volume-off",
		"volume-down"          => "volume-down",
		"volume-up"            => "volume-up",
		"qrcode"               => "qrcode",
		"barcode"              => "barcode",
		"tag"                  => "tag",
		"tags"                 => "tags",
		"book"                 => "book",
		"bookmark"             => "bookmark",
		"print"                => "print",
		"camera"               => "camera",
		"font"                 => "font",
		"bold"                 => "bold",
		"italic"               => "italic",
		"text-height"          => "text-height",
		"text-width"           => "text-width",
		"align-left"           => "align-left",
		"align-center"         => "align-center",
		"align-right"          => "align-right",
		"align-justify"        => "align-justify",
		"list"                 => "list",
		"outdent"              => "outdent",
		"indent"               => "indent",
		"video-camera"         => "video-camera",
		"picture-o"            => "picture-o",
		"pencil"               => "pencil",
		"map-marker"           => "map-marker",
		"adjust"               => "adjust",
		"tint"                 => "tint",
		"pencil-square-o"      => "pencil-square-o",
		"share-square-o"       => "share-square-o",
		"check-square-o"       => "check-square-o",
		"arrows"               => "arrows",
		"step-backward"        => "step-backward",
		"fast-backward"        => "fast-backward",
		"backward"             => "backward",
		"play"                 => "play",
		"pause"                => "pause",
		"stop"                 => "stop",
		"forward"              => "forward",
		"fast-forward"         => "fast-forward",
		"step-forward"         => "step-forward",
		"eject"                => "eject",
		"chevron-left"         => "chevron-left",
		"chevron-right"        => "chevron-right",
		"plus-circle"          => "plus-circle",
		"minus-circle"         => "minus-circle",
		"times-circle"         => "times-circle",
		"check-circle"         => "check-circle",
		"question-circle"      => "question-circle",
		"info-circle"          => "info-circle",
		"crosshairs"           => "crosshairs",
		"times-circle-o"       => "times-circle-o",
		"check-circle-o"       => "check-circle-o",
		"ban"                  => "ban",
		"arrow-left"           => "arrow-left",
		"arrow-right"          => "arrow-right",
		"arrow-up"             => "arrow-up",
		"arrow-down"           => "arrow-down",
		"share"                => "share",
		"expand"               => "expand",
		"compress"             => "compress",
		"plus"                 => "plus",
		"minus"                => "minus",
		"asterisk"             => "asterisk",
		"exclamation-circle"   => "exclamation-circle",
		"gift"                 => "gift",
		"leaf"                 => "leaf",
		"fire"                 => "fire",
		"eye"                  => "eye",
		"eye-slash"            => "eye-slash",
		"exclamation-triangle" => "exclamation-triangle",
		"plane"                => "plane",
		"calendar"             => "calendar",
		"random"               => "random",
		"comment"              => "comment",
		"magnet"               => "magnet",
		"chevron-up"           => "chevron-up",
		"chevron-down"         => "chevron-down",
		"retweet"              => "retweet",
		"shopping-cart"        => "shopping-cart",
		"folder"               => "folder",
		"folder-open"          => "folder-open",
		"arrows-v"             => "arrows-v",
		"arrows-h"             => "arrows-h",
		"bar-chart-o"          => "bar-chart-o",
		"twitter-square"       => "twitter-square",
		"facebook-square"      => "facebook-square",
		"camera-retro"         => "camera-retro",
		"key"                  => "key",
		"cogs"                 => "cogs",
		"comments"             => "comments",
		"thumbs-o-up"          => "thumbs-o-up",
		"thumbs-o-down"        => "thumbs-o-down",
		"star-half"            => "star-half",
		"heart-o"              => "heart-o",
		"sign-out"             => "sign-out",
		"linkedin-square"      => "linkedin-square",
		"thumb-tack"           => "thumb-tack",
		"external-link"        => "external-link",
		"sign-in"              => "sign-in",
		"trophy"               => "trophy",
		"github-square"        => "github-square",
		"upload"               => "upload",
		"lemon-o"              => "lemon-o",
		"phone"                => "phone",
		"square-o"             => "square-o",
		"bookmark-o"           => "bookmark-o",
		"phone-square"         => "phone-square",
		"twitter"              => "twitter",
		"facebook"             => "facebook",
		"github"               => "github",
		"unlock"               => "unlock",
		"credit-card"          => "credit-card",
		"rss"                  => "rss",
		"hdd-o"                => "hdd-o",
		"bullhorn"             => "bullhorn",
		"bell"                 => "bell",
		"certificate"          => "certificate",
		"hand-o-right"         => "hand-o-right",
		"hand-o-left"          => "hand-o-left",
		"hand-o-up"            => "hand-o-up",
		"hand-o-down"          => "hand-o-down",
		"arrow-circle-left"    => "arrow-circle-left",
		"arrow-circle-right"   => "arrow-circle-right",
		"arrow-circle-up"      => "arrow-circle-up",
		"arrow-circle-down"    => "arrow-circle-down",
		"globe"                => "globe",
		"wrench"               => "wrench",
		"tasks"                => "tasks",
		"filter"               => "filter",
		"briefcase"            => "briefcase",
		"arrows-alt"           => "arrows-alt",
		"users"                => "users",
		"link"                 => "link",
		"cloud"                => "cloud",
		"flask"                => "flask",
		"scissors"             => "scissors",
		"files-o"              => "files-o",
		"paperclip"            => "paperclip",
		"floppy-o"             => "floppy-o",
		"square"               => "square",
		"bars"                 => "bars",
		"list-ul"              => "list-ul",
		"list-ol"              => "list-ol",
		"strikethrough"        => "strikethrough",
		"underline"            => "underline",
		"table"                => "table",
		"magic"                => "magic",
		"truck"                => "truck",
		"pinterest"            => "pinterest",
		"pinterest-square"     => "pinterest-square",
		"google-plus-square"   => "google-plus-square",
		"google-plus"          => "google-plus",
		"money"                => "money",
		"caret-down"           => "caret-down",
		"caret-up"             => "caret-up",
		"caret-left"           => "caret-left",
		"caret-right"          => "caret-right",
		"columns"              => "columns",
		"sort"                 => "sort",
		"sort-asc"             => "sort-asc",
		"sort-desc"            => "sort-desc",
		"envelope"             => "envelope",
		"linkedin"             => "linkedin",
		"undo"                 => "undo",
		"gavel"                => "gavel",
		"tachometer"           => "tachometer",
		"comment-o"            => "comment-o",
		"comments-o"           => "comments-o",
		"bolt"                 => "bolt",
		"sitemap"              => "sitemap",
		"umbrella"             => "umbrella",
		"clipboard"            => "clipboard",
		"lightbulb-o"          => "lightbulb-o",
		"exchange"             => "exchange",
		"cloud-download"       => "cloud-download",
		"cloud-upload"         => "cloud-upload",
		"user-md"              => "user-md",
		"stethoscope"          => "stethoscope",
		"suitcase"             => "suitcase",
		"bell-o"               => "bell-o",
		"coffee"               => "coffee",
		"cutlery"              => "cutlery",
		"file-text-o"          => "file-text-o",
		"building-o"           => "building-o",
		"hospital-o"           => "hospital-o",
		"ambulance"            => "ambulance",
		"medkit"               => "medkit",
		"fighter-jet"          => "fighter-jet",
		"beer"                 => "beer",
		"h-square"             => "h-square",
		"plus-square"          => "plus-square",
		"angle-double-left"    => "angle-double-left",
		"angle-double-right"   => "angle-double-right",
		"angle-double-up"      => "angle-double-up",
		"angle-double-down"    => "angle-double-down",
		"angle-left"           => "angle-left",
		"angle-right"          => "angle-right",
		"angle-up"             => "angle-up",
		"angle-down"           => "angle-down",
		"desktop"              => "desktop",
		"laptop"               => "laptop",
		"tablet"               => "tablet",
		"mobile"               => "mobile",
		"circle-o"             => "circle-o",
		"quote-left"           => "quote-left",
		"quote-right"          => "quote-right",
		"spinner"              => "spinner",
		"circle"               => "circle",
		"reply"                => "reply",
		"github-alt"           => "github-alt",
		"folder-o"             => "folder-o",
		"folder-open-o"        => "folder-open-o",
		"smile-o"              => "smile-o",
		"frown-o"              => "frown-o",
		"meh-o"                => "meh-o",
		"gamepad"              => "gamepad",
		"keyboard-o"           => "keyboard-o",
		"flag-o"               => "flag-o",
		"flag-checkered"       => "flag-checkered",
		"terminal"             => "terminal",
		"code"                 => "code",
		"reply-all"            => "reply-all",
		"mail-reply-all"       => "mail-reply-all",
		"star-half-o"          => "star-half-o",
		"location-arrow"       => "location-arrow",
		"crop"                 => "crop",
		"code-fork"            => "code-fork",
		"chain-broken"         => "chain-broken",
		"question"             => "question",
		"info"                 => "info",
		"exclamation"          => "exclamation",
		"superscript"          => "superscript",
		"subscript"            => "subscript",
		"eraser"               => "eraser",
		"puzzle-piece"         => "puzzle-piece",
		"microphone"           => "microphone",
		"microphone-slash"     => "microphone-slash",
		"shield"               => "shield",
		"calendar-o"           => "calendar-o",
		"fire-extinguisher"    => "fire-extinguisher",
		"rocket"               => "rocket",
		"maxcdn"               => "maxcdn",
		"chevron-circle-left"  => "chevron-circle-left",
		"chevron-circle-right" => "chevron-circle-right",
		"chevron-circle-up"    => "chevron-circle-up",
		"chevron-circle-down"  => "chevron-circle-down",
		"html5"                => "html5",
		"css3"                 => "css3",
		"anchor"               => "anchor",
		"unlock-alt"           => "unlock-alt",
		"bullseye"             => "bullseye",
		"ellipsis-h"           => "ellipsis-h",
		"ellipsis-v"           => "ellipsis-v",
		"rss-square"           => "rss-square",
		"play-circle"          => "play-circle",
		"ticket"               => "ticket",
		"minus-square"         => "minus-square",
		"minus-square-o"       => "minus-square-o",
		"level-up"             => "level-up",
		"level-down"           => "level-down",
		"check-square"         => "check-square",
		"pencil-square"        => "pencil-square",
		"external-link-square" => "external-link-square",
		"share-square"         => "share-square",
		"compass"              => "compass",
		"caret-square-o-down"  => "caret-square-o-down",
		"caret-square-o-up"    => "caret-square-o-up",
		"caret-square-o-right" => "caret-square-o-right",
		"eur"                  => "eur",
		"gbp"                  => "gbp",
		"usd"                  => "usd",
		"inr"                  => "inr",
		"jpy"                  => "jpy",
		"rub"                  => "rub",
		"krw"                  => "krw",
		"btc"                  => "btc",
		"file"                 => "file",
		"file-text"            => "file-text",
		"sort-alpha-asc"       => "sort-alpha-asc",
		"sort-alpha-desc"      => "sort-alpha-desc",
		"sort-amount-asc"      => "sort-amount-asc",
		"sort-amount-desc"     => "sort-amount-desc",
		"sort-numeric-asc"     => "sort-numeric-asc",
		"sort-numeric-desc"    => "sort-numeric-desc",
		"thumbs-up"            => "thumbs-up",
		"thumbs-down"          => "thumbs-down",
		"youtube-square"       => "youtube-square",
		"youtube"              => "youtube",
		"xing"                 => "xing",
		"xing-square"          => "xing-square",
		"youtube-play"         => "youtube-play",
		"dropbox"              => "dropbox",
		"stack-overflow"       => "stack-overflow",
		"instagram"            => "instagram",
		"flickr"               => "flickr",
		"adn"                  => "adn",
		"bitbucket"            => "bitbucket",
		"bitbucket-square"     => "bitbucket-square",
		"tumblr"               => "tumblr",
		"tumblr-square"        => "tumblr-square",
		"long-arrow-down"      => "long-arrow-down",
		"long-arrow-up"        => "long-arrow-up",
		"long-arrow-left"      => "long-arrow-left",
		"long-arrow-right"     => "long-arrow-right",
		"apple"                => "apple",
		"windows"              => "windows",
		"android"              => "android",
		"linux"                => "linux",
		"dribbble"             => "dribbble",
		"skype"                => "skype",
		"foursquare"           => "foursquare",
		"trello"               => "trello",
		"female"               => "female",
		"male"                 => "male",
		"gittip"               => "gittip",
		"sun-o"                => "sun-o",
		"moon-o"               => "moon-o",
		"archive"              => "archive",
		"bug"                  => "bug",
		"vk"                   => "vk",
		"weibo"                => "weibo",
		"renren"               => "renren",
		"pagelines"            => "pagelines",
		"stack-exchange"       => "stack-exchange",
		"arrow-circle-o-right" => "arrow-circle-o-right",
		"arrow-circle-o-left"  => "arrow-circle-o-left",
		"caret-square-o-left"  => "caret-square-o-left",
		"dot-circle-o"         => "dot-circle-o",
		"wheelchair"           => "wheelchair",
		"vimeo-square"         => "vimeo-square",
		"try"                  => "try",
		"plus-square-o"        => "plus-square-o"
);

	asort( $ayo_fontawesome );
	return apply_filters( 'ayo_fontawesome', $ayo_fontawesome );

}
endif; /** end conditional statement for ayo_fontawesome() */