<?php

namespace muv\YouTubeDatenschutz\Wordpress;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Deaktivierung des Plugins
 *
 * @since 0.9.0
 */
class Deactivate {

	/**
	 * Deaktiviert das Plugin (handelt multisite und nicht multisite Installationen)
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function deactivate($netzwerkweit) {
		/*
		 * Wenn es sich um eine Multisite installation handelt UND das Plugin für ALLE Sites deaktiviert werden soll,
		 * dann über alle bisher aktiven Sites gehen und das Plugin für jede Site einzeln deaktivieren
		 */
		if ( is_multisite() && $netzwerkweit ) {
			$currentId = get_current_blog_id();
			/* Alle Sites "abarbeiten" */
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				self::_deactivate();
			}
			switch_to_blog( $currentId );
		} else {
			/*
			 * Es ist entweder KEINE Multisite Installation oder es ist eine Multisite installation, aber das
			 * Plugin soll nur für eine einzelne Site deaktiviert werden und nicht für das gesamte Netzwerk
			 */
			self::_deactivate();
		}
	}

	/**
	 * Beinhaltet den eigentlichen Deaktivierungs-Code.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function _deactivate(){
		/*
		 * Aktuell passiert noch nichts...
		 */
	}

}
