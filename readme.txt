=== Widget Text Icon ===
Contributors: tareiking, sennza
Tags: widget, icons
Requires at least: 3.2
Tested up to: 3.9.1
Stable tag: 0.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

A text widget with icon's from [FontAwesome](http://fortawesome.github.io/Font-Awesome/). Originally forked from https://github.com/wp-plugins/widget-text-icon by Arya Prakasa.
Likely to be used by SiteOrigin's PageBuilder.

== Installation ==

== Usage ==
The icons and text are designed to be styled via external css (IE your own stylesheets). The css cascade looks like this (please note this is likely to change)

``.easy-fa-icon-text-widget{
	.icon-heading {

	}

	.icon {

	}
	.icon-text {

	}
}``

== Roadmap ==

* May re-introduce widget level icon sizing.
* May introduce different presentation styles (icon-top, icon-left, icon-right, icon-below)
* May add icon-preview to the select box (in widget form)
* May add default css/scss file for extending


== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==
= 0.1 =
* Update FontAwesome to 4.0.3
* Removed IE7 support (http://modern.ie)
* Removed usage of "size" for icon, as this can be styled via css

== Upgrade Notice ==

None