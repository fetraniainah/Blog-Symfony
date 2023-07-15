var slide =  document.querySelectorAll('.carousel-item');
var content = document.querySelectorAll('#content')
console.log(content);


content.forEach(function(element) {
    var firstSlide = element.querySelector('.carousel-item:first-child');
    console.log(firstSlide);
    firstSlide.classList.add('active');
});
