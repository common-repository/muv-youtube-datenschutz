<?php

namespace muv\YouTubeDatenschutz\Wordpress;

use muv\YouTubeDatenschutz\Classes\DBTables;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Installation des Plugins
 *
 * @since 0.9.0
 */
class Install {

	/**
	 * Installiert das Plugin (handelt multisite und nicht multisite Installationen)
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function install( $netzwerkweit ) {
		/*
		 * Wenn es sich um eine Multisite installation handelt UND das Plugin für ALLE sites aktiviert werden soll,
		 * dann über alle bisher aktiven Sites gehen und das Plugin für jede Site einzeln aktivieren
		 */
		if ( is_multisite() && $netzwerkweit ) {
			$currentId = get_current_blog_id();
			/* Alle Sites "abarbeiten" */
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				self::_install();
			}
			switch_to_blog( $currentId );
		} else {
			/*
			 * Es ist entweder KEINE Multisite Installation oder es ist eine Multisite installation, aber das
			 * Plugin soll nur für eine einzelne Site installiert werden und nicht für das gesamte Netzwerk.
			 */
			self::_install();
		}
	}

	/**
	 * Wird aufgerufen, wenn innerhalb einer multisite-Installation eine neue Site erstellt wird.
	 * Sollte das Plugin Netzwerkweit aktiviert sein, dann wird das Plugin auch gleich in dem neuen Blog aktiviert.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function newBlog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network( MUV_YD_BASE ) ) {
			/*
			 * Das Plugin ist Netzwerkweit aktiviert, also auch in dem neuen Blog aktivieren...
			 */
			$currentId = get_current_blog_id();
			switch_to_blog( $blog_id );
			self::_install();
			switch_to_blog( $currentId );
		}
	}

	/**
	 * Beinhaltet den eigentlichen Installations-Code.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function _install() {
		/*
		 * Die eigene DB auf die aktuelle Version bringen, falls notwendig...
		 */
		self::updateTables();
	}

	/**
	 * Erzeugt alle benötigten Tabellen bzw. bringt sie auf den neuesten Stand
	 *
	 * @author tobias Frohme <t.frohme@muv.com>
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function updateTables() {

		/* Ich benötige die DB */
		global $wpdb;

		/* Alle Tabellen */
		$tables = DBTables::getTables();

		/*
		 * Zuerst eimal muss sicher gestellt werden, dass die Grundtabelle existiert, die zum Update
		 * benötigt wird. Dazu einfach mal versuchen, die Tabelle anzulegen, sollte sie NICHT existieren.
		 * (Sollte sie existieren, macht der Befehl einfach nichts...)
		 */
		$sql = "CREATE TABLE IF NOT EXISTS " . $tables['intversion'] . " ( 
				`identifier` VARCHAR(50) NOT NULL,
				`version` INT(10) UNSIGNED NOT NULL,
				`created_at` DATETIME NOT NULL,
				PRIMARY KEY (`identifier`, `version`)
				)";
		$wpdb->query( $sql );

		$updateRoot = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/update/';

		/* Die Soll-Version des Systems ermitteln. Dies ist einfach die Anzahl der Update-Scripte */
		$sollVersion = count( glob( $updateRoot . '/*.inc.php' ) );

		/*
		 * Die aktuelle Version des Systems aus der DB ermitteln
		 */
		$sql = $wpdb->prepare( "SELECT max(version) FROM " . $tables['intversion'] . " WHERE identifier = %s",
			MUV_YD_UPATE_IDENTIFIER );

		$istVersionSql = $wpdb->get_var( $sql );
		$istVersion    = ( empty( $istVersionSql ) ) ? 0 : (integer) $istVersionSql;

		/*
		 * Sollte die aktuelle Version kleiner sein, als die (benötigte) Version, dann updaten...
		 */
		if ( $istVersion < $sollVersion ) {
			/*
			 * Alle Update-Schritte ausführen (Schritt = Version)
			 */
			for ( $i = $istVersion; $i < $sollVersion; $i ++ ) {
				/* Das File heißt so, wie die Version  */
				$updateFile = $updateRoot . str_pad( $i + 1, 4, '0', STR_PAD_LEFT ) . '.inc.php';

				/*
				 * Sollte das Update-File existieren, dann einbinden (und damit ausführen)
				 */
				if ( file_exists( $updateFile ) ) {
					include $updateFile;
				}
				/*
				 * und die neue Version merken
				 */
				$sql = $wpdb->prepare( "INSERT INTO " . $tables['intversion'] .
				                       " (`identifier`, `version`, `created_at`) VALUES (%s, %d, NOW())", MUV_YD_UPATE_IDENTIFIER, $i + 1 );

				$wpdb->query( $sql );
			}
		}
	}

}
