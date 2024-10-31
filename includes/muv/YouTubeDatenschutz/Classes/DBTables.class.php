<?php

namespace muv\YouTubeDatenschutz\Classes;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Das Objekt, das alle Tabellen-Definitionen beinhaltet
 *
 * @since 0.9.0
 */
class DBTables {

	/**
	 * Liefert die Definitionen aller benötigten Tabellen zurück
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function getTables() {
		global $wpdb;

		/* Die Tabelle, die die DB-Versionen beinhaltet (für ALLE Plugins gleich!) */
		$tables['intversion'] = $wpdb->get_blog_prefix() . 'muv_sh_intversion';

		/* Das Plugin selber hat keine eigenen Tabellen */
		return $tables;
	}

}
