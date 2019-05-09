// Wenn die Seite geladen ist 
$(document).ready(function() {
    // Wird das video in eine Variable gespeichert
    video = document.getElementById("enter_video");
    // Die schnelligkeit wird auf 0.8 eingestellt
    video.playbackRate = .8; 
    
    // Eine Animantion das man was tipt
    $('header .text').addClass('anim-typewriter');

    // Nach 5 Sekunden erscheint der Button
    // Und das Video wird auf 2x schnelligkeit eingestellt
    setTimeout( function(){ 
        $('.btnDo').css('opacity', '1');
        video.playbackRate = 2; 
    }  , 5000 );

    // Nach 7.5 Sekunden wird die Standartgeschwindigkeit eingestellt
    setTimeout( function(){ 
        video.playbackRate = 1; 
    }  , 7500 );
});