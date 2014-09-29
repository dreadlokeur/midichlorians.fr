(function ($) {
    $(document).ready(function () {
        /* PUBLIC VARS */
        var defaultInputs = {};
        var historyApi = true;
        var historyState = false;
        var datatables = [];
        var uploadUpdate = false;

        /* INIT */
        init();

        if (typeof history.pushState === 'undefined')
            historyApi = false;
        if (historyApi) {
            window.onpopstate = function (event) {
                if (historyState) {
                    var url = event.state === null ? urls['backoffice'] : document.location;
                    ajaxSwitch(url);
                }
            }
        }

        // login background
        if ($('#login').length > 0)
            $.backstretch(urls['img'] + "login-bg.jpg", {speed: 500});

        // custom scrollbar
        $("#sidebar").niceScroll({styler: "fb", cursorcolor: "#18bc9c", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled: false, cursorborder: ''});
        $("html").niceScroll({styler: "fb", cursorcolor: "#18bc9c", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled: false, cursorborder: '', zindex: '99999'});

        // --------------------------------------------EVENTS-------------------------------------------//
        //refresh captcha
        $("body").on("click", ".refresh-captcha", function () {
            refreshCaptcha($(this).find(".captach-image"), $(this).attr('href'));
            return false;
        });


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
                    if (datas.updated === true)
                        window.location.replace(urls['index']);
                }
            });
            return false;
        });


        // jQuery for page scrolling feature - requires jQuery Easing plugin
        $('.page-scroll a').bind('click', function (event) {
            var anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });

        // sidebar dropdown menu auto scrolling
        $('#sidebar .sub-menu > a').click(function () {
            var o = ($(this).offset());
            diff = 250 - o.top;
            if (diff > 0)
                $("#sidebar").scrollTo("-=" + Math.abs(diff), 500);
            else
                $("#sidebar").scrollTo("+=" + Math.abs(diff), 500);
        });
        // sidebar toggle
        $(window).on('load', responsiveView());
        $(window).on('resize', responsiveView());
        $('.fa-bars').click(function () {
            if ($('#sidebar > ul').is(":visible") === true) {
                $('#main-content').css({
                    'margin-left': '0px'
                });
                $('#sidebar').css({
                    'margin-left': '-210px'
                });
                $('#sidebar > ul').hide();
                $("#container").addClass("sidebar-closed");
            } else {
                $('#main-content').css({
                    'margin-left': '210px'
                });
                $('#sidebar > ul').show();
                $('#sidebar').css({
                    'margin-left': '0'
                });
                $("#container").removeClass("sidebar-closed");
            }
        });

        // LEFT BAR ACCORDION
        $('#nav-accordion').dcAccordion({
            eventType: 'click',
            autoClose: true,
            saveState: true,
            disableLink: true,
            speed: 'slow',
            showCount: false,
            autoExpand: true,
            //        cookie: 'dcjq-accordion-1',
            classExpand: 'dcjq-current-parent'
        });


        /* SUBMIT LOGIN */
        $("body").on("submit", "#login", function () {
            $("#login-loader").addClass('show');
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: getInputsValues(),
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    //reload
                    if (typeof (datas.success) !== "undefined" && datas.success !== null)
                        location.reload();
                    else {
                        $('#login').wiggle('start', {
                            wiggleDegrees: ['1', '2', '1', '0', '-1', '-2', '-1', '0'],
                            delay: 10,
                            limit: 4,
                            randomStart: false
                        });

                        $('.form-group').addClass('has-error');
                        //update token
                        $('input#backoffice-token').val(datas.token);
                    }
                    $("#login-loader").removeClass('show');
                }
            });
            return false;
        });

        /* LOGOUT */
        $("body").on("click", "#logout", function () {
            $.ajax({
                type: 'POST',
                url: $(this).attr('href'),
                data: getInputsValues(),
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    //reload
                    if (typeof (datas.success) !== "undefined" && datas.success !== null)
                        location.reload();
                    else
                        $('input#backoffice-token').val(datas.token);
                }
            });
            return false;
        });

        /* AJAX SWITCHER */
        $("body").on("click", ".ajax-switcher", function (event) {
            clickSwitch($(this).attr('href'), event);
        });

        /* SELECT ALL */
        $("body").on('click', "input[name='selectAll']", function () {
            var tableId = $(this).parent().parents("table").attr('id');
            $('#' + tableId).find('input:checkbox').not(this).prop('checked', this.checked);
        });

        /* CLICK DELETE LINK */
        $("body").on("click", ".delete", function () {
            remove($(this).attr('href'), $(this).parent().parents("table").attr('id'));
            return false;
        });
        $("body").on("click", ".deleteAll", function () {
            var tableId = $(this).parent().find('table').attr('id');
            var urlsList = [];
            $("body").find(':input').each(function () {
                if ($(this).hasClass('deleteCheckbox')) {
                    if (this.type === 'checkbox') {
                        if ($(this).is(':checked')) {
                            urlsList.push($(this).parent().parent().find('td:last').find('a.delete').attr('href'));
                        }

                    }
                }
            });

            if (urlsList.length !== 0) {
                var url = urlsList[0];
                urlsList.splice(0, 1);
                remove(url, tableId, urlsList);
            }
            return false;
        });




        /* CLICK ADD LINK */
        $("body").on("click", ".add", function () {
            var tableId = $(this).parent().parents("table").attr('id');
            var inputs = getInputsValues();
            var url = $(this).attr('href');
            add(tableId, inputs, url);
            return false;
        });



        // --------------------------------------------FUNCTIONS-------------------------------------------//
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
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

        function clickSwitch(url, event) {
            if ($('#ajax-content').length > 0) {
                event.preventDefault();
                ajaxSwitch(url);
                if (historyApi) {
                    history.pushState('', '', url);
                    historyState = true;
                }

                return false;
            }
        }
        function ajaxSwitch(url) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    $('#ajax-content').fadeOut('slow');
                    $("#global-loader").addClass('show').delay(800).queue(function () {
                        $('#ajax-content').html(datas.content).addClass('hide').fadeIn('slow').removeClass('hide');
                        $('title').html(datas.title);
                        init();
                        $("#global-loader").removeClass('show');
                        //update token
                        $('input#backoffice-token').val(datas.token);
                        $(this).dequeue();
                    });
                }
            });
        }
        function init() {
            //inputs/textearea forms
            setDefaultInputs();

            //sidebar active link, and accordeon
            $('ul#nav-accordion').children('li').each(function () {
                var li = $(this);
                if (li.has('a').children('a')) {
                    var liAChildren = li.children('a');
                    liAChildren.removeClass('active');
                    if (liAChildren.attr('href') === window.location.href)
                        liAChildren.addClass('active');
                }
                $(this).has('ul').children('ul').find('li').each(function () {
                    $(this).removeClass('active');
                    if ($(this).has('a').children('a')) {
                        $(this).has('a').children('a').removeClass('active');
                        if ($(this).children('a').attr('href') === window.location.href) {
                            $(this).addClass('active');
                            li.children('a').addClass('active');
                        }
                    }
                });
            });


            //tableaux
            tablesData();
            tablesEditable();
        }

        function tablesData() {
            //tables (managed by dataTable)
            $('table.datatable').each(function () {
                var id = $(this).attr('id');
                if (id !== "undefined") {
                    datatables[id] = $('#' + id).dataTable({
                        "bDestroy": true,
                        "oLanguage": {
                            "sProcessing": "Traitement en cours...",
                            "sSearch": "Rechercher&nbsp;:",
                            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                            "sInfo": "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                            "sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                            "sInfoPostFix": "",
                            "sLoadingRecords": "Chargement en cours...",
                            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                            "sEmptyTable": "Aucune donnée disponible dans le tableau",
                            "oPaginate": {
                                "sFirst": "Premier",
                                "sPrevious": "Pr&eacute;c&eacute;dent",
                                "sNext": "Suivant",
                                "sLast": "Dernier"
                            },
                            "oAria": {
                                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                            }
                        }
                    });
                }
            });
        }

        function tablesEditable() {
            $('td.editable, th.editable').each(function () {
                var url = urls[$(this).parents("table").attr('id') + 'Update'] + '/' + $(this).parents("tr").attr('id');
                var tr = $(this).parents("tr");
                $(this).editInPlace({
                    field_type: "textarea",
                    saving_animation_color: "rgb(24,188,156)",
                    bg_over: "rgb(24,188,156)",
                    default_text: "",
                    callback: function (idOfEditor, enteredText, orinalHTMLContent, settingsParams, animationCallbacks) {
                        update(url, tr);
                        animationCallbacks.didStartSaving();
                        setTimeout(animationCallbacks.didEndSaving, 1200);
                        return enteredText;

                    }
                });
            });
        }

        function getInputsValues() {
            var datas = {};
            $("body").find(':input').each(function () {
                if (this.type === 'checkbox') {
                    if ($(this).is(':checked'))
                        datas[this.name] = this.value;
                } else
                    datas[this.name] = this.value;
            });
            $("body").find('textarea').each(function () {
                datas[this.name] = this.value;
            });

            return datas;
        }
        function setDefaultInputs() {
            $("body").find(':input').each(function () {
                defaultInputs[this.name] = this.value;
            });
            $("body").find('textarea').each(function () {
                defaultInputs[this.name] = this.value;
            });
        }
        function restoreDefaultInputs() {
            $("body").find(':input').each(function () {
                $(this).val(defaultInputs[this.name]);
            });
            $("body").find('textarea').each(function () {
                $(this).val(defaultInputs[this.name]);
            });
        }

        function ajaxCallback(datas) {
            if (datas.notifyError !== null) {
                if (typeof (datas.notifyError.messages.url) !== "undefined")
                    window.location.replace(datas.notifyError.messages.url);
            }
        }

        function remove(url, tableId, urlsList) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {'backoffice-token': $('input#backoffice-token').val()},
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    if (typeof (datas.success) !== "undefined" && datas.success === true) {
                        // update datatable
                        if (typeof (tableId) !== "undefined") {
                            //restore inputs default value
                            restoreDefaultInputs();
                            //update table content
                            $('#' + tableId).html(datas.content);
                        }
                    }

                    //reload plugins tables
                    tablesData();
                    tablesEditable();
                    //update token
                    $('input#backoffice-token').val(datas.token);

                    if (urlsList.length !== 0) {
                        var url = urlsList[0];
                        urlsList.splice(0, 1);
                        remove(url, tableId, urlsList);
                    }
                }
            });
        }

        function add(tableId, inputs, url) {
            $.ajax({
                type: 'POST',
                url: url,
                data: inputs,
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    if (typeof (datas.success) !== "undefined" && datas.success === true) {
                        //update datatable
                        if (typeof (tableId) !== "undefined") {
                            //restore inputs default value
                            restoreDefaultInputs();
                            //remove last upload img
                            $('#' + tableId).find('img.uploaded').each(function () {
                                $(this).remove();
                            });
                            $('#' + tableId).html(datas.content);
                        }
                    }

                    //reload plugins tables
                    tablesData();
                    tablesEditable();
                    // update token
                    $('input#backoffice-token').val(datas.token);
                }
            });
        }

        function update(url, trElement) {
            var inputs = {};
            inputs['backoffice-token'] = $('input#backoffice-token').val();
            trElement.find('th, td').each(function () {
                var name = $(this).attr('name');
                if (typeof (name) !== "undefined")
                    inputs[name] = $(this).html();
            });

            $.ajax({
                type: 'POST',
                url: url,
                data: inputs,
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    if (typeof (datas.success) !== "undefined" && datas.success === true) {
                        if (typeof (datas.thumb) !== "undefined" && datas.thumb !== null)
                            trElement.find('img.editable-upload.thumb').attr('src', datas.thumb);

                        if (typeof (datas.original) !== "undefined" && datas.original !== null)
                            trElement.find('img.editable-upload.original').attr('src', datas.original);
                    }

                    // update token
                    $('input#backoffice-token').val(datas.token);
                }
            });

            uploadUpdate = false;
        }



    });
})(jQuery);


/*
 var Script = function() {
 
 
 
 
 
 
 /*
 // widget tools
 jQuery('.panel .tools .fa-chevron-down').click(function() {
 var el = jQuery(this).parents(".panel").children(".panel-body");
 if (jQuery(this).hasClass("fa-chevron-down")) {
 jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
 el.slideUp(200);
 } else {
 jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
 el.slideDown(200);
 }
 });
 
 jQuery('.panel .tools .fa-times').click(function() {
 jQuery(this).parents(".panel").parent().remove();
 });
 */


/*
 // tool tips
 $('.tooltips').tooltip();
 //    popovers
 $('.popovers').popover();
 // custom bar chart
 if ($(".custom-bar-chart")) {
 $(".bar").each(function() {
 var i = $(this).find(".value").html();
 $(this).find(".value").html("");
 $(this).find(".value").animate({
 height: i
 }, 2000)
 })
 }
 }();
 */

/*
 var Gritter = function() {
 $('#add-sticky').click(function() {
 var unique_id = $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'This is a Sticky Notice!',
 // (string | mandatory) the text inside the notification
 text: 'Hover me to enable the Close Button. This note also contains a link example. Thank you so much to try Dashgum. Developed by <a href="#" style="color:#FFD777">Alvarez.is</a>.',
 // (string | optional) the image to display on the left
 image: 'assets/img/ui-sam.jpg',
 // (bool | optional) if you want it to fade out on its own or just sit there
 sticky: true,
 // (int | optional) the time you want it to be alive for before fading out
 time: '',
 // (string | optional) the class name you want to apply to that specific message
 class_name: 'my-sticky-class'
 });
 
 // You can have it return a unique id, this can be used to manually remove it later using
 
 //setTimeout(function(){
 
 //$.gritter.remove(unique_id, {
 //fade: true,
 //speed: 'slow'
 //});
 
 //}, 6000)
 
 
 return false;
 
 });
 
 $('#add-regular').click(function() {
 
 $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'This is a Regular Notice!',
 // (string | mandatory) the text inside the notification
 text: 'This will fade out after a certain amount of time. This note also contains a link example. Thank you so much to try Dashgum. Developed by <a href="#" style="color:#FFD777">Alvarez.is</a>.',
 // (string | optional) the image to display on the left
 image: 'assets/img/ui-sam.jpg',
 // (bool | optional) if you want it to fade out on its own or just sit there
 sticky: false,
 // (int | optional) the time you want it to be alive for before fading out
 time: ''
 });
 
 return false;
 
 });
 
 $('#add-max').click(function() {
 
 $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'This is a notice with a max of 3 on screen at one time!',
 // (string | mandatory) the text inside the notification
 text: 'This will fade out after a certain amount of time. This note also contains a link example. Thank you so much to try Dashgum. Developed by <a href="#" style="color:#FFD777">Alvarez.is</a>.',
 // (string | optional) the image to display on the left
 image: 'assets/img/ui-sam.jpg',
 // (bool | optional) if you want it to fade out on its own or just sit there
 sticky: false,
 // (function) before the gritter notice is opened
 before_open: function() {
 if ($('.gritter-item-wrapper').length == 3)
 {
 // Returning false prevents a new gritter from opening
 return false;
 }
 }
 });
 
 return false;
 
 });
 
 $('#add-without-image').click(function() {
 
 $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'This is a Notice Without an Image!',
 // (string | mandatory) the text inside the notification
 text: 'This will fade out after a certain amount of time. This note also contains a link example. Thank you so much to try Dashgum. Developed by <a href="#" style="color:#FFD777">Alvarez.is</a>.'
 });
 
 return false;
 });
 
 $('#add-gritter-light').click(function() {
 
 $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'This is a Light Notification',
 // (string | mandatory) the text inside the notification
 text: 'Just add a "gritter-light" class_name to your $.gritter.add or globally to $.gritter.options.class_name',
 class_name: 'gritter-light'
 });
 
 return false;
 });
 
 $("#remove-all").click(function() {
 
 $.gritter.removeAll();
 return false;
 
 });
 
 
 
 }();
 */


/*
 var Script = function() {
 $(".sparkline").each(function() {
 var $data = $(this).data();
 $data.valueSpots = {'0:': $data.spotColor};
 $(this).sparkline($data.data || "html", $data,
 {
 tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 
 
 
 });
 
 //sparkline chart
 
 $("#barchart").sparkline([5, 3, 6, 7, 5, 6, 4, 2, 3, 4, 6, 8, 9, 10, 8, 6, 5, 7, 6, 5, 4, 7, 4], {
 type: 'bar',
 height: '65',
 barWidth: 8,
 barSpacing: 5,
 barColor: '#fff'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 
 });
 
 
 $("#linechart").sparkline([1, 5, 3, 7, 9, 3, 6, 4, 7, 9, 7, 6, 2], {
 type: 'line',
 width: '300',
 height: '75',
 fillColor: '',
 lineColor: '#fff',
 lineWidth: 2,
 spotColor: '#fff',
 minSpotColor: '#fff',
 maxSpotColor: '#fff',
 highlightSpotColor: '#fff',
 highlightLineColor: '#ffffff',
 spotRadius: 4,
 highlightLineColor: '#ffffff'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 
 
 
 });
 
 $("#pie-chart").sparkline([2, 1, 1, 1], {
 type: 'pie',
 width: '100',
 height: '100',
 borderColor: '#00bf00',
 sliceColors: ['#41CAC0', '#A8D76F', '#F8D347', '#EF6F66']
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 //work progress bar
 
 $("#work-progress1").sparkline([5, 6, 7, 5, 9, 6, 4], {
 type: 'bar',
 height: '20',
 barWidth: 5,
 barSpacing: 2,
 barColor: '#5fbf00'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 $("#work-progress2").sparkline([3, 2, 5, 8, 4, 7, 5], {
 type: 'bar',
 height: '22',
 barWidth: 5,
 barSpacing: 2,
 barColor: '#58c9f1'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 $("#work-progress3").sparkline([1, 6, 9, 3, 4, 8, 5], {
 type: 'bar',
 height: '22',
 barWidth: 5,
 barSpacing: 2,
 barColor: '#8075c4'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 $("#work-progress4").sparkline([9, 4, 9, 6, 7, 4, 3], {
 type: 'bar',
 height: '22',
 barWidth: 5,
 barSpacing: 2,
 barColor: '#ff6c60'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 $("#work-progress5").sparkline([6, 8, 5, 7, 6, 8, 3], {
 type: 'bar',
 height: '22',
 barWidth: 5,
 barSpacing: 2,
 barColor: '#41cac0'
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //            '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'
 });
 
 $("#pie-chart2").sparkline([2, 1, 1, 1], {
 type: 'pie',
 width: '250',
 height: '125',
 sliceColors: ['#41CAC0', '#A8D76F', '#F8D347', '#EF6F66']
 //        tooltipFormat: '<span style="display:block; padding:0px 10px 12px 0px;">' +
 //    '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)</span>'});
 
 });
 
 }();
 */



/*
 $(document).ready(function() {
 var unique_id = $.gritter.add({
 // (string | mandatory) the heading of the notification
 title: 'Welcome to Dashgum!',
 // (string | mandatory) the text inside the notification
 text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo. Free version for <a href="http://blacktie.co" target="_blank" style="color:#ffd777">BlackTie.co</a>.',
 // (string | optional) the image to display on the left
 image: 'assets/img/ui-sam.jpg',
 // (bool | optional) if you want it to fade out on its own or just sit there
 sticky: true,
 // (int | optional) the time you want it to be alive for before fading out
 time: '',
 // (string | optional) the class name you want to apply to that specific message
 class_name: 'my-sticky-class'
 });
 
 return false;
 });
 */

/*
 $(document).ready(function() {
 $("#date-popover").popover({html: true, trigger: "manual"});
 $("#date-popover").hide();
 $("#date-popover").click(function(e) {
 $(this).hide();
 });
 
 $("#my-calendar").zabuto_calendar({
 action: function() {
 return myDateFunction(this.id, false);
 },
 action_nav: function() {
 return myNavFunction(this.id);
 },
 ajax: {
 url: "show_data.php?action=1",
 modal: true
 },
 legend: [
 {type: "text", label: "Special event", badge: "00"},
 {type: "block", label: "Regular event", }
 ]
 });
 });
 */


/*
 function myNavFunction(id) {
 $("#date-popover").hide();
 var nav = $("#" + id).data("navigation");
 var to = $("#" + id).data("to");
 console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
 }
 */


/*
 var Script = function() {
 // initialize the external events
 
 $('#external-events div.external-event').each(function() {
 
 // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
 // it doesn't need to have a start or end
 var eventObject = {
 title: $.trim($(this).text()) // use the element's text as the event title
 };
 
 // store the Event Object in the DOM element so we can get to it later
 $(this).data('eventObject', eventObject);
 
 // make the event draggable using jQuery UI
 $(this).draggable({
 zIndex: 999,
 revert: true, // will cause the event to go back to its
 revertDuration: 0  //  original position after the drag
 });
 
 });
 
 
 // initialize the calenda
 
 var date = new Date();
 var d = date.getDate();
 var m = date.getMonth();
 var y = date.getFullYear();
 
 $('#calendar').fullCalendar({
 header: {
 left: 'prev,next today',
 center: 'title',
 right: 'month,basicWeek,basicDay'
 },
 editable: true,
 droppable: true, // this allows things to be dropped onto the calendar !!!
 drop: function(date, allDay) { // this function is called when something is dropped
 
 // retrieve the dropped element's stored Event Object
 var originalEventObject = $(this).data('eventObject');
 
 // we need to copy it, so that multiple events don't have a reference to the same object
 var copiedEventObject = $.extend({}, originalEventObject);
 
 // assign it the date that was reported
 copiedEventObject.start = date;
 copiedEventObject.allDay = allDay;
 
 // render the event on the calendar
 // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
 $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
 
 // is the "remove after drop" checkbox checked?
 if ($('#drop-remove').is(':checked')) {
 // if so, remove the element from the "Draggable Events" list
 $(this).remove();
 }
 
 },
 events: [
 {
 title: 'All Day Event',
 start: new Date(y, m, 1)
 },
 {
 title: 'Long Event',
 start: new Date(y, m, d - 5),
 end: new Date(y, m, d - 2)
 },
 {
 id: 999,
 title: 'Repeating Event',
 start: new Date(y, m, d - 3, 16, 0),
 allDay: false
 },
 {
 id: 999,
 title: 'Repeating Event',
 start: new Date(y, m, d + 4, 16, 0),
 allDay: false
 },
 {
 title: 'Meeting',
 start: new Date(y, m, d, 10, 30),
 allDay: false
 },
 {
 title: 'Lunch',
 start: new Date(y, m, d, 12, 0),
 end: new Date(y, m, d, 14, 0),
 allDay: false
 },
 {
 title: 'Birthday Party',
 start: new Date(y, m, d + 1, 19, 0),
 end: new Date(y, m, d + 1, 22, 30),
 allDay: false
 },
 {
 title: 'Click for Google',
 start: new Date(y, m, 28),
 end: new Date(y, m, 29),
 url: 'http://google.com/'
 }
 ]
 });
 
 
 }();
 */


/*
 var Script = function() {
 var doughnutData = [
 {
 value: 30,
 color: "#1abc9c"
 },
 {
 value: 50,
 color: "#2ecc71"
 },
 {
 value: 100,
 color: "#3498db"
 },
 {
 value: 40,
 color: "#9b59b6"
 },
 {
 value: 120,
 color: "#34495e"
 }
 
 ];
 var lineChartData = {
 labels: ["", "", "", "", "", "", ""],
 datasets: [
 {
 fillColor: "rgba(220,220,220,0.5)",
 strokeColor: "rgba(220,220,220,1)",
 pointColor: "rgba(220,220,220,1)",
 pointStrokeColor: "#fff",
 data: [65, 59, 90, 81, 56, 55, 40]
 },
 {
 fillColor: "rgba(151,187,205,0.5)",
 strokeColor: "rgba(151,187,205,1)",
 pointColor: "rgba(151,187,205,1)",
 pointStrokeColor: "#fff",
 data: [28, 48, 40, 19, 96, 27, 100]
 }
 ]
 
 };
 var pieData = [
 {
 value: 30,
 color: "#1abc9c"
 },
 {
 value: 50,
 color: "#16a085"
 },
 {
 value: 100,
 color: "#27ae60"
 }
 
 ];
 var barChartData = {
 labels: ["January", "February", "March", "April", "May", "June", "July"],
 datasets: [
 {
 fillColor: "rgba(220,220,220,0.5)",
 strokeColor: "rgba(220,220,220,1)",
 data: [65, 59, 90, 81, 56, 55, 40]
 },
 {
 fillColor: "rgba(151,187,205,0.5)",
 strokeColor: "rgba(151,187,205,1)",
 data: [28, 48, 40, 19, 96, 27, 100]
 }
 ]
 
 };
 var chartData = [
 {
 value: Math.random(),
 color: "#D97041"
 },
 {
 value: Math.random(),
 color: "#C7604C"
 },
 {
 value: Math.random(),
 color: "#21323D"
 },
 {
 value: Math.random(),
 color: "#9D9B7F"
 },
 {
 value: Math.random(),
 color: "#7D4F6D"
 },
 {
 value: Math.random(),
 color: "#584A5E"
 }
 ];
 var radarChartData = {
 labels: ["", "", "", "", "", "", ""],
 datasets: [
 {
 fillColor: "rgba(220,220,220,0.5)",
 strokeColor: "rgba(220,220,220,1)",
 pointColor: "rgba(220,220,220,1)",
 pointStrokeColor: "#fff",
 data: [65, 59, 90, 81, 56, 55, 40]
 },
 {
 fillColor: "rgba(151,187,205,0.5)",
 strokeColor: "rgba(151,187,205,1)",
 pointColor: "rgba(151,187,205,1)",
 pointStrokeColor: "#fff",
 data: [28, 48, 40, 19, 96, 27, 100]
 }
 ]
 
 };
 new Chart(document.getElementById("doughnut").getContext("2d")).Doughnut(doughnutData);
 new Chart(document.getElementById("line").getContext("2d")).Line(lineChartData);
 new Chart(document.getElementById("radar").getContext("2d")).Radar(radarChartData);
 new Chart(document.getElementById("polarArea").getContext("2d")).PolarArea(chartData);
 new Chart(document.getElementById("bar").getContext("2d")).Bar(barChartData);
 new Chart(document.getElementById("pie").getContext("2d")).Pie(pieData);
 
 
 }();
 */



/*
 var Script = function() {
 
 // easy pie chart
 
 $('.percentage').easyPieChart({
 animate: 1000,
 size: 135,
 barColor: '#ff6c60'
 });
 $('.percentage-light').easyPieChart({
 barColor: function(percent) {
 percent /= 100;
 return "rgb(" + Math.round(255 * (1 - percent)) + ", " + Math.round(255 * percent) + ", 0)";
 },
 trackColor: '#666',
 scaleColor: false,
 lineCap: 'butt',
 lineWidth: 15,
 animate: 1000
 });
 
 $('.update-easy-pie-chart').click(function() {
 $('.easy-pie-chart .percentage').each(function() {
 var newValue = Math.floor(100 * Math.random());
 $(this).data('easyPieChart').update(newValue);
 $('span', this).text(newValue);
 });
 });
 
 $('.updateEasyPieChart').on('click', function(e) {
 e.preventDefault();
 $('.percentage, .percentage-light').each(function() {
 var newValue = Math.round(100 * Math.random());
 $(this).data('easyPieChart').update(newValue);
 $('span', this).text(newValue);
 });
 });
 
 }();
 
 */


/*
 var Script = function() {
 
 
 //checkbox and radio btn
 
 var d = document;
 var safari = (navigator.userAgent.toLowerCase().indexOf('safari') != -1) ? true : false;
 var gebtn = function(parEl, child) {
 return parEl.getElementsByTagName(child);
 };
 onload = function() {
 
 var body = gebtn(d, 'body')[0];
 body.className = body.className && body.className != '' ? body.className + ' has-js' : 'has-js';
 
 if (!d.getElementById || !d.createTextNode)
 return;
 var ls = gebtn(d, 'label');
 for (var i = 0; i < ls.length; i++) {
 var l = ls[i];
 if (l.className.indexOf('label_') == -1)
 continue;
 var inp = gebtn(l, 'input')[0];
 if (l.className == 'label_check') {
 l.className = (safari && inp.checked == true || inp.checked) ? 'label_check c_on' : 'label_check c_off';
 l.onclick = check_it;
 }
 ;
 if (l.className == 'label_radio') {
 l.className = (safari && inp.checked == true || inp.checked) ? 'label_radio r_on' : 'label_radio r_off';
 l.onclick = turn_radio;
 }
 ;
 }
 ;
 };
 var check_it = function() {
 var inp = gebtn(this, 'input')[0];
 if (this.className == 'label_check c_off' || (!safari && inp.checked)) {
 this.className = 'label_check c_on';
 if (safari)
 inp.click();
 } else {
 this.className = 'label_check c_off';
 if (safari)
 inp.click();
 }
 ;
 };
 var turn_radio = function() {
 var inp = gebtn(this, 'input')[0];
 if (this.className == 'label_radio r_off' || inp.checked) {
 var ls = gebtn(this.parentNode, 'label');
 for (var i = 0; i < ls.length; i++) {
 var l = ls[i];
 if (l.className.indexOf('label_radio') == -1)
 continue;
 l.className = 'label_radio r_off';
 }
 ;
 this.className = 'label_radio r_on';
 if (safari)
 inp.click();
 } else {
 this.className = 'label_radio r_off';
 if (safari)
 inp.click();
 }
 ;
 };
 
 
 
 $(function() {
 
 // Tags Input
 $(".tagsinput").tagsInput();
 
 // Switch
 $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
 
 });
 
 
 
 //color picker
 
 $('.cp1').colorpicker({
 format: 'hex'
 });
 $('.cp2').colorpicker();
 
 
 //date picker
 
 if (top.location != location) {
 top.location.href = document.location.href;
 }
 $(function() {
 window.prettyPrint && prettyPrint();
 $('#dp1').datepicker({
 format: 'mm-dd-yyyy'
 });
 $('#dp2').datepicker();
 $('#dp3').datepicker();
 $('#dp3').datepicker();
 $('#dpYears').datepicker();
 $('#dpMonths').datepicker();
 
 
 var startDate = new Date(2012, 1, 20);
 var endDate = new Date(2012, 1, 25);
 $('#dp4').datepicker()
 .on('changeDate', function(ev) {
 if (ev.date.valueOf() > endDate.valueOf()) {
 $('#alert').show().find('strong').text('The start date can not be greater then the end date');
 } else {
 $('#alert').hide();
 startDate = new Date(ev.date);
 $('#startDate').text($('#dp4').data('date'));
 }
 $('#dp4').datepicker('hide');
 });
 $('#dp5').datepicker()
 .on('changeDate', function(ev) {
 if (ev.date.valueOf() < startDate.valueOf()) {
 $('#alert').show().find('strong').text('The end date can not be less then the start date');
 } else {
 $('#alert').hide();
 endDate = new Date(ev.date);
 $('#endDate').text($('#dp5').data('date'));
 }
 $('#dp5').datepicker('hide');
 });
 
 // disabling dates
 var nowTemp = new Date();
 var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
 var checkin = $('#dpd1').datepicker({
 onRender: function(date) {
 return date.valueOf() < now.valueOf() ? 'disabled' : '';
 }
 }).on('changeDate', function(ev) {
 if (ev.date.valueOf() > checkout.date.valueOf()) {
 var newDate = new Date(ev.date)
 newDate.setDate(newDate.getDate() + 1);
 checkout.setValue(newDate);
 }
 checkin.hide();
 $('#dpd2')[0].focus();
 }).data('datepicker');
 var checkout = $('#dpd2').datepicker({
 onRender: function(date) {
 return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
 }
 }).on('changeDate', function(ev) {
 checkout.hide();
 }).data('datepicker');
 });
 
 
 
 //daterange picker
 
 $('#reservation').daterangepicker();
 
 $('#reportrange').daterangepicker(
 {
 ranges: {
 'Today': ['today', 'today'],
 'Yesterday': ['yesterday', 'yesterday'],
 'Last 7 Days': [Date.today().add({days: -6}), 'today'],
 'Last 30 Days': [Date.today().add({days: -29}), 'today'],
 'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
 'Last Month': [Date.today().moveToFirstDayOfMonth().add({months: -1}), Date.today().moveToFirstDayOfMonth().add({days: -1})]
 },
 opens: 'left',
 format: 'MM/dd/yyyy',
 separator: ' to ',
 startDate: Date.today().add({days: -29}),
 endDate: Date.today(),
 minDate: '01/01/2012',
 maxDate: '12/31/2013',
 locale: {
 applyLabel: 'Submit',
 fromLabel: 'From',
 toLabel: 'To',
 customRangeLabel: 'Custom Range',
 daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
 monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
 firstDay: 1
 },
 showWeekNumbers: true,
 buttonClasses: ['btn-danger']
 },
 function(start, end) {
 $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
 }
 );
 
 //Set the initial state of the picker label
 $('#reportrange span').html(Date.today().add({days: -29}).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
 
 
 }();
 */




/*
 var Script = function() {
 
 //morris chart
 
 $(function() {
 // data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type
 var tax_data = [
 {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
 {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
 {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
 {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
 {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
 {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
 {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
 {"period": "2006 Q4", "licensed": 3245, "sorned": null},
 {"period": "2005 Q4", "licensed": 3289, "sorned": null}
 ];
 Morris.Line({
 element: 'hero-graph',
 data: tax_data,
 xkey: 'period',
 ykeys: ['licensed', 'sorned'],
 labels: ['Licensed', 'Off the road'],
 lineColors: ['#4ECDC4', '#ed5565']
 });
 
 Morris.Donut({
 element: 'hero-donut',
 data: [
 {label: 'Jam', value: 25},
 {label: 'Frosted', value: 40},
 {label: 'Custard', value: 25},
 {label: 'Sugar', value: 10}
 ],
 colors: ['#3498db', '#2980b9', '#34495e'],
 formatter: function(y) {
 return y + "%"
 }
 });
 
 Morris.Area({
 element: 'hero-area',
 data: [
 {period: '2010 Q1', iphone: 2666, ipad: null, itouch: 2647},
 {period: '2010 Q2', iphone: 2778, ipad: 2294, itouch: 2441},
 {period: '2010 Q3', iphone: 4912, ipad: 1969, itouch: 2501},
 {period: '2010 Q4', iphone: 3767, ipad: 3597, itouch: 5689},
 {period: '2011 Q1', iphone: 6810, ipad: 1914, itouch: 2293},
 {period: '2011 Q2', iphone: 5670, ipad: 4293, itouch: 1881},
 {period: '2011 Q3', iphone: 4820, ipad: 3795, itouch: 1588},
 {period: '2011 Q4', iphone: 15073, ipad: 5967, itouch: 5175},
 {period: '2012 Q1', iphone: 10687, ipad: 4460, itouch: 2028},
 {period: '2012 Q2', iphone: 8432, ipad: 5713, itouch: 1791}
 ],
 xkey: 'period',
 ykeys: ['iphone', 'ipad', 'itouch'],
 labels: ['iPhone', 'iPad', 'iPod Touch'],
 hideHover: 'auto',
 lineWidth: 1,
 pointSize: 5,
 lineColors: ['#4a8bc2', '#ff6c60', '#a9d86e'],
 fillOpacity: 0.5,
 smooth: true
 });
 
 Morris.Bar({
 element: 'hero-bar',
 data: [
 {device: 'iPhone', geekbench: 136},
 {device: 'iPhone 3G', geekbench: 137},
 {device: 'iPhone 3GS', geekbench: 275},
 {device: 'iPhone 4', geekbench: 380},
 {device: 'iPhone 4S', geekbench: 655},
 {device: 'iPhone 5', geekbench: 1571}
 ],
 xkey: 'device',
 ykeys: ['geekbench'],
 labels: ['Geekbench'],
 barRatio: 0.4,
 xLabelAngle: 35,
 hideHover: 'auto',
 barColors: ['#ac92ec']
 });
 
 new Morris.Line({
 element: 'examplefirst',
 xkey: 'year',
 ykeys: ['value'],
 labels: ['Value'],
 data: [
 {year: '2008', value: 20},
 {year: '2009', value: 10},
 {year: '2010', value: 5},
 {year: '2011', value: 5},
 {year: '2012', value: 20}
 ]
 });
 
 $('.code-example').each(function(index, el) {
 eval($(el).text());
 });
 });
 
 }();
 */





/*
 var TaskList = function() {
 
 return {
 initTaskWidget: function() {
 $('input.list-child').change(function() {
 if ($(this).is(':checked')) {
 $(this).parents('li').addClass("task-done");
 } else {
 $(this).parents('li').removeClass("task-done");
 }
 });
 }
 
 };
 
 }();
 */


/*
 //custom select box
 $(function() {
 $('select.styled').customSelect();
 });
 */







/*
 function getTime()
 {
 var today = new Date();
 var h = today.getHours();
 var m = today.getMinutes();
 var s = today.getSeconds();
 // add a zero in front of numbers<10
 m = checkTime(m);
 s = checkTime(s);
 document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
 t = setTimeout(function() {
 getTime()
 }, 500);
 }
 
 function checkTime(i)
 {
 if (i < 10)
 {
 i = "0" + i;
 }
 return i;
 }
 
 */


/*
 jQuery(document).ready(function() {
 TaskList.initTaskWidget();
 });
 
 $(function() {
 $("#sortable").sortable();
 $("#sortable").disableSelection();
 });
 
 */


