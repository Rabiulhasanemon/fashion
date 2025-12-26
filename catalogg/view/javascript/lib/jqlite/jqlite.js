(function () {
    var $d = document, $w = window, eventQueue = [], isDomLoaded = false;
    function completed() {
        isDomLoaded = true;
        $d.removeEventListener( "DOMContentLoaded", completed );
        $w.removeEventListener( "load", completed );
        eventQueue.forEach(function (fn) {
            fn();
        });
    }
    if($d.readyState === "complete") {
        completed()
    } else {
        $d.addEventListener( "DOMContentLoaded", completed );
        $w.addEventListener( "load", completed );
    }

    function isHtml(html) {
        html = html.trim();
        return html[0] === "<" && html[html.length - 1] === ">" && html.length >= 3
    }
    var slice = Array.prototype.slice;
    if (!String.prototype.startsWith) {
        String.prototype.startsWith = function(search, pos) {
            return this.substr(!pos || pos < 0 ? 0 : +pos, search.length) === search;
        };
    }
    if (!String.prototype.endsWith) {
        String.prototype.endsWith = function(search, this_len) {
            if (this_len === undefined || this_len > this.length) {
                this_len = this.length;
            }
            return this.substring(this_len - search.length, this_len) === search;
        };
    }
    $w.$ = function (arg, data) {
        var htmlEls;
        if (arg instanceof Function) {
            if(isDomLoaded) {
                arg()
            } else {
                eventQueue.push(arg);
            }
            return $d;
        } else if (arg instanceof NodeList) {
            return new JQLite(slice.call(arg))
        } else if (arg instanceof Node && arg.nodeType === 1) {
            return new JQLite([arg])
        } else if (typeof arg === "string" && isHtml(arg)) {
            arg = arg.trim();
            var matches = arg.match(/^<([\w-]+)\s*\/?>(?:<\/\1>|)$/);
            if (matches) {
                data = data || {};
                var elm = $d.createElement(matches[1]);
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        elm.setAttribute(key, data[key])
                    }
                }
                htmlEls = [elm];
            } else {
                if(arg.startsWith("<!DOCTYPE") || arg.startsWith("<!doctype")) {
                    var parser = new DOMParser();
                    var dom = parser.parseFromString(arg, "text/html");
                    htmlEls =  [dom.documentElement]
                } else {
                    var dummyElm = $d.createElement("div");
                    dummyElm.innerHTML = arg;
                    htmlEls =  slice.call(dummyElm.childNodes)
                }
            }
            return new JQLite(htmlEls);
        } else if (typeof arg === "string") {
            return new JQLite(slice.call($d.querySelectorAll(arg)));
        } else if (arg instanceof Document) {
            return new JQLite([arg.documentElement])
        }
    };

    $w.JQLite = function (elements) {
        this.elements = elements;
        return this;
    };

    var _j = $.fn = JQLite.prototype;

    _j.html = function (elm) {
        if (typeof elm !== 'undefined') {
            var html = "";
            if (elm instanceof JQLite) {
                elm.each(function () {
                    html += this.outerHTML
                })
            } else {
                html = elm;
            }
            this.elements.forEach(function (el) {
                el.innerHTML = html
            });
            return this;
        } else {
            return this.elements[0].innerHTML;
        }
    };

    _j.empty = function () {
        this.html("");
        return this;
    };

    _j.prevSiblings = function () {
        var siblings = [], n = this.get(0);
        while (n = n.previousElementSibling) siblings.push(n);
        return new JQLite(siblings);
    };

    _j.nextSiblings = function () {
        var siblings = [], n = this.get(0);
        while (n = n.nextElementSibling) siblings.push(n);
        return new JQLite(siblings);
    };

    _j.siblings = function (selector) {
        var _self = this, siblings = _self.prevSiblings().elements.concat(_self.nextSiblings().elements);
        var results = [];
        if (!selector) {
            results = siblings
        } else {
            siblings.forEach(function (value) {
                if ($(value).is(selector)) {
                    results.push(value)
                }
            })
        }
        return new JQLite(results)
    };

    _j.appendToFirst = function (arg) {
        this.elements[0].appendChild(arg);
    };

    _j.prepend = function (arg) {
        if (arg instanceof JQLite) {
            arg.elements.forEach(function (el) {
                this.elements[0].prepend(el);
            }.bind(this));
        } else {
            this.elements[0].prepend(arg);
        }
        return this;
    };

    _j.append = function (arg) {
        if (arg instanceof JQLite) {
            arg.elements.forEach(function (el) {
                this.elements[0].appendChild(el);
            }.bind(this));
        } else if (arg instanceof HTMLElement) {
            this.elements[0].appendChild(arg);
        } else if (typeof arg === "string") {
            this.elements.forEach(function (el) {
                el.innerHTML += arg;
            });
        }
        return this;
    };

    _j.before = function (elm) {
        var _self = this, clone = _self.size() > 1;
        _self.elements.forEach(function (value) {
            var parent = value.parentNode;
            if (typeof elm === "string") {
                elm = $(elm)
            }
            elm.each(function () {
                var after = clone ? this.cloneNode(true) : this;
                parent.insertBefore(after, value)
            })
        })
    };

    _j.after = function (elm) {
        var _self = this, clone = _self.size() > 1;
        _self.elements.forEach(function (value) {
            var parent = value.parentNode, nextSibling = value.nextSibling;
            if (typeof elm === "string") {
                elm = $(elm)
            }
            elm.each(function () {
                var after = clone ? this.cloneNode(true) : this;
                if (nextSibling) {
                    parent.insertBefore(after, nextSibling)
                } else {
                    parent.appendChild(after)
                }
            })
        })
    };

    _j.attr = function (name, value) {
        if (typeof value !== 'undefined') {
            this.elements.forEach(function (el) {
                el.setAttribute(name, value);
            });
            return this;
        } else {
            var element = this.elements[0];
            return element ? element.getAttribute(name) : null;
        }
    };

    _j.removeAttr = function (name) {
        this.elements.forEach(function (el) {
            el.removeAttribute(name);
        });
    };

    _j.get = function (index) {
        return this.elements[index];
    };

    _j.hasClass = function (cls) {
        var elm = this.get(0);
        return new RegExp(' ' + cls + ' ').test(' ' + elm.className + ' ');
    };

    _j.addClass = function (cls) {
        this.elements.forEach(function (el) {
            if(el.classList) {
                el.classList.add(cls);
            } else {
                if($(el).hasClass(cls)) return;
                el.className += ' ' + cls;
            }
        });
        return this;
    };

    _j.removeClass = function (cls) {
        this.elements.forEach(function (el) {
            if(el.classList) {
                el.classList.remove(cls);
            } else {
                var newClass = ' ' + el.className.replace( /[\t\r\n]/g, ' ') + ' ';
                if ($(el).hasClass(cls)) {
                    while (newClass.indexOf(' ' + cls + ' ') >= 0 ) {
                        newClass = newClass.replace(' ' + cls + ' ', ' ');
                    }
                    el.className = newClass.replace(/^\s+|\s+$/g, '');
                }
            }
        });
        return this;
    };

    _j.toggleClass = function (className) {
        this.each(function (i, elem) {
            elem = $(elem);
            if (elem.hasClass(className)) {
                elem.removeClass(className);
            } else {
                elem.addClass(className);
            }
        })
    };

    _j.hasClass = function (className) {
        return new RegExp(' ' + className + ' ').test(' ' + this.get(0).className + ' ');
    };

    _j.children = function () {
        var allChildren = [], newChildren;

        this.elements.forEach(function (el) {
            newChildren = Array.prototype.slice.call(el.children);

            allChildren = allChildren.concat(newChildren);
        });

        return new JQLite(allChildren);
    };

    _j.val = function (value) {
        if(value !== undefined) {
            this.each(function () {
                this.value = value
            })
        } else {
            var elm = this.get(0);
            return  elm ? elm.value : null
        }
    };

    _j.parent = function () {
        var parents = [], currentParent;

        this.elements.forEach(function (el) {
            currentParent = el.parentElement;
            if (currentParent && parents.indexOf(currentParent) === -1) {
                parents.push(currentParent);
            }
        });
        return new JQLite(parents);
    };

    _j.parents = function (selector) {
        var _self = this;
        if(!selector) return _self.parent();
        var parent = $(_self.get(0).parentElement);
        while(parent.size()) {
            if(parent.is(selector)) return parent;
            parent = parent.parent()

        }
        return parent
    };

    _j.find = function (selector) {
        var matchingElements = [], currentMatchesQuery, currentMatches;

        this.elements.forEach(function (el) {
            currentMatchesQuery = el.querySelectorAll(selector);
            currentMatches = Array.prototype.slice.call(currentMatchesQuery);
            currentMatches.forEach(function (match) {
                if (matchingElements.indexOf(match) === -1) {
                    matchingElements.push(match);
                }
            });
        });

        return new JQLite(matchingElements);
    };

    _j.is = function (selector) {
        if (this.elements.length === 0) return false;
        var finalResult = true;
        this.elements.forEach(function (elem) {
            var result = false;
            if (selector.nodeType) {
                result = elem === selector;
            } else {
                var qa = (typeof(selector) === 'string' ? $d.querySelectorAll(selector) : [selector]),
                    length = qa.length;
                while (length--) {
                    if (qa[length] === elem) {
                        result = true;
                    }
                }
            }
            finalResult = result && finalResult;
        });
        return finalResult
    };

    _j.has = function (elm) {
        var result = [];
        elm = (elm instanceof Node) ? elm : elm.get(0);
        this.each(function () {
            if (this.contains(elm)) {
                result.push(this)
            }
        });
        return new JQLite(result)
    };

    _j.remove = function () {
        this.elements.forEach(function (el) {
            try { el.remove(); } catch (e) { el.parentNode.removeChild(el); }
        });
    };

    _j.on = function (type, callback) {
        this.elements.forEach(function (el) {
            el.addEventListener(type, callback);
        });
        return this;
    };

    _j.delegate = function (selector, type, callback) {
        this.on(type, function (ev) {
            if ($(ev.target).is(selector)) callback.apply(ev.target, arguments)
        })
    };

    _j.off = function (type, callback) {
        this.elements.forEach(function (el) {
            el.removeEventListener(type, callback);
        });
        return this;
    };

    _j.trigger = function (evt) {
        var event;
        try {
             event = new Event(evt);
        } catch(ex) {
            event = $d.createEvent('Event');
            event.initCustomEvent(evt, true, true);
        }
        this.elements.forEach(function (elm) {
            elm.dispatchEvent(event)
        });
    };

    _j.each = function (callback) {
        this.elements.forEach(function (value, i) {
            callback.call(value, i, value)
        });
    };

    _j.hide = function () {
        this.originalDisplay = this.css("display");
        this.css("display", "none");
        return this;
    };

    _j.show = function () {
        var newDisplay = this.originalDisplay && this.originalDisplay !== "none" ? this.originalDisplay : "block";
        this.css("display", newDisplay);
        return this;
    };

    _j.css = function (property, value) {
        if(property && typeof property !== "string") {
            for (var key in property) {
                this.elements.forEach(function (element) {
                    element.style[key] = property[key];
                });
            }
        } else if (typeof value === 'undefined') {
            return this.elements[0].style.getPropertyValue(property);
        } else {
            this.elements.forEach(function (element) {
                element.style[property] = value;
            });
            return this;
        }
    };

    function toFloat(value) {
        return parseFloat(value ? value : 0)
    }

    _j.outerHeight = function () {
        var self = this;
        var margin = toFloat(self.css("marginTop")) + toFloat(self.css("marginBottom"));
        return Math.ceil(self.get(0).offsetHeight + margin);
    };

    _j.offset = function(  ) {
        var docElem, win, elem = this.get(0),
            box = { top: 0, left: 0 },
            doc = elem && elem.ownerDocument;

        docElem = doc.documentElement;
        box = elem.getBoundingClientRect();
        win = doc.defaultView;
        return {
            top: box.top + win.pageYOffset - docElem.clientTop,
            left: box.left + win.pageXOffset - docElem.clientLeft
        };
    };

    _j.position = function() {
        var _self = this, offset, elem = this.get(0), parentOffset = { top: 0, left: 0 };
        if ( this.css("position" ) === "fixed" ) {
            offset = elem.getBoundingClientRect();
        } else {
            var offsetParent = new JQLite([elem.offsetParent]);
            offset = this.offset();
            if (_self.get(0).nodeName !== "html" ) {
                parentOffset = offsetParent.offset();
            }
            parentOffset.top += toFloat(offsetParent.css("borderTopWidth"));
            parentOffset.left += toFloat(offsetParent.css("borderLeftWidth"));
        }
        return {
            top: offset.top - parentOffset.top - _self.css( "marginTop"),
            left: offset.left - parentOffset.left - _self.css("marginLeft")
        };
    };

    _j.text = function (string) {
        if (typeof string !== 'undefined') {

            this.elements.forEach(function (el) {
                el.innerText = string;
            });

            return this;
        } else {
            text = "";

            this.elements.forEach(function (element) {
                text += element.innerText;
            });

            return text;
        }
    };

    _j.size = function () {
        return this.elements.length
    };

    _j.data = function (key, value) {
        var firstElm = this.get(0), data = firstElm.dataset;
        data = data || {};
        if (key && value) {
            data[key] = value
        } else if (key) {
            return data[key]
        } else {
            return data
        }
    };

    _j.scrollTop = function (value) {
        var firstElm = this.get(0);
        if (!firstElm) return null;
        if (value !== undefined) {
            this.get(0).scrollTop = value
        }
        return firstElm.scrollTop
    };


    $.extend = function (base) {
        var objects = Array.prototype.slice.call(arguments, 1);

        objects.forEach(function (object) {
            for (var attribute in object) {
                base[attribute] = object[attribute];
            }
        });
    };

    var loadXMLDoc = function (options) {
        var method = options.method.toUpperCase().trim();
        var data = options.data;
        var url = options.url;
        if(data instanceof JQLite) {
            var elements = data.elements;
            data = {};
            elements.forEach(function (el) {
                if(el.name) {
                    data[el.name] = el.value
                }
            })
        }
        if(typeof data !== "string" && !($w.FormData && data instanceof FormData)) {
            var formData = [];
            for (var key in data) {
                if(method === "GET") {
                    url = updateURLVar(url, key, data[key])
                } else {
                    formData.push(key + "=" + encodeURIComponent(data[key]))
                }
            }
            data = formData.join("&")
        }

        var xmlHTTP = new XMLHttpRequest();
        xmlHTTP.onreadystatechange = function () {
            if (xmlHTTP.readyState === XMLHttpRequest.DONE) {
                if (xmlHTTP.status === 200) {
                    var response = xmlHTTP.response || xmlHTTP.responseText;
                    if(options.dataType === "json") {
                        response = JSON.parse(response)
                    }
                    options.success(response);
                } else {
                    options.error(xmlHTTP, xmlHTTP.status, xmlHTTP.response);
                }
                options.complete()
            }
        };
        xmlHTTP.open(method, url, true);
        if(options.headers) {
            options.headers.forEach(function (header) {
                xmlHTTP.setRequestHeader(header.name, header.value);
            })
        }
        if (method !== 'GET' && options.contentType) {
            xmlHTTP.setRequestHeader('Content-type', options.contentType);
        }
        options.beforeSend(options);
        xmlHTTP.send(data);

    };

    $.ajax = function (options) {
        var defaults = {
            success: function () {
            },
            error: function () {
            },
            url: $w.location.href,
            method: "GET",
            complete: function () {

            },
            beforeSend: function () {

            },
            dataType: "html",
            data: "",
            contentType: "application/x-www-form-urlencoded"
        };

        $.extend(defaults, options);
        loadXMLDoc(defaults);
    };

    _j.load = function (url, params, callback) {
        var selector, type, response,
            self = this,
            off = url.indexOf(" ");

        if (off > -1) {
            selector = url.slice(off).trim();
            url = url.slice(0, off);
        }

        if (params && typeof params === "object") {
            type = "POST";
        }

        $.ajax({
            url: url,
            method: type || "GET",
            dataType: "html",
            data: params,
            success: function (responseText) {
                self.html(selector ? $("<div>").append(responseText).find(selector) : responseText);
                callback && callback(responseText);
            }
        });
    };

    $.get = function (url, successCallback) {
        $.ajax({
            url: url,
            success: successCallback
        });
    };

    $.post = function (url, data, successCallback) {
        $.ajax({
            url: url,
            data: data,
            method: "POST",
            success: successCallback
        });
    };


}());


