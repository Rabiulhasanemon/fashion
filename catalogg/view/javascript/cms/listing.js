app.onReady(window, "$", function () { $(function () {
    window.onpopstate = function(event) {
        location.reload(true)
    };

    var slider = $("#rang-slider"),
        inputs = [$("#range-to"), $("#range-from")],
        filterGroups = $(".filters .filter-group"),
        from = parseInt(slider.attr("data-from")),
        to = parseInt(slider.attr("data-to")),
        min =  parseInt(slider.attr("data-min")),
        max =  parseInt(slider.attr("data-max"));
    min = min === max ? 0 : min;
    slider = slider.get(0);

    function update() {
        var values = slider ? slider.noUiSlider.get() : [],  from = parseInt(values[0]), to =  parseInt(values[1]), search = location.search;
        if(from !== min || to !== max) {
            search = updateURLVar(search, "filter_price", from + "-" + to)
        } else {
            search = updateURLVar(search, "filter_price", "")
        }
        var loader = $("<div class='popup f-in'><div class='loader popup-inner'></div></div>");
        $("body").append(loader);
        var filters = [];
        var categories = null
        filterGroups.each(function () {
            var result = [],
                group = $(this);
            group.find(".filter input").each(function (i, filter) {
                if(filter.checked) result.push(filter.value)
            });
            if(result.length > 0) {
                if(group.attr("data-group-type") === "category") {
                    categories = result.join(",")
                } else {
                    filters.push(result.join(","))
                }
            }
        });
        search = updateURLVar(updateURLVar(search, "page"), "filter", filters.join("_"));
        search = updateURLVar(search, "filter_category", categories);

        var url = location.pathname + search;
        if(localStorage.reloadPage === true) {
            location.href = url;
            return
        }
        $.ajax({
            url: url,
            success: function (resp) {
                try {
                    resp = $(resp);
                    history.pushState(null, "", url);
                    $("#content .main-content").html(resp.find("#content .main-content").html());
                    $("#content .bottom-bar").html(resp.find("#content .bottom-bar").html());
                    loader.remove();
                    addClearFix && addClearFix()
                } catch (ex) {
                    localStorage.reloadPage = true;
                    location.href = url
                }
            }
        })

    }

    var selectedFilters = [];
    decodeURIComponent(getURLVar("filter")).split("_").forEach(function (value) {
        value.split(",").forEach(function (filterId) {
            var filter  = $(".filters input[name=filter][value='" + filterId + "']");
            if(filter.size()) {
                filter.get(0).checked = true;
                filter.parents( ".filter-group").addClass("show")
            }
        })
    });

    decodeURIComponent(getURLVar("filter_category")).split("_").forEach(function (value) {
        value.split(",").forEach(function (categoryId) {
            var filter  = $(".filters input[name=category][value='" + categoryId + "']");
            if(filter.size()) {
                filter.get(0).checked = true;
                filter.parents( ".filter-group").addClass("show")
            }
        })
    });

    filterGroups.each(function () {
        var group = $(this);
        group.find(".label").on("click", function () {
            group.toggleClass("show")
        });
        group.on("change", update)
    });

    if(!slider || !(min < max)) { return }

    noUiSlider.create(slider, {
        start: [from, to],
        connect: true,
        range: {
            'min': min,
            'max': max
        }
    });

    slider.noUiSlider.on('update', function( values, handle ) {
        inputs[handle].val(parseInt(values[handle]).toCommaFormat());
    });

    slider.noUiSlider.on('change', update);

    function setSliderHandle(i, value) {
        var r = [null,null];
        r[i] = value;
        slider.noUiSlider.set(r);
    }

    inputs.forEach(function(input, i) {
        input.on('change', function(){
            setSliderHandle(i, input.val().replaceAll(",", ""));
        });

        input.on('keydown', function( e ) {
            var values = slider.noUiSlider.get();
            var value = Number(values[i]);
            var steps = slider.noUiSlider.steps();
            var step = steps[i];
            var position;
            switch ( e.which ) {
                case 13:
                    setSliderHandle(i, this.value.replaceAll(",", ""));
                    update()
                    break;
                case 38:
                    position = step[1];
                    if ( position === false ) {
                        position = 1;
                    }
                    if ( position !== null ) {
                        setSliderHandle(i, value + position);
                    }
                    break;
                case 40:
                    position = step[0];
                    if ( position === false ) {
                        position = 1;
                    }
                    if ( position !== null ) {
                        setSliderHandle(i, value - position);
                    }
                    break;
            }
        });
    });


})}, 20);
