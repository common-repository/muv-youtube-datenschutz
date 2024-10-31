=== muv - YouTube - Datenschutz ===
Contributors: meinsundvogel
Plugin URI: https://wordpress.org/plugins/muv-youtube-datenschutz
Stable tag: 1.0.0
Tags: YouTube, Datenschutz, Datenschutz konform, Video, einbinden, Sicherheit
Requires at least: 4.9
Tested up to: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Bindet ein YouTube-Video mit maximaler Datenschutz-konformität ein. Funktioniert sowohl mit dem "originalen" WordPress - Editor als auch mit sämtlichen visuellen Editoren von z.B. "Divi Builder".
 

== Description ==
Datenschutz wird immer wichtiger. Um den strengen europäischen bzw. deutschen Anforderungen gerecht zu werden und einer evtuellen Abmahnung entgegen zu wirken muss jedes YouTube Video best-gesichert eingebudnen werden.
Dies erfolgt durch unser Plugin in zwei Schritten.
 * Es wird ein lokal auf Ihrem Server gespeichertes Bild Vorschaubild angezeigt. Dieses Bild hat keinerlei Verbindung zu YouTube.
 * Nach klick auf dieses Video wird dynamisch ein IFrame erzeugt, dass das YouTube Video nun mit "aktiviertem erweiterten Datenschutzmodus" einbindet.

Das Plugin funktioniert sowohl mit dem "originalen" WordPress-Editor, als auch mit dem Visuellen Editor von Divi 3.x

= So einfach werden Videos eingebunden =
"Originaler" WordPress Editor

Videos mit original YouTube Vorschaubild
Erstellen Sie einen sogenannten Shortcode. Geben Sie dazu ein
[muv-youtube v=<<Kennung des Videos>>]
Also z.B. [muv-youtube v=2x8Lt4Z0jGg]
Das war's. Das original Vorschaubild von YouTube wird angezeigt und beim Klick auf das Bild wird ein IFrame erzeugt, das das Video im "aktiviertem erweiterten Datenschutzmodus" einbindet.

Videos mit eigenem Vorschaubild vom eigenen Server
Fügen Sie - wie Sie es gewohnt sind - das Vorschaubild lokal von Ihrem Server ein. Es kann sich dabei um ein beliebiges Bild handeln.
Klicken Sie mit der linken Maustaste auf das Bild. Es öffnet sich ein Menü. Klicken Sie dort auf den Stift.
Es öffnet sich ein Fenster. Unter "Erweiterte Optionen" Bild-CSS-Klasse vergeben Sie nun eine beliebige Klasse.
Es ist egal, wie Sie die Klasse nennen. Sie müssen sich nur merken, was Sie einggegeben haben.
Bitte verwenden Sie nur "a".."z" und "0".."9" und "-" und "_" für die Eingabe also z.B. "video_1" (ohne "")

Erstellen Sie nun einen sogenannten Shortcode. Geben Sie dazu ein
[muv-youtube v=<<Kennung des Videos>> vorschau=<<Klasse des Bildes>> button=<< 0 oder 1>>]

Also z.B. [muv-youtube v=2x8Lt4Z0jGg vorschau=video_1]

Ohne Angebe des Parameters button wird dieser über dem Bild angezeigt.

Das war's. Das Bild wird angezeigt und beim Klick auf das Bild wird ein IFrame erzeugt, das das Video im "aktiviertem erweiterten Datenschutzmodus" einbindet.

"Divi Builder"
Fügen Sie ein Bild mit der Bild-Komponente von Divi ein. Geben Sie diesem Bild eine Klasse (wie oben beschrieben).
Fügen Sie nun "Code" mit der Code-Componente ein. geben Sie dort den oben Beschriebenen Shortcode ein.

Alternativ können Sie auch das von diesem Plugin zur Verfügung gestellte Modul "muv Youtube Video" verwenden.

Andere visuelle Builder
Fügen Sie ein Bild mit der Bild-Komponente des Builders ein. Geben Sie diesem Bild eine Klasse (wie oben beschrieben).
Fügen Sie nun "Code" mit der Code-Componente ein. geben Sie dort den oben Beschriebenen Shortcode ein.
Das war's

== Installation ==
1. Entpacken Sie die ZIP-Datei und laden Sie den Ordner muv-youtube-datenschutz in das Plugin-Verzeichnis von WordPress hoch: wp-content/plugins/.
2. Loggen Sie sich dann als Admin unter WordPress ein. Unter dem Menüpunkt "Plugins" können Sie "muv - YouTube Datenschutz" nun aktivieren.

== Frequently Asked Questions ==

= Können Sie mir die Rechtssicherheit garantieren? =
NEIN, das können wir natürlich nicht! Wir können Ihnen aber bestätigen, das wir das Modul so geschrieben haben wie es unserer Meinung nach am sichersten ist.
Das Bild liegt lokal auf Ihrem Server (oder wird als statischer Inhalt vom YouTube-Server geholt) und das IFrame wird nur auf Anforderung erzegut und bindet das Video dann im "aktiviertem erweiterten Datenschutzmodus" ein.
Bei unserem ausführlichen Test (19.12.2017) wurden keine Cookies von YouTube in unserem Browser gespeichert!

= Kann mir mit dem Bild etwas passieren? =
An dieser Stelle müssen Sie bitte aufpassen. Falls Sie ein eigenes Vorschaubild von Ihrem Server verwenden, sollten Sie sicher stellen das Recht zu haben, dieses Bild zu verwenden.

= Unterstützt das Plugin Multisite Installationen? =
Ja, das Plugin wurde Multisite-kompatibel programmiert.

== Changelog ==
= 1.0.0 =
Veröffentlicht am: 11.11.2019
Dokumentation angepasst. Deshalb wurde die Versionsnummer nicht geändert.

= 1.0.0 =
Veröffentlicht am: 19.12.2017

* Verbesserung
  * Klickbare Vorschaubilder erhalten die Hand als Mauszeiger um anzuzeigen, das das Bild anklickbar ist.
  * Sollte der Parameter "vorschau" nicht übergeben werden, so wird das original YouTube Vorschaubild angezeigt.

* Erweiterung
  * Modul für den Divi Visual Builder


= 0.9.0 =

* Erstes internes Release

