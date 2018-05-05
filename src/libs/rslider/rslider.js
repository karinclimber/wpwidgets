// version v9.5.7
(function ($) {
    function rsInit(sliderElement, options) {
        var a = this;
        var navigator = window.navigator;
        var userAgent = navigator.userAgent.toLowerCase();
        a.uid = $.rsModules.uid++;
        a.ns = ".rs" + a.uid;
        var rsContainerMarkup = document.createElement("div").style;
        var browserPrefixes = ["webkit", "Moz", "ms", "O"];
        var k = "";
        var l = 0;
        var q;
        var c;
        for (c = 0; c < browserPrefixes.length; c++) {
            q = browserPrefixes[c], !k && q + "Transform" in rsContainerMarkup && (k = q), q = q.toLowerCase(), window.requestAnimationFrame || (window.requestAnimationFrame = window[q + "RequestAnimationFrame"], window.cancelAnimationFrame = window[q + "CancelAnimationFrame"] || window[q + "CancelRequestAnimationFrame"]);
        }
        window.requestAnimationFrame ||
        (window.requestAnimationFrame = function (a, b) {
            var currentTime = (new Date).getTime(), d = Math.max(0, 16 - (currentTime - l)), animationTimerId = window.setTimeout(function () {
                a(currentTime + d)
            }, d);
            l = currentTime + d;
            return animationTimerId
        });
        window.cancelAnimationFrame || (window.cancelAnimationFrame = function (a) {
            clearTimeout(a)
        });
        a.isIPAD = userAgent.match(/(ipad)/);
        a.isIOS = a.isIPAD || userAgent.match(/(iphone|ipod)/);
        c = function (a) {
            a = /(chrome)[ \/]([\w.]+)/.exec(a) || /(webkit)[ \/]([\w.]+)/.exec(a) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a) || /(msie) ([\w.]+)/.exec(a) || 0 > a.indexOf("compatible") && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a) ||
                [];
            return {browser: a[1] || "", version: a[2] || "0"}
        }(userAgent);
        browserPrefixes = {};
        c.browser && (browserPrefixes[c.browser] = true, browserPrefixes.version = c.version);
        browserPrefixes.chrome && (browserPrefixes.webkit = true);
        a.deviceInfo = browserPrefixes;
        a.isAndroid = (-1 < userAgent.indexOf("android"));
        a.slider = $(sliderElement);
        a.ev = $(a);
        a.$document = $(document);
        a.options = $.extend({}, $.fn.royalSlider.defaults, options);
        a.transitionSpeed = a.options.transitionSpeed;
        a._d = 0;
        k = k.toLowerCase();
        a._g = "-" + k + "-";
        a.isHorizontal = ("vertical" === a.options.slidesOrientation) ? false : true;
        a._i = a.isHorizontal ? "left" : "top";
        a._j = a.isHorizontal ? "width" : "height";
        a._k = -1;
        a.transitionIsNotFade = ("fade" === a.options.transitionType) ? false : true;
        a.transitionIsNotFade || (a.options.sliderDrag = false, a._m = 10);
        a._n = "z-index:0; display:none; opacity:0;";
        a._o = 0;
        a._p = 0;
        a._q = 0;
        $.each($.rsModules, function (b, c) {
            "uid" !== b && c.call(a)
        });
        a.slides = [];
        a._r = 0;
        (a.options.slides ? $(a.options.slides) : a.slider.children().detach()).each(function () {
            a._s(this, !0)
        });
        a.options.randomizeSlides && a.slides.sort(function () {
            return .5 - Math.random()
        });
        a.numSlides = a.slides.length;
        a._t();
        a.options.startSlideId ? a.options.startSlideId >
            a.numSlides - 1 && (a.options.startSlideId = a.numSlides - 1) : a.options.startSlideId = 0;
        a._o = a.staticSlideId = a.currSlideId = a._u = a.options.startSlideId;
        a.currSlide = a.slides[a.currSlideId];
        a._v = 0;
        a.pointerMultitouch = !1;
        a.slider.addClass((a.isHorizontal ? "rsHor" : "rsVer") + (a.transitionIsNotFade ? "" : " rsFade"));

        a.slidesSpacing = a.options.slidesSpacing;
        a._w = (a.isHorizontal ? a.slider.width() : a.slider.height()) + a.options.slidesSpacing;
        a._x = Boolean(0 < a._y);
        1 >= a.numSlides && (a._z = !1);
        a._a1 = a._z && a.transitionIsNotFade ? 2 === a.numSlides ? 1 : 2 : 0;
        a._b1 =
            6 > a.numSlides ? a.numSlides : 6;
        a._c1 = 0;
        a._d1 = 0;
        a.slidesJQ = [];
        rsContainerMarkup = '<div class="rsOverflow"><div class="rsContainer">';
        for (c = 0; c < a.numSlides; c++) {
            a.slidesJQ.push($('<div style="' + (a.transitionIsNotFade ? "" : c !== a.currSlideId ? a._n : "z-index:0;") + '" class="rsSlide "></div>'));
        }
        a._e1 = rsContainerMarkup = $(rsContainerMarkup + "</div></div>");
        var m = a.ns;
        var k = function (b, c, d, e, f) {
            a._j1 = b + c + m;
            a._k1 = b + d + m;
            a._l1 = b + e + m;
            f && (a._m1 = b + f + m)
        };
        c = navigator.pointerEnabled;
        a.pointerEnabled = c || navigator.msPointerEnabled;
        a.pointerEnabled ? (a.hasTouch = !1, a._n1 = .2, a.pointerMultitouch = Boolean(1 < navigator[(c ? "m" : "msM") + "axTouchPoints"]), c ? k("pointer", "down", "move", "up", "cancel") : k("MSPointer", "Down", "Move", "Up", "Cancel")) : (a.isIOS ? a._j1 = a._k1 = a._l1 = a._m1 = "" : k("mouse", "down", "move", "up"), "ontouchstart" in window || "createTouch" in document ? (a.hasTouch = !0, a._j1 += " touchstart" + m, a._k1 += " touchmove" + m, a._l1 += " touchend" + m, a._m1 += " touchcancel" + m, a._n1 = .5, a.options.sliderTouch && (a._f1 = !0)) : (a.hasTouch = !1, a._n1 = .2));
        a.options.sliderDrag && (a._f1 = !0, browserPrefixes.msie || browserPrefixes.opera ? a._g1 = a._h1 = "move" : browserPrefixes.mozilla ? (a._g1 = "-moz-grab", a._h1 = "-moz-grabbing") : browserPrefixes.webkit && -1 != navigator.platform.indexOf("Mac") && (a._g1 = "-webkit-grab", a._h1 = "-webkit-grabbing"), a._i1());
        a.slider.html(rsContainerMarkup);
        a._o1 = a._e1;
        a._p1 = a._e1.children(".rsContainer");
        a.pointerEnabled && a._p1.css((c ? "" : "-ms-") + "touch-action", a.isHorizontal ? "pan-y" : "pan-x");
        a._q1 = $('<div class="rsPreloader"></div>');
        navigator = a._p1.children(".rsSlide");
        a._r1 = a.slidesJQ[a.currSlideId];
        a._s1 = 0;
        (a._e && a.numSlides > 1) ? (a._t1 = "transition-property", a._u1 = "transition-duration", a._v1 = "transition-timing-function", a._w1 = a._x1 = a._g + "transform", a._f ? (browserPrefixes.webkit && !browserPrefixes.chrome && a.slider.addClass("rsWebkit3d"),
            a._y1 = "translate3d(", a._z1 = "px, ", a._a2 = "px, 0px)") : (a._y1 = "translate(", a._z1 = "px, ", a._a2 = "px)"), a.transitionIsNotFade ? a._p1[a._g + a._t1] = a._g + "transform" : (browserPrefixes = {}, browserPrefixes[a._g + a._t1] = "opacity", browserPrefixes[a._g + a._u1] = a.options.transitionSpeed + "ms", browserPrefixes[a._g + a._v1] = a.options.css3easeInOut, navigator.css(browserPrefixes))) : (a._x1 = "left", a._w1 = "top");
        var p;
        $(window).on("resize" + a.ns, function () {
            p && clearTimeout(p);
            p = setTimeout(function () {
                a.updateSliderSize()
            }, 50)
        });
        a.ev.trigger("rsAfterPropsSetup");
        a.updateSliderSize();
        a.options.keyboardNavEnabled && a._b2();
        a.options.arrowsNavHideOnTouch &&
        (a.hasTouch || a.pointerMultitouch) && (a.options.arrowsNav = !1);
        a.options.arrowsNav && (navigator = a._o1, $('<div class="rsArrow rsArrowLeft"><div class="rsArrowIcn"></div></div><div class="rsArrow rsArrowRight"><div class="rsArrowIcn"></div></div>').appendTo(navigator), a._c2 = navigator.children(".rsArrowLeft").click(function (b) {
            b.preventDefault();
            a.prev()
        }), a._d2 = navigator.children(".rsArrowRight").click(function (b) {
            b.preventDefault();
            a.next()
        }), a.options.arrowsNavAutoHide && !a.hasTouch && (a._c2.addClass("rsHidden"), a._d2.addClass("rsHidden"), navigator.one("mousemove.arrowshover",
            function () {
                a._c2.removeClass("rsHidden");
                a._d2.removeClass("rsHidden")
            }), navigator.hover(function () {
            a._e2 || (a._c2.removeClass("rsHidden"), a._d2.removeClass("rsHidden"))
        }, function () {
            a._e2 || (a._c2.addClass("rsHidden"), a._d2.addClass("rsHidden"))
        })), a.ev.on("rsOnUpdateNav", function () {
            a._f2()
        }), a._f2());
        if (a.hasTouch && a.options.sliderTouch || !a.hasTouch && a.options.sliderDrag) a._p1.on(a._j1, function (b) {
            a.startTouch(b)
        }); else a.dragSuccess = !1;
        var r = ["rsPlayBtnIcon", "rsPlayBtn", "rsCloseVideoBtn", "rsCloseVideoIcn"];
        a._p1.click(function (b) {
            if (!a.dragSuccess) {
                var c =
                    $(b.target).attr("class");
                if (-1 !== $.inArray(c, r) && a.toggleVideo()) return !1;
                if (a.options.navigateByClick && !a._h2) {
                    if ($(b.target).closest(".rsNoDrag", a._r1).length) return !0;
                    a._i2(b)
                }
                a.ev.trigger("rsSlideClick", b)
            }
        }).on("click.rs", "a", function (b) {
            if (a.dragSuccess) return !1;
            a._h2 = !0;
            setTimeout(function () {
                a._h2 = !1
            }, 3)
        });
        a.ev.trigger("rsAfterInit")
    }

    $.rsModules || ($.rsModules = {uid: 0});
    rsInit.prototype = {
        constructor: rsInit, _i2: function (b) {
            b = b[this.isHorizontal ? "pageX" : "pageY"] - this._j2;
            b >= this._q ? this.next() : 0 > b && this.prev()
        },
        _t: function () {
            var numImagesToPreload = this.options.numImagesToPreload;
            if (this._z = this.options.loop) 2 === this.numSlides ? (this._z = !1, this.options.loopRewind = !0) : 2 > this.numSlides && (this.options.loopRewind = this._z = !1);
            this._z && 0 < numImagesToPreload && (4 >= this.numSlides ? numImagesToPreload = 1 : this.options.numImagesToPreload > (this.numSlides - 1) / 2 && (numImagesToPreload = Math.floor((this.numSlides - 1) / 2)));
            this._y = numImagesToPreload
        },
        _s: function (b, f) {
            function c(b, c) {
                c ? g.images.push(b.attr(c)) : g.images.push(b.text());
                if (h) {
                    h = !1;
                    g.caption = "src" === c ? b.attr("alt") : b.contents();
                    g.image = g.images[0];
                    g.videoURL = b.attr("data-rsVideo");
                    var d = b.attr("data-rsw"),
                        e = b.attr("data-rsh");
                    "undefined" !== typeof d && !1 !== d && "undefined" !== typeof e && !1 !== e ? (g.iW = parseInt(d, 10), g.iH = parseInt(e, 10)) : a.options.imgWidth && a.options.imgHeight && (g.iW = a.options.imgWidth, g.iH = a.options.imgHeight)
                }
            }

            var a = this, e, g = {}, d, h = !0;
            b = $(b);
            a._k2 = b;
            a.ev.trigger("rsBeforeParseNode", [b, g]);
            if (!g.stopParsing) return b = a._k2, g.id = a._r, g.contentAdded = !1, a._r++, g.images = [], g.isBig = !1, g.hasCover || (b.hasClass("rsImg") ? (d = b, e = !0) : (d = b.find(".rsImg"), d.length && (e = !0)), e ? (g.bigImage = d.eq(0).attr("data-rsBigImg"), d.each(function () {
                var a =
                    $(this);
                a.is("a") ? c(a, "href") : a.is("img") ? c(a, "src") : c(a)
            })) : b.is("img") && (b.addClass("rsImg rsMainSlideImage"), c(b, "src"))), d = b.find(".rsCaption"), d.length && (g.caption = d.remove()), g.content = b, a.ev.trigger("rsAfterParseNode", [b, g]), f && a.slides.push(g), 0 === g.images.length && (g.isLoaded = !0, g.isRendered = !1, g.isLoading = !1, g.images = null), g
        },
        _b2: function () {
            var self = this, f, c, a = function (a) {
                37 === a ? self.prev() : 39 === a && self.next()
            };
            self.$document.on("keydown" + self.ns, function (e) {
                if (!self.options.keyboardNavEnabled) return !0;
                if (!(self._l2 || (c =
                        e.keyCode, 37 !== c && 39 !== c || f))) {
                    if (document.activeElement && /(INPUT|SELECT|TEXTAREA)/i.test(document.activeElement.tagName)) return !0;
                    self.isFullscreen && e.preventDefault();
                    a(c);
                    f = setInterval(function () {
                        a(c)
                    }, 700)
                }
            }).on("keyup" + self.ns, function (a) {
                f && (clearInterval(f), f = null)
            })
        },
        goTo: function (b, f) {
            b !== this.currSlideId && this._m2(b, this.options.transitionSpeed, !0, !f)
        },
        destroy: function (b) {
            this.ev.trigger("rsBeforeDestroy");
            this.$document.off("keydown" + this.ns + " keyup" + this.ns + " " + this._k1 + " " + this._l1);
            this._p1.off(this._j1 +
                " click");
            this.slider.data("royalSlider", null);
            $.removeData(this.slider, "royalSlider");
            $(window).off("resize" + this.ns);
            this.loadingTimeout && clearTimeout(this.loadingTimeout);
            b && this.slider.remove();
            this.ev = this.slider = this.slides = null
        },
        _n2: function (b, f) {
            function c(c, f, g) {
                c.isAdded ? (a(f, c), e(f, c)) : (g || (g = d.slidesJQ[f]), c.holder ? g = c.holder : (g = d.slidesJQ[f] = $(g), c.holder = g), c.appendOnLoaded = !1, e(f, c, g), a(f, c), d.appendHolder(c, g, b), c.isAdded = !0)
            }

            function a(a, c) {
                c.contentAdded || (d.setItemHtml(c, b), b || (c.contentAdded = !0))
            }

            function e(a, b, c) {
                d.transitionIsNotFade && (c || (c = d.slidesJQ[a]), c.css(d._i, (a + d._d1 + p) * d._w))
            }

            function g(a) {
                if (l) {
                    if (a > q - 1) return g(a - q);
                    if (0 > a) return g(q + a)
                }
                return a
            }

            var d = this, h, k, l = d._z, q = d.numSlides;
            if (!isNaN(f)) return g(f);
            var m = d.currSlideId, p, r = b ? Math.abs(d._o2 - d.currSlideId) >= d.numSlides - 1 ? 0 : 1 : d._y,
                t = Math.min(2, r), w = !1, v = !1, u;
            for (k = m; k < m + 1 + t; k++) if (u = g(k), (h = d.slides[u]) && (!h.isAdded || !h.positionSet)) {
                w = !0;
                break
            }
            for (k = m - 1; k > m - 1 - t; k--) if (u = g(k), (h = d.slides[u]) && (!h.isAdded || !h.positionSet)) {
                v = !0;
                break
            }
            if (w) for (k =
                            m; k < m + r + 1; k++) u = g(k), p = Math.floor((d._u - (m - k)) / d.numSlides) * d.numSlides, (h = d.slides[u]) && c(h, u);
            if (v) for (k = m - 1; k > m - 1 - r; k--) u = g(k), p = Math.floor((d._u - (m - k)) / q) * q, (h = d.slides[u]) && c(h, u);
            if (!b) for (t = g(m - r), m = g(m + r), r = t > m ? 0 : t, k = 0; k < q; k++) t > m && k > t - 1 || !(k < r || k > m) || (h = d.slides[k]) && h.holder && (h.holder.detach(), h.isAdded = !1)
        },
        setItemHtml: function (b, f) {
            var self = this, a = function () {
                if (!b.images) b.isRendered = !0, b.isLoaded = !0, b.isLoading = !1, d(!0); else if (!b.isLoading) {
                    var a, f;
                    b.content.hasClass("rsImg") ? (a = b.content,
                        f = !0) : a = b.content.find(".rsImg:not(img)");
                    a && !a.is("img") && a.each(function () {
                        var a = $(this),
                            c = '<img class="rsImg" src="' + (a.is("a") ? a.attr("href") : a.text()) + '" />';
                        f ? b.content = $(c) : a.replaceWith(c)
                    });
                    a = f ? b.content : b.content.find("img.rsImg");
                    k();
                    a.eq(0).addClass("rsMainSlideImage");
                    b.iW && b.iH && (b.isLoaded || self._q2(b), d());
                    b.isLoading = !0;
                    if (b.isBig) $("<img />").on("load.rs error.rs", function (a) {
                        $(this).off("load.rs error.rs");
                        e([this], !0)
                    }).attr("src", b.image); else {
                        b.loaded = [];
                        b.numStartedLoad = 0;
                        a = function (a) {
                            $(this).off("load.rs error.rs");
                            b.loaded.push(this);
                            b.loaded.length === b.numStartedLoad && e(b.loaded, !1)
                        };
                        for (var g = 0; g < b.images.length; g++) {
                            var h = $("<img />");
                            b.numStartedLoad++;
                            h.on("load.rs error.rs", a).attr("src", b.images[g])
                        }
                    }
                }
            }, e = function (a, c) {
                if (a.length) {
                    var d = a[0];
                    if (c !== b.isBig) (d = b.holder.children()) && 1 < d.length && l(); else if (b.iW && b.iH) g(); else if (b.iW = d.width, b.iH = d.height, b.iW && b.iH) g(); else {
                        var e = new Image;
                        e.onload = function () {
                            e.width ? (b.iW = e.width, b.iH = e.height, g()) : setTimeout(function () {
                                e.width && (b.iW = e.width, b.iH =
                                    e.height);
                                g()
                            }, 1E3)
                        };
                        e.src = d.src
                    }
                } else g()
            }, g = function () {
                b.isLoaded = !0;
                b.isLoading = !1;
                d();
                l();
                h()
            }, d = function () {
                if (!b.isAppended && self.ev) {
                    var a = self.options.visibleNearby, d = b.id - self._o;
                    f || b.appendOnLoaded || 0 !== d && (!(a || self._r2 || self._l2) || -1 !== d && 1 !== d) || (a = {
                        visibility: "visible",
                        opacity: 0
                    }, a[self._g + "transition"] = "opacity 400ms ease-in-out", b.content.css(a), setTimeout(function () {
                        b.content.css("opacity", 1)
                    }, 16));
                    b.holder.find(".rsPreloader").length ? b.holder.append(b.content) : b.holder.html(b.content);
                    b.isAppended = !0;
                    b.isLoaded && (self._q2(b), h());
                    b.sizeReady || (b.sizeReady = !0, setTimeout(function () {
                        self.ev.trigger("rsMaybeSizeReady", b)
                    }, 100))
                }
            }, h = function () {
                !b.loadedTriggered && self.ev && (b.isLoaded = b.loadedTriggered = !0, b.holder.trigger("rsAfterContentSet"), self.ev.trigger("rsAfterContentSet", b))
            }, k = function () {
                self.options.usePreloader && b.holder.html(self._q1.clone())
            }, l = function (a) {
                self.options.usePreloader && (a = b.holder.find(".rsPreloader"), a.length && a.remove())
            };
            b.isLoaded ? d() : f ? !self.transitionIsNotFade && b.images && b.iW && b.iH ? a() : (b.holder.isWaiting = !0, k(), b.holder.slideId = -99) : a()
        },
        appendHolder: function (b, f, c) {
            this._p1.append(b.holder);
            b.appendOnLoaded = !1
        },
        startTouch: function (event, f) {
            var self = this;
            var isTouchStart = ("touchstart" === event.type);
            var a;
            self._s2 = isTouchStart;
            self.ev.trigger("rsDragStart");
            if ($(event.target).closest(".rsNoDrag", self._r1).length) return self.dragSuccess = !1, !0;
            !f && self._r2 && (self._t2 = !0, self._u2());
            self.dragSuccess = !1;
            if (self._l2) isTouchStart && (self._v2 = !0); else {
                isTouchStart && (self._v2 = !1);
                self._w2();
                if (isTouchStart) {
                    var g = event.originalEvent.touches;
                    if (g && 0 < g.length) a = g[0], 1 < g.length && (self._v2 = !0); else return
                } else event.preventDefault(), a = event, self.pointerEnabled &&
                (a = a.originalEvent);
                self._l2 = !0;
                self.$document.on(self._k1, function (a) {
                    self.startTouchMove(a, f)
                }).on(self._l1, function (a) {
                    self._y2(a, f)
                });
                self._z2 = "";
                self._a3 = !1;
                self._b3 = a.pageX;
                self._c3 = a.pageY;
                self._d3 = self._v = (f ? self._e3 : self.isHorizontal) ? a.pageX : a.pageY;
                self._f3 = 0;
                self._g3 = 0;
                self._h3 = f ? self._i3 : self._p;
                self._j3 = (new Date).getTime();
                if (isTouchStart) {
                    self._e1.on(self._m1, function (event) {
                        self._y2(event, f);
                    });
                }
            }
        },
        _k3: function (b, f) {
            if (this._l3) {
                var c = this._m3, a = b.pageX - this._b3, e = b.pageY - this._c3, g = this._h3 + a, d = this._h3 + e,
                    h = f ? this._e3 : this.isHorizontal, g = h ? g : d, d = this._z2;
                this._a3 = !0;
                this._b3 = b.pageX;
                this._c3 = b.pageY;
                "x" ===
                d && 0 !== a ? this._f3 = 0 < a ? 1 : -1 : "y" === d && 0 !== e && (this._g3 = 0 < e ? 1 : -1);
                d = h ? this._b3 : this._c3;
                a = h ? a : e;
                f ? g > this._n3 ? g = this._h3 + a * this._n1 : g < this._o3 && (g = this._h3 + a * this._n1) : this._z || (0 >= this.currSlideId && 0 < d - this._d3 && (g = this._h3 + a * this._n1), this.currSlideId >= this.numSlides - 1 && 0 > d - this._d3 && (g = this._h3 + a * this._n1));
                this._h3 = g;
                200 < c - this._j3 && (this._j3 = c, this._v = d);
                f ? this._q3(this._h3) : this.transitionIsNotFade && this._p3(this._h3)
            }
        },
        startTouchMove: function (event, f) {
            var self = this;
            var isTouchMove = ("touchmove" === event.type);
            var a;
            if (!self._s2 || isTouchMove) {
                if (isTouchMove) {
                    if (self._r3) return;
                    var g =
                        event.originalEvent.touches;
                    if (g) {
                        if (1 < g.length) return;
                        a = g[0]
                    } else return
                } else a = event, self.pointerEnabled && (a = a.originalEvent);
                self._a3 || (self._e && (f ? self._s3 : self._p1).css(self._g + self._u1, "0s"), function h() {
                    self._l2 && (self._t3 = requestAnimationFrame(h), self._u3 && self._k3(self._u3, f))
                }());
                if (self._l3) event.preventDefault(), self._m3 = (new Date).getTime(), self._u3 = a; else if (g = f ? self._e3 : self.isHorizontal, a = Math.abs(a.pageX - self._b3) - Math.abs(a.pageY - self._c3) - (g ? -7 : 7), 7 < a) {
                    if (g) event.preventDefault(), self._z2 = "x"; else if (isTouchMove) {
                        self._v3(event);
                        return
                    }
                    self._l3 = !0
                } else if (-7 > a) {
                    if (!g) event.preventDefault(),
                        self._z2 = "y"; else if (isTouchMove) {
                        self._v3(event);
                        return
                    }
                    self._l3 = !0
                }
            }
        },
        _v3: function (event, f) {
            this._r3 = true;
            this._a3 = this._l2 = false;
            this._y2(event)
        },
        _y2: function (event, f) {
            function c(a) {
                return 100 > a ? 100 : 500 < a ? 500 : a
            }

            function a(a, b) {
                if (e.transitionIsNotFade || f) h = (-e._u - e._d1) * e._w, k = Math.abs(e._p - h), e.transitionSpeed = k / b, a && (e.transitionSpeed += 250), e.transitionSpeed = c(e.transitionSpeed), e._x3(h, !1)
            }

            var e = this, g, d, h, k;
            g = -1 < event.type.indexOf("touch");
            if (!e._s2 || g) if (e._s2 = !1, e.ev.trigger("rsDragRelease"), e._u3 = null, e._l2 = !1, e._r3 = !1, e._l3 = !1, e._m3 = 0, cancelAnimationFrame(e._t3), e._a3 && (f ? e._q3(e._h3) : e.transitionIsNotFade && e._p3(e._h3)),
                    e.$document.off(e._k1).off(e._l1), g && e._e1.off(e._m1), e._i1(), !e._a3 && !e._v2 && f && e._w3) {
                var l = $(event.target).closest(".rsNavItem");
                l.length && e.goTo(l.index())
            } else {
                d = f ? e._e3 : e.isHorizontal;
                if (!e._a3 || "y" === e._z2 && d || "x" === e._z2 && !d) if (!f && e._t2) {
                    e._t2 = !1;
                    if (e.options.navigateByClick) {
                        e._i2(e.pointerEnabled ? event.originalEvent : event);
                        e.dragSuccess = !0;
                        return
                    }
                    e.dragSuccess = !0
                } else {
                    e._t2 = !1;
                    e.dragSuccess = !1;
                    return
                } else e.dragSuccess = !0;
                e._t2 = !1;
                e._z2 = "";
                var q = e.options.minSlideOffset;
                g = g ? event.originalEvent.changedTouches[0] : e.pointerEnabled ? event.originalEvent : event;
                var m = d ? g.pageX : g.pageY, p = e._d3;
                g = e._v;
                var r = e.currSlideId, t = e.numSlides, w = d ? e._f3 : e._g3, v = e._z;
                Math.abs(m - p);
                g = m - g;
                d = (new Date).getTime() - e._j3;
                d = Math.abs(g) / d;
                if (0 === w || 1 >= t) a(!0, d); else {
                    if (!v && !f) if (0 >= r) {
                        if (0 < w) {
                            a(!0, d);
                            return
                        }
                    } else if (r >= t - 1 && 0 > w) {
                        a(!0, d);
                        return
                    }
                    if (f) {
                        h = e._i3;
                        if (h > e._n3) h = e._n3; else if (h < e._o3) h = e._o3; else {
                            m = d * d / .006;
                            l = -e._i3;
                            p = e._y3 - e._z3 + e._i3;
                            0 < g && m > l ? (l += e._z3 / (15 / (m / d * .003)), d = d * l / m, m = l) : 0 > g && m > p && (p += e._z3 / (15 / (m / d * .003)), d = d * p / m, m = p);
                            l = Math.max(Math.round(d /
                                .003), 50);
                            h += m * (0 > g ? -1 : 1);
                            if (h > e._n3) {
                                e._a4(h, l, !0, e._n3, 200);
                                return
                            }
                            if (h < e._o3) {
                                e._a4(h, l, !0, e._o3, 200);
                                return
                            }
                        }
                        e._a4(h, l, !0)
                    } else l = function (a) {
                        var b = Math.floor(a / e._w);
                        a - b * e._w > q && b++;
                        return b
                    }, p + q < m ? 0 > w ? a(!1, d) : (l = l(m - p), e._m2(e.currSlideId - l, c(Math.abs(e._p - (-e._u - e._d1 + l) * e._w) / d), !1, !0, !0)) : p - q > m ? 0 < w ? a(!1, d) : (l = l(p - m), e._m2(e.currSlideId + l, c(Math.abs(e._p - (-e._u - e._d1 - l) * e._w) / d), !1, !0, !0)) : a(!1, d)
                }
            }
        },
        _p3: function (b) {
            b = this._p = b;
            this._e ? this._p1.css(this._x1, this._y1 + (this.isHorizontal ? b + this._z1 + 0 :
                0 + this._z1 + b) + this._a2) : this._p1.css(this.isHorizontal ? this._x1 : this._w1, b)
        },
        updateSliderSize: function (b) {
            if (this.slider) {
                var sliderWidth = this.slider.width();
                var sliderHeight = this.slider.height();
                if (b || sliderWidth !== this.width || sliderHeight !== this.height) {
                    this.width = sliderWidth;
                    this.height = sliderHeight;
                    this.sliderWidth = sliderWidth;
                    this.sliderHeight = sliderHeight;
                    this.ev.trigger("rsBeforeSizeSet");
                    this.ev.trigger("rsAfterSizePropSet");
                    this._e1.css({width: this.sliderWidth, height: this.sliderHeight});
                    this._w = (this.isHorizontal ? this.sliderWidth : this.sliderHeight) + this.options.slidesSpacing;
                    this.imageScalePadding = this.options.imageScalePadding;
                    for (sliderWidth = 0; sliderWidth < this.slides.length; sliderWidth++) {
                        b = this.slides[sliderWidth], b.positionSet = !1, b && b.images && b.isLoaded && (b.isRendered = !1, this._q2(b));
                    }
                    this._n2();
                    this.transitionIsNotFade && (this._e && this._p1.css(this._g + "transition-duration", "0s"), this._p3((-this._u - this._d1) * this._w));
                    this.ev.trigger("rsOnUpdateNav")
                }
                this._j2 = this._e1.offset();
                this._j2 = this._j2[this._i]
            }
        },
        appendSlide: function (b, f) {
            var c = this._s(b);
            if (isNaN(f) || f > this.numSlides) f = this.numSlides;
            this.slides.splice(f, 0, c);
            this.slidesJQ.splice(f, 0, $('<div style="' + (this.transitionIsNotFade ? "position:absolute;" : this._n) + '" class="rsSlide"></div>'));
            f <= this.currSlideId && this.currSlideId++;
            this.ev.trigger("rsOnAppendSlide",
                [c, f]);
            this._f4(f);
            f === this.currSlideId && this.ev.trigger("rsAfterSlideChange")
        },
        removeSlide: function (b) {
            var f = this.slides[b];
            f && (f.holder && f.holder.remove(), b < this.currSlideId && this.currSlideId--, this.slides.splice(b, 1), this.slidesJQ.splice(b, 1), this.ev.trigger("rsOnRemoveSlide", [b]), this._f4(b), b === this.currSlideId && this.ev.trigger("rsAfterSlideChange"))
        },
        _f4: function (b) {
            var slider = this;
            b = slider.numSlides;
            b = 0 >= slider._u ? 0 : Math.floor(slider._u / b);
            slider.numSlides = slider.slides.length;
            0 === slider.numSlides ? (slider.currSlideId = slider._d1 = slider._u =
                0, slider.currSlide = slider._g4 = null) : slider._u = b * slider.numSlides + slider.currSlideId;
            for (b = 0; b < slider.numSlides; b++) slider.slides[b].id = b;
            slider.currSlide = slider.slides[slider.currSlideId];
            slider._r1 = slider.slidesJQ[slider.currSlideId];
            slider.currSlideId >= slider.numSlides ? slider.goTo(slider.numSlides - 1) : 0 > slider.currSlideId && slider.goTo(0);
            slider._t();
            slider.transitionIsNotFade && slider._p1.css(slider._g + slider._u1, "0ms");
            slider.timerId && clearTimeout(slider.timerId);
            slider.timerId = setTimeout(function () {
                slider.transitionIsNotFade && slider._p3((-slider._u - slider._d1) * slider._w);
                slider._n2();
                slider.transitionIsNotFade || slider._r1.css({display: "block", opacity: 1})
            }, 14);
            slider.ev.trigger("rsOnUpdateNav")
        },
        _i1: function () {
            this._f1 && this.transitionIsNotFade && (this._g1 ?
                this._e1.css("cursor", this._g1) : (this._e1.removeClass("grabbing-cursor"), this._e1.addClass("grab-cursor")))
        },
        _w2: function () {
            this._f1 && this.transitionIsNotFade && (this._h1 ? this._e1.css("cursor", this._h1) : (this._e1.removeClass("grab-cursor"), this._e1.addClass("grabbing-cursor")))
        },
        next: function (b) {
            this._m2("next", this.options.transitionSpeed, !0, !b)
        },
        prev: function (b) {
            this._m2("prev", this.options.transitionSpeed, !0, !b)
        },
        _m2: function (b, f, c, a, e) {
            var g = this, d, h, k;
            g.ev.trigger("rsBeforeMove", [b, a]);
            k = "next" === b ? g.currSlideId + 1 : "prev" ===
            b ? g.currSlideId - 1 : b = parseInt(b, 10);
            if (!g._z) {
                if (0 > k) {
                    g._i4("left", !a);
                    return
                }
                if (k >= g.numSlides) {
                    g._i4("right", !a);
                    return
                }
            }
            g._r2 && (g._u2(!0), c = !1);
            h = k - g.currSlideId;
            k = g._o2 = g.currSlideId;
            var l = g.currSlideId + h;
            a = g._u;
            var n;
            g._z ? (l = g._n2(!1, l), a += h) : a = l;
            g._o = l;
            g._g4 = g.slidesJQ[g.currSlideId];
            g._u = a;
            g.currSlideId = g._o;
            g.currSlide = g.slides[g.currSlideId];
            g._r1 = g.slidesJQ[g.currSlideId];
            var l = g.options.slidesDiff, m = Boolean(0 < h);
            h = Math.abs(h);
            var p = Math.floor(k / g._y), r = Math.floor((k + (m ? l : -l)) / g._y), p = (m ? Math.max(p,
                r) : Math.min(p, r)) * g._y + (m ? g._y - 1 : 0);
            p > g.numSlides - 1 ? p = g.numSlides - 1 : 0 > p && (p = 0);
            k = m ? p - k : k - p;
            k > g._y && (k = g._y);
            if (h > k + l) for (g._d1 += (h - (k + l)) * (m ? -1 : 1), f *= 1.4, k = 0; k < g.numSlides; k++) g.slides[k].positionSet = !1;
            g.transitionSpeed = f;
            g._n2(!0);
            e || (n = !0);
            d = (-a - g._d1) * g._w;
            n ? setTimeout(function () {
                g._j4 = !1;
                g._x3(d, b, !1, c);
                g.ev.trigger("rsOnUpdateNav")
            }, 0) : (g._x3(d, b, !1, c), g.ev.trigger("rsOnUpdateNav"))
        },
        _f2: function () {
            this.options.arrowsNav && (1 >= this.numSlides ? (this._c2.css("display", "none"), this._d2.css("display", "none")) : (this._c2.css("display",
                "block"), this._d2.css("display", "block"), this._z || this.options.loopRewind || (0 === this.currSlideId ? this._c2.addClass("rsArrowDisabled") : this._c2.removeClass("rsArrowDisabled"), this.currSlideId === this.numSlides - 1 ? this._d2.addClass("rsArrowDisabled") : this._d2.removeClass("rsArrowDisabled"))))
        },
        _x3: function (b, f, c, a, e) {
            function g() {
                var a;
                h && (a = h.data("rsTimeout")) && (h !== k && h.css({
                    opacity: 0,
                    display: "none",
                    zIndex: 0
                }), clearTimeout(a), h.data("rsTimeout", ""));
                if (a = k.data("rsTimeout")) clearTimeout(a), k.data("rsTimeout",
                    "")
            }

            var d = this, h, k, l = {};
            isNaN(d.transitionSpeed) && (d.transitionSpeed = 400);
            d._p = d._h3 = b;
            d.ev.trigger("rsBeforeAnimStart");
            d._e ? d.transitionIsNotFade ? (d.transitionSpeed = parseInt(d.transitionSpeed, 10), c = d._g + d._v1, l[d._g + d._u1] = d.transitionSpeed + "ms", l[c] = a ? $.rsCSS3Easing[d.options.easeInOut] : $.rsCSS3Easing[d.options.easeOut], d._p1.css(l), a || !d.hasTouch ? setTimeout(function () {
                d._p3(b)
            }, 5) : d._p3(b)) : (d.transitionSpeed = d.options.transitionSpeed, h = d._g4, k = d._r1, k.data("rsTimeout") && k.css("opacity", 0), g(), h && h.data("rsTimeout", setTimeout(function () {
                l[d._g + d._u1] = "0ms";
                l.zIndex = 0;
                l.display = "none";
                h.data("rsTimeout",
                    "");
                h.css(l);
                setTimeout(function () {
                    h.css("opacity", 0)
                }, 16)
            }, d.transitionSpeed + 60)), l.display = "block", l.zIndex = d._m, l.opacity = 0, l[d._g + d._u1] = "0ms", l[d._g + d._v1] = $.rsCSS3Easing[d.options.easeInOut], k.css(l), k.data("rsTimeout", setTimeout(function () {
                k.css(d._g + d._u1, d.transitionSpeed + "ms");
                k.data("rsTimeout", setTimeout(function () {
                    k.css("opacity", 1);
                    k.data("rsTimeout", "")
                }, 20))
            }, 20))) : d.transitionIsNotFade ? (l[d.isHorizontal ? d._x1 : d._w1] = b + "px", d._p1.animate(l, d.transitionSpeed, a ? d.options.easeInOut : d.options.easeOut)) : (h = d._g4, k = d._r1, k.stop(!0, !0).css({
                opacity: 0, display: "block",
                zIndex: d._m
            }), d.transitionSpeed = d.options.transitionSpeed, k.animate({opacity: 1}, d.transitionSpeed, d.options.easeInOut), g(), h && h.data("rsTimeout", setTimeout(function () {
                h.stop(!0, !0).css({opacity: 0, display: "none", zIndex: 0})
            }, d.transitionSpeed + 60)));
            d._r2 = !0;
            d.loadingTimeout && clearTimeout(d.loadingTimeout);
            d.loadingTimeout = e ? setTimeout(function () {
                d.loadingTimeout = null;
                e.call()
            }, d.transitionSpeed + 60) : setTimeout(function () {
                d.loadingTimeout = null;
                d._k4(f)
            }, d.transitionSpeed + 60)
        },
        _u2: function (b) {
            this._r2 = !1;
            clearTimeout(this.loadingTimeout);
            if (this.transitionIsNotFade) if (!this._e) this._p1.stop(!0),
                this._p = parseInt(this._p1.css(this.isHorizontal ? this._x1 : this._w1), 10); else {
                if (!b) {
                    b = this._p;
                    var f = this._h3 = this._l4();
                    this._p1.css(this._g + this._u1, "0ms");
                    b !== f && this._p3(f)
                }
            } else 20 < this._m ? this._m = 10 : this._m++
        },
        _l4: function () {
            var b = window.getComputedStyle(this._p1.get(0), null).getPropertyValue(this._g + "transform").replace(/^matrix\(/i, "").split(/, |\)$/g),
                f = 0 === b[0].indexOf("matrix3d");
            return parseInt(b[this.isHorizontal ? f ? 12 : 4 : f ? 13 : 5], 10)
        },
        _m4: function (b, f) {
            return this._e ? this._y1 + (f ? b + this._z1 + 0 : 0 + this._z1 + b) + this._a2 :
                b
        },
        _k4: function (b) {
            this.transitionIsNotFade || (this._r1.css("z-index", 0), this._m = 10);
            this._r2 = !1;
            this.staticSlideId = this.currSlideId;
            this._n2();
            this._n4 = !1;
            this.ev.trigger("rsAfterSlideChange")
        },
        _i4: function (b, f) {
            var c = this, a = (-c._u - c._d1) * c._w;
            if (0 !== c.numSlides && !c._r2) if (c.options.loopRewind) c.goTo("left" === b ? c.numSlides - 1 : 0, f); else if (c.transitionIsNotFade) {
                c.transitionSpeed = 200;
                var e = function () {
                    c._r2 = !1
                };
                c._x3(a + ("left" === b ? 30 : -30), "", !1, !0, function () {
                    c._r2 = !1;
                    c._x3(a, "", !1, !0, e)
                })
            }
        },
        _q2: function (b, f) {
            if (!b.isRendered) {
                var c = b.content, a = "rsMainSlideImage",
                    e,
                    g = $.isFunction(this.options.imageAlignCenter) ? this.options.imageAlignCenter(b) : this.options.imageAlignCenter,
                    d = $.isFunction(this.options.imageScaleMode) ? this.options.imageScaleMode(b) : this.options.imageScaleMode,
                    h;
                b.videoURL && (a = "rsVideoContainer", "fill" !== d ? e = !0 : (h = c, h.hasClass(a) || (h = h.find("." + a)), h.css({
                    width: "100%",
                    height: "100%"
                }), a = "rsMainSlideImage"));
                c.hasClass(a) || (c = c.find("." + a));
                if (c) {
                    var k = b.iW, l = b.iH;
                    b.isRendered = !0;
                    if ("none" !== d || g) {
                        a = "fill" !== d ? this.imageScalePadding : 0;
                        h = this.sliderWidth - 2 * a;
                        var q = this.sliderHeight - 2 * a, m, p, r = {};
                        "fit-if-smaller" ===
                        d && (k > h || l > q) && (d = "fit");
                        if ("fill" === d || "fit" === d) m = h / k, p = q / l, m = "fill" == d ? m > p ? m : p : "fit" == d ? m < p ? m : p : 1, k = Math.ceil(k * m, 10), l = Math.ceil(l * m, 10);
                        "none" !== d && (r.width = k, r.height = l, e && c.find(".rsImg").css({
                            width: "100%",
                            height: "100%"
                        }));
                        g && (r.marginLeft = Math.floor((h - k) / 2) + a, r.marginTop = Math.floor((q - l) / 2) + a);
                        c.css(r)
                    }
                }
            }
        }
    };
    $.rsProto = rsInit.prototype;
    $.fn.royalSlider = function (args) {
        return this.each(function () {
            var $self = $(this);
            if (typeof args !== "object" && args) {
                if (($self = $self.data("royalSlider")) && $self[args]) {
                    return $self[args].apply($self, Array.prototype.slice.call(arguments, 1))
                }
            } else {
                $self.data("royalSlider") || $self.data("royalSlider", new rsInit($self, args))
            }
        });
    };
    $.fn.royalSlider.defaults = {
        imageScaleMode: "fit-if-smaller",
        controlNavigation: "bullets",
        slidesOrientation: "horizontal",
        transitionType: "move",
        easeOut: "easeOutSine",
        easeInOut: "easeInOutSine",

        arrowsNav: true,
        arrowsNavAutoHide: true,
        arrowsNavHideOnTouch: false,

        navigateByClick: true,
        sliderTouch: true,
        sliderDrag: true,
        keyboardNavEnabled: false,

        loop: false,
        loopRewind: false,

        randomizeSlides: false,
        usePreloader: true,
        globalCaption: false,

        autoHeight: false,
        imageAlignCenter: true,
        addActiveClass: false,
        autoScaleHeight: false,

        startSlideId: 0,
        numImagesToPreload: 4,
        slidesSpacing: 8,
        minSlideOffset: 10,
        transitionSpeed: 600,
        imageScalePadding: 4,
        slidesDiff: 2
    };
    $.rsCSS3Easing = {
        easeOutSine: "cubic-bezier(0.390, 0.575, 0.565, 1.000)",
        easeInOutSine: "cubic-bezier(0.445, 0.050, 0.550, 0.950)"
    };
    $.extend(jQuery.easing, {
        easeOutSine: function (b, f, c, a, e) {
            return a * Math.sin(f / e * (Math.PI / 2)) + c
        },
        easeInOutSine: function (b, f, c, a, e) {
            return -a / 2 * (Math.cos(Math.PI * f / e) - 1) + c
        },
        easeOutCubic: function (b, f, c, a, e) {
            return a * ((f = f / e - 1) * f * f + 1) + c
        }
    })
})(jQuery, window);
// jquery.rs.bullets v1.0.1
(function ($) {
    $.rsModules.bullets = $.rsProto.bullets = function () {
        var slider = this;
        "bullets" === slider.options.controlNavigation && (slider.ev.one("rsAfterPropsSetup", function () {
            slider._j5 = !0;
            slider.slider.addClass("rsWithBullets");
            for (var markupBullets = '<div class="rsNav rsBullets">', e = 0; e < slider.numSlides; e++) {
                markupBullets += '<div class="rsNavItem rsBullet"><span></span></div>';
            }
            slider._k5 = markupBullets = $(markupBullets + "</div>");
            slider._l5 = markupBullets.appendTo(slider.slider).children();
            slider._k5.on("click.rs", ".rsNavItem", function (event) {
                slider._m5 || slider.goTo($(this).index())
            });
        }), slider.ev.on("rsOnAppendSlide", function (b, c, d) {
            d >= slider.numSlides ? slider._k5.append('<div class="rsNavItem rsBullet"><span></span></div>') :
                slider._l5.eq(d).before('<div class="rsNavItem rsBullet"><span></span></div>');
            slider._l5 = slider._k5.children()
        }), slider.ev.on("rsOnRemoveSlide", function (b, c) {
            var d = slider._l5.eq(c);
            d && d.length && (d.remove(), slider._l5 = slider._k5.children())
        }), slider.ev.on("rsOnUpdateNav", function () {
            var b = slider.currSlideId;
            slider._n5 && slider._n5.removeClass("rsNavSelected");
            b = slider._l5.eq(b);
            b.addClass("rsNavSelected");
            slider._n5 = b
        }))
    };
})(jQuery);
// jquery.rs.thumbnails v1.0.8
(function ($) {
    $.extend($.rsProto, {
        thumbnails: function () {
            var self = this;
            "thumbnails" === self.options.controlNavigation && (self.optionsThumbs = {
                drag: true,
                touch: true,
                orientation: "horizontal",
                navigation: true,
                arrows: true,
                arrowLeft: null,
                arrowRight: null,
                spacing: 4,
                arrowsAutoHide: false,
                appendSpan: false,
                transitionSpeed: 600,
                autoCenter: true,
                fitInViewport: true,
                firstMargin: true,
                paddingTop: 0,
                paddingBottom: 0
            }, self.options.thumbs = $.extend({}, self.optionsThumbs, self.options.thumbs), self._j6 = !0, !1 === self.options.thumbs.firstMargin ? self.options.thumbs.firstMargin = 0 : !0 === self.options.thumbs.firstMargin && (self.options.thumbs.firstMargin =
                self.options.thumbs.spacing),
                self.ev.on("rsBeforeParseNode", function (a, b, c) {
                    b = $(b);
                    c.thumbnail = b.find(".rsTmb").remove();
                    c.thumbnail.length ? c.thumbnail = $(document.createElement("div")).append(c.thumbnail).html() : (c.thumbnail = b.attr("data-rsTmb"), c.thumbnail || (c.thumbnail = b.find(".rsImg").attr("data-rsTmb")), c.thumbnail = c.thumbnail ? '<img src="' + c.thumbnail + '"/>' : "")
                }),
                self.ev.one("rsAfterPropsSetup", function () {
                    self._k6()
                }), self._n5 = null, self.ev.on("rsOnUpdateNav", function () {
                var e = $(self._l5[self.currSlideId]);
                e !== self._n5 && (self._n5 &&
                (self._n5.removeClass("rsNavSelected"), self._n5 = null), self._l6 && self._m6(self.currSlideId), self._n5 = e.addClass("rsNavSelected"))
            }), self.ev.on("rsOnAppendSlide", function (e, b, c) {
                e = "<div" + self._n6 + ' class="rsNavItem rsThumb">' + self._o6 + b.thumbnail + "</div>";
                self._e && self._s3.css(self._g + "transition-duration", "0ms");
                c >= self.numSlides ? self._s3.append(e) : self._l5.eq(c).before(e);
                self._l5 = self._s3.children();
                self.updateThumbsSize(!0)
            }), self.ev.on("rsOnRemoveSlide", function (e, b) {
                var c = self._l5.eq(b);
                c && (self._e && self._s3.css(self._g + "transition-duration", "0ms"), c.remove(),
                    self._l5 = self._s3.children(), self.updateThumbsSize(!0))
            }))
        },
        _k6: function () {
            var self = this, e = "rsThumbs", b = self.options.thumbs, c = "", g, d, h = b.spacing;
            self._j5 = !0;
            self._e3 = "vertical" === b.orientation ? !1 : !0;
            self._n6 = g = h ? ' style="margin-' + (self._e3 ? "right" : "bottom") + ":" + h + 'px;"' : "";
            self._i3 = 0;
            self._p6 = !1;
            self._m5 = !1;
            self._l6 = !1;
            self._q6 = b.arrows && b.navigation;
            d = self._e3 ? "Hor" : "Ver";
            self.slider.addClass("rsWithThumbs rsWithThumbs" + d);
            c += '<div class="rsNav rsThumbs rsThumbs' + d + '"><div class="' + e + 'Container">';
            self._o6 = b.appendSpan ? '<span class="thumbIco"></span>' :
                "";
            for (var k = 0; k < self.numSlides; k++) d = self.slides[k], c += "<div" + g + ' class="rsNavItem rsThumb">' + d.thumbnail + self._o6 + "</div>";
            c = $(c + "</div></div>");
            g = {};
            b.paddingTop && (g[self._e3 ? "paddingTop" : "paddingLeft"] = b.paddingTop);
            b.paddingBottom && (g[self._e3 ? "paddingBottom" : "paddingRight"] = b.paddingBottom);
            c.css(g);
            self._s3 = $(c).find("." + e + "Container");
            self._q6 && (e += "Arrow", b.arrowLeft ? self._r6 = b.arrowLeft : (self._r6 = $('<div class="' + e + " " + e + 'Left"><div class="' + e + 'Icn"></div></div>'), c.append(self._r6)), b.arrowRight ? self._s6 = b.arrowRight :
                (self._s6 = $('<div class="' + e + " " + e + 'Right"><div class="' + e + 'Icn"></div></div>'), c.append(self._s6)), self._r6.click(function () {
                var b = (Math.floor(self._i3 / self._t6) + self._u6) * self._t6 + self.options.thumbs.firstMargin;
                self._a4(b > self._n3 ? self._n3 : b)
            }), self._s6.click(function () {
                var b = (Math.floor(self._i3 / self._t6) - self._u6) * self._t6 + self.options.thumbs.firstMargin;
                self._a4(b < self._o3 ? self._o3 : b)
            }), b.arrowsAutoHide && !self.hasTouch && (self._r6.css("opacity", 0), self._s6.css("opacity", 0), c.one("mousemove.rsarrowshover", function () {
                self._l6 && (self._r6.css("opacity", 1), self._s6.css("opacity", 1))
            }),
                c.hover(function () {
                    self._l6 && (self._r6.css("opacity", 1), self._s6.css("opacity", 1))
                }, function () {
                    self._l6 && (self._r6.css("opacity", 0), self._s6.css("opacity", 0))
                })));
            self._k5 = c;
            self._l5 = self._s3.children();
            self.msEnabled && self.options.thumbs.navigation && self._s3.css("-ms-touch-action", self._e3 ? "pan-y" : "pan-x");
            self.slider.append(c);
            self._w3 = !0;
            self._v6 = h;
            b.navigation && self._e && self._s3.css(self._g + "transition-property", self._g + "transform");
            self._k5.on("click.rs", ".rsNavItem", function (b) {
                self._m5 || self.goTo($(this).index())
            });
            self.ev.off("rsBeforeSizeSet.thumbs").on("rsBeforeSizeSet.thumbs", function () {
                self._w6 = self._e3 ? self.sliderHeight : self.sliderWidth;
                self.updateThumbsSize(true)
            });
            self.ev.off("rsAutoHeightChange.thumbs").on("rsAutoHeightChange.thumbs", function (b, c) {
                self.updateThumbsSize(true, c)
            })
        },
        updateThumbsSize: function (a, e) {
            var self = this, c = self._l5.first(), f = {}, d = self._l5.length;
            self._t6 = (self._e3 ? c.outerWidth() : c.outerHeight()) + self._v6;
            self._y3 = d * self._t6 - self._v6;
            f[self._e3 ? "width" : "height"] = self._y3 + self._v6;
            self._z3 = self._e3 ? self._k5.width() : void 0 !== e ? e : self._k5.height();
            self._w3 && (self.isFullscreen || self.options.thumbs.fitInViewport) && (self._e3 ? self.sliderHeight = self._w6 - self._k5.outerHeight() :
                self.sliderWidth = self._w6 - self._k5.outerWidth());
            self._z3 && (self._o3 = -(self._y3 - self._z3) - self.options.thumbs.firstMargin, self._n3 = self.options.thumbs.firstMargin, self._u6 = Math.floor(self._z3 / self._t6), self._y3 < self._z3 ? (self.options.thumbs.autoCenter ? self._q3((self._z3 - self._y3) / 2) : self._q3(self._n3), self.options.thumbs.arrows && self._r6 && (self._r6.addClass("rsThumbsArrowDisabled"), self._s6.addClass("rsThumbsArrowDisabled")), self._l6 = !1, self._m5 = !1, self._k5.off(self._j1)) : self.options.thumbs.navigation && !self._l6 && (self._l6 = !0, !self.hasTouch && self.options.thumbs.drag || self.hasTouch && self.options.thumbs.touch) && (self._m5 = true, self._k5.on(self._j1, function (event) {
                self.startTouch(event, true);
            })), self._s3.css(f), a && e && self._m6(self.currSlideId, true))
            //TODO Here Added Modification Hack For Left Alignment must be found another way
            /*if (self.numSlides == 1){
                self._s3.css({ float: "right" });
            } else if (self.numSlides == 3){
                self._s3.css({ transform: "translate3d(160px, 0, 0)" });
            }*/
            self._s3.css({transform: "translate3d(160px, 0, 0)"});
        },
        setThumbsOrientation: function (a, e) {
            this._w3 && (this.options.thumbs.orientation = a, this._k5.remove(), this.slider.removeClass("rsWithThumbsHor rsWithThumbsVer"), this._k6(), this._k5.off(this._j1), e || this.updateSliderSize(!0))
        },
        _q3: function (a) {
            this._i3 = a;
            this._e ? this._s3.css(this._x1, this._y1 + (this._e3 ? a + this._z1 + 0 : 0 + this._z1 + a) + this._a2) : this._s3.css(this._e3 ? this._x1 : this._w1, a)
        },
        _a4: function (a, e, b, c, g) {
            var d = this;
            if (d._l6) {
                e || (e = d.options.thumbs.transitionSpeed);
                d._i3 = a;
                d._x6 && clearTimeout(d._x6);
                d._p6 && (d._e || d._s3.stop(), b = !0);
                var h = {};
                d._p6 = !0;
                d._e ? (h[d._g + "transition-duration"] = e + "ms", h[d._g + "transition-timing-function"] = b ? $.rsCSS3Easing[d.options.easeOut] : $.rsCSS3Easing[d.options.easeInOut], d._s3.css(h), d._q3(a)) : (h[d._e3 ? d._x1 : d._w1] = a + "px", d._s3.animate(h, e, b ? "easeOutCubic" : d.options.easeInOut));
                c && (d._i3 = c);
                d._y6();
                d._x6 = setTimeout(function () {
                    d._p6 = !1;
                    g && (d._a4(c, g, !0), g = null)
                }, e)
            }
        },
        _y6: function () {
            this._q6 && (this._i3 === this._n3 ? this._r6.addClass("rsThumbsArrowDisabled") :
                this._r6.removeClass("rsThumbsArrowDisabled"), this._i3 === this._o3 ? this._s6.addClass("rsThumbsArrowDisabled") : this._s6.removeClass("rsThumbsArrowDisabled"))
        },
        _m6: function (a, e) {
            var b = 0, c, f = a * this._t6 + 2 * this._t6 - this._v6 + this._n3, d = Math.floor(this._i3 / this._t6);
            this._l6 && (this._j6 && (e = !0, this._j6 = !1), f + this._i3 > this._z3 ? (a === this.numSlides - 1 && (b = 1), d = -a + this._u6 - 2 + b, c = d * this._t6 + this._z3 % this._t6 + this._v6 - this._n3) : 0 !== a ? (a - 1) * this._t6 <= -this._i3 + this._n3 && a - 1 <= this.numSlides - this._u6 && (c = (-a + 1) * this._t6 +
                this._n3) : c = this._n3, c !== this._i3 && (b = void 0 === c ? this._i3 : c, b > this._n3 ? this._q3(this._n3) : b < this._o3 ? this._q3(this._o3) : void 0 !== c && (e ? this._q3(c) : this._a4(c))), this._y6())
        }
    });
    $.rsModules.thumbnails = $.rsProto.thumbnails
})(jQuery);
// jquery.rs.tabs v1.0.2
(function ($) {
    $.extend($.rsProto, {
        _f6: function () {
            var slider = this;
            "tabs" === slider.options.controlNavigation && (slider.ev.on("rsBeforeParseNode", function (a, d, b) {
                d = $(d);
                b.thumbnail = d.find(".rsTmb").remove();
                b.thumbnail.length ? b.thumbnail = $(document.createElement("div")).append(b.thumbnail).html() : (b.thumbnail = d.attr("data-rsTmb"), b.thumbnail || (b.thumbnail = d.find(".rsImg").attr("data-rsTmb")), b.thumbnail = b.thumbnail ? '<img src="' + b.thumbnail + '"/>' : "")
            }), slider.ev.one("rsAfterPropsSetup", function () {
                slider._g6()
            }), slider.ev.on("rsOnAppendSlide",
                function (c, d, b) {
                    b >= slider.numSlides ? slider._k5.append('<div class="rsNavItem rsTab">' + d.thumbnail + "</div>") : slider._l5.eq(b).before('<div class="rsNavItem rsTab">' + item.thumbnail + "</div>");
                    slider._l5 = slider._k5.children()
                }), slider.ev.on("rsOnRemoveSlide", function (c, d) {
                var b = slider._l5.eq(d);
                b && (b.remove(), slider._l5 = slider._k5.children())
            }), slider.ev.on("rsOnUpdateNav", function () {
                var c = slider.currSlideId;
                slider._n5 && slider._n5.removeClass("rsNavSelected");
                c = slider._l5.eq(c);
                c.addClass("rsNavSelected");
                slider._n5 = c
            }))
        }, _g6: function () {
            var slider = this;
            slider._j5 = !0;
            var markupTabs = '<div class="rsNav rsTabs">';
            for (var d = 0; d < slider.numSlides; d++) markupTabs += '<div class="rsNavItem rsTab">' + slider.slides[d].thumbnail + "</div>";
            markupTabs = $(markupTabs + "</div>");
            slider._k5 = markupTabs;
            slider._l5 = markupTabs.children(".rsNavItem");
            slider.slider.append(markupTabs);
            slider._k5.click(function (event) {
                event = $(event.target).closest(".rsNavItem");
                event.length && slider.goTo(event.index())
            });
        }
    });
    $.rsModules.tabs = $.rsProto._f6
})(jQuery);
// jquery.rs.fullscreen v1.0.6
(function ($) {
    $.extend($.rsProto, {
        _q5: function () {
            var slider = this;
            slider._r5 = {enabled: !1, keyboardNav: !0, buttonFS: !0, nativeFS: !1, doubleTap: !0};
            slider.options.fullscreen = $.extend({}, slider._r5, slider.options.fullscreen);
            if (slider.options.fullscreen.enabled) {
                slider.ev.one("rsBeforeSizeSet", function () {
                    slider._s5()
                });
            }
        },
        _s5: function () {
            var slider = this;
            slider._t5 = !slider.options.keyboardNavEnabled && slider.options.fullscreen.keyboardNav;
            if (slider.options.fullscreen.nativeFS) {
                var b = {
                    supportsFullScreen: !1,
                    fullScreenEventName: "",
                    prefix: "",
                    isFullScreen: function () {
                        return !1;
                    },
                    requestFullScreen: function () {
                    },
                    cancelFullScreen: function () {
                    }
                };
                var d = ["webkit", "moz", "o", "ms", "khtml"];
                if ("undefined" !== typeof document.cancelFullScreen) {
                    b.supportsFullScreen = !0;
                } else {
                    for (var e = 0, f = d.length; e < f; e++) {
                        if (b.prefix = d[e], "undefined" !== typeof document[b.prefix + "CancelFullScreen"]) {
                            b.supportsFullScreen = !0;
                            break;
                        }
                    }
                }
                b.supportsFullScreen ? (slider.nativeFS = !0, b.fullScreenEventName = b.prefix + "fullscreenchange" + slider.ns, b.isFullScreen = function () {
                    switch (this.prefix) {
                        case "": {
                            return document.fullScreen;
                        }
                        case "webkit": {
                            return document.webkitIsFullScreen;
                        }
                        default: {
                            return document[this.prefix + "FullScreen"]
                        }
                    }
                }, b.requestFullScreen = function (a) {
                    return "" === this.prefix ? a.requestFullScreen() : a[this.prefix + "RequestFullScreen"]()
                }, b.cancelFullScreen = function (a) {
                    return "" === this.prefix ? document.cancelFullScreen() : document[this.prefix + "CancelFullScreen"]()
                }, slider._u5 = b) : slider._u5 = !1
            }
            slider.options.fullscreen.buttonFS && (slider._v5 = $('<div class="rsFullscreenBtn"><div class="rsFullscreenIcn"></div></div>').appendTo(slider._o1).on("click.rs", function () {
                slider.isFullscreen ? slider.exitFullscreen() : slider.enterFullscreen()
            }))
        },
        enterFullscreen: function (a) {
            var slider = this;
            if (slider._u5) if (a) slider._u5.requestFullScreen($("html")[0]); else {
                slider.$document.on(slider._u5.fullScreenEventName, function (a) {
                    slider._u5.isFullScreen() ? slider.enterFullscreen(!0) : slider.exitFullscreen(!0)
                });
                slider._u5.requestFullScreen($("html")[0]);
                return
            }
            if (!slider._w5) {
                slider._w5 = !0;
                slider.$document.on("keyup" + slider.ns + "fullscreen", function (a) {
                    27 === a.keyCode && slider.exitFullscreen()
                });
                slider._t5 && slider._b2();
                a = $(window);
                slider._x5 = a.scrollTop();
                slider._y5 = a.scrollLeft();
                slider._z5 = $("html").attr("style");
                slider._a6 = $("body").attr("style");
                slider._b6 = slider.slider.attr("style");
                $("body, html").css({
                    overflow: "hidden",
                    height: "100%", width: "100%", margin: "0", padding: "0"
                });
                slider.slider.addClass("rsFullscreen");
                var d;
                for (d = 0; d < slider.numSlides; d++) a = slider.slides[d], a.isRendered = !1, a.bigImage && (a.isBig = !0, a.isMedLoaded = a.isLoaded, a.isMedLoading = a.isLoading, a.medImage = a.image, a.medIW = a.iW, a.medIH = a.iH, a.slideId = -99, a.bigImage !== a.medImage && (a.sizeType = "big"), a.isLoaded = a.isBigLoaded, a.isLoading = !1, a.image = a.bigImage, a.images[0] = a.bigImage, a.iW = a.bigIW, a.iH = a.bigIH, a.isAppended = a.contentAdded = !1, slider.updateMarkup(a));
                slider.isFullscreen = !0;
                slider._w5 = !1;
                slider.updateSliderSize();
                slider.ev.trigger("rsEnterFullscreen")
            }
        },
        exitFullscreen: function (a) {
            var slider = this;
            if (slider._u5) {
                if (!a) {
                    slider._u5.cancelFullScreen($("html")[0]);
                    return
                }
                slider.$document.off(slider._u5.fullScreenEventName)
            }
            if (!slider._w5) {
                slider._w5 = !0;
                slider.$document.off("keyup" + slider.ns + "fullscreen");
                slider._t5 && slider.$document.off("keydown" + slider.ns);
                $("html").attr("style", slider._z5 || "");
                $("body").attr("style", slider._a6 || "");
                var d;
                for (d = 0; d < slider.numSlides; d++) a = slider.slides[d], a.isRendered = !1, a.bigImage && (a.isBig = !1, a.slideId = -99, a.isBigLoaded = a.isLoaded, a.isBigLoading = a.isLoading, a.bigImage =
                    a.image, a.bigIW = a.iW, a.bigIH = a.iH, a.isLoaded = a.isMedLoaded, a.isLoading = !1, a.image = a.medImage, a.images[0] = a.medImage, a.iW = a.medIW, a.iH = a.medIH, a.isAppended = a.contentAdded = !1, slider.updateMarkup(a, !0), a.bigImage !== a.medImage && (a.sizeType = "med"));
                slider.isFullscreen = !1;
                a = $(window);
                a.scrollTop(slider._x5);
                a.scrollLeft(slider._y5);
                slider._w5 = !1;
                slider.slider.removeClass("rsFullscreen");
                slider.updateSliderSize();
                setTimeout(function () {
                    slider.updateSliderSize()
                }, 1);
                slider.ev.trigger("rsExitFullscreen")
            }
        },
        updateMarkup: function (a, b) {
            var imgMarkup = '<a class="rsImg rsMainSlideImage" href="' + a.image + '"></a>';
            if (a.isLoaded || a.isLoading) {
                imgMarkup = '<img class="rsImg rsMainSlideImage" src="' + a.image + '"/>';
            }
            a.content.hasClass("rsImg") ? a.content = $(imgMarkup) : a.content.find(".rsImg").eq(0).replaceWith(imgMarkup);
            a.isLoaded || a.isLoading || !a.holder || a.holder.html(a.content)
        }
    });
    $.rsModules.fullscreen = $.rsProto._q5
})(jQuery);
// jquery.rs.autoplay v1.0.5
(function ($) {
    $.extend($.rsProto, {
        _x4: function () {
            var a = this, d;
            a._y4 = {enabled: !1, stopAtAction: !0, pauseOnHover: !0, delay: 2E3};
            !a.options.autoPlay && a.options.autoplay && (a.options.autoPlay = a.options.autoplay);
            a.options.autoPlay = $.extend({}, a._y4, a.options.autoPlay);
            a.options.autoPlay.enabled && (a.ev.on("rsBeforeParseNode", function (a, c, f) {
                c = $(c);
                if (d = c.attr("data-rsDelay")) f.customDelay = parseInt(d, 10)
            }), a.ev.one("rsAfterInit", function () {
                a._z4()
            }), a.ev.on("rsBeforeDestroy", function () {
                a.stopAutoPlay();
                a.slider.off("mouseenter mouseleave");
                $(window).off("blur" +
                    a.ns + " focus" + a.ns)
            }))
        }, _z4: function () {
            var a = this;
            a.startAutoPlay();
            a.ev.on("rsAfterContentSet", function (b, e) {
                a._l2 || a._r2 || !a._a5 || e !== a.currSlide || a._b5()
            });
            a.ev.on("rsDragRelease", function () {
                a._a5 && a._c5 && (a._c5 = !1, a._b5())
            });
            a.ev.on("rsAfterSlideChange", function () {
                a._a5 && a._c5 && (a._c5 = !1, a.currSlide.isLoaded && a._b5())
            });
            a.ev.on("rsDragStart", function () {
                a._a5 && (a.options.autoPlay.stopAtAction ? a.stopAutoPlay() : (a._c5 = !0, a._d5()))
            });
            a.ev.on("rsBeforeMove", function (b, e, c) {
                a._a5 && (c && a.options.autoPlay.stopAtAction ?
                    a.stopAutoPlay() : (a._c5 = !0, a._d5()))
            });
            a._e5 = !1;
            a.ev.on("rsVideoStop", function () {
                a._a5 && (a._e5 = !1, a._b5())
            });
            a.ev.on("rsVideoPlay", function () {
                a._a5 && (a._c5 = !1, a._d5(), a._e5 = !0)
            });
            $(window).on("blur" + a.ns, function () {
                a._a5 && (a._c5 = !0, a._d5())
            }).on("focus" + a.ns, function () {
                a._a5 && a._c5 && (a._c5 = !1, a._b5())
            });
            a.options.autoPlay.pauseOnHover && (a._f5 = !1, a.slider.hover(function () {
                a._a5 && (a._c5 = !1, a._d5(), a._f5 = !0)
            }, function () {
                a._a5 && (a._f5 = !1, a._b5())
            }))
        }, toggleAutoPlay: function () {
            this._a5 ? this.stopAutoPlay() :
                this.startAutoPlay()
        }, startAutoPlay: function () {
            this._a5 = !0;
            this.currSlide.isLoaded && this._b5()
        }, stopAutoPlay: function () {
            this._e5 = this._f5 = this._c5 = this._a5 = !1;
            this._d5()
        }, _b5: function () {
            var a = this;
            a._f5 || a._e5 || (a._g5 = !0, a._h5 && clearTimeout(a._h5), a._h5 = setTimeout(function () {
                var b;
                a._z || a.options.loopRewind || (b = !0, a.options.loopRewind = !0);
                a.next(!0);
                b && (a.options.loopRewind = !1)
            }, a.currSlide.customDelay ? a.currSlide.customDelay : a.options.autoPlay.delay))
        }, _d5: function () {
            this._f5 || this._e5 || (this._g5 = !1, this._h5 && (clearTimeout(this._h5), this._h5 = null))
        }
    });
    $.rsModules.autoplay = $.rsProto._x4
})(jQuery);
// jquery.rs.video v1.1.3
(function ($) {
    $.extend($.rsProto, {
        _z6: function () {
            var a = this;
            a._a7 = {
                autoHideArrows: !0,
                autoHideControlNav: !1,
                autoHideBlocks: !1,
                autoHideCaption: !1,
                disableCSS3inFF: !0,
                youTubeCode: '<iframe src="https://www.youtube.com/embed/%id%?rel=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
                vimeoCode: '<iframe src="https://player.vimeo.com/video/%id%?byline=0&portrait=0&autoplay=1" frameborder="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
            };
            a.options.video = $.extend({}, a._a7,
                a.options.video);
            a.ev.on("rsBeforeSizeSet", function () {
                a._b7 && setTimeout(function () {
                    var b = a._r1, b = b.hasClass("rsVideoContainer") ? b : b.find(".rsVideoContainer");
                    a._c7 && a._c7.css({width: b.width(), height: b.height()})
                }, 32)
            });
            var d = a.deviceInfo.mozilla;
            a.ev.on("rsAfterParseNode", function (b, c, e) {
                b = $(c);
                if (e.videoURL) {
                    a.options.video.disableCSS3inFF && d && (a._e = a._f = !1);
                    c = $('<div class="rsVideoContainer"></div>');
                    var g = $('<div class="rsBtnCenterer"><div class="rsPlayBtn"><div class="rsPlayBtnIcon"></div></div></div>');
                    b.hasClass("rsImg") ?
                        e.content = c.append(b).append(g) : e.content.find(".rsImg").wrap(c).after(g)
                }
            });
            a.ev.on("rsAfterSlideChange", function () {
                a.stopVideo()
            })
        }, toggleVideo: function () {
            return this._b7 ? this.stopVideo() : this.playVideo()
        }, playVideo: function () {
            var a = this;
            if (!a._b7) {
                var d = a.currSlide;
                if (!d.videoURL) return !1;
                a._d7 = d;
                var b = a._e7 = d.content, d = d.videoURL, c, e;
                d.match(/youtu\.be/i) || d.match(/youtube\.com/i) ? (e = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/, (e = d.match(e)) && 11 == e[7].length &&
                (c = e[7]), void 0 !== c && (a._c7 = a.options.video.youTubeCode.replace("%id%", c))) : d.match(/vimeo\.com/i) && (e = /(www\.)?vimeo.com\/(\d+)($|\/)/, (e = d.match(e)) && (c = e[2]), void 0 !== c && (a._c7 = a.options.video.vimeoCode.replace("%id%", c)));
                a.videoObj = $(a._c7);
                a.ev.trigger("rsOnCreateVideoElement", [d]);
                a.videoObj.length && (a._c7 = $('<div class="rsVideoFrameHolder"><div class="rsPreloader"></div><div class="rsCloseVideoBtn"><div class="rsCloseVideoIcn"></div></div></div>'), a._c7.find(".rsPreloader").after(a.videoObj), b = b.hasClass("rsVideoContainer") ?
                    b : b.find(".rsVideoContainer"), a._c7.css({
                    width: b.width(),
                    height: b.height()
                }).find(".rsCloseVideoBtn").off("click.rsv").on("click.rsv", function (b) {
                    a.stopVideo();
                    b.preventDefault();
                    b.stopPropagation();
                    return !1
                }), b.append(a._c7), a.isIPAD && b.addClass("rsIOSVideo"), a._f7(!1), setTimeout(function () {
                    a._c7.addClass("rsVideoActive")
                }, 10), a.ev.trigger("rsVideoPlay"), a._b7 = !0);
                return !0
            }
            return !1
        }, stopVideo: function () {
            var a = this;
            return a._b7 ? (a.isIPAD && a.slider.find(".rsCloseVideoBtn").remove(), a._f7(!0), setTimeout(function () {
                a.ev.trigger("rsOnDestroyVideoElement",
                    [a.videoObj]);
                var d = a._c7.find("iframe");
                if (d.length) try {
                    d.attr("src", "")
                } catch (b) {
                }
                a._c7.remove();
                a._c7 = null
            }, 16), a.ev.trigger("rsVideoStop"), a._b7 = !1, !0) : !1
        }, _f7: function (a, d) {
            var b = [], c = this.options.video;
            c.autoHideArrows && (this._c2 && (b.push(this._c2, this._d2), this._e2 = !a), this._v5 && b.push(this._v5));
            c.autoHideControlNav && this._k5 && b.push(this._k5);
            c.autoHideBlocks && this._d7.animBlocks && b.push(this._d7.animBlocks);
            c.autoHideCaption && this.globalCaption && b.push(this.globalCaption);
            this.slider[a ? "removeClass" :
                "addClass"]("rsVideoPlaying");
            if (b.length) for (c = 0; c < b.length; c++) a ? b[c].removeClass("rsHidden") : b[c].addClass("rsHidden")
        }
    });
    $.rsModules.video = $.rsProto._z6
})(jQuery);
// jquery.rs.animated-blocks v1.0.7
(function ($) {
    $.extend($.rsProto, {
        _p4: function () {
            function m() {
                var g = a.currSlide;
                if (a.currSlide && a.currSlide.isLoaded && a._t4 !== g) {
                    if (0 < a._s4.length) {
                        for (b = 0; b < a._s4.length; b++) clearTimeout(a._s4[b]);
                        a._s4 = []
                    }
                    if (0 < a._r4.length) {
                        var f;
                        for (b = 0; b < a._r4.length; b++) if (f = a._r4[b]) a._e ? (f.block.css(a._g + a._u1, "0s"), f.block.css(f.css)) : f.block.stop(!0).css(f.css), a._t4 = null, g.animBlocksDisplayed = !1;
                        a._r4 = []
                    }
                    g.animBlocks && (g.animBlocksDisplayed = !0, a._t4 = g, a._u4(g.animBlocks))
                }
            }

            var a = this, b;
            a._q4 = {
                fadeEffect: !0,
                moveEffect: "top", moveOffset: 20, speed: 400, easing: "easeOutSine", delay: 200
            };
            a.options.block = $.extend({}, a._q4, a.options.block);
            a._r4 = [];
            a._s4 = [];
            a.ev.on("rsAfterInit", function () {
                m()
            });
            a.ev.on("rsBeforeParseNode", function (a, b, d) {
                b = $(b);
                d.animBlocks = b.find(".rsABlock").css("display", "none");
                d.animBlocks.length || (b.hasClass("rsABlock") ? d.animBlocks = b.css("display", "none") : d.animBlocks = !1)
            });
            a.ev.on("rsAfterContentSet", function (b, f) {
                f.id === a.slides[a.currSlideId].id && setTimeout(function () {
                    m()
                }, 0)
            });
            a.ev.on("rsAfterSlideChange", function () {
                m()
            })
        }, _v4: function (l, a) {
            setTimeout(function () {
                l.css(a)
            }, 6)
        }, _u4: function (m) {
            var a = this, b, g, f, d, h, e, n;
            a._s4 = [];
            m.each(function (m) {
                b = $(this);
                g = {};
                f = {};
                d = null;
                var c = b.attr("data-move-offset"), c = c ? parseInt(c, 10) : a.options.block.moveOffset;
                if (0 < c && ((e = b.data("move-effect")) ? (e = e.toLowerCase(), "none" === e ? e = !1 : "left" !== e && "top" !== e && "bottom" !== e && "right" !== e && (e = a.options.block.moveEffect, "none" === e && (e = !1))) : e = a.options.block.moveEffect, e && "none" !== e)) {
                    var p;
                    p = "right" ===
                    e || "left" === e ? !0 : !1;
                    var k;
                    n = !1;
                    a._e ? (k = 0, h = a._x1) : (p ? isNaN(parseInt(b.css("right"), 10)) ? h = "left" : (h = "right", n = !0) : isNaN(parseInt(b.css("bottom"), 10)) ? h = "top" : (h = "bottom", n = !0), h = "margin-" + h, n && (c = -c), a._e ? k = parseInt(b.css(h), 10) : (k = b.data("rs-start-move-prop"), void 0 === k && (k = parseInt(b.css(h), 10), isNaN(k) && (k = 0), b.data("rs-start-move-prop", k))));
                    f[h] = a._m4("top" === e || "left" === e ? k - c : k + c, p);
                    g[h] = a._m4(k, p)
                }
                c = b.attr("data-fade-effect");
                if (!c) c = a.options.block.fadeEffect; else if ("none" === c.toLowerCase() ||
                    "false" === c.toLowerCase()) c = !1;
                c && (f.opacity = 0, g.opacity = 1);
                if (c || e) d = {}, d.hasFade = Boolean(c), Boolean(e) && (d.moveProp = h, d.hasMove = !0), d.speed = b.data("speed"), isNaN(d.speed) && (d.speed = a.options.block.speed), d.easing = b.data("easing"), d.easing || (d.easing = a.options.block.easing), d.css3Easing = $.rsCSS3Easing[d.easing], d.delay = b.data("delay"), isNaN(d.delay) && (d.delay = a.options.block.delay * m);
                c = {};
                a._e && (c[a._g + a._u1] = "0ms");
                c.moveProp = g.moveProp;
                c.opacity = g.opacity;
                c.display = "none";
                a._r4.push({block: b, css: c});
                a._v4(b,
                    f);
                a._s4.push(setTimeout(function (b, d, c, e) {
                    return function () {
                        b.css("display", "block");
                        if (c) {
                            var g = {};
                            if (a._e) {
                                var f = "";
                                c.hasMove && (f += c.moveProp);
                                c.hasFade && (c.hasMove && (f += ", "), f += "opacity");
                                g[a._g + a._t1] = f;
                                g[a._g + a._u1] = c.speed + "ms";
                                g[a._g + a._v1] = c.css3Easing;
                                b.css(g);
                                setTimeout(function () {
                                    b.css(d)
                                }, 24)
                            } else setTimeout(function () {
                                b.animate(d, c.speed, c.easing)
                            }, 16)
                        }
                        delete a._s4[e]
                    }
                }(b, g, d, m), 6 >= d.delay ? 12 : d.delay))
            })
        }
    });
    $.rsModules.animatedBlocks = $.rsProto._p4
})(jQuery);
// jquery.rs.auto-height v1.0.3
(function ($) {
    $.rsModules.autoHeight = $.rsProto.autoHeight = function () {
        var slider = this;
        if (slider.options.autoHeight) {
            var slideActiveHolder;
            var slideActiveHolderHeight;
            var slideActive;
            var f = !0;
            var d = function (d) {
                slideActive = slider.slides[slider.currSlideId];
                (slideActiveHolder = slideActive.holder) && (slideActiveHolderHeight = slideActiveHolder.height()) && void 0 !== slideActiveHolderHeight && slideActiveHolderHeight > (slider.options.minAutoHeight || 30) && (slider.sliderHeight = slideActiveHolderHeight, slider._e || !d ? slider._e1.css("height", slideActiveHolderHeight) : slider._e1.stop(!0, !0).animate({height: slideActiveHolderHeight}, slider.options.transitionSpeed), slider.ev.trigger("rsAutoHeightChange", slideActiveHolderHeight), f && (slider._e && setTimeout(function () {
                    slider._e1.css(slider._g + "transition", "height " + slider.options.transitionSpeed + "ms ease-in-out")
                }, 16), f = !1))
            };
            slider.ev.on("rsMaybeSizeReady.rsAutoHeight", function (a, b) {
                slideActive === b && d()
            });
            slider.ev.on("rsAfterContentSet.rsAutoHeight", function (a, b) {
                slideActive === b && d()
            });
            slider.slider.addClass("rsAutoHeight");
            slider.ev.one("rsAfterInit", function () {
                setTimeout(function () {
                    d(false);
                    setTimeout(function () {
                        slider.slider.append('<div style="clear:both; float: none;"></div>');
                    }, 16)
                }, 16);
            });
            slider.ev.on("rsBeforeAnimStart", function () {
                d(true);
            });
            slider.ev.on("rsBeforeSizeSet", function () {
                setTimeout(function () {
                    d(false);
                }, 16);
            });
        }
    };
})(jQuery);
// jquery.rs.global-caption v1.0
(function ($) {
    $.rsModules.globalCaption = $.rsProto.globalCaption = function () {
        var a = this;
        a.options.globalCaption && (a.ev.on("rsAfterInit", function () {
            a.globalCaption = $('<div class="rsGCaption"></div>').appendTo(a.options.globalCaptionInside ? a._e1 : a.slider);
            a.globalCaption.html(a.currSlide.caption)
        }), a.ev.on("rsBeforeAnimStart", function () {
            a.globalCaption.html(a.currSlide.caption)
        }))
    };
})(jQuery);
// jquery.rs.active-class v1.0.1
(function ($) {
    $.rsModules.activeClass = $.rsProto.activeClass = function () {
        var timerId, slider = this;
        if (slider.options.addActiveClass) {
            slider.ev.on("rsOnUpdateNav", function () {
                timerId && clearTimeout(timerId);
                timerId = setTimeout(function () {
                    slider._g4 && slider._g4.removeClass("rsActiveSlide");
                    slider._r1 && slider._r1.addClass("rsActiveSlide");
                    timerId = null
                }, 50);
            });
        }
    };
})(jQuery);
// jquery.rs.visible-nearby v1.0.2
(function ($) {
    $.rsModules.visibleNearby = $.rsProto.visibleNearby = function () {
        var a = this;
        a.options.visibleNearby && a.options.visibleNearby.enabled && (a._h7 = {
            enabled: !0,
            centerArea: .6,
            center: !0,
            breakpoint: 0,
            breakpointCenterArea: .8,
            hiddenOverflow: !0,
            navigateByCenterClick: !1
        }, a.options.visibleNearby = $.extend({}, a._h7, a.options.visibleNearby), a.ev.one("rsAfterPropsSetup", function () {
            a._i7 = a._e1.css("overflow", "visible").wrap('<div class="rsVisibleNearbyWrap"></div>').parent();
            a.options.visibleNearby.hiddenOverflow || a._i7.css("overflow", "visible");
            a._o1 = a._i7
        }), a.ev.on("rsAfterSizePropSet", function () {
            var b, c = a.options.visibleNearby;
            b = c.breakpoint && a.width < c.breakpoint ? c.breakpointCenterArea : c.centerArea;
            a.isHorizontal ? (a.sliderWidth *= b, a._i7.css({
                height: a.sliderHeight,
                width: a.sliderWidth / b
            }), a._d = a.sliderWidth * (1 - b) / 2 / b) : (a.sliderHeight *= b, a._i7.css({
                height: a.sliderHeight / b,
                width: a.sliderWidth
            }), a._d = a.sliderHeight * (1 - b) / 2 / b);
            c.navigateByCenterClick || (a._q = a.isHorizontal ? a.sliderWidth : a.sliderHeight);
            c.center && a._e1.css("margin-" + (a.isHorizontal ? "left" : "top"), a._d)
        }))
    };
})(jQuery);