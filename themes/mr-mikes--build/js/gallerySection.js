const interiorBeforeSwiper = new Swiper('.interior-swiper-before', {
    // Navigation arrows
    navigation: {
        nextEl: '.interior-slider-before__next-btn',
        prevEl: '.interior-slider-before__prev-btn',
    },
});

const interiorAfterSwiper = new Swiper('.interior-swiper-after', {
    // Navigation arrows
    navigation: {
        nextEl: '.interior-slider-after__next-btn',
        prevEl: '.interior-slider-after__prev-btn',
    },
});

document.getElementById('interior-before-tab')
    .addEventListener('click', function (e) {
        e.preventDefault();
		beforeSwiper = document.getElementById("interior-before-swiper");
		afterSwiper = document.getElementById("interior-after-swiper");
		beforeSwiper.style.display = "block";
		afterSwiper.style.display = "none";
        tablinks = document.getElementsByClassName("interior-links");
        for (i = 0; i < tablinks.length; i ++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        e.currentTarget.className += " active";
    });

document.getElementById('interior-after-tab')
    .addEventListener('click', function (e) {
        e.preventDefault();
		beforeSwiper = document.getElementById("interior-before-swiper");
		afterSwiper = document.getElementById("interior-after-swiper");
		afterSwiper.style.display = "block";
		beforeSwiper.style.display = "none";
        tablinks = document.getElementsByClassName("interior-links");
        for (i = 0; i < tablinks.length; i ++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        e.currentTarget.className += " active";
    });

function openExterior(evt, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("exterior-content");
    for (i = 0; i < tabcontent.length; i ++) {
        tabcontent[i].style.display = 'none';
    }

    tablinks = document.getElementsByClassName("exterior-links");
    for (i = 0; i < tablinks.length; i ++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.getElementById("exterior-before-tab").click();
document.getElementById("interior-before-tab").click();