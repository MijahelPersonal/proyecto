document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card");

    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    const botones = document.querySelectorAll("button");

    botones.forEach(boton => {
        boton.addEventListener("click", function () {
            boton.style.transform = "scale(0.95)";

            setTimeout(() => {
                boton.style.transform = "";
            }, 150);
        });
    });
});