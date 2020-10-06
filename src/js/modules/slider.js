document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".stuffs-maintenance__slider");
  const slides = document.querySelectorAll(".stuffs-maintenance__slide");

  const getCurrentSlide = () =>
    Array.from(slides).find((slide) => slide.hasAttribute("active"));

  const getNextSlide = (currentIndex) =>
    currentIndex < slides.length - 1 ? slides[parseInt(currentIndex) + 1] : slides[0];

  const setNewActive = (toActivate, toDeactivate) => {
    toActivate.setAttribute("active", "");
    toDeactivate.removeAttribute("active");
  };

  const handleSlideChange = () => {
    const currentSlide = getCurrentSlide();
    const nextSlide = getNextSlide(currentSlide.getAttribute("data-index"));
    setNewActive(nextSlide, currentSlide);
  };
  const stopSlider = clearInterval(sliderInterval);

  const sliderInterval = setInterval(handleSlideChange, 7000);
});
