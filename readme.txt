=== Plugin Name ===
Contributors: rvencu
Donate link: 
Tags: multisite, network, site, blog, hierarchy
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 0.1.2

This plugin creates and maintains one or multiple hierarchies of blogs in the WPMS network.

== Description ==

This plugin creates and maintains one or multiple hierarchies of blogs in the WPMS network. Options screens appear in network admin dashboard. The blogs hierarchy can be used for several purposes such as:

1. consolidate posts (or latest posts) from descendant blogs only
1. display a list of descendant blogs or even a tree of blogs from any node

**Other features**

1. Hierarchy cannot loop (cannot set as parent one of the descendants or itself)
1. The main site (blog_id 1) cannot have a parent
1. Individual blogs may be taken out of hierarchy
1. Multiple independent hierarchies may be created

== Installation ==

1. Upload `nsh.zip` to the `/wp-content/plugins/` directory
1. Unzip the archive
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Not yet available =
We haven't received any question yet.

== Other notes ==

Limitations: this plugin is not optimized for large installations. More than 50 blogs can be hard to manage.

== Screenshots ==

1. Hierarchy admin interface

== Changelog ==

= 0.1.2 =
Fixed a bug with getting correct site descendants. Also fixed plugin files encoding.

= 0.1.1 =
Fixed some issues with plugin definition

= 0.1 =
Incipient version
