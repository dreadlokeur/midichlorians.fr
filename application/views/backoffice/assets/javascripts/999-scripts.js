(function ($) {
    $(document).ready(function () {
        /* PUBLIC VARS */
        var defaultInputs = {};
        var historyApi = true;
        var historyState = false;
        var datatables = [];

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
            };
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
        $('.page-scroll a').bind('click', function (e) {
            var anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
            e.preventDefault();
        });

        // sidebar dropdown menu auto scrolling
        $('#sidebar .sub-menu > a').click(function () {
            var o = ($(this).offset());
            var diff = 250 - o.top;
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

            if (urlsList && urlsList.length !== 0) {
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

        $("body").on("click", "#request-media-dropzone", function () {
            if ($('#media-dropzone').hasClass("hide"))
                $('#media-dropzone').removeClass('hide');
            else
                $('#media-dropzone').addClass('hide');

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

            // media upload form
            dropzone();


            iconSelect();
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
        $("body").on('change', '.editableInput, .editableSelect', function () {
            var url = urls[$(this).parents("table").attr('id') + 'Update'] + '/' + $(this).parents("tr").attr('id');
            var tr = $(this).parents("tr");
            update(url, tr);
        });

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

                    //reload plugins
                    tablesData();
                    tablesEditable();
                    iconSelect();
                    //update token
                    $('input#backoffice-token').val(datas.token);

                    if (urlsList && urlsList.length !== 0) {
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
                            $('#' + tableId).html(datas.content);
                        }
                    }

                    //reload plugins
                    tablesData();
                    tablesEditable();
                    iconSelect();
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
                if (typeof (name) !== "undefined") {
                    inputs[name] = $(this).html();
                }
                $(this).find('input').each(function () {
                    if ($(this).attr('name') !== "undefined") {
                        inputs[$(this).attr('name')] = $(this).val();
                    }
                });
            });
            trElement.find('option').each(function () {
                if ($(this).is(':selected'))
                    inputs[$(this).parent().attr('name')] = this.value;

            });

            $.ajax({
                type: 'POST',
                url: url,
                data: inputs,
                dataType: "json",
                success: function (datas) {
                    ajaxCallback(datas);
                    // update token
                    $('input#backoffice-token').val(datas.token);
                }
            });
        }

        function dropzone() {
            //disable auto init
            Dropzone.autoDiscover = false;
            if ($('#formMediaDropzone').length > 0) {
                new Dropzone("#formMediaDropzone", {
                    url: $('#formMediaDropzone').attr('action'),
                    uploadMultiple: false,
                    parallelUploads: 1,
                    acceptedFiles: $('#formMediaDropzone').attr('accept'),
                    maxFilesize: $('#formMediaDropzone').attr('max-size'),
                    init: function () {
                        this.on("sending", function (file, xhr, formData) {
                            formData.append("backoffice-token", $('input#backoffice-token').val());
                        });
                        this.on("success", function (file, response) {
                            ajaxCallback(response);
                            if (typeof (response.success) !== "undefined" && response.success === true) {
                                //update datatable
                                if (typeof ('media') !== "undefined") {
                                    //restore inputs default value
                                    restoreDefaultInputs();
                                    $('#media').html(response.content);
                                }
                            }

                            //reload plugins tables
                            tablesData();
                            tablesEditable();
                            // update token
                            $('input#backoffice-token').val(response.token);
                        });
                        this.on("error", function (file, response) {
                            $('input#backoffice-token').val(response.token);
                        });
                    }
                });
            }
        }

        function iconSelect() {
            $('body').find('div.select2-container').each(function () {
                $(this).remove();
            });
            $("select.icon-select").select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });
        }


        function format(state) {
            return "<div style='font-family: FontAwesome, arial'>" + state.text + "</i>";
        }

    });
})(jQuery);