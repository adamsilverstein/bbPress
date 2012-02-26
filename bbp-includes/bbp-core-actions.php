<?php

/**
 * bbPress Actions
 *
 * @package bbPress
 * @subpackage Core
 *
 * This file contains the actions that are used through-out bbPress. They are
 * consolidated here to make searching for them easier, and to help developers
 * understand at a glance the order in which things occur.
 *
 * There are a few common places that additional actions can currently be found
 *
 *  - bbPress: In {@link bbPress::setup_actions()} in bbpress.php
 *  - Component: In {@link BBP_Component::setup_actions()} in
 *                bbp-includes/bbp-classes.php
 *  - Admin: More in {@link BBP_Admin::setup_actions()} in
 *            bbp-admin/bbp-admin.php
 * 
 * @see bbp-core-filters.php
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** ACTIONS *******************************************************************/

/**
 * Attach bbPress to WordPress
 *
 * bbPress uses its own internal actions to help aid in third-party plugin
 * development, and to limit the amount of potential future code changes when
 * updates to WordPress core occur.
 *
 * These actions exist to create the concept of 'plugin dependencies'. They
 * provide a safe way for plugins to execute code *only* when bbPress is
 * installed and activated, without needing to do complicated guesswork.
 *
 * For more information on how this works, see the 'Plugin Dependency' section
 * near the bottom of this file.
 *
 *           v--WordPress Actions       v--bbPress Sub-actions
 */
add_action( 'plugins_loaded',         'bbp_loaded',                 10 );
add_action( 'init',                   'bbp_init',                   10 );
add_action( 'widgets_init',           'bbp_widgets_init',           10 );
add_action( 'generate_rewrite_rules', 'bbp_generate_rewrite_rules', 10 );
add_action( 'wp_enqueue_scripts',     'bbp_enqueue_scripts',        10 );
add_action( 'set_current_user',       'bbp_setup_current_user',     10 );
add_action( 'setup_theme',            'bbp_setup_theme',            10 );
add_action( 'after_setup_theme',      'bbp_after_setup_theme',      10 );
add_action( 'template_redirect',      'bbp_template_redirect',      10 );

/**
 * bbp_loaded - Attached to 'plugins_loaded' above
 *
 * Attach various loader actions to the bbp_loaded action.
 * The load order helps to execute code at the correct time.
 *                                                        v---Load order
 */
add_action( 'bbp_loaded', 'bbp_constants',                2  );
add_action( 'bbp_loaded', 'bbp_boot_strap_globals',       4  );
add_action( 'bbp_loaded', 'bbp_includes',                 6  );
add_action( 'bbp_loaded', 'bbp_setup_globals',            8  );
add_action( 'bbp_loaded', 'bbp_register_theme_directory', 10 );

/**
 * bbp_init - Attached to 'init' above
 *
 * Attach various initialization actions to the init action.
 * The load order helps to execute code at the correct time.
 *                                                    v---Load order
 */
add_action( 'bbp_init', 'bbp_load_textdomain',         2   );
add_action( 'bbp_init', 'bbp_setup_option_filters',    4   );
add_action( 'bbp_init', 'bbp_register_post_types',     10  );
add_action( 'bbp_init', 'bbp_register_post_statuses',  12  );
add_action( 'bbp_init', 'bbp_register_taxonomies',     14  );
add_action( 'bbp_init', 'bbp_register_views',          16  );
add_action( 'bbp_init', 'bbp_register_shortcodes',     18  );
add_action( 'bbp_init', 'bbp_add_rewrite_tags',        20  );
add_action( 'bbp_init', 'bbp_ready',                   999 );

// Autoembeds
add_action( 'bbp_init', 'bbp_reply_content_autoembed', 8   );
add_action( 'bbp_init', 'bbp_topic_content_autoembed', 8   );

/**
 * bbp_ready - attached to end 'bbp_init' above
 *
 * Attach actions to the ready action after bbPress has fully initialized.
 * The load order helps to execute code at the correct time.
 *                                                v---Load order
 */
add_action( 'bbp_ready',  'bbp_setup_akismet',    2  ); // Spam prevention for topics and replies
add_action( 'bp_include', 'bbp_setup_buddypress', 10 ); // Social network integration

/**
 * bbp_after_setup_theme  - attached to 'after_setup_theme' above
 *
 * Attach theme related actions to take place after the theme's functions.php
 * file has been included.
 *                                                               v---Load order
 */
add_action( 'bbp_after_setup_theme', 'bbp_setup_theme_compat',   8  );
add_action( 'bbp_after_setup_theme', 'bbp_load_theme_functions', 10 );

// Multisite Global Forum Access
add_action( 'bbp_setup_current_user', 'bbp_global_access_role_mask', 10 );

// Widgets
add_action( 'bbp_widgets_init', array( 'BBP_Login_Widget',   'register_widget' ), 10 );
add_action( 'bbp_widgets_init', array( 'BBP_Views_Widget',   'register_widget' ), 10 );
add_action( 'bbp_widgets_init', array( 'BBP_Forums_Widget',  'register_widget' ), 10 );
add_action( 'bbp_widgets_init', array( 'BBP_Topics_Widget',  'register_widget' ), 10 );
add_action( 'bbp_widgets_init', array( 'BBP_Replies_Widget', 'register_widget' ), 10 );

// Template - Head, foot, errors and messages
add_action( 'wp_head',              'bbp_head'             );
add_action( 'wp_footer',            'bbp_footer'           );
add_action( 'bbp_loaded',           'bbp_login_notices'    );
add_action( 'bbp_head',             'bbp_topic_notices'    );
add_action( 'bbp_template_notices', 'bbp_template_notices' );

// Caps & Roles
add_action( 'bbp_activation',   'bbp_add_roles',    1 );
add_action( 'bbp_activation',   'bbp_add_caps',     2 );
add_action( 'bbp_deactivation', 'bbp_remove_caps',  1 );
add_action( 'bbp_deactivation', 'bbp_remove_roles', 2 );

// Options & Settings
add_action( 'bbp_activation', 'bbp_add_options', 1 );

// Multisite
add_action( 'bbp_new_site', 'bbp_add_roles',   2 );
add_action( 'bbp_new_site', 'bbp_add_caps',    4 );
add_action( 'bbp_new_site', 'bbp_add_options', 6 );

// Parse the main query
add_action( 'parse_query', 'bbp_parse_query', 2 );

// Always exclude private/hidden forums if needed
add_action( 'pre_get_posts', 'bbp_pre_get_posts_exclude_forums', 4 );

// Profile Page Messages
add_action( 'bbp_template_notices', 'bbp_notice_edit_user_success'           );
add_action( 'bbp_template_notices', 'bbp_notice_edit_user_is_super_admin', 2 );

// Before Delete/Trash/Untrash Topic
add_action( 'wp_trash_post', 'bbp_trash_forum'   );
add_action( 'trash_post',    'bbp_trash_forum'   );
add_action( 'untrash_post',  'bbp_untrash_forum' );
add_action( 'delete_post',   'bbp_delete_forum'  );

// After Deleted/Trashed/Untrashed Topic
add_action( 'trashed_post',   'bbp_trashed_forum'   );
add_action( 'untrashed_post', 'bbp_untrashed_forum' );
add_action( 'deleted_post',   'bbp_deleted_forum'   );

// Auto trash/untrash/delete a forums topics
add_action( 'bbp_delete_forum',  'bbp_delete_forum_topics',  10 );
add_action( 'bbp_trash_forum',   'bbp_trash_forum_topics',   10 );
add_action( 'bbp_untrash_forum', 'bbp_untrash_forum_topics', 10 );

// New/Edit Forum
add_action( 'bbp_new_forum',  'bbp_update_forum', 10 );
add_action( 'bbp_edit_forum', 'bbp_update_forum', 10 );

// Save forum extra metadata
add_action( 'bbp_new_forum_post_extras',         'bbp_save_forum_extras', 2 );
add_action( 'bbp_edit_forum_post_extras',        'bbp_save_forum_extras', 2 );
add_action( 'bbp_forum_attributes_metabox_save', 'bbp_save_forum_extras', 2 );

// New/Edit Reply
add_action( 'bbp_new_reply',  'bbp_update_reply', 10, 6 );
add_action( 'bbp_edit_reply', 'bbp_update_reply', 10, 6 );

// Before Delete/Trash/Untrash Reply
add_action( 'wp_trash_post', 'bbp_trash_reply'   );
add_action( 'trash_post',    'bbp_trash_reply'   );
add_action( 'untrash_post',  'bbp_untrash_reply' );
add_action( 'delete_post',   'bbp_delete_reply'  );

// After Deleted/Trashed/Untrashed Reply
add_action( 'trashed_post',   'bbp_trashed_reply'   );
add_action( 'untrashed_post', 'bbp_untrashed_reply' );
add_action( 'deleted_post',   'bbp_deleted_reply'   );

// New/Edit Topic
add_action( 'bbp_new_topic',  'bbp_update_topic', 10, 5 );
add_action( 'bbp_edit_topic', 'bbp_update_topic', 10, 5 );

// Split/Merge Topic
add_action( 'bbp_merged_topic',     'bbp_merge_topic_count', 1, 3 );
add_action( 'bbp_post_split_topic', 'bbp_split_topic_count', 1, 3 );

// Before Delete/Trash/Untrash Topic
add_action( 'wp_trash_post', 'bbp_trash_topic'   );
add_action( 'trash_post',    'bbp_trash_topic'   );
add_action( 'untrash_post',  'bbp_untrash_topic' );
add_action( 'delete_post',   'bbp_delete_topic'  );

// After Deleted/Trashed/Untrashed Topic
add_action( 'trashed_post',   'bbp_trashed_topic'   );
add_action( 'untrashed_post', 'bbp_untrashed_topic' );
add_action( 'deleted_post',   'bbp_deleted_topic'   );

// Favorites
add_action( 'bbp_trash_topic',  'bbp_remove_topic_from_all_favorites' );
add_action( 'bbp_delete_topic', 'bbp_remove_topic_from_all_favorites' );

// Subscriptions
add_action( 'bbp_trash_topic',  'bbp_remove_topic_from_all_subscriptions'      );
add_action( 'bbp_delete_topic', 'bbp_remove_topic_from_all_subscriptions'      );
add_action( 'bbp_new_reply',    'bbp_notify_subscribers',                 1, 5 );

// Sticky
add_action( 'bbp_trash_topic',  'bbp_unstick_topic' );
add_action( 'bbp_delete_topic', 'bbp_unstick_topic' );

// Update topic branch
add_action( 'bbp_trashed_topic',   'bbp_update_topic_walker' );
add_action( 'bbp_untrashed_topic', 'bbp_update_topic_walker' );
add_action( 'bbp_deleted_topic',   'bbp_update_topic_walker' );
add_action( 'bbp_spammed_topic',   'bbp_update_topic_walker' );
add_action( 'bbp_unspammed_topic', 'bbp_update_topic_walker' );

// Update reply branch
add_action( 'bbp_trashed_reply',   'bbp_update_reply_walker' );
add_action( 'bbp_untrashed_reply', 'bbp_update_reply_walker' );
add_action( 'bbp_deleted_reply',   'bbp_update_reply_walker' );
add_action( 'bbp_spammed_reply',   'bbp_update_reply_walker' );
add_action( 'bbp_unspammed_reply', 'bbp_update_reply_walker' );

// User status
add_action( 'make_ham_user',  'bbp_make_ham_user'  );
add_action( 'make_spam_user', 'bbp_make_spam_user' );

// User role
add_action( 'bbp_new_topic', 'bbp_global_access_auto_role' );
add_action( 'bbp_new_reply', 'bbp_global_access_auto_role' );

// Flush rewrite rules
add_action( 'bbp_activation',   'flush_rewrite_rules' );
add_action( 'bbp_deactivation', 'flush_rewrite_rules' );

/**
 * bbPress needs to redirect the user around in a few different circumstances:
 *
 * 1. Form submission within a theme (new and edit)
 * 2. Accessing private or hidden forums
 * 3. Editing forums, topics, replies, users, and tags
 */
add_action( 'bbp_template_redirect', 'bbp_forum_enforce_hidden',    -1 );
add_action( 'bbp_template_redirect', 'bbp_forum_enforce_private',   -1 );
add_action( 'bbp_template_redirect', 'bbp_new_forum_handler',       10 );
add_action( 'bbp_template_redirect', 'bbp_new_reply_handler',       10 );
add_action( 'bbp_template_redirect', 'bbp_new_topic_handler',       10 );
add_action( 'bbp_template_redirect', 'bbp_edit_topic_tag_handler',  1  );
add_action( 'bbp_template_redirect', 'bbp_edit_user_handler',       1  );
add_action( 'bbp_template_redirect', 'bbp_edit_forum_handler',      1  );
add_action( 'bbp_template_redirect', 'bbp_edit_reply_handler',      1  );
add_action( 'bbp_template_redirect', 'bbp_edit_topic_handler',      1  );
add_action( 'bbp_template_redirect', 'bbp_merge_topic_handler',     1  );
add_action( 'bbp_template_redirect', 'bbp_split_topic_handler',     1  );
add_action( 'bbp_template_redirect', 'bbp_toggle_topic_handler',    1  );
add_action( 'bbp_template_redirect', 'bbp_toggle_reply_handler',    1  );
add_action( 'bbp_template_redirect', 'bbp_favorites_handler',       1  );
add_action( 'bbp_template_redirect', 'bbp_subscriptions_handler',   1  );
add_action( 'bbp_template_redirect', 'bbp_check_user_edit',         10 );
add_action( 'bbp_template_redirect', 'bbp_check_forum_edit',        10 );
add_action( 'bbp_template_redirect', 'bbp_check_topic_edit',        10 );
add_action( 'bbp_template_redirect', 'bbp_check_reply_edit',        10 );
add_action( 'bbp_template_redirect', 'bbp_check_topic_tag_edit',    10 );

/**
 * Requires and creates the BuddyPress extension, and adds component creation
 * action to bp_init hook. @see bbp_setup_buddypress_component()
 *
 * @since bbPress (r3395)
 *
 * @return If BuddyPress is not active
 */
function bbp_setup_buddypress() {
	global $bp;

	// Bail if no BuddyPress
	if ( !empty( $bp->maintenance_mode ) || !defined( 'BP_VERSION' ) ) return;

	// Include the BuddyPress Component
	require( bbpress()->plugin_dir . 'bbp-includes/bbp-extend-buddypress.php' );

	// Instantiate BuddyPress for bbPress
	bbpress()->extend->buddypress = new BBP_BuddyPress();

	// Add component setup to bp_init action
	add_action( 'bp_init', 'bbp_setup_buddypress_component' );
}

/**
 * When a new site is created in a multisite installation, run the activation
 * routine on that site
 *
 * @since bbPress (r3283)
 *
 * @param int $blog_id
 * @param int $user_id
 * @param string $domain
 * @param string $path
 * @param int $site_id
 * @param array() $meta
 */
function bbp_new_site( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

	// Switch to the new blog
	switch_to_blog( $blog_id );

	// Do the bbPress activation routine
	do_action( 'bbp_new_site' );

	// restore original blog
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'bbp_new_site', 10, 6 );

/** Admin *********************************************************************/

if ( is_admin() ) {

	add_action( 'bbp_init',          'bbp_admin'                   );
	add_action( 'bbp_admin_init',    'bbp_admin_forums',         9 );
	add_action( 'bbp_admin_init',    'bbp_admin_topics',         9 );
	add_action( 'bbp_admin_init',    'bbp_admin_replies',        9 );
	add_action( 'admin_menu',        'bbp_admin_separator'         );
	add_action( 'custom_menu_order', 'bbp_admin_custom_menu_order' );
	add_action( 'menu_order',        'bbp_admin_menu_order'        );

	// Contextual Helpers
	add_action( 'load-settings_page_bbpress', 'bbp_admin_settings_help' );

	/**
	 * Run the updater late on 'bbp_admin_init' to ensure that all alterations
	 * to the permalink structure have taken place. This fixes the issue of
	 * permalinks not being flushed properly when a bbPress update occurs.
	 */
	add_action( 'bbp_admin_init',    'bbp_setup_updater', 999 );
}

/**
 * Plugin Dependency
 *
 * The purpose of the following actions is to mimic the behavior of something
 * called 'plugin dependency' which enables a plugin to have plugins of their
 * own in a safe and reliable way.
 *
 * We do this in bbPress by mirroring existing WordPress actions in many places
 * allowing dependant plugins to hook into the bbPress specific ones, thus
 * guaranteeing proper code execution only when bbPress is active.
 *
 * The following functions are wrappers for their actions, allowing them to be
 * manually called and/or piggy-backed on top of other actions if needed.
 */

/** Activation Actions ********************************************************/

/**
 * Runs on bbPress activation
 *
 * @since bbPress (r2509)
 *
 * @uses register_uninstall_hook() To register our own uninstall hook
 * @uses do_action() Calls 'bbp_activation' hook
 */
function bbp_activation() {
	do_action( 'bbp_activation' );
}

/**
 * Runs on bbPress deactivation
 *
 * @since bbPress (r2509)
 *
 * @uses do_action() Calls 'bbp_deactivation' hook
 */
function bbp_deactivation() {
	do_action( 'bbp_deactivation' );
}

/**
 * Runs when uninstalling bbPress
 *
 * @since bbPress (r2509)
 *
 * @uses do_action() Calls 'bbp_uninstall' hook
 */
function bbp_uninstall() {
	do_action( 'bbp_uninstall' );
}

/** Main Actions **************************************************************/

/**
 * Main action responsible for constants, globals, and includes
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_loaded'
 */
function bbp_loaded() {
	do_action( 'bbp_loaded' );
}

/**
 * Setup constants
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_constants'
 */
function bbp_constants() {
	do_action( 'bbp_constants' );
}

/**
 * Setup globals BEFORE includes
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_boot_strap_globals'
 */
function bbp_boot_strap_globals() {
	do_action( 'bbp_boot_strap_globals' );
}

/**
 * Include files
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_includes'
 */
function bbp_includes() {
	do_action( 'bbp_includes' );
}

/**
 * Setup globals AFTER includes
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_setup_globals'
 */
function bbp_setup_globals() {
	do_action( 'bbp_setup_globals' );
}

/**
 * Initialize any code after everything has been loaded
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_init'
 */
function bbp_init() {
	do_action ( 'bbp_init' );
}

/**
 * Initialize widgets
 *
 * @since bbPress (r3389)
 *
 * @uses do_action() Calls 'bbp_widgets_init'
 */
function bbp_widgets_init() {
	do_action ( 'bbp_widgets_init' );
}

/**
 * Setup the currently logged-in user
 *
 * @since bbPress (r2695)
 *
 * @uses do_action() Calls 'bbp_setup_current_user'
 */
function bbp_setup_current_user() {
	do_action ( 'bbp_setup_current_user' );
}

/** Supplemental Actions ******************************************************/

/**
 * Load translations for current language
 *
 * @since bbPress (r2599)
 *
 * @uses do_action() Calls 'bbp_load_textdomain'
 */
function bbp_load_textdomain() {
	do_action( 'bbp_load_textdomain' );
}

/**
 * Sets up the theme directory
 *
 * @since bbPress (r2507)
 *
 * @uses do_action() Calls 'bbp_register_theme_directory'
 */
function bbp_register_theme_directory() {
	do_action( 'bbp_register_theme_directory' );
}

/**
 * Setup the post types
 *
 * @since bbPress (r2464)
 *
 * @uses do_action() Calls 'bbp_register_post_type'
 */
function bbp_register_post_types() {
	do_action ( 'bbp_register_post_types' );
}

/**
 * Setup the post statuses
 *
 * @since bbPress (r2727)
 *
 * @uses do_action() Calls 'bbp_register_post_statuses'
 */
function bbp_register_post_statuses() {
	do_action ( 'bbp_register_post_statuses' );
}

/**
 * Register the built in bbPress taxonomies
 *
 * @since bbPress (r2464)
 *
 * @uses do_action() Calls 'bbp_register_taxonomies'
 */
function bbp_register_taxonomies() {
	do_action ( 'bbp_register_taxonomies' );
}

/**
 * Register the default bbPress views
 *
 * @since bbPress (r2789)
 *
 * @uses do_action() Calls 'bbp_register_views'
 */
function bbp_register_views() {
	do_action ( 'bbp_register_views' );
}

/**
 * Enqueue bbPress specific CSS and JS
 *
 * @since bbPress (r3373)
 *
 * @uses do_action() Calls 'bbp_enqueue_scripts'
 */
function bbp_enqueue_scripts() {
	do_action ( 'bbp_enqueue_scripts' );
}

/**
 * Add the bbPress-specific rewrite tags
 *
 * @since bbPress (r2753)
 *
 * @uses do_action() Calls 'bbp_add_rewrite_tags'
 */
function bbp_add_rewrite_tags() {
	do_action ( 'bbp_add_rewrite_tags' );
}

/** Final Action **************************************************************/

/**
 * bbPress has loaded and initialized everything, and is okay to go
 *
 * @since bbPress (r2618)
 *
 * @uses do_action() Calls 'bbp_ready'
 */
function bbp_ready() {
	do_action( 'bbp_ready' );
}

/** Theme Permissions *********************************************************/

/**
 * The main action used for redirecting bbPress theme actions that are not
 * permitted by the current_user
 *
 * @since bbPress (r3605)
 *
 * @uses do_action()
 */
function bbp_template_redirect() {
	do_action( 'bbp_template_redirect' );
}

/** Theme Helpers *************************************************************/

/**
 * The main action used for executing code before the theme has been setup
 *
 * @since bbPress (r3732)
 *
 * @uses do_action()
 */
function bbp_setup_theme() {
	do_action( 'bbp_setup_theme' );
}

/**
 * The main action used for executing code after the theme has been setup
 *
 * @since bbPress (r3732)
 *
 * @uses do_action()
 */
function bbp_after_setup_theme() {
	do_action( 'bbp_after_setup_theme' );
}

?>