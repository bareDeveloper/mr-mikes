/**
 * MrMikes Location Popup JavaScript
 */

(function($) {
    'use strict';

    // Variable to track popup trigger source
    var popupTriggerSource = 'default';

    $(document).ready(function() {
        createPopupHTML();
        initLocationPopup();

        // Capture clicks on "Locations" links
        $(document).on('click', 'a:contains("Locations")', function(e) {
            e.preventDefault();
            popupTriggerSource = 'locations-link';
            openLocationPopup($(this));
        });

        // Capture clicks on "Menus" links
        $(document).on('click', 'a:contains("Menus")', function(e) {
            e.preventDefault();

            // Check if location is selected
            var locationId = getCookie('mrmikes_selected_location');

            if (!locationId) {
                // No location selected, show popup
                openLocationPopup($(this));
            } else {
                // Location selected, redirect to menu URL using full slug
                if (typeof mrmikes_vars !== 'undefined' && mrmikes_vars.location_slugs && mrmikes_vars.location_slugs[locationId]) {
                    var locationSlug = mrmikes_vars.location_slugs[locationId];
                    var menuUrl = '/locations/' + locationSlug + '/menus/food/';
                    window.location.href = menuUrl;
                } else {
                    // Fallback: show popup if we can't build URL
                    openLocationPopup($(this));
                }
            }
        });
    });

    function createPopupHTML() {
        // Check if popup already exists
        if ($('#mrmikes-location-popup').length > 0) {
            return;
        }

        // Build provinces list HTML
        var provincesListHTML = '';
        if (typeof mrmikes_vars !== 'undefined' && mrmikes_vars.provinces_data) {
            var provincesData = mrmikes_vars.provinces_data;

            if (Object.keys(provincesData).length === 0) {
                provincesListHTML = '<p>' + mrmikes_vars.strings.no_locations + '</p>';
            } else {
                provincesListHTML = '<div class="mrmikes-provinces-accordion">';

                for (var province in provincesData) {
                    if (provincesData.hasOwnProperty(province) && province) {
                        provincesListHTML += '<div class="mrmikes-province-section">';

                        // Province header
                        provincesListHTML += '<div class="mrmikes-province-header" data-province="' + province + '">';
                        provincesListHTML += '<span class="province-name">' + province + '</span>';
                        provincesListHTML += '<img src="' + mrmikes_vars.plugin_url + 'assets/img/dropdown_arrow.png" alt="" class="dropdown-icon" />';
                        provincesListHTML += '</div>';

                        // Locations list
                        provincesListHTML += '<div class="mrmikes-locations-list" style="display: none;">';
                        var locations = provincesData[province];
                        for (var i = 0; i < locations.length; i++) {
                            var location = locations[i];
                            provincesListHTML += '<div class="mrmikes-location-item">';
                            provincesListHTML += '<a href="#" class="mrmikes-location-link" data-location-id="' + location.id + '" data-location-slug="' + location.slug + '" data-location-permalink="' + location.permalink + '">';
                            provincesListHTML += '<span class="location-city">' + location.name + '</span>';
                            if(location.location_status == "opening_soon") {
                                provincesListHTML += '<span class="location-status">Opening Soon</span>';
                            }
                            if(location.location_status == "now_open") {
                                provincesListHTML += '<span class="location-status">Now Open</span>';
                            }
                            if (location.street) {
                                provincesListHTML += '<span class="location-street">' + location.street + '</span>';
                            }
                            provincesListHTML += '</a>';
                            provincesListHTML += '</div>';
                        }
                        provincesListHTML += '</div>';

                        provincesListHTML += '</div>';
                    }
                }

                provincesListHTML += '</div>';
            }
        }

        // Create the complete popup HTML
        var popupHTML = '' +
            '<div id="mrmikes-location-popup" class="mrmikes-location-popup">' +
                '<div class="mrmikes-popup-content">' +
                    '<div class="mrmikes-popup-location-selector">' +
                        '<img src="' + mrmikes_vars.plugin_url + 'assets/img/mrm-location-pin-white.svg" alt="Location pin" class="mrmikes-popup-location-pin">' +
                        '<a href="#" class="mrmikes-popup-location-trigger">' + mrmikes_vars.strings.set_location + '</a>' +
                    '</div>' +
                    '<div class="mrmikes-search-container">' +
                        '<input type="text" id="mrmikes-location-search" class="mrmikes-search-input" placeholder="' + mrmikes_vars.strings.search_placeholder + '">' +
                        '<span class="mrmikes-search-clear" style="display: none;">&times;</span>' +
                    '</div>' +
                    '<img src="' + mrmikes_vars.plugin_url + 'assets/img/close.png" alt="Close" class="mrmikes-popup-close-icon">' +
                    provincesListHTML +
                    '<img src="' + mrmikes_vars.plugin_url + 'assets/img/seal.png" alt="Seal" class="mrmikes-popup-seal">' +
                '</div>' +
            '</div>';

        // Append popup to body
        $('body').append(popupHTML);
    }

    function initLocationPopup() {
        // Create overlay element
        if (!$('.mrmikes-popup-overlay').length) {
            $('body').append('<div class="mrmikes-popup-overlay"></div>');
        }

        // Open popup when trigger is clicked
        $(document).on('click', '.mrmikes-location-trigger', function(e) {
            e.preventDefault();
            openLocationPopup($(this));
        });

        // Close popup when close icon is clicked
        $(document).on('click', '.mrmikes-popup-close-icon', function(e) {
            e.preventDefault();
            closeLocationPopup();
        });

        // Close popup when overlay is clicked
        $(document).on('click', '.mrmikes-popup-overlay', function() {
            closeLocationPopup();
        });

        // Close popup when escape key is pressed
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $('.mrmikes-location-popup').hasClass('active')) {
                closeLocationPopup();
            }
        });

        // Handle accordion functionality
        $(document).on('click', '.mrmikes-province-header', function() {
            var $header = $(this);
            var $section = $header.closest('.mrmikes-province-section');
            var $locationsList = $section.find('.mrmikes-locations-list');
            var isCurrentlyActive = $header.hasClass('active');

            // Close all other sections
            $('.mrmikes-province-header').removeClass('active');
            $('.mrmikes-locations-list').slideUp(300);

            // If this section wasn't active, open it
            if (!isCurrentlyActive) {
                $header.addClass('active');
                $locationsList.slideDown(300);
            }
        });

        // Handle location selection
        $(document).on('click', '.mrmikes-location-link', function(e) {
            e.preventDefault();
            var locationId = $(this).data('location-id');
            var locationSlug = $(this).data('location-slug');
            var locationPermalink = $(this).data('location-permalink');
            var locationName = $(this).text();

            // Set cookie with location ID (never expires)
            var expirationDate = new Date();
            expirationDate.setTime(expirationDate.getTime() + (365 * 10 * 24 * 60 * 60 * 1000)); // 10 years
            document.cookie = 'mrmikes_selected_location=' + locationId + '; expires=' + expirationDate.toUTCString() + '; path=/';

            console.log('Selected location:', locationName, 'ID:', locationId);
            console.log('Cookie set: mrmikes_selected_location=' + locationId);
            console.log('Popup trigger source:', popupTriggerSource);

            // Close the popup
            closeLocationPopup();

            // Check trigger source for redirect behavior
            if (popupTriggerSource === 'locations-link') {
                // Redirect to location permalink (individual location page)
                window.location.href = locationPermalink;
            } else {
                // Default behavior: redirect to menu URL using the new format
                if (locationSlug) {
                    var newUrl = '/locations/' + locationSlug + '/menus/food/';
                    console.log('Redirecting to:', newUrl);
                    window.location.href = newUrl;
                } else {
                    // Fallback: redirect to menu page if URL is available
                    if (typeof mrmikes_vars !== 'undefined' && mrmikes_vars.menu_page_url) {
                        window.location.href = mrmikes_vars.menu_page_url;
                    }
                }
            }

            // Reset the popup trigger source
            popupTriggerSource = 'default';
        });

        // Handle search functionality
        $(document).on('input', '#mrmikes-location-search', function() {
            var searchTerm = $(this).val().toLowerCase().trim();
            var $clearButton = $('.mrmikes-search-clear');

            // Show/hide clear button
            if (searchTerm === '') {
                $clearButton.hide();
                // Show all provinces and locations, collapse all
                $('.mrmikes-province-section').show();
                $('.mrmikes-location-item').show();
                $('.mrmikes-province-header').removeClass('active');
                $('.mrmikes-locations-list').hide();
            } else {
                $clearButton.show();
                // Hide all provinces initially
                $('.mrmikes-province-section').hide();
                $('.mrmikes-location-item').hide();
                $('.mrmikes-province-header').removeClass('active');
                $('.mrmikes-locations-list').hide();

                // Search through location names
                $('.mrmikes-location-link').each(function() {
                    var locationName = $(this).text().toLowerCase();
                    if (locationName.indexOf(searchTerm) !== -1) {
                        var $locationItem = $(this).closest('.mrmikes-location-item');
                        var $provinceSection = $locationItem.closest('.mrmikes-province-section');
                        var $locationsList = $provinceSection.find('.mrmikes-locations-list');
                        var $provinceHeader = $provinceSection.find('.mrmikes-province-header');

                        // Show the matching location and its province
                        $locationItem.show();
                        $provinceSection.show();
                        $provinceHeader.addClass('active');
                        $locationsList.show();
                    }
                });
            }
        });

        // Handle clear search button
        $(document).on('click', '.mrmikes-search-clear', function() {
            $('#mrmikes-location-search').val('').trigger('input');
        });
    }

    function openLocationPopup($trigger) {
        var $popup = $('.mrmikes-location-popup');

        // Always use the original shortcode trigger for positioning
        var $originalTrigger = $('.mrmikes-location-trigger').first();

        // Position the popup horizontally based on the original trigger's position
        positionPopup($originalTrigger, $popup);

        $popup.addClass('active');
        $('.mrmikes-popup-overlay').addClass('active');
        $('body').addClass('mrmikes-popup-open');

        // Prevent body scroll
        $('body').css('overflow', 'hidden');
    }

    function positionPopup($trigger, $popup) {
        // Only position horizontally on desktop (above 1023px)
        if ($(window).width() > 1023) {
            var triggerOffset = $trigger.offset();
            var triggerWidth = $trigger.outerWidth();
            var popupWidth = 360; // Fixed popup width from CSS
            var windowWidth = $(window).width();

            // Calculate desired left position (centered under trigger)
            var desiredLeft = triggerOffset.left + (triggerWidth / 2) - (popupWidth / 2);

            // Ensure popup doesn't go off-screen
            var minLeft = 20; // 20px margin from left edge
            var maxLeft = windowWidth - popupWidth - 20; // 20px margin from right edge

            var finalLeft = Math.max(minLeft, Math.min(desiredLeft, maxLeft));

            // Apply the horizontal position
            $popup.css('left', finalLeft + 'px');
        } else {
            // On mobile, ensure it's centered
            $popup.css('left', '0');
        }
    }

    function closeLocationPopup() {
        $('.mrmikes-location-popup').removeClass('active');
        $('.mrmikes-popup-overlay').removeClass('active');
        $('body').removeClass('mrmikes-popup-open');

        // Restore body scroll
        $('body').css('overflow', '');

        // Collapse all province sections
        $('.mrmikes-province-header').removeClass('active');
        $('.mrmikes-locations-list').hide();

        // Clear search and reset visibility
        $('#mrmikes-location-search').val('');
        $('.mrmikes-search-clear').hide();
        $('.mrmikes-province-section').show();
        $('.mrmikes-location-item').show();
    }

    // Handle window resize to reposition popup if it's open
    $(window).on('resize', function() {
        var $popup = $('.mrmikes-location-popup');
        if ($popup.hasClass('active')) {
            // Find the currently clicked trigger (we'll need to store this reference)
            var $activeTrigger = $('.mrmikes-location-trigger').first(); // Fallback to first trigger
            positionPopup($activeTrigger, $popup);
        }
    });

    // Helper function to get cookie value
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

})(jQuery);