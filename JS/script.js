window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (window.scrollY > 150) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

function topFunction() {
    window.scrollTo({ top: 0, behavior: 'smooth' });

}