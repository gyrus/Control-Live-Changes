=== Control Live Changes ===
Contributors: gyrus
Donate link: http://www.babyloniantimes.co.uk/index.php?page=donate
Tags: disable, updates, upgrades, git
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 0.2.2

Prevents certain upgrade and installation actions on non-local environments.

== Description ==
This plugin has arisen from the requirements of a client who manage many of their WordPress sites via Git repositories on Beanstalk. Core WP files are not in the repo, to keep its size down, but plugins and themes are. WP upgrades are of course tested on local development and remote staging servers before being run on the production server. But since plugins and themes are in the repo, ideally they should be upgraded locally for testing, then pushed to Beanstalk. Beanstalk in turn deploys the changes to the staging server, and then the production server.

To help maintain this workflow, this plugin tests if the environment is local or not (checking for `WP_LOCAL_DEV` or searching for "localhost" in `$_SERVER['HTTP_HOST']`). If the environment isn't local, plugin and theme installation, editing and upgrades are disabled.

The following constants can be defined in `wp-config.php` to override the defaults:

* `SLT_CLC_LOCAL_STRING` - The string to search for in `$_SERVER['HTTP_HOST']` that will indicate a local development environment. Default: `"localhost"`
* `SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES` - Default: `false`
* `SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES` - This will also disable editing plugin and theme files via the admin interface. Default: `true`
* `SLT_CLC_OUTPUT_NOTICES` - Whether to output explanatory notices on the upgrades, themes, and plugins admin pages. Default: `true`
* `SLT_CLC_CORE_NOTICE` - Default: `"Core upgrades are currently disabled on this server by the Control Live Changes plugin."`
* `SLT_CLC_PLUGIN_THEME_NOTICE` - Default: `"Plugin and theme upgrades are currently disabled on this server by the Control Live Changes plugin."`

An alternative to the `SLT_CLC_LOCAL_STRING` check for a local environment is the `WP_LOCAL_DEV` constant. This is used by Mark Jaquith in his technique for defining local database connection details in a separate file (http://markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips/). If `WP_LOCAL_DEV` is set to true, the `SLT_CLC_LOCAL_STRING` check is made irrelevant.

Development code hosted at [GitHub](https://github.com/gyrus/Control-Live-Changes).

== Installation ==
1. Upload the `control-live-changes` directory into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. If necessary, define constants in `wp-config.php` to override the plugin defaults - see the Description for details.

NOTE: Instead of the above, you may want to drop the `control-live-changes.php` file into the `/wp-content/mu-plugins/` directory to ensure that no one deactivates this plugin!

== Changelog ==
= 0.2.2 =
* Improved screen checking for plugins and theme pages

= 0.2.1 =
* Added tests for constants that might already be defined

= 0.2 =
* Improved disabling functionality by using the `map_meta_cap` filter

= 0.1 =
* First version