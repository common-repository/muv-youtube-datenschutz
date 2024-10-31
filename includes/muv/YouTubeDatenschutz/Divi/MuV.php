<?php


include __DIR__ . '/Image.php';

/**
 * Eigenes Video - Modul für den Visual Builder von Divi.
 * Um die Wartbarkeit zu erhöhen beinhaltet dieses Modul lediglich DIE ÄNDERUNGEN zum Divi Bilder-Modul.
 * Um keine Probleme mit einem eventuellen Update zu bekommen wird die Divi Klasse als KOPIE eingebunden
 *
 * @author Oliver Vogel <o.vogel@muv.com>
 * @since 1.0.0
 * @version 1.0.0
 */
class ET_Builder_Module_MuV_YD extends muv_ET_Builder_Module_Image {
	/**
	 * Initialisiert das Modul.
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function init() {
		/*
		 * Init des Vaters und dann die Änderungen einbringen
		 */
		parent::init();

		$this->name       = esc_html__( 'muv Youtube Video', 'muv-youtube-datenschutz' );
		$this->fb_support = true;

		/* Wichtig! Der Slug MUSS mit "et_pb_" beginnen, sonst wird das Modul nicht innerhalb der Seite gespeichert */
		$this->slug = 'et_pb_muv_yd_video';

		/*
		 * Die Felder löschen, die nicht benötigt werden...
		 */
		$this->whitelisted_fields = array_diff( $this->whitelisted_fields, [
			'show_in_lightbox',
			'url',
			'url_new_window'
		] );

		/*
		 * Neue Felder hinzufügen
		 */
		$this->whitelisted_fields = array_merge( $this->whitelisted_fields, [
			'vid'
		] );

		/*
		 * Bei den Default-Werten ist löschen nicht notwendig. Was es nicht gibt, hat auch keinen Default-Wert.
		 */

		/*
		 * Die allgemeinen Optionen ändern...
		 */
		$this->options_toggles['general'] = array(
			'toggles' => array(
				/* Video (neu) */
				'main_video'   => esc_html__( 'YouTube Video', 'et_builder' ),
				/* Vorschaubild (ändern) */
				'main_content' => esc_html__( 'Vorschaubild', 'et_builder' )
				/* Den Bereich "Link" entfernen */
			),
		);
	}

	/**
	 * Liefert die Feld-Definitionen zurück
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function get_fields() {
		/*
         * Methode des Vaters und dann die Änderungen einbringen
         */
		$fields = parent::get_fields();

		/*
		 * Nicht benötigte Felder löschen
		 */
		unset( $fields['show_in_lightbox'] );
		unset( $fields['url'] );
		unset( $fields['url_new_window'] );

		/*
		 * Das YouTube Video
		 */
		$fields['vid'] = array(
			'label'           => esc_html__( 'Video Id', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Der Wert hinter (d.h. OHNE) https://www.youtube.com/watch?v=', 'et_builder' ),
			'toggle_slug'     => 'main_video',
		);

		/*
		 * Das Vorschaubild
		 */
		$fields['src'] = array(
			'label'              => esc_html__( 'Bild-URL', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'basic_option',
			'upload_button_text' => esc_attr__( 'Ein Bild hochladen', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Vorschaubild auswählen', 'et_builder' ),
			'update_text'        => esc_attr__( 'Als Vorschaubild auswählen', 'et_builder' ),
			'affects'            => array(
				'alt',
				'title_text',
			),
			'description'        => esc_html__( 'Gewünsches Bild hochladen/auswählen oder leer lassen um das Vorschaubild von YouTube zu verwenden.', 'et_builder' ),
			'toggle_slug'        => 'main_content',
		);

		return $fields;
	}

	/**
	 * Erzeugt das HTML aus den Werten des Moduls
	 *
	 * @author Oliver Vogel <o.vogel@muv.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function shortcode_callback( $atts, $content = null, $function_name ) {
		/*
		 * Die Werte, die UNSER Modul nicht hat simulieren, damit das Divi Modul arbeiten kann...
		 */
		$this->shortcode_atts['url']              = '';
		$this->shortcode_atts['url_new_window']   = '';
		$this->shortcode_atts['show_in_lightbox'] = 'off';

		/*
		 * Die Klasse um unsere benötigte Klasse erweitern
		 */
		$vorschau                             = uniqid( 'muv_yd_' );
		$this->shortcode_atts['module_class'] .= ' ' . $vorschau;

		/*
		 * Sollte der Vorschau Parameter leer sein, dann das Bild von YouTube anzeigen
		 */
		if ( empty( $this->shortcode_atts['src'] ) ) {
			$this->shortcode_atts['src'] = 'https://i.ytimg.com/vi/' . esc_html( $this->shortcode_atts['vid'] ) . '/hqdefault.jpg"';
		}

		/*
		 * Jetzt die Ausgabe des Vaters erzeugen
		 */
		$output = parent::shortcode_callback( $atts, $content, $function_name );

		/*
		 * Das JavaScript dazu machen
		 */
		$output .= '<script>';
		$output .= 'jQuery(document).ready(function($){';

		/* Video abspielen */
		$output .= '$("div.' . esc_html( $vorschau ) . ' span").click(function(e){';
		$output .= 'muv_yd_zeigeVideo("' . esc_html( $this->shortcode_atts['vid'] ) . '");';
		/* keine weiteren Aktionen ausführen! */
		$output .= 'e.preventDefault();';
		$output .= '});';

		/* Bilder sollten noch die Hand als Markierung bekommen, damit der user sieht, das er drauf klicken kann... */
		$output .= '$("div.' . esc_html( $vorschau ) . ' span").addClass("muv_yd_pointer");';

//		/* Sollte der Button gewünscht sein, dann über eine Klasse einbinden */
//		if ($button){
			$output .= '$("div.' . esc_html( $vorschau ) . ' span").addClass("muv_yd_button");';
//		}

		$output .= '});';
		$output .= '</script>';

		return $output;
	}


}

new ET_Builder_Module_MuV_YD();

