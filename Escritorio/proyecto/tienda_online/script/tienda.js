//Seleccionamos las cartas de los productos
const cartas = document.querySelectorAll(".producto");
const articulos = document.querySelectorAll("article");
//Creamos el observador
let observador = new IntersectionObserver((entires,observador)=>{
    entires.forEach(carta=>{
        if(carta.isIntersecting){
            carta.target.classList.add("visible");
            observador.unobserve(carta.target);
        }
    });
},{threshold: 0.2});
cartas.forEach(elemento=>observador.observe(elemento));
articulos.forEach(articulo=>observador.observe(articulo));