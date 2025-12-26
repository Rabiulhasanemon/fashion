(function () {
    function loadImage(el, fn) {
        var img = new Image()
            , src = el.getAttribute('data-src');
        img.onload = function () {
            if (!!el.parent)
                el.parent.replaceChild(img, el)
            else
                el.src = src;

            fn ? fn() : null;
        }
        img.src = src;
    }

    function elementInViewport(el) {
        var rect = el.getBoundingClientRect()

        return (
            rect.top >= 0
            && rect.left >= 0
            && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
        )
    }

    var images = [] ;
    var processScroll = function () {
        var toRemove = []
        for (var i = 0; i < images.length; i++) {
            if (elementInViewport(images[i])) {
                loadImage(images[i]);
                toRemove.push(images[i])
            }
        }
        toRemove.forEach(function (element) {
            var index = images.indexOf(element);
            if (index > -1) {
                images.splice(index, 1);
            }
        })
    };

    $(function () {
        $('img.lazy').each(function () {
            images.push(this);
        });
        processScroll();
        window.addEventListener('scroll', processScroll);
    });

})();