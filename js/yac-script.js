jQuery(function ($) {

    var ajaxStatus = false;

    var yacPicture = $('.yac-picture img');
    var yacCaptchaCode = $('#captcha-code');
    var yacCaptchaInput = $('#captcha-input');


    var refreshButton = $('.refresh-button', 'div.yac-container');
    var yacIcons = $('.yac-icon', 'div.yac-container');


    refreshButton.on('click', function (event) {

        // prevent submit form event
        event.preventDefault();

        if (ajaxStatus == true)
            return false;

        ajaxStatus = true;

        yacIcons.toggleClass('hidden');

        var data = {
            'action': 'get_captcha_ajax'
        };

        $.post(ajaxurl, data, function (response) {

            var yacResponse = $.parseJSON(response);

            yacPicture.attr('src', yacResponse.url);

            yacCaptchaCode.attr('value', yacResponse.id);
            yacCaptchaInput.attr('value', '');

            ajaxStatus = false;

            yacIcons.toggleClass('hidden');

        });
    });

});