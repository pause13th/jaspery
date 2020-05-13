export default class MobileMenu {
  constructor() {
    this.theHeader = document.querySelector(".site-header .wrap");

    this.insertHTML();

    this.theButton = document.querySelector(".mobile-menu-button");
    this.theMenu = document.querySelector(".nav-primary");
    this.events();
  }

  events() {
    this.theButton.addEventListener("click", e => {
      e.preventDefault();
      console.log(this.theButton);
      this.toggleMobileMenu();
    });
  }

  toggleMobileMenu() {
    if (this.theMenu.classList.contains("visible")) {
      this.theMenu.classList.remove("visible");
    } else {
      this.theMenu.classList.add("visible");
    }
  }

  insertHTML() {
    this.theHeader.insertAdjacentHTML(
      "beforeend",
      `<button class="mobile-menu-button"></button>`
    );
  }
}
