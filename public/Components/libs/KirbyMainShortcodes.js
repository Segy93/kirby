(function () {
    "use strict";

    var config = {
        pattern_twitter   : /\[embed-twitter .*? .*?\]/g,
        pattern_instagram : /\[embed-instagram .*?]/g,
    };


    var initEmbeds = function(wrapper) {
        var wrapper = wrapper;
        var html = wrapper.innerHTML;

        var data = {
            'twitter'   : [],
            'instagram' : [],
            'facebook'  : [],
            'youtube'   : [],
        };

        var pattern_twitter = config.pattern_twitter;
        var embeds_twitter = html.match(pattern_twitter);
        if (embeds_twitter !== null) {
            var data_twitter = data.twitter;
            for (var i = 0, l = embeds_twitter.length; i < l; i++) {
                var embed_data = embeds_twitter[i].match(/\[embed-twitter (.*?) (.*?)\]/);
                data_twitter.push({
                    'user': embed_data[1],
                    'id':   embed_data[2],
                });
            }
        }

        var pattern_instagram = config.pattern_instagram;
        var embeds_instagram = html.match(pattern_instagram);
        if (embeds_instagram !== null) {
            var data_instagram = data.instagram;
            for (var i = 0, l = embeds_instagram.length; i < l; i++) {
                var embed_data = embeds_instagram[i].match(/\[embed-instagram (.*?)]/);
                data_instagram.push({
                    'post_id': embed_data[1],
                });
            }
        }

        fetchEmbeds(data, wrapper);
    };

    /**
     * Dati HTML string preformatira tako sto
     * [iframe ...] shortcode-ove
     * menja <iframe ...> objektima
     * @param   {string}    text            HTML koji hocemo da dekodiramo
     * @return  {string}                    Dekodiran string
     */
    var decodeEmbed = function(text) {
        return decodeEmbedInstagram(text)
            .replace(/&#34;/g, '"')
            .replace(/(\[)embed.*?"(.*?)"(\])/ig, "<iframe src='$2'></iframe>")
        ;
    };

    var embedPaste = function (args) {
        var matches_tw      = args.content.match(/http(?:s)?:\/\/twitter.com\/(.*)\/status\/(.*?)&/);
        var matches_in      = args.content.match(/http(?:s)?:\/\/www\.instagram.com\/p\/(.*?)\//);
        var matches_fb_yt   = args.content.match(/(<|\&lt;)iframe(.*?)src="\/*(.*?)"(.*?)(>|\&gt;)(<|\&lt;)\/iframe(>|\&gt;)/ig);

        if (matches_tw !== null) {
            var user_id     = matches_tw[1];
            var status_id   = matches_tw[2];
            args.content    = '[embed-twitter ' + user_id + ' ' + status_id + ']';
        } else if (matches_in !== null) {
            var post_id     = matches_in[1];
            args.content    = '[embed-instagram ' + post_id+ ']';
        } else if (matches_fb_yt !== null) {
            args.content = args.content.replace(
                /(<|\&lt;)iframe(.*?)src="\/*(.*?)"(.*?)(>|\&gt;)(<|\&lt;)\/iframe(>|\&gt;)/ig,
                '[embed "$3" $4]'
            )
            .replace(/http[s]:\/\/?/, "//");
        }
    }


    /**
     * Menja shortcode za dospeli html
     * @param  {[type]} embeds  rezultat sa oembed-a
     * @param  {[type]} wrapper div u kojem treba da zameni shortkodove za html
     */
    var renderEmbeds = function(embeds, wrapper) {
        var html = wrapper.innerHTML;

        var pattern_twitter = /\[embed-twitter .*? .*?\]/g;
        var matches_twitter = html.match(pattern_twitter);


        var pattern_instagram = /\[embed-instagram .*?]/g;
        var matches_instagram = html.match(pattern_instagram);

        if (matches_twitter !== null) {
            for (var i = 0, l = embeds.twitter.length; i < l; i++) {
                html = html.replace(matches_twitter[i], embeds.twitter[i]);
            }
        }

        if (matches_instagram !== null) {
            for (var i = 0, l = embeds.instagram.length; i < l; i++) {
                html = html.replace(matches_instagram[i], embeds.instagram[i]);
            }
        }

        wrapper.innerHTML = html;
        if (typeof twttr !== "undefined") twttr.widgets.load();
        if (typeof instgrm !== "undefined") instgrm.Embeds.process();
    };


    /**
     * Dohvata oembed objekat za date shortkodove
     * @param  {[type]} embed_data Kolekcija shortkodova
     * @param  {[type]} wrapper    Element koji sadrzi shortkodove
     */
    var fetchEmbeds = function(embed_data, wrapper) {
        Kirby.Main.Ajax(
            "Editor",
            "fetchEmbeds",
            embed_data,
            renderEmbeds,
            wrapper
        );
    };










    /**
     * Daje mogucnost enkodiranja i dekodiranja shortcode-ova za TinyMCE
     */
    window.Kirby.Main.Shortcodes = {
        /*decodeEmbed: decodeEmbed,*/
        initEmbeds: initEmbeds,
        embedPaste: embedPaste,
    };

}());
