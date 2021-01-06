if(document.getElementById("navbar") != null){
    var categories = document.getElementById("navbar").getElementsByClassName("navbtn");

    for (var i = 0; i < categories.length; i++) {
        if(categories[i].id == document.location.pathname){
            categories[i].className = "selected";
        }
    }
}