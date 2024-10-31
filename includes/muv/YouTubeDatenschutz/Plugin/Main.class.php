<?php

namespace muv\YouTubeDatenschutz\Plugin;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Die Hauptfunktionalität des Plugins. Initialisiert das Frontend, das Backend usw.
 *
 * @since 0.9.0
 */
class Main {

	/**
	 * Initialisiert das Plugin
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @author Tobias Frohme <t.frohme@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function init() {
		if ( is_admin() ) {
			\muv\YouTubeDatenschutz\Admin\Main::init();
		} else {
			\muv\YouTubeDatenschutz\Frontend\Main::init();
		}

		/*
		 * Das Divi Modul wird bei Frontend und Backend benötigt
		 */
		add_action('et_builder_ready', array(self::class, 'initDiviVideo'));
	}

	/**
	 * Initialisiert das Divi Modul für den visuellen Editor
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public static function initDiviVideo(){
		if ( class_exists('ET_Builder_Module')) {
			include dirname( __DIR__ ) . '/Divi/MuV.php';
		}
	}

}
