(function () {
    "use strict";

    if (window.Kirby === undefined) {
        window.Kirby = {};
    }

    if (window.Kirby.Main === undefined) {
        window.Kirby.Main = {};
    }

    /*var alertFunc = function (data, editor) {
        //var html = data.html;
        //console.log(html, editor);
        //editor.insertContent(html);
    };

    var EditorEmbed = function(url, editor) {
        Kirby.Main.Ajax(
            "Editor",
            "getOembed",
            {
                url: url,
            },
            alertFunc,
            editor
        );
    };*/

    window.Kirby.Main.Editor = {
        initEditor: function (element, selector, callback, settings = null) {
            tinyMCE.baseURL = "/Components/libs/tinymce";
            if(settings && settings.type === "compact") {
                tinyMCE.init({
                    content_css: '/Components/libs/KirbyMainEditor.css',
                    filemanager_access_key: Kirby._params.fm_key,
                    fontsize_formats: "8pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt",
                    paste_retain_style_properties: "color font-size",
                    paste_webkit_styles: "color font-size",
                    paste_word_valid_elements: "b,strong,i,em,h1,h2",
                    plugins: "image link paste textcolor media responsivefilemanager textcolor colorpicker eqneditor ",
                    selector: selector,
                    theme_advanced_buttons3_add : "pastetext,pasteword,selectall",

                    image_advtab: true,

                    textcolor_map: [
                        "FAE405", "FAE405",
                        "FFC300", "FFC300",
                        "8FD306", "8FD306",
                        "3CB815", "3CB815",
                        "05720A", "05720A",
                        "18F476", "18F476",
                        "13AFC8", "13AFC8",
                        "0068FF", "0068FF",
                        "0D149E", "0D149E",
                        "0C14B5", "0C14B5",
                        "A939DD", "A939DD",
                        "C924AD", "C924AD",
                        "8A1276", "8A1276",
                        "EB179B", "EB179B",
                        "FD007F", "FD007F",
                        "9A0650", "9A0650",
                        "F72238", "F72238",
                        "F14E5F", "F14E5F",
                        "900C3F", "900C3F",
                        "FF0000", "Red",
                        "FFC200", "Amber",
                        "FFA500", "Orange",
                        "800080", "Purple",
                        "FF00FF", "Magenta",
                        "9acd32", "Yellow green",
                        "000000", "Black",
                        "808080", "Gray"
                    ],
                    textcolor_rows: 7,

                    external_filemanager_path:"/filemanager/",
                    filemanager_title:"Responsive Filemanager" ,


                    height: 100,
                    toolbar1: "undo redo | paste | styleselect | fontsizeselect forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |  eqneditor responsivefilemanager",
                    extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
                    convert_urls: true,
                    relative_urls: false,
                    remove_script_host: false,


                    setup: function (editor) {
                        editor.on('GetContent', function(e) {
                            var internal_link_filter = new RegExp('(src|href)="(https?:\/\/)(www\.)?'+ window.location.hostname, 'g');
                            var external_link_filter = new RegExp('(src|href)="(https?:\/\/)(www\.)?(.*)["]', 'g');
                            e.content = e.content.replace(internal_link_filter, "$1=\"");
                            //.replace(external_link_filter, "$1=\"//$5");
                            //e.content = e.content.replace(/(src|href)="(https?:\/\/)(www\.)?(.*)["]/g, "$1=\"//$4");


                        });
                    },

                }).then(function(editors) {
                    return callback(editors[0], element);
                }.bind(this));
            } else {
                tinyMCE.init({
                    content_css: '/Components/libs/KirbyMainEditor.css',
                    filemanager_access_key: Kirby._params.fm_key,
                    fontsize_formats: "8pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt",
                    paste_retain_style_properties: "color font-size",
                    paste_webkit_styles: "color font-size",
                    paste_word_valid_elements: "b,strong,i,em,h1,h2",
                    plugins: "image link paste textcolor media responsivefilemanager textcolor colorpicker",
                    selector: selector,
                    theme_advanced_buttons3_add : "pastetext,pasteword,selectall",

                    image_advtab: true,

                    textcolor_map: [
                        "FAE405", "FAE405",
                        "FFC300", "FFC300",
                        "8FD306", "8FD306",
                        "3CB815", "3CB815",
                        "05720A", "05720A",
                        "18F476", "18F476",
                        "13AFC8", "13AFC8",
                        "0068FF", "0068FF",
                        "0D149E", "0D149E",
                        "0C14B5", "0C14B5",
                        "A939DD", "A939DD",
                        "C924AD", "C924AD",
                        "8A1276", "8A1276",
                        "EB179B", "EB179B",
                        "FD007F", "FD007F",
                        "9A0650", "9A0650",
                        "F72238", "F72238",
                        "F14E5F", "F14E5F",
                        "900C3F", "900C3F",
                        "FF0000", "Red",
                        "FFC200", "Amber",
                        "FFA500", "Orange",
                        "800080", "Purple",
                        "FF00FF", "Magenta",
                        "9acd32", "Yellow green",
                        "000000", "Black",
                        "808080", "Gray"
                    ],
                    textcolor_rows: 7,

                    external_filemanager_path:"/filemanager/",
                    filemanager_title:"Responsive Filemanager" ,

                    height: 300,
                    toolbar1: "undo redo | paste | styleselect | fontsizeselect forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link responsivefilemanager media | eqneditor",
                    toolbar2: "Info Citat Baner",
                    extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
                    convert_urls: true,
                    relative_urls: false,
                    remove_script_host: false,
                    paste_preprocess: function(plugin, args) {
                        Kirby.Main.Shortcodes.embedPaste(args);
                        // var pattern_tw  =  /(https:\/\/(?:www\.)?twitter\.com\/.*\/status\/[0-9]*)\//;
                        // var pattern_in  =  /(https:\/\/(?:www\.)?instagram\.com\/p\/[A-z0-9]*(?:\/)*)/;
                        // //var pattern_fb  =  /(https:\/\/(?:www\.)?facebook\.com(?:\/[A-z0-9]*))\/.*/;
                        // if(args.content.match(pattern_in)) {
                        //     args.content = args.content.match(pattern_in)[1];
                        // }
                        // args.content    = args.content.replace(pattern_tw, '$1'+'/');
                        // args.content    = args.content.replace(pattern_in,'$1');
                        // args.content    = args.content.replace(/www\./, '');
                        // debugger;

                    },

                    setup: function (editor) {
                        editor.addButton('Info', {
                            text: 'Info',
                            icon: false,
                            onclick: function () {
                                editor.focus();
                                editor.selection.setContent(
                                        '<span class = "Monitor_editor__info_box" ">'
                                        + editor.selection.getContent() + '</span>');
                            },
                        });
                        editor.addButton('Citat', {
                            text: 'Citat',
                            icon: false,
                            onclick: function () {
                                editor.focus();
                                editor.selection.setContent(
                                        '<blockquote class = "Monitor_editor__cite_box"  ">'
                                        + editor.selection.getContent() + '</blockquote>');
                            },
                        });
                        /*editor.addButton('Baner', {
                            text: 'Baner',
                            icon: false,
                            onclick: function () {
                                editor.focus(); editor.selection.setContent('[google-banner]');
                            },
                        });*/

                        editor.on('GetContent', function(e) {
                            var internal_link_filter = new RegExp('(src|href)="(https?:\/\/)(www\.)?'+ window.location.hostname, 'g');
                            var external_link_filter = new RegExp('(src|href)="(https?:\/\/)(www\.)?(.*)["]', 'g');
                            e.content = e.content.replace(internal_link_filter, "$1=\"");
                            //.replace(external_link_filter, "$1=\"//$5");
                            //e.content = e.content.replace(/(src|href)="(https?:\/\/)(www\.)?(.*)["]/g, "$1=\"//$4");


                        });
                    },

                }).then(function(editors) {
                    return callback(editors[0], element);
                }.bind(this));
            }
        },
    };
}());
