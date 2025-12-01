document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.mrm-testimonials-slider', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        slidesPerView: 1,
        grabCursor: true,
        centeredSlides: true,

        // If we need pagination
//         pagination: {
//             el: '.swiper-pagination',
//         },

        // Navigation arrows
        navigation: {
            nextEl: '.mrm-testimonials-slider__next-btn',
            prevEl: '.mrm-testimonials-slider__prev-btn',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },

        breakpoints: {
            1199: {
                slidesPerView: 3,
                spaceBetween: 220,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 160,
            }
        }
    });
	
	swiper.on('slideChangeTransitionEnd', function () {
		const pagination = document.getElementById('mrm-testimonial-swiper-pagination');
		for (const child of pagination.children) {
			child.className = "swiper-pagination-bullet";
		}
		pagination.children[swiper.realIndex % pagination.children.length].className += " swiper-pagination-bullet-active"
	});
	
	swiper.slideTo(5, 0);
});