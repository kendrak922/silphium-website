  if(window.innerWidth <= 821){
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        spaceBetween: 20, 
        slidesPerView: 1, 
        loop: true,
    
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
      });
  }
  window.addEventListener('load', function () {
    const gridElem = document.querySelector('.testimonial--desktop');
  
    if (gridElem) {
      imagesLoaded(gridElem, function () {
        new Masonry(gridElem, {
          itemSelector: '.testimonial__single',
          columnWidth: 520,
          gutter: 40,
        });
      });
    } else {
      console.warn("No .testimonial element found.");
    }
  });