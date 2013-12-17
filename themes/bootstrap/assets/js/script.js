$.stellar.scrollProperty.margin = {
    getLeft: function($element) {
        return parseInt($element.css('margin-left'), 10) * -1;
    },
    getTop: function($element) {
        return parseInt($element.css('margin-top'), 10) * -1;
    }
}

var $body = $(document.body);

var navHeight = $('.navbar').outerHeight(true) + 10;

$body.scrollspy({
    target: '.bs-sidebar',
    offset: navHeight
});