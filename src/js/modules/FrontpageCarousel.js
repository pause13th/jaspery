import { tns } from "../../../node_modules/tiny-slider/src/tiny-slider";

export default class FrontpageCarousel {
  constructor() {
    tns({
      container: ".frontpage-carousel",
      items: 1,
      slideBy: "page",
      autoplay: true,
      controls: false,
      nav: false,
      autoplayButtonOutput: false,
      mouseDrag: true
    });
  }
}
