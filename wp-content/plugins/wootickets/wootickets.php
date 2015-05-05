<?php
/*
Plugin Name: The Events Calendar: WooCommerce Tickets
Description: The Events Calendar: WooCommerce Tickets allows you to sell tickets to events through WooCommerce
Version: 3.9.3
Author: Modern Tribe, Inc.
Author URI: http://m.tri.be/28
License: GPLv2 or later
Text Domain: tribe-wootickets
Domain Path: /lang/
 */

/*
 Copyright 2010-2012 by Modern Tribe Inc and the contributors

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

if ( ! defined( 'ABSPATH' ) ) die( '-1' );

define( 'TRIBE_WOOTICKETS_DIR', dirname( __FILE__ ) );
add_action( 'plugins_loaded', 'wootickets_init' );

/**
 * Load WooCommerce Tickets classes and verify if the min required conditions are met.
 *
 * If they are, it instantiates the TribeWooTickets singleton.
 * If they are not, it uses the admin_notices hook with tribe_wootickets_show_fail_message
 *
 */
function wootickets_init() {
	require_once( TRIBE_WOOTICKETS_DIR . '/classes/class-wootickets.php' );
	require_once( TRIBE_WOOTICKETS_DIR . '/classes/tribe-events-wootickets-pue.class.php' );
	new TribeEventsWooticketsPUE( __FILE__ );

	if ( ! wootickets_should_run() || ! class_exists( 'TribeEventsTickets' ) ) {
		$langpath = trailingslashit( basename( dirname( __FILE__ ) ) ) . 'lang/';
		load_plugin_textdomain( 'tribe-wootickets', false, $langpath );
		add_action( 'admin_notices', 'tribe_wootickets_show_fail_message' );

		return;
	}

	TribeWooTickets::init();
}

/**
 * Whether the current version is incompatible with the installed and active WooCommerce
 * @return bool
 */
function is_incompatible_woocommerce_installed() {
	if ( ! class_exists( 'Woocommerce' ) )
		return true;

	if ( ! class_exists( 'TribeWooTickets' ) )
		return true;

	global $woocommerce;
	if ( ! version_compare( $woocommerce->version, TribeWooTickets::REQUIRED_WC_VERSION, '>=' ) )
		return true;

	return false;
}

/**
 * Whether the current version is incompatible with the installed and active The Events Calendar
 * @return bool
 */
function is_incompatible_events_core_installed() {
	if ( ! class_exists( 'TribeEventsTickets' ) )
		return true;

	if ( ! class_exists( 'TribeWooTickets' ) )
		return true;

	if ( ! version_compare( TribeEvents::VERSION, TribeWooTickets::REQUIRED_TEC_VERSION, '>=' ) )
		return true;

	return false;
}

/**
 * Verifies if the min required conditions are met.
 * @return bool
 */
function wootickets_should_run() {

	if ( is_incompatible_events_core_installed() )
		return false;

	if ( is_incompatible_woocommerce_installed() )
		return false;

	return true;
}


/**
 * Shows an admin_notices message explaining why it couldn't be activated.
 */
function tribe_wootickets_show_fail_message() {
	if ( ! current_user_can( 'activate_plugins' ) )
		return;

	$url_tec = add_query_arg(
		array( 'tab'       => 'plugin-information',
			'plugin'    => 'the-events-calendar',
			'TB_iframe' => 'true' ), admin_url( 'plugin-install.php' ) );

	$url_woocommerce = add_query_arg(
		array( 'tab'       => 'plugin-information',
			'plugin'    => 'woocommerce',
			'TB_iframe' => 'true' ), admin_url( 'plugin-install.php' ) );

	$title_tec         = __( 'The Events Calendar', 'tribe-wootickets' );
	$title_woocommerce = __( 'WooCommerce', 'tribe-wootickets' );

	echo '<div class="error"><p>';

	if ( is_incompatible_events_core_installed() ) {
		printf( __( 'To begin using WooTickets, please install and activate the latest version of <a href="%s" class="thickbox" title="%s">%s</a>.', 'tribe-wootickets' ), esc_url( $url_tec ), $title_tec, $title_tec );
	} elseif ( is_incompatible_woocommerce_installed() ) {
		printf( __( 'To begin using WooTickets, please install and activate the latest version of <a href="%s" class="thickbox" title="%s">%s</a>.', 'tribe-wootickets' ), esc_url( $url_woocommerce ), $title_woocommerce, $title_woocommerce );
	}

	echo '</p></div>';
}
