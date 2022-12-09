function darkMode() {
    var body = document.body;
    body.classList.toggle("dark-mode");
    var h1 = document.querySelector("h1");
    h1.classList.toggle("dark-mode");
    var a = document.querySelector("a");
    a.classList.toggle("dark-mode");
    var p = document.querySelector("p");
    p.classList.toggle("dark-mode");
    // document.querySelector("h1").style.color    = "red";
    
}

// $(document).ready(function(){
//     //Lors du clic sur le bouton #b1...
//     $("#b1").click(function(){
//         //Toggle la classe et accroche des styles à chaque classe
//         $("body").toggleClass("light dark-mode");
//         $(".light").css({"background-color": "#", "color": "#121212"});
//         $(".dark-mode").css({"background-color": "#121212", "color": "#ffffff"});
//     });
// });