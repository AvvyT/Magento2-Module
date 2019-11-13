define([
  'jquery',
  'AvvyTest_FirstAvvysModule/js/carousel/owl.carousel.min'
], function () {
  'use strict';
  jQuery.noConflict();

  jQuery('.owl-carousel').owlCarousel({
    pagination: false,
    marginRight: 20,
    lazyLoad: true,

    navigation: true, // Show next and prev buttons
    //navigationText: ["<", ">"], //show prev next
    loop: true,
    autoPlay: true,
    /*
   animateOut: 'fadeOut',
   animateIn: 'fadeIn',
   */
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    responsive: {
      0: {
        items: 1
      },

      600: {
        items: 3
      },

      1024: {
        items: 4
      },

      1366: {
        items: 4
      }
    }
  });
});
