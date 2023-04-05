(function () {
    "use strict";

    var elements = {
        form_comment:         "#comment_single__create",
        form_reply:           ".comment_single__reply_form",
        approve_comment:      ".comment_single__approve",
        reply_toggle:         ".comment_single__reply_toggle",
        reply_field:          ".comment_single__reply_text",
    };



    var config = {
        //node_id: Monitor._params.CommentsList.node_id,
    };






    var init = function(event) {
        registerElements();
        initListeners();
        // initTimeAgo();
    };

    var initListeners = function() {
        var forms_reply = getElement("form_reply", true);
        for (var i = 0, l = forms_reply.length; i < l; i++) {
            forms_reply[i].addEventListener("submit", submitReplyForm, false);
            forms_reply[i].addEventListener("keyup", formKeyUp, false);
        }

        var reply_toggle = getElement("reply_toggle", true);
        for (var i = 0, l = reply_toggle.length; i < l; i++) {
            reply_toggle[i].addEventListener("change", replyFocus, false);
        }

        var comment_approve = getElement("approve_comment", true);
        for (var i = 0, l = comment_approve.length; i < l; i++) {
            comment_approve[i].addEventListener("click", changeStatusClicked, false);
        }


        document.addEventListener("Monitor.Comment.InitListeners", initListeners, false);
    };

    var registerElements = function() {
        Monitor.Main.DOM.register("CommentSingle", elements);
    };

    // var initTimeAgo = function() {
    //     timeago().render(document.querySelectorAll('.comment_single__time'));
    // };






    var formKeyUp = function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            var form = event.currentTarget;
            submitReplyForm(event);
        }
    };




    var submitReplyForm = function(event) {
        event.preventDefault();

        var form     = event.currentTarget;
        var elements = form.elements;

        var type = elements.type.value;
        var node_id = parseInt(elements.node_id.value, 10);
        var parent_id = parseInt(elements.parent_id.value, 10);
        var message = elements.text.value;

        postMessageReply(message, node_id,type, parent_id);
        form.reset();
    };

    var changeStatusClicked = function(event) {
        var status      = parseInt(event.currentTarget.dataset.status, 10);
        var comment_id  = parseInt(event.currentTarget.dataset.commentId, 10);

        var status_next = status === 1 ? 0 : 1;

        changeCommentStatus(comment_id, status_next);
    }









    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("CommentSingle", element, query_all, modifier, parent);
    };







    var postMessageReply = function(message, node_id,type, parent_id) {
        if (typeof parent_id === "undefined") parent_id = null;

        Monitor.Main.Ajax(
            "CommentSingle",
            "postMessage",
            {
                node_id:    node_id,
                message:    message,
                type:       type,
                parent_id:  parent_id,
            },
            function (data) {
                var event   = new CustomEvent("Monitor.Comment.Added");
                document.dispatchEvent(event);
            }
        );
    };




    var changeCommentStatus = function(comment_id, status) {
        Monitor.Main.Ajax(
            "CommentSingle",
            "changeCommentStatus",
            {
                comment_id: comment_id,
                status: status,
            },
            function (data) {
                var event   = new CustomEvent("Monitor.Comment.Status");
                document.dispatchEvent(event);
            }
        );
    }

    var replyFocus = function(event) {
        var comment_id  = parseInt(event.currentTarget.dataset.commentId, 10);

        //Timeout zbog cekanja animacije na textarea, jer u suprotnom nece raditi focus!
        setTimeout(function(){
            getElement("reply_field", false, comment_id).focus();
        }, 100);
    }




    document.addEventListener("DOMContentLoaded", init);
}());
