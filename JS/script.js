window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (window.scrollY > 100) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

function topFunction() {
    // Wenn du eine sanfte Scroll-Animation bevorzugst, verwende die folgende Zeile
    window.scrollTo({ top: 0, behavior: 'smooth' });

}