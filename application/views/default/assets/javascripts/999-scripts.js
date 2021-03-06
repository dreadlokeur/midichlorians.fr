(function ($) {
    $(document).ready(function () {
        //refresh captcha
        $("body").on("click", ".refresh-captcha", function () {
            refreshCaptcha($(this).find(".captach-image"), $(this).attr('href'));
            return false;
        });
        function refreshCaptcha(img, url) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                success: function (datas) {
                    if (datas.imageUrl && img !== "undefined")
                        img.attr("src", datas.imageUrl + '/' + Math.floor(Math.random() * 100));
                }
            });
        }

        //refresh audio captcha
        $("body").on("click", ".play-captcha", function () {
            $(this).find(".captach-audio").remove();
            if ($.browser.msie) {
                $(this).append('<embed src="' + $(this).attr('href') + '/' + Math.floor(Math.random() * 100) + '" hidden="true" class="captach-audio">').appendTo('body');
            } else {
                $(this).append('<audio src="' + $(this).attr('href') + '/' + Math.floor(Math.random() * 100) + '" hidden="true" autoplay="true" class="captach-audio"></audio>');
            }

            return false;
        });

        // language updater
        $('.updateLanguage').click(function () {
            var language = $(this).attr('id');
            if (language === $('html').attr("lang"))
                return false;
            $.ajax({
                type: 'GET',
                url: urls['language'] + '/' + language,
                dataType: 'json',
                success: function (datas) {
                    if (datas.notifySuccess !== null)
                        window.location.replace(urls['index']);
                }
            });
            return false;
        });

        /* REQUEST BACKOFFICE CONSOLE */
        $('body').keydown(function (event) {
            if (event.keyCode === 120)
                window.location.replace(urls['backoffice']);
        });

        // jQuery for page scrolling feature - requires jQuery Easing plugin
        $('.page-scroll a').bind('click', function (event) {
            var anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });
        $(window).scroll(function () {
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
        $('.navbar-collapse ul li a').click(function () {
            $('.navbar-toggle:visible').click();
        });
        // jPages plugin
        if ($('div.holder').length > 0) {
            $("div.holder").jPages({
                perPage: 6,
                containerID: "portfolio-row",
                animation: "bounceIn",
                previous: 'i.jPagesPrevious',
                next: 'i.jPagesNext',
                first: false,
                last: false,
            });
        }
        // bootsrap lightbox
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
            event.preventDefault();
            return $(this).ekkoLightbox();
        });

        // github timeline
        if ($('#github-graph').length > 0) {
            $.ajax({
                type: 'GET',
                url: urls['github'],
                dataType: "json",
                success: function (datas) {
                    $('#github-commit').find('span.count').html(datas.commitsCount);
                    $('#github-repo').find('span.count').html(datas.reposteriesCount);
                    $('#github-fork').find('span.count').html(datas.forksCount);
                    var s = new sigma({
                        graph: datas.graph,
                        container: 'github-graph',
                        settings: {
                            edgeColor: 'default',
                            defaultEdgeColor: '#18bc9c',
                            nodeColor: 'default',
                            defaultNodeColor: '#2c3e50',
                        }
                    });
                }
            });
        }
    });


})(jQuery);



