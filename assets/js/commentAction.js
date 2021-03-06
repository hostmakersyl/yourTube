function postComment (button, postedBy, videoId, replyTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val("");

    if(commentText){
        $.post("ajax/postComment.php", {commentText: commentText, postedBy: postedBy, videoId: videoId, responseTo: replyTo })
        .done(function(comment){
            if(!replyTo){
                $("." + containerClass).prepend(comment);
            } else {
                $(button).parent().siblings("." + containerClass.append(comment));
            }
            
        });
    } else {
        alert("You can not post an empty comment");
    }
}


function toggleReply(button){
    var parent = $(button).closest(".itemContainer");
    var commentForm = parent.find(".commentForm").first();
    
    commentForm.toggleClass("hidden");
}


function likeComment(commentId, button, videoId) {
    $.post("ajax/likeComment.php", {commentId: commentId, videoId: videoId})
    .done(function(numToChange) {
        var likeButton = $(button);
        var disLikeButton = $(button).siblings(".disLikeComment");

        likeButton.addClass("active");
        disLikeButton.removeClass("active");

        var likesCount = $(button).siblings(".likesCount");
        updateLikesValue(likesCount, numToChange);
        
        

        if(numToChange < 0) {
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png");
        } else {
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up-active.png")
        }

        disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")

    });
}

function disLikeComment(commentId, button, videoId) {
    $.post("ajax/disLikeComment.php", {commentId: commentId, videoId: videoId})
    .done(function(numToChange) {
        var disLikeButton = $(button);
        var likeButton = $(button).siblings(".likeComment");

        disLikeButton.addClass("active");
        likeButton.removeClass("active");

        var likesCount = $(button).siblings(".likesCount");
        updateLikesValue(likesCount, numToChange);
        
        

        if(numToChange > 0) {
            disLikeButton.removeClass("active");
            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png");
        } else {
            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down-active.png")
        }

        likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png")

    });

}

function updateDisLikesValue(element, num) {
    var likesCountVal = element.text() || 0 ;
    element.text(parseInt(likesCountVal) + parseInt(num));
}

function getReplies(commentId, button, videoId){
    $.post("ajax/getCommentReplies.php", {commentId: commentId, videoId: videoId})
    .done(function(comment) {
        var replies = $("<div>").addClass("repliesSection");
        replies.append(comment);

        $(button).replaceWith(replies);
    });
}