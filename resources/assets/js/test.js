seajs.use(["plugins", "fancybox", "globals", "comments", "singleplayer", "cache"], function (plugins, fancybox, globals, comments, $wsp, Cache) {
    function checkImage() {
    }

    !function (factory) {
        "function" == typeof define && define.amd ? define(["jquery"], factory) : factory(jQuery)
    }(function (jQuery) {
        function getScriptPath() {
            var scripts = document.getElementsByTagName("script"), path = scripts[scripts.length - 1].src.split("?")[0];
            return path.split("/").length > 0 ? path.split("/").slice(0, -1).join("/") + "/" : ""
        }

        function mplex(el, lst, fn) {
            for (var a = 0; a < lst.length; a++)fn(el, lst[a])
        }

        var domfocus = !1, mousefocus = !1, tabindexcounter = 0, ascrailcounter = 2e3, globalmaxzindex = 0, $ = jQuery, vendors = ["ms", "moz", "webkit", "o"], setAnimationFrame = window.requestAnimationFrame || !1, clearAnimationFrame = window.cancelAnimationFrame || !1;
        if (!setAnimationFrame)for (var vx in vendors) {
            var v = vendors[vx];
            setAnimationFrame || (setAnimationFrame = window[v + "RequestAnimationFrame"]), clearAnimationFrame || (clearAnimationFrame = window[v + "CancelAnimationFrame"] || window[v + "CancelRequestAnimationFrame"])
        }
        var clsMutationObserver = window.MutationObserver || window.WebKitMutationObserver || !1, _globaloptions = {
            zindex: "auto",
            cursoropacitymin: 0,
            cursoropacitymax: 1,
            cursorcolor: "#424242",
            cursorwidth: "5px",
            cursorborder: "1px solid #fff",
            cursorborderradius: "5px",
            scrollspeed: 60,
            mousescrollstep: 24,
            touchbehavior: !1,
            hwacceleration: !0,
            usetransition: !0,
            boxzoom: !1,
            dblclickzoom: !0,
            gesturezoom: !0,
            grabcursorenabled: !0,
            autohidemode: !0,
            background: "",
            iframeautoresize: !0,
            cursorminheight: 32,
            preservenativescrolling: !0,
            railoffset: !1,
            railhoffset: !1,
            bouncescroll: !0,
            spacebarenabled: !0,
            railpadding: {top: 0, right: 0, left: 0, bottom: 0},
            disableoutline: !0,
            horizrailenabled: !0,
            railalign: "right",
            railvalign: "bottom",
            enabletranslate3d: !0,
            enablemousewheel: !0,
            enablekeyboard: !0,
            smoothscroll: !0,
            sensitiverail: !0,
            enablemouselockapi: !0,
            cursorfixedheight: !1,
            directionlockdeadzone: 6,
            hidecursordelay: 400,
            nativeparentscrolling: !0,
            enablescrollonselection: !0,
            overflowx: !0,
            overflowy: !0,
            cursordragspeed: .3,
            rtlmode: "auto",
            cursordragontouch: !1,
            oneaxismousemode: "auto",
            scriptpath: getScriptPath(),
            scrollStopListener: null
        }, browserdetected = !1, getBrowserDetection = function () {
            function detectCursorGrab() {
                var lst = ["-moz-grab", "-webkit-grab", "grab"];
                (d.ischrome && !d.ischrome22 || d.isie) && (lst = []);
                for (var a = 0; a < lst.length; a++) {
                    var p = lst[a];
                    if (domtest.style.cursor = p, domtest.style.cursor == p)return p
                }
                return "url(//mail.google.com/mail/images/2/openhand.cur),n-resize"
            }

            if (browserdetected)return browserdetected;
            var domtest = document.createElement("DIV"), d = {};
            d.haspointerlock = "pointerLockElement" in document || "mozPointerLockElement" in document || "webkitPointerLockElement" in document, d.isopera = "opera" in window, d.isopera12 = d.isopera && "getUserMedia" in navigator, d.isoperamini = "[object OperaMini]" === Object.prototype.toString.call(window.operamini), d.isie = "all" in document && "attachEvent" in domtest && !d.isopera, d.isieold = d.isie && !("msInterpolationMode" in domtest.style), d.isie7 = !(!d.isie || d.isieold || "documentMode" in document && 7 != document.documentMode), d.isie8 = d.isie && "documentMode" in document && 8 == document.documentMode, d.isie9 = d.isie && "performance" in window && document.documentMode >= 9, d.isie10 = d.isie && "performance" in window && document.documentMode >= 10, d.isie9mobile = /iemobile.9/i.test(navigator.userAgent), d.isie9mobile && (d.isie9 = !1), d.isie7mobile = !d.isie9mobile && d.isie7 && /iemobile/i.test(navigator.userAgent), d.ismozilla = "MozAppearance" in domtest.style, d.iswebkit = "WebkitAppearance" in domtest.style, d.ischrome = "chrome" in window, d.ischrome22 = d.ischrome && d.haspointerlock, d.ischrome26 = d.ischrome && "transition" in domtest.style, d.cantouch = "ontouchstart" in document.documentElement || "ontouchstart" in window, d.hasmstouch = window.navigator.msPointerEnabled || !1, d.ismac = /^mac$/i.test(navigator.platform), d.isios = d.cantouch && /iphone|ipad|ipod/i.test(navigator.platform), d.isios4 = d.isios && !("seal" in Object), d.isandroid = /android/i.test(navigator.userAgent), d.trstyle = !1, d.hastransform = !1, d.hastranslate3d = !1, d.transitionstyle = !1, d.hastransition = !1, d.transitionend = !1;
            for (var check = ["transform", "msTransform", "webkitTransform", "MozTransform", "OTransform"], a = 0; a < check.length; a++)if ("undefined" != typeof domtest.style[check[a]]) {
                d.trstyle = check[a];
                break
            }
            d.hastransform = 0 != d.trstyle, d.hastransform && (domtest.style[d.trstyle] = "translate3d(1px,2px,3px)", d.hastranslate3d = /translate3d/.test(domtest.style[d.trstyle])), d.transitionstyle = !1, d.prefixstyle = "", d.transitionend = !1;
            for (var check = ["transition", "webkitTransition", "MozTransition", "OTransition", "OTransition", "msTransition", "KhtmlTransition"], prefix = ["", "-webkit-", "-moz-", "-o-", "-o", "-ms-", "-khtml-"], evs = ["transitionend", "webkitTransitionEnd", "transitionend", "otransitionend", "oTransitionEnd", "msTransitionEnd", "KhtmlTransitionEnd"], a = 0; a < check.length; a++)if (check[a] in domtest.style) {
                d.transitionstyle = check[a], d.prefixstyle = prefix[a], d.transitionend = evs[a];
                break
            }
            return d.ischrome26 && (d.prefixstyle = prefix[1]), d.hastransition = d.transitionstyle, d.cursorgrabvalue = detectCursorGrab(), d.hasmousecapture = "setCapture" in domtest, d.hasMutationObserver = clsMutationObserver !== !1, domtest = null, browserdetected = d, d
        }, NiceScrollClass = function (myopt, me) {
            function getMatrixValues() {
                var tr = self.doc.css(cap.trstyle);
                return tr && "matrix" == tr.substr(0, 6) ? tr.replace(/^.*\((.*)\)$/g, "$1").replace(/px/g, "").split(/, +/) : !1
            }

            function getZIndex() {
                var dom = self.win;
                if ("zIndex" in dom)return dom.zIndex();
                for (; dom.length > 0;) {
                    if (9 == dom[0].nodeType)return !1;
                    var zi = dom.css("zIndex");
                    if (!isNaN(zi) && 0 != zi)return parseInt(zi);
                    dom = dom.parent()
                }
                return !1
            }

            function getWidthToPixel(dom, prop, chkheight) {
                var wd = dom.css(prop), px = parseFloat(wd);
                if (isNaN(px)) {
                    px = _convertBorderWidth[wd] || 0;
                    var brd = 3 == px ? chkheight ? self.win.outerHeight() - self.win.innerHeight() : self.win.outerWidth() - self.win.innerWidth() : 1;
                    return self.isie8 && px && (px += 1), brd ? px : 0
                }
                return px
            }

            function _modernWheelEvent(dom, name, fn, bubble) {
                self._bind(dom, name, function (e) {
                    var e = e ? e : window.event, event = {
                        original: e,
                        target: e.target || e.srcElement,
                        type: "wheel",
                        deltaMode: "MozMousePixelScroll" == e.type ? 0 : 1,
                        deltaX: 0,
                        deltaZ: 0,
                        preventDefault: function () {
                            return e.preventDefault ? e.preventDefault() : e.returnValue = !1, !1
                        },
                        stopImmediatePropagation: function () {
                            e.stopImmediatePropagation ? e.stopImmediatePropagation() : e.cancelBubble = !0
                        }
                    };
                    return "mousewheel" == name ? (event.deltaY = -1 / 40 * e.wheelDelta, e.wheelDeltaX && (event.deltaX = -1 / 40 * e.wheelDeltaX)) : event.deltaY = e.detail, fn.call(dom, event)
                }, bubble)
            }

            function execScrollWheel(e, hr, chkscroll) {
                var px, py;
                if (0 == e.deltaMode ? (px = -Math.floor(e.deltaX * (self.opt.mousescrollstep / 54)), py = -Math.floor(e.deltaY * (self.opt.mousescrollstep / 54))) : 1 == e.deltaMode && (px = -Math.floor(e.deltaX * self.opt.mousescrollstep), py = -Math.floor(e.deltaY * self.opt.mousescrollstep)), hr && self.opt.oneaxismousemode && 0 == px && py && (px = py, py = 0), px && (self.scrollmom && self.scrollmom.stop(), self.lastdeltax += px, self.debounced("mousewheelx", function () {
                        var dt = self.lastdeltax;
                        self.lastdeltax = 0, self.rail.drag || self.doScrollLeftBy(dt)
                    }, 15)), py) {
                    if (self.opt.nativeparentscrolling && chkscroll && !self.ispage && !self.zoomactive)if (0 > py) {
                        if (self.getScrollTop() >= self.page.maxh)return !0
                    } else if (self.getScrollTop() <= 0)return !0;
                    self.scrollmom && self.scrollmom.stop(), self.lastdeltay += py, self.debounced("mousewheely", function () {
                        var dt = self.lastdeltay;
                        self.lastdeltay = 0, self.rail.drag || self.doScrollBy(dt)
                    }, 15)
                }
                return e.stopImmediatePropagation(), e.preventDefault()
            }

            var self = this;
            if (this.version = "3.5.6", this.name = "nicescroll", this.me = me, this.opt = {
                    doc: $("body"),
                    win: !1
                }, $.extend(this.opt, _globaloptions), this.opt.snapbackspeed = 80, myopt)for (var a in self.opt)"undefined" != typeof myopt[a] && (self.opt[a] = myopt[a]);
            this.doc = self.opt.doc, this.iddoc = this.doc && this.doc[0] ? this.doc[0].id || "" : "", this.ispage = /^BODY|HTML/.test(self.opt.win ? self.opt.win[0].nodeName : this.doc[0].nodeName), this.haswrapper = self.opt.win !== !1, this.win = self.opt.win || (this.ispage ? $(window) : this.doc), this.docscroll = this.ispage && !this.haswrapper ? $(window) : this.win, this.body = $("body"), this.viewport = !1, this.isfixed = !1, this.iframe = !1, this.isiframe = "IFRAME" == this.doc[0].nodeName && "IFRAME" == this.win[0].nodeName, this.istextarea = "TEXTAREA" == this.win[0].nodeName, this.forcescreen = !1, this.canshowonmouseevent = "scroll" != self.opt.autohidemode, this.onmousedown = !1, this.onmouseup = !1, this.onmousemove = !1, this.onmousewheel = !1, this.onkeypress = !1, this.ongesturezoom = !1, this.onclick = !1, this.onscrollstart = !1, this.onscrollend = !1, this.onscrollcancel = !1, this.onzoomin = !1, this.onzoomout = !1, this.view = !1, this.page = !1, this.scroll = {
                x: 0,
                y: 0
            }, this.scrollratio = {
                x: 0,
                y: 0
            }, this.cursorheight = 20, this.scrollvaluemax = 0, this.isrtlmode = "auto" == this.opt.rtlmode ? "rtl" == (this.win[0] == window ? this.body : this.win).css("direction") : this.opt.rtlmode === !0, this.scrollrunning = !1, this.scrollmom = !1, this.observer = !1, this.observerremover = !1;
            do this.id = "ascrail" + ascrailcounter++; while (document.getElementById(this.id));
            this.rail = !1, this.cursor = !1, this.cursorfreezed = !1, this.selectiondrag = !1, this.zoom = !1, this.zoomactive = !1, this.hasfocus = !1, this.hasmousefocus = !1, this.visibility = !0, this.locked = !1, this.hidden = !1, this.cursoractive = !0, this.wheelprevented = !1, this.overflowx = self.opt.overflowx, this.overflowy = self.opt.overflowy, this.nativescrollingarea = !1, this.checkarea = 0, this.events = [], this.saved = {}, this.delaylist = {}, this.synclist = {}, this.lastdeltax = 0, this.lastdeltay = 0, this.detected = getBrowserDetection();
            var cap = $.extend({}, this.detected);
            this.canhwscroll = cap.hastransform && self.opt.hwacceleration, this.ishwscroll = this.canhwscroll && self.haswrapper, this.istouchcapable = !1, cap.cantouch && cap.iswebkit && !cap.isios && !cap.isandroid && (this.istouchcapable = !0, cap.cantouch = !1), cap.cantouch && cap.ismozilla && !cap.isios && !cap.isandroid && (this.istouchcapable = !0, cap.cantouch = !1), self.opt.enablemouselockapi || (cap.hasmousecapture = !1, cap.haspointerlock = !1), this.delayed = function (name, fn, tm, lazy) {
                var dd = self.delaylist[name], nw = (new Date).getTime();
                return !lazy && dd && dd.tt ? !1 : (dd && dd.tt && clearTimeout(dd.tt), void(dd && dd.last + tm > nw && !dd.tt ? self.delaylist[name] = {
                    last: nw + tm,
                    tt: setTimeout(function () {
                        self && (self.delaylist[name].tt = 0, fn.call())
                    }, tm)
                } : dd && dd.tt || (self.delaylist[name] = {last: nw, tt: 0}, setTimeout(function () {
                    fn.call()
                }, 0))))
            }, this.debounced = function (name, fn, tm) {
                {
                    var dd = self.delaylist[name];
                    (new Date).getTime()
                }
                self.delaylist[name] = fn, dd || setTimeout(function () {
                    var fn = self.delaylist[name];
                    self.delaylist[name] = !1, fn.call()
                }, tm)
            };
            var _onsync = !1;
            if (this.synched = function (name, fn) {
                    function requestSync() {
                        _onsync || (setAnimationFrame(function () {
                            _onsync = !1;
                            for (name in self.synclist) {
                                var fn = self.synclist[name];
                                fn && fn.call(self), self.synclist[name] = !1
                            }
                        }), _onsync = !0)
                    }

                    return self.synclist[name] = fn, requestSync(), name
                }, this.unsynched = function (name) {
                    self.synclist[name] && (self.synclist[name] = !1)
                }, this.css = function (el, pars) {
                    for (var n in pars)self.saved.css.push([el, n, el.css(n)]), el.css(n, pars[n])
                }, this.scrollTop = function (val) {
                    return "undefined" == typeof val ? self.getScrollTop() : self.setScrollTop(val)
                }, this.scrollLeft = function (val) {
                    return "undefined" == typeof val ? self.getScrollLeft() : self.setScrollLeft(val)
                }, BezierClass = function (st, ed, spd, p1, p2, p3, p4) {
                    this.st = st, this.ed = ed, this.spd = spd, this.p1 = p1 || 0, this.p2 = p2 || 1, this.p3 = p3 || 0, this.p4 = p4 || 1, this.ts = (new Date).getTime(), this.df = this.ed - this.st
                }, BezierClass.prototype = {
                    B2: function (t) {
                        return 3 * t * t * (1 - t)
                    }, B3: function (t) {
                        return 3 * t * (1 - t) * (1 - t)
                    }, B4: function (t) {
                        return (1 - t) * (1 - t) * (1 - t)
                    }, getNow: function () {
                        var nw = (new Date).getTime(), pc = 1 - (nw - this.ts) / this.spd, bz = this.B2(pc) + this.B3(pc) + this.B4(pc);
                        return 0 > pc ? this.ed : this.st + Math.round(this.df * bz)
                    }, update: function (ed, spd) {
                        return this.st = this.getNow(), this.ed = ed, this.spd = spd, this.ts = (new Date).getTime(), this.df = this.ed - this.st, this
                    }
                }, this.ishwscroll) {
                this.doc.translate = {
                    x: 0,
                    y: 0,
                    tx: "0px",
                    ty: "0px"
                }, cap.hastranslate3d && cap.isios && this.doc.css("-webkit-backface-visibility", "hidden"), this.getScrollTop = function (last) {
                    if (!last) {
                        var mtx = getMatrixValues();
                        if (mtx)return 16 == mtx.length ? -mtx[13] : -mtx[5];
                        if (self.timerscroll && self.timerscroll.bz)return self.timerscroll.bz.getNow()
                    }
                    return self.doc.translate.y
                }, this.getScrollLeft = function (last) {
                    if (!last) {
                        var mtx = getMatrixValues();
                        if (mtx)return 16 == mtx.length ? -mtx[12] : -mtx[4];
                        if (self.timerscroll && self.timerscroll.bh)return self.timerscroll.bh.getNow()
                    }
                    return self.doc.translate.x
                }, this.notifyScrollEvent = document.createEvent ? function (el) {
                    var e = document.createEvent("UIEvents");
                    e.initUIEvent("scroll", !1, !0, window, 1), el.dispatchEvent(e)
                } : document.fireEvent ? function (el) {
                    var e = document.createEventObject();
                    el.fireEvent("onscroll"), e.cancelBubble = !0
                } : function () {
                };
                var cxscrollleft = this.isrtlmode ? 1 : -1;
                cap.hastranslate3d && self.opt.enabletranslate3d ? (this.setScrollTop = function (val, silent) {
                    self.doc.translate.y = val, self.doc.translate.ty = -1 * val + "px", self.doc.css(cap.trstyle, "translate3d(" + self.doc.translate.tx + "," + self.doc.translate.ty + ",0px)"), silent || self.notifyScrollEvent(self.win[0])
                }, this.setScrollLeft = function (val, silent) {
                    self.doc.translate.x = val, self.doc.translate.tx = val * cxscrollleft + "px", self.doc.css(cap.trstyle, "translate3d(" + self.doc.translate.tx + "," + self.doc.translate.ty + ",0px)"), silent || self.notifyScrollEvent(self.win[0])
                }) : (this.setScrollTop = function (val, silent) {
                    self.doc.translate.y = val, self.doc.translate.ty = -1 * val + "px", self.doc.css(cap.trstyle, "translate(" + self.doc.translate.tx + "," + self.doc.translate.ty + ")"), silent || self.notifyScrollEvent(self.win[0])
                }, this.setScrollLeft = function (val, silent) {
                    self.doc.translate.x = val, self.doc.translate.tx = val * cxscrollleft + "px", self.doc.css(cap.trstyle, "translate(" + self.doc.translate.tx + "," + self.doc.translate.ty + ")"), silent || self.notifyScrollEvent(self.win[0])
                })
            } else this.getScrollTop = function () {
                return self.docscroll.scrollTop()
            }, this.setScrollTop = function (val) {
                return self.docscroll.scrollTop(val)
            }, this.getScrollLeft = function () {
                return self.detected.ismozilla && self.isrtlmode ? Math.abs(self.docscroll.scrollLeft()) : self.docscroll.scrollLeft()
            }, this.setScrollLeft = function (val) {
                return self.docscroll.scrollLeft(self.detected.ismozilla && self.isrtlmode ? -val : val)
            };
            this.getTarget = function (e) {
                return e ? e.target ? e.target : e.srcElement ? e.srcElement : !1 : !1
            }, this.hasParent = function (e, id) {
                if (!e)return !1;
                for (var el = e.target || e.srcElement || e || !1; el && el.id != id;)el = el.parentNode || !1;
                return el !== !1
            };
            var _convertBorderWidth = {thin: 1, medium: 3, thick: 5};
            this.getOffset = function () {
                if (self.isfixed)return {top: parseFloat(self.win.css("top")), left: parseFloat(self.win.css("left"))};
                if (!self.viewport)return self.win.offset();
                var ww = self.win.offset(), vp = self.viewport.offset();
                return {
                    top: ww.top - vp.top + self.viewport.scrollTop(),
                    left: ww.left - vp.left + self.viewport.scrollLeft()
                }
            }, this.updateScrollBar = function (len) {
                if (self.ishwscroll)self.rail.css({height: self.win.innerHeight()}), self.railh && self.railh.css({width: self.win.innerWidth()}); else {
                    var wpos = self.getOffset(), pos = {top: wpos.top, left: wpos.left};
                    pos.top += getWidthToPixel(self.win, "border-top-width", !0);
                    {
                        (self.win.outerWidth() - self.win.innerWidth()) / 2
                    }
                    pos.left += self.rail.align ? self.win.outerWidth() - getWidthToPixel(self.win, "border-right-width") - self.rail.width : getWidthToPixel(self.win, "border-left-width");
                    var off = self.opt.railoffset;
                    if (off && (off.top && (pos.top += off.top), self.rail.align && off.left && (pos.left += off.left)), self.locked || self.rail.css({
                            top: pos.top,
                            left: pos.left,
                            height: len ? len.h : self.win.innerHeight()
                        }), self.zoom && self.zoom.css({
                            top: pos.top + 1,
                            left: 1 == self.rail.align ? pos.left - 20 : pos.left + self.rail.width + 4
                        }), self.railh && !self.locked) {
                        var pos = {top: wpos.top, left: wpos.left}, off = self.opt.railhoffset;
                        off && (off.top && (pos.top += off.top), off.left && (pos.left += off.left));
                        var y = self.railh.align ? pos.top + getWidthToPixel(self.win, "border-top-width", !0) + self.win.innerHeight() - self.railh.height : pos.top + getWidthToPixel(self.win, "border-top-width", !0), x = pos.left + getWidthToPixel(self.win, "border-left-width");
                        self.railh.css({top: y, left: x, width: self.railh.width})
                    }
                }
            }, this.doRailClick = function (e, dbl, hr) {
                var fn, pg, cur, pos;
                self.locked || (self.cancelEvent(e), dbl ? (fn = hr ? self.doScrollLeft : self.doScrollTop, cur = hr ? (e.pageX - self.railh.offset().left - self.cursorwidth / 2) * self.scrollratio.x : (e.pageY - self.rail.offset().top - self.cursorheight / 2) * self.scrollratio.y, fn(cur)) : (fn = hr ? self.doScrollLeftBy : self.doScrollBy, cur = hr ? self.scroll.x : self.scroll.y, pos = hr ? e.pageX - self.railh.offset().left : e.pageY - self.rail.offset().top, pg = hr ? self.view.w : self.view.h, fn(cur >= pos ? pg : -pg)))
            }, self.hasanimationframe = setAnimationFrame, self.hascancelanimationframe = clearAnimationFrame, self.hasanimationframe ? self.hascancelanimationframe || (clearAnimationFrame = function () {
                self.cancelAnimationFrame = !0
            }) : (setAnimationFrame = function (fn) {
                return setTimeout(fn, 15 - Math.floor(+new Date / 1e3) % 16)
            }, clearAnimationFrame = clearInterval), this.init = function () {
                function checkSelectionScroll(e) {
                    if (self.selectiondrag) {
                        if (e) {
                            var ww = self.win.outerHeight(), df = e.pageY - self.selectiondrag.top;
                            df > 0 && ww > df && (df = 0), df >= ww && (df -= ww), self.selectiondrag.df = df
                        }
                        if (0 != self.selectiondrag.df) {
                            var rt = 2 * -Math.floor(self.selectiondrag.df / 6);
                            self.doScrollBy(rt), self.debounced("doselectionscroll", function () {
                                checkSelectionScroll()
                            }, 50)
                        }
                    }
                }

                function oniframeload() {
                    self.iframexd = !1;
                    try {
                        {
                            var doc = "contentDocument" in this ? this.contentDocument : this.contentWindow.document;
                            doc.domain
                        }
                    } catch (e) {
                        self.iframexd = !0, doc = !1
                    }
                    if (self.iframexd)return "console" in window && console.log("NiceScroll error: policy restriced iframe"), !0;
                    if (self.forcescreen = !0, self.isiframe && (self.iframe = {
                            doc: $(doc),
                            html: self.doc.contents().find("html")[0],
                            body: self.doc.contents().find("body")[0]
                        }, self.getContentSize = function () {
                            return {
                                w: Math.max(self.iframe.html.scrollWidth, self.iframe.body.scrollWidth),
                                h: Math.max(self.iframe.html.scrollHeight, self.iframe.body.scrollHeight)
                            }
                        }, self.docscroll = $(self.iframe.body)), !cap.isios && self.opt.iframeautoresize && !self.isiframe) {
                        self.win.scrollTop(0), self.doc.height("");
                        var hh = Math.max(doc.getElementsByTagName("html")[0].scrollHeight, doc.body.scrollHeight);
                        self.doc.height(hh)
                    }
                    self.lazyResize(30), cap.isie7 && self.css($(self.iframe.html), {"overflow-y": "hidden"}), self.css($(self.iframe.body), {"overflow-y": "hidden"}), cap.isios && self.haswrapper && self.css($(doc.body), {"-webkit-transform": "translate3d(0,0,0)"}), "contentWindow" in this ? self.bind(this.contentWindow, "scroll", self.onscroll) : self.bind(doc, "scroll", self.onscroll), self.opt.enablemousewheel && self.bind(doc, "mousewheel", self.onmousewheel), self.opt.enablekeyboard && self.bind(doc, cap.isopera ? "keypress" : "keydown", self.onkeypress), (cap.cantouch || self.opt.touchbehavior) && (self.bind(doc, "mousedown", self.ontouchstart), self.bind(doc, "mousemove", function (e) {
                        self.ontouchmove(e, !0)
                    }), self.opt.grabcursorenabled && cap.cursorgrabvalue && self.css($(doc.body), {cursor: cap.cursorgrabvalue})), self.bind(doc, "mouseup", self.ontouchend), self.zoom && (self.opt.dblclickzoom && self.bind(doc, "dblclick", self.doZoom), self.ongesturezoom && self.bind(doc, "gestureend", self.ongesturezoom))
                }

                if (self.saved.css = [], cap.isie7mobile)return !0;
                if (cap.isoperamini)return !0;
                if (cap.hasmstouch && self.css(self.ispage ? $("html") : self.win, {"-ms-touch-action": "none"}), self.zindex = "auto", self.zindex = self.ispage || "auto" != self.opt.zindex ? self.opt.zindex : getZIndex() || "auto", self.ispage || "auto" == self.zindex || self.zindex > globalmaxzindex && (globalmaxzindex = self.zindex), self.isie && 0 == self.zindex && "auto" == self.opt.zindex && (self.zindex = "auto"), !self.ispage || !cap.cantouch && !cap.isieold && !cap.isie9mobile) {
                    var cont = self.docscroll;
                    self.ispage && (cont = self.haswrapper ? self.win : self.doc), cap.isie9mobile || self.css(cont, {"overflow-y": "hidden"}), self.ispage && cap.isie7 && ("BODY" == self.doc[0].nodeName ? self.css($("html"), {"overflow-y": "hidden"}) : "HTML" == self.doc[0].nodeName && self.css($("body"), {"overflow-y": "hidden"})), !cap.isios || self.ispage || self.haswrapper || self.css($("body"), {"-webkit-overflow-scrolling": "touch"});
                    var cursor = $(document.createElement("div"));
                    cursor.css({
                        position: "relative",
                        top: 0,
                        "float": "right",
                        width: self.opt.cursorwidth,
                        height: "0px",
                        "background-color": self.opt.cursorcolor,
                        border: self.opt.cursorborder,
                        "background-clip": "padding-box",
                        "-webkit-border-radius": self.opt.cursorborderradius,
                        "-moz-border-radius": self.opt.cursorborderradius,
                        "border-radius": self.opt.cursorborderradius
                    }), cursor.hborder = parseFloat(cursor.outerHeight() - cursor.innerHeight()), self.cursor = cursor;
                    var rail = $(document.createElement("div"));
                    rail.attr("id", self.id), rail.addClass("nicescroll-rails");
                    var v, a, kp = ["left", "right"];
                    for (var n in kp)a = kp[n], v = self.opt.railpadding[a], v ? rail.css("padding-" + a, v + "px") : self.opt.railpadding[a] = 0;
                    rail.append(cursor), rail.width = Math.max(parseFloat(self.opt.cursorwidth), cursor.outerWidth()) + self.opt.railpadding.left + self.opt.railpadding.right, rail.css({
                        width: rail.width + "px",
                        zIndex: self.zindex,
                        background: self.opt.background,
                        cursor: "default"
                    }), rail.visibility = !0, rail.scrollable = !0, rail.align = "left" == self.opt.railalign ? 0 : 1, self.rail = rail, self.rail.drag = !1;
                    var zoom = !1;
                    if (!self.opt.boxzoom || self.ispage || cap.isieold || (zoom = document.createElement("div"), self.bind(zoom, "click", self.doZoom), self.zoom = $(zoom), self.zoom.css({
                            cursor: "pointer",
                            "z-index": self.zindex,
                            backgroundImage: "url(" + self.opt.scriptpath + "zoomico.png)",
                            height: 18,
                            width: 18,
                            backgroundPosition: "0px 0px"
                        }), self.opt.dblclickzoom && self.bind(self.win, "dblclick", self.doZoom), cap.cantouch && self.opt.gesturezoom && (self.ongesturezoom = function (e) {
                            return e.scale > 1.5 && self.doZoomIn(e), e.scale < .8 && self.doZoomOut(e), self.cancelEvent(e)
                        }, self.bind(self.win, "gestureend", self.ongesturezoom))), self.railh = !1, self.opt.horizrailenabled) {
                        self.css(cont, {"overflow-x": "hidden"});
                        var cursor = $(document.createElement("div"));
                        cursor.css({
                            position: "absolute",
                            top: 0,
                            height: self.opt.cursorwidth,
                            width: "0px",
                            "background-color": self.opt.cursorcolor,
                            border: self.opt.cursorborder,
                            "background-clip": "padding-box",
                            "-webkit-border-radius": self.opt.cursorborderradius,
                            "-moz-border-radius": self.opt.cursorborderradius,
                            "border-radius": self.opt.cursorborderradius
                        }), cursor.wborder = parseFloat(cursor.outerWidth() - cursor.innerWidth()), self.cursorh = cursor;
                        var railh = $(document.createElement("div"));
                        railh.attr("id", self.id + "-hr"), railh.addClass("nicescroll-rails"), railh.height = Math.max(parseFloat(self.opt.cursorwidth), cursor.outerHeight()), railh.css({
                            height: railh.height + "px",
                            zIndex: self.zindex,
                            background: self.opt.background
                        }), railh.append(cursor), railh.visibility = !0, railh.scrollable = !0, railh.align = "top" == self.opt.railvalign ? 0 : 1, self.railh = railh, self.railh.drag = !1
                    }
                    if (self.ispage)rail.css({
                        position: "fixed",
                        top: "0px",
                        height: "100%"
                    }), rail.css(rail.align ? {right: "0px"} : {left: "0px"}), self.body.append(rail), self.railh && (railh.css({
                        position: "fixed",
                        left: "0px",
                        width: "100%"
                    }), railh.css(railh.align ? {bottom: "0px"} : {top: "0px"}), self.body.append(railh)); else {
                        if (self.ishwscroll) {
                            "static" == self.win.css("position") && self.css(self.win, {position: "relative"});
                            var bd = "HTML" == self.win[0].nodeName ? self.body : self.win;
                            self.zoom && (self.zoom.css({
                                position: "absolute",
                                top: 1,
                                right: 0,
                                "margin-right": rail.width + 4
                            }), bd.append(self.zoom)), rail.css({
                                position: "absolute",
                                top: 0
                            }), rail.css(rail.align ? {right: 0} : {left: 0}), bd.append(rail), railh && (railh.css({
                                position: "absolute",
                                left: 0,
                                bottom: 0
                            }), railh.css(railh.align ? {bottom: 0} : {top: 0}), bd.append(railh))
                        } else {
                            self.isfixed = "fixed" == self.win.css("position");
                            var rlpos = self.isfixed ? "fixed" : "absolute";
                            self.isfixed || (self.viewport = self.getViewport(self.win[0])), self.viewport && (self.body = self.viewport, 0 == /fixed|relative|absolute/.test(self.viewport.css("position")) && self.css(self.viewport, {position: "relative"})), rail.css({position: rlpos}), self.zoom && self.zoom.css({position: rlpos}), self.updateScrollBar(), self.body.append(rail), self.zoom && self.body.append(self.zoom), self.railh && (railh.css({position: rlpos}), self.body.append(railh))
                        }
                        cap.isios && self.css(self.win, {
                            "-webkit-tap-highlight-color": "rgba(0,0,0,0)",
                            "-webkit-touch-callout": "none"
                        }), cap.isie && self.opt.disableoutline && self.win.attr("hideFocus", "true"), cap.iswebkit && self.opt.disableoutline && self.win.css({outline: "none"})
                    }
                    if (self.opt.autohidemode === !1 ? (self.autohidedom = !1, self.rail.css({opacity: self.opt.cursoropacitymax}), self.railh && self.railh.css({opacity: self.opt.cursoropacitymax})) : self.opt.autohidemode === !0 || "leave" === self.opt.autohidemode ? (self.autohidedom = $().add(self.rail), cap.isie8 && (self.autohidedom = self.autohidedom.add(self.cursor)), self.railh && (self.autohidedom = self.autohidedom.add(self.railh)), self.railh && cap.isie8 && (self.autohidedom = self.autohidedom.add(self.cursorh))) : "scroll" == self.opt.autohidemode ? (self.autohidedom = $().add(self.rail), self.railh && (self.autohidedom = self.autohidedom.add(self.railh))) : "cursor" == self.opt.autohidemode ? (self.autohidedom = $().add(self.cursor), self.railh && (self.autohidedom = self.autohidedom.add(self.cursorh))) : "hidden" == self.opt.autohidemode && (self.autohidedom = !1, self.hide(), self.locked = !1), cap.isie9mobile) {
                        self.scrollmom = new ScrollMomentumClass2D(self), self.onmangotouch = function () {
                            var py = self.getScrollTop(), px = self.getScrollLeft();
                            if (py == self.scrollmom.lastscrolly && px == self.scrollmom.lastscrollx)return !0;
                            var dfy = py - self.mangotouch.sy, dfx = px - self.mangotouch.sx, df = Math.round(Math.sqrt(Math.pow(dfx, 2) + Math.pow(dfy, 2)));
                            if (0 != df) {
                                var dry = 0 > dfy ? -1 : 1, drx = 0 > dfx ? -1 : 1, tm = +new Date;
                                if (self.mangotouch.lazy && clearTimeout(self.mangotouch.lazy), tm - self.mangotouch.tm > 80 || self.mangotouch.dry != dry || self.mangotouch.drx != drx)self.scrollmom.stop(), self.scrollmom.reset(px, py), self.mangotouch.sy = py, self.mangotouch.ly = py, self.mangotouch.sx = px, self.mangotouch.lx = px, self.mangotouch.dry = dry, self.mangotouch.drx = drx, self.mangotouch.tm = tm; else {
                                    self.scrollmom.stop(), self.scrollmom.update(self.mangotouch.sx - dfx, self.mangotouch.sy - dfy);
                                    {
                                        tm - self.mangotouch.tm
                                    }
                                    self.mangotouch.tm = tm;
                                    var ds = Math.max(Math.abs(self.mangotouch.ly - py), Math.abs(self.mangotouch.lx - px));
                                    self.mangotouch.ly = py, self.mangotouch.lx = px, ds > 2 && (self.mangotouch.lazy = setTimeout(function () {
                                        self.mangotouch.lazy = !1, self.mangotouch.dry = 0, self.mangotouch.drx = 0, self.mangotouch.tm = 0, self.scrollmom.doMomentum(30)
                                    }, 100))
                                }
                            }
                        };
                        var top = self.getScrollTop(), lef = self.getScrollLeft();
                        self.mangotouch = {
                            sy: top,
                            ly: top,
                            dry: 0,
                            sx: lef,
                            lx: lef,
                            drx: 0,
                            lazy: !1,
                            tm: 0
                        }, self.bind(self.docscroll, "scroll", self.onmangotouch)
                    } else {
                        if (cap.cantouch || self.istouchcapable || self.opt.touchbehavior || cap.hasmstouch) {
                            self.scrollmom = new ScrollMomentumClass2D(self), self.ontouchstart = function (e) {
                                if (e.pointerType && 2 != e.pointerType && "touch" != e.pointerType)return !1;
                                if (self.hasmoving = !1, !self.locked) {
                                    if (cap.hasmstouch)for (var tg = e.target ? e.target : !1; tg;) {
                                        var nc = $(tg).getNiceScroll();
                                        if (nc.length > 0 && nc[0].me == self.me)break;
                                        if (nc.length > 0)return !1;
                                        if ("DIV" == tg.nodeName && tg.id == self.id)break;
                                        tg = tg.parentNode ? tg.parentNode : !1
                                    }
                                    self.cancelScroll();
                                    var tg = self.getTarget(e);
                                    if (tg) {
                                        var skp = /INPUT/i.test(tg.nodeName) && /range/i.test(tg.type);
                                        if (skp)return self.stopPropagation(e)
                                    }
                                    if (!("clientX" in e) && "changedTouches" in e && (e.clientX = e.changedTouches[0].clientX, e.clientY = e.changedTouches[0].clientY), self.forcescreen) {
                                        var le = e, e = {original: e.original ? e.original : e};
                                        e.clientX = le.screenX, e.clientY = le.screenY
                                    }
                                    if (self.rail.drag = {
                                            x: e.clientX,
                                            y: e.clientY,
                                            sx: self.scroll.x,
                                            sy: self.scroll.y,
                                            st: self.getScrollTop(),
                                            sl: self.getScrollLeft(),
                                            pt: 2,
                                            dl: !1
                                        }, self.ispage || !self.opt.directionlockdeadzone)self.rail.drag.dl = "f"; else {
                                        var view = {
                                            w: $(window).width(),
                                            h: $(window).height()
                                        }, page = {
                                            w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                                            h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                                        }, maxh = Math.max(0, page.h - view.h), maxw = Math.max(0, page.w - view.w);
                                        self.rail.drag.ck = !self.rail.scrollable && self.railh.scrollable ? maxh > 0 ? "v" : !1 : self.rail.scrollable && !self.railh.scrollable && maxw > 0 ? "h" : !1, self.rail.drag.ck || (self.rail.drag.dl = "f")
                                    }
                                    if (self.opt.touchbehavior && self.isiframe && cap.isie) {
                                        var wp = self.win.position();
                                        self.rail.drag.x += wp.left, self.rail.drag.y += wp.top
                                    }
                                    if (self.hasmoving = !1, self.lastmouseup = !1, self.scrollmom.reset(e.clientX, e.clientY), !cap.cantouch && !this.istouchcapable && !cap.hasmstouch) {
                                        var ip = tg ? /INPUT|SELECT|TEXTAREA/i.test(tg.nodeName) : !1;
                                        if (!ip)return !self.ispage && cap.hasmousecapture && tg.setCapture(), self.opt.touchbehavior ? (tg.onclick && !tg._onclick && (tg._onclick = tg.onclick, tg.onclick = function (e) {
                                            return self.hasmoving ? !1 : void tg._onclick.call(this, e)
                                        }), self.cancelEvent(e)) : self.stopPropagation(e);
                                        /SUBMIT|CANCEL|BUTTON/i.test($(tg).attr("type")) && (pc = {
                                            tg: tg,
                                            click: !1
                                        }, self.preventclick = pc)
                                    }
                                }
                            }, self.ontouchend = function (e) {
                                return e.pointerType && 2 != e.pointerType && "touch" != e.pointerType ? !1 : self.rail.drag && 2 == self.rail.drag.pt && (self.scrollmom.doMomentum(), self.rail.drag = !1, self.hasmoving && (self.lastmouseup = !0, self.hideCursor(), cap.hasmousecapture && document.releaseCapture(), !cap.cantouch)) ? self.cancelEvent(e) : void 0
                            };
                            var moveneedoffset = self.opt.touchbehavior && self.isiframe && !cap.hasmousecapture;
                            self.ontouchmove = function (e, byiframe) {
                                if (e.pointerType && 2 != e.pointerType && "touch" != e.pointerType)return !1;
                                if (self.rail.drag && 2 == self.rail.drag.pt) {
                                    if (cap.cantouch && "undefined" == typeof e.original)return !0;
                                    self.hasmoving = !0, self.preventclick && !self.preventclick.click && (self.preventclick.click = self.preventclick.tg.onclick || !1, self.preventclick.tg.onclick = self.onpreventclick);
                                    var ev = $.extend({original: e}, e);
                                    if (e = ev, "changedTouches" in e && (e.clientX = e.changedTouches[0].clientX, e.clientY = e.changedTouches[0].clientY), self.forcescreen) {
                                        var le = e, e = {original: e.original ? e.original : e};
                                        e.clientX = le.screenX, e.clientY = le.screenY
                                    }
                                    var ofx = ofy = 0;
                                    if (moveneedoffset && !byiframe) {
                                        var wp = self.win.position();
                                        ofx = -wp.left, ofy = -wp.top
                                    }
                                    var fy = e.clientY + ofy, my = fy - self.rail.drag.y, fx = e.clientX + ofx, mx = fx - self.rail.drag.x, ny = self.rail.drag.st - my;
                                    if (self.ishwscroll && self.opt.bouncescroll ? 0 > ny ? ny = Math.round(ny / 2) : ny > self.page.maxh && (ny = self.page.maxh + Math.round((ny - self.page.maxh) / 2)) : (0 > ny && (ny = 0, fy = 0), ny > self.page.maxh && (ny = self.page.maxh, fy = 0)), self.railh && self.railh.scrollable) {
                                        var nx = self.isrtlmode ? mx - self.rail.drag.sl : self.rail.drag.sl - mx;
                                        self.ishwscroll && self.opt.bouncescroll ? 0 > nx ? nx = Math.round(nx / 2) : nx > self.page.maxw && (nx = self.page.maxw + Math.round((nx - self.page.maxw) / 2)) : (0 > nx && (nx = 0, fx = 0), nx > self.page.maxw && (nx = self.page.maxw, fx = 0))
                                    }
                                    var grabbed = !1;
                                    if (self.rail.drag.dl)grabbed = !0, "v" == self.rail.drag.dl ? nx = self.rail.drag.sl : "h" == self.rail.drag.dl && (ny = self.rail.drag.st); else {
                                        var ay = Math.abs(my), ax = Math.abs(mx), dz = self.opt.directionlockdeadzone;
                                        if ("v" == self.rail.drag.ck) {
                                            if (ay > dz && .3 * ay >= ax)return self.rail.drag = !1, !0;
                                            ax > dz && (self.rail.drag.dl = "f", $("body").scrollTop($("body").scrollTop()))
                                        } else if ("h" == self.rail.drag.ck) {
                                            if (ax > dz && .3 * ax >= ay)return self.rail.drag = !1, !0;
                                            ay > dz && (self.rail.drag.dl = "f", $("body").scrollLeft($("body").scrollLeft()))
                                        }
                                    }
                                    if (self.synched("touchmove", function () {
                                            self.rail.drag && 2 == self.rail.drag.pt && (self.prepareTransition && self.prepareTransition(0), self.rail.scrollable && self.setScrollTop(ny), self.scrollmom.update(fx, fy), self.railh && self.railh.scrollable ? (self.setScrollLeft(nx), self.showCursor(ny, nx)) : self.showCursor(ny), cap.isie10 && document.selection.clear())
                                        }), cap.ischrome && self.istouchcapable && (grabbed = !1), grabbed)return self.cancelEvent(e)
                                }
                            }
                        }
                        self.onmousedown = function (e, hronly) {
                            if (!self.rail.drag || 1 == self.rail.drag.pt) {
                                if (self.locked)return self.cancelEvent(e);
                                self.cancelScroll(), self.rail.drag = {
                                    x: e.clientX,
                                    y: e.clientY,
                                    sx: self.scroll.x,
                                    sy: self.scroll.y,
                                    pt: 1,
                                    hr: !!hronly
                                };
                                var tg = self.getTarget(e);
                                return !self.ispage && cap.hasmousecapture && tg.setCapture(), self.isiframe && !cap.hasmousecapture && (self.saved.csspointerevents = self.doc.css("pointer-events"), self.css(self.doc, {"pointer-events": "none"})), self.hasmoving = !1, self.cancelEvent(e)
                            }
                        }, self.onmouseup = function (e) {
                            if (self.rail.drag) {
                                if (cap.hasmousecapture && document.releaseCapture(), self.isiframe && !cap.hasmousecapture && self.doc.css("pointer-events", self.saved.csspointerevents), 1 != self.rail.drag.pt)return;
                                return self.rail.drag = !1, self.hasmoving && self.triggerScrollEnd(), self.cancelEvent(e)
                            }
                        }, self.onmousemove = function (e) {
                            if (self.rail.drag) {
                                if (1 != self.rail.drag.pt)return;
                                if (cap.ischrome && 0 == e.which)return self.onmouseup(e);
                                if (self.cursorfreezed = !0, self.hasmoving = !0, self.rail.drag.hr) {
                                    self.scroll.x = self.rail.drag.sx + (e.clientX - self.rail.drag.x), self.scroll.x < 0 && (self.scroll.x = 0);
                                    var mw = self.scrollvaluemaxw;
                                    self.scroll.x > mw && (self.scroll.x = mw)
                                } else {
                                    self.scroll.y = self.rail.drag.sy + (e.clientY - self.rail.drag.y), self.scroll.y < 0 && (self.scroll.y = 0);
                                    var my = self.scrollvaluemax;
                                    self.scroll.y > my && (self.scroll.y = my)
                                }
                                return self.synched("mousemove", function () {
                                    self.rail.drag && 1 == self.rail.drag.pt && (self.showCursor(), self.rail.drag.hr ? self.doScrollLeft(Math.round(self.scroll.x * self.scrollratio.x), self.opt.cursordragspeed) : self.doScrollTop(Math.round(self.scroll.y * self.scrollratio.y), self.opt.cursordragspeed))
                                }), self.cancelEvent(e)
                            }
                        }, cap.cantouch || self.opt.touchbehavior ? (self.onpreventclick = function (e) {
                            return self.preventclick ? (self.preventclick.tg.onclick = self.preventclick.click, self.preventclick = !1, self.cancelEvent(e)) : void 0
                        }, self.bind(self.win, "mousedown", self.ontouchstart), self.onclick = cap.isios ? !1 : function (e) {
                            return self.lastmouseup ? (self.lastmouseup = !1, self.cancelEvent(e)) : !0
                        }, self.opt.grabcursorenabled && cap.cursorgrabvalue && (self.css(self.ispage ? self.doc : self.win, {cursor: cap.cursorgrabvalue}), self.css(self.rail, {cursor: cap.cursorgrabvalue}))) : (self.hasTextSelected = "getSelection" in document ? function () {
                            return document.getSelection().rangeCount > 0
                        } : "selection" in document ? function () {
                            return "None" != document.selection.type
                        } : function () {
                            return !1
                        }, self.onselectionstart = function () {
                            self.ispage || (self.selectiondrag = self.win.offset())
                        }, self.onselectionend = function () {
                            self.selectiondrag = !1
                        }, self.onselectiondrag = function (e) {
                            self.selectiondrag && self.hasTextSelected() && self.debounced("selectionscroll", function () {
                                checkSelectionScroll(e)
                            }, 250)
                        }), cap.hasmstouch && (self.css(self.rail, {"-ms-touch-action": "none"}), self.css(self.cursor, {"-ms-touch-action": "none"}), self.bind(self.win, "MSPointerDown", self.ontouchstart), self.bind(document, "MSPointerUp", self.ontouchend), self.bind(document, "MSPointerMove", self.ontouchmove), self.bind(self.cursor, "MSGestureHold", function (e) {
                            e.preventDefault()
                        }), self.bind(self.cursor, "contextmenu", function (e) {
                            e.preventDefault()
                        })), this.istouchcapable && (self.bind(self.win, "touchstart", self.ontouchstart), self.bind(document, "touchend", self.ontouchend), self.bind(document, "touchcancel", self.ontouchend), self.bind(document, "touchmove", self.ontouchmove)), self.bind(self.cursor, "mousedown", self.onmousedown), self.bind(self.cursor, "mouseup", self.onmouseup), self.railh && (self.bind(self.cursorh, "mousedown", function (e) {
                            self.onmousedown(e, !0)
                        }), self.bind(self.cursorh, "mouseup", self.onmouseup)), (self.opt.cursordragontouch || !cap.cantouch && !self.opt.touchbehavior) && (self.rail.css({cursor: "default"}), self.railh && self.railh.css({cursor: "default"}), self.jqbind(self.rail, "mouseenter", function () {
                            return self.win.is(":visible") ? (self.canshowonmouseevent && self.showCursor(), void(self.rail.active = !0)) : !1
                        }), self.jqbind(self.rail, "mouseleave", function () {
                            self.rail.active = !1, self.rail.drag || self.hideCursor()
                        }), self.opt.sensitiverail && (self.bind(self.rail, "click", function (e) {
                            self.doRailClick(e, !1, !1)
                        }), self.bind(self.rail, "dblclick", function (e) {
                            self.doRailClick(e, !0, !1)
                        }), self.bind(self.cursor, "click", function (e) {
                            self.cancelEvent(e)
                        }), self.bind(self.cursor, "dblclick", function (e) {
                            self.cancelEvent(e)
                        })), self.railh && (self.jqbind(self.railh, "mouseenter", function () {
                            return self.win.is(":visible") ? (self.canshowonmouseevent && self.showCursor(), void(self.rail.active = !0)) : !1
                        }), self.jqbind(self.railh, "mouseleave", function () {
                            self.rail.active = !1, self.rail.drag || self.hideCursor()
                        }), self.opt.sensitiverail && (self.bind(self.railh, "click", function (e) {
                            self.doRailClick(e, !1, !0)
                        }), self.bind(self.railh, "dblclick", function (e) {
                            self.doRailClick(e, !0, !0)
                        }), self.bind(self.cursorh, "click", function (e) {
                            self.cancelEvent(e)
                        }), self.bind(self.cursorh, "dblclick", function (e) {
                            self.cancelEvent(e)
                        })))), cap.cantouch || self.opt.touchbehavior ? (self.bind(cap.hasmousecapture ? self.win : document, "mouseup", self.ontouchend), self.bind(document, "mousemove", self.ontouchmove), self.onclick && self.bind(document, "click", self.onclick), self.opt.cursordragontouch && (self.bind(self.cursor, "mousedown", self.onmousedown), self.bind(self.cursor, "mousemove", self.onmousemove), self.cursorh && self.bind(self.cursorh, "mousedown", function (e) {
                            self.onmousedown(e, !0)
                        }), self.cursorh && self.bind(self.cursorh, "mousemove", self.onmousemove))) : (self.bind(cap.hasmousecapture ? self.win : document, "mouseup", self.onmouseup), self.bind(document, "mousemove", self.onmousemove), self.onclick && self.bind(document, "click", self.onclick), !self.ispage && self.opt.enablescrollonselection && (self.bind(self.win[0], "mousedown", self.onselectionstart), self.bind(document, "mouseup", self.onselectionend), self.bind(self.cursor, "mouseup", self.onselectionend), self.cursorh && self.bind(self.cursorh, "mouseup", self.onselectionend), self.bind(document, "mousemove", self.onselectiondrag)), self.zoom && (self.jqbind(self.zoom, "mouseenter", function () {
                            self.canshowonmouseevent && self.showCursor(), self.rail.active = !0
                        }), self.jqbind(self.zoom, "mouseleave", function () {
                            self.rail.active = !1, self.rail.drag || self.hideCursor()
                        }))), self.opt.enablemousewheel && (self.isiframe || self.bind(cap.isie && self.ispage ? document : self.win, "mousewheel", self.onmousewheel), self.bind(self.rail, "mousewheel", self.onmousewheel), self.railh && self.bind(self.railh, "mousewheel", self.onmousewheelhr)), self.ispage || cap.cantouch || /HTML|^BODY/.test(self.win[0].nodeName) || (self.win.attr("tabindex") || self.win.attr({tabindex: tabindexcounter++}), self.jqbind(self.win, "focus", function (e) {
                            domfocus = self.getTarget(e).id || !0, self.hasfocus = !0, self.canshowonmouseevent && self.noticeCursor()
                        }), self.jqbind(self.win, "blur", function () {
                            domfocus = !1, self.hasfocus = !1
                        }), self.jqbind(self.win, "mouseenter", function (e) {
                            mousefocus = self.getTarget(e).id || !0, self.hasmousefocus = !0, self.canshowonmouseevent && self.noticeCursor()
                        }), self.jqbind(self.win, "mouseleave", function () {
                            mousefocus = !1, self.hasmousefocus = !1, self.rail.drag || self.hideCursor()
                        }))
                    }
                    if (self.onkeypress = function (e) {
                            if (self.locked && 0 == self.page.maxh)return !0;
                            e = e ? e : window.e;
                            var tg = self.getTarget(e);
                            if (tg && /INPUT|TEXTAREA|SELECT|OPTION/.test(tg.nodeName)) {
                                var tp = tg.getAttribute("type") || tg.type || !1;
                                if (!tp || !/submit|button|cancel/i.tp)return !0
                            }
                            if ($(tg).attr("contenteditable"))return !0;
                            if (self.hasfocus || self.hasmousefocus && !domfocus || self.ispage && !domfocus && !mousefocus) {
                                var key = e.keyCode;
                                if (self.locked && 27 != key)return self.cancelEvent(e);
                                var ctrl = e.ctrlKey || !1, shift = e.shiftKey || !1, ret = !1;
                                switch (key) {
                                    case 38:
                                    case 63233:
                                        self.doScrollBy(72), ret = !0;
                                        break;
                                    case 40:
                                    case 63235:
                                        self.doScrollBy(-72), ret = !0;
                                        break;
                                    case 37:
                                    case 63232:
                                        self.railh && (ctrl ? self.doScrollLeft(0) : self.doScrollLeftBy(72), ret = !0);
                                        break;
                                    case 39:
                                    case 63234:
                                        self.railh && (ctrl ? self.doScrollLeft(self.page.maxw) : self.doScrollLeftBy(-72), ret = !0);
                                        break;
                                    case 33:
                                    case 63276:
                                        self.doScrollBy(self.view.h), ret = !0;
                                        break;
                                    case 34:
                                    case 63277:
                                        self.doScrollBy(-self.view.h), ret = !0;
                                        break;
                                    case 36:
                                    case 63273:
                                        self.railh && ctrl ? self.doScrollPos(0, 0) : self.doScrollTo(0), ret = !0;
                                        break;
                                    case 35:
                                    case 63275:
                                        self.railh && ctrl ? self.doScrollPos(self.page.maxw, self.page.maxh) : self.doScrollTo(self.page.maxh), ret = !0;
                                        break;
                                    case 32:
                                        self.opt.spacebarenabled && (self.doScrollBy(shift ? self.view.h : -self.view.h), ret = !0);
                                        break;
                                    case 27:
                                        self.zoomactive && (self.doZoom(), ret = !0)
                                }
                                if (ret)return self.cancelEvent(e)
                            }
                        }, self.opt.enablekeyboard && self.bind(document, cap.isopera && !cap.isopera12 ? "keypress" : "keydown", self.onkeypress), self.bind(document, "keydown", function (e) {
                            var ctrl = e.ctrlKey || !1;
                            ctrl && (self.wheelprevented = !0)
                        }), self.bind(document, "keyup", function (e) {
                            var ctrl = e.ctrlKey || !1;
                            ctrl || (self.wheelprevented = !1)
                        }), self.bind(window, "resize", self.lazyResize), self.bind(window, "orientationchange", self.lazyResize), self.bind(window, "load", self.lazyResize), cap.ischrome && !self.ispage && !self.haswrapper) {
                        var tmp = self.win.attr("style"), ww = parseFloat(self.win.css("width")) + 1;
                        self.win.css("width", ww), self.synched("chromefix", function () {
                            self.win.attr("style", tmp)
                        })
                    }
                    self.onAttributeChange = function () {
                        self.lazyResize(250)
                    }, self.ispage || self.haswrapper || (clsMutationObserver !== !1 ? (self.observer = new clsMutationObserver(function (mutations) {
                        mutations.forEach(self.onAttributeChange)
                    }), self.observer.observe(self.win[0], {
                        childList: !0,
                        characterData: !1,
                        attributes: !0,
                        subtree: !1
                    }), self.observerremover = new clsMutationObserver(function (mutations) {
                        mutations.forEach(function (mo) {
                            if (mo.removedNodes.length > 0)for (var dd in mo.removedNodes)if (mo.removedNodes[dd] == self.win[0])return self.remove()
                        })
                    }), self.observerremover.observe(self.win[0].parentNode, {
                        childList: !0,
                        characterData: !1,
                        attributes: !1,
                        subtree: !1
                    })) : (self.bind(self.win, cap.isie && !cap.isie9 ? "propertychange" : "DOMAttrModified", self.onAttributeChange), cap.isie9 && self.win[0].attachEvent("onpropertychange", self.onAttributeChange), self.bind(self.win, "DOMNodeRemoved", function (e) {
                        e.target == self.win[0] && self.remove()
                    }))), !self.ispage && self.opt.boxzoom && self.bind(window, "resize", self.resizeZoom), self.istextarea && self.bind(self.win, "mouseup", self.lazyResize), self.lazyResize(30)
                }
                "IFRAME" == this.doc[0].nodeName && (this.doc[0].readyState && "complete" == this.doc[0].readyState && setTimeout(function () {
                    oniframeload.call(self.doc[0], !1)
                }, 500), self.bind(this.doc, "load", oniframeload))
            }, this.showCursor = function (py, px) {
                self.cursortimeout && (clearTimeout(self.cursortimeout), self.cursortimeout = 0), self.rail && (self.autohidedom && (self.autohidedom.stop().css({opacity: self.opt.cursoropacitymax}), self.cursoractive = !0), self.rail.drag && 1 == self.rail.drag.pt || ("undefined" != typeof py && py !== !1 && (self.scroll.y = Math.round(1 * py / self.scrollratio.y)), "undefined" != typeof px && (self.scroll.x = Math.round(1 * px / self.scrollratio.x))), self.cursor.css({
                    height: self.cursorheight,
                    top: self.scroll.y
                }), self.cursorh && (self.cursorh.css(!self.rail.align && self.rail.visibility ? {
                    width: self.cursorwidth,
                    left: self.scroll.x + self.rail.width
                } : {
                    width: self.cursorwidth,
                    left: self.scroll.x
                }), self.cursoractive = !0), self.zoom && self.zoom.stop().css({opacity: self.opt.cursoropacitymax}))
            }, this.hideCursor = function (tm) {
                self.cursortimeout || self.rail && self.autohidedom && (self.hasmousefocus && "leave" == self.opt.autohidemode || (self.cursortimeout = setTimeout(function () {
                    self.rail.active && self.showonmouseevent || (self.autohidedom.stop().animate({opacity: self.opt.cursoropacitymin}), self.zoom && self.zoom.stop().animate({opacity: self.opt.cursoropacitymin}), self.cursoractive = !1), self.cursortimeout = 0
                }, tm || self.opt.hidecursordelay)))
            }, this.noticeCursor = function (tm, py, px) {
                self.showCursor(py, px), self.rail.active || self.hideCursor(tm)
            }, this.getContentSize = self.ispage ? function () {
                return {
                    w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                    h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                }
            } : self.haswrapper ? function () {
                return {
                    w: self.doc.outerWidth() + parseInt(self.win.css("paddingLeft")) + parseInt(self.win.css("paddingRight")),
                    h: self.doc.outerHeight() + parseInt(self.win.css("paddingTop")) + parseInt(self.win.css("paddingBottom"))
                }
            } : function () {
                return {w: self.docscroll[0].scrollWidth, h: self.docscroll[0].scrollHeight}
            }, this.onResize = function (e, page) {
                if (!self || !self.win)return !1;
                if (!self.haswrapper && !self.ispage) {
                    if ("none" == self.win.css("display"))return self.visibility && self.hideRail().hideRailHr(), !1;
                    self.hidden || self.visibility || self.showRail().showRailHr()
                }
                var premaxh = self.page.maxh, premaxw = self.page.maxw, preview = {h: self.view.h, w: self.view.w};
                if (self.view = {
                        w: self.ispage ? self.win.width() : parseInt(self.win[0].clientWidth),
                        h: self.ispage ? self.win.height() : parseInt(self.win[0].clientHeight)
                    }, self.page = page ? page : self.getContentSize(), self.page.maxh = Math.max(0, self.page.h - self.view.h), self.page.maxw = Math.max(0, self.page.w - self.view.w), self.page.maxh == premaxh && self.page.maxw == premaxw && self.view.w == preview.w) {
                    if (self.ispage)return self;
                    var pos = self.win.offset();
                    if (self.lastposition) {
                        var lst = self.lastposition;
                        if (lst.top == pos.top && lst.left == pos.left)return self
                    }
                    self.lastposition = pos
                }
                if (0 == self.page.maxh ? (self.hideRail(), self.scrollvaluemax = 0, self.scroll.y = 0, self.scrollratio.y = 0, self.cursorheight = 0, self.setScrollTop(0), self.rail.scrollable = !1) : self.rail.scrollable = !0, 0 == self.page.maxw ? (self.hideRailHr(), self.scrollvaluemaxw = 0, self.scroll.x = 0, self.scrollratio.x = 0, self.cursorwidth = 0, self.setScrollLeft(0), self.railh.scrollable = !1) : self.railh.scrollable = !0, self.locked = 0 == self.page.maxh && 0 == self.page.maxw, self.locked)return self.ispage || self.updateScrollBar(self.view), !1;
                self.hidden || self.visibility ? self.hidden || self.railh.visibility || self.showRailHr() : self.showRail().showRailHr(), self.istextarea && self.win.css("resize") && "none" != self.win.css("resize") && (self.view.h -= 20), self.cursorheight = Math.min(self.view.h, Math.round(self.view.h * (self.view.h / self.page.h))), self.cursorheight = self.opt.cursorfixedheight ? self.opt.cursorfixedheight : Math.max(self.opt.cursorminheight, self.cursorheight), self.cursorwidth = Math.min(self.view.w, Math.round(self.view.w * (self.view.w / self.page.w))), self.cursorwidth = self.opt.cursorfixedheight ? self.opt.cursorfixedheight : Math.max(self.opt.cursorminheight, self.cursorwidth), self.scrollvaluemax = self.view.h - self.cursorheight - self.cursor.hborder, self.railh && (self.railh.width = self.page.maxh > 0 ? self.view.w - self.rail.width : self.view.w, self.scrollvaluemaxw = self.railh.width - self.cursorwidth - self.cursorh.wborder), self.ispage || self.updateScrollBar(self.view), self.scrollratio = {
                    x: self.page.maxw / self.scrollvaluemaxw,
                    y: self.page.maxh / self.scrollvaluemax
                };
                var sy = self.getScrollTop();
                return sy > self.page.maxh ? self.doScrollTop(self.page.maxh) : (self.scroll.y = Math.round(self.getScrollTop() * (1 / self.scrollratio.y)), self.scroll.x = Math.round(self.getScrollLeft() * (1 / self.scrollratio.x)), self.cursoractive && self.noticeCursor()), self.scroll.y && 0 == self.getScrollTop() && self.doScrollTo(Math.floor(self.scroll.y * self.scrollratio.y)), self
            }, this.resize = self.onResize, this.lazyResize = function (tm) {
                return tm = isNaN(tm) ? 30 : tm, self.delayed("resize", self.resize, tm), self
            }, this._bind = function (el, name, fn, bubble) {
                self.events.push({
                    e: el,
                    n: name,
                    f: fn,
                    b: bubble,
                    q: !1
                }), el.addEventListener ? el.addEventListener(name, fn, bubble || !1) : el.attachEvent ? el.attachEvent("on" + name, fn) : el["on" + name] = fn
            }, this.jqbind = function (dom, name, fn) {
                self.events.push({e: dom, n: name, f: fn, q: !0}), $(dom).bind(name, fn)
            }, this.bind = function (dom, name, fn, bubble) {
                var el = "jquery" in dom ? dom[0] : dom;
                if ("mousewheel" == name)if ("onwheel" in document || document.documentMode >= 9)self._bind(el, "wheel", fn, bubble || !1); else {
                    var wname = "undefined" != typeof document.onmousewheel ? "mousewheel" : "DOMMouseScroll";
                    _modernWheelEvent(el, wname, fn, bubble || !1), "DOMMouseScroll" == wname && _modernWheelEvent(el, "MozMousePixelScroll", fn, bubble || !1)
                } else if (el.addEventListener) {
                    if (cap.cantouch && /mouseup|mousedown|mousemove/.test(name)) {
                        var tt = "mousedown" == name ? "touchstart" : "mouseup" == name ? "touchend" : "touchmove";
                        self._bind(el, tt, function (e) {
                            if (e.touches) {
                                if (e.touches.length < 2) {
                                    var ev = e.touches.length ? e.touches[0] : e;
                                    ev.original = e, fn.call(this, ev)
                                }
                            } else if (e.changedTouches) {
                                var ev = e.changedTouches[0];
                                ev.original = e, fn.call(this, ev)
                            }
                        }, bubble || !1)
                    }
                    self._bind(el, name, fn, bubble || !1), cap.cantouch && "mouseup" == name && self._bind(el, "touchcancel", fn, bubble || !1)
                } else self._bind(el, name, function (e) {
                    return e = e || window.event || !1, e && e.srcElement && (e.target = e.srcElement), "pageY" in e || (e.pageX = e.clientX + document.documentElement.scrollLeft, e.pageY = e.clientY + document.documentElement.scrollTop), fn.call(el, e) === !1 || bubble === !1 ? self.cancelEvent(e) : !0
                })
            }, this._unbind = function (el, name, fn, bub) {
                el.removeEventListener ? el.removeEventListener(name, fn, bub) : el.detachEvent ? el.detachEvent("on" + name, fn) : el["on" + name] = !1
            }, this.unbindAll = function () {
                for (var a = 0; a < self.events.length; a++) {
                    var r = self.events[a];
                    r.q ? r.e.unbind(r.n, r.f) : self._unbind(r.e, r.n, r.f, r.b)
                }
            }, this.cancelEvent = function (e) {
                var e = e.original ? e.original : e ? e : window.event || !1;
                return e ? (e.preventDefault && e.preventDefault(), e.stopPropagation && e.stopPropagation(), e.preventManipulation && e.preventManipulation(), e.cancelBubble = !0, e.cancel = !0, e.returnValue = !1, !1) : !1
            }, this.stopPropagation = function (e) {
                var e = e.original ? e.original : e ? e : window.event || !1;
                return e ? e.stopPropagation ? e.stopPropagation() : (e.cancelBubble && (e.cancelBubble = !0), !1) : !1
            }, this.showRail = function () {
                return 0 == self.page.maxh || !self.ispage && "none" == self.win.css("display") || (self.visibility = !0, self.rail.visibility = !0, self.rail.css("display", "block")), self
            }, this.showRailHr = function () {
                return self.railh ? (0 == self.page.maxw || !self.ispage && "none" == self.win.css("display") || (self.railh.visibility = !0, self.railh.css("display", "block")), self) : self
            }, this.hideRail = function () {
                return self.visibility = !1, self.rail.visibility = !1, self.rail.css("display", "none"), self
            }, this.hideRailHr = function () {
                return self.railh ? (self.railh.visibility = !1, self.railh.css("display", "none"), self) : self
            }, this.show = function () {
                return self.hidden = !1, self.locked = !1, self.showRail().showRailHr()
            }, this.hide = function () {
                return self.hidden = !0, self.locked = !0, self.hideRail().hideRailHr()
            }, this.toggle = function () {
                return self.hidden ? self.show() : self.hide()
            }, this.remove = function () {
                self.stop(), self.cursortimeout && clearTimeout(self.cursortimeout), self.doZoomOut(), self.unbindAll(), cap.isie9 && self.win[0].detachEvent("onpropertychange", self.onAttributeChange), self.observer !== !1 && self.observer.disconnect(), self.observerremover !== !1 && self.observerremover.disconnect(), self.events = null, self.cursor && self.cursor.remove(), self.cursorh && self.cursorh.remove(), self.rail && self.rail.remove(), self.railh && self.railh.remove(), self.zoom && self.zoom.remove();
                for (var a = 0; a < self.saved.css.length; a++) {
                    var d = self.saved.css[a];
                    d[0].css(d[1], "undefined" == typeof d[2] ? "" : d[2])
                }
                self.saved = !1, self.me.data("__nicescroll", "");
                var lst = $.nicescroll;
                lst.each(function (i) {
                    if (this && this.id === self.id) {
                        delete lst[i];
                        for (var b = ++i; b < lst.length; b++, i++)lst[i] = lst[b];
                        lst.length--, lst.length && delete lst[lst.length]
                    }
                });
                for (var i in self)self[i] = null, delete self[i];
                self = null
            }, this.scrollstart = function (fn) {
                return this.onscrollstart = fn, self
            }, this.scrollend = function (fn) {
                return this.onscrollend = fn, self
            }, this.scrollcancel = function (fn) {
                return this.onscrollcancel = fn, self
            }, this.zoomin = function (fn) {
                return this.onzoomin = fn, self
            }, this.zoomout = function (fn) {
                return this.onzoomout = fn, self
            }, this.isScrollable = function (e) {
                var dom = e.target ? e.target : e;
                if ("OPTION" == dom.nodeName)return !0;
                for (; dom && 1 == dom.nodeType && !/^BODY|HTML/.test(dom.nodeName);) {
                    var dd = $(dom), ov = dd.css("overflowY") || dd.css("overflowX") || dd.css("overflow") || "";
                    if (/scroll|auto/.test(ov))return dom.clientHeight != dom.scrollHeight;
                    dom = dom.parentNode ? dom.parentNode : !1
                }
                return !1
            }, this.getViewport = function (me) {
                for (var dom = me && me.parentNode ? me.parentNode : !1; dom && 1 == dom.nodeType && !/^BODY|HTML/.test(dom.nodeName);) {
                    var dd = $(dom);
                    if (/fixed|absolute/.test(dd.css("position")))return dd;
                    var ov = dd.css("overflowY") || dd.css("overflowX") || dd.css("overflow") || "";
                    if (/scroll|auto/.test(ov) && dom.clientHeight != dom.scrollHeight)return dd;
                    if (dd.getNiceScroll().length > 0)return dd;
                    dom = dom.parentNode ? dom.parentNode : !1
                }
                return dom ? $(dom) : !1
            }, this.triggerScrollEnd = function () {
                if (self.onscrollend) {
                    var px = self.getScrollLeft(), py = self.getScrollTop(), info = {
                        type: "scrollend",
                        current: {x: px, y: py},
                        end: {x: px, y: py}
                    };
                    self.onscrollend.call(self, info)
                }
            }, this.onmousewheel = function (e) {
                if (!self.wheelprevented) {
                    if (self.locked)return self.debounced("checkunlock", self.resize, 250), !0;
                    if (self.rail.drag)return self.cancelEvent(e);
                    if ("auto" == self.opt.oneaxismousemode && 0 != e.deltaX && (self.opt.oneaxismousemode = !1), self.opt.oneaxismousemode && 0 == e.deltaX && !self.rail.scrollable)return self.railh && self.railh.scrollable ? self.onmousewheelhr(e) : !0;
                    var nw = +new Date, chk = !0;
                    if (self.opt.preservenativescrolling && self.checkarea + 600 < nw && (self.nativescrollingarea = self.isScrollable(e), chk = !0), self.checkarea = nw, self.nativescrollingarea)return !0;
                    var ret = execScrollWheel(e, !1, chk);
                    return ret && (self.checkarea = 0), ret
                }
            }, this.onmousewheelhr = function (e) {
                if (!self.wheelprevented) {
                    if (self.locked || !self.railh.scrollable)return !0;
                    if (self.rail.drag)return self.cancelEvent(e);
                    var nw = +new Date, chk = !1;
                    return self.opt.preservenativescrolling && self.checkarea + 600 < nw && (self.nativescrollingarea = self.isScrollable(e), chk = !0), self.checkarea = nw, self.nativescrollingarea ? !0 : self.locked ? self.cancelEvent(e) : execScrollWheel(e, !0, chk)
                }
            }, this.stop = function () {
                return self.cancelScroll(), self.scrollmon && self.scrollmon.stop(), self.cursorfreezed = !1, self.scroll.y = Math.round(self.getScrollTop() * (1 / self.scrollratio.y)), self.noticeCursor(), self
            }, this.getTransitionSpeed = function (dif) {
                var sp = Math.round(10 * self.opt.scrollspeed), ex = Math.min(sp, Math.round(dif / 20 * self.opt.scrollspeed));
                return ex > 20 ? ex : 0
            }, self.opt.smoothscroll ? self.ishwscroll && cap.hastransition && self.opt.usetransition ? (this.prepareTransition = function (dif, istime) {
                var ex = istime ? dif > 20 ? dif : 0 : self.getTransitionSpeed(dif), trans = ex ? cap.prefixstyle + "transform " + ex + "ms ease-out" : "";
                return self.lasttransitionstyle && self.lasttransitionstyle == trans || (self.lasttransitionstyle = trans, self.doc.css(cap.transitionstyle, trans)), ex
            }, this.doScrollLeft = function (x, spd) {
                var y = self.scrollrunning ? self.newscrolly : self.getScrollTop();
                self.doScrollPos(x, y, spd)
            }, this.doScrollTop = function (y, spd) {
                var x = self.scrollrunning ? self.newscrollx : self.getScrollLeft();
                self.doScrollPos(x, y, spd)
            }, this.doScrollPos = function (x, y, spd) {
                var py = self.getScrollTop(), px = self.getScrollLeft();
                return ((self.newscrolly - py) * (y - py) < 0 || (self.newscrollx - px) * (x - px) < 0) && self.cancelScroll(), 0 == self.opt.bouncescroll && (0 > y ? y = 0 : y > self.page.maxh && (y = self.page.maxh), 0 > x ? x = 0 : x > self.page.maxw && (x = self.page.maxw)), self.scrollrunning && x == self.newscrollx && y == self.newscrolly ? !1 : (self.newscrolly = y, self.newscrollx = x, self.newscrollspeed = spd || !1, self.timer ? !1 : void(self.timer = setTimeout(function () {
                    var top = self.getScrollTop(), lft = self.getScrollLeft(), dst = {};
                    dst.x = x - lft, dst.y = y - top, dst.px = lft, dst.py = top;
                    var dd = Math.round(Math.sqrt(Math.pow(dst.x, 2) + Math.pow(dst.y, 2))), ms = self.newscrollspeed && self.newscrollspeed > 1 ? self.newscrollspeed : self.getTransitionSpeed(dd);
                    if (self.newscrollspeed && self.newscrollspeed <= 1 && (ms *= self.newscrollspeed), self.prepareTransition(ms, !0), self.timerscroll && self.timerscroll.tm && clearInterval(self.timerscroll.tm), ms > 0) {
                        if (!self.scrollrunning && self.onscrollstart) {
                            var info = {
                                type: "scrollstart",
                                current: {x: lft, y: top},
                                request: {x: x, y: y},
                                end: {x: self.newscrollx, y: self.newscrolly},
                                speed: ms
                            };
                            self.onscrollstart.call(self, info)
                        }
                        cap.transitionend ? self.scrollendtrapped || (self.scrollendtrapped = !0, self.bind(self.doc, cap.transitionend, self.onScrollTransitionEnd, !1)) : (self.scrollendtrapped && clearTimeout(self.scrollendtrapped), self.scrollendtrapped = setTimeout(self.onScrollTransitionEnd, ms));
                        var py = top, px = lft;
                        self.timerscroll = {
                            bz: new BezierClass(py, self.newscrolly, ms, 0, 0, .58, 1),
                            bh: new BezierClass(px, self.newscrollx, ms, 0, 0, .58, 1)
                        }, self.cursorfreezed || (self.timerscroll.tm = setInterval(function () {
                            self.showCursor(self.getScrollTop(), self.getScrollLeft())
                        }, 60))
                    }
                    self.synched("doScroll-set", function () {
                        self.timer = 0, self.scrollendtrapped && (self.scrollrunning = !0), self.setScrollTop(self.newscrolly), self.setScrollLeft(self.newscrollx), self.scrollendtrapped || self.onScrollTransitionEnd()
                    })
                }, 50)))
            }, this.cancelScroll = function () {
                if (!self.scrollendtrapped)return !0;
                var py = self.getScrollTop(), px = self.getScrollLeft();
                return self.scrollrunning = !1, cap.transitionend || clearTimeout(cap.transitionend), self.scrollendtrapped = !1, self._unbind(self.doc, cap.transitionend, self.onScrollTransitionEnd), self.prepareTransition(0), self.setScrollTop(py), self.railh && self.setScrollLeft(px), self.timerscroll && self.timerscroll.tm && clearInterval(self.timerscroll.tm), self.timerscroll = !1, self.cursorfreezed = !1, self.showCursor(py, px), self
            }, this.onScrollTransitionEnd = function () {
                self.scrollendtrapped && self._unbind(self.doc, cap.transitionend, self.onScrollTransitionEnd), self.scrollendtrapped = !1, self.prepareTransition(0), self.timerscroll && self.timerscroll.tm && clearInterval(self.timerscroll.tm), self.timerscroll = !1;
                var py = self.getScrollTop(), px = self.getScrollLeft();
                return self.setScrollTop(py), self.railh && self.setScrollLeft(px), self.noticeCursor(!1, py, px), self.cursorfreezed = !1, 0 > py ? py = 0 : py > self.page.maxh && (py = self.page.maxh), 0 > px ? px = 0 : px > self.page.maxw && (px = self.page.maxw), py != self.newscrolly || px != self.newscrollx ? self.doScrollPos(px, py, self.opt.snapbackspeed) : (self.onscrollend && self.scrollrunning && self.triggerScrollEnd(), self.scrollrunning = !1, void(self.opt.scrollStopListener && self.opt.scrollStopListener()))
            }) : (this.doScrollLeft = function (x, spd) {
                var y = self.scrollrunning ? self.newscrolly : self.getScrollTop();
                self.doScrollPos(x, y, spd)
            }, this.doScrollTop = function (y, spd) {
                var x = self.scrollrunning ? self.newscrollx : self.getScrollLeft();
                self.doScrollPos(x, y, spd)
            }, this.doScrollPos = function (x, y, spd) {
                function scrolling() {
                    if (self.cancelAnimationFrame)return !0;
                    if (self.scrollrunning = !0, sync = 1 - sync)return self.timer = setAnimationFrame(scrolling) || 1;
                    var done = 0, sc = sy = self.getScrollTop();
                    if (self.dst.ay) {
                        sc = self.bzscroll ? self.dst.py + self.bzscroll.getNow() * self.dst.ay : self.newscrolly;
                        var dr = sc - sy;
                        (0 > dr && sc < self.newscrolly || dr > 0 && sc > self.newscrolly) && (sc = self.newscrolly), self.setScrollTop(sc), sc == self.newscrolly && (done = 1)
                    } else done = 1;
                    var scx = sx = self.getScrollLeft();
                    if (self.dst.ax) {
                        scx = self.bzscroll ? self.dst.px + self.bzscroll.getNow() * self.dst.ax : self.newscrollx;
                        var dr = scx - sx;
                        (0 > dr && scx < self.newscrollx || dr > 0 && scx > self.newscrollx) && (scx = self.newscrollx), self.setScrollLeft(scx), scx == self.newscrollx && (done += 1)
                    } else done += 1;
                    2 == done ? (self.timer = 0, self.cursorfreezed = !1, self.bzscroll = !1, self.scrollrunning = !1, 0 > sc ? sc = 0 : sc > self.page.maxh && (sc = self.page.maxh), 0 > scx ? scx = 0 : scx > self.page.maxw && (scx = self.page.maxw), scx != self.newscrollx || sc != self.newscrolly ? self.doScrollPos(scx, sc) : self.onscrollend && self.triggerScrollEnd()) : self.timer = setAnimationFrame(scrolling) || 1
                }

                var y = "undefined" == typeof y || y === !1 ? self.getScrollTop(!0) : y;
                if (self.timer && self.newscrolly == y && self.newscrollx == x)return !0;
                self.timer && clearAnimationFrame(self.timer), self.timer = 0;
                var py = self.getScrollTop(), px = self.getScrollLeft();
                ((self.newscrolly - py) * (y - py) < 0 || (self.newscrollx - px) * (x - px) < 0) && self.cancelScroll(), self.newscrolly = y, self.newscrollx = x, self.bouncescroll && self.rail.visibility || (self.newscrolly < 0 ? self.newscrolly = 0 : self.newscrolly > self.page.maxh && (self.newscrolly = self.page.maxh)), self.bouncescroll && self.railh.visibility || (self.newscrollx < 0 ? self.newscrollx = 0 : self.newscrollx > self.page.maxw && (self.newscrollx = self.page.maxw)), self.dst = {}, self.dst.x = x - px, self.dst.y = y - py, self.dst.px = px, self.dst.py = py;
                var dst = Math.round(Math.sqrt(Math.pow(self.dst.x, 2) + Math.pow(self.dst.y, 2)));
                self.dst.ax = self.dst.x / dst, self.dst.ay = self.dst.y / dst;
                var pa = 0, pe = dst;
                0 == self.dst.x ? (pa = py, pe = y, self.dst.ay = 1, self.dst.py = 0) : 0 == self.dst.y && (pa = px, pe = x, self.dst.ax = 1, self.dst.px = 0);
                var ms = self.getTransitionSpeed(dst);
                if (spd && 1 >= spd && (ms *= spd), self.bzscroll = ms > 0 ? self.bzscroll ? self.bzscroll.update(pe, ms) : new BezierClass(pa, pe, ms, 0, 1, 0, 1) : !1, !self.timer) {
                    (py == self.page.maxh && y >= self.page.maxh || px == self.page.maxw && x >= self.page.maxw) && self.checkContentSize();
                    var sync = 1;
                    if (self.cancelAnimationFrame = !1, self.timer = 1, self.onscrollstart && !self.scrollrunning) {
                        var info = {
                            type: "scrollstart",
                            current: {x: px, y: py},
                            request: {x: x, y: y},
                            end: {x: self.newscrollx, y: self.newscrolly},
                            speed: ms
                        };
                        self.onscrollstart.call(self, info)
                    }
                    scrolling(), (py == self.page.maxh && y >= py || px == self.page.maxw && x >= px) && self.checkContentSize(), self.noticeCursor()
                }
            }, this.cancelScroll = function () {
                return self.timer && clearAnimationFrame(self.timer), self.timer = 0, self.bzscroll = !1, self.scrollrunning = !1, self
            }) : (this.doScrollLeft = function (x, spd) {
                var y = self.getScrollTop();
                self.doScrollPos(x, y, spd)
            }, this.doScrollTop = function (y, spd) {
                var x = self.getScrollLeft();
                self.doScrollPos(x, y, spd)
            }, this.doScrollPos = function (x, y) {
                var nx = x > self.page.maxw ? self.page.maxw : x;
                0 > nx && (nx = 0);
                var ny = y > self.page.maxh ? self.page.maxh : y;
                0 > ny && (ny = 0), self.synched("scroll", function () {
                    self.setScrollTop(ny), self.setScrollLeft(nx)
                })
            }, this.cancelScroll = function () {
            }), this.doScrollBy = function (stp, relative) {
                var ny = 0;
                if (relative)ny = Math.floor((self.scroll.y - stp) * self.scrollratio.y); else {
                    var sy = self.timer ? self.newscrolly : self.getScrollTop(!0);
                    ny = sy - stp
                }
                if (self.bouncescroll) {
                    var haf = Math.round(self.view.h / 2);
                    -haf > ny ? ny = -haf : ny > self.page.maxh + haf && (ny = self.page.maxh + haf)
                }
                return self.cursorfreezed = !1, py = self.getScrollTop(!0), 0 > ny && 0 >= py ? self.noticeCursor() : ny > self.page.maxh && py >= self.page.maxh ? (self.checkContentSize(), self.noticeCursor()) : void self.doScrollTop(ny)
            }, this.doScrollLeftBy = function (stp, relative) {
                var nx = 0;
                if (relative)nx = Math.floor((self.scroll.x - stp) * self.scrollratio.x); else {
                    var sx = self.timer ? self.newscrollx : self.getScrollLeft(!0);
                    nx = sx - stp
                }
                if (self.bouncescroll) {
                    var haf = Math.round(self.view.w / 2);
                    -haf > nx ? nx = -haf : nx > self.page.maxw + haf && (nx = self.page.maxw + haf)
                }
                return self.cursorfreezed = !1, px = self.getScrollLeft(!0), 0 > nx && 0 >= px ? self.noticeCursor() : nx > self.page.maxw && px >= self.page.maxw ? self.noticeCursor() : void self.doScrollLeft(nx)
            }, this.doScrollTo = function (pos, relative) {
                var ny = relative ? Math.round(pos * self.scrollratio.y) : pos;
                0 > ny ? ny = 0 : ny > self.page.maxh && (ny = self.page.maxh), self.cursorfreezed = !1, self.doScrollTop(pos)
            }, this.checkContentSize = function () {
                var pg = self.getContentSize();
                (pg.h != self.page.h || pg.w != self.page.w) && self.resize(!1, pg)
            }, self.onscroll = function () {
                self.rail.drag || self.cursorfreezed || self.synched("scroll", function () {
                    self.scroll.y = Math.round(self.getScrollTop() * (1 / self.scrollratio.y)), self.railh && (self.scroll.x = Math.round(self.getScrollLeft() * (1 / self.scrollratio.x))), self.noticeCursor()
                })
            }, self.bind(self.docscroll, "scroll", self.onscroll), this.doZoomIn = function (e) {
                if (!self.zoomactive) {
                    self.zoomactive = !0, self.zoomrestore = {style: {}};
                    var lst = ["position", "top", "left", "zIndex", "backgroundColor", "marginTop", "marginBottom", "marginLeft", "marginRight"], win = self.win[0].style;
                    for (var a in lst) {
                        var pp = lst[a];
                        self.zoomrestore.style[pp] = "undefined" != typeof win[pp] ? win[pp] : ""
                    }
                    self.zoomrestore.style.width = self.win.css("width"), self.zoomrestore.style.height = self.win.css("height"), self.zoomrestore.padding = {
                        w: self.win.outerWidth() - self.win.width(),
                        h: self.win.outerHeight() - self.win.height()
                    }, cap.isios4 && (self.zoomrestore.scrollTop = $(window).scrollTop(), $(window).scrollTop(0)), self.win.css({
                        position: cap.isios4 ? "absolute" : "fixed",
                        top: 0,
                        left: 0,
                        "z-index": globalmaxzindex + 100,
                        margin: "0px"
                    });
                    var bkg = self.win.css("backgroundColor");
                    return ("" == bkg || /transparent|rgba\(0, 0, 0, 0\)|rgba\(0,0,0,0\)/.test(bkg)) && self.win.css("backgroundColor", "#fff"), self.rail.css({"z-index": globalmaxzindex + 101}), self.zoom.css({"z-index": globalmaxzindex + 102}), self.zoom.css("backgroundPosition", "0px -18px"), self.resizeZoom(), self.onzoomin && self.onzoomin.call(self), self.cancelEvent(e)
                }
            }, this.doZoomOut = function (e) {
                return self.zoomactive ? (self.zoomactive = !1, self.win.css("margin", ""), self.win.css(self.zoomrestore.style), cap.isios4 && $(window).scrollTop(self.zoomrestore.scrollTop), self.rail.css({"z-index": self.zindex}), self.zoom.css({"z-index": self.zindex}), self.zoomrestore = !1, self.zoom.css("backgroundPosition", "0px 0px"), self.onResize(), self.onzoomout && self.onzoomout.call(self), self.cancelEvent(e)) : void 0
            }, this.doZoom = function (e) {
                return self.zoomactive ? self.doZoomOut(e) : self.doZoomIn(e)
            }, this.resizeZoom = function () {
                if (self.zoomactive) {
                    var py = self.getScrollTop();
                    self.win.css({
                        width: $(window).width() - self.zoomrestore.padding.w + "px",
                        height: $(window).height() - self.zoomrestore.padding.h + "px"
                    }), self.onResize(), self.setScrollTop(Math.min(self.page.maxh, py))
                }
            }, this.init(), $.nicescroll.push(this)
        }, ScrollMomentumClass2D = function (nc) {
            var self = this;
            this.nc = nc, this.lastx = 0, this.lasty = 0, this.speedx = 0, this.speedy = 0, this.lasttime = 0, this.steptime = 0, this.snapx = !1, this.snapy = !1, this.demulx = 0, this.demuly = 0, this.lastscrollx = -1, this.lastscrolly = -1, this.chkx = 0, this.chky = 0, this.timer = 0, this.time = function () {
                return +new Date
            }, this.reset = function (px, py) {
                self.stop();
                var now = self.time();
                self.steptime = 0, self.lasttime = now, self.speedx = 0, self.speedy = 0, self.lastx = px, self.lasty = py, self.lastscrollx = -1, self.lastscrolly = -1
            }, this.update = function (px, py) {
                var now = self.time();
                self.steptime = now - self.lasttime, self.lasttime = now;
                var dy = py - self.lasty, dx = px - self.lastx, sy = self.nc.getScrollTop(), sx = self.nc.getScrollLeft(), newy = sy + dy, newx = sx + dx;
                self.snapx = 0 > newx || newx > self.nc.page.maxw, self.snapy = 0 > newy || newy > self.nc.page.maxh, self.speedx = dx, self.speedy = dy, self.lastx = px, self.lasty = py
            }, this.stop = function () {
                self.nc.unsynched("domomentum2d"), self.timer && clearTimeout(self.timer), self.timer = 0, self.lastscrollx = -1, self.lastscrolly = -1
            }, this.doSnapy = function (nx, ny) {
                var snap = !1;
                0 > ny ? (ny = 0, snap = !0) : ny > self.nc.page.maxh && (ny = self.nc.page.maxh, snap = !0), 0 > nx ? (nx = 0, snap = !0) : nx > self.nc.page.maxw && (nx = self.nc.page.maxw, snap = !0), snap ? self.nc.doScrollPos(nx, ny, self.nc.opt.snapbackspeed) : self.nc.triggerScrollEnd()
            }, this.doMomentum = function (gp) {
                var t = self.time(), l = gp ? t + gp : self.lasttime, sl = self.nc.getScrollLeft(), st = self.nc.getScrollTop(), pageh = self.nc.page.maxh, pagew = self.nc.page.maxw;
                self.speedx = pagew > 0 ? Math.min(60, self.speedx) : 0, self.speedy = pageh > 0 ? Math.min(60, self.speedy) : 0;
                var chk = l && 60 >= t - l;
                (0 > st || st > pageh || 0 > sl || sl > pagew) && (chk = !1);
                var sy = self.speedy && chk ? self.speedy : !1, sx = self.speedx && chk ? self.speedx : !1;
                if (sy || sx) {
                    var tm = Math.max(16, self.steptime);
                    if (tm > 50) {
                        var xm = tm / 50;
                        self.speedx *= xm, self.speedy *= xm, tm = 50
                    }
                    self.demulxy = 0, self.lastscrollx = self.nc.getScrollLeft(), self.chkx = self.lastscrollx, self.lastscrolly = self.nc.getScrollTop(), self.chky = self.lastscrolly;
                    var nx = self.lastscrollx, ny = self.lastscrolly, onscroll = function () {
                        var df = self.time() - t > 600 ? .04 : .02;
                        self.speedx && (nx = Math.floor(self.lastscrollx - self.speedx * (1 - self.demulxy)), self.lastscrollx = nx, (0 > nx || nx > pagew) && (df = .1)), self.speedy && (ny = Math.floor(self.lastscrolly - self.speedy * (1 - self.demulxy)), self.lastscrolly = ny, (0 > ny || ny > pageh) && (df = .1)), self.demulxy = Math.min(1, self.demulxy + df), self.nc.synched("domomentum2d", function () {
                            if (self.speedx) {
                                var scx = self.nc.getScrollLeft();
                                scx != self.chkx && self.stop(), self.chkx = nx, self.nc.setScrollLeft(nx)
                            }
                            if (self.speedy) {
                                var scy = self.nc.getScrollTop();
                                scy != self.chky && self.stop(), self.chky = ny, self.nc.setScrollTop(ny)
                            }
                            self.timer || (self.nc.hideCursor(), self.doSnapy(nx, ny))
                        }), self.demulxy < 1 ? self.timer = setTimeout(onscroll, tm) : (self.stop(), self.nc.hideCursor(), self.doSnapy(nx, ny))
                    };
                    onscroll()
                } else self.doSnapy(self.nc.getScrollLeft(), self.nc.getScrollTop())
            }
        }, _scrollTop = jQuery.fn.scrollTop;
        jQuery.cssHooks.pageYOffset = {
            get: function (elem) {
                var nice = $.data(elem, "__nicescroll") || !1;
                return nice && nice.ishwscroll ? nice.getScrollTop() : _scrollTop.call(elem)
            }, set: function (elem, value) {
                var nice = $.data(elem, "__nicescroll") || !1;
                return nice && nice.ishwscroll ? nice.setScrollTop(parseInt(value)) : _scrollTop.call(elem, value), this
            }
        }, jQuery.fn.scrollTop = function (value) {
            if ("undefined" == typeof value) {
                var nice = this[0] ? $.data(this[0], "__nicescroll") || !1 : !1;
                return nice && nice.ishwscroll ? nice.getScrollTop() : _scrollTop.call(this)
            }
            return this.each(function () {
                var nice = $.data(this, "__nicescroll") || !1;
                nice && nice.ishwscroll ? nice.setScrollTop(parseInt(value)) : _scrollTop.call($(this), value)
            })
        };
        var _scrollLeft = jQuery.fn.scrollLeft;
        $.cssHooks.pageXOffset = {
            get: function (elem) {
                var nice = $.data(elem, "__nicescroll") || !1;
                return nice && nice.ishwscroll ? nice.getScrollLeft() : _scrollLeft.call(elem)
            }, set: function (elem, value) {
                var nice = $.data(elem, "__nicescroll") || !1;
                return nice && nice.ishwscroll ? nice.setScrollLeft(parseInt(value)) : _scrollLeft.call(elem, value), this
            }
        }, jQuery.fn.scrollLeft = function (value) {
            if ("undefined" == typeof value) {
                var nice = this[0] ? $.data(this[0], "__nicescroll") || !1 : !1;
                return nice && nice.ishwscroll ? nice.getScrollLeft() : _scrollLeft.call(this)
            }
            return this.each(function () {
                var nice = $.data(this, "__nicescroll") || !1;
                nice && nice.ishwscroll ? nice.setScrollLeft(parseInt(value)) : _scrollLeft.call($(this), value)
            })
        };
        var NiceScrollArray = function (doms) {
            var self = this;
            if (this.length = 0, this.name = "nicescrollarray", this.each = function (fn) {
                    for (var a = 0, i = 0; a < self.length; a++)fn.call(self[a], i++);
                    return self
                }, this.push = function (nice) {
                    self[self.length] = nice, self.length++
                }, this.eq = function (idx) {
                    return self[idx]
                }, doms)for (var a = 0; a < doms.length; a++) {
                var nice = $.data(doms[a], "__nicescroll") || !1;
                nice && (this[this.length] = nice, this.length++)
            }
            return this
        };
        mplex(NiceScrollArray.prototype, ["show", "hide", "toggle", "onResize", "resize", "remove", "stop", "doScrollPos"], function (e, n) {
            e[n] = function () {
                var args = arguments;
                return this.each(function () {
                    this[n].apply(this, args)
                })
            }
        }), jQuery.fn.getNiceScroll = function (index) {
            if ("undefined" == typeof index)return new NiceScrollArray(this);
            var nice = this[index] && $.data(this[index], "__nicescroll") || !1;
            return nice
        }, jQuery.extend(jQuery.expr[":"], {
            nicescroll: function (a) {
                return $.data(a, "__nicescroll") ? !0 : !1
            }
        }), $.fn.niceScroll = function (wrapper, opt) {
            "undefined" == typeof opt && ("object" != typeof wrapper || "jquery" in wrapper || (opt = wrapper, wrapper = !1));
            var ret = new NiceScrollArray;
            "undefined" == typeof opt && (opt = {}), wrapper && (opt.doc = $(wrapper), opt.win = $(this));
            var docundef = !("doc" in opt);
            return docundef || "win" in opt || (opt.win = $(this)), this.each(function () {
                var nice = $(this).data("__nicescroll") || !1;
                nice || (opt.doc = docundef ? $(this) : opt.doc, nice = new NiceScrollClass(opt, $(this)), $(this).data("__nicescroll", nice)), ret.push(nice)
            }), 1 == ret.length ? ret[0] : ret
        }, window.NiceScroll = {
            getjQuery: function () {
                return jQuery
            }
        }, $.nicescroll || ($.nicescroll = new NiceScrollArray, $.nicescroll.options = _globaloptions)
    }), homePage = {}, homePage.rootPath = "undefined" == typeof RootPath ? "" : RootPath, homePage.userSource = globals.urlPrefix.service + "user/spaceInit?jsoncallback=?", homePage.guestsSource = globals.urlPrefix.service + "relation/guests?jsoncallback=?", homePage.songHitsSource = homePage.rootPath + "/json/songHits", homePage.SongInitSource = globals.urlPrefix.service + "song/songDetailInit?jsoncallback=?", homePage.LikeSource = globals.urlPrefix.service + "song/love?jsoncallback=?", homePage.UnLikeSource = globals.urlPrefix.service + "song/unLove?jsoncallback=?", homePage.SongRecommend = globals.urlPrefix.service + "song/recommend";
    var referrer = document.referrer, collectCount = 0, like = function () {
        var hasLike = $(this).hasClass("view_player_pop_clo"), source = homePage.LikeSource;
        hasLike && (source = homePage.UnLikeSource), $.getJSON(source, {
            songType: SongType,
            songId: SongID
        }, function (res) {
            res.isLogin ? res.isSuccess ? res.data.length > 0 && ($("#func_Like").html('<b class="v_b">赞</b>(<span class="tips_yel">' + getNum(res.data[0].love) + "</span>)"), hasLike ? $("#func_Like").removeClass("view_player_pop_clo") : $("#func_Like").addClass("view_player_pop_clo")) : plugins.ksPlugin.faild("操作失败", 1e3) : plugins.login(function () {
                window.location.reload()
            })
        })
    }, getPhoto = function (url) {
        return url ? url.replace("_32_32.jpg", "_72_72.jpg") : "http://static.5sing.kugou.com/images/nan_60_60.jpg"
    }, getNum = function (num) {
        return 1e6 > num ? num : num = (num / 1e6).toFixed(0) + "百万+"
    }, updateSongInfo = function () {
        $.getJSON(homePage.SongInitSource, {
            SongID: SongID,
            UserID: OwnerUserID,
            url: referrer,
            SongType: SongType
        }, function (o) {
            if (o.status) {
                o.song.totalrq > 1e4 && o.song.totalrq < 1e8 ? o.song.totalrq = parseInt(o.song.totalrq / 1e4) + "万+" : o.song.totalrq >= 1e8 && (o.song.totalrq = parseInt(o.song.totalrq / 1e8) + "亿+"), $("#func_RQ").html('<b class="v_b">人气</b>(<span class="tips_yel">' + o.song.totalrq + "</span>)").attr({
                    href: globals.urlPrefix.home + SongType + "/rq/" + SongID,
                    target: "_blank"
                }), $("#func_Like").unbind("click").bind("click", like).html('<b class="v_b">赞</b>(<span class="tips_yel">' + getNum(o.like.songlike) + "</span>)"), 0 != o.like.userlike && $("#func_Like").addClass("view_player_pop_clo"), $("#func_Down").html('<b class="v_b"></b>下载(' + o.song.totaldown + ")").attr("target", "_blank"), "undefined" != typeof o.favorite && 1 == o.favorite ? ($("#func_delCollect").html('<b class="v_b"></b>已收藏(' + getNum(o.song.collect) + ")"), $("#func_delCollect").show(), $("#func_Collect").hide()) : ($("#func_Collect").html('<b class="v_b"></b>收藏(' + getNum(o.song.collect) + ")"), $("#func_delCollect").hide(), $("#func_Collect").show()), collectCount = o.song.collect;
                var currentUserId = o.currentUser.id, listens = o.listens, html = "", a0 = [], showLength = 8;
                o.currentUser.id > 0 && o.currentUser.id !== OwnerUserID && (html = '<li><span class="del_guess_btn" data-userid="' + o.currentUser.id + '" title="删除"></span><a href="' + globals.urlPrefix.home + o.currentUser.id + '" target="_blank" title="' + o.currentUser.nickname + '"><img src="' + getPhoto(o.currentUser.photo) + '" width="50" height="50" alt="' + o.currentUser.nickname + '" /><span class="fans_name">' + o.currentUser.nickname + '</span></a><em class="tips_gray">1秒前</em></li>', a0.push(currentUserId));
                for (var i = 0; i < listens.length && a0.length < showLength; i++) {
                    var l1 = listens[i];
                    $.inArray(l1.userid, a0) < 0 && l1.userid != currentUserId && (html += o.currentUser.id === OwnerUserID ? '<li><span class="del_guess_btn" data-userid="' + l1.userid + '" title="删除"></span><a href="' + globals.urlPrefix.home + l1.userid + '" target="_blank" title="' + l1.nickname + '"><img src="' + getPhoto(l1.photo) + '" width="50" height="50" alt="' + l1.nickname + '" /><span class="fans_name">' + l1.nickname + '</span></a><em class="tips_gray">' + l1.createtime + "</em></li>" : '<li><a href="' + globals.urlPrefix.home + l1.userid + '" target="_blank" title="' + l1.nickname + '"><img src="' + getPhoto(l1.photo) + '" width="50" height="50" alt="' + l1.nickname + '" /><span class="fans_name">' + l1.nickname + '</span></a><em class="tips_gray">' + l1.createtime + "</em></li>", a0.push(l1.userid))
                }
                html = '<div class="fans_list guest_list"><ul>' + html + "</ul></div>", $(".guest_list").html(html), o.currentUser.manager >= 1 && !o.hasRc && o.currentUser.id !== OwnerUserID && $.getJSON("http://service.5sing.kugou.com/song/CheckRecommendBySong?jsoncallback=?", {
                    SongID: SongID,
                    SongType: SongType,
                    Location: 2
                }, function (obj) {
                    if (!obj.isRecommend) {
                        var cateHtml = "";
                        o.songRecommand && o.songRecommand.length > 0 && $.each(o.songRecommand, function (i, item) {
                            cateHtml += item + "$_$"
                        }), $(".admin_fixed").show().find("a").click(function () {
                            "undefined" == typeof OwnerUserID && (OwnerUserID = UserID), $.getJSON("http://service.5sing.kugou.com/song/CheckRecommendByUser?jsoncallback=?", {
                                SongType: SongType,
                                UserID: OwnerUserID
                            }, function (obj1) {
                                obj1.isRecommend ? $.tips("该歌曲一段时间内不能被推荐") : fancybox.open({
                                    href: globals.urlPrefix.static + "release/js/modules/static/recommend.html?songid=" + SongID + "&songtype=" + SongType + "&cate=" + encodeURIComponent(cateHtml),
                                    type: "iframe",
                                    padding: 0,
                                    scrolling: "no",
                                    width: 600,
                                    title: "管理员推荐"
                                })
                            })
                        })
                    }
                })
            }
            $(".listen_right_gg").show()
        })
    };
    homePage.AddFriend = function () {
        {
            var THIS = $(this);
            arguments.callee
        }
        globals.AddFriend(OwnerUserID, function (res) {
            res.isLogin ? res.isSuccess ? THIS.html("取消关注").unbind("click").bind("click", homePage.RemoveFriend) : plugins.ksPlugin.faild(res.message, 1e3) : plugins.login(function () {
                window.location.reload()
            })
        })
    }, homePage.RemoveFriend = function () {
        var THIS = $(this);
        globals.RemoveFriend(OwnerUserID, function (res) {
            res.isSuccess ? THIS.html("+ 关注").unbind("click").bind("click", homePage.AddFriend) : plugins.ksPlugin.faild(res.message, 1e3)
        })
    }, homePage.InitTopBar = function () {
        $.getJSON(homePage.userSource, {userId: OwnerUserID}, function (res) {
            "function" == typeof homePage.InitTopBarCallback && homePage.InitTopBarCallback(res), res.isSuccess && ($("#totalfriend > a").html(res.data.totalfriend), $("#totalfans > a").html(res.data.totalfans), $("#totalrq > a").html(res.data.totalrq), globals.currentUserId = res.data.currentUserId, globals.logListenPageVisit(SongID, SongType, OwnerUserID, globals.currentUserId), res.data.currentUserId > 0 && res.data.currentUserId == OwnerUserID ? ($("#topNavSelfMenu").show(), $("#topNavOtherMenu").hide(), $(".show_owner_panel").show(), $(".show_visitor_panel").hide(), $(".musicbox_actions").addClass("list_action_normal"), $("#space_morePage_album_link").attr("href", globals.urlPrefix.member + "Writing/Album.aspx")) : ($("#topNavSelfMenu").hide(), $("#topNavOtherMenu").show(), res.data.follow > 0 ? $("#topNavOtherMenu a").eq(0).html("取消关注").unbind("click").bind("click", homePage.RemoveFriend) : $("#topNavOtherMenu a").eq(0).unbind("click").bind("click", homePage.AddFriend), $(".show_owner_panel").hide(), $(".show_visitor_panel").show()))
        })
    }, homePage.createCommentsBox = function () {
        var options = {
            title: "我要评论",
            submitText: {comment: "发表评论", reply: "回复"},
            type: SongType,
            rootId: SongID,
            ownerUserId: OwnerUserID,
            canLoadMore: !0,
            limit: 10,
            template: {titlePanel: '<div class="main_tit"><h2 class="main_tit_comment">我要评论</h2></div>'}
        };
        $(".box_msg").WSingComment(options)
    }, homePage.init = function () {
        homePage.InitTopBar()
    };
    var songRecommendList = function () {
    }, initDailyRecommend = function () {
        $(".tg_box").live({
            mouseover: function () {
                $(this).find(".c_tips > .gl_tip").show()
            }, mouseout: function () {
                $(this).find(".c_tips > .gl_tip").hide()
            }
        }), $(".view_player_tj").click(function () {
            {
                var loadingId = "recommandLoading", loadObj = null;
                $(this)
            }
            $.ajax({
                timeout: 3e4,
                url: "http://5sing.kugou.com/my/handler/canrecommand",
                type: "GET",
                cache: !1,
                data: {songType: SongType, songId: SongID},
                beforeSend: function () {
                    loadObj = $.ksPlugin.showLoading(loadingId, "加载中...")
                },
                error: function () {
                    $.ksPlugin.faild("服务器繁忙，请稍候重试")
                },
                complete: function () {
                    $.ksPlugin.hideLoading(loadingId, loadObj)
                },
                success: function (data) {
                    if ("string" == typeof data)$.login(function () {
                        globals.UserStatus(), $(".view_player_tj").click()
                    }); else switch (data.errorCode) {
                        case 0:
                            var tmp = data.recommandNum >= 7 ? "当前歌曲推荐数：" + data.recommandNum + "首，歌曲推广出现概率将小于7/" + data.recommandNum : "当前歌曲推荐数：" + data.recommandNum + "首", content = "<p><span>确认将歌曲&nbsp;&nbsp;<strong>" + data.songName + '</strong></span><span class="tg_box"><a href="###" class="c_tips rt"><i class="lt"></i><span class="gl_tip"><i class="arr"></i>' + tmp + "</span></a>明天进行推广吗？</span> </p><p><span>（参加推广将使用1张推广卡，您当前剩余" + data.hasCardNum + "张）</span></p>";
                            $.ksPlugin.confirm({content: content, yes: "确定", no: "取消"}, function () {
                                var recommandSongLoadingId = "recommandSongLoadingId", recommandSongLoadingObj = null;
                                $.ajax({
                                    timeout: 3e4,
                                    url: "http://5sing.kugou.com/my/handler/recommandSongByCard",
                                    type: "GET",
                                    cache: !1,
                                    data: {songType: SongType, songId: SongID},
                                    beforeSend: function () {
                                        recommandSongLoadingObj = $.ksPlugin.showLoading(recommandSongLoadingId, "加载中...")
                                    },
                                    error: function () {
                                        $.ksPlugin.faild("服务器繁忙，请稍候重试")
                                    },
                                    complete: function () {
                                        $.ksPlugin.hideLoading(recommandSongLoadingId, recommandSongLoadingObj)
                                    },
                                    success: function (data) {
                                        if (0 == data.errorCode) {
                                            var options = {
                                                content: "<h2>推荐成功</h2>歌曲：<strong>" + data.songName + "</strong><span>将于" + data.showTime + "”会员推荐“进行推广</span>",
                                                yes: "知道了",
                                                no: "推广记录",
                                                icon: "success",
                                                noClass: "btn_link"
                                            };
                                            $.ksPlugin.confirm(options, !1, function () {
                                                globals.open("http://5sing.kugou.com/my/wealth/myrecommand")
                                            })
                                        } else $.ksPlugin.faild("推荐失败，请重试！")
                                    }
                                })
                            });
                            break;
                        case 1002:
                            $.ksPlugin.faild("歌曲不存在");
                            break;
                        case 1010:
                            $.ksPlugin.faild("帐号状态异常，可能被锁定");
                            break;
                        case 1001:
                            $.ksPlugin.confirm({
                                yes: "立即开通VIP",
                                no: "知道了",
                                content: '<p>歌曲推荐是VIP特权，开通5sing VIP推广歌曲<br><a href="http://5sing.kugou.com/help/detail-40.html" target="_blank" class="c_vip"><i class="lt vip"></i>查看VIP特权</a></p>'
                            }, function () {
                                globals.open("http://5sing.kugou.com/my/pay/vip")
                            });
                            break;
                        case 1004:
                            $.ksPlugin.alert({
                                content: "您本月的推广卡已用完，请下个月再进行推广",
                                yesClass: "btn_no",
                                icon: "success",
                                yes: "知道了"
                            });
                            break;
                        case 1003:
                            $.ksPlugin.alert({
                                icon: "lose",
                                content: "<h2>真巧，该歌曲今天已经有人帮推广了</h2><span>无需再推荐，请明天再来呗！</span>",
                                yes: "知道了",
                                yesClass: "btn_no"
                            });
                            break;
                        default:
                            $.ksPlugin.faild("未知错误")
                    }
                }
            })
        })
    }, bindEvents = function () {
        $("#func_Collect").unbind("click").click(function () {
            globals.collectSong(SongID, SongType, function (isSuccess) {
                if (isSuccess) {
                    collectCount += 1, $("#func_delCollect").html('<b class="v_b"></b>已收藏(' + getNum(collectCount) + ")"), $("#func_delCollect").show(), $("#func_Collect").hide();
                    var pb = $(".player_box");
                    pb.find(".collect_btn[songid=" + SongID + "][songtype=" + SongType + "]").addClass("collect_sel"), pb.find(".l_collect[songid=" + SongID + "][songtype=" + SongType + "]").addClass("collect_sel"), plugins.ksPlugin.success("收藏成功", 1e3)
                }
            })
        }), $("#func_delCollect").unbind("click").click(function () {
            globals.deleteCollectSong(SongID, SongType, function (isSuccess) {
                if (isSuccess) {
                    collectCount -= 1, $("#func_Collect").html('<b class="v_b"></b>收藏(' + getNum(collectCount) + ")"), $("#func_delCollect").hide(), $("#func_Collect").show();
                    var pb = $(".player_box");
                    pb.find(".collect_btn[songid=" + SongID + "][songtype=" + SongType + "]").removeClass("collect_sel"), pb.find(".l_collect[songid=" + SongID + "][songtype=" + SongType + "]").removeClass("collect_sel"), plugins.ksPlugin.success("取消收藏成功", 1e3)
                }
            })
        });
        $(".vplay_list"), $(".songinfo_hide_value");
        $("#li_yc").unbind("click").click(function () {
            $(".v_list_info").hide(), $(".list_yc").show().getNiceScroll().resize(), $(this).addClass("v_list_tit_clo").siblings().removeClass("v_list_tit_clo")
        }), $("#li_fc").unbind("click").click(function () {
            $(".v_list_info").hide(), $(".list_fc").show().getNiceScroll().resize(), $(this).addClass("v_list_tit_clo").siblings().removeClass("v_list_tit_clo")
        }), $("#li_bz").unbind("click").click(function () {
            $(".v_list_info").hide(), $(".list_bz").show().getNiceScroll().resize(), $(this).addClass("v_list_tit_clo").siblings().removeClass("v_list_tit_clo")
        })
    }, initCache = function () {
        var cache = new Cache(function () {
            $(".playerWidget_Play").live("click", function () {
                var songInfo = $(this).attr("songinfo");
                if (songInfo) {
                    cache.set("fmPage_Add", {data: songInfo, type: 0, playNow: !0});
                    var time = parseInt(globals.cookies.get("fmPageTime"));
                    isNaN(time) && globals.open("/fm/m/")
                } else $(this).attr("href", "###")
            }), $(".playerWidget_Add").live("click", function () {
                var songInfo = $(this).attr("songinfo");
                if (songInfo) {
                    cache.set("fmPage_Add", {data: songInfo, type: 0, playNow: !1});
                    var time = parseInt(globals.cookies.get("fmPageTime"));
                    isNaN(time) && globals.open("/fm/m/")
                }
            })
        })
    }, initReport = function () {
        $(".view").delegate("div.report_btn", "click", function () {
            var selfFun = arguments.callee;
            if (globals.currentUserId <= 0)return void $.login(function () {
                globals.currentUserId = 21394771, globals.UserStatus(), selfFun()
            });
            var content = '<div class="report"><em>*</em>&nbsp;&nbsp;举报原因<br><label for="name01"><input type="radio" name="report_cate" value="非原创" />非原创</label>&nbsp;&nbsp;<label for="name02"><input type="radio" name="report_cate" value="冒充原作者" />冒充原作者</label>&nbsp;&nbsp;<label for="name03"><input type="radio" name="report_cate" value="侵权" />侵权</label>&nbsp;&nbsp;<label for="name04"><input type="radio" name="report_cate" value="其他" />其他</label></div><div class="report"><em>*</em>&nbsp;&nbsp;举报理由具体描述<br><textarea rows="4" id="report_content"></textarea></div><div class="report">联系方式(QQ/手机号码/Email)<br><input type="text" class="conten" id="report_contact" /></div>';
            $.ksPlugin.form({
                title: "举报",
                content: content,
                containerClass: " w400_box report_box"
            }, function (callback) {
                var cate = $("input[name=report_cate]:checked").val(), content = $("#report_content").val(), contact = $("#report_contact").val();
                cate ? "其他" != cate || content ? $.post("common/postReport", {
                    category: cate,
                    content: content,
                    contact: contact
                }, function (res) {
                    res.isSuccess ? $.ksPlugin.success("举报内容已提交成功") : $.ksPlugin.faild(res.msg), callback(res.isSuccess)
                }) : (callback(!1), $.ksPlugin.faild("请填写举报理由具体描述")) : (callback(!1), $.ksPlugin.faild("请选择举报原因"))
            })
        })
    }, WSUtils = {
        isArray: function (obj) {
            return "[object Array]" === Object.prototype.toString.call(obj)
        }, isEmpty: function (obj) {
            if ("string" != typeof obj && !WSUtils.isArray(obj))throw TypeError("WSUtils.isEmpty(obj): obj-->非法参数类型!");
            return !obj || obj.length < 1 ? !0 : !1
        }, replace: function (template, replaceObj) {
            if (WSUtils.isEmpty(template) || !replaceObj)return template;
            var newTemplate = template, name = null, value = null, regExp = null;
            for (name in replaceObj)value = replaceObj[name], regExp = new RegExp(name, "g"), newTemplate = newTemplate.replace(regExp, value);
            return newTemplate
        }, getQueryString: function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"), r = window.location.search.substr(1).match(reg);
            return null != r ? decodeURIComponent(r[2]) : null
        }, addZero: function (num) {
            if ("number" != typeof num)throw TypeError("WSUtils.addZero(num): num-->非法参数类型!");
            return 10 > num && num > 0 ? "0" + num : num
        }, concat4Array: function () {
            if (arguments && !(arguments.length < 1)) {
                for (var tmp = [], i = 0, len = arguments.length; len > i;)tmp = tmp.concat(arguments[i++]);
                return tmp
            }
        }, left4Array: function (array, length) {
            var newArray = array;
            if (!WSUtils.isArray(newArray))throw TypeError("WSUtils.left4Array(array): array-->非法参数类型!");
            return length = length > array.length ? array.length : length, newArray.slice(0, length)
        }, right4Array: function (array, length) {
            var newArray = array;
            if (!WSUtils.isArray(newArray))throw TypeError("WSUtils.left4Array(array): array-->非法参数类型!");
            return length = length > array.length ? array.length : length, newArray.reverse().slice(0, length).reverse()
        }, getCookie: function (key) {
            if (WSUtils.isEmpty(key))return null;
            var cookie = document.cookie.match(new RegExp("(^| )" + key + "=([^;]*)(;|$)"));
            return null != cookie ? decodeURI(cookie[2]) : null
        }, setCookie: function (cookies) {
            if (!WSUtils.isArray(cookies))throw TypeError("WSUtils：setCookie(cookies)：非法参数cookies!");
            if (!WSUtils.isEmpty(cookies))for (var i = 0, len = cookies.length; len > i;) {
                var cookie = cookies[i++];
                document.cookie = cookie.time ? cookie.key + "=" + cookie.value + ";expires=" + new Date((new Date).getTime() + cookie.time) : cookie.key + "=" + cookie.value
            }
        }, subString: function (str, len, hasDot) {
            for (var newLength = 0, newStr = "", chineseRegex = /[^\x00-\xff]/g, singleChar = "", strLength = str.replace(chineseRegex, "**").length, i = 0; strLength > i && (singleChar = str.charAt(i).toString(), null != singleChar.match(chineseRegex) ? newLength += 2 : newLength++, !(newLength > len)); i++)newStr += singleChar;
            return hasDot && strLength > len && (newStr += "..."), newStr
        }
    }, playerModule = function () {
        function _ShowOrHidePlayerListShadeLayer(isShow) {
            if (isShow) {
                var offset = $(".v_list").position(), shadeWidth = $(".v_list").outerWidth() - 2, shadeHeight = $(".v_list").height();
                $(".v_list_shade .bg").css({
                    width: shadeWidth,
                    height: shadeHeight
                }), $(".v_list_shade .content").css({
                    width: shadeWidth,
                    height: shadeHeight,
                    "line-height": shadeHeight + "px"
                }), $(".v_list_shade").css({
                    left: offset.left,
                    top: offset.top,
                    width: shadeWidth,
                    height: shadeHeight
                }).show()
            } else $(".v_list_shade").hide()
        }

        function _ShowOrHideLoadOldSongBtn(isShow) {
            $(".v_list .loading_old_more_btn").length < 1 && $(".v_list").append("<div class='player_bottom_panel' style='display:block'><button class='loading_old_more_btn'>更多较早上传</button></div>"), isShow ? $(".v_list .loading_old_more_btn").show() : $(".v_list .loading_old_more_btn").hide()
        }

        function _ShowOrHideLoadNewSongBtn(isShow) {
            $(".v_list .loading_new_more_btn").length < 1 && $(".v_list .v_list_tit").after("<div class='player_bottom_panel' style='display:block'><button class='loading_new_more_btn'>更多最新上传</button></div>"), isShow ? $(".v_list .loading_new_more_btn").show() : $(".v_list .loading_new_more_btn").hide()
        }

        function _PlayerEndedListener() {
            "single" === _playMode ? _player.play() : _playNextSong()
        }

        function _createPlayer(songObj) {
            var player = null;
            try {
                player = new $wsp.mediaElement(".player_control_panel", {
                    song: {
                        songID: songObj.songID,
                        songType: songObj.songType,
                        file: songObj.file,
                        singerID: songObj.singerID
                    },
                    stats: {enable: !0, source: 1, isSyncVolume: !0},
                    volume: {mutedClass: "vol_no_btn"},
                    ended: function () {
                        _PlayerEndedListener()
                    },
                    errorToPlay: function () {
                        setTimeout(function () {
                            $.ksPlugin.alert({content: "播放出错，请检查网络是否通畅", yes: "我知道了"})
                        }, 5e3)
                    },
                    unsupport: function (player) {
                        setTimeout(function () {
                            if (player.song) {
                                var song = player.song;
                                song.file.indexOf(".wma") > 0 ? $.ksPlugin.confirm({
                                    content: "很抱歉无法播放，安装Silverlight插件即可正常播放哦",
                                    yes: "马上安装"
                                }, function () {
                                    var vra = document.getElementById("_newWinLink");
                                    vra || (vra = document.createElement("a"), vra.target = "_blank", vra.href = "http://www.microsoft.com/getsilverlight/Get-Started/Install/Default.aspx", vra.id = "_newWinLink", document.body.appendChild(vra)), vra.click()
                                }) : $.ksPlugin.confirm({
                                    content: "很抱歉无法播放，安装flash插件即可正常播放哦",
                                    yes: "马上安装"
                                }, function () {
                                    var vra = document.getElementById("_newWinLink");
                                    vra || (vra = document.createElement("a"), vra.target = "_blank", vra.href = "http://get.adobe.com/cn/flashplayer/", vra.id = "_newWinLink", document.body.appendChild(vra)), vra.click()
                                })
                            }
                        }, 5e3)
                    }
                })
            } catch (ex) {
                throw"console" in window && !!console.log && console.log(ex), ex
            }
            return player
        }

        function _GetLoadMoreSongURL(songid, songtype, userid, isPrev) {
            var params = {
                "{=userid}": userid,
                "{=songtype}": _ConvertServerSongType(songtype),
                "{=songid}": songid,
                "{=timestamp}": (new Date).getTime(),
                "{=isPrev}": isPrev ? 1 : 0,
                "{=isNext}": isPrev ? 0 : 1
            };
            return WSUtils.replace(_apiInfo.songListBySongId, params)
        }

        function _GetLatestSongURL(songtype, userid) {
            var params = {"{=userid}": userid, "{=songtype}": _ConvertServerSongType(songtype)};
            return WSUtils.replace(_apiInfo.latestSongAPI, params)
        }

        function _ConvertServerSongType(songtype) {
            return "yc" === songtype ? 1 : "fc" === songtype ? 2 : 3
        }

        function _showSongToSongList(newSongList, songtype, isPrev) {
            if (!WSUtils.isArray(newSongList))throw TypeError("list.js：_showSongToSongList(newSongList, songtype, isPrev)：newSongList-->非法参数类型!");
            var oldSongList = $(".v_list_info.list_" + songtype + " ul li"), index = 1, len = newSongList.length;
            isPrev ? index = oldSongList.length + 1 : oldSongList.each(function (i) {
                $(this).find("a em").text(WSUtils.addZero(len + i + 1))
            });
            for (var html = [], i = 0, tmp = null; len > i; i++, index++)tmp = newSongList[i], html.push("<li>"), html.push("<a target='_blank' data-isLoaded='true' href='http://5sing.kugou.com/" + tmp.songType + "/" + tmp.id + ".html' title='" + tmp.songName + "' data-songid='" + tmp.id + "'>"), 0 < tmp.recommend ? (html.push("<strong>" + WSUtils.subString(tmp.songName, 15, "...") + "</strong>"), html.push("<b class='icon_rec s_b' title='推荐'></b>")) : html.push("<strong>" + WSUtils.subString(tmp.songName, 19, "...") + "</strong>"), html.push("<span>" + tmp.createTime + "</span>"), html.push("</a>"), html.push("<span class='vp_add' style='display:none;'>添加到播放列表</span>"), html.push("</li>");
            (!html || html.length < 1) && html.push("<li class='no_" + songtype + "_song'></li>"), isPrev ? $(".v_list_info.list_" + songtype + " ul:first").append(html.join("")) : $(".v_list_info.list_" + songtype + " ul:first").prepend(html.join("")), _ResetPlayListScroll(songtype)
        }

        function _LoadLastestSongs(userid, songtype, callback) {
            var url = _GetLatestSongURL(songtype, userid);
            $.getJSON(url, null, function (songList) {
                callback && callback(songList)
            })
        }

        function _LoadMoreSong(songid, songtype, userid, isPrev, callback) {
            var url = _GetLoadMoreSongURL(songid, songtype, userid, isPrev);
            $.ajax({
                type: "GET", url: url, dataType: "jsonp", jsonp: "jsoncallback", success: function (data) {
                    callback && callback(data)
                }
            })
        }

        function _SelectSong(songid, songtype) {
            $(".v_list_info.list_" + _songType + " ul li a").each(function (index) {
                var self = $(this);
                if (songid === parseInt(self.attr("data-songid"))) {
                    self.parent().addClass("v_list_info_clo");
                    var offsetTop = index / 10 * 410;
                    $(".v_list .list_" + songtype).getNiceScroll().doScrollPos(0, offsetTop, 0)
                }
            })
        }

        function _playNextSong() {
            var songid = _GetPlayListSongID(SongType, "next");
            return songid ? void _RedirectNewPage(songid, SongType) : void _LoadLastestSongs(OwnerUserID, SongType, function (data) {
                var songid = data[0].id;
                _RedirectNewPage(songid, SongType)
            })
        }

        function _GetPlayListSongID(songtype, index) {
            var songid = null;
            if ("first" === index)songid = $(".v_list_info.list_" + songtype + " ul li:first a").attr("data-songid"); else if ("last" === index)songid = $(".v_list_info.list_" + songtype + " ul li:last a").attr("data-songid"); else if ("next" === index) {
                var curPlaySongNode = $(".v_list_info.list_" + songtype + " ul li.v_list_info_clo"), nextPlaySongNode = curPlaySongNode.next();
                songid = nextPlaySongNode.find("a:first").attr("data-songid")
            } else if ("prev" === index) {
                {
                    var curPlaySongNode = $(".v_list_info.list_" + songtype + " ul li.v_list_info_clo");
                    curPlaySongNode.prev()
                }
                songid = nextPlaySongNode.find("a:first").attr("data-songid")
            } else {
                var songList = $(".v_list_info.list_" + songtype + " ul li");
                songList.each(function (i) {
                    i === index && (songid = $(this).find("a").attr("data-songid"))
                })
            }
            return songid
        }

        function _ResetPlayListScroll(songtype) {
            var songList = $(".v_list_info.list_" + songtype + " ul li");
            songList.length < 11 ? $(".v_list .list_" + songtype).getNiceScroll().hide() : ($(".v_list .list_" + songtype).getNiceScroll().show(), $(".v_list .list_" + songtype).getNiceScroll().resize())
        }

        function _RedirectNewPage(songid, songtype) {
            var niceScrollTop = $(".v_list .list_" + songtype).getNiceScroll()[0].getScrollTop();
            WSUtils.setCookie([{
                key: "niceScrollTop",
                value: niceScrollTop,
                time: 0
            }]), location.href = "http://5sing.kugou.com/" + songtype + "/" + songid + ".html"
        }

        function _SaveLoadingMoreBtnStatus(songtype) {
            _SongTypeAndMoreBtnMap[songtype].LoadingMoreNewSongBtn = "none" !== $(".loading_new_more_btn").css("display"), _SongTypeAndMoreBtnMap[songtype].LoadingMoreOldSongBtn = "none" !== $(".loading_old_more_btn").css("display")
        }

        function _RecoverLoadingMoreBtnStatus(songtype) {
            $(".loading_new_more_btn").toggle(_SongTypeAndMoreBtnMap[songtype].LoadingMoreNewSongBtn), $(".loading_old_more_btn").toggle(_SongTypeAndMoreBtnMap[songtype].LoadingMoreOldSongBtn)
        }

        function _PlayModuleBtnClickListener() {
            var that = $(this), singleModeBtn = that.siblings(".single_mode"), listModeBtn = that.siblings(".list_mode"), className = that.attr("class");
            -1 !== className.indexOf("single_mode") ? (_playMode = "list", that.hide(), listModeBtn.show()) : -1 !== className.indexOf("list_mode") && (_playMode = "single", that.hide(), singleModeBtn.show()), WSUtils.setCookie([{
                key: "playMode",
                value: _playMode,
                time: 31556926
            }])
        }

        function _LoadingMoreBtnClickListener() {
            var that = $(this), className = that.attr("class");
            _ShowOrHidePlayerListShadeLayer(!0);
            var isPrev = null, songid = null;
            className.indexOf("loading_old_more_btn") > -1 ? (isPrev = !0, songid = _GetPlayListSongID(_songType, "last")) : className.indexOf("loading_new_more_btn") > -1 && (isPrev = !1, songid = _GetPlayListSongID(_songType, "first")), _LoadMoreSong(songid, _songType, OwnerUserID, isPrev, function (data) {
                if (data && data.length > 0 && _showSongToSongList(data, _songType, isPrev), data.length < 10 && (isPrev ? _ShowOrHideLoadOldSongBtn(!1) : _ShowOrHideLoadNewSongBtn(!1)), data.length > 0)if (isPrev) {
                    var lenght = $(".v_list_info.list_" + _songType + " ul li").length, offsetTop = lenght / 10 * 400;
                    $(".v_list .list_" + _songType).getNiceScroll().doScrollPos(0, offsetTop, 300)
                } else $(".v_list .list_" + _songType).getNiceScroll().doScrollPos(0, 0, 300);
                _ShowOrHidePlayerListShadeLayer(!1)
            })
        }

        function _SongTypeTabClickListener() {
            var that = $(this);
            _SaveLoadingMoreBtnStatus(_songType);
            var id = that.attr("id"), songType = _songType = "li_yc" === id ? "yc" : "li_fc" === id ? "fc" : "bz";
            return that.attr("isInit") ? void _RecoverLoadingMoreBtnStatus(songType) : (_ShowOrHidePlayerListShadeLayer(!0), void _LoadLastestSongs(OwnerUserID, songType, function (newSongs) {
                _showSongToSongList(WSUtils.left4Array(newSongs, 20), songType, !1), that.attr("isInit", "true"), _ShowOrHideLoadNewSongBtn(!1), _ShowOrHideLoadOldSongBtn(newSongs.length > 20 ? !0 : !1), _ShowOrHidePlayerListShadeLayer(!1)
            }))
        }

        function _SongClickListener() {
            var songid = $(this).attr("data-songid");
            return _RedirectNewPage(songid, _songType), !1
        }

        function _initDefaultPlayList(songid, songtype, userid) {
            var curSongObj = eval("(" + globals.base64.decode(globals.ticket) + ")");
            curSongObj.id = curSongObj.songID, curSongObj.createTime = CreateTime, curSongObj.recommend = Recommend, $(".v_list_tit ul li").removeClass("v_list_tit_clo"), $("#li_" + songtype).addClass("v_list_tit_clo").attr("isInit", "true"), $(".v_list .v_list_info").hide(), $(".v_list .v_list_info.list_" + songtype).show(), $(".player_control_panel .song_title").text(curSongObj.songName), $(".v_list .list_" + _songType).css('position', 'staic'), $(".v_list").addClass('p_rel'), $.when($.ajax({
                type: "GET",
                dataType: "jsonp",
                jsonp: "jsoncallback",
                url: _GetLoadMoreSongURL(songid, songtype, userid, !1)
            }), $.ajax({
                type: "GET",
                dataType: "jsonp",
                jsonp: "jsoncallback",
                url: _GetLoadMoreSongURL(songid, songtype, userid, !0)
            })).then(function (res1, res2) {
                if (res1[0].length < 10 && res2[0].length < 10) {
                    var songList = WSUtils.concat4Array(res1[0], [curSongObj], res2[0]);
                    $(".v_list_info.list_" + songtype + " ul").html(""), _showSongToSongList(WSUtils.left4Array(songList, 20), songtype, !1), _ShowOrHideLoadNewSongBtn(!1), _ShowOrHideLoadOldSongBtn(!1), _SelectSong(curSongObj.id, curSongObj.songType), _player = _createPlayer(curSongObj)
                }
                if (res1[0].length >= 10 && res2[0].length >= 10) {
                    var songList = WSUtils.concat4Array(res1[0], [curSongObj], res2[0]);
                    $(".v_list_info.list_" + songtype + " ul").html(""), _showSongToSongList(WSUtils.left4Array(songList, 20), songtype, !1), _ShowOrHideLoadNewSongBtn(!0), _ShowOrHideLoadOldSongBtn(!0), _SelectSong(curSongObj.id, curSongObj.songType), _player = _createPlayer(curSongObj)
                }
                if (res1[0].length >= 10 && res2[0].length < 10) {
                    var songid = res1[0][0].id;
                    $.when($.ajax({
                        type: "GET",
                        dataType: "jsonp",
                        jsonp: "jsoncallback",
                        url: _GetLoadMoreSongURL(songid, songtype, userid, !1)
                    })).then(function (res3) {
                        var songList = WSUtils.concat4Array(res3, res1[0], [curSongObj], res2[0]);
                        $(".v_list_info.list_" + songtype + " ul").html(""), _showSongToSongList(WSUtils.right4Array(songList, 20), songtype, !1), _ShowOrHideLoadNewSongBtn(songList.length > 20 ? !0 : !1), _ShowOrHideLoadOldSongBtn(!1), _SelectSong(curSongObj.id, curSongObj.songType), _player = _createPlayer(curSongObj)
                    })
                }
                if (res1[0].length < 10 && res2[0].length >= 10) {
                    var songid = res2[0][res2[0].length - 1].id;
                    $.when($.ajax({
                        type: "GET",
                        dataType: "jsonp",
                        jsonp: "jsoncallback",
                        url: _GetLoadMoreSongURL(songid, songtype, userid, !0)
                    })).then(function (res3) {
                        var songList = WSUtils.concat4Array(res1[0], [curSongObj], res2[0], res3);
                        $(".v_list_info.list_" + songtype + " ul").html(""), _showSongToSongList(WSUtils.left4Array(songList, 20), songtype, !1), _ShowOrHideLoadNewSongBtn(!1), _ShowOrHideLoadOldSongBtn(songList.length > 20 ? !0 : !1), _SelectSong(curSongObj.id, curSongObj.songType), _player = _createPlayer(curSongObj)
                    })
                }
            })
        }

        var _apiInfo = {
            latestSongAPI: "http://service.5sing.kugou.com/song/lastestSongs?jsoncallback=?&songKind={=songtype}&userId={=userid}&_={=timestamp}",
            songListBySongId: "http://service.5sing.kugou.com/song/songListBySongId?jsoncallback=?&songId={=songid}&songKind={=songtype}&userId={=userid}&isPrev={=isPrev}&isNext={=isNext}&_={=timestamp}"
        }, _playMode = null, _songType = null, _player = null, _SongTypeAndMoreBtnMap = {
            yc: {
                LoadingMoreNewSongBtn: !1,
                LoadingMoreOldSongBtn: !1
            },
            fc: {LoadingMoreNewSongBtn: !1, LoadingMoreOldSongBtn: !1},
            bz: {LoadingMoreNewSongBtn: !1, LoadingMoreOldSongBtn: !1}
        };
        return {
            init: function () {
                $(".player_control_panel").delegate(".play_mode_btn", "click", _PlayModuleBtnClickListener), $(".v_list").delegate(".loading_old_more_btn,.loading_new_more_btn", "click", _LoadingMoreBtnClickListener), $(".v_list_tit").delegate("#li_yc,#li_fc,#li_bz", "click", _SongTypeTabClickListener), $(".v_list_info").delegate("a[data-songid]", "click", _SongClickListener), _playMode = WSUtils.getCookie("playMode") ? WSUtils.getCookie("playMode") : "list", _songType = SongType, "list" === _playMode ? ($(".play_mode_btn.list_mode").show(), $(".play_mode_btn.single_mode").hide()) : ($(".play_mode_btn.list_mode").hide(), $(".play_mode_btn.single_mode").show()), $(".v_list .list_yc").niceScroll(".v_list .list_yc ul", {
                    cursorcolor: "#e3e3e3",
                    background: "#ffffff",
                    cursorwidth: "6px",
                    autohidemode: !1
                }), $(".v_list .list_fc").niceScroll(".v_list .list_fc ul", {
                    cursorcolor: "#e3e3e3",
                    background: "#ffffff",
                    cursorwidth: "6px",
                    autohidemode: !1
                }), $(".v_list .list_bz").niceScroll(".v_list .list_bz ul", {
                    cursorcolor: "#e3e3e3",
                    background: "#ffffff",
                    cursorwidth: "6px",
                    autohidemode: !1
                }), _initDefaultPlayList(SongID, SongType, OwnerUserID), $(".player_bottom_panel").remove()
            }
        }
    }();
    playerModule.init();
    var guessModule = function () {
        function _reloadGuess(songid, songtype, userid, flag) {
            $.getJSON(homePage.SongInitSource, {
                SongID: songid,
                UserID: userid,
                url: referrer,
                SongType: songtype
            }, function (o) {
                if (o.status) {
                    o.song.totalrq > 1e4 && o.song.totalrq < 1e8 ? o.song.totalrq = parseInt(o.song.totalrq / 1e4) + "万+" : o.song.totalrq >= 1e8 && (o.song.totalrq = parseInt(o.song.totalrq / 1e8) + "亿+"), $("#func_RQ").html('<b class="v_b">人气</b>(<span class="tips_yel">' + o.song.totalrq + "</span>)").attr({
                        href: globals.urlPrefix.home + songtype + "/rq/" + songid,
                        target: "_blank"
                    }), $("#func_Like").unbind("click").bind("click", like).html('<b class="v_b">赞</b>(<span class="tips_yel">' + getNum(o.like.songlike) + "</span>)"), 0 != o.like.userlike && $("#func_Like").addClass("view_player_pop_clo"), $("#func_Down").html('<b class="v_b"></b>下载(' + o.song.totaldown + ")").attr("target", "_blank"), "undefined" != typeof o.favorite && 1 == o.favorite ? ($("#func_delCollect").html('<b class="v_b"></b>已收藏(' + getNum(o.song.collect) + ")"), $("#func_delCollect").show(), $("#func_Collect").hide()) : ($("#func_Collect").html('<b class="v_b"></b>收藏(' + getNum(o.song.collect) + ")"), $("#func_delCollect").hide(), $("#func_Collect").show()), collectCount = o.song.collect;
                    var currentUserId = o.currentUser.id, listens = o.listens, html = "", a0 = [], i = 0, showLength = 8;
                    for (currentUserId > 0 && currentUserId !== OwnerUserID && flag && (html = '<li><span class="del_guess_btn" data-userid="' + o.currentUser.id + '" title="删除"></span><a href="' + globals.urlPrefix.home + o.currentUser.id + '" target="_blank" title="' + o.currentUser.nickname + '"><img src="' + getPhoto(o.currentUser.photo) + '" width="50" height="50" alt="' + o.currentUser.nickname + '" /><span class="fans_name">' + o.currentUser.nickname + '</span></a><em class="tips_gray">1秒前</em></li>', a0.push(currentUserId)); i < listens.length && a0.length < showLength; i++) {
                        var l1 = listens[i];
                        $.inArray(l1.userid, a0) < 0 && l1.userid != currentUserId && (html += currentUserId === OwnerUserID ? '<li><span class="del_guess_btn" data-userid="' + l1.userid + '" title="删除"></span><a href="' + globals.urlPrefix.home + l1.userid + '" target="_blank" title="' + l1.nickname + '"><img src="' + getPhoto(l1.photo) + '" width="50" height="50" alt="' + l1.nickname + '" /><span class="fans_name">' + l1.nickname + '</span></a><em class="tips_gray">' + l1.createtime + "</em></li>" : '<li><a href="' + globals.urlPrefix.home + l1.userid + '" target="_blank" title="' + l1.nickname + '"><img src="' + getPhoto(l1.photo) + '" width="50" height="50" alt="' + l1.nickname + '" /><span class="fans_name">' + l1.nickname + '</span></a><em class="tips_gray">' + l1.createtime + "</em></li>", a0.push(l1.userid))
                    }
                    html = '<div class="fans_list guest_list"><ul>' + html + "</ul></div>", _geussContaner.html(html)
                }
            })
        }

        function _delGuess(songid, songtype, userid, callback) {
            var params = {SongID: songid, SongType: songtype, UserID: userid}, url = "delMyListen";
            $.ajax({url: url, data: params, contentType: "application/json", dataType: "json", success: callback})
        }

        var _geussContaner = $(".guest_list");
        $(".fans_list.guest_list").delegate("li", "mouseenter mouseleave", function (event) {
            var delBtn = $(this).find(".del_guess_btn"), eventType = event.type;
            "mouseenter" === eventType ? delBtn.show() : "mouseleave" === eventType && delBtn.hide()
        }), $(".del_guess_btn").live("click", "", function () {
            var userid = $(this).attr("data-userid");
            $(this).parent().remove(), _delGuess(SongID, SongType, userid, function (res) {
                res.status && _reloadGuess(SongID, SongType, OwnerUserID, !1)
            })
        })
    }(), spreadModule = function () {
        function _ShowSpreadContent(res) {
            var songLiHtml = "", ids = "";
            res && res.songList && res.songList.length > 0 ? $.each(res.songList, function (i, item) {
                ids += item.songType + "$" + item.songId + "$", songLiHtml += '<li class="c_wap"><a target="_blank" href="http://5sing.kugou.com/' + item.NewUserID + '" class="lt head"><img src="' + globals.getAvatar(item.Img, 38, 38) + '" width="38" class="lt"></a><span class="lt song"><a target="_blank" href="http://5sing.kugou.com/' + item.songType + "/" + item.songId + '.html" class="lt title" title="' + item.songName + '">' + item.songName + '</a><a target="_blank" href="http://5sing.kugou.com/' + item.NewUserID + '" class="lt">' + item.NickName + '</a></span><a href="###" songinfo="' + item.songType + "$" + item.songId + '"  class="lt play playerWidget_Play"><i>播放</i></a></li>'
            }) : songLiHtml = "<li>没有推荐内容!</li>";
            var html = '<div class="spread"><h2 class="c_wap"><div class="lt title tj_title"><i class="rt help">?</i>会员推荐<span class="op_tips" style="display: none;"><i></i><a target="_blank" href="http://5sing.kugou.com/help/detail-197.html">如何参与推荐？</a></span></div><a href="###" songinfo="' + ids + '" class="rt play_all playerWidget_Play"><i class="lt"></i>全部播放</a></h2><ul>' + songLiHtml + "</ul></div>";
            $(".spread_box").append(html), $(".play_all").attr("songinfo", ids), $(".spread  ul").html(songLiHtml), $(".spread > .c_wap > .tj_title").hover(function () {
                $(this).find(".op_tips").show()
            }, function () {
                $(this).find(".op_tips").hide()
            }), $(".spread > ul > li.c_wap").hover(function () {
                $(this).find(".play_all").show()
            }, function () {
                $(this).find(".play_all").hide()
            }), _spread = _spreadBox.find(".spread").first();
            var spreadBoxStatus = WSUtils.getCookie("spreadBoxStatus");
            spreadBoxStatus ? "packup" === spreadBoxStatus ? _packupSpreadBox(!0) : _expandSpreadBox(!0) : $(window).width() >= _minWidth && ($(".spread").is(":visible") ? _expandSpreadBox(!0) : _expandSpreadBox())
        }

        function _expandSpreadBox(isWithOutAnimate) {
            var width = _packupBtn.outerWidth() + _spread.outerWidth();
            1 == isWithOutAnimate ? (_expandBtn.hide(), _packupBtn.show(), _spread.show(), _spreadBox.css("width", width)) : _spreadBox.animate({width: 0}, 300, function () {
                _expandBtn.hide(), _packupBtn.show(), _spread.show(), _spreadBox.animate({width: width}, 300)
            }), _isPackup = !1, WSUtils.setCookie([{key: "spreadBoxStatus", value: "expand"}])
        }

        function _packupSpreadBox(isWithOutAnimate) {
            1 == isWithOutAnimate ? (_expandBtn.removeClass("s_over").addClass("s_normal").show(), _packupBtn.hide(), _spread.hide(), _spreadBox.css("width", _expandBtn.outerWidth())) : _spreadBox.animate({width: 0}, 300, function () {
                _expandBtn.removeClass("s_over").addClass("s_normal").show(), _packupBtn.hide(), _spread.hide(), _spreadBox.animate({width: _expandBtn.outerWidth()}, 300)
            }), _isPackup = !0, WSUtils.setCookie([{key: "spreadBoxStatus", value: "packup"}])
        }

        var _minWidth = 1349, _windowResizeTimmer = null, _isPackup = ($(".spread_box").position(), !0), _isRoll = $(window).width() >= 1366 ? !1 : !0, _spreadBox = $(".spread_box").first(), _expandBtn = _spreadBox.find(".expand_btn").first(), _packupBtn = _spreadBox.find(".packup_btn").first(), _spread = null;
        return {
            init: function () {
                $.getJSON("http://service.5sing.kugou.com/song/getRecommandSongList?jsoncallback=?", {}, function (res) {
                    _ShowSpreadContent(res)
                }), $(window).resize(function () {
                    _windowResizeTimmer && clearTimeout(_windowResizeTimmer), _windowResizeTimmer = setTimeout(function () {
                        var width = $(window).width();
                        width >= _minWidth ? (_expandSpreadBox(!0), _isRoll = !1) : (_packupSpreadBox(!0), _isRoll = !0)
                    }, 200)
                }), _spreadBox.delegate(".expand_btn", "mouseover mouseout", function (event) {
                    _isPackup && (_spreadBox.css({width: "auto"}), "mouseover" === event.type ? _expandBtn.removeClass("s_normal").addClass("s_over") : "mouseout" === event.type && _expandBtn.removeClass("s_over").addClass("s_normal"))
                }), _spreadBox.delegate(".expand_btn,.packup_btn", "click", function () {
                    var className = $(this).attr("class");
                    -1 !== className.indexOf("expand_btn") ? _expandSpreadBox() : -1 !== className.indexOf("packup_btn") && _packupSpreadBox()
                })
            }
        }
    }();
    try {
        spreadModule.init()
    } catch (err) {
    }
    var relatedSong = function () {
        var width = ($(".related_song_panel"), $(".related_song_panel .top").width());
        $(".related_song_panel").delegate(".top li", "click", function () {
            var index = $(".related_song_panel .top li").removeClass("current").index(this), offset = index * width;
            $(this).addClass("current"), $(".related_song_panel .record_wrap").animate({left: -offset}, 500)
        })
    }(), lrcModule = function () {
        var _inspNiceScroll = ($(".inspiration-tab-content div:eq(0)"), $(".lrc-tab-content div:eq(0)"), $(".inspiration-tab-content").niceScroll(".inspiration-tab-content div", {
            nativeparentscrolling: !0,
            cursorcolor: "#e3e3e3",
            background: "#f3f3f3",
            cursorwidth: "6px",
            autohidemode: !1
        })), _lrcNiceScroll = $(".lrc-tab-content").niceScroll(".lrc-tab-content div", {
            nativeparentscrolling: !0,
            cursorcolor: "#e3e3e3",
            background: "#f3f3f3",
            cursorwidth: "6px",
            autohidemode: !1
        });
        _inspNiceScroll.onmousewheelhr = function (e) {
            if (!self.wheelprevented) {
                if (self.locked || !self.railh.scrollable)return !0;
                if (self.rail.drag)return self.cancelEvent(e);
                var nw = +new Date, chk = !1;
                return self.opt.preservenativescrolling && self.checkarea + 600 < nw && (self.nativescrollingarea = self.isScrollable(e), chk = !0), self.checkarea = nw, self.nativescrollingarea ? !0 : self.locked ? self.cancelEvent(e) : !0
            }
        }, $(".inspiration-tab-content").bind("scroll", function () {
        }), $(".lrc .lrc_tit").delegate("a", "click", function () {
            var lrcTitles = $(".lrc > .lrc_tit > .flx > li > h2 > a"), index = lrcTitles.index(this);
            lrcTitles.parent().parent().removeClass("lrc_tit_clo").eq(index).addClass("lrc_tit_clo"), $(".lrc > .lrc_box > table").hide().eq(index).show(), _inspNiceScroll.resize(), _lrcNiceScroll.resize()
        }), _inspNiceScroll.resize()
    }(), songInfoModule = function () {
        $(".k_main .view"), $(".view_player_down a").first(), $(".view_player_share a").first()
    }(), navModule = function () {
        $(".nav_container")
    }(), commentModule = function () {
        function _reload(songid, songtype, userid, container) {
            var config = {
                title: "我要评论",
                submitText: {comment: "评论", reply: "回复"},
                type: songtype,
                rootId: songid,
                ownerUserId: userid,
                canLoadMore: !0,
                limit: 10,
                template: {titlePanel: '<div class="main_tit"><h2 class="main_tit_comment">我要评论</h2></div>'}
            };
            container.WSingComment(config)
        }

        var _commontContainer = $(".box_msg");
        _reload(SongID, SongType, OwnerUserID, _commontContainer)
    }(), pageInit = function () {
        globals.logKugouVisitor(), homePage.init(), updateSongInfo(), bindEvents(), initDailyRecommend(), initReport(), initCache(), checkImage(), $("body").css("padding-bottom", 0)
    };
    pageInit()
});