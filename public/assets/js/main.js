// Navbar interaction
var navbar = document.querySelector('.navbar');
var logo = document.querySelector('.navbar-brand img');
var prevScrollpos = window.pageYOffset;

window.onscroll = () => { scrollFunction() };

function scrollFunction() {
    if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {
        navbar.classList.add('navbar--scrolled');

        // show/hide navbar when down/up scroll
        var currentScrollPos = window.pageYOffset;
        
        if (prevScrollpos > currentScrollPos) {
            navbar.classList.remove('navbar--hide')
        } else {
            navbar.classList.add('navbar--hide')
        }
        prevScrollpos = currentScrollPos;

    } else {
        navbar.classList.remove('navbar--scrolled')
    }
}

// Navbar offcanvas
const body = $('body');

const offc_close = $('#menu-open');
const offc_open = $('#menu-close');
const offc_menu = $('#offcanvas-menu');
const link_to = $('.link-to');

// open
offc_open.click(function() {
    offc_menu.toggleClass('offcanvas-menu--open');
    body.toggleClass('offcanvas--open');
});

// close
offc_close.click(function() {
    offc_menu.toggleClass('offcanvas-menu--open');
    body.toggleClass('offcanvas--open');
});

//close when menu it's open
link_to.click(function () { 
    if(offc_menu.hasClass('offcanvas-menu--open') && body.hasClass('offcanvas--open')) {
        offc_menu.toggleClass('offcanvas-menu--open');
        body.toggleClass('offcanvas--open');
    }
});