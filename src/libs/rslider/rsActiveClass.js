(function($) {

	"use strict";

	/**
     * RS Module: ActiveClass
	 * @version 1.0.1:
	 */
	$.rsProto._initActiveClass = function() {
		var	self = this;
		var aSlideClass = 'rsActiveSlide';
		if(self.st.addActiveClass) {
            var idTimerUpdateClass;
			self.ev.on('rsOnUpdateNav', function() {
				if(idTimerUpdateClass) {
					clearTimeout(idTimerUpdateClass);
                }
				idTimerUpdateClass = setTimeout(function() {
					if(self._oldHolder) {
						self._oldHolder.removeClass(aSlideClass);
                    }
					if(self._currHolder) {
						self._currHolder.addClass(aSlideClass);
                    }
					idTimerUpdateClass = null;
				}, 50);
			});
		}
	};
	$.rsModules.activeClass = $.rsProto._initActiveClass;
})(jQuery);
