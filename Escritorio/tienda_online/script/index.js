// Seleccionamos todos los elementos que vamos a observar y aplicar el efecto
const zap = document.getElementsByClassName("zap");//Aqui hemos seleccionado el primer elemento de zap(del titilo)
//Seleccionamos todas las imagenes de los logos
const logos = document.querySelectorAll(".logos img");
const store = document.getElementsByClassName("store");
// Aqui creamos el observador
let observador = new IntersectionObserver((entries,observador)=>{
    entries.forEach(elemento => {
        if(elemento.isIntersecting){
            elemento.target.classList.add("visible");
            observador.unobserve(elemento.target);//Al observar ya dejamos de observarlo,entonces nunca se aplicara otra vez al realizar scroll
        }
    });
},{threshold: 0.8});
//Aqui le indicamos que observar
//Foreach para recorrer varios elmentos
Array.from(zap).forEach(el => observador.observe(el));
Array.from(store).forEach(el => observador.observe(el));
logos.forEach(img => observador.observe(img));
