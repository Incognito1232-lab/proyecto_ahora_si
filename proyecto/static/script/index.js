let currentSlide = 0;
const slides = document.querySelectorAll('#carousel li');
const totalSlides = slides.length;

function showSlide(index) {
    // Asegúrate de que el índice esté dentro del rango
    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }

    // Mueve el carrusel
    const offset = -currentSlide * 100; // Mueve el carrusel
    document.getElementById('carousel').style.transform = `translateX(${offset}vw)`;
}

// Función para mover el carrusel
function moveSlide(direction) {
    showSlide(currentSlide + direction);
}

// Cambia de imagen automáticamente cada 5 segundos
setInterval(() => {
    showSlide(currentSlide + 1);
}, 5000);

// Muestra la primera imagen al cargar
showSlide(currentSlide);

function toggleNovedad(button) {
    const info = button.previousElementSibling; // Selecciona el contenedor de información adicional
    if (info.style.display === "none" || info.style.display === "") {
        info.style.display = "block"; // Muestra la información adicional
        button.textContent = "Leer menos"; // Cambia el texto del botón
    } else {
        info.style.display = "none"; // Oculta la información adicional
        button.textContent = "Leer más"; // Cambia el texto del botón
    }
}