//Année en cours dans le Footer
document.getElementById("year").textContent = new Date().getFullYear();

//BurgerNavBar Mobile
const burger = document.querySelector(".burger");
const nav = document.getElementById("mainnav");
if (burger && nav) {
  burger.addEventListener("click", () => {
    const open = burger.getAttribute("aria-expanded") === "true";
    burger.setAttribute("aria-expanded", String(!open));
    nav.classList.toggle("nav--open", !open);
    document.body.classList.toggle("nav-open", !open);
  });
}
