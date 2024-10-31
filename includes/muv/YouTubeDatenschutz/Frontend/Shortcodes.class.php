<?php

namespace muv\YouTubeDatenschutz\Frontend;

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

/**
 * Definiert alle vorhandenen shortcodes
 *
 * @since 0.9.0
 */
class Shortcodes {

	/**
	 * Initialisierung alle Shortcodes
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @author Tobias Frohme <t.frohme@muv.com>
	 * @since 0.9.0
	 * @version 0.9.0
	 */
	public static function init() {
		/* Der Shortcode zum Einbinden des Videos */
		add_shortcode( 'muv-youtube', array( self::class, 'shortcodeYouTube' ) );
	}

	/**
	 * Der Shortcode zum Einbinden des Videos
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @author Tobias Frohme <t.frohme@muv.com>
	 * @since 0.9.0
	 * @version 1.0.0
	 *
	 * @param array $attr Die Parameter des Shortcodes
	 *
	 * @return string Die Ausgabe
	 */
	public static function shortcodeYouTube( $attr ) {
		/* Das Ergebnis */
		$html = '';

		/*
		 * Die übergebenen Parameter auslesen
		 */
		$video  = (isset($attr['v'])) ? $attr['v'] : '';
		$vorschau = (isset($attr['vorschau'])) ? $attr['vorschau'] : '';
		/* Sollte der Button Parameter NICHT übergeben werden, dann den Button ANZEIGEN */
		$button = (isset($attr['button'])) ? $attr['button'] : 1;

		/*
		 * Sollte der Video-Parameter leer sein, gibt es auch KEINE Ausgabe
		 */
		if (  empty( $video ) ) {
			return $html;
		}

		/*
		 * Sollte der Vorschau Parameter leer sein, dann das Bild von YouTube anzeigen
		 */
		if ( empty($vorschau)){
			/*
			 * Da alles auf Klassen basiert, selber das Vorschaubild ausgeben und dieses Bild mit einer
			 * "eigenen" Klasse versehen...
			 */
			$vorschau = uniqid('muv_yd_');
			$html .= '<div><img src="https://i.ytimg.com/vi/' . esc_html( $video ) . '/hqdefault.jpg"';
			$html .= ' class="' . $vorschau . '"></div>';
		}


		/*
		 * Jetzt ein kleines Javascript ausgeben, dass unsere globale Javascript - Methode mit Parametern
		 * aufruft. Dabei werden sowohl Bilder als auch Links mit einer onklick-Methode versehen.
		 * Sollte der Play
		 */
		$html .= '<script>';
		$html .= 'jQuery(document).ready(function($){';

		/* Video abspielen */
		/* Das "div" wird für Divi benötigt */
		$html .= '$("a.' . esc_html( $vorschau ) . ', img.' . esc_html( $vorschau ) . ', div.' . esc_html( $vorschau ) . ', div.' . esc_html( $vorschau ) . '::before").click(function(e){';
		$html .= 'muv_yd_zeigeVideo("' . esc_html( $video ) . '");';
		/* keine weiteren Aktionen ausführen! */
		$html .= 'e.preventDefault();';
		$html .= '});';

		/* Bilder sollten noch die Hand als Markierung bekommen, damit der user sieht, das er drauf klicken kann... */
		$html .= '$("img.' . esc_html( $vorschau ) . ', div.' . esc_html( $vorschau ) . '").addClass("muv_yd_pointer");';

		/* Sollte der Button gewünscht sein, dann über eine Klasse einbinden */
		if ($button){
			$html .= '$("div.' . esc_html( $vorschau ) . '").addClass("muv_yd_button");';
		}

		$html .= '});';
		$html .= '</script>';

		/*
		 * Fertig!
		 * Also den Inhalt zurück geben (NICHT ausgeben!)
		 */
		return $html;
	}

}
