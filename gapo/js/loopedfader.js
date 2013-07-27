if(typeof jQuery != 'undefined') {
	jQuery(function($) {
		$.fn.extend({
			loopedFader: function(options) {
				var settings = $.extend({}, $.fn.loopedFader.defaults, options);

				return this.each(
					function() {
						var $t = $(this);
						var o = $.metadata ? $.extend({}, settings, $t.metadata()) : settings;
						var slides = $(o.slide,$t).size();
						var current = 0;
						
						$(o.slide,$t).hide();
						$(o.slide + ':eq(' + current + ')',$t).fadeIn(o.fadeInSpeed);
						
						if (slides < 2) {
							return;
						}
						
						if (o.method == 'normal') {
							waitTime = o.waitTime + o.fadeInSpeed + o.fadeOutSpeed;
						} else if (o.method == 'merge') {
							waitTime = o.waitTime + o.fadeInSpeed;
						}
						
						faderIntervalId = setInterval(function () {
							animate();
						}, waitTime);
						
						function animate()
						{
							next = (current == slides-1) ? 0 : current + 1;
														
							if (o.method == 'normal') {
								$(o.slide + ':eq(' + current + ')',$t).fadeOut(o.fadeOutSpeed, function () {
									$(o.slide + ':eq(' + next + ')',$t).fadeIn(o.fadeInSpeed);
								});
							} else if (o.method == 'merge') {
								$(o.slide + ':eq(' + current + ')',$t).fadeOut(o.fadeOutSpeed);
								$(o.slide + ':eq(' + next + ')',$t).fadeIn(o.fadeInSpeed);
							}
							
							current = next;
						}
					}
				);
			}
		});
		 $.fn.loopedFader.defaults = {
			 slide: ".fader-slide", // Class each of slide.
			 fadeInSpeed: 1000, // Time for the slide to fade in 1000 = 1 second
			 fadeOutSpeed: 1000, // Time for the slide to fade out 1000 = 1 second
			 waitTime: 4000, // Time for the slide to show for 1000 = 1 second
			 method: 'normal' // Either 'normal' or 'merge'.  Merge will start to fade in the next slide as soon as the previous slide starts to fade out.  Normal will wait until the previous slide has completely faded out before fading in the next slide. 
		 };
	});
}