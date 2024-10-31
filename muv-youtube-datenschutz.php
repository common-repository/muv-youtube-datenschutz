<?php
/*
 * Plugin Name: muv - Youtube - Datenschutz
 * Plugin URI: https://wordpress.org/plugins/muv-youtube-datenschutz
 * Description: Bindet ein YouTube-Video mit maximaler Datenschutz-konformität ein. Funktioniert sowohl mit dem "originalen" WordPress - Editor als auch mit sämtlichen visuellen Editoren von z.B. Divi.
 * Version: 1.0.0
 * Requires at least: 4.9
 * Tested up to: 5.2.4
 * Author: Meins und Vogel
 * Author URI: https://muv.com
 * Text Domain: muv-youtube-datenschutz
 * Domain Path: /languages 
 * License: GPLv2 or later
 */

/*
 * muv - YouTube - Datenschutz
 * Copyright (C) 2017, Meins und Vogel GmbH / muv.com - info@muv.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Zugriff nur als Plugin innerhalb von Wordpress
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/*
 * benötigte Konstanten 
 */

/* Der Dateiname (inkl. Pfad) */
if ( ! defined( 'MUV_YD_FILE' ) ) {
	define( 'MUV_YD_FILE', __FILE__ );
}

/* Der Ordner */
if ( ! defined( 'MUV_YD_DIR' ) ) {
	define( 'MUV_YD_DIR', dirname( __FILE__ ) );
}

/* Der UNTER-Order inkl Dateiname des Plugins */
if ( ! defined( 'MUV_YD_BASE' ) ) {
	define( 'MUV_YD_BASE', plugin_basename( __FILE__ ) );
}

/* Die URL zu den Plugin-Dateien */
if ( ! defined( 'MUV_YD_URL' ) ) {
	define( 'MUV_YD_URL', plugins_url( dirname( MUV_YD_BASE ) ) );
}

/* Der inlcude - Ordner, der die Klassen beinhaltet */
if ( ! defined( 'MUV_YD_INC' ) ) {
	define( 'MUV_YD_INC', MUV_YD_DIR . '/includes/' );
}

/* Die Update-Kennung innerhalb unserer Update-Tabelle */
if ( ! defined( 'MUV_YD_UPATE_IDENTIFIER' ) ) {
	define( 'MUV_YD_UPATE_IDENTIFIER', 'muv-youtube-datenschutz' );
}


/* Autoload */
spl_autoload_register( 'muv_yd_autoload' );


/*
 * Unser Plugin benötigt die Session zum Freischalten der einzelnen Seiten
 */
if ( ! session_id() ) {
	session_start();
}


/* Hooks */
register_activation_hook( MUV_YD_FILE, array( muv\YouTubeDatenschutz\Wordpress\Install::class, 'install' ) );
add_action( 'wpmu_new_blog', array( muv\YouTubeDatenschutz\Wordpress\Install::class, 'newBlog'), 10, 6);

register_deactivation_hook( MUV_YD_FILE, array( muv\YouTubeDatenschutz\Wordpress\Deactivate::class, 'deactivate' ) );

add_action( 'delete_blog', array( muv\YouTubeDatenschutz\Wordpress\Uninstall::class, 'deleteBlog'));
register_uninstall_hook( MUV_YD_FILE, array( muv\YouTubeDatenschutz\Wordpress\Uninstall::class, 'uninstall' ) );

/* Go... */
add_action( 'plugins_loaded', array( muv\YouTubeDatenschutz\Plugin\Main::class, 'init' ) );

function muv_yd_autoload( $class ) {
	if ( strpos( $class, 'muv\YouTubeDatenschutz' ) === 0 ) {
		$libFile = MUV_YD_INC . $class . '.class.php';
		$libFile = str_replace( '\\', '/', $libFile );
		if ( file_exists( $libFile ) ) {
			require_once( $libFile );
		}
	}
}
