(function ($) {
    /*
     * TODO 
     * bug on jcrop bug rotate or flip image, or update form (need destroy and reload plugin)
     */
    $(document).ready(function () {
        /* PUBLIC VARS */
        var defaultInputs = {};
        var historyApi = true;
        var historyState = false;
        var datatables = [];
        var wysiwygs = [];
        var rotateStep = 0;
        var flipHSep = 0;
        var flipVSep = 0;
        var jcrop_api;

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
                    if (datas.notifySuccess !== null)
                        window.location.replace(urls['index']);
                },
                error: function () {
                    window.location.reload();
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
                    //reload
                    if (datas.notifySuccess !== null)
                        window.location.reload();
                    else {
                        $('#login').wiggle('start', {
                            wiggleDegrees: ['1', '2', '1', '0', '-1', '-2', '-1', '0'],
                            delay: 10,
                            limit: 4,
                            randomStart: false
                        });

                        $('.form-group').addClass('has-error');
                        //update token
                        $('input#csrf').val(datas.csrf);
                    }
                    $("#login-loader").removeClass('show');
                },
                error: function () {
                    window.location.reload();
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
                    //reload
                    if (datas.notifySuccess !== null)
                        location.reload();
                    else
                        $('input#csrf').val(datas.csrf);
                },
                error: function () {
                    window.location.reload();
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
            var tableId = $(this).parents().find('table').attr('id');
            $('#' + tableId).find('input:checkbox').not(this).prop('checked', this.checked);
        });

        /* CLICK DELETE LINK */
        $("body").on("click", ".delete", function () {
            remove($(this).attr('href'), $(this).parent().parents("table").attr('id'));
            return false;
        });
        $("body").on("click", ".deleteAll", function () {
            var tableId = $(this).parents().find('table').attr('id');
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
            var inputs = getInputsTr($(this).parents("tr"));
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

        $("body").on("click", 'input[type="reset"]', function () {
            //restore wysiwyg default content
            var idwysiwyg = $(this).parents().find('.wysiwyg').attr('id');
            if (idwysiwyg) {
                if (wysiwygs[idwysiwyg] !== "undefined") {
                    wysiwygs[idwysiwyg].code($('#' + idwysiwyg).html());
                }
            }

            var img = $('body').find('img.media');
            if (img) {
                resetMediaManipulate();
                rotateMediaImage(rotateStep, img);
            }
            /*
             * TODO 
             * reset input checkbox data-toggle="switch"
             * crop selection
             */
        });

        /* CLICK UPDATE */
        $("body").on("submit", "form.form-update", function () {
            var inputs = getInputsValues();
            var idwysiwyg = $(this).parents().find('.wysiwyg').attr('id');
            if (idwysiwyg) {
                if (wysiwygs[idwysiwyg] !== "undefined")
                    inputs[idwysiwyg] = wysiwygs[idwysiwyg].code();
            }

            var url = $(this).attr('action');
            update(url, inputs);
            return false;
        });

        $("body").on('change', '.editableDate', function () {
            var url = urls[$(this).parents("table").attr('id') + 'Update'] + '/' + $(this).parents("tr").attr('id');
            update(url, getInputsTr($(this).parents("tr")));
            //replace hide span
            $(this).find('span.hide').html($(this).find('input').val());
        });
        $("body").on('change', '.editableSwitch', function () {
            var url = urls[$(this).parents("table").attr('id') + 'Update'] + '/' + $(this).parents("tr").attr('id');
            update(url, getInputsTr($(this).parents("tr")));
        });
        $("body").on('click', '.mediaModal', function () {
            var idMedia = $(this).parent().attr('id');
            var idSrc = $(this).attr('src');
            $(this).parent().parent().parent().find('img').each(function () {
                $(this).removeClass('active');
            });
            $(this).addClass('active');
            $('body').find('#mediaId').val(idMedia);
            $('body').find('#imageMediaId').attr('src', idSrc);
            $(this).parents('div.modal').modal('hide');
        });




        // media image manipulate
        $("body").on('click', '.media-rotate-l', function () {
            var img = $(this).parents().find('img.media');
            rotateStep -= 90;
            if (rotateStep <= -360)
                rotateStep = 0;

            rotateMediaImage(rotateStep, img);
        });
        $("body").on('click', '.media-rotate-r', function () {
            var img = $(this).parents().find('img.media');
            rotateStep += 90;
            if (rotateStep >= 360)
                rotateStep = 0;

            rotateMediaImage(rotateStep, img);
        });


        $("body").on('click', '.media-flip-h', function () {
            if (flipHSep === 0 || flipHSep === 1)
                flipHSep = -1;
            else {
                flipHSep = 1;
            }
            var img = $(this).parents().find('img.media');
            var transform = 'scaleX(' + flipHSep + ')';
            if (flipVSep !== 0)
                transform += ' scaleY(' + flipVSep + ')';
            if (rotateStep !== 0)
                transform += ' rotate(' + rotateStep + 'deg)';
            img.css('transform', transform);

            //set value on hidden input
            img.parent('p#media-block').find('input#flipH').val(flipHSep);
        });

        $("body").on('click', '.media-flip-v', function () {
            if (flipVSep === 0 || flipVSep === 1)
                flipVSep = -1;
            else {
                flipVSep = 1;
            }
            var img = $(this).parents().find('img.media');
            var transform = 'scaleY(' + flipVSep + ')';
            if (flipHSep !== 0)
                transform += ' scaleX(' + flipVSep + ')';
            if (rotateStep !== 0)
                transform += ' rotate(' + rotateStep + 'deg)';
            img.css('transform', transform);

            //set value on hidden input
            img.parent('p#media-block').find('input#flipV').val(flipVSep);

        });

        $("body").on('change', 'input#media-height', function () {
            var heightValue = $(this).attr('value');
            var proportion = $('body').find('input#media-proportion');
            if (proportion.is(':checked')) {
                var ratio = getImageMediaRatio();
                var widthInput = $('body').find('input#media-width');
                widthInput.val(Math.round(heightValue * ratio));
            }
        });
        $("body").on('change', 'input#media-width', function () {
            var widthValue = $(this).attr('value');
            var proportion = $('body').find('input#media-proportion');
            if (proportion.is(':checked')) {
                var ratio = getImageMediaRatio();
                var heightInput = $('body').find('input#media-height');
                heightInput.val(Math.round(widthValue / ratio));
            }
        });

        $("body").on('click', '.media-crop', function () {
            var x1 = $('#x1').value;
            var y1 = $('#y1').value;
            var w = $('#w').value;
            var h = $('#h').value;
            var img = $(this).parents().find('img.media');
            /*
             * TODO 
             * need to complete
             */
        });
        $('#coords').on('change', 'input', function (e) {
            var x1 = $('#x1').val(),
                    x2 = $('#x2').val(),
                    y1 = $('#y1').val(),
                    y2 = $('#y2').val();
            jcrop_api.setSelect([x1, y1, x2, y2]);
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
                    $('#ajax-content').fadeOut('slow');
                    $("#global-loader").addClass('show').delay(800).queue(function () {
                        $('#ajax-content').html(datas.content).addClass('hide').fadeIn('slow').removeClass('hide');
                        $('title').html(datas.title);
                        init();
                        $("#global-loader").removeClass('show');
                        //update token
                        $('input#csrf').val(datas.csrf);
                        $(this).dequeue();
                    });
                },
                error: function () {
                    window.location.reload();
                }
            });
        }
        function init() {
            // forms with wysiwyg plugin
            formsWysiwyg();

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
            inputSwitch();
            datepicker();

            // reset
            rotateStep = 0;
            flipHSep = 0;
            flipVSep = 0;

            crop();
        }


        function inputSwitch() {
            $('body').find("[data-toggle='switch']").each(function () {
                if ($(this).parent().hasClass('switch-animate') == true) {
                    var parent = $(this).parent().parent().parent();
                    var input = $(this).parent().find('input');
                    $(this).parent().parent().remove();
                    parent.html(input);

                }
                $(this).wrap('<div class="switch"></div>').parent().bootstrapSwitch();
            });
        }

        function datepicker() {
            $('body').find('.bootstrap-datetimepicker-widget').each(function () {
                $(this).remove();
            });

            $('body').find('.datepicker').each(function () {
                $(this).datetimepicker({
                    language: 'fr',
                    pickTime: false,
                    format: "YYYY-MM-DD"
                });
            });
        }

        function tablesData() {
            //pages info
            $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": oSettings._iDisplayLength === -1 ?
                            0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": oSettings._iDisplayLength === -1 ?
                            0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };
            //tables (managed by dataTable)
            $('table.datatable').each(function () {
                var id = $(this).attr('id');
                if (id !== "undefined") {
                    datatables[id] = $('#' + id).dataTable({
                        "bDestroy": true,
                        "bStateSave": true,
                        "bJQueryUI": true,
                        "sPaginationType": "full_numbers",
                        "bProcessing": true,
                        "oSearch": {"bSmart": false},
                        "iDisplayLength": 10,
                        "dom": 'fptip',
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
                        },
                        "fnDrawCallback": function () {
                            tablesEditable();
                            iconSelect();

                        }
                    });
                    // custom input search and pagination filter
                    $("body").on("keyup", ".filter-search", function () {
                        $("#global-loader").addClass('show');
                        datatables[id].fnFilter($(this).val());
                        $("#global-loader").removeClass('show');
                    });
                    if ($('.filter-search').length !== 0 && $('#' + id + '_filter').length !== 0) {
                        $('#' + id + '_filter').remove();
                    }
                    $("body").on("change", ".filter-number", function () {
                        setDefaultInputs();
                        $("#global-loader").addClass('show');
                        var perPage = $("select.filter-number option").filter(":selected").val();
                        var oSettings = datatables[id].fnSettings();
                        if (perPage === 'all') {
                            oSettings._iDisplayLength = 9999999999999; //TODO FIX: need disabled paging and reload
                        } else
                            oSettings._iDisplayLength = perPage;

                        datatables[id].fnDraw();
                        $("#global-loader").removeClass('show');


                    });

                    if ($('.filter-number').length !== 0 && $('#' + id + '_length').length !== 0)
                        $('#' + id + '_length').remove();
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
                        update(url, getInputsTr(tr));
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
                }
                else
                    datas[this.name] = this.value;
            });
            $("body").find('textarea').each(function () {
                datas[this.name] = this.value;
            });

            $("body").find('option:selected').each(function () {
                datas[this.name] = this.value;
            });

            return datas;
        }

        function getInputsTr(tr) {
            var inputs = {};
            tr.find('th, td').each(function () {
                var name = $(this).attr('name');
                if (typeof (name) !== "undefined") {
                    inputs[name] = $(this).html();
                }
                $(this).find('input').each(function () {
                    if (this.type === 'checkbox') {
                        if ($(this).is(':checked'))
                            inputs[this.name] = 'on';
                    }
                    else
                        inputs[this.name] = this.value;
                });
            });
            tr.find('option:selected').each(function () {
                inputs[this.name] = this.value;
            });

            return inputs;
        }

        function setDefaultInputs() {
            $("body").find(':input').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    defaultInputs[this.name] = this.value;
            });
            $("body").find('textarea').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    defaultInputs[this.name] = this.value;
            });
            $("body").find('option:selected').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    defaultInputs[this.name] = this.value;
            });
        }
        function restoreDefaultInputs() {
            $("body").find(':input').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    $(this).val(defaultInputs[this.name]);
            });
            $("body").find('textarea').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    $(this).val(defaultInputs[this.name]);
            });
            $("body").find('option:selected').each(function () {
                if (this.name !== "" && typeof (this.name) !== "undefined")
                    $(this).val(defaultInputs[this.name]);
            });
        }

        function remove(url, tableId, urlsList) {
            $("#global-loader").addClass('show');
            $.ajax({
                type: 'POST',
                url: url,
                data: {'csrf': $('input#csrf').val()},
                dataType: "json",
                success: function (datas) {
                    if (datas.notifySuccess !== null) {
                        // update datatable
                        if (typeof (tableId) !== "undefined") {
                            //restore inputs default value
                            restoreDefaultInputs();
                            //update table content
                            $('#' + tableId).html(datas.content);


                            //reload plugins tables
                            if (datatables[tableId] !== "undefined") {
                                var datatablePages = datatables[tableId].fnPagingInfo();
                            }
                            tablesData();
                            //set current table
                            if (datatables[tableId] !== "undefined") {
                                var currentPage = datatablePages.iPage;
                                if (datatables[tableId].fnPagingInfo().iTotalPages <= currentPage)
                                    currentPage = currentPage - 1;
                                datatables[tableId].fnPageChange(currentPage);
                            }

                            tablesEditable();
                            iconSelect();
                            inputSwitch();
                            datepicker();
                        }
                    }

                    //update token
                    $('input#csrf').val(datas.csrf);
                    $("#global-loader").removeClass('show');

                    if (urlsList && urlsList.length !== 0) {
                        var url = urlsList[0];
                        urlsList.splice(0, 1);
                        remove(url, tableId, urlsList);
                    }
                },
                error: function () {
                    window.location.reload();
                }
            });
        }

        function add(tableId, inputs, url) {
            $("#global-loader").addClass('show');
            inputs['csrf'] = $('input#csrf').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: inputs,
                dataType: "json",
                success: function (datas) {
                    if (datas.notifySuccess !== null) {
                        //update datatable
                        if (typeof (tableId) !== "undefined") {
                            //restore inputs default value
                            restoreDefaultInputs();
                            $('#' + tableId).html(datas.content);


                            //reload plugins tables
                            if (datatables[tableId] !== "undefined") {
                                var datatablePages = datatables[tableId].fnPagingInfo();
                            }
                            tablesData();
                            //set current table
                            if (datatables[tableId] !== "undefined") {
                                if ((datatablePages.iPage + 1) < datatables[tableId].fnPagingInfo().iTotalPages)
                                    datatables[tableId].fnPageChange(datatablePages.iPage + 1);

                            }

                            tablesEditable();
                            iconSelect();
                            inputSwitch();
                            datepicker();
                        }
                    }

                    // update token
                    $('input#csrf').val(datas.csrf);

                    $("#global-loader").removeClass('show');
                },
                error: function () {
                    window.location.reload();
                }
            });
        }



        function update(url, inputs) {
            $("#global-loader").addClass('show');
            inputs['csrf'] = $('input#csrf').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: inputs,
                dataType: "json",
                success: function (datas) {
                    if (datas.notifySuccess !== null) {
                        if (typeof (datas.mediaImageSrc) !== "undefined" && datas.mediaImageSrc !== null) {
                            //update image src
                            $('body').find('#media-block img').attr('src', datas.mediaImageSrc + '?' + Math.floor(Math.random() * 100)).removeAttr('style');
                            $('body').find('#media-block').css('margin-bottom', 10);
                            //update input height/width
                            $('body').find('input#media-height-default').val(datas.mediaImageHeight);
                            $('body').find('input#media-height').val(datas.mediaImageHeight);
                            $('body').find('input#media-width-default').val(datas.mediaImageWidth);
                            $('body').find('input#media-width').val(datas.mediaImageWidth);
                            $('body').find('input#size').val(datas.mediaImageSize);
                            // reset
                            resetMediaManipulate();

                        }
                    }
                    // update token
                    $('input#csrf').val(datas.csrf);
                    $("#global-loader").removeClass('show');
                },
                error: function () {
                    window.location.reload();
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
                            formData.append("csrf", $('input#csrf').val());
                        });
                        this.on("success", function (file, response) {
                            if (response.notifySuccess !== null) {
                                //update datatable
                                if (typeof ('media') !== "undefined") {
                                    //restore inputs default value
                                    restoreDefaultInputs();
                                    $('#media').html(response.content);


                                    //reload plugins tables
                                    if (datatables['media'] !== "undefined") {
                                        var datatablePages = datatables['media'].fnPagingInfo();
                                    }
                                    tablesData();
                                    //set current table
                                    if (datatables['media'] !== "undefined") {
                                        if ((datatablePages.iPage + 1) < datatables['media'].fnPagingInfo().iTotalPages)
                                            datatables['media'].fnPageChange(datatablePages.iPage + 1);

                                    }

                                    tablesEditable();
                                    iconSelect();
                                }
                            }
                            // update token
                            $('input#csrf').val(response.csrf);
                        });
                        this.on("error", function (file, response) {
                            window.location.reload();
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


        function formsWysiwyg() {
            $('.wysiwyg').each(function () {
                var id = $(this).attr('id');
                if (id !== "undefined")
                    wysiwygs[id] = $('#' + id).summernote({
                        height: 450, // set editor height
                        minHeight: null, // set minimum height of editor
                        maxHeight: null, // set maximum height of editor
                        focus: true // set focus to editable area after initializing summernote
                    });
            });
        }


        function resetMediaManipulate() {
            rotateStep = 0;
            flipHSep = 0;
            flipVSep = 0;
            $('body').find('input#rotate').val(0);
            $('body').find('input#flipV').val(0);
            $('body').find('input#flipH').val(0);
            if (jcrop_api) {
                jcrop_api.destroy();
                clearCoords();
            }
        }

        function rotateMediaImage(rotateStep, img) {
            var transform = 'rotate(' + rotateStep + 'deg)';
            if (flipHSep !== 0)
                transform += ' scaleX(' + flipHSep + ')';
            if (flipVSep !== 0)
                transform += ' scaleY(' + flipVSep + ')';
            img.css('transform', transform);


            //align
            if (rotateStep === -90 || rotateStep === -270 || rotateStep === 90 || rotateStep === 270) {
                img.css('margin-top', ((img.width() - img.height()) / 2) + 15);
                img.css('margin-left', ((img.height() - img.width()) / 2));
                img.parent('p#media-block').css('margin-bottom', (img.width() - img.height()) / 2);
            } else {
                img.css('margin-top', 0);
                img.css('margin-left', 0);
                img.parent('p#media-block').css('margin-bottom', 0);
            }

            //update inputs
            img.parent('p#media-block').find('input#rotate').val(getRotationDegrees(img));
            var height = $('body').find('input#media-height-default').attr('value');
            var width = $('body').find('input#media-width-default').attr('value');
            if (rotateStep === -90 || rotateStep === -270 || rotateStep === 90 || rotateStep === 270) {
                $('body').find('input#media-height').val(width);
                $('body').find('input#media-width').val(height);
            } else {
                $('body').find('input#media-height').val(height);
                $('body').find('input#media-width').val(width);
            }
        }

        function getImageMediaRatio() {
            return $('body').find('input#media-width-default').attr('value') / $('body').find('input#media-height-default').attr('value');
        }

        function getRotationDegrees(img) {
            var angle = 0;
            var matrix = img.css("-webkit-transform") ||
                    img.css("-moz-transform") ||
                    img.css("-ms-transform") ||
                    img.css("-o-transform") ||
                    img.css("transform");
            if (typeof matrix === 'string' && matrix !== 'none') {
                var values = matrix.split('(')[1].split(')')[0].split(',');
                var a = values[0];
                var b = values[1];
                angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
            }
            return angle;
        }



        function crop() {
            $('img.crop').Jcrop({
                onChange: showCoords,
                onSelect: showCoords,
                onRelease: clearCoords
            }, function () {
                jcrop_api = this;
            });
        }
        function showCoords(c) {
            var realH = $('body').find('input#media-height-default').attr('value');
            var h = $('img.crop').height();
            var ratioH = realH / h;
            var realW = $('body').find('input#media-width-default').attr('value');
            var w = $('img.crop').width();
            var ratioW = realW / w;
            var x1 = Math.round(c.x * ratioW);
            var y1 = Math.round(c.y * ratioH);
            var w = Math.round(c.w * ratioW);
            var h = Math.round(c.h * ratioH);
            $('#x1').val(x1);
            $('#y1').val(y1);
            $('#w').val(w);
            $('#h').val(h);
            $('#x2').val(x1 + w);
            $('#y2').val(y1 + h);
        }

        function clearCoords() {
            $('#coords input').val('');
        }


    });
})(jQuery);