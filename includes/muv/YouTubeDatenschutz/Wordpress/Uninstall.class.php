<?php

namespace muv\YouTubeDatenschutz\Wordpress;

use muv\YouTubeDatenschutz\Classes\DBTables;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * De-Installation des Plugins
 *
 * @since 0.9.0
 */
class Uninstall {

	/**
	 * Deinstalliert das Plugin (handelt multisite und nicht multisite Installationen)
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function uninstall() {
		/*
		 * Wenn es sich um eine Multisite installation handelt dann IMMER über alle Blogs gehen und das
		 * Plugin deinstallieren. Ich weiß nicht, ob es im betreffenden Blog auch wirklich aktiv war.
		 * Deshalb muss das uninstall immer so programmiert werden, dass nichts passiert, wenn es das Plugin nicht gab!
		 */
		if ( is_multisite()) {
			$currentId = get_current_blog_id();
			/* Alle Sites "abarbeiten" */
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				self::_uninstall();
			}
			switch_to_blog( $currentId );
		} else {
			/*
			 * Es ist entweder KEINE Multisite Installation oder es ist eine Multisite installation, aber das
			 * Plugin soll nur für eine einzelne Site deinstalliert werden und nicht für das gesamte Netzwerk
			 */
			self::_uninstall();
		}
	}

	/**
	 * Wird aufgerufen, wenn innerhalb einer multisite-Installation eine bestehende Site gelöscht wird.
	 * Sollte das Plugin Netzwerkweit installiert sein, dann muss das Plugin vor dem Löschen deinstalliert werden.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function deleteBlog($blog_id) {
		/*
		 * Das Plugin ist Netzwerkweit installiert, also auch in dem aktuell zu löschenden Blog deinstallieren
		 * ACHTUNG! Es gibt keine Garantie, dass das Plugin dort installiert ist!
		 * D.h. die Lösch-Methoden dürfen keinen Fehler erzeugen, wenn es das Plugin dort NICHT gibt!
		 */
		if ( is_plugin_active_for_network( MUV_YD_BASE ) ) {
			$currentId = get_current_blog_id();
			switch_to_blog( $blog_id );
			self::_uninstall();
			switch_to_blog( $currentId );
		}
	}

	/**
	 * Beinhaltet den eigentlichen Deinstallations-Code.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function _uninstall(){
		/*
		 * Die eigene DB (die eigenen Tabellen) komplett löschen...
		 */
		self::dropTables();

		/*
		 * Alle Optionen löschen...
		 */
		self::dropOptions();

	}

	/**
	 * Löscht die nicht mehr benötigten Tabellen
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function dropTables() {

		/* Ich benötige die DB */
		global $wpdb;

		/* Alle Tabellen */
		$tables = DBTables::getTables();

		/*
		 * Zuerst alle Tabellen löschen, aber auf keinen Fall die für alle Updates intern verwendete Tabelle
		 * (da ich sonst die Informationen ALLER Plugins löschen würde)
		 *
		 * IMMER mit "if exists" da es bei einer Multisite Installation sein kann, dass ein Plugin für die aktuelle
		 * Site gar nie aktiv war!...
		 */
		foreach ( $tables as $table ) {
			if ( $table !== $tables['intversion'] ) {
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $table );
			}
		}

		/*
		 * Sollte das Plugin zwar hochkopiert, aber NIE aktiviert worden sein, dann existiert die Tabelle NICHT!
		 */
		$tblGefunden = $wpdb->get_row( "SHOW TABLES LIKE '" . $tables['intversion'] . "'" );
		if ( ! empty( $tblGefunden ) ) {
			/*
			 * Jetzt noch die Update-Informationen dieses Plugins aus der Datenbank löschen
			 */
			$sql = $wpdb->prepare( "DELETE FROM " . $tables['intversion'] . " WHERE `identifier` = %s", MUV_YD_UPATE_IDENTIFIER );
			$wpdb->query( $sql );

			/*
			 * Sollte die Update Tabelle nun leer sein, war dies das letzte Plugin von uns, also dann auch die
			 * Tabelle selber löschen
			 */
			$rest = $wpdb->get_var( "SELECT COUNT(*) FROM " . $tables['intversion'] );
			if ( empty( $rest ) ) {
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $tables['intversion'] );
			}
		}
	}

	/**
	 * Löscht die nicht mehr benötigten Optionen
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	private static function dropOptions() {
		global $wpdb;
		/*
		 * Wir erkennen die Optionen dieses Plugins an deren Kennung!
		 */
		$wpdb->query('DELETE FROM ' . $wpdb->options  . " WHERE option_name like 'muv-fd-%'");
	}

}
