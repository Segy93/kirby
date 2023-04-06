(function () {
    "use strict";

    var elements = {
        comment_more:     ".comments__more",
        form_comment:     "#comments__create",
        node_id:          "#comments_list__node_id",
        type:             "#comments_list__page_type",
        wrapper:          ".comments__list",
    };

    var templates = {
        main: function() {},
    };

    var config = {
        limit: 20,
    };










    var init = function(event) {
        registerElements();
        initTemplates();
        initListeners();
    };

    var initListeners = function() {
        var form_comment = getElement("form_comment");
        // kada korisnik nije logovan ne postoji forma
        if (form_comment !== null) form_comment.addEventListener("submit", submitCommentForm, false);
        if(getElement("comment_more") !== null) getElement("comment_more").addEventListener("click", loadMore, false);

        if (getElement("form_comment"))getElement("form_comment").addEventListener("keyup", formKeyUp, false);

        document.addEventListener("Kirby.Comment.Added", dataChanged, false);
        document.addEventListener("Kirby.Comment.Status", dataChanged, false);
    };

    var initTemplates = function() {
        var html = document.getElementById("comment_list__tmpl").innerHTML;
        templates.main = _.template(html);
    };

    var registerElements = function() {
        Kirby.Main.Dom.register("CommentList", elements);
    };









    var dataChanged = function() {
        var node_id = parseInt(getElement("node_id").value, 10);
        var type    = getElement("type").value;
        var limit   = config.limit + 1;
        fetchData(node_id, type, limit);
    };

    var loadMore  = function() {
        config.limit +=  2;

        dataChanged();
    };






    var formKeyUp = function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            var form = event.currentTarget;
            submitCommentForm(event);
        }
    };



    var submitCommentForm = function(event) {
        event.preventDefault();

        var form    = event.currentTarget;
        var elems   = form.elements;

        var type    = getElement("type").value;
        var node_id = parseInt(elems.node_id.value, 10);
        var message = elems.text.value;

        postMessage(message, node_id, type);
        form.reset();
    };








    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("CommentList", element, query_all, modifier, parent);
    };









    var render = function(data) {
        var more        = data.length > config.limit;
        var more_button = getElement("comment_more");
        var wrapper     = getElement("wrapper");
        var html        = templates.main({
            comments: data,
        });
        more ?
            more_button.classList.remove("common_landings__visually_hidden") :
            more_button.classList.add("common_landings__visually_hidden");

        wrapper.innerHTML = html;

        var event   = new CustomEvent("Kirby.Comment.InitListeners");
        document.dispatchEvent(event);
    };






    var postMessage = function(message, node_id, type, parent_id) {
        if (typeof parent_id === "undefined") parent_id = null;

        Kirby.Main.Ajax(
            "CommentsList",
            "postMessage",
            {
                node_id:  node_id,
                message:  message,
                type:     type,
                parent_id:  parent_id,
            },
            function (data) {
                var event   = new CustomEvent("Kirby.Comment.Added");
                document.dispatchEvent(event);
            }
        );
    };



    var fetchData = function(node_id, type, limit) {
        Kirby.Main.Ajax(
            "CommentsList",
            "fetchData",
            {
                node_id:    node_id,
                type:       type,
                limit:      limit,
            },
            render
        );
    };



    document.addEventListener("DOMContentLoaded", init);
}());
