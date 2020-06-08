
function navroll(state) {
    if(state === true)
    {
        if(window.screen.height>=1024)
            document.getElementById("main").style.marginLeft = "250px";
    }
    else
        document.getElementById("main").style.marginLeft = "0px";
}