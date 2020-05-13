class SiteHeader {
  constructor(siteHeader, slimStartingFrom = 100) {
    this.siteHeader = document.querySelector(siteHeader);
    this.slimStartingFrom = slimStartingFrom;
    this.events();
  }

  events() {
    document.addEventListener("DOMContentLoaded", e => {
      this.addSlimClassToHeader(this.siteHeader);
    });

    document.addEventListener("scroll", e => {
      this.addSlimClassToHeader(this.siteHeader);
    });
  }

  addSlimClassToHeader(element) {
    let scrollTop = document.scrollingElement.scrollTop;
    if (scrollTop > this.slimStartingFrom) {
      element.classList.add("slim");
    } else {
      element.classList.remove("slim");
    }
  }
}

export default SiteHeader;
