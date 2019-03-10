function subscribe(userTo, userFrom, button){
    if(userTo == userFrom){
        alert("You can't subscribe in your account");
        return;
    }
    $.post("ajax/subscribe.php", {userTo: userTo, userFrom: userFrom})
    .done(function(count){
        if(count != null ){
            $(button).toggleClass("subscribe unSubscribe");
            var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";
            $(button).text(buttonText + " " + count);
        } else {
            alert("Something went to wrong");
        }
    })

}

