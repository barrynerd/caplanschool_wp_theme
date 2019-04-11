(function( $ ) {
"use strict";
$(function() {
    // set the container that Masonry will be inside of in a var
    // adjust to match your own wrapper/container class/id name
    var container = document.querySelector('.blog #main');
    //create empty var msnry
    var msnry;
    // initialize Masonry after all images have loaded
    imagesLoaded( container, function() {
        msnry = new Masonry( container, {
            // adjust to match your own block wrapper/container class/id name
            itemSelector: 'article',
            // option that allows for your website to center in the page
            isFitWidth: true,
            gutter: 10,
            columnWidth: 250
        });
    });
});
}(jQuery));
