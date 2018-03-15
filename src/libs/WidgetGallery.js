/**
 * Created by Vitalie Lupu on 6/2/17.
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        /** ------------------------------------------ Slider Post Single Page */
        if (jQuery().royalSlider) {
            var isSingleProperty = (document.getElementsByClassName('single-property').length > 0);
            var $wideSlider = $(".rsWhite > .royalSlider.rsMinW"),
                $smallSlider = $(".rsWhiteSmall > .royalSlider.rsMinW"),
                $gallerySlider = $(".WidgetGallery > .royalSlider.rsMinW");
            if ($gallerySlider[0]) {
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
                });
            } else if ($wideSlider[0] && isSingleProperty) {
                $($wideSlider[0]).royalSlider({
                    autoScaleSlider: true,
                    autoScaleSliderWidth: 710,
                    fadeinLoadedSlide: true,
                    loop: false,
                    arrowsNav: true,
                    arrowsNavAutoHide: false,
                    arrowsNavHideOnTouch: false,
                    navigateByClick: false,
                    keyboardNavEnabled: true,
                    numImagesToPreload: 5,
                    globalCaption: true,
                    fullscreen: { enabled: true },
                    imageScaleMode: 'fit-if-smaller',
                    imageScalePadding:0,
                    // transitionType:'fade',
                    controlNavigation: 'thumbnails',
                    thumbs: {
                        fitInViewport:false,
                        orientation: 'horizontal',
                        spacing: 16,
                        paddingBottom: 11
                    }
                });
            } else if ($smallSlider[0] && isSingleProperty){
                $($smallSlider[0]).royalSlider({
                    autoScaleSlider: true,
                    autoScaleSliderWidth: 557,
                    autoScaleSliderHeight: 420,
                    fadeinLoadedSlide: true,
                    loop: false,
                    arrowsNav: true,
                    arrowsNavAutoHide: false,
                    arrowsNavHideOnTouch: true,
                    navigateByClick: false,
                    keyboardNavEnabled: true,
                    numImagesToPreload: 5,
                    globalCaption: false,
                    fullscreen: {enabled: true},
                    imageScaleMode: 'fit-if-smaller',//fit fill fit-if-smaller
                    imageScalePadding: 0,
                    // transitionType:'fade',
                    controlNavigation: 'thumbnails',
                    thumbs: {
                        fitInViewport: false,
                        orientation: 'horizontal',
                        spacing: 16,
                        paddingBottom: 11
                    }
                });
            } else {
                $('.royalSlider').royalSlider({
                    autoScaleSlider: true,
                    fadeinLoadedSlide: true,
                    loop: false,
                    arrowsNav: true,
                    arrowsNavAutoHide: true,
                    arrowsNavHideOnTouch: true,
                    navigateByClick: true,
                    keyboardNavEnabled: true,
                    numImagesToPreload: 2,
                    imageScaleMode: 'fill',
                });
            }
        }
    });
})(jQuery);