const burger = document.querySelector('.navbar-burger');
const menu = document.querySelector('.liens');

burger.addEventListener('click', function () {
    
    burger.classList.toggle('active');
    menu.classList.toggle('active');
});

