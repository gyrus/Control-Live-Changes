=== Control Live Changes ===
Contributors: gyrus
Donate link: http://www.babyloniantimes.co.uk/index.php?page=donate
Tags: updates, upgrades, git
Requires at least: 3.3
Tested up to: 3.3.2
Stable tag: 1.0

Prevents certain upgrade and installation actions on non-local environments.

== Description ==
This plugin has arisen from the requirements of a client who manage many of their WordPress sites via Git repositories on Beanstalk. Core WP files are not in the repo, to keep its size down, but plugins and themes are. WP upgrades are of course tested on local development and remote staging servers before being run on the production server. But since plugins and themes are in the repo, ideally they should be upgraded locally for testing, then pushed to Beanstalk. Beanstalk in turn deploys the changes to the staging server, and then the production server.

To help maintain this workflow, this plugin tests if the environment is local or not (searching for "localhost" in `$_SERVER['HTTP_HOST']`). If the environment isn't local, plugin upgrades are disabled.

The following constants can be defined in `wp-config.php` to override the defaults:

* `SLT_CLC_LOCAL_STRING` - The string to search for in `$_SERVER['HTTP_HOST']` that will indicate a local development environment. Default: `"localhost"`
* `SLT_CLC_DISABLE_REMOTE_CORE_UPGRADES` - Default: `false`
* `SLT_CLC_DISABLE_REMOTE_PLUGIN_THEME_UPGRADES` - This will also disable editing plugin and theme files via the admin interface. Default: `true`
* `SLT_CLC_OUTPUT_NOTICES` - Whether to output explanatory notices on the upgrades, themes, and plugins admin pages. Default: `true`

Development code hosted at [GitHub](https://github.com/gyrus/Control-Live-Changes).

== Installation ==
1. Upload the `control-live-changes` directory into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0 =
* First version