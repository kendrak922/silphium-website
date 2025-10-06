const swiper = new Swiper(".swiper", {
  // Optional parameters
  spaceBetween: 50,
  slidesPerView: 1,
  loop: true,
  autoheight: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  centeredSlides: true,

  breakpoints: {
    400: {
      slidesPerView: 2,
      spaceBetween: 50
    },

  }

  // If we need pagination
  // pagination: {
  //   el: ".swiper-pagination",
  // },
});
