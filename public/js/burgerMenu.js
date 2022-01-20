const button = document.querySelector(".burger") 
const menu = document.querySelector(".textMenuBurger")


button.addEventListener("click", event => {
    menu.classList.toggle("show")
    button.classList.toggle("croixBurger")
})