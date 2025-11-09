//Année en cours dans le Footer
document.getElementById("year").textContent = new Date().getFullYear();

//BurgerNavBar Mobile
const burger = document.querySelector(".burger");
const nav = document.getElementById("mainnav");
if (burger && nav) {
  burger.addEventListener("click", () => {
    burger.classList.toggle("active");
    nav.classList.toggle("open"); // si tu veux ouvrir/fermer ton menu
  });
  burger.addEventListener("click", () => {
    const open = burger.getAttribute("aria-expanded") === "true";
    burger.setAttribute("aria-expanded", String(!open));
    nav.classList.toggle("nav--open", !open);
    document.body.classList.toggle("nav-open", !open);
  });
}

//Hauteur du bandeau admin

document.addEventListener("DOMContentLoaded", () => {
  const bandeau = document.querySelector(".bandeau_admin");
  if (bandeau) {
    const height = bandeau.offsetHeight;
    // on crée une variable CSS globale
    document.documentElement.style.setProperty(
      "--bandeau-height",
      `${height}px`
    );
  }
});
