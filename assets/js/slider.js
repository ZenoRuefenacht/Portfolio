document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".image-slider").forEach(slider => {
        let index = 0;
        const images = slider.querySelectorAll("img");
        const prevBtn = slider.querySelector(".prev");
        const nextBtn = slider.querySelector(".next");

        function updateSlider() {
            images.forEach((img, i) => {
                img.style.display = i === index ? "block" : "none";
            });
        }

        prevBtn.addEventListener("click", function () {
            index = (index - 1 + images.length) % images.length;
            updateSlider();
        });

        nextBtn.addEventListener("click", function () {
            index = (index + 1) % images.length;
            updateSlider();
        });

        updateSlider();
    });
});
