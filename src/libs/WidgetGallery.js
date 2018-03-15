/**
 * Created by Vitalie Lupu on 6/2/17.
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        /** ------------------------------------------ Slider Post Single Page */
        if (jQuery().royalSlider) {
            $('.royalSlider').royalSlider({
                autoScaleSlider: true,
                autoScaleSliderWidth: 1170,
                autoScaleSliderHeight: 425,
                fadeinLoadedSlide: true,
                loop: true,
                arrowsNav: true,
                arrowsNavAutoHide: true,
                arrowsNavHideOnTouch: true,
                navigateByClick: true,
                keyboardNavEnabled: true,
                numImagesToPreload: 2,
                imageScaleMode: 'fill'
            });
            /*$gallerySlider = $(".WidgetGallery > .royalSlider.rsMinW");
            $gallerySlider.css('background-color','transparent');
            $gallerySlider.royalSlider({
                fadeinLoadedSlide: true,
                loop: true,
                arrowsNav: true,
                arrowsNavAutoHide: true,
                arrowsNavHideOnTouch: true,
                navigateByClick: false,
                keyboardNavEnabled: true,
                autoScaleSlider: true,
                autoScaleSliderWidth: 1170,
                autoScaleSliderHeight: 425,
                imageScaleMode: 'fit-if-smaller',
                imageScalePadding: 0,
                transitionType: 'move',//move
                transitionSpeed: 1000,
                autoPlay: {
                    enabled: true,
                    stopAtAction: false,
                    pauseOnHover: true,
                    delay: 10000
                },
                usePreloader: true,
                numImagesToPreload: 1,
                controlNavigation: 'none',
            });*/
        }
    });
})(jQuery);