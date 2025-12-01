/**
 * MrMikes Template JavaScript
 * Handles sticky sidebar functionality, active section highlighting, and image toggle
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initStickySidebar();
        initActiveSection();
        initTabSwitching();
        initMobileCategorySlider();
        initMobileCategorySticky();
        initImageToggle();

        // Handle initial tab state from URL parameters
        initTabStateFromURL();
    });

    /**
     * Initialize tab state based on URL parameters
     */
    function initTabStateFromURL() {
        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const menuType = urlParams.get('menu_type');

        if (menuType) {
            // Normalize menu type (handle both 'drink' and 'drinks')
            let normalizedMenuType = menuType;
            if (menuType === 'drink') {
                normalizedMenuType = 'drinks';
            }

            // Find the corresponding tab and activate it
            const targetTabId = normalizedMenuType + '-tab';
            const $targetPane = $('#' + targetTabId);

            if ($targetPane.length) {
                // Remove active class from all tabs and panes
                $('.menu-tab').removeClass('active');
                $('.tab-pane').removeClass('active');

                // Add active class to the target tab and pane
                $('.menu-tab').each(function() {
                    const tabText = $(this).text().toLowerCase().trim();
                    if (tabText === normalizedMenuType ||
                        (tabText === 'daily specials' && normalizedMenuType === 'daily-specials')) {
                        $(this).addClass('active');
                    }
                });

                $targetPane.addClass('active');

                // Reset sidebar active state when switching tabs
                if (normalizedMenuType === 'food') {
                    // Trigger scroll event to set correct active section for food tab
                    setTimeout(function() {
                        $(window).trigger('scroll');
                    }, 100);
                } else {
                    // Remove active states from sidebar links for non-food tabs
                    $('.menu-category-link').removeClass('active');
                }

                // Update arrow states for visible sliders after tab switch
                setTimeout(function() {
                    updateArrowStates();
                }, 200);
            }
        }
    }

    function initImageToggle() {
        // Handle camera icon clicks for image toggle (both regular and personalized)
        $(document).on('click', '.menu-item-camera, .personalized-camera-icon', function(e) {
            e.preventDefault();

            var $camera = $(this);
            var $menuItem = $camera.closest('.menu-item, .personalized-style-item');
            var dishId, dishTitle;

            // Check if this is a personalized camera icon
            if ($camera.hasClass('personalized-camera-icon')) {
                // For personalized items, get data from attributes
                var styleImageId = $camera.data('style-image-id');
                var styleName = $camera.data('style-name');

                if (styleImageId && styleName) {
                    // Handle personalized style image
                    handlePersonalizedStyleImage($camera, styleImageId, styleName);
                }
                return;
            }

            // Check if image is already showing, hide it
            var $existingImage = $menuItem.find('.menu-item-mobile-image');
            if ($existingImage.length > 0) {
                hideItemImage($camera, $existingImage);
                return;
            }

            // Check if camera icon has a direct image ID attribute (for daily specials)
            var itemImageId = $camera.data('item-image-id');

            if (itemImageId) {
                // Show loading state
                $camera.addClass('loading');

                // Load image using attachment method (same as personalized styles)
                loadPersonalizedStyleImage(itemImageId, function(imageData) {
                    $camera.removeClass('loading');

                    if (imageData && imageData.url) {
                        showItemImage($camera, $menuItem, imageData);
                    } else {
                        console.log('No image data returned for image ID:', itemImageId);
                    }
                }, function(error) {
                    $camera.removeClass('loading');
                    console.log('Error loading image:', error);
                });
                return;
            }

            // Regular menu item handling (dish post type)
            dishId = $menuItem.find('.menu-item-add').data('dish-id');
            dishTitle = $menuItem.find('.menu-item-title').text();

            // If no dish ID, can't load image
            if (!dishId) {
                console.log('No dish ID found for image loading');
                return;
            }

            // Show loading state
            $camera.addClass('loading');

            // Load image via AJAX
            loadItemImage(dishId, function(imageData) {
                $camera.removeClass('loading');

                if (imageData && imageData.url) {
                    showItemImage($camera, $menuItem, imageData);
                } else {
                    console.log('No image data returned for dish ID:', dishId);
                }
            }, function(error) {
                $camera.removeClass('loading');
                console.log('Error loading image:', error);
            });
        });
    }

    function handlePersonalizedStyleImage($camera, styleImageId, styleName) {
        // Check if image is already showing for this style
        var $styleItem = $camera.closest('.personalized-style-item');
        var $existingImage = $styleItem.find('.menu-item-mobile-image');
        if ($existingImage.length > 0) {
            hideItemImage($camera, $existingImage);
            return;
        }

        // Show loading state
        $camera.addClass('loading');

        // Load personalized style image
        loadPersonalizedStyleImage(styleImageId, function(imageData) {
            $camera.removeClass('loading');

            if (imageData && imageData.url) {
                showItemImage($camera, $styleItem, imageData);
            } else {
                console.log('No image data returned for style image ID:', styleImageId);
            }
        }, function(error) {
            $camera.removeClass('loading');
            console.log('Error loading style image:', error);
        });
    }

    function loadPersonalizedStyleImage(imageId, successCallback, errorCallback) {
        // Check if we have cached image data
        var cacheKey = 'mrmikes_style_image_' + imageId;
        var cachedData = sessionStorage.getItem(cacheKey);

        if (cachedData) {
            try {
                var imageData = JSON.parse(cachedData);
                if (imageData.url) {
                    successCallback(imageData);
                } else {
                    errorCallback('No image available');
                }
                return;
            } catch (e) {
                // Cache corrupted, continue with request
            }
        }

        // Get image data directly from WordPress attachment
        $.ajax({
            url: mrmikes_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'mrmikes_get_attachment_image',
                image_id: imageId,
                nonce: mrmikes_ajax.nonce
            },
            success: function(response) {
                if (response.success && response.data && response.data.url) {
                    // Cache the result
                    try {
                        sessionStorage.setItem(cacheKey, JSON.stringify(response.data));
                    } catch (e) {
                        // Storage quota exceeded, continue without caching
                    }

                    successCallback(response.data);
                } else {
                    // Cache the "no image" result to avoid repeated requests
                    try {
                        sessionStorage.setItem(cacheKey, JSON.stringify({url: null}));
                    } catch (e) {
                        // Storage quota exceeded, continue without caching
                    }
                    errorCallback(response.data || 'No image available');
                }
            },
            error: function(xhr, status, error) {
                errorCallback(error);
            }
        });
    }

    function loadItemImage(dishId, successCallback, errorCallback) {
        // Check if we have cached image data
        var cacheKey = 'mrmikes_image_' + dishId;
        var cachedData = sessionStorage.getItem(cacheKey);

        if (cachedData) {
            try {
                var imageData = JSON.parse(cachedData);
                if (imageData.url) {
                    successCallback(imageData);
                } else {
                    errorCallback('No image available');
                }
                return;
            } catch (e) {
                // Cache corrupted, continue with AJAX
            }
        }

        // Make AJAX request to get image data
        $.ajax({
            url: mrmikes_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'mrmikes_get_dish_image',
                dish_id: dishId,
                nonce: mrmikes_ajax.nonce
            },
            success: function(response) {
                if (response.success && response.data && response.data.url) {
                    // Cache the result
                    try {
                        sessionStorage.setItem(cacheKey, JSON.stringify(response.data));
                    } catch (e) {
                        // Storage quota exceeded, continue without caching
                    }

                    successCallback(response.data);
                } else {
                    // Cache the "no image" result to avoid repeated requests
                    try {
                        sessionStorage.setItem(cacheKey, JSON.stringify({url: null}));
                    } catch (e) {
                        // Storage quota exceeded, continue without caching
                    }
                    errorCallback(response.data || 'No image available');
                }
            },
            error: function(xhr, status, error) {
                errorCallback(error);
            }
        });
    }

    function showItemImage($camera, $menuItem, imageData) {
        // Change camera icon to "off" state
        var currentSrc = $camera.attr('src');
        var offSrc = currentSrc.replace('camera_icon.png', 'camera_icon_off.png');
        $camera.attr('src', offSrc);

        // Create image element
        var $imageContainer = $('<div class="menu-item-mobile-image"></div>');
        var $image = $('<img>').attr({
            src: imageData.url,
            alt: imageData.alt || 'Dish image',
            loading: 'lazy'
        });

        $imageContainer.append($image);

        // Insert image after the appropriate header
        var $header = $menuItem.find('.menu-item-header, .personalized-style-header');
        if ($header.length > 0) {
            $header.after($imageContainer);
        } else {
            $menuItem.append($imageContainer);
        }

        // Add fade-in animation
        $imageContainer.hide().fadeIn(300);
    }

    function hideItemImage($camera, $existingImage) {
        // Change camera icon back to normal state
        var currentSrc = $camera.attr('src');
        var normalSrc = currentSrc.replace('camera_icon_off.png', 'camera_icon.png');
        $camera.attr('src', normalSrc);

        // Remove image with fade-out animation
        $existingImage.fadeOut(300, function() {
            $(this).remove();
        });
    }

    function initMobileCategorySticky() {
        var $mobileSliders = $('.mobile-category-slider');

        if ($mobileSliders.length === 0) {
            return;
        }

        var stickyPoint = 163;
        var sliderOriginalPositions = new Map();

        // Function to get or calculate original position for a slider
        function getOriginalPosition($slider) {
            var sliderId = $slider.index();

            if (!sliderOriginalPositions.has(sliderId)) {
                // Temporarily show the slider to get its position if it's hidden
                var wasHidden = !$slider.is(':visible');
                if (wasHidden) {
                    var $parentTab = $slider.closest('.tab-pane');
                    $parentTab.css('display', 'block');
                }

                var originalTop = $slider.offset().top;
                sliderOriginalPositions.set(sliderId, originalTop);

                // Hide it back if it was hidden
                if (wasHidden) {
                    var $parentTab = $slider.closest('.tab-pane');
                    $parentTab.css('display', '');
                }
            }

            return sliderOriginalPositions.get(sliderId);
        }

        $(window).on('scroll', function() {
            // Only apply on mobile (below 650px)
            if ($(window).width() <= 649) {
                var scrollTop = $(window).scrollTop();

                // Get the currently visible slider
                var $visibleSlider = $('.mobile-category-slider');

                if ($visibleSlider.length > 0) {
                    var originalTop = getOriginalPosition($visibleSlider);
                    var triggerPoint = originalTop - stickyPoint;

                    if (scrollTop >= triggerPoint) {
                        $visibleSlider.addClass('fixed');
                    } else {
                        $visibleSlider.removeClass('fixed');
                    }
                }
            } else {
                // Remove fixed class on larger screens
                $mobileSliders.removeClass('fixed');
            }
        });

        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 649) {
                $mobileSliders.removeClass('fixed');
            } else {
                // Clear stored positions so they get recalculated
                sliderOriginalPositions.clear();
            }
        });

        // Clear positions when tabs are switched to force recalculation
        $(document).on('click', '.menu-tab', function() {
            sliderOriginalPositions.clear();
            // Remove fixed class from all sliders when switching tabs
            $mobileSliders.removeClass('fixed');
        });
    }

    function initMobileCategorySlider() {
        // Set initial active state for mobile categories
        updateMobileCategoryActive();

        // Handle mobile category clicks
        $(document).on('click', '.mobile-category-link', function(e) {
            e.preventDefault();

            var targetId = $(this).attr('href');
            var $targetSection = $(targetId);

            if ($targetSection.length) {
                // Update active states for the current slider
                var $currentSlider = $(this).closest('.mobile-category-slider');
                $currentSlider.find('.mobile-category-link').removeClass('active');
                $(this).addClass('active');

                // Smooth scroll to section
                $('html, body').animate({
                    scrollTop: $targetSection.offset().top - 180
                }, 500);
            }
        });
    }

    function updateMobileCategoryActive() {
        // Update mobile category active state based on current section
        $(window).on('scroll', function() {
            if ($(window).width() <= 649) {
                var scrollTop = $(window).scrollTop();
                var currentSection = '';

                $('.menu-section').each(function() {
                    var $section = $(this);
                    var sectionTop = $section.offset().top - 150;
                    var sectionBottom = sectionTop + $section.outerHeight();

                    if (scrollTop >= sectionTop && scrollTop < sectionBottom) {
                        currentSection = $section.attr('id');
                        return false;
                    }
                });

                if (currentSection) {
                    // Determine which tab we're in based on the section ID
                    var isInDrinks = currentSection.includes('-drinks');
                    var isInSpecials = ['happiest-hours', 'daily-specials', 'thursdays-lodge-night'].includes(currentSection);

                    var $activeSlider;
                    if (isInDrinks) {
                        $activeSlider = $('.mobile-category-slider').eq(1); // Drinks slider (second one)
                    } else if (isInSpecials) {
                        $activeSlider = $('.mobile-category-slider').eq(2); // Specials slider (third one)
                    } else {
                        $activeSlider = $('.mobile-category-slider').eq(0); // Food slider (first one)
                    }

                    if ($activeSlider.length > 0) {
                        var $currentActiveLink = $activeSlider.find('.mobile-category-link[data-category="' + currentSection + '"]');

                        // Only update if this is a different category
                        if ($currentActiveLink.length > 0 && !$currentActiveLink.hasClass('active')) {
                            $activeSlider.find('.mobile-category-link').removeClass('active');
                            $currentActiveLink.addClass('active');

                            // Auto-scroll slider to show active category
                            scrollToActiveCategory($currentActiveLink, $activeSlider);
                        }
                    }
                }
            }
        });
    }

    function scrollToActiveCategory($activeLink, $activeSlider) {
        if ($activeLink.length === 0 || $activeSlider.length === 0) return;

        var $sliderTrack = $activeSlider.find('.mobile-slider-track');
        if ($sliderTrack.length === 0) return;

        var $activeItem = $activeLink.closest('.mobile-category-item');

        // Use native DOM methods for more reliable positioning on mobile Chrome
        var track = $sliderTrack[0];
        var activeItem = $activeItem[0];

        if (!activeItem) return;

        // Get positions using getBoundingClientRect for better mobile Chrome compatibility
        var trackRect = track.getBoundingClientRect();
        var itemRect = activeItem.getBoundingClientRect();

        // Calculate relative position
        var itemOffsetLeft = itemRect.left - trackRect.left + track.scrollLeft;
        var itemWidth = itemRect.width;
        var trackWidth = trackRect.width;
        var currentScrollLeft = track.scrollLeft;

        // Calculate if item is outside visible area
        var itemLeftEdge = itemOffsetLeft - currentScrollLeft;
        var itemRightEdge = itemLeftEdge + itemWidth;

        var newScrollLeft = currentScrollLeft;
        var padding = 20;

        // If item is cut off on the left
        if (itemLeftEdge < padding) {
            newScrollLeft = itemOffsetLeft - padding;
        }
        // If item is cut off on the right
        else if (itemRightEdge > (trackWidth - padding)) {
            newScrollLeft = itemOffsetLeft - trackWidth + itemWidth + padding;
        }

        // Ensure we don't scroll beyond boundaries
        newScrollLeft = Math.max(0, Math.min(newScrollLeft, track.scrollWidth - track.clientWidth));

        // Use native scrollTo for better mobile Chrome support
        if (Math.abs(newScrollLeft - currentScrollLeft) > 5) { // Only scroll if significant difference
            // Try smooth scrolling first (supported in newer browsers)
            if (track.scrollTo && 'behavior' in document.documentElement.style) {
                track.scrollTo({
                    left: newScrollLeft,
                    behavior: 'smooth'
                });
            } else {
                // Fallback to jQuery animation for older browsers
                $sliderTrack.animate({
                    scrollLeft: newScrollLeft
                }, 300);
            }

            // Update arrow states after scrolling for this specific slider
            setTimeout(function() {
                updateArrowStates($activeSlider);
            }, 350);
        }
    }

    // Global function for slider arrows - now accepts optional slider parameter
    window.slideCategories = function(direction, sliderElement) {
        var track;

        if (sliderElement) {
            // If specific slider element is passed
            track = $(sliderElement).closest('.mobile-category-slider').find('.mobile-slider-track')[0];
        } else {
            // Find the currently visible slider track
            var $visibleSlider = $('.mobile-category-slider').filter(':visible');
            if ($visibleSlider.length > 0) {
                track = $visibleSlider.find('.mobile-slider-track')[0];
            }
        }

        if (!track) return;

        var scrollAmount = 200;

        if (direction === 'prev') {
            track.scrollLeft -= scrollAmount;
        } else {
            track.scrollLeft += scrollAmount;
        }

        // Update arrow states for the specific slider
        setTimeout(function() {
            var $slider = $(track).closest('.mobile-category-slider');
            updateArrowStates($slider);
        }, 100);
    };

    // Specific function for food categories
    window.slideFoodCategories = function(direction) {
        var track = document.getElementById('mobile-categories-track');
        if (!track) return;

        var scrollAmount = 200;

        if (direction === 'prev') {
            track.scrollLeft -= scrollAmount;
        } else {
            track.scrollLeft += scrollAmount;
        }

        // Update arrow states for food slider
        setTimeout(function() {
            var $slider = $(track).closest('.mobile-category-slider');
            updateArrowStates($slider);
        }, 100);
    };

    // Specific function for drinks categories
    window.slideDrinksCategories = function(direction) {
        var track = document.getElementById('mobile-categories-track-drinks');
        if (!track) return;

        var scrollAmount = 200;

        if (direction === 'prev') {
            track.scrollLeft -= scrollAmount;
        } else {
            track.scrollLeft += scrollAmount;
        }

        // Update arrow states for drinks slider
        setTimeout(function() {
            var $slider = $(track).closest('.mobile-category-slider');
            updateArrowStates($slider);
        }, 100);
    };

    // Specific function for daily specials categories
    window.slideSpecialsCategories = function(direction) {
        var track = document.getElementById('mobile-categories-track-specials');
        if (!track) return;

        var scrollAmount = 200;

        if (direction === 'prev') {
            track.scrollLeft -= scrollAmount;
        } else {
            track.scrollLeft += scrollAmount;
        }

        // Update arrow states for specials slider
        setTimeout(function() {
            var $slider = $(track).closest('.mobile-category-slider');
            updateArrowStates($slider);
        }, 100);
    };

    function updateArrowStates($slider) {
        if (!$slider || $slider.length === 0) {
            // If no specific slider provided, update all visible sliders
            $('.mobile-category-slider:visible').each(function() {
                updateArrowStates($(this));
            });
            return;
        }

        var track = $slider.find('.mobile-slider-track')[0];
        var prevBtn = $slider.find('.mobile-slider-arrow.prev')[0];
        var nextBtn = $slider.find('.mobile-slider-arrow.next')[0];

        if (track && prevBtn && nextBtn) {
            prevBtn.disabled = track.scrollLeft <= 0;
            nextBtn.disabled = track.scrollLeft >= (track.scrollWidth - track.clientWidth);
        }
    }

    function initStickySidebar() {
        var $sidebar = $('.menu-sidebar');

        if ($sidebar.length === 0) {
            return;
        }

        $(window).on('scroll', function() {
            var scrollTop = $(window).scrollTop();

            if (scrollTop >= 214) {
                $sidebar.addClass('fixed');
                $(".menu-tabs-section").addClass("menu-tabs-section-fixed");
            } else {
                $sidebar.removeClass('fixed');
                $(".menu-tabs-section").removeClass("menu-tabs-section-fixed");
            }
        });
    }

    function initActiveSection() {
        var $menuLinks = $('.menu-category-link');
        var $menuSections = $('.menu-section, .features-section'); // Include features section

        if ($menuLinks.length === 0) {
            return;
        }

        // Handle click events on menu links
        $menuLinks.on('click', function(e) {
            e.preventDefault();

            var targetId = $(this).attr('href');
            var $targetSection = $(targetId);

            if ($targetSection.length) {
                // Remove active class from all links
                $menuLinks.removeClass('active');

                // Add active class to clicked link
                $(this).addClass('active');

                // Smooth scroll to section
                $('html, body').animate({
                    scrollTop: $targetSection.offset().top - 100
                }, 500);
            }
        });

        // Handle scroll-based active section detection
        $(window).on('scroll', function() {
            var scrollTop = $(window).scrollTop();
            var windowHeight = $(window).height();
            var currentSection = '';

            // Check all sections including features
            $menuSections.each(function() {
                var $section = $(this);
                var sectionTop = $section.offset().top - 150;
                var sectionBottom = sectionTop + $section.outerHeight();

                if (scrollTop >= sectionTop && scrollTop < sectionBottom) {
                    currentSection = $section.attr('id');
                    return false; // Break out of loop
                }
            });

            // Update active link based on current section
            if (currentSection) {
                $menuLinks.removeClass('active');
                $menuLinks.filter('[href="#' + currentSection + '"]').addClass('active');
            }
        });

        // Set initial active section
        $(window).trigger('scroll');
    }

    function initTabSwitching() {
        var $menuTabs = $('.menu-tab');
        var $tabPanes = $('.tab-pane');

        if ($menuTabs.length === 0 || $tabPanes.length === 0) {
            return;
        }

        // Handle tab click events (including touch events for mobile)
        $menuTabs.on('click touchend', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var tabText = $(this).text().toLowerCase().trim().replace(/\s+/g, '-');
            var targetTabId = tabText + '-tab';
            var $targetPane = $('#' + targetTabId);

            // Debug logging
            console.log('Tab clicked:', tabText);
            console.log('Target ID:', targetTabId);
            console.log('Target pane found:', $targetPane.length > 0);

            if ($targetPane.length) {
                // Remove active class from all tabs and panes
                $menuTabs.removeClass('active');
                $tabPanes.removeClass('active');

                // Add active class to clicked tab and corresponding pane
                $(this).addClass('active');
                $targetPane.addClass('active');

                // Reset sidebar active state when switching tabs
                if (tabText === 'food') {
                    // Trigger scroll event to set correct active section for food tab
                    setTimeout(function() {
                        $(window).trigger('scroll');
                    }, 100);
                } else {
                    // Remove active states from sidebar links for non-food tabs
                    $('.menu-category-link').removeClass('active');
                }

                // Update arrow states for visible sliders after tab switch
                setTimeout(function() {
                    updateArrowStates();
                }, 200);
            } else {
                console.error('Tab pane not found for ID:', targetTabId);
            }
        });
    }

})(jQuery);