function likeVideo(button, videoId) {
    $.post("ajax/likesVideo.php", {videoId: videoId})
    .done(function(data) {
        var likeButton = $(button);
        var disLikeButton = $(button).siblings(".disLikeButton");

        likeButton.addClass("active");
        disLikeButton.removeClass("active");

        var result = JSON.parse(data);
        updateLikesValue(likeButton.find(".text"), result.likes);
        updateLikesValue(disLikeButton.find(".text"), result.disLikes);
        

        if(result.likes < 0) {
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png");
        } else {
            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up-active.png")
        }

        disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png")

    });
}


function updateLikesValue(element, num) {
    var likesCountVal = element.text() || 0 ;
    element.text(parseInt(likesCountVal) + parseInt(num));
}

function disLikeVideo(button, videoId) {
    $.post("ajax/disLikesVideo.php", {videoId: videoId})
    .done(function(data) {
        var disLikeButton = $(button);
        var likeButton = $(button).siblings(".likeButton");

        
        disLikeButton.addClass("active");
        likeButton.removeClass("active");

        var result = JSON.parse(data);
        updateDisLikesValue(likeButton.find(".text"), result.likes);
        updateDisLikesValue(disLikeButton.find(".text"), result.disLikes);
        

        if(result.disLikes < 0) {
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

