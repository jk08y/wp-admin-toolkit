jQuery(document).ready(function($) {
    // Initialize color pickers
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.wpatk-color-picker').wpColorPicker({
            change: function(event, ui) {
                $(event.target).trigger('change');
            }
        });
    }

    // Media uploader for images
    $('.wpatk-upload-button').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = $(button.data('target'));
        var previewImg = $(button.data('preview'));
        
        var frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            targetInput.val(attachment.url).trigger('change');
            
            if (previewImg.length) {
                previewImg.attr('src', attachment.url).addClass('has-image');
            }
        });

        frame.open();
    });

    // Remove image button
    $('.wpatk-remove-image').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = $(button.data('target'));
        var previewImg = $(button.data('preview'));
        
        targetInput.val('');
        if (previewImg.length) {
            previewImg.attr('src', '').removeClass('has-image');
        }
    });

    // Initialize tabs
    function initTabs() {
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tab-wrapper a[href="' + hash + '"]').click();
        }
        
        $('.nav-tab-wrapper a').on('click', function(e) {
            e.preventDefault();
            var tab = $(this);
            
            // Update URL hash without scrolling
            history.pushState(null, null, tab.attr('href'));
            
            // Update active states
            $('.nav-tab-wrapper a').removeClass('nav-tab-active');
            tab.addClass('nav-tab-active');
            
            // Show selected content
            $('.wpatk-tab-content').hide();
            $(tab.attr('href')).show();
        });
    }
    
    initTabs();

    // Form validation
    $('#wpatk-settings-form').on('submit', function(e) {
        var emailInput = $('#wpatk_email_from');
        if (emailInput.length && emailInput.val()) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.val())) {
                e.preventDefault();
                alert('Please enter a valid email address');
                emailInput.focus();
            }
        }
    });

    // Save settings with AJAX
    var saveTimeout;
    $('.wpatk-live-update').on('change', function() {
        clearTimeout(saveTimeout);
        var input = $(this);
        var originalValue = input.data('original');
        
        saveTimeout = setTimeout(function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'wpatk_save_setting',
                    nonce: wpatkSettings.nonce,
                    option: input.attr('name'),
                    value: input.val()
                },
                success: function(response) {
                    if (response.success) {
                        input.data('original', input.val());
                    } else {
                        input.val(originalValue);
                        alert('Failed to save setting');
                    }
                },
                error: function() {
                    input.val(originalValue);
                    alert('Failed to save setting');
                }
            });
        }, 1000);
    });
});