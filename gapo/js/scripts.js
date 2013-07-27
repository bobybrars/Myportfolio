// Twitter settings
var twitterUsername = 'sabrewebdesign'; 	// Your Twitter username
var tweetCount = 2; 						// How many Tweets to display

// Cufon settings
if (typeof(Cufon) != 'undefined') {
	Cufon.replace('h1')('h2')('h3');											// Every h1 h2 h3
	Cufon.replace('.footer-header h1', {										// Footer header h1
		color: '-linear-gradient(#fcfcfc, #e7e7e7, rgb(0, 0, 0))',
		textShadow: '#000000 2px 2px 0px'
	});
	Cufon.replace('.stick-out-text h1', {										// Homepage stick out text h1
		color: '-linear-gradient(#f9f9f9, #eeeeee, rgb(0, 0, 0))',
		textShadow: '#000000 -1px -1px 0px'
	});
	Cufon.replace('.sub-page-header h1, .page-header h1', {						// Page header h1
		color: '-linear-gradient(#f9f9f9, #ffffff, #e1e1e1,  rgb(0, 0, 0))',
		textShadow: '#000000 1px 1px 0px'
	});
	Cufon.replace('.nav-outer ul li a', {hover: true});							// Navigation
}

$(window).load(function() {
	// Start the homepage slider
	if ($.fn.nivoSlider) {
		$('#slider').nivoSlider({
			controlNav: false
		});
	}
	
	// Get the Twitter feed
	if (twitterUsername) {
		(function() {
			var b = document.createElement('script'); b.type = 'text/javascript'; b.src = 'js/twitter.js'; b.defer = 'defer';
			var t = document.createElement('script'); t.type = 'text/javascript'; t.src = 'http://twitter.com/statuses/user_timeline/' + twitterUsername + '.json?callback=twitterCallback2&count=' + tweetCount; t.defer = 'defer';
			var h = document.getElementsByTagName('head')[0]; h.appendChild(b); h.appendChild(t);
		})();
	}
});


$(document).ready(function() {	
	// Hide homepage content
	$('#homepage-content-wrap').hide().addClass('hc-hidden');
	$('#homepage-content-toggle').click(function() {		
		var contentWrapper = $('#homepage-content-wrap');
		if (contentWrapper.hasClass('hc-hidden')) {
			contentWrapper.slideDown('slow').removeClass('hc-hidden');
		} else {
			contentWrapper.slideUp().addClass('hc-hidden');
		}
	});
	
	// Testimonial fader
	$('#testimonial-wrapper').loopedFader({
		fadeInSpeed: 1500,
		fadeOutSpeed: 1500,
		waitTime: 4000,
		slide: '.testimonial'
	});
    
	// Gallery tabs
	if ($.fn.tabs) {
		$tabs = $('#galleryTabs').tabs();
	}
	
	// Colorbox on gallery
	if ($.fn.colorbox) {
		$('a[rel^="gallery"]').colorbox({
			transition: 'fade'
		});
	}
	
	// Clear the contact form elements on first click
	$('#name, #email, #phone, #message').focus(function() {
		$(this).val('').unbind('focus');
	});
});


//Image preloader
var images = new Array(
	'images/more-info1.png'
);
var imageObjs = new Array();
for (var i in images) {
	imageObjs[i] = new Image();
	imageObjs[i].src = images[i];
}
