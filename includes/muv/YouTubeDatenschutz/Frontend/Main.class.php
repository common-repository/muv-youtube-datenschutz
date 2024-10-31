<?php

namespace muv\YouTubeDatenschutz\Frontend;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Die Hauptfunktionalität des Frontend
 *
 * @since 0.9.0
 */
class Main {

	/**
	 * Initialisiert das Frontend
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @author Tobias Frohme <t.frohme@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function init() {
		/*
		 * Initialisieren der benötigten Objekte
		 */
		Shortcodes::init();

		/*
		 * Einige Scripte werden von allen Frontend Seiten benötigt und deshalb eingebunden
		 */
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueueScripts' ) );
	}

	public static function enqueueScripts() {
		/*
		 * Das CSS für das Video Popup
		 */
		wp_enqueue_style('muv_yd_css', MUV_YD_URL . '/assets/css/frontend.min.css');
		/*
		 * Das Script, dss das IFrame erzeugt
		 */
		wp_enqueue_script( 'muv_yd_js',  MUV_YD_URL . '/assets/js/frontend.js', array( 'jquery' ), null, true );
	}

}
