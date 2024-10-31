"use strict";
function muv_yd_zeigeVideo(video){
    /*
     * Für spätere Versionen ...
     */
    var autoplayOption = 1;
    var videoLooping = 0;
    var videoWidth = 100;
    var videoShowInfo = 0;
    /*
     * Der Link mit NoCookie!
     */
    var videoLink = "https://www.youtube-nocookie.com/embed/" + video + "?autoplay=" + autoplayOption + "&" + videoLooping + "&rel=0&showinfo=" + videoShowInfo;
    /*
     * Popup öffnen
     */
    jQuery("body").append('<div class="muv_yd_popup"><span class="muv_yd_close"></span><div class="muv_yd_content" style="width:'+videoWidth+'% !important"><iframe src="'+videoLink+'" allowfullscreen allow="encrypted-media"></iframe></div></div>');

    /*
     * Popup wieder schließen
     */
    jQuery(".muv_yd_popup, .muv_yd_close").click(function (e) {
        //jQuery(".muv_yd_popup").addClass("muv_yd_hide").delay(500).queue(function () {
            jQuery(this).remove();
        //});
    });

    /*
     * Esc schließt auch das Video
     */
    jQuery(document).keyup(function (e) {
        if (e.keyCode == 27) {
            jQuery('.muv_yd_close').click();
        }

    });
}