(function($) {
    $(document).ready(function() {
        //refresh captcha
        $("body").on("click", ".refresh-captcha", function() {
            refreshCaptcha($(this).find(".captach-image"), $(this).attr('href'));
            return false;
        });
        function refreshCaptcha(img, url) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function(datas) {
                    if (datas.imageUrl && img !== "undefined")
                        img.attr("src", datas.imageUrl + '/' + Math.floor(Math.random() * 100));
                }
            });
        }

        //refresh audio captcha
        $("body").on("click", ".play-captcha", function() {
            $(this).find(".captach-audio").remove();
            if ($.browser.msie) {
                $(this).append('<embed src="' + $(this).attr('href') + '/' + Math.floor(Math.random() * 100) + '" hidden="true" class="captach-audio">').appendTo('body');
            } else {
                $(this).append('<audio src="' + $(this).attr('href') + '/' + Math.floor(Math.random() * 100) + '" hidden="true" autoplay="true" class="captach-audio"></audio>');
            }

            return false;
        });

        // language updater
        $('.updateLanguage').click(function() {
            var language = $(this).attr('id');
            if (language === $('html').attr("lang"))
                return false;
            $.ajax({
                type: 'GET',
                url: urls['language'] + '/' + language,
                dataType: 'json',
                success: function(datas) {
                    if (datas.updated === true)
                        window.location.replace(urls['index']);
                }
            });
            return false;
        });

        /* REQUEST BACKOFFICE CONSOLE */
        $('body').keydown(function(event) {
            if (event.keyCode === 120)
                window.location.replace(urls['backoffice']);
        });

        // jQuery for page scrolling feature - requires jQuery Easing plugin
        $('.page-scroll a').bind('click', function(event) {
            var anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });
        $(window).scroll(function() {
            if ($(this).scrollTop() < 100)
                $('.nicescroll-rails').addClass('hide');
            else
                $('.nicescroll-rails').removeClass('hide');

            if ($(this).scrollTop() > 400)
                $('#scrolltop').removeClass('hide');
            else
                $('#scrolltop').addClass('hide');
        });
        $("html").niceScroll({styler: "fb", cursorcolor: "#2c3e50"});
        $('.nicescroll-rails').addClass('hide');

        // Highlight the top nav as scrolling occurs
        $('body').scrollspy({
            target: '.navbar-fixed-top'
        });
        // Closes the Responsive Menu on Menu Item Click
        $('.navbar-collapse ul li a').click(function() {
            $('.navbar-toggle:visible').click();
        });
    });


})(jQuery);



