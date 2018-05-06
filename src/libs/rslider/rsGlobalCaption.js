(function($) {

	"use strict";

    /**
     * RS Module: Global Caption
     * @version 1.0.1:
     */
	$.extend($.rsProto, {
		_initGlobalCaption: function() {
			var self = this;
			if(self.settings.globalCaption) {
				var setCurrCaptionHTML = function () {
					self.globalCaption.html(self.currSlide.caption||'');
				};
				self.ev.on('rsAfterInit', function() {
					self.globalCaption = $('<div class="rsGCaption"></div>').appendTo( !self.settings.globalCaptionInside ? self.slider : self._sliderOverflow );
					setCurrCaptionHTML();
				});
				self.ev.on('rsBeforeAnimStart' , function() {
					setCurrCaptionHTML();
				});
			}
		}
	});
	$.rsModules.globalCaption = $.rsProto._initGlobalCaption;
})(jQuery);