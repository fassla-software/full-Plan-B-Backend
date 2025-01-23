/* ====================================
=======================================
    Nice Select Js 
=======================================
=======================================*/

(function($) {
    $.fn.niceSelect = function(method) {
        if (typeof method == 'string') {
            if (method == 'update') {
                this.each(function() {
                    var $select = $(this);
                    var $dropdown = $(this).next('.nice-select');
                    var open = $dropdown.hasClass('open');
                    if ($dropdown.length) {
                        $dropdown.remove();
                        create_nice_select($select);
                        if (open) { $select.next().trigger('click') }
                    }
                })
            } else if (method == 'destroy') {
                this.each(function() {
                    var $select = $(this);
                    var $dropdown = $(this).next('.nice-select');
                    if ($dropdown.length) {
                        $dropdown.remove();
                        $select.css('display', '')
                    }
                });
                if ($('.nice-select').length == 0) { $(document).off('.nice_select') }
            } else { console.log('Method "' + method + '" does not exist.') }
            return this
        }
        this.hide();
        this.each(function() { var $select = $(this); if (!$select.next().hasClass('nice-select')) { create_nice_select($select) } });

        function create_nice_select($select) {
            $select.after($('<div></div>').addClass('nice-select').addClass($select.attr('class') || '').addClass($select.attr('disabled') ? 'disabled' : '').addClass($select.attr('multiple') ? 'has-multiple' : '').attr('tabindex', $select.attr('disabled') ? null : '0').html($select.attr('multiple') ? '<span class="multiple-options"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search..."/></div><ul class="list"></ul>' : '<span class="current"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search..."/></div><ul class="list"></ul>'));
            var $dropdown = $select.next();
            var $options = $select.find('option');
            if ($select.attr('multiple')) {
                var $selected = $select.find('option:selected');
                var $selected_html = '';
                $selected.each(function() {
                    $selected_option = $(this);
                    $selected_text = $selected_option.data('display') || $selected_option.text();
                    if (!$selected_option.val()) { return }
                    $selected_html += '<span class="current">' + $selected_text + '</span>'
                });
                $select_placeholder = $select.data('js-placeholder') || $select.attr('js-placeholder');
                $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
                console.log($select_placeholder);
                $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
                $dropdown.find('.multiple-options').html($selected_html)
            } else {
                var $selected = $select.find('option:selected');
                $dropdown.find('.current').html($selected.data('display') || $selected.text())
            }
            $options.each(function(i) {
                var $option = $(this);
                var display = $option.data('display');
                $dropdown.find('ul').append($('<li></li>').attr('data-value', $option.val()).attr('data-display', (display || null)).addClass('option' + ($option.is(':selected') ? ' selected' : '') + ($option.is(':disabled') ? ' disabled' : '')).html($option.text()))
            })
        }
        $(document).off('.nice_select');
        $(document).on('click.nice_select', '.nice-select', function(event) {
            var $dropdown = $(this);
            $('.nice-select').not($dropdown).removeClass('open');
            $dropdown.toggleClass('open');
            if ($dropdown.hasClass('open')) {
                $dropdown.find('.option');
                $dropdown.find('.nice-select-search').val('');
                $dropdown.find('.nice-select-search').focus();
                $dropdown.find('.focus').removeClass('focus');
                $dropdown.find('.selected').addClass('focus');
                $dropdown.find('ul li').show()
            } else { $dropdown.focus() }
        });
        $(document).on('click', '.nice-select-search-box', function(event) { event.stopPropagation(); return !1 });
        $(document).on('keyup.nice-select-search', '.nice-select', function() {
            var $self = $(this);
            var $text = $self.find('.nice-select-search').val();
            var $options = $self.find('ul li');
            if ($text == '')
                $options.show();
            else if ($self.hasClass('open')) {
                $text = $text.toLowerCase();
                var $matchReg = new RegExp($text);
                if (0 < $options.length) {
                    $options.each(function() {
                        var $this = $(this);
                        var $optionText = $this.text().toLowerCase();
                        var $matchCheck = $matchReg.test($optionText);
                        $matchCheck ? $this.show() : $this.hide()
                    })
                } else { $options.show() }
            }
            $self.find('.option'), $self.find('.focus').removeClass('focus'), $self.find('.selected').addClass('focus')
        });
        $(document).on('click.nice_select', function(event) { if ($(event.target).closest('.nice-select').length === 0) { $('.nice-select').removeClass('open').find('.option') } });
        $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
            var $option = $(this);
            var $dropdown = $option.closest('.nice-select');
            if ($dropdown.hasClass('has-multiple')) {
                console.log('clicked', $option);
                if ($option.hasClass('selected')) { $option.removeClass('selected') } else { $option.addClass('selected') }
                $selected_html = '';
                $selected_values = [];
                $dropdown.find('.selected').each(function() {
                    $selected_option = $(this);
                    var text = $selected_option.data('display') || $selected_option.text();
                    $selected_html += '<span class="current">' + text + '</span>';
                    $selected_values.push($selected_option.data('value'))
                });
                $select_placeholder = $dropdown.prev('select').data('js-placeholder') || $dropdown.prev('select').attr('js-placeholder');
                console.log($dropdown.prev('select'));
                $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
                $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
                $dropdown.find('.multiple-options').html($selected_html);
                $dropdown.prev('select').val($selected_values).trigger('change')
            } else {
                $dropdown.find('.selected').removeClass('selected');
                $option.addClass('selected');
                var text = $option.data('display') || $option.text();
                $dropdown.find('.current').text(text);
                $dropdown.prev('select').val($option.data('value')).trigger('change')
            }
        });
        $(document).on('keydown.nice_select', '.nice-select', function(event) {
            var $dropdown = $(this);
            var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));
            if (event.keyCode == 32 || event.keyCode == 13) {
                if ($dropdown.hasClass('open')) { $focused_option.trigger('click') } else { $dropdown.trigger('click') }
                return !1
            } else if (event.keyCode == 40) {
                if (!$dropdown.hasClass('open')) { $dropdown.trigger('click') } else {
                    var $next = $focused_option.nextAll('.option:not(.disabled)').first();
                    if ($next.length > 0) {
                        $dropdown.find('.focus').removeClass('focus');
                        $next.addClass('focus')
                    }
                }
                return !1
            } else if (event.keyCode == 38) {
                if (!$dropdown.hasClass('open')) { $dropdown.trigger('click') } else {
                    var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
                    if ($prev.length > 0) {
                        $dropdown.find('.focus').removeClass('focus');
                        $prev.addClass('focus')
                    }
                }
                return !1
            } else if (event.keyCode == 27) { if ($dropdown.hasClass('open')) { $dropdown.trigger('click') } } else if (event.keyCode == 9) { if ($dropdown.hasClass('open')) { return !1 } }
        });
        var style = document.createElement('a').style;
        style.cssText = 'pointer-events:auto';
        if (style.pointerEvents !== 'auto') { $('html').addClass('no-csspointerevents') }
        return this
    }
}(jQuery))

/* ====================================
=======================================
    Flat Picker Js 
=======================================
=======================================*/

/* flatpickr v4.6.13,, @license MIT */
! function(e, n) { "object" == typeof exports && "undefined" != typeof module ? module.exports = n() : "function" == typeof define && define.amd ? define(n) : (e = "undefined" != typeof globalThis ? globalThis : e || self).flatpickr = n() }(this, (function() {
    "use strict";
    var e = function() {
        return (e = Object.assign || function(e) {
            for (var n, t = 1, a = arguments.length; t < a; t++)
                for (var i in n = arguments[t]) Object.prototype.hasOwnProperty.call(n, i) && (e[i] = n[i]);
            return e
        }).apply(this, arguments)
    };

    function n() {
        for (var e = 0, n = 0, t = arguments.length; n < t; n++) e += arguments[n].length;
        var a = Array(e),
            i = 0;
        for (n = 0; n < t; n++)
            for (var o = arguments[n], r = 0, l = o.length; r < l; r++, i++) a[i] = o[r];
        return a
    }
    var t = ["onChange", "onClose", "onDayCreate", "onDestroy", "onKeyDown", "onMonthChange", "onOpen", "onParseConfig", "onReady", "onValueUpdate", "onYearChange", "onPreCalendarPosition"],
        a = {
            _disable: [],
            allowInput: !1,
            allowInvalidPreload: !1,
            altFormat: "F j, Y",
            altInput: !1,
            altInputClass: "form-control input",
            animate: "object" == typeof window && -1 === window.navigator.userAgent.indexOf("MSIE"),
            ariaDateFormat: "F j, Y",
            autoFillDefaultTime: !0,
            clickOpens: !0,
            closeOnSelect: !0,
            conjunction: ", ",
            dateFormat: "Y-m-d",
            defaultHour: 12,
            defaultMinute: 0,
            defaultSeconds: 0,
            disable: [],
            disableMobile: !1,
            enableSeconds: !1,
            enableTime: !1,
            errorHandler: function(e) { return "undefined" != typeof console && console.warn(e) },
            getWeek: function(e) {
                var n = new Date(e.getTime());
                n.setHours(0, 0, 0, 0), n.setDate(n.getDate() + 3 - (n.getDay() + 6) % 7);
                var t = new Date(n.getFullYear(), 0, 4);
                return 1 + Math.round(((n.getTime() - t.getTime()) / 864e5 - 3 + (t.getDay() + 6) % 7) / 7)
            },
            hourIncrement: 1,
            ignoredFocusElements: [],
            inline: !1,
            locale: "default",
            minuteIncrement: 5,
            mode: "single",
            monthSelectorType: "dropdown",
            nextArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",
            noCalendar: !1,
            now: new Date,
            onChange: [],
            onClose: [],
            onDayCreate: [],
            onDestroy: [],
            onKeyDown: [],
            onMonthChange: [],
            onOpen: [],
            onParseConfig: [],
            onReady: [],
            onValueUpdate: [],
            onYearChange: [],
            onPreCalendarPosition: [],
            plugins: [],
            position: "auto",
            positionElement: void 0,
            prevArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",
            shorthandCurrentMonth: !1,
            showMonths: 1,
            static: !1,
            time_24hr: !1,
            weekNumbers: !1,
            wrap: !1
        },
        i = {
            weekdays: { shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"] },
            months: { shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"] },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                var n = e % 100;
                if (n > 3 && n < 21) return "th";
                switch (n % 10) {
                    case 1:
                        return "st";
                    case 2:
                        return "nd";
                    case 3:
                        return "rd";
                    default:
                        return "th"
                }
            },
            rangeSeparator: " to ",
            weekAbbreviation: "Wk",
            scrollTitle: "Scroll to increment",
            toggleTitle: "Click to toggle",
            amPM: ["AM", "PM"],
            yearAriaLabel: "Year",
            monthAriaLabel: "Month",
            hourAriaLabel: "Hour",
            minuteAriaLabel: "Minute",
            time_24hr: !1
        },
        o = function(e, n) { return void 0 === n && (n = 2), ("000" + e).slice(-1 * n) },
        r = function(e) { return !0 === e ? 1 : 0 };

    function l(e, n) {
        var t;
        return function() {
            var a = this,
                i = arguments;
            clearTimeout(t), t = setTimeout((function() { return e.apply(a, i) }), n)
        }
    }
    var c = function(e) { return e instanceof Array ? e : [e] };

    function s(e, n, t) {
        if (!0 === t) return e.classList.add(n);
        e.classList.remove(n)
    }

    function d(e, n, t) { var a = window.document.createElement(e); return n = n || "", t = t || "", a.className = n, void 0 !== t && (a.textContent = t), a }

    function u(e) { for (; e.firstChild;) e.removeChild(e.firstChild) }

    function f(e, n) { return n(e) ? e : e.parentNode ? f(e.parentNode, n) : void 0 }

    function m(e, n) {
        var t = d("div", "numInputWrapper"),
            a = d("input", "numInput " + e),
            i = d("span", "arrowUp"),
            o = d("span", "arrowDown");
        if (-1 === navigator.userAgent.indexOf("MSIE 9.0") ? a.type = "number" : (a.type = "text", a.pattern = "\\d*"), void 0 !== n)
            for (var r in n) a.setAttribute(r, n[r]);
        return t.appendChild(a), t.appendChild(i), t.appendChild(o), t
    }

    function g(e) { try { return "function" == typeof e.composedPath ? e.composedPath()[0] : e.target } catch (n) { return e.target } }
    var p = function() {},
        h = function(e, n, t) { return t.months[n ? "shorthand" : "longhand"][e] },
        v = {
            D: p,
            F: function(e, n, t) { e.setMonth(t.months.longhand.indexOf(n)) },
            G: function(e, n) { e.setHours((e.getHours() >= 12 ? 12 : 0) + parseFloat(n)) },
            H: function(e, n) { e.setHours(parseFloat(n)) },
            J: function(e, n) { e.setDate(parseFloat(n)) },
            K: function(e, n, t) { e.setHours(e.getHours() % 12 + 12 * r(new RegExp(t.amPM[1], "i").test(n))) },
            M: function(e, n, t) { e.setMonth(t.months.shorthand.indexOf(n)) },
            S: function(e, n) { e.setSeconds(parseFloat(n)) },
            U: function(e, n) { return new Date(1e3 * parseFloat(n)) },
            W: function(e, n, t) {
                var a = parseInt(n),
                    i = new Date(e.getFullYear(), 0, 2 + 7 * (a - 1), 0, 0, 0, 0);
                return i.setDate(i.getDate() - i.getDay() + t.firstDayOfWeek), i
            },
            Y: function(e, n) { e.setFullYear(parseFloat(n)) },
            Z: function(e, n) { return new Date(n) },
            d: function(e, n) { e.setDate(parseFloat(n)) },
            h: function(e, n) { e.setHours((e.getHours() >= 12 ? 12 : 0) + parseFloat(n)) },
            i: function(e, n) { e.setMinutes(parseFloat(n)) },
            j: function(e, n) { e.setDate(parseFloat(n)) },
            l: p,
            m: function(e, n) { e.setMonth(parseFloat(n) - 1) },
            n: function(e, n) { e.setMonth(parseFloat(n) - 1) },
            s: function(e, n) { e.setSeconds(parseFloat(n)) },
            u: function(e, n) { return new Date(parseFloat(n)) },
            w: p,
            y: function(e, n) { e.setFullYear(2e3 + parseFloat(n)) }
        },
        D = { D: "", F: "", G: "(\\d\\d|\\d)", H: "(\\d\\d|\\d)", J: "(\\d\\d|\\d)\\w+", K: "", M: "", S: "(\\d\\d|\\d)", U: "(.+)", W: "(\\d\\d|\\d)", Y: "(\\d{4})", Z: "(.+)", d: "(\\d\\d|\\d)", h: "(\\d\\d|\\d)", i: "(\\d\\d|\\d)", j: "(\\d\\d|\\d)", l: "", m: "(\\d\\d|\\d)", n: "(\\d\\d|\\d)", s: "(\\d\\d|\\d)", u: "(.+)", w: "(\\d\\d|\\d)", y: "(\\d{2})" },
        w = { Z: function(e) { return e.toISOString() }, D: function(e, n, t) { return n.weekdays.shorthand[w.w(e, n, t)] }, F: function(e, n, t) { return h(w.n(e, n, t) - 1, !1, n) }, G: function(e, n, t) { return o(w.h(e, n, t)) }, H: function(e) { return o(e.getHours()) }, J: function(e, n) { return void 0 !== n.ordinal ? e.getDate() + n.ordinal(e.getDate()) : e.getDate() }, K: function(e, n) { return n.amPM[r(e.getHours() > 11)] }, M: function(e, n) { return h(e.getMonth(), !0, n) }, S: function(e) { return o(e.getSeconds()) }, U: function(e) { return e.getTime() / 1e3 }, W: function(e, n, t) { return t.getWeek(e) }, Y: function(e) { return o(e.getFullYear(), 4) }, d: function(e) { return o(e.getDate()) }, h: function(e) { return e.getHours() % 12 ? e.getHours() % 12 : 12 }, i: function(e) { return o(e.getMinutes()) }, j: function(e) { return e.getDate() }, l: function(e, n) { return n.weekdays.longhand[e.getDay()] }, m: function(e) { return o(e.getMonth() + 1) }, n: function(e) { return e.getMonth() + 1 }, s: function(e) { return e.getSeconds() }, u: function(e) { return e.getTime() }, w: function(e) { return e.getDay() }, y: function(e) { return String(e.getFullYear()).substring(2) } },
        b = function(e) {
            var n = e.config,
                t = void 0 === n ? a : n,
                o = e.l10n,
                r = void 0 === o ? i : o,
                l = e.isMobile,
                c = void 0 !== l && l;
            return function(e, n, a) { var i = a || r; return void 0 === t.formatDate || c ? n.split("").map((function(n, a, o) { return w[n] && "\\" !== o[a - 1] ? w[n](e, i, t) : "\\" !== n ? n : "" })).join("") : t.formatDate(e, n, i) }
        },
        C = function(e) {
            var n = e.config,
                t = void 0 === n ? a : n,
                o = e.l10n,
                r = void 0 === o ? i : o;
            return function(e, n, i, o) {
                if (0 === e || e) {
                    var l, c = o || r,
                        s = e;
                    if (e instanceof Date) l = new Date(e.getTime());
                    else if ("string" != typeof e && void 0 !== e.toFixed) l = new Date(e);
                    else if ("string" == typeof e) {
                        var d = n || (t || a).dateFormat,
                            u = String(e).trim();
                        if ("today" === u) l = new Date, i = !0;
                        else if (t && t.parseDate) l = t.parseDate(e, d);
                        else if (/Z$/.test(u) || /GMT$/.test(u)) l = new Date(e);
                        else {
                            for (var f = void 0, m = [], g = 0, p = 0, h = ""; g < d.length; g++) {
                                var w = d[g],
                                    b = "\\" === w,
                                    C = "\\" === d[g - 1] || b;
                                if (D[w] && !C) {
                                    h += D[w];
                                    var M = new RegExp(h).exec(e);
                                    M && (f = !0) && m["Y" !== w ? "push" : "unshift"]({ fn: v[w], val: M[++p] })
                                } else b || (h += ".")
                            }
                            l = t && t.noCalendar ? new Date((new Date).setHours(0, 0, 0, 0)) : new Date((new Date).getFullYear(), 0, 1, 0, 0, 0, 0), m.forEach((function(e) {
                                var n = e.fn,
                                    t = e.val;
                                return l = n(l, t, c) || l
                            })), l = f ? l : void 0
                        }
                    }
                    if (l instanceof Date && !isNaN(l.getTime())) return !0 === i && l.setHours(0, 0, 0, 0), l;
                    t.errorHandler(new Error("Invalid date provided: " + s))
                }
            }
        };

    function M(e, n, t) { return void 0 === t && (t = !0), !1 !== t ? new Date(e.getTime()).setHours(0, 0, 0, 0) - new Date(n.getTime()).setHours(0, 0, 0, 0) : e.getTime() - n.getTime() }
    var y = function(e, n, t) { return 3600 * e + 60 * n + t },
        x = 864e5;

    function E(e) {
        var n = e.defaultHour,
            t = e.defaultMinute,
            a = e.defaultSeconds;
        if (void 0 !== e.minDate) {
            var i = e.minDate.getHours(),
                o = e.minDate.getMinutes(),
                r = e.minDate.getSeconds();
            n < i && (n = i), n === i && t < o && (t = o), n === i && t === o && a < r && (a = e.minDate.getSeconds())
        }
        if (void 0 !== e.maxDate) {
            var l = e.maxDate.getHours(),
                c = e.maxDate.getMinutes();
            (n = Math.min(n, l)) === l && (t = Math.min(c, t)), n === l && t === c && (a = e.maxDate.getSeconds())
        }
        return { hours: n, minutes: t, seconds: a }
    }
    "function" != typeof Object.assign && (Object.assign = function(e) {
        for (var n = [], t = 1; t < arguments.length; t++) n[t - 1] = arguments[t];
        if (!e) throw TypeError("Cannot convert undefined or null to object");
        for (var a = function(n) { n && Object.keys(n).forEach((function(t) { return e[t] = n[t] })) }, i = 0, o = n; i < o.length; i++) {
            var r = o[i];
            a(r)
        }
        return e
    });

    function k(p, v) {
        var w = { config: e(e({}, a), I.defaultConfig), l10n: i };

        function k() { var e; return (null === (e = w.calendarContainer) || void 0 === e ? void 0 : e.getRootNode()).activeElement || document.activeElement }

        function T(e) { return e.bind(w) }

        function S() {
            var e = w.config;
            !1 === e.weekNumbers && 1 === e.showMonths || !0 !== e.noCalendar && window.requestAnimationFrame((function() {
                if (void 0 !== w.calendarContainer && (w.calendarContainer.style.visibility = "hidden", w.calendarContainer.style.display = "block"), void 0 !== w.daysContainer) {
                    var n = (w.days.offsetWidth + 1) * e.showMonths;
                    w.daysContainer.style.width = n + "px", w.calendarContainer.style.width = n + (void 0 !== w.weekWrapper ? w.weekWrapper.offsetWidth : 0) + "px", w.calendarContainer.style.removeProperty("visibility"), w.calendarContainer.style.removeProperty("display")
                }
            }))
        }

        function _(e) {
            if (0 === w.selectedDates.length) {
                var n = void 0 === w.config.minDate || M(new Date, w.config.minDate) >= 0 ? new Date : new Date(w.config.minDate.getTime()),
                    t = E(w.config);
                n.setHours(t.hours, t.minutes, t.seconds, n.getMilliseconds()), w.selectedDates = [n], w.latestSelectedDateObj = n
            }
            void 0 !== e && "blur" !== e.type && function(e) {
                e.preventDefault();
                var n = "keydown" === e.type,
                    t = g(e),
                    a = t;
                void 0 !== w.amPM && t === w.amPM && (w.amPM.textContent = w.l10n.amPM[r(w.amPM.textContent === w.l10n.amPM[0])]);
                var i = parseFloat(a.getAttribute("min")),
                    l = parseFloat(a.getAttribute("max")),
                    c = parseFloat(a.getAttribute("step")),
                    s = parseInt(a.value, 10),
                    d = e.delta || (n ? 38 === e.which ? 1 : -1 : 0),
                    u = s + c * d;
                if (void 0 !== a.value && 2 === a.value.length) {
                    var f = a === w.hourElement,
                        m = a === w.minuteElement;
                    u < i ? (u = l + u + r(!f) + (r(f) && r(!w.amPM)), m && L(void 0, -1, w.hourElement)) : u > l && (u = a === w.hourElement ? u - l - r(!w.amPM) : i, m && L(void 0, 1, w.hourElement)), w.amPM && f && (1 === c ? u + s === 23 : Math.abs(u - s) > c) && (w.amPM.textContent = w.l10n.amPM[r(w.amPM.textContent === w.l10n.amPM[0])]), a.value = o(u)
                }
            }(e);
            var a = w._input.value;
            O(), ye(), w._input.value !== a && w._debouncedChange()
        }

        function O() {
            if (void 0 !== w.hourElement && void 0 !== w.minuteElement) {
                var e, n, t = (parseInt(w.hourElement.value.slice(-2), 10) || 0) % 24,
                    a = (parseInt(w.minuteElement.value, 10) || 0) % 60,
                    i = void 0 !== w.secondElement ? (parseInt(w.secondElement.value, 10) || 0) % 60 : 0;
                void 0 !== w.amPM && (e = t, n = w.amPM.textContent, t = e % 12 + 12 * r(n === w.l10n.amPM[1]));
                var o = void 0 !== w.config.minTime || w.config.minDate && w.minDateHasTime && w.latestSelectedDateObj && 0 === M(w.latestSelectedDateObj, w.config.minDate, !0),
                    l = void 0 !== w.config.maxTime || w.config.maxDate && w.maxDateHasTime && w.latestSelectedDateObj && 0 === M(w.latestSelectedDateObj, w.config.maxDate, !0);
                if (void 0 !== w.config.maxTime && void 0 !== w.config.minTime && w.config.minTime > w.config.maxTime) {
                    var c = y(w.config.minTime.getHours(), w.config.minTime.getMinutes(), w.config.minTime.getSeconds()),
                        s = y(w.config.maxTime.getHours(), w.config.maxTime.getMinutes(), w.config.maxTime.getSeconds()),
                        d = y(t, a, i);
                    if (d > s && d < c) {
                        var u = function(e) {
                            var n = Math.floor(e / 3600),
                                t = (e - 3600 * n) / 60;
                            return [n, t, e - 3600 * n - 60 * t]
                        }(c);
                        t = u[0], a = u[1], i = u[2]
                    }
                } else {
                    if (l) {
                        var f = void 0 !== w.config.maxTime ? w.config.maxTime : w.config.maxDate;
                        (t = Math.min(t, f.getHours())) === f.getHours() && (a = Math.min(a, f.getMinutes())), a === f.getMinutes() && (i = Math.min(i, f.getSeconds()))
                    }
                    if (o) {
                        var m = void 0 !== w.config.minTime ? w.config.minTime : w.config.minDate;
                        (t = Math.max(t, m.getHours())) === m.getHours() && a < m.getMinutes() && (a = m.getMinutes()), a === m.getMinutes() && (i = Math.max(i, m.getSeconds()))
                    }
                }
                A(t, a, i)
            }
        }

        function F(e) {
            var n = e || w.latestSelectedDateObj;
            n && n instanceof Date && A(n.getHours(), n.getMinutes(), n.getSeconds())
        }

        function A(e, n, t) { void 0 !== w.latestSelectedDateObj && w.latestSelectedDateObj.setHours(e % 24, n, t || 0, 0), w.hourElement && w.minuteElement && !w.isMobile && (w.hourElement.value = o(w.config.time_24hr ? e : (12 + e) % 12 + 12 * r(e % 12 == 0)), w.minuteElement.value = o(n), void 0 !== w.amPM && (w.amPM.textContent = w.l10n.amPM[r(e >= 12)]), void 0 !== w.secondElement && (w.secondElement.value = o(t))) }

        function N(e) {
            var n = g(e),
                t = parseInt(n.value) + (e.delta || 0);
            (t / 1e3 > 1 || "Enter" === e.key && !/[^\d]/.test(t.toString())) && ee(t)
        }

        function P(e, n, t, a) { return n instanceof Array ? n.forEach((function(n) { return P(e, n, t, a) })) : e instanceof Array ? e.forEach((function(e) { return P(e, n, t, a) })) : (e.addEventListener(n, t, a), void w._handlers.push({ remove: function() { return e.removeEventListener(n, t, a) } })) }

        function Y() { De("onChange") }

        function j(e, n) {
            var t = void 0 !== e ? w.parseDate(e) : w.latestSelectedDateObj || (w.config.minDate && w.config.minDate > w.now ? w.config.minDate : w.config.maxDate && w.config.maxDate < w.now ? w.config.maxDate : w.now),
                a = w.currentYear,
                i = w.currentMonth;
            try { void 0 !== t && (w.currentYear = t.getFullYear(), w.currentMonth = t.getMonth()) } catch (e) { e.message = "Invalid date supplied: " + t, w.config.errorHandler(e) }
            n && w.currentYear !== a && (De("onYearChange"), q()), !n || w.currentYear === a && w.currentMonth === i || De("onMonthChange"), w.redraw()
        }

        function H(e) { var n = g(e);~n.className.indexOf("arrow") && L(e, n.classList.contains("arrowUp") ? 1 : -1) }

        function L(e, n, t) {
            var a = e && g(e),
                i = t || a && a.parentNode && a.parentNode.firstChild,
                o = we("increment");
            o.delta = n, i && i.dispatchEvent(o)
        }

        function R(e, n, t, a) {
            var i = ne(n, !0),
                o = d("span", e, n.getDate().toString());
            return o.dateObj = n, o.$i = a, o.setAttribute("aria-label", w.formatDate(n, w.config.ariaDateFormat)), -1 === e.indexOf("hidden") && 0 === M(n, w.now) && (w.todayDateElem = o, o.classList.add("today"), o.setAttribute("aria-current", "date")), i ? (o.tabIndex = -1, be(n) && (o.classList.add("selected"), w.selectedDateElem = o, "range" === w.config.mode && (s(o, "startRange", w.selectedDates[0] && 0 === M(n, w.selectedDates[0], !0)), s(o, "endRange", w.selectedDates[1] && 0 === M(n, w.selectedDates[1], !0)), "nextMonthDay" === e && o.classList.add("inRange")))) : o.classList.add("flatpickr-disabled"), "range" === w.config.mode && function(e) { return !("range" !== w.config.mode || w.selectedDates.length < 2) && (M(e, w.selectedDates[0]) >= 0 && M(e, w.selectedDates[1]) <= 0) }(n) && !be(n) && o.classList.add("inRange"), w.weekNumbers && 1 === w.config.showMonths && "prevMonthDay" !== e && a % 7 == 6 && w.weekNumbers.insertAdjacentHTML("beforeend", "<span class='flatpickr-day'>" + w.config.getWeek(n) + "</span>"), De("onDayCreate", o), o
        }

        function W(e) { e.focus(), "range" === w.config.mode && oe(e) }

        function B(e) {
            for (var n = e > 0 ? 0 : w.config.showMonths - 1, t = e > 0 ? w.config.showMonths : -1, a = n; a != t; a += e)
                for (var i = w.daysContainer.children[a], o = e > 0 ? 0 : i.children.length - 1, r = e > 0 ? i.children.length : -1, l = o; l != r; l += e) { var c = i.children[l]; if (-1 === c.className.indexOf("hidden") && ne(c.dateObj)) return c }
        }

        function J(e, n) {
            var t = k(),
                a = te(t || document.body),
                i = void 0 !== e ? e : a ? t : void 0 !== w.selectedDateElem && te(w.selectedDateElem) ? w.selectedDateElem : void 0 !== w.todayDateElem && te(w.todayDateElem) ? w.todayDateElem : B(n > 0 ? 1 : -1);
            void 0 === i ? w._input.focus() : a ? function(e, n) {
                for (var t = -1 === e.className.indexOf("Month") ? e.dateObj.getMonth() : w.currentMonth, a = n > 0 ? w.config.showMonths : -1, i = n > 0 ? 1 : -1, o = t - w.currentMonth; o != a; o += i)
                    for (var r = w.daysContainer.children[o], l = t - w.currentMonth === o ? e.$i + n : n < 0 ? r.children.length - 1 : 0, c = r.children.length, s = l; s >= 0 && s < c && s != (n > 0 ? c : -1); s += i) { var d = r.children[s]; if (-1 === d.className.indexOf("hidden") && ne(d.dateObj) && Math.abs(e.$i - s) >= Math.abs(n)) return W(d) }
                w.changeMonth(i), J(B(i), 0)
            }(i, n) : W(i)
        }

        function K(e, n) { for (var t = (new Date(e, n, 1).getDay() - w.l10n.firstDayOfWeek + 7) % 7, a = w.utils.getDaysInMonth((n - 1 + 12) % 12, e), i = w.utils.getDaysInMonth(n, e), o = window.document.createDocumentFragment(), r = w.config.showMonths > 1, l = r ? "prevMonthDay hidden" : "prevMonthDay", c = r ? "nextMonthDay hidden" : "nextMonthDay", s = a + 1 - t, u = 0; s <= a; s++, u++) o.appendChild(R("flatpickr-day " + l, new Date(e, n - 1, s), 0, u)); for (s = 1; s <= i; s++, u++) o.appendChild(R("flatpickr-day", new Date(e, n, s), 0, u)); for (var f = i + 1; f <= 42 - t && (1 === w.config.showMonths || u % 7 != 0); f++, u++) o.appendChild(R("flatpickr-day " + c, new Date(e, n + 1, f % i), 0, u)); var m = d("div", "dayContainer"); return m.appendChild(o), m }

        function U() {
            if (void 0 !== w.daysContainer) {
                u(w.daysContainer), w.weekNumbers && u(w.weekNumbers);
                for (var e = document.createDocumentFragment(), n = 0; n < w.config.showMonths; n++) {
                    var t = new Date(w.currentYear, w.currentMonth, 1);
                    t.setMonth(w.currentMonth + n), e.appendChild(K(t.getFullYear(), t.getMonth()))
                }
                w.daysContainer.appendChild(e), w.days = w.daysContainer.firstChild, "range" === w.config.mode && 1 === w.selectedDates.length && oe()
            }
        }

        function q() {
            if (!(w.config.showMonths > 1 || "dropdown" !== w.config.monthSelectorType)) {
                var e = function(e) { return !(void 0 !== w.config.minDate && w.currentYear === w.config.minDate.getFullYear() && e < w.config.minDate.getMonth()) && !(void 0 !== w.config.maxDate && w.currentYear === w.config.maxDate.getFullYear() && e > w.config.maxDate.getMonth()) };
                w.monthsDropdownContainer.tabIndex = -1, w.monthsDropdownContainer.innerHTML = "";
                for (var n = 0; n < 12; n++)
                    if (e(n)) {
                        var t = d("option", "flatpickr-monthDropdown-month");
                        t.value = new Date(w.currentYear, n).getMonth().toString(), t.textContent = h(n, w.config.shorthandCurrentMonth, w.l10n), t.tabIndex = -1, w.currentMonth === n && (t.selected = !0), w.monthsDropdownContainer.appendChild(t)
                    }
            }
        }

        function $() {
            var e, n = d("div", "flatpickr-month"),
                t = window.document.createDocumentFragment();
            w.config.showMonths > 1 || "static" === w.config.monthSelectorType ? e = d("span", "cur-month") : (w.monthsDropdownContainer = d("select", "flatpickr-monthDropdown-months"), w.monthsDropdownContainer.setAttribute("aria-label", w.l10n.monthAriaLabel), P(w.monthsDropdownContainer, "change", (function(e) {
                var n = g(e),
                    t = parseInt(n.value, 10);
                w.changeMonth(t - w.currentMonth), De("onMonthChange")
            })), q(), e = w.monthsDropdownContainer);
            var a = m("cur-year", { tabindex: "-1" }),
                i = a.getElementsByTagName("input")[0];
            i.setAttribute("aria-label", w.l10n.yearAriaLabel), w.config.minDate && i.setAttribute("min", w.config.minDate.getFullYear().toString()), w.config.maxDate && (i.setAttribute("max", w.config.maxDate.getFullYear().toString()), i.disabled = !!w.config.minDate && w.config.minDate.getFullYear() === w.config.maxDate.getFullYear());
            var o = d("div", "flatpickr-current-month");
            return o.appendChild(e), o.appendChild(a), t.appendChild(o), n.appendChild(t), { container: n, yearElement: i, monthElement: e }
        }

        function V() {
            u(w.monthNav), w.monthNav.appendChild(w.prevMonthNav), w.config.showMonths && (w.yearElements = [], w.monthElements = []);
            for (var e = w.config.showMonths; e--;) {
                var n = $();
                w.yearElements.push(n.yearElement), w.monthElements.push(n.monthElement), w.monthNav.appendChild(n.container)
            }
            w.monthNav.appendChild(w.nextMonthNav)
        }

        function z() {
            w.weekdayContainer ? u(w.weekdayContainer) : w.weekdayContainer = d("div", "flatpickr-weekdays");
            for (var e = w.config.showMonths; e--;) {
                var n = d("div", "flatpickr-weekdaycontainer");
                w.weekdayContainer.appendChild(n)
            }
            return G(), w.weekdayContainer
        }

        function G() {
            if (w.weekdayContainer) {
                var e = w.l10n.firstDayOfWeek,
                    t = n(w.l10n.weekdays.shorthand);
                e > 0 && e < t.length && (t = n(t.splice(e, t.length), t.splice(0, e)));
                for (var a = w.config.showMonths; a--;) w.weekdayContainer.children[a].innerHTML = "\n      <span class='flatpickr-weekday'>\n        " + t.join("</span><span class='flatpickr-weekday'>") + "\n      </span>\n      "
            }
        }

        function Z(e, n) {
            void 0 === n && (n = !0);
            var t = n ? e : e - w.currentMonth;
            t < 0 && !0 === w._hidePrevMonthArrow || t > 0 && !0 === w._hideNextMonthArrow || (w.currentMonth += t, (w.currentMonth < 0 || w.currentMonth > 11) && (w.currentYear += w.currentMonth > 11 ? 1 : -1, w.currentMonth = (w.currentMonth + 12) % 12, De("onYearChange"), q()), U(), De("onMonthChange"), Ce())
        }

        function Q(e) { return w.calendarContainer.contains(e) }

        function X(e) {
            if (w.isOpen && !w.config.inline) {
                var n = g(e),
                    t = Q(n),
                    a = !(n === w.input || n === w.altInput || w.element.contains(n) || e.path && e.path.indexOf && (~e.path.indexOf(w.input) || ~e.path.indexOf(w.altInput))) && !t && !Q(e.relatedTarget),
                    i = !w.config.ignoredFocusElements.some((function(e) { return e.contains(n) }));
                a && i && (w.config.allowInput && w.setDate(w._input.value, !1, w.config.altInput ? w.config.altFormat : w.config.dateFormat), void 0 !== w.timeContainer && void 0 !== w.minuteElement && void 0 !== w.hourElement && "" !== w.input.value && void 0 !== w.input.value && _(), w.close(), w.config && "range" === w.config.mode && 1 === w.selectedDates.length && w.clear(!1))
            }
        }

        function ee(e) {
            if (!(!e || w.config.minDate && e < w.config.minDate.getFullYear() || w.config.maxDate && e > w.config.maxDate.getFullYear())) {
                var n = e,
                    t = w.currentYear !== n;
                w.currentYear = n || w.currentYear, w.config.maxDate && w.currentYear === w.config.maxDate.getFullYear() ? w.currentMonth = Math.min(w.config.maxDate.getMonth(), w.currentMonth) : w.config.minDate && w.currentYear === w.config.minDate.getFullYear() && (w.currentMonth = Math.max(w.config.minDate.getMonth(), w.currentMonth)), t && (w.redraw(), De("onYearChange"), q())
            }
        }

        function ne(e, n) {
            var t;
            void 0 === n && (n = !0);
            var a = w.parseDate(e, void 0, n);
            if (w.config.minDate && a && M(a, w.config.minDate, void 0 !== n ? n : !w.minDateHasTime) < 0 || w.config.maxDate && a && M(a, w.config.maxDate, void 0 !== n ? n : !w.maxDateHasTime) > 0) return !1;
            if (!w.config.enable && 0 === w.config.disable.length) return !0;
            if (void 0 === a) return !1;
            for (var i = !!w.config.enable, o = null !== (t = w.config.enable) && void 0 !== t ? t : w.config.disable, r = 0, l = void 0; r < o.length; r++) { if ("function" == typeof(l = o[r]) && l(a)) return i; if (l instanceof Date && void 0 !== a && l.getTime() === a.getTime()) return i; if ("string" == typeof l) { var c = w.parseDate(l, void 0, !0); return c && c.getTime() === a.getTime() ? i : !i } if ("object" == typeof l && void 0 !== a && l.from && l.to && a.getTime() >= l.from.getTime() && a.getTime() <= l.to.getTime()) return i }
            return !i
        }

        function te(e) { return void 0 !== w.daysContainer && (-1 === e.className.indexOf("hidden") && -1 === e.className.indexOf("flatpickr-disabled") && w.daysContainer.contains(e)) }

        function ae(e) {
            var n = e.target === w._input,
                t = w._input.value.trimEnd() !== Me();
            !n || !t || e.relatedTarget && Q(e.relatedTarget) || w.setDate(w._input.value, !0, e.target === w.altInput ? w.config.altFormat : w.config.dateFormat)
        }

        function ie(e) {
            var n = g(e),
                t = w.config.wrap ? p.contains(n) : n === w._input,
                a = w.config.allowInput,
                i = w.isOpen && (!a || !t),
                o = w.config.inline && t && !a;
            if (13 === e.keyCode && t) {
                if (a) return w.setDate(w._input.value, !0, n === w.altInput ? w.config.altFormat : w.config.dateFormat), w.close(), n.blur();
                w.open()
            } else if (Q(n) || i || o) {
                var r = !!w.timeContainer && w.timeContainer.contains(n);
                switch (e.keyCode) {
                    case 13:
                        r ? (e.preventDefault(), _(), fe()) : me(e);
                        break;
                    case 27:
                        e.preventDefault(), fe();
                        break;
                    case 8:
                    case 46:
                        t && !w.config.allowInput && (e.preventDefault(), w.clear());
                        break;
                    case 37:
                    case 39:
                        if (r || t) w.hourElement && w.hourElement.focus();
                        else {
                            e.preventDefault();
                            var l = k();
                            if (void 0 !== w.daysContainer && (!1 === a || l && te(l))) {
                                var c = 39 === e.keyCode ? 1 : -1;
                                e.ctrlKey ? (e.stopPropagation(), Z(c), J(B(1), 0)) : J(void 0, c)
                            }
                        }
                        break;
                    case 38:
                    case 40:
                        e.preventDefault();
                        var s = 40 === e.keyCode ? 1 : -1;
                        w.daysContainer && void 0 !== n.$i || n === w.input || n === w.altInput ? e.ctrlKey ? (e.stopPropagation(), ee(w.currentYear - s), J(B(1), 0)) : r || J(void 0, 7 * s) : n === w.currentYearElement ? ee(w.currentYear - s) : w.config.enableTime && (!r && w.hourElement && w.hourElement.focus(), _(e), w._debouncedChange());
                        break;
                    case 9:
                        if (r) {
                            var d = [w.hourElement, w.minuteElement, w.secondElement, w.amPM].concat(w.pluginElements).filter((function(e) { return e })),
                                u = d.indexOf(n);
                            if (-1 !== u) {
                                var f = d[u + (e.shiftKey ? -1 : 1)];
                                e.preventDefault(), (f || w._input).focus()
                            }
                        } else !w.config.noCalendar && w.daysContainer && w.daysContainer.contains(n) && e.shiftKey && (e.preventDefault(), w._input.focus())
                }
            }
            if (void 0 !== w.amPM && n === w.amPM) switch (e.key) {
                case w.l10n.amPM[0].charAt(0):
                case w.l10n.amPM[0].charAt(0).toLowerCase():
                    w.amPM.textContent = w.l10n.amPM[0], O(), ye();
                    break;
                case w.l10n.amPM[1].charAt(0):
                case w.l10n.amPM[1].charAt(0).toLowerCase():
                    w.amPM.textContent = w.l10n.amPM[1], O(), ye()
            }(t || Q(n)) && De("onKeyDown", e)
        }

        function oe(e, n) {
            if (void 0 === n && (n = "flatpickr-day"), 1 === w.selectedDates.length && (!e || e.classList.contains(n) && !e.classList.contains("flatpickr-disabled"))) {
                for (var t = e ? e.dateObj.getTime() : w.days.firstElementChild.dateObj.getTime(), a = w.parseDate(w.selectedDates[0], void 0, !0).getTime(), i = Math.min(t, w.selectedDates[0].getTime()), o = Math.max(t, w.selectedDates[0].getTime()), r = !1, l = 0, c = 0, s = i; s < o; s += x) ne(new Date(s), !0) || (r = r || s > i && s < o, s < a && (!l || s > l) ? l = s : s > a && (!c || s < c) && (c = s));
                Array.from(w.rContainer.querySelectorAll("*:nth-child(-n+" + w.config.showMonths + ") > ." + n)).forEach((function(n) {
                    var i, o, s, d = n.dateObj.getTime(),
                        u = l > 0 && d < l || c > 0 && d > c;
                    if (u) return n.classList.add("notAllowed"), void["inRange", "startRange", "endRange"].forEach((function(e) { n.classList.remove(e) }));
                    r && !u || (["startRange", "inRange", "endRange", "notAllowed"].forEach((function(e) { n.classList.remove(e) })), void 0 !== e && (e.classList.add(t <= w.selectedDates[0].getTime() ? "startRange" : "endRange"), a < t && d === a ? n.classList.add("startRange") : a > t && d === a && n.classList.add("endRange"), d >= l && (0 === c || d <= c) && (o = a, s = t, (i = d) > Math.min(o, s) && i < Math.max(o, s)) && n.classList.add("inRange")))
                }))
            }
        }

        function re() {!w.isOpen || w.config.static || w.config.inline || de() }

        function le(e) {
            return function(n) {
                var t = w.config["_" + e + "Date"] = w.parseDate(n, w.config.dateFormat),
                    a = w.config["_" + ("min" === e ? "max" : "min") + "Date"];
                void 0 !== t && (w["min" === e ? "minDateHasTime" : "maxDateHasTime"] = t.getHours() > 0 || t.getMinutes() > 0 || t.getSeconds() > 0), w.selectedDates && (w.selectedDates = w.selectedDates.filter((function(e) { return ne(e) })), w.selectedDates.length || "min" !== e || F(t), ye()), w.daysContainer && (ue(), void 0 !== t ? w.currentYearElement[e] = t.getFullYear().toString() : w.currentYearElement.removeAttribute(e), w.currentYearElement.disabled = !!a && void 0 !== t && a.getFullYear() === t.getFullYear())
            }
        }

        function ce() { return w.config.wrap ? p.querySelector("[data-input]") : p }

        function se() { "object" != typeof w.config.locale && void 0 === I.l10ns[w.config.locale] && w.config.errorHandler(new Error("flatpickr: invalid locale " + w.config.locale)), w.l10n = e(e({}, I.l10ns.default), "object" == typeof w.config.locale ? w.config.locale : "default" !== w.config.locale ? I.l10ns[w.config.locale] : void 0), D.D = "(" + w.l10n.weekdays.shorthand.join("|") + ")", D.l = "(" + w.l10n.weekdays.longhand.join("|") + ")", D.M = "(" + w.l10n.months.shorthand.join("|") + ")", D.F = "(" + w.l10n.months.longhand.join("|") + ")", D.K = "(" + w.l10n.amPM[0] + "|" + w.l10n.amPM[1] + "|" + w.l10n.amPM[0].toLowerCase() + "|" + w.l10n.amPM[1].toLowerCase() + ")", void 0 === e(e({}, v), JSON.parse(JSON.stringify(p.dataset || {}))).time_24hr && void 0 === I.defaultConfig.time_24hr && (w.config.time_24hr = w.l10n.time_24hr), w.formatDate = b(w), w.parseDate = C({ config: w.config, l10n: w.l10n }) }

        function de(e) {
            if ("function" != typeof w.config.position) {
                if (void 0 !== w.calendarContainer) {
                    De("onPreCalendarPosition");
                    var n = e || w._positionElement,
                        t = Array.prototype.reduce.call(w.calendarContainer.children, (function(e, n) { return e + n.offsetHeight }), 0),
                        a = w.calendarContainer.offsetWidth,
                        i = w.config.position.split(" "),
                        o = i[0],
                        r = i.length > 1 ? i[1] : null,
                        l = n.getBoundingClientRect(),
                        c = window.innerHeight - l.bottom,
                        d = "above" === o || "below" !== o && c < t && l.top > t,
                        u = window.pageYOffset + l.top + (d ? -t - 2 : n.offsetHeight + 2);
                    if (s(w.calendarContainer, "arrowTop", !d), s(w.calendarContainer, "arrowBottom", d), !w.config.inline) {
                        var f = window.pageXOffset + l.left,
                            m = !1,
                            g = !1;
                        "center" === r ? (f -= (a - l.width) / 2, m = !0) : "right" === r && (f -= a - l.width, g = !0), s(w.calendarContainer, "arrowLeft", !m && !g), s(w.calendarContainer, "arrowCenter", m), s(w.calendarContainer, "arrowRight", g);
                        var p = window.document.body.offsetWidth - (window.pageXOffset + l.right),
                            h = f + a > window.document.body.offsetWidth,
                            v = p + a > window.document.body.offsetWidth;
                        if (s(w.calendarContainer, "rightMost", h), !w.config.static)
                            if (w.calendarContainer.style.top = u + "px", h)
                                if (v) {
                                    var D = function() {
                                        for (var e = null, n = 0; n < document.styleSheets.length; n++) {
                                            var t = document.styleSheets[n];
                                            if (t.cssRules) {
                                                try { t.cssRules } catch (e) { continue }
                                                e = t;
                                                break
                                            }
                                        }
                                        return null != e ? e : (a = document.createElement("style"), document.head.appendChild(a), a.sheet);
                                        var a
                                    }();
                                    if (void 0 === D) return;
                                    var b = window.document.body.offsetWidth,
                                        C = Math.max(0, b / 2 - a / 2),
                                        M = D.cssRules.length,
                                        y = "{left:" + l.left + "px;right:auto;}";
                                    s(w.calendarContainer, "rightMost", !1), s(w.calendarContainer, "centerMost", !0), D.insertRule(".flatpickr-calendar.centerMost:before,.flatpickr-calendar.centerMost:after" + y, M), w.calendarContainer.style.left = C + "px", w.calendarContainer.style.right = "auto"
                                } else w.calendarContainer.style.left = "auto", w.calendarContainer.style.right = p + "px";
                        else w.calendarContainer.style.left = f + "px", w.calendarContainer.style.right = "auto"
                    }
                }
            } else w.config.position(w, e)
        }

        function ue() { w.config.noCalendar || w.isMobile || (q(), Ce(), U()) }

        function fe() { w._input.focus(), -1 !== window.navigator.userAgent.indexOf("MSIE") || void 0 !== navigator.msMaxTouchPoints ? setTimeout(w.close, 0) : w.close() }

        function me(e) {
            e.preventDefault(), e.stopPropagation();
            var n = f(g(e), (function(e) { return e.classList && e.classList.contains("flatpickr-day") && !e.classList.contains("flatpickr-disabled") && !e.classList.contains("notAllowed") }));
            if (void 0 !== n) {
                var t = n,
                    a = w.latestSelectedDateObj = new Date(t.dateObj.getTime()),
                    i = (a.getMonth() < w.currentMonth || a.getMonth() > w.currentMonth + w.config.showMonths - 1) && "range" !== w.config.mode;
                if (w.selectedDateElem = t, "single" === w.config.mode) w.selectedDates = [a];
                else if ("multiple" === w.config.mode) {
                    var o = be(a);
                    o ? w.selectedDates.splice(parseInt(o), 1) : w.selectedDates.push(a)
                } else "range" === w.config.mode && (2 === w.selectedDates.length && w.clear(!1, !1), w.latestSelectedDateObj = a, w.selectedDates.push(a), 0 !== M(a, w.selectedDates[0], !0) && w.selectedDates.sort((function(e, n) { return e.getTime() - n.getTime() })));
                if (O(), i) {
                    var r = w.currentYear !== a.getFullYear();
                    w.currentYear = a.getFullYear(), w.currentMonth = a.getMonth(), r && (De("onYearChange"), q()), De("onMonthChange")
                }
                if (Ce(), U(), ye(), i || "range" === w.config.mode || 1 !== w.config.showMonths ? void 0 !== w.selectedDateElem && void 0 === w.hourElement && w.selectedDateElem && w.selectedDateElem.focus() : W(t), void 0 !== w.hourElement && void 0 !== w.hourElement && w.hourElement.focus(), w.config.closeOnSelect) {
                    var l = "single" === w.config.mode && !w.config.enableTime,
                        c = "range" === w.config.mode && 2 === w.selectedDates.length && !w.config.enableTime;
                    (l || c) && fe()
                }
                Y()
            }
        }
        w.parseDate = C({ config: w.config, l10n: w.l10n }), w._handlers = [], w.pluginElements = [], w.loadedPlugins = [], w._bind = P, w._setHoursFromDate = F, w._positionCalendar = de, w.changeMonth = Z, w.changeYear = ee, w.clear = function(e, n) {
            void 0 === e && (e = !0);
            void 0 === n && (n = !0);
            w.input.value = "", void 0 !== w.altInput && (w.altInput.value = "");
            void 0 !== w.mobileInput && (w.mobileInput.value = "");
            w.selectedDates = [], w.latestSelectedDateObj = void 0, !0 === n && (w.currentYear = w._initialDate.getFullYear(), w.currentMonth = w._initialDate.getMonth());
            if (!0 === w.config.enableTime) {
                var t = E(w.config),
                    a = t.hours,
                    i = t.minutes,
                    o = t.seconds;
                A(a, i, o)
            }
            w.redraw(), e && De("onChange")
        }, w.close = function() {
            w.isOpen = !1, w.isMobile || (void 0 !== w.calendarContainer && w.calendarContainer.classList.remove("open"), void 0 !== w._input && w._input.classList.remove("active"));
            De("onClose")
        }, w.onMouseOver = oe, w._createElement = d, w.createDay = R, w.destroy = function() {
            void 0 !== w.config && De("onDestroy");
            for (var e = w._handlers.length; e--;) w._handlers[e].remove();
            if (w._handlers = [], w.mobileInput) w.mobileInput.parentNode && w.mobileInput.parentNode.removeChild(w.mobileInput), w.mobileInput = void 0;
            else if (w.calendarContainer && w.calendarContainer.parentNode)
                if (w.config.static && w.calendarContainer.parentNode) {
                    var n = w.calendarContainer.parentNode;
                    if (n.lastChild && n.removeChild(n.lastChild), n.parentNode) {
                        for (; n.firstChild;) n.parentNode.insertBefore(n.firstChild, n);
                        n.parentNode.removeChild(n)
                    }
                } else w.calendarContainer.parentNode.removeChild(w.calendarContainer);
            w.altInput && (w.input.type = "text", w.altInput.parentNode && w.altInput.parentNode.removeChild(w.altInput), delete w.altInput);
            w.input && (w.input.type = w.input._type, w.input.classList.remove("flatpickr-input"), w.input.removeAttribute("readonly"));
            ["_showTimeInput", "latestSelectedDateObj", "_hideNextMonthArrow", "_hidePrevMonthArrow", "__hideNextMonthArrow", "__hidePrevMonthArrow", "isMobile", "isOpen", "selectedDateElem", "minDateHasTime", "maxDateHasTime", "days", "daysContainer", "_input", "_positionElement", "innerContainer", "rContainer", "monthNav", "todayDateElem", "calendarContainer", "weekdayContainer", "prevMonthNav", "nextMonthNav", "monthsDropdownContainer", "currentMonthElement", "currentYearElement", "navigationCurrentMonth", "selectedDateElem", "config"].forEach((function(e) { try { delete w[e] } catch (e) {} }))
        }, w.isEnabled = ne, w.jumpToDate = j, w.updateValue = ye, w.open = function(e, n) {
            void 0 === n && (n = w._positionElement);
            if (!0 === w.isMobile) {
                if (e) {
                    e.preventDefault();
                    var t = g(e);
                    t && t.blur()
                }
                return void 0 !== w.mobileInput && (w.mobileInput.focus(), w.mobileInput.click()), void De("onOpen")
            }
            if (w._input.disabled || w.config.inline) return;
            var a = w.isOpen;
            w.isOpen = !0, a || (w.calendarContainer.classList.add("open"), w._input.classList.add("active"), De("onOpen"), de(n));
            !0 === w.config.enableTime && !0 === w.config.noCalendar && (!1 !== w.config.allowInput || void 0 !== e && w.timeContainer.contains(e.relatedTarget) || setTimeout((function() { return w.hourElement.select() }), 50))
        }, w.redraw = ue, w.set = function(e, n) {
            if (null !== e && "object" == typeof e)
                for (var a in Object.assign(w.config, e), e) void 0 !== ge[a] && ge[a].forEach((function(e) { return e() }));
            else w.config[e] = n, void 0 !== ge[e] ? ge[e].forEach((function(e) { return e() })) : t.indexOf(e) > -1 && (w.config[e] = c(n));
            w.redraw(), ye(!0)
        }, w.setDate = function(e, n, t) {
            void 0 === n && (n = !1);
            void 0 === t && (t = w.config.dateFormat);
            if (0 !== e && !e || e instanceof Array && 0 === e.length) return w.clear(n);
            pe(e, t), w.latestSelectedDateObj = w.selectedDates[w.selectedDates.length - 1], w.redraw(), j(void 0, n), F(), 0 === w.selectedDates.length && w.clear(!1);
            ye(n), n && De("onChange")
        }, w.toggle = function(e) {
            if (!0 === w.isOpen) return w.close();
            w.open(e)
        };
        var ge = { locale: [se, G], showMonths: [V, S, z], minDate: [j], maxDate: [j], positionElement: [ve], clickOpens: [function() {!0 === w.config.clickOpens ? (P(w._input, "focus", w.open), P(w._input, "click", w.open)) : (w._input.removeEventListener("focus", w.open), w._input.removeEventListener("click", w.open)) }] };

        function pe(e, n) {
            var t = [];
            if (e instanceof Array) t = e.map((function(e) { return w.parseDate(e, n) }));
            else if (e instanceof Date || "number" == typeof e) t = [w.parseDate(e, n)];
            else if ("string" == typeof e) switch (w.config.mode) {
                case "single":
                case "time":
                    t = [w.parseDate(e, n)];
                    break;
                case "multiple":
                    t = e.split(w.config.conjunction).map((function(e) { return w.parseDate(e, n) }));
                    break;
                case "range":
                    t = e.split(w.l10n.rangeSeparator).map((function(e) { return w.parseDate(e, n) }))
            } else w.config.errorHandler(new Error("Invalid date supplied: " + JSON.stringify(e)));
            w.selectedDates = w.config.allowInvalidPreload ? t : t.filter((function(e) { return e instanceof Date && ne(e, !1) })), "range" === w.config.mode && w.selectedDates.sort((function(e, n) { return e.getTime() - n.getTime() }))
        }

        function he(e) { return e.slice().map((function(e) { return "string" == typeof e || "number" == typeof e || e instanceof Date ? w.parseDate(e, void 0, !0) : e && "object" == typeof e && e.from && e.to ? { from: w.parseDate(e.from, void 0), to: w.parseDate(e.to, void 0) } : e })).filter((function(e) { return e })) }

        function ve() { w._positionElement = w.config.positionElement || w._input }

        function De(e, n) {
            if (void 0 !== w.config) {
                var t = w.config[e];
                if (void 0 !== t && t.length > 0)
                    for (var a = 0; t[a] && a < t.length; a++) t[a](w.selectedDates, w.input.value, w, n);
                "onChange" === e && (w.input.dispatchEvent(we("change")), w.input.dispatchEvent(we("input")))
            }
        }

        function we(e) { var n = document.createEvent("Event"); return n.initEvent(e, !0, !0), n }

        function be(e) { for (var n = 0; n < w.selectedDates.length; n++) { var t = w.selectedDates[n]; if (t instanceof Date && 0 === M(t, e)) return "" + n } return !1 }

        function Ce() {
            w.config.noCalendar || w.isMobile || !w.monthNav || (w.yearElements.forEach((function(e, n) {
                var t = new Date(w.currentYear, w.currentMonth, 1);
                t.setMonth(w.currentMonth + n), w.config.showMonths > 1 || "static" === w.config.monthSelectorType ? w.monthElements[n].textContent = h(t.getMonth(), w.config.shorthandCurrentMonth, w.l10n) + " " : w.monthsDropdownContainer.value = t.getMonth().toString(), e.value = t.getFullYear().toString()
            })), w._hidePrevMonthArrow = void 0 !== w.config.minDate && (w.currentYear === w.config.minDate.getFullYear() ? w.currentMonth <= w.config.minDate.getMonth() : w.currentYear < w.config.minDate.getFullYear()), w._hideNextMonthArrow = void 0 !== w.config.maxDate && (w.currentYear === w.config.maxDate.getFullYear() ? w.currentMonth + 1 > w.config.maxDate.getMonth() : w.currentYear > w.config.maxDate.getFullYear()))
        }

        function Me(e) { var n = e || (w.config.altInput ? w.config.altFormat : w.config.dateFormat); return w.selectedDates.map((function(e) { return w.formatDate(e, n) })).filter((function(e, n, t) { return "range" !== w.config.mode || w.config.enableTime || t.indexOf(e) === n })).join("range" !== w.config.mode ? w.config.conjunction : w.l10n.rangeSeparator) }

        function ye(e) { void 0 === e && (e = !0), void 0 !== w.mobileInput && w.mobileFormatStr && (w.mobileInput.value = void 0 !== w.latestSelectedDateObj ? w.formatDate(w.latestSelectedDateObj, w.mobileFormatStr) : ""), w.input.value = Me(w.config.dateFormat), void 0 !== w.altInput && (w.altInput.value = Me(w.config.altFormat)), !1 !== e && De("onValueUpdate") }

        function xe(e) {
            var n = g(e),
                t = w.prevMonthNav.contains(n),
                a = w.nextMonthNav.contains(n);
            t || a ? Z(t ? -1 : 1) : w.yearElements.indexOf(n) >= 0 ? n.select() : n.classList.contains("arrowUp") ? w.changeYear(w.currentYear + 1) : n.classList.contains("arrowDown") && w.changeYear(w.currentYear - 1)
        }
        return function() {
            w.element = w.input = p, w.isOpen = !1,
                function() {
                    var n = ["wrap", "weekNumbers", "allowInput", "allowInvalidPreload", "clickOpens", "time_24hr", "enableTime", "noCalendar", "altInput", "shorthandCurrentMonth", "inline", "static", "enableSeconds", "disableMobile"],
                        i = e(e({}, JSON.parse(JSON.stringify(p.dataset || {}))), v),
                        o = {};
                    w.config.parseDate = i.parseDate, w.config.formatDate = i.formatDate, Object.defineProperty(w.config, "enable", { get: function() { return w.config._enable }, set: function(e) { w.config._enable = he(e) } }), Object.defineProperty(w.config, "disable", { get: function() { return w.config._disable }, set: function(e) { w.config._disable = he(e) } });
                    var r = "time" === i.mode;
                    if (!i.dateFormat && (i.enableTime || r)) {
                        var l = I.defaultConfig.dateFormat || a.dateFormat;
                        o.dateFormat = i.noCalendar || r ? "H:i" + (i.enableSeconds ? ":S" : "") : l + " H:i" + (i.enableSeconds ? ":S" : "")
                    }
                    if (i.altInput && (i.enableTime || r) && !i.altFormat) {
                        var s = I.defaultConfig.altFormat || a.altFormat;
                        o.altFormat = i.noCalendar || r ? "h:i" + (i.enableSeconds ? ":S K" : " K") : s + " h:i" + (i.enableSeconds ? ":S" : "") + " K"
                    }
                    Object.defineProperty(w.config, "minDate", { get: function() { return w.config._minDate }, set: le("min") }), Object.defineProperty(w.config, "maxDate", { get: function() { return w.config._maxDate }, set: le("max") });
                    var d = function(e) { return function(n) { w.config["min" === e ? "_minTime" : "_maxTime"] = w.parseDate(n, "H:i:S") } };
                    Object.defineProperty(w.config, "minTime", { get: function() { return w.config._minTime }, set: d("min") }), Object.defineProperty(w.config, "maxTime", { get: function() { return w.config._maxTime }, set: d("max") }), "time" === i.mode && (w.config.noCalendar = !0, w.config.enableTime = !0);
                    Object.assign(w.config, o, i);
                    for (var u = 0; u < n.length; u++) w.config[n[u]] = !0 === w.config[n[u]] || "true" === w.config[n[u]];
                    t.filter((function(e) { return void 0 !== w.config[e] })).forEach((function(e) { w.config[e] = c(w.config[e] || []).map(T) })), w.isMobile = !w.config.disableMobile && !w.config.inline && "single" === w.config.mode && !w.config.disable.length && !w.config.enable && !w.config.weekNumbers && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    for (u = 0; u < w.config.plugins.length; u++) { var f = w.config.plugins[u](w) || {}; for (var m in f) t.indexOf(m) > -1 ? w.config[m] = c(f[m]).map(T).concat(w.config[m]) : void 0 === i[m] && (w.config[m] = f[m]) }
                    i.altInputClass || (w.config.altInputClass = ce().className + " " + w.config.altInputClass);
                    De("onParseConfig")
                }(), se(),
                function() {
                    if (w.input = ce(), !w.input) return void w.config.errorHandler(new Error("Invalid input element specified"));
                    w.input._type = w.input.type, w.input.type = "text", w.input.classList.add("flatpickr-input"), w._input = w.input, w.config.altInput && (w.altInput = d(w.input.nodeName, w.config.altInputClass), w._input = w.altInput, w.altInput.placeholder = w.input.placeholder, w.altInput.disabled = w.input.disabled, w.altInput.required = w.input.required, w.altInput.tabIndex = w.input.tabIndex, w.altInput.type = "text", w.input.setAttribute("type", "hidden"), !w.config.static && w.input.parentNode && w.input.parentNode.insertBefore(w.altInput, w.input.nextSibling));
                    w.config.allowInput || w._input.setAttribute("readonly", "readonly");
                    ve()
                }(),
                function() {
                    w.selectedDates = [], w.now = w.parseDate(w.config.now) || new Date;
                    var e = w.config.defaultDate || ("INPUT" !== w.input.nodeName && "TEXTAREA" !== w.input.nodeName || !w.input.placeholder || w.input.value !== w.input.placeholder ? w.input.value : null);
                    e && pe(e, w.config.dateFormat);
                    w._initialDate = w.selectedDates.length > 0 ? w.selectedDates[0] : w.config.minDate && w.config.minDate.getTime() > w.now.getTime() ? w.config.minDate : w.config.maxDate && w.config.maxDate.getTime() < w.now.getTime() ? w.config.maxDate : w.now, w.currentYear = w._initialDate.getFullYear(), w.currentMonth = w._initialDate.getMonth(), w.selectedDates.length > 0 && (w.latestSelectedDateObj = w.selectedDates[0]);
                    void 0 !== w.config.minTime && (w.config.minTime = w.parseDate(w.config.minTime, "H:i"));
                    void 0 !== w.config.maxTime && (w.config.maxTime = w.parseDate(w.config.maxTime, "H:i"));
                    w.minDateHasTime = !!w.config.minDate && (w.config.minDate.getHours() > 0 || w.config.minDate.getMinutes() > 0 || w.config.minDate.getSeconds() > 0), w.maxDateHasTime = !!w.config.maxDate && (w.config.maxDate.getHours() > 0 || w.config.maxDate.getMinutes() > 0 || w.config.maxDate.getSeconds() > 0)
                }(), w.utils = { getDaysInMonth: function(e, n) { return void 0 === e && (e = w.currentMonth), void 0 === n && (n = w.currentYear), 1 === e && (n % 4 == 0 && n % 100 != 0 || n % 400 == 0) ? 29 : w.l10n.daysInMonth[e] } }, w.isMobile || function() {
                    var e = window.document.createDocumentFragment();
                    if (w.calendarContainer = d("div", "flatpickr-calendar"), w.calendarContainer.tabIndex = -1, !w.config.noCalendar) {
                        if (e.appendChild((w.monthNav = d("div", "flatpickr-months"), w.yearElements = [], w.monthElements = [], w.prevMonthNav = d("span", "flatpickr-prev-month"), w.prevMonthNav.innerHTML = w.config.prevArrow, w.nextMonthNav = d("span", "flatpickr-next-month"), w.nextMonthNav.innerHTML = w.config.nextArrow, V(), Object.defineProperty(w, "_hidePrevMonthArrow", { get: function() { return w.__hidePrevMonthArrow }, set: function(e) { w.__hidePrevMonthArrow !== e && (s(w.prevMonthNav, "flatpickr-disabled", e), w.__hidePrevMonthArrow = e) } }), Object.defineProperty(w, "_hideNextMonthArrow", { get: function() { return w.__hideNextMonthArrow }, set: function(e) { w.__hideNextMonthArrow !== e && (s(w.nextMonthNav, "flatpickr-disabled", e), w.__hideNextMonthArrow = e) } }), w.currentYearElement = w.yearElements[0], Ce(), w.monthNav)), w.innerContainer = d("div", "flatpickr-innerContainer"), w.config.weekNumbers) {
                            var n = function() {
                                    w.calendarContainer.classList.add("hasWeeks");
                                    var e = d("div", "flatpickr-weekwrapper");
                                    e.appendChild(d("span", "flatpickr-weekday", w.l10n.weekAbbreviation));
                                    var n = d("div", "flatpickr-weeks");
                                    return e.appendChild(n), { weekWrapper: e, weekNumbers: n }
                                }(),
                                t = n.weekWrapper,
                                a = n.weekNumbers;
                            w.innerContainer.appendChild(t), w.weekNumbers = a, w.weekWrapper = t
                        }
                        w.rContainer = d("div", "flatpickr-rContainer"), w.rContainer.appendChild(z()), w.daysContainer || (w.daysContainer = d("div", "flatpickr-days"), w.daysContainer.tabIndex = -1), U(), w.rContainer.appendChild(w.daysContainer), w.innerContainer.appendChild(w.rContainer), e.appendChild(w.innerContainer)
                    }
                    w.config.enableTime && e.appendChild(function() {
                        w.calendarContainer.classList.add("hasTime"), w.config.noCalendar && w.calendarContainer.classList.add("noCalendar");
                        var e = E(w.config);
                        w.timeContainer = d("div", "flatpickr-time"), w.timeContainer.tabIndex = -1;
                        var n = d("span", "flatpickr-time-separator", ":"),
                            t = m("flatpickr-hour", { "aria-label": w.l10n.hourAriaLabel });
                        w.hourElement = t.getElementsByTagName("input")[0];
                        var a = m("flatpickr-minute", { "aria-label": w.l10n.minuteAriaLabel });
                        w.minuteElement = a.getElementsByTagName("input")[0], w.hourElement.tabIndex = w.minuteElement.tabIndex = -1, w.hourElement.value = o(w.latestSelectedDateObj ? w.latestSelectedDateObj.getHours() : w.config.time_24hr ? e.hours : function(e) {
                            switch (e % 24) {
                                case 0:
                                case 12:
                                    return 12;
                                default:
                                    return e % 12
                            }
                        }(e.hours)), w.minuteElement.value = o(w.latestSelectedDateObj ? w.latestSelectedDateObj.getMinutes() : e.minutes), w.hourElement.setAttribute("step", w.config.hourIncrement.toString()), w.minuteElement.setAttribute("step", w.config.minuteIncrement.toString()), w.hourElement.setAttribute("min", w.config.time_24hr ? "0" : "1"), w.hourElement.setAttribute("max", w.config.time_24hr ? "23" : "12"), w.hourElement.setAttribute("maxlength", "2"), w.minuteElement.setAttribute("min", "0"), w.minuteElement.setAttribute("max", "59"), w.minuteElement.setAttribute("maxlength", "2"), w.timeContainer.appendChild(t), w.timeContainer.appendChild(n), w.timeContainer.appendChild(a), w.config.time_24hr && w.timeContainer.classList.add("time24hr");
                        if (w.config.enableSeconds) {
                            w.timeContainer.classList.add("hasSeconds");
                            var i = m("flatpickr-second");
                            w.secondElement = i.getElementsByTagName("input")[0], w.secondElement.value = o(w.latestSelectedDateObj ? w.latestSelectedDateObj.getSeconds() : e.seconds), w.secondElement.setAttribute("step", w.minuteElement.getAttribute("step")), w.secondElement.setAttribute("min", "0"), w.secondElement.setAttribute("max", "59"), w.secondElement.setAttribute("maxlength", "2"), w.timeContainer.appendChild(d("span", "flatpickr-time-separator", ":")), w.timeContainer.appendChild(i)
                        }
                        w.config.time_24hr || (w.amPM = d("span", "flatpickr-am-pm", w.l10n.amPM[r((w.latestSelectedDateObj ? w.hourElement.value : w.config.defaultHour) > 11)]), w.amPM.title = w.l10n.toggleTitle, w.amPM.tabIndex = -1, w.timeContainer.appendChild(w.amPM));
                        return w.timeContainer
                    }());
                    s(w.calendarContainer, "rangeMode", "range" === w.config.mode), s(w.calendarContainer, "animate", !0 === w.config.animate), s(w.calendarContainer, "multiMonth", w.config.showMonths > 1), w.calendarContainer.appendChild(e);
                    var i = void 0 !== w.config.appendTo && void 0 !== w.config.appendTo.nodeType;
                    if ((w.config.inline || w.config.static) && (w.calendarContainer.classList.add(w.config.inline ? "inline" : "static"), w.config.inline && (!i && w.element.parentNode ? w.element.parentNode.insertBefore(w.calendarContainer, w._input.nextSibling) : void 0 !== w.config.appendTo && w.config.appendTo.appendChild(w.calendarContainer)), w.config.static)) {
                        var l = d("div", "flatpickr-wrapper");
                        w.element.parentNode && w.element.parentNode.insertBefore(l, w.element), l.appendChild(w.element), w.altInput && l.appendChild(w.altInput), l.appendChild(w.calendarContainer)
                    }
                    w.config.static || w.config.inline || (void 0 !== w.config.appendTo ? w.config.appendTo : window.document.body).appendChild(w.calendarContainer)
                }(),
                function() {
                    w.config.wrap && ["open", "close", "toggle", "clear"].forEach((function(e) { Array.prototype.forEach.call(w.element.querySelectorAll("[data-" + e + "]"), (function(n) { return P(n, "click", w[e]) })) }));
                    if (w.isMobile) return void

                    function() {
                        var e = w.config.enableTime ? w.config.noCalendar ? "time" : "datetime-local" : "date";
                        w.mobileInput = d("input", w.input.className + " flatpickr-mobile"), w.mobileInput.tabIndex = 1, w.mobileInput.type = e, w.mobileInput.disabled = w.input.disabled, w.mobileInput.required = w.input.required, w.mobileInput.placeholder = w.input.placeholder, w.mobileFormatStr = "datetime-local" === e ? "Y-m-d\\TH:i:S" : "date" === e ? "Y-m-d" : "H:i:S", w.selectedDates.length > 0 && (w.mobileInput.defaultValue = w.mobileInput.value = w.formatDate(w.selectedDates[0], w.mobileFormatStr));
                        w.config.minDate && (w.mobileInput.min = w.formatDate(w.config.minDate, "Y-m-d"));
                        w.config.maxDate && (w.mobileInput.max = w.formatDate(w.config.maxDate, "Y-m-d"));
                        w.input.getAttribute("step") && (w.mobileInput.step = String(w.input.getAttribute("step")));
                        w.input.type = "hidden", void 0 !== w.altInput && (w.altInput.type = "hidden");
                        try { w.input.parentNode && w.input.parentNode.insertBefore(w.mobileInput, w.input.nextSibling) } catch (e) {}
                        P(w.mobileInput, "change", (function(e) { w.setDate(g(e).value, !1, w.mobileFormatStr), De("onChange"), De("onClose") }))
                    }();
                    var e = l(re, 50);
                    w._debouncedChange = l(Y, 300), w.daysContainer && !/iPhone|iPad|iPod/i.test(navigator.userAgent) && P(w.daysContainer, "mouseover", (function(e) { "range" === w.config.mode && oe(g(e)) }));
                    P(w._input, "keydown", ie), void 0 !== w.calendarContainer && P(w.calendarContainer, "keydown", ie);
                    w.config.inline || w.config.static || P(window, "resize", e);
                    void 0 !== window.ontouchstart ? P(window.document, "touchstart", X) : P(window.document, "mousedown", X);
                    P(window.document, "focus", X, { capture: !0 }), !0 === w.config.clickOpens && (P(w._input, "focus", w.open), P(w._input, "click", w.open));
                    void 0 !== w.daysContainer && (P(w.monthNav, "click", xe), P(w.monthNav, ["keyup", "increment"], N), P(w.daysContainer, "click", me));
                    if (void 0 !== w.timeContainer && void 0 !== w.minuteElement && void 0 !== w.hourElement) {
                        var n = function(e) { return g(e).select() };
                        P(w.timeContainer, ["increment"], _), P(w.timeContainer, "blur", _, { capture: !0 }), P(w.timeContainer, "click", H), P([w.hourElement, w.minuteElement], ["focus", "click"], n), void 0 !== w.secondElement && P(w.secondElement, "focus", (function() { return w.secondElement && w.secondElement.select() })), void 0 !== w.amPM && P(w.amPM, "click", (function(e) { _(e) }))
                    }
                    w.config.allowInput && P(w._input, "blur", ae)
                }(), (w.selectedDates.length || w.config.noCalendar) && (w.config.enableTime && F(w.config.noCalendar ? w.latestSelectedDateObj : void 0), ye(!1)), S();
            var n = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            !w.isMobile && n && de(), De("onReady")
        }(), w
    }

    function T(e, n) {
        for (var t = Array.prototype.slice.call(e).filter((function(e) { return e instanceof HTMLElement })), a = [], i = 0; i < t.length; i++) {
            var o = t[i];
            try {
                if (null !== o.getAttribute("data-fp-omit")) continue;
                void 0 !== o._flatpickr && (o._flatpickr.destroy(), o._flatpickr = void 0), o._flatpickr = k(o, n || {}), a.push(o._flatpickr)
            } catch (e) { console.error(e) }
        }
        return 1 === a.length ? a[0] : a
    }
    "undefined" != typeof HTMLElement && "undefined" != typeof HTMLCollection && "undefined" != typeof NodeList && (HTMLCollection.prototype.flatpickr = NodeList.prototype.flatpickr = function(e) { return T(this, e) }, HTMLElement.prototype.flatpickr = function(e) { return T([this], e) });
    var I = function(e, n) { return "string" == typeof e ? T(window.document.querySelectorAll(e), n) : e instanceof Node ? T([e], n) : T(e, n) };
    return I.defaultConfig = {}, I.l10ns = { en: e({}, i), default: e({}, i) }, I.localize = function(n) { I.l10ns.default = e(e({}, I.l10ns.default), n) }, I.setDefaults = function(n) { I.defaultConfig = e(e({}, I.defaultConfig), n) }, I.parseDate = C({}), I.formatDate = b({}), I.compareDates = M, "undefined" != typeof jQuery && void 0 !== jQuery.fn && (jQuery.fn.flatpickr = function(e) { return T(this, e) }), Date.prototype.fp_incr = function(e) { return new Date(this.getFullYear(), this.getMonth(), this.getDate() + ("string" == typeof e ? parseInt(e, 10) : e)) }, "undefined" != typeof window && (window.flatpickr = I), I
}));


/* ====================================
=======================================
    Range Slider Js 
=======================================
=======================================*/

/*! nouislider - 8.5.1 - 2016-04-24 16:00:29 */

! function(a) { "function" == typeof define && define.amd ? define([], a) : "object" == typeof exports ? module.exports = a() : window.noUiSlider = a() }(function() {
    "use strict";

    function a(a) { return a.filter(function(a) { return this[a] ? !1 : this[a] = !0 }, {}) }

    function b(a, b) { return Math.round(a / b) * b }

    function c(a) {
        var b = a.getBoundingClientRect(),
            c = a.ownerDocument,
            d = c.documentElement,
            e = l();
        return /webkit.*Chrome.*Mobile/i.test(navigator.userAgent) && (e.x = 0), { top: b.top + e.y - d.clientTop, left: b.left + e.x - d.clientLeft }
    }

    function d(a) { return "number" == typeof a && !isNaN(a) && isFinite(a) }

    function e(a, b, c) { i(a, b), setTimeout(function() { j(a, b) }, c) }

    function f(a) { return Math.max(Math.min(a, 100), 0) }

    function g(a) { return Array.isArray(a) ? a : [a] }

    function h(a) { var b = a.split("."); return b.length > 1 ? b[1].length : 0 }

    function i(a, b) { a.classList ? a.classList.add(b) : a.className += " " + b }

    function j(a, b) { a.classList ? a.classList.remove(b) : a.className = a.className.replace(new RegExp("(^|\\b)" + b.split(" ").join("|") + "(\\b|$)", "gi"), " ") }

    function k(a, b) { return a.classList ? a.classList.contains(b) : new RegExp("\\b" + b + "\\b").test(a.className) }

    function l() {
        var a = void 0 !== window.pageXOffset,
            b = "CSS1Compat" === (document.compatMode || ""),
            c = a ? window.pageXOffset : b ? document.documentElement.scrollLeft : document.body.scrollLeft,
            d = a ? window.pageYOffset : b ? document.documentElement.scrollTop : document.body.scrollTop;
        return { x: c, y: d }
    }

    function m() { return window.navigator.pointerEnabled ? { start: "pointerdown", move: "pointermove", end: "pointerup" } : window.navigator.msPointerEnabled ? { start: "MSPointerDown", move: "MSPointerMove", end: "MSPointerUp" } : { start: "mousedown touchstart", move: "mousemove touchmove", end: "mouseup touchend" } }

    function n(a, b) { return 100 / (b - a) }

    function o(a, b) { return 100 * b / (a[1] - a[0]) }

    function p(a, b) { return o(a, a[0] < 0 ? b + Math.abs(a[0]) : b - a[0]) }

    function q(a, b) { return b * (a[1] - a[0]) / 100 + a[0] }

    function r(a, b) { for (var c = 1; a >= b[c];) c += 1; return c }

    function s(a, b, c) { if (c >= a.slice(-1)[0]) return 100; var d, e, f, g, h = r(c, a); return d = a[h - 1], e = a[h], f = b[h - 1], g = b[h], f + p([d, e], c) / n(f, g) }

    function t(a, b, c) { if (c >= 100) return a.slice(-1)[0]; var d, e, f, g, h = r(c, b); return d = a[h - 1], e = a[h], f = b[h - 1], g = b[h], q([d, e], (c - f) * n(f, g)) }

    function u(a, c, d, e) { if (100 === e) return e; var f, g, h = r(e, a); return d ? (f = a[h - 1], g = a[h], e - f > (g - f) / 2 ? g : f) : c[h - 1] ? a[h - 1] + b(e - a[h - 1], c[h - 1]) : e }

    function v(a, b, c) {
        var e;
        if ("number" == typeof b && (b = [b]), "[object Array]" !== Object.prototype.toString.call(b)) throw new Error("noUiSlider: 'range' contains invalid value.");
        if (e = "min" === a ? 0 : "max" === a ? 100 : parseFloat(a), !d(e) || !d(b[0])) throw new Error("noUiSlider: 'range' value isn't numeric.");
        c.xPct.push(e), c.xVal.push(b[0]), e ? c.xSteps.push(isNaN(b[1]) ? !1 : b[1]) : isNaN(b[1]) || (c.xSteps[0] = b[1])
    }

    function w(a, b, c) { return b ? void(c.xSteps[a] = o([c.xVal[a], c.xVal[a + 1]], b) / n(c.xPct[a], c.xPct[a + 1])) : !0 }

    function x(a, b, c, d) { this.xPct = [], this.xVal = [], this.xSteps = [d || !1], this.xNumSteps = [!1], this.snap = b, this.direction = c; var e, f = []; for (e in a) a.hasOwnProperty(e) && f.push([a[e], e]); for (f.length && "object" == typeof f[0][0] ? f.sort(function(a, b) { return a[0][0] - b[0][0] }) : f.sort(function(a, b) { return a[0] - b[0] }), e = 0; e < f.length; e++) v(f[e][1], f[e][0], this); for (this.xNumSteps = this.xSteps.slice(0), e = 0; e < this.xNumSteps.length; e++) w(e, this.xNumSteps[e], this) }

    function y(a, b) {
        if (!d(b)) throw new Error("noUiSlider: 'step' is not numeric.");
        a.singleStep = b
    }

    function z(a, b) {
        if ("object" != typeof b || Array.isArray(b)) throw new Error("noUiSlider: 'range' is not an object.");
        if (void 0 === b.min || void 0 === b.max) throw new Error("noUiSlider: Missing 'min' or 'max' in 'range'.");
        if (b.min === b.max) throw new Error("noUiSlider: 'range' 'min' and 'max' cannot be equal.");
        a.spectrum = new x(b, a.snap, a.dir, a.singleStep)
    }

    function A(a, b) {
        if (b = g(b), !Array.isArray(b) || !b.length || b.length > 2) throw new Error("noUiSlider: 'start' option is incorrect.");
        a.handles = b.length, a.start = b
    }

    function B(a, b) { if (a.snap = b, "boolean" != typeof b) throw new Error("noUiSlider: 'snap' option must be a boolean.") }

    function C(a, b) { if (a.animate = b, "boolean" != typeof b) throw new Error("noUiSlider: 'animate' option must be a boolean.") }

    function D(a, b) { if (a.animationDuration = b, "number" != typeof b) throw new Error("noUiSlider: 'animationDuration' option must be a number.") }

    function E(a, b) {
        if ("lower" === b && 1 === a.handles) a.connect = 1;
        else if ("upper" === b && 1 === a.handles) a.connect = 2;
        else if (b === !0 && 2 === a.handles) a.connect = 3;
        else {
            if (b !== !1) throw new Error("noUiSlider: 'connect' option doesn't match handle count.");
            a.connect = 0
        }
    }

    function F(a, b) {
        switch (b) {
            case "horizontal":
                a.ort = 0;
                break;
            case "vertical":
                a.ort = 1;
                break;
            default:
                throw new Error("noUiSlider: 'orientation' option is invalid.")
        }
    }

    function G(a, b) { if (!d(b)) throw new Error("noUiSlider: 'margin' option must be numeric."); if (0 !== b && (a.margin = a.spectrum.getMargin(b), !a.margin)) throw new Error("noUiSlider: 'margin' option is only supported on linear sliders.") }

    function H(a, b) { if (!d(b)) throw new Error("noUiSlider: 'limit' option must be numeric."); if (a.limit = a.spectrum.getMargin(b), !a.limit) throw new Error("noUiSlider: 'limit' option is only supported on linear sliders.") }

    function I(a, b) {
        switch (b) {
            case "ltr":
                a.dir = 0;
                break;
            case "rtl":
                a.dir = 1, a.connect = [0, 2, 1, 3][a.connect];
                break;
            default:
                throw new Error("noUiSlider: 'direction' option was not recognized.")
        }
    }

    function J(a, b) {
        if ("string" != typeof b) throw new Error("noUiSlider: 'behaviour' must be a string containing options.");
        var c = b.indexOf("tap") >= 0,
            d = b.indexOf("drag") >= 0,
            e = b.indexOf("fixed") >= 0,
            f = b.indexOf("snap") >= 0,
            g = b.indexOf("hover") >= 0;
        if (d && !a.connect) throw new Error("noUiSlider: 'drag' behaviour must be used with 'connect': true.");
        a.events = { tap: c || f, drag: d, fixed: e, snap: f, hover: g }
    }

    function K(a, b) {
        var c;
        if (b !== !1)
            if (b === !0)
                for (a.tooltips = [], c = 0; c < a.handles; c++) a.tooltips.push(!0);
            else {
                if (a.tooltips = g(b), a.tooltips.length !== a.handles) throw new Error("noUiSlider: must pass a formatter for all handles.");
                a.tooltips.forEach(function(a) { if ("boolean" != typeof a && ("object" != typeof a || "function" != typeof a.to)) throw new Error("noUiSlider: 'tooltips' must be passed a formatter or 'false'.") })
            }
    }

    function L(a, b) { if (a.format = b, "function" == typeof b.to && "function" == typeof b.from) return !0; throw new Error("noUiSlider: 'format' requires 'to' and 'from' methods.") }

    function M(a, b) {
        if (void 0 !== b && "string" != typeof b && b !== !1) throw new Error("noUiSlider: 'cssPrefix' must be a string or `false`.");
        a.cssPrefix = b
    }

    function N(a, b) { if (void 0 !== b && "object" != typeof b) throw new Error("noUiSlider: 'cssClasses' must be an object."); if ("string" == typeof a.cssPrefix) { a.cssClasses = {}; for (var c in b) b.hasOwnProperty(c) && (a.cssClasses[c] = a.cssPrefix + b[c]) } else a.cssClasses = b }

    function O(a) {
        var b, c = { margin: 0, limit: 0, animate: !0, animationDuration: 300, format: R };
        b = { step: { r: !1, t: y }, start: { r: !0, t: A }, connect: { r: !0, t: E }, direction: { r: !0, t: I }, snap: { r: !1, t: B }, animate: { r: !1, t: C }, animationDuration: { r: !1, t: D }, range: { r: !0, t: z }, orientation: { r: !1, t: F }, margin: { r: !1, t: G }, limit: { r: !1, t: H }, behaviour: { r: !0, t: J }, format: { r: !1, t: L }, tooltips: { r: !1, t: K }, cssPrefix: { r: !1, t: M }, cssClasses: { r: !1, t: N } };
        var d = { connect: !1, direction: "ltr", behaviour: "tap", orientation: "horizontal", cssPrefix: "noUi-", cssClasses: { target: "target", base: "base", origin: "origin", handle: "handle", handleLower: "handle-lower", handleUpper: "handle-upper", horizontal: "horizontal", vertical: "vertical", background: "background", connect: "connect", ltr: "ltr", rtl: "rtl", draggable: "draggable", drag: "state-drag", tap: "state-tap", active: "active", stacking: "stacking", tooltip: "tooltip", pips: "pips", pipsHorizontal: "pips-horizontal", pipsVertical: "pips-vertical", marker: "marker", markerHorizontal: "marker-horizontal", markerVertical: "marker-vertical", markerNormal: "marker-normal", markerLarge: "marker-large", markerSub: "marker-sub", value: "value", valueHorizontal: "value-horizontal", valueVertical: "value-vertical", valueNormal: "value-normal", valueLarge: "value-large", valueSub: "value-sub" } };
        return Object.keys(b).forEach(function(e) {
            if (void 0 === a[e] && void 0 === d[e]) { if (b[e].r) throw new Error("noUiSlider: '" + e + "' is required."); return !0 }
            b[e].t(c, void 0 === a[e] ? d[e] : a[e])
        }), c.pips = a.pips, c.style = c.ort ? "top" : "left", c
    }

    function P(b, d, n) {
        function o(a, b, c) {
            var d = a + b[0],
                e = a + b[1];
            return c ? (0 > d && (e += Math.abs(d)), e > 100 && (d -= e - 100), [f(d), f(e)]) : [d, e]
        }

        function p(a, b) {
            a.preventDefault();
            var c, d, e = 0 === a.type.indexOf("touch"),
                f = 0 === a.type.indexOf("mouse"),
                g = 0 === a.type.indexOf("pointer"),
                h = a;
            return 0 === a.type.indexOf("MSPointer") && (g = !0), e && (c = a.changedTouches[0].pageX, d = a.changedTouches[0].pageY), b = b || l(), (f || g) && (c = a.clientX + b.x, d = a.clientY + b.y), h.pageOffset = b, h.points = [c, d], h.cursor = f || g, h
        }

        function q(a, b) {
            var c = document.createElement("div"),
                e = document.createElement("div"),
                f = [d.cssClasses.handleLower, d.cssClasses.handleUpper];
            return a && f.reverse(), i(e, d.cssClasses.handle), i(e, f[b]), i(c, d.cssClasses.origin), c.appendChild(e), c
        }

        function r(a, b, c) {
            switch (a) {
                case 1:
                    i(b, d.cssClasses.connect), i(c[0], d.cssClasses.background);
                    break;
                case 3:
                    i(c[1], d.cssClasses.background);
                case 2:
                    i(c[0], d.cssClasses.connect);
                case 0:
                    i(b, d.cssClasses.background)
            }
        }

        function s(a, b, c) { var d, e = []; for (d = 0; a > d; d += 1) e.push(c.appendChild(q(b, d))); return e }

        function t(a, b, c) { i(c, d.cssClasses.target), 0 === a ? i(c, d.cssClasses.ltr) : i(c, d.cssClasses.rtl), 0 === b ? i(c, d.cssClasses.horizontal) : i(c, d.cssClasses.vertical); var e = document.createElement("div"); return i(e, d.cssClasses.base), c.appendChild(e), e }

        function u(a, b) { if (!d.tooltips[b]) return !1; var c = document.createElement("div"); return c.className = d.cssClasses.tooltip, a.firstChild.appendChild(c) }

        function v() {
            d.dir && d.tooltips.reverse();
            var a = W.map(u);
            d.dir && (a.reverse(), d.tooltips.reverse()), S("update", function(b, c, e) { a[c] && (a[c].innerHTML = d.tooltips[c] === !0 ? b[c] : d.tooltips[c].to(e[c])) })
        }

        function w(a, b, c) {
            if ("range" === a || "steps" === a) return _.xVal;
            if ("count" === a) {
                var d, e = 100 / (b - 1),
                    f = 0;
                for (b = [];
                    (d = f++ * e) <= 100;) b.push(d);
                a = "positions"
            }
            return "positions" === a ? b.map(function(a) { return _.fromStepping(c ? _.getStep(a) : a) }) : "values" === a ? c ? b.map(function(a) { return _.fromStepping(_.getStep(_.toStepping(a))) }) : b : void 0
        }

        function x(b, c, d) {
            function e(a, b) { return (a + b).toFixed(7) / 1 }
            var f = _.direction,
                g = {},
                h = _.xVal[0],
                i = _.xVal[_.xVal.length - 1],
                j = !1,
                k = !1,
                l = 0;
            return _.direction = 0, d = a(d.slice().sort(function(a, b) { return a - b })), d[0] !== h && (d.unshift(h), j = !0), d[d.length - 1] !== i && (d.push(i), k = !0), d.forEach(function(a, f) {
                var h, i, m, n, o, p, q, r, s, t, u = a,
                    v = d[f + 1];
                if ("steps" === c && (h = _.xNumSteps[f]), h || (h = v - u), u !== !1 && void 0 !== v)
                    for (i = u; v >= i; i = e(i, h)) {
                        for (n = _.toStepping(i), o = n - l, r = o / b, s = Math.round(r), t = o / s, m = 1; s >= m; m += 1) p = l + m * t, g[p.toFixed(5)] = ["x", 0];
                        q = d.indexOf(i) > -1 ? 1 : "steps" === c ? 2 : 0, !f && j && (q = 0), i === v && k || (g[n.toFixed(5)] = [i, q]), l = n
                    }
            }), _.direction = f, g
        }

        function y(a, b, c) {
            function e(a, b) {
                var c = b === d.cssClasses.value,
                    e = c ? m : n,
                    f = c ? k : l;
                return b + " " + e[d.ort] + " " + f[a]
            }

            function f(a, b, c) { return 'class="' + e(c[1], b) + '" style="' + d.style + ": " + a + '%"' }

            function g(a, e) { _.direction && (a = 100 - a), e[1] = e[1] && b ? b(e[0], e[1]) : e[1], j += "<div " + f(a, d.cssClasses.marker, e) + "></div>", e[1] && (j += "<div " + f(a, d.cssClasses.value, e) + ">" + c.to(e[0]) + "</div>") }
            var h = document.createElement("div"),
                j = "",
                k = [d.cssClasses.valueNormal, d.cssClasses.valueLarge, d.cssClasses.valueSub],
                l = [d.cssClasses.markerNormal, d.cssClasses.markerLarge, d.cssClasses.markerSub],
                m = [d.cssClasses.valueHorizontal, d.cssClasses.valueVertical],
                n = [d.cssClasses.markerHorizontal, d.cssClasses.markerVertical];
            return i(h, d.cssClasses.pips), i(h, 0 === d.ort ? d.cssClasses.pipsHorizontal : d.cssClasses.pipsVertical), Object.keys(a).forEach(function(b) { g(b, a[b]) }), h.innerHTML = j, h
        }

        function z(a) {
            var b = a.mode,
                c = a.density || 1,
                d = a.filter || !1,
                e = a.values || !1,
                f = a.stepped || !1,
                g = w(b, e, f),
                h = x(c, b, g),
                i = a.format || { to: Math.round };
            return Z.appendChild(y(h, d, i))
        }

        function A() {
            var a = V.getBoundingClientRect(),
                b = "offset" + ["Width", "Height"][d.ort];
            return 0 === d.ort ? a.width || V[b] : a.height || V[b]
        }

        function B(a, b, c) {
            var e;
            for (e = 0; e < d.handles; e++)
                if (-1 === $[e]) return;
            void 0 !== b && 1 !== d.handles && (b = Math.abs(b - d.dir)), Object.keys(ba).forEach(function(d) {
                var e = d.split(".")[0];
                a === e && ba[d].forEach(function(a) { a.call(X, g(P()), b, g(C(Array.prototype.slice.call(aa))), c || !1, $) })
            })
        }

        function C(a) { return 1 === a.length ? a[0] : d.dir ? a.reverse() : a }

        function D(a, b, c, e) {
            var f = function(b) { return Z.hasAttribute("disabled") ? !1 : k(Z, d.cssClasses.tap) ? !1 : (b = p(b, e.pageOffset), a === Y.start && void 0 !== b.buttons && b.buttons > 1 ? !1 : e.hover && b.buttons ? !1 : (b.calcPoint = b.points[d.ort], void c(b, e))) },
                g = [];
            return a.split(" ").forEach(function(a) { b.addEventListener(a, f, !1), g.push([a, f]) }), g
        }

        function E(a, b) {
            if (-1 === navigator.appVersion.indexOf("MSIE 9") && 0 === a.buttons && 0 !== b.buttonsProperty) return F(a, b);
            var c, d, e = b.handles || W,
                f = !1,
                g = 100 * (a.calcPoint - b.start) / b.baseSize,
                h = e[0] === W[0] ? 0 : 1;
            if (c = o(g, b.positions, e.length > 1), f = L(e[0], c[h], 1 === e.length), e.length > 1) {
                if (f = L(e[1], c[h ? 0 : 1], !1) || f)
                    for (d = 0; d < b.handles.length; d++) B("slide", d)
            } else f && B("slide", h)
        }

        function F(a, b) {
            var c = V.querySelector("." + d.cssClasses.active),
                e = b.handles[0] === W[0] ? 0 : 1;
            null !== c && j(c, d.cssClasses.active), a.cursor && (document.body.style.cursor = "", document.body.removeEventListener("selectstart", document.body.noUiListener));
            var f = document.documentElement;
            f.noUiListeners.forEach(function(a) { f.removeEventListener(a[0], a[1]) }), j(Z, d.cssClasses.drag), B("set", e), B("change", e), void 0 !== b.handleNumber && B("end", b.handleNumber)
        }

        function G(a, b) { "mouseout" === a.type && "HTML" === a.target.nodeName && null === a.relatedTarget && F(a, b) }

        function H(a, b) {
            var c = document.documentElement;
            if (1 === b.handles.length) {
                if (b.handles[0].hasAttribute("disabled")) return !1;
                i(b.handles[0].children[0], d.cssClasses.active)
            }
            a.preventDefault(), a.stopPropagation();
            var e = D(Y.move, c, E, { start: a.calcPoint, baseSize: A(), pageOffset: a.pageOffset, handles: b.handles, handleNumber: b.handleNumber, buttonsProperty: a.buttons, positions: [$[0], $[W.length - 1]] }),
                f = D(Y.end, c, F, { handles: b.handles, handleNumber: b.handleNumber }),
                g = D("mouseout", c, G, { handles: b.handles, handleNumber: b.handleNumber });
            if (c.noUiListeners = e.concat(f, g), a.cursor) {
                document.body.style.cursor = getComputedStyle(a.target).cursor, W.length > 1 && i(Z, d.cssClasses.drag);
                var h = function() { return !1 };
                document.body.noUiListener = h, document.body.addEventListener("selectstart", h, !1)
            }
            void 0 !== b.handleNumber && B("start", b.handleNumber)
        }

        function I(a) {
            var b, f, g = a.calcPoint,
                h = 0;
            return a.stopPropagation(), W.forEach(function(a) { h += c(a)[d.style] }), b = h / 2 > g || 1 === W.length ? 0 : 1, W[b].hasAttribute("disabled") && (b = b ? 0 : 1), g -= c(V)[d.style], f = 100 * g / A(), d.events.snap || e(Z, d.cssClasses.tap, d.animationDuration), W[b].hasAttribute("disabled") ? !1 : (L(W[b], f), B("slide", b, !0), B("set", b, !0), B("change", b, !0), void(d.events.snap && H(a, { handles: [W[b]] })))
        }

        function J(a) {
            var b = a.calcPoint - c(V)[d.style],
                e = _.getStep(100 * b / A()),
                f = _.fromStepping(e);
            Object.keys(ba).forEach(function(a) { "hover" === a.split(".")[0] && ba[a].forEach(function(a) { a.call(X, f) }) })
        }

        function K(a) {
            if (a.fixed || W.forEach(function(a, b) { D(Y.start, a.children[0], H, { handles: [a], handleNumber: b }) }), a.tap && D(Y.start, V, I, { handles: W }), a.hover && D(Y.move, V, J, { hover: !0 }), a.drag) {
                var b = [V.querySelector("." + d.cssClasses.connect)];
                i(b[0], d.cssClasses.draggable), a.fixed && b.push(W[b[0] === W[0] ? 1 : 0].children[0]), b.forEach(function(a) { D(Y.start, a, H, { handles: W }) })
            }
        }

        function L(a, b, c) {
            var e = a !== W[0] ? 1 : 0,
                g = $[0] + d.margin,
                h = $[1] - d.margin,
                k = $[0] + d.limit,
                l = $[1] - d.limit;
            return W.length > 1 && (b = e ? Math.max(b, g) : Math.min(b, h)), c !== !1 && d.limit && W.length > 1 && (b = e ? Math.min(b, k) : Math.max(b, l)), b = _.getStep(b), b = f(b), b === $[e] ? !1 : (window.requestAnimationFrame ? window.requestAnimationFrame(function() { a.style[d.style] = b + "%" }) : a.style[d.style] = b + "%", a.previousSibling || (j(a, d.cssClasses.stacking), b > 50 && i(a, d.cssClasses.stacking)), $[e] = b, aa[e] = _.fromStepping(b), B("update", e), !0)
        }

        function M(a, b) { var c, e, f; for (d.limit && (a += 1), c = 0; a > c; c += 1) e = c % 2, f = b[e], null !== f && f !== !1 && ("number" == typeof f && (f = String(f)), f = d.format.from(f), (f === !1 || isNaN(f) || L(W[e], _.toStepping(f), c === 3 - d.dir) === !1) && B("update", e)) }

        function N(a, b) { var c, f, h = g(a); for (b = void 0 === b ? !0 : !!b, d.dir && d.handles > 1 && h.reverse(), d.animate && -1 !== $[0] && e(Z, d.cssClasses.tap, d.animationDuration), c = W.length > 1 ? 3 : 1, 1 === h.length && (c = 1), M(c, h), f = 0; f < W.length; f++) null !== h[f] && b && B("set", f) }

        function P() { var a, b = []; for (a = 0; a < d.handles; a += 1) b[a] = d.format.to(aa[a]); return C(b) }

        function Q() {
            for (var a in d.cssClasses) d.cssClasses.hasOwnProperty(a) && j(Z, d.cssClasses[a]);
            for (; Z.firstChild;) Z.removeChild(Z.firstChild);
            delete Z.noUiSlider
        }

        function R() {
            var a = $.map(function(a, b) {
                var c = _.getApplicableStep(a),
                    d = h(String(c[2])),
                    e = aa[b],
                    f = 100 === a ? null : c[2],
                    g = Number((e - c[2]).toFixed(d)),
                    i = 0 === a ? null : g >= c[1] ? c[2] : c[0] || !1;
                return [i, f]
            });
            return C(a)
        }

        function S(a, b) { ba[a] = ba[a] || [], ba[a].push(b), "update" === a.split(".")[0] && W.forEach(function(a, b) { B("update", b) }) }

        function T(a) {
            var b = a && a.split(".")[0],
                c = b && a.substring(b.length);
            Object.keys(ba).forEach(function(a) {
                var d = a.split(".")[0],
                    e = a.substring(d.length);
                b && b !== d || c && c !== e || delete ba[a]
            })
        }

        function U(a, b) {
            var c = P(),
                e = O({ start: [0, 0], margin: a.margin, limit: a.limit, step: void 0 === a.step ? d.singleStep : a.step, range: a.range, animate: a.animate, snap: void 0 === a.snap ? d.snap : a.snap });
            ["margin", "limit", "range", "animate"].forEach(function(b) { void 0 !== a[b] && (d[b] = a[b]) }), e.spectrum.direction = _.direction, _ = e.spectrum, $ = [-1, -1], N(a.start || c, b)
        }
        var V, W, X, Y = m(),
            Z = b,
            $ = [-1, -1],
            _ = d.spectrum,
            aa = [],
            ba = {};
        if (Z.noUiSlider) throw new Error("Slider was already initialized.");
        return V = t(d.dir, d.ort, Z), W = s(d.handles, d.dir, V), r(d.connect, Z, W), d.pips && z(d.pips), d.tooltips && v(), X = { destroy: Q, steps: R, on: S, off: T, get: P, set: N, updateOptions: U, options: n, target: Z, pips: z }, K(d.events), X
    }

    function Q(a, b) {
        if (!a.nodeName) throw new Error("noUiSlider.create requires a single element.");
        var c = O(b, a),
            d = P(a, c, b);
        return d.set(c.start), a.noUiSlider = d, d
    }
    x.prototype.getMargin = function(a) { return 2 === this.xPct.length ? o(this.xVal, a) : !1 }, x.prototype.toStepping = function(a) { return a = s(this.xVal, this.xPct, a), this.direction && (a = 100 - a), a }, x.prototype.fromStepping = function(a) { return this.direction && (a = 100 - a), t(this.xVal, this.xPct, a) }, x.prototype.getStep = function(a) { return this.direction && (a = 100 - a), a = u(this.xPct, this.xSteps, this.snap, a), this.direction && (a = 100 - a), a }, x.prototype.getApplicableStep = function(a) {
        var b = r(a, this.xPct),
            c = 100 === a ? 2 : 1;
        return [this.xNumSteps[b - 2], this.xVal[b - c], this.xNumSteps[b - c]]
    }, x.prototype.convert = function(a) { return this.getStep(this.toStepping(a)) };
    var R = { to: function(a) { return void 0 !== a && a.toFixed(2) }, from: Number };
    return { create: Q }
});


/* ====================================
=======================================
    TellInput Js 
=======================================
=======================================*/

/*
 * International Telephone Input v17.0.8
 * https://github.com/jackocnr/intl-tel-input.git
 * Licensed under the MIT license
 */

// wrap in UMD
(function(factory) {
    if (typeof module === "object" && module.exports) module.exports = factory();
    else window.intlTelInput = factory();
})(function(undefined) {
    "use strict";
    return function() {
        // Array of country objects for the flag dropdown.
        // Here is the criteria for the plugin to support a given country/territory
        // - It has an iso2 code: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
        // - It has it's own country calling code (it is not a sub-region of another country): https://en.wikipedia.org/wiki/List_of_country_calling_codes
        // - It has a flag in the region-flags project: https://github.com/behdad/region-flags/tree/gh-pages/png
        // - It is supported by libphonenumber (it must be listed on this page): https://github.com/googlei18n/libphonenumber/blob/master/resources/ShortNumberMetadata.xml
        // Each country array has the following information:
        // [
        //    Country name,
        //    iso2 code,
        //    International dial code,
        //    Order (if >1 country with same dial code),
        //    Area codes
        // ]
        var allCountries = [
            ["Afghanistan (‫افغانستان‬‎)", "af", "93"],
            ["Albania (Shqipëri)", "al", "355"],
            ["Algeria (‫الجزائر‬‎)", "dz", "213"],
            ["American Samoa", "as", "1", 5, ["684"]],
            ["Andorra", "ad", "376"],
            ["Angola", "ao", "244"],
            ["Anguilla", "ai", "1", 6, ["264"]],
            ["Antigua and Barbuda", "ag", "1", 7, ["268"]],
            ["Argentina", "ar", "54"],
            ["Armenia (Հայաստան)", "am", "374"],
            ["Aruba", "aw", "297"],
            ["Australia", "au", "61", 0],
            ["Austria (Österreich)", "at", "43"],
            ["Azerbaijan (Azərbaycan)", "az", "994"],
            ["Bahamas", "bs", "1", 8, ["242"]],
            ["Bahrain (‫البحرين‬‎)", "bh", "973"],
            ["Bangladesh (বাংলাদেশ)", "bd", "880"],
            ["Barbados", "bb", "1", 9, ["246"]],
            ["Belarus (Беларусь)", "by", "375"],
            ["Belgium (België)", "be", "32"],
            ["Belize", "bz", "501"],
            ["Benin (Bénin)", "bj", "229"],
            ["Bermuda", "bm", "1", 10, ["441"]],
            ["Bhutan (འབྲུག)", "bt", "975"],
            ["Bolivia", "bo", "591"],
            ["Bosnia and Herzegovina (Босна и Херцеговина)", "ba", "387"],
            ["Botswana", "bw", "267"],
            ["Brazil (Brasil)", "br", "55"],
            ["British Indian Ocean Territory", "io", "246"],
            ["British Virgin Islands", "vg", "1", 11, ["284"]],
            ["Brunei", "bn", "673"],
            ["Bulgaria (България)", "bg", "359"],
            ["Burkina Faso", "bf", "226"],
            ["Burundi (Uburundi)", "bi", "257"],
            ["Cambodia (កម្ពុជា)", "kh", "855"],
            ["Cameroon (Cameroun)", "cm", "237"],
            ["Canada", "ca", "1", 1, ["204", "226", "236", "249", "250", "289", "306", "343", "365", "387", "403", "416", "418", "431", "437", "438", "450", "506", "514", "519", "548", "579", "581", "587", "604", "613", "639", "647", "672", "705", "709", "742", "778", "780", "782", "807", "819", "825", "867", "873", "902", "905"]],
            ["Cape Verde (Kabu Verdi)", "cv", "238"],
            ["Caribbean Netherlands", "bq", "599", 1, ["3", "4", "7"]],
            ["Cayman Islands", "ky", "1", 12, ["345"]],
            ["Central African Republic (République centrafricaine)", "cf", "236"],
            ["Chad (Tchad)", "td", "235"],
            ["Chile", "cl", "56"],
            ["China (中国)", "cn", "86"],
            ["Christmas Island", "cx", "61", 2, ["89164"]],
            ["Cocos (Keeling) Islands", "cc", "61", 1, ["89162"]],
            ["Colombia", "co", "57"],
            ["Comoros (‫جزر القمر‬‎)", "km", "269"],
            ["Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)", "cd", "243"],
            ["Congo (Republic) (Congo-Brazzaville)", "cg", "242"],
            ["Cook Islands", "ck", "682"],
            ["Costa Rica", "cr", "506"],
            ["Côte d’Ivoire", "ci", "225"],
            ["Croatia (Hrvatska)", "hr", "385"],
            ["Cuba", "cu", "53"],
            ["Curaçao", "cw", "599", 0],
            ["Cyprus (Κύπρος)", "cy", "357"],
            ["Czech Republic (Česká republika)", "cz", "420"],
            ["Denmark (Danmark)", "dk", "45"],
            ["Djibouti", "dj", "253"],
            ["Dominica", "dm", "1", 13, ["767"]],
            ["Dominican Republic (República Dominicana)", "do", "1", 2, ["809", "829", "849"]],
            ["Ecuador", "ec", "593"],
            ["Egypt (‫مصر‬‎)", "eg", "20"],
            ["El Salvador", "sv", "503"],
            ["Equatorial Guinea (Guinea Ecuatorial)", "gq", "240"],
            ["Eritrea", "er", "291"],
            ["Estonia (Eesti)", "ee", "372"],
            ["Eswatini", "sz", "268"],
            ["Ethiopia", "et", "251"],
            ["Falkland Islands (Islas Malvinas)", "fk", "500"],
            ["Faroe Islands (Føroyar)", "fo", "298"],
            ["Fiji", "fj", "679"],
            ["Finland (Suomi)", "fi", "358", 0],
            ["France", "fr", "33"],
            ["French Guiana (Guyane française)", "gf", "594"],
            ["French Polynesia (Polynésie française)", "pf", "689"],
            ["Gabon", "ga", "241"],
            ["Gambia", "gm", "220"],
            ["Georgia (საქართველო)", "ge", "995"],
            ["Germany (Deutschland)", "de", "49"],
            ["Ghana (Gaana)", "gh", "233"],
            ["Gibraltar", "gi", "350"],
            ["Greece (Ελλάδα)", "gr", "30"],
            ["Greenland (Kalaallit Nunaat)", "gl", "299"],
            ["Grenada", "gd", "1", 14, ["473"]],
            ["Guadeloupe", "gp", "590", 0],
            ["Guam", "gu", "1", 15, ["671"]],
            ["Guatemala", "gt", "502"],
            ["Guernsey", "gg", "44", 1, ["1481", "7781", "7839", "7911"]],
            ["Guinea (Guinée)", "gn", "224"],
            ["Guinea-Bissau (Guiné Bissau)", "gw", "245"],
            ["Guyana", "gy", "592"],
            ["Haiti", "ht", "509"],
            ["Honduras", "hn", "504"],
            ["Hong Kong (香港)", "hk", "852"],
            ["Hungary (Magyarország)", "hu", "36"],
            ["Iceland (Ísland)", "is", "354"],
            ["India (भारत)", "in", "91"],
            ["Indonesia", "id", "62"],
            ["Iran (‫ایران‬‎)", "ir", "98"],
            ["Iraq (‫العراق‬‎)", "iq", "964"],
            ["Ireland", "ie", "353"],
            ["Isle of Man", "im", "44", 2, ["1624", "74576", "7524", "7924", "7624"]],
            ["Israel (‫ישראל‬‎)", "il", "972"],
            ["Italy (Italia)", "it", "39", 0],
            ["Jamaica", "jm", "1", 4, ["876", "658"]],
            ["Japan (日本)", "jp", "81"],
            ["Jersey", "je", "44", 3, ["1534", "7509", "7700", "7797", "7829", "7937"]],
            ["Jordan (‫الأردن‬‎)", "jo", "962"],
            ["Kazakhstan (Казахстан)", "kz", "7", 1, ["33", "7"]],
            ["Kenya", "ke", "254"],
            ["Kiribati", "ki", "686"],
            ["Kosovo", "xk", "383"],
            ["Kuwait (‫الكويت‬‎)", "kw", "965"],
            ["Kyrgyzstan (Кыргызстан)", "kg", "996"],
            ["Laos (ລາວ)", "la", "856"],
            ["Latvia (Latvija)", "lv", "371"],
            ["Lebanon (‫لبنان‬‎)", "lb", "961"],
            ["Lesotho", "ls", "266"],
            ["Liberia", "lr", "231"],
            ["Libya (‫ليبيا‬‎)", "ly", "218"],
            ["Liechtenstein", "li", "423"],
            ["Lithuania (Lietuva)", "lt", "370"],
            ["Luxembourg", "lu", "352"],
            ["Macau (澳門)", "mo", "853"],
            ["Macedonia (FYROM) (Македонија)", "mk", "389"],
            ["Madagascar (Madagasikara)", "mg", "261"],
            ["Malawi", "mw", "265"],
            ["Malaysia", "my", "60"],
            ["Maldives", "mv", "960"],
            ["Mali", "ml", "223"],
            ["Malta", "mt", "356"],
            ["Marshall Islands", "mh", "692"],
            ["Martinique", "mq", "596"],
            ["Mauritania (‫موريتانيا‬‎)", "mr", "222"],
            ["Mauritius (Moris)", "mu", "230"],
            ["Mayotte", "yt", "262", 1, ["269", "639"]],
            ["Mexico (México)", "mx", "52"],
            ["Micronesia", "fm", "691"],
            ["Moldova (Republica Moldova)", "md", "373"],
            ["Monaco", "mc", "377"],
            ["Mongolia (Монгол)", "mn", "976"],
            ["Montenegro (Crna Gora)", "me", "382"],
            ["Montserrat", "ms", "1", 16, ["664"]],
            ["Morocco (‫المغرب‬‎)", "ma", "212", 0],
            ["Mozambique (Moçambique)", "mz", "258"],
            ["Myanmar (Burma) (မြန်မာ)", "mm", "95"],
            ["Namibia (Namibië)", "na", "264"],
            ["Nauru", "nr", "674"],
            ["Nepal (नेपाल)", "np", "977"],
            ["Netherlands (Nederland)", "nl", "31"],
            ["New Caledonia (Nouvelle-Calédonie)", "nc", "687"],
            ["New Zealand", "nz", "64"],
            ["Nicaragua", "ni", "505"],
            ["Niger (Nijar)", "ne", "227"],
            ["Nigeria", "ng", "234"],
            ["Niue", "nu", "683"],
            ["Norfolk Island", "nf", "672"],
            ["North Korea (조선 민주주의 인민 공화국)", "kp", "850"],
            ["Northern Mariana Islands", "mp", "1", 17, ["670"]],
            ["Norway (Norge)", "no", "47", 0],
            ["Oman (‫عُمان‬‎)", "om", "968"],
            ["Pakistan (‫پاکستان‬‎)", "pk", "92"],
            ["Palau", "pw", "680"],
            ["Palestine (‫فلسطين‬‎)", "ps", "970"],
            ["Panama (Panamá)", "pa", "507"],
            ["Papua New Guinea", "pg", "675"],
            ["Paraguay", "py", "595"],
            ["Peru (Perú)", "pe", "51"],
            ["Philippines", "ph", "63"],
            ["Poland (Polska)", "pl", "48"],
            ["Portugal", "pt", "351"],
            ["Puerto Rico", "pr", "1", 3, ["787", "939"]],
            ["Qatar (‫قطر‬‎)", "qa", "974"],
            ["Réunion (La Réunion)", "re", "262", 0],
            ["Romania (România)", "ro", "40"],
            ["Russia (Россия)", "ru", "7", 0],
            ["Rwanda", "rw", "250"],
            ["Saint Barthélemy", "bl", "590", 1],
            ["Saint Helena", "sh", "290"],
            ["Saint Kitts and Nevis", "kn", "1", 18, ["869"]],
            ["Saint Lucia", "lc", "1", 19, ["758"]],
            ["Saint Martin (Saint-Martin (partie française))", "mf", "590", 2],
            ["Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)", "pm", "508"],
            ["Saint Vincent and the Grenadines", "vc", "1", 20, ["784"]],
            ["Samoa", "ws", "685"],
            ["San Marino", "sm", "378"],
            ["São Tomé and Príncipe (São Tomé e Príncipe)", "st", "239"],
            ["Saudi Arabia (‫المملكة العربية السعودية‬‎)", "sa", "966"],
            ["Senegal (Sénégal)", "sn", "221"],
            ["Serbia (Србија)", "rs", "381"],
            ["Seychelles", "sc", "248"],
            ["Sierra Leone", "sl", "232"],
            ["Singapore", "sg", "65"],
            ["Sint Maarten", "sx", "1", 21, ["721"]],
            ["Slovakia (Slovensko)", "sk", "421"],
            ["Slovenia (Slovenija)", "si", "386"],
            ["Solomon Islands", "sb", "677"],
            ["Somalia (Soomaaliya)", "so", "252"],
            ["South Africa", "za", "27"],
            ["South Korea (대한민국)", "kr", "82"],
            ["South Sudan (‫جنوب السودان‬‎)", "ss", "211"],
            ["Spain (España)", "es", "34"],
            ["Sri Lanka (ශ්‍රී ලංකාව)", "lk", "94"],
            ["Sudan (‫السودان‬‎)", "sd", "249"],
            ["Suriname", "sr", "597"],
            ["Svalbard and Jan Mayen", "sj", "47", 1, ["79"]],
            ["Sweden (Sverige)", "se", "46"],
            ["Switzerland (Schweiz)", "ch", "41"],
            ["Syria (‫سوريا‬‎)", "sy", "963"],
            ["Taiwan (台灣)", "tw", "886"],
            ["Tajikistan", "tj", "992"],
            ["Tanzania", "tz", "255"],
            ["Thailand (ไทย)", "th", "66"],
            ["Timor-Leste", "tl", "670"],
            ["Togo", "tg", "228"],
            ["Tokelau", "tk", "690"],
            ["Tonga", "to", "676"],
            ["Trinidad and Tobago", "tt", "1", 22, ["868"]],
            ["Tunisia (‫تونس‬‎)", "tn", "216"],
            ["Turkey (Türkiye)", "tr", "90"],
            ["Turkmenistan", "tm", "993"],
            ["Turks and Caicos Islands", "tc", "1", 23, ["649"]],
            ["Tuvalu", "tv", "688"],
            ["U.S. Virgin Islands", "vi", "1", 24, ["340"]],
            ["Uganda", "ug", "256"],
            ["Ukraine (Україна)", "ua", "380"],
            ["United Arab Emirates (‫الإمارات العربية المتحدة‬‎)", "ae", "971"],
            ["United Kingdom", "gb", "44", 0],
            ["United States", "us", "1", 0],
            ["Uruguay", "uy", "598"],
            ["Uzbekistan (Oʻzbekiston)", "uz", "998"],
            ["Vanuatu", "vu", "678"],
            ["Vatican City (Città del Vaticano)", "va", "39", 1, ["06698"]],
            ["Venezuela", "ve", "58"],
            ["Vietnam (Việt Nam)", "vn", "84"],
            ["Wallis and Futuna (Wallis-et-Futuna)", "wf", "681"],
            ["Western Sahara (‫الصحراء الغربية‬‎)", "eh", "212", 1, ["5288", "5289"]],
            ["Yemen (‫اليمن‬‎)", "ye", "967"],
            ["Zambia", "zm", "260"],
            ["Zimbabwe", "zw", "263"],
            ["Åland Islands", "ax", "358", 1, ["18"]]
        ];
        // loop over all of the countries above, restructuring the data to be objects with named keys
        for (var i = 0; i < allCountries.length; i++) {
            var c = allCountries[i];
            allCountries[i] = {
                name: c[0],
                iso2: c[1],
                dialCode: c[2],
                priority: c[3] || 0,
                areaCodes: c[4] || null
            };
        }
        "use strict";

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            return Constructor;
        }
        var intlTelInputGlobals = {
            getInstance: function getInstance(input) {
                var id = input.getAttribute("data-intl-tel-input-id");
                return window.intlTelInputGlobals.instances[id];
            },
            instances: {},
            // using a global like this allows us to mock it in the tests
            documentReady: function documentReady() {
                return document.readyState === "complete";
            }
        };
        if (typeof window === "object") window.intlTelInputGlobals = intlTelInputGlobals;
        // these vars persist through all instances of the plugin
        var id = 0;
        var defaults = {
            // whether or not to allow the dropdown
            allowDropdown: true,
            // if there is just a dial code in the input: remove it on blur
            autoHideDialCode: true,
            // add a placeholder in the input with an example number for the selected country
            autoPlaceholder: "polite",
            // modify the parentClass
            customContainer: "",
            // modify the auto placeholder
            customPlaceholder: null,
            // append menu to specified element
            dropdownContainer: null,
            // don't display these countries
            excludeCountries: [],
            // format the input value during initialisation and on setNumber
            formatOnDisplay: true,
            // geoIp lookup function
            geoIpLookup: null,
            // inject a hidden input with this name, and on submit, populate it with the result of getNumber
            hiddenInput: "",
            // initial country
            initialCountry: "",
            // localized country names e.g. { 'de': 'Deutschland' }
            localizedCountries: null,
            // don't insert international dial codes
            nationalMode: true,
            // display only these countries
            onlyCountries: [],
            // number type to use for placeholders
            placeholderNumberType: "MOBILE",
            // the countries at the top of the list. defaults to united states and united kingdom
            preferredCountries: ["bd", "gb", "us"],
            // display the country dial code next to the selected flag so it's not part of the typed number
            separateDialCode: false,
            // specify the path to the libphonenumber script to enable validation/formatting
            utilsScript: ""
        };
        // https://en.wikipedia.org/wiki/List_of_North_American_Numbering_Plan_area_codes#Non-geographic_area_codes
        var regionlessNanpNumbers = ["800", "822", "833", "844", "855", "866", "877", "880", "881", "882", "883", "884", "885", "886", "887", "888", "889"];
        // utility function to iterate over an object. can't use Object.entries or native forEach because
        // of IE11
        var forEachProp = function forEachProp(obj, callback) {
            var keys = Object.keys(obj);
            for (var i = 0; i < keys.length; i++) {
                callback(keys[i], obj[keys[i]]);
            }
        };
        // run a method on each instance of the plugin
        var forEachInstance = function forEachInstance(method) {
            forEachProp(window.intlTelInputGlobals.instances, function(key) {
                window.intlTelInputGlobals.instances[key][method]();
            });
        };
        // this is our plugin class that we will create an instance of
        // eslint-disable-next-line no-unused-vars
        var Iti = /*#__PURE__*/
            function() {
                function Iti(input, options) {
                    var _this = this;
                    _classCallCheck(this, Iti);
                    this.id = id++;
                    this.telInput = input;
                    this.activeItem = null;
                    this.highlightedItem = null;
                    // process specified options / defaults
                    // alternative to Object.assign, which isn't supported by IE11
                    var customOptions = options || {};
                    this.options = {};
                    forEachProp(defaults, function(key, value) {
                        _this.options[key] = customOptions.hasOwnProperty(key) ? customOptions[key] : value;
                    });
                    this.hadInitialPlaceholder = Boolean(input.getAttribute("placeholder"));
                }
                _createClass(Iti, [{
                    key: "_init",
                    value: function _init() {
                        var _this2 = this;
                        // if in nationalMode, disable options relating to dial codes
                        if (this.options.nationalMode) this.options.autoHideDialCode = false;
                        // if separateDialCode then doesn't make sense to A) insert dial code into input
                        // (autoHideDialCode), and B) display national numbers (because we're displaying the country
                        // dial code next to them)
                        if (this.options.separateDialCode) {
                            this.options.autoHideDialCode = this.options.nationalMode = false;
                        }
                        // we cannot just test screen size as some smartphones/website meta tags will report desktop
                        // resolutions
                        // Note: for some reason jasmine breaks if you put this in the main Plugin function with the
                        // rest of these declarations
                        // Note: to target Android Mobiles (and not Tablets), we must find 'Android' and 'Mobile'
                        this.isMobile = /Android.+Mobile|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                        if (this.isMobile) {
                            // trigger the mobile dropdown css
                            document.body.classList.add("iti-mobile");
                            // on mobile, we want a full screen dropdown, so we must append it to the body
                            if (!this.options.dropdownContainer) this.options.dropdownContainer = document.body;
                        }
                        // these promises get resolved when their individual requests complete
                        // this way the dev can do something like iti.promise.then(...) to know when all requests are
                        // complete
                        if (typeof Promise !== "undefined") {
                            var autoCountryPromise = new Promise(function(resolve, reject) {
                                _this2.resolveAutoCountryPromise = resolve;
                                _this2.rejectAutoCountryPromise = reject;
                            });
                            var utilsScriptPromise = new Promise(function(resolve, reject) {
                                _this2.resolveUtilsScriptPromise = resolve;
                                _this2.rejectUtilsScriptPromise = reject;
                            });
                            this.promise = Promise.all([autoCountryPromise, utilsScriptPromise]);
                        } else {
                            // prevent errors when Promise doesn't exist
                            this.resolveAutoCountryPromise = this.rejectAutoCountryPromise = function() {};
                            this.resolveUtilsScriptPromise = this.rejectUtilsScriptPromise = function() {};
                        }
                        // in various situations there could be no country selected initially, but we need to be able
                        // to assume this variable exists
                        this.selectedCountryData = {};
                        // process all the data: onlyCountries, excludeCountries, preferredCountries etc
                        this._processCountryData();
                        // generate the markup
                        this._generateMarkup();
                        // set the initial state of the input value and the selected flag
                        this._setInitialState();
                        // start all of the event listeners: autoHideDialCode, input keydown, selectedFlag click
                        this._initListeners();
                        // utils script, and auto country
                        this._initRequests();
                    }
                }, {
                    key: "_processCountryData",
                    value: function _processCountryData() {
                        // process onlyCountries or excludeCountries array if present
                        this._processAllCountries();
                        // process the countryCodes map
                        this._processCountryCodes();
                        // process the preferredCountries
                        this._processPreferredCountries();
                        // translate countries according to localizedCountries option
                        if (this.options.localizedCountries) this._translateCountriesByLocale();
                        // sort countries by name
                        if (this.options.onlyCountries.length || this.options.localizedCountries) {
                            this.countries.sort(this._countryNameSort);
                        }
                    }
                }, {
                    key: "_addCountryCode",
                    value: function _addCountryCode(iso2, countryCode, priority) {
                        if (countryCode.length > this.countryCodeMaxLen) {
                            this.countryCodeMaxLen = countryCode.length;
                        }
                        if (!this.countryCodes.hasOwnProperty(countryCode)) {
                            this.countryCodes[countryCode] = [];
                        }
                        // bail if we already have this country for this countryCode
                        for (var i = 0; i < this.countryCodes[countryCode].length; i++) {
                            if (this.countryCodes[countryCode][i] === iso2) return;
                        }
                        // check for undefined as 0 is falsy
                        var index = priority !== undefined ? priority : this.countryCodes[countryCode].length;
                        this.countryCodes[countryCode][index] = iso2;
                    }
                }, {
                    key: "_processAllCountries",
                    value: function _processAllCountries() {
                        if (this.options.onlyCountries.length) {
                            var lowerCaseOnlyCountries = this.options.onlyCountries.map(function(country) {
                                return country.toLowerCase();
                            });
                            this.countries = allCountries.filter(function(country) {
                                return lowerCaseOnlyCountries.indexOf(country.iso2) > -1;
                            });
                        } else if (this.options.excludeCountries.length) {
                            var lowerCaseExcludeCountries = this.options.excludeCountries.map(function(country) {
                                return country.toLowerCase();
                            });
                            this.countries = allCountries.filter(function(country) {
                                return lowerCaseExcludeCountries.indexOf(country.iso2) === -1;
                            });
                        } else {
                            this.countries = allCountries;
                        }
                    }
                }, {
                    key: "_translateCountriesByLocale",
                    value: function _translateCountriesByLocale() {
                        for (var i = 0; i < this.countries.length; i++) {
                            var iso = this.countries[i].iso2.toLowerCase();
                            if (this.options.localizedCountries.hasOwnProperty(iso)) {
                                this.countries[i].name = this.options.localizedCountries[iso];
                            }
                        }
                    }
                }, {
                    key: "_countryNameSort",
                    value: function _countryNameSort(a, b) {
                        return a.name.localeCompare(b.name);
                    }
                }, {
                    key: "_processCountryCodes",
                    value: function _processCountryCodes() {
                        this.countryCodeMaxLen = 0;
                        // here we store just dial codes
                        this.dialCodes = {};
                        // here we store "country codes" (both dial codes and their area codes)
                        this.countryCodes = {};
                        // first: add dial codes
                        for (var i = 0; i < this.countries.length; i++) {
                            var c = this.countries[i];
                            if (!this.dialCodes[c.dialCode]) this.dialCodes[c.dialCode] = true;
                            this._addCountryCode(c.iso2, c.dialCode, c.priority);
                        }
                        // next: add area codes
                        // this is a second loop over countries, to make sure we have all of the "root" countries
                        // already in the map, so that we can access them, as each time we add an area code substring
                        // to the map, we also need to include the "root" country's code, as that also matches
                        for (var _i = 0; _i < this.countries.length; _i++) {
                            var _c = this.countries[_i];
                            // area codes
                            if (_c.areaCodes) {
                                var rootCountryCode = this.countryCodes[_c.dialCode][0];
                                // for each area code
                                for (var j = 0; j < _c.areaCodes.length; j++) {
                                    var areaCode = _c.areaCodes[j];
                                    // for each digit in the area code to add all partial matches as well
                                    for (var k = 1; k < areaCode.length; k++) {
                                        var partialDialCode = _c.dialCode + areaCode.substr(0, k);
                                        // start with the root country, as that also matches this dial code
                                        this._addCountryCode(rootCountryCode, partialDialCode);
                                        this._addCountryCode(_c.iso2, partialDialCode);
                                    }
                                    // add the full area code
                                    this._addCountryCode(_c.iso2, _c.dialCode + areaCode);
                                }
                            }
                        }
                    }
                }, {
                    key: "_processPreferredCountries",
                    value: function _processPreferredCountries() {
                        this.preferredCountries = [];
                        for (var i = 0; i < this.options.preferredCountries.length; i++) {
                            var countryCode = this.options.preferredCountries[i].toLowerCase();
                            var countryData = this._getCountryData(countryCode, false, true);
                            if (countryData) this.preferredCountries.push(countryData);
                        }
                    }
                }, {
                    key: "_createEl",
                    value: function _createEl(name, attrs, container) {
                        var el = document.createElement(name);
                        if (attrs) forEachProp(attrs, function(key, value) {
                            return el.setAttribute(key, value);
                        });
                        if (container) container.appendChild(el);
                        return el;
                    }
                }, {
                    key: "_generateMarkup",
                    value: function _generateMarkup() {
                        // if autocomplete does not exist on the element and its form, then
                        // prevent autocomplete as there's no safe, cross-browser event we can react to, so it can
                        // easily put the plugin in an inconsistent state e.g. the wrong flag selected for the
                        // autocompleted number, which on submit could mean wrong number is saved (esp in nationalMode)
                        if (!this.telInput.hasAttribute("autocomplete") && !(this.telInput.form && this.telInput.form.hasAttribute("autocomplete"))) {
                            this.telInput.setAttribute("autocomplete", "off");
                        }
                        // containers (mostly for positioning)
                        var parentClass = "iti";
                        if (this.options.allowDropdown) parentClass += " iti--allow-dropdown";
                        if (this.options.separateDialCode) parentClass += " iti--separate-dial-code";
                        if (this.options.customContainer) {
                            parentClass += " ";
                            parentClass += this.options.customContainer;
                        }
                        var wrapper = this._createEl("div", {
                            "class": parentClass
                        });
                        this.telInput.parentNode.insertBefore(wrapper, this.telInput);
                        this.flagsContainer = this._createEl("div", {
                            "class": "iti__flag-container"
                        }, wrapper);
                        wrapper.appendChild(this.telInput);
                        // selected flag (displayed to left of input)
                        this.selectedFlag = this._createEl("div", {
                            "class": "iti__selected-flag",
                            role: "combobox",
                            "aria-controls": "iti-".concat(this.id, "__country-listbox"),
                            "aria-owns": "iti-".concat(this.id, "__country-listbox"),
                            "aria-expanded": "false"
                        }, this.flagsContainer);
                        this.selectedFlagInner = this._createEl("div", {
                            "class": "iti__flag"
                        }, this.selectedFlag);
                        if (this.options.separateDialCode) {
                            this.selectedDialCode = this._createEl("div", {
                                "class": "iti__selected-dial-code"
                            }, this.selectedFlag);
                        }
                        if (this.options.allowDropdown) {
                            // make element focusable and tab navigable
                            this.selectedFlag.setAttribute("tabindex", "0");
                            this.dropdownArrow = this._createEl("div", {
                                "class": "iti__arrow"
                            }, this.selectedFlag);
                            // country dropdown: preferred countries, then divider, then all countries
                            this.countryList = this._createEl("ul", {
                                "class": "iti__country-list iti__hide",
                                id: "iti-".concat(this.id, "__country-listbox"),
                                role: "listbox",
                                "aria-label": "List of countries"
                            });
                            if (this.preferredCountries.length) {
                                this._appendListItems(this.preferredCountries, "iti__preferred", true);
                                this._createEl("li", {
                                    "class": "iti__divider",
                                    role: "separator",
                                    "aria-disabled": "true"
                                }, this.countryList);
                            }
                            this._appendListItems(this.countries, "iti__standard");
                            // create dropdownContainer markup
                            if (this.options.dropdownContainer) {
                                this.dropdown = this._createEl("div", {
                                    "class": "iti iti--container"
                                });
                                this.dropdown.appendChild(this.countryList);
                            } else {
                                this.flagsContainer.appendChild(this.countryList);
                            }
                        }
                        if (this.options.hiddenInput) {
                            var hiddenInputName = this.options.hiddenInput;
                            var name = this.telInput.getAttribute("name");
                            if (name) {
                                var i = name.lastIndexOf("[");
                                // if input name contains square brackets, then give the hidden input the same name,
                                // replacing the contents of the last set of brackets with the given hiddenInput name
                                if (i !== -1) hiddenInputName = "".concat(name.substr(0, i), "[").concat(hiddenInputName, "]");
                            }
                            this.hiddenInput = this._createEl("input", {
                                type: "hidden",
                                name: hiddenInputName
                            });
                            wrapper.appendChild(this.hiddenInput);
                        }
                    }
                }, {
                    key: "_appendListItems",
                    value: function _appendListItems(countries, className, preferred) {
                        // we create so many DOM elements, it is faster to build a temp string
                        // and then add everything to the DOM in one go at the end
                        var tmp = "";
                        // for each country
                        for (var i = 0; i < countries.length; i++) {
                            var c = countries[i];
                            var idSuffix = preferred ? "-preferred" : "";
                            // open the list item
                            tmp += "<li class='iti__country ".concat(className, "' tabIndex='-1' id='iti-").concat(this.id, "__item-").concat(c.iso2).concat(idSuffix, "' role='option' data-dial-code='").concat(c.dialCode, "' data-country-code='").concat(c.iso2, "' aria-selected='false'>");
                            // add the flag
                            tmp += "<div class='iti__flag-box'><div class='iti__flag iti__".concat(c.iso2, "'></div></div>");
                            // and the country name and dial code
                            tmp += "<span class='iti__country-name'>".concat(c.name, "</span>");
                            tmp += "<span class='iti__dial-code'>+".concat(c.dialCode, "</span>");
                            // close the list item
                            tmp += "</li>";
                        }
                        this.countryList.insertAdjacentHTML("beforeend", tmp);
                    }
                }, {
                    key: "_setInitialState",
                    value: function _setInitialState() {
                        var val = this.telInput.value;
                        var dialCode = this._getDialCode(val);
                        var isRegionlessNanp = this._isRegionlessNanp(val);
                        var _this$options = this.options,
                            initialCountry = _this$options.initialCountry,
                            nationalMode = _this$options.nationalMode,
                            autoHideDialCode = _this$options.autoHideDialCode,
                            separateDialCode = _this$options.separateDialCode;
                        // if we already have a dial code, and it's not a regionlessNanp, we can go ahead and set the
                        // flag, else fall back to the default country
                        if (dialCode && !isRegionlessNanp) {
                            this._updateFlagFromNumber(val);
                        } else if (initialCountry !== "auto") {
                            // see if we should select a flag
                            if (initialCountry) {
                                this._setFlag(initialCountry.toLowerCase());
                            } else {
                                if (dialCode && isRegionlessNanp) {
                                    // has intl dial code, is regionless nanp, and no initialCountry, so default to US
                                    this._setFlag("us");
                                } else {
                                    // no dial code and no initialCountry, so default to first in list
                                    this.defaultCountry = this.preferredCountries.length ? this.preferredCountries[0].iso2 : this.countries[0].iso2;
                                    if (!val) {
                                        this._setFlag(this.defaultCountry);
                                    }
                                }
                            }
                            // if empty and no nationalMode and no autoHideDialCode then insert the default dial code
                            if (!val && !nationalMode && !autoHideDialCode && !separateDialCode) {
                                this.telInput.value = "+".concat(this.selectedCountryData.dialCode);
                            }
                        }
                        // NOTE: if initialCountry is set to auto, that will be handled separately
                        // format - note this wont be run after _updateDialCode as that's only called if no val
                        if (val) this._updateValFromNumber(val);
                    }
                }, {
                    key: "_initListeners",
                    value: function _initListeners() {
                        this._initKeyListeners();
                        if (this.options.autoHideDialCode) this._initBlurListeners();
                        if (this.options.allowDropdown) this._initDropdownListeners();
                        if (this.hiddenInput) this._initHiddenInputListener();
                    }
                }, {
                    key: "_initHiddenInputListener",
                    value: function _initHiddenInputListener() {
                        var _this3 = this;
                        this._handleHiddenInputSubmit = function() {
                            _this3.hiddenInput.value = _this3.getNumber();
                        };
                        if (this.telInput.form) this.telInput.form.addEventListener("submit", this._handleHiddenInputSubmit);
                    }
                }, {
                    key: "_getClosestLabel",
                    value: function _getClosestLabel() {
                        var el = this.telInput;
                        while (el && el.tagName !== "LABEL") {
                            el = el.parentNode;
                        }
                        return el;
                    }
                }, {
                    key: "_initDropdownListeners",
                    value: function _initDropdownListeners() {
                        var _this4 = this;
                        // hack for input nested inside label (which is valid markup): clicking the selected-flag to
                        // open the dropdown would then automatically trigger a 2nd click on the input which would
                        // close it again
                        this._handleLabelClick = function(e) {
                            // if the dropdown is closed, then focus the input, else ignore the click
                            if (_this4.countryList.classList.contains("iti__hide")) _this4.telInput.focus();
                            else e.preventDefault();
                        };
                        var label = this._getClosestLabel();
                        if (label) label.addEventListener("click", this._handleLabelClick);
                        // toggle country dropdown on click
                        this._handleClickSelectedFlag = function() {
                            // only intercept this event if we're opening the dropdown
                            // else let it bubble up to the top ("click-off-to-close" listener)
                            // we cannot just stopPropagation as it may be needed to close another instance
                            if (_this4.countryList.classList.contains("iti__hide") && !_this4.telInput.disabled && !_this4.telInput.readOnly) {
                                _this4._showDropdown();
                            }
                        };
                        this.selectedFlag.addEventListener("click", this._handleClickSelectedFlag);
                        // open dropdown list if currently focused
                        this._handleFlagsContainerKeydown = function(e) {
                            var isDropdownHidden = _this4.countryList.classList.contains("iti__hide");
                            if (isDropdownHidden && ["ArrowUp", "Up", "ArrowDown", "Down", " ", "Enter"].indexOf(e.key) !== -1) {
                                // prevent form from being submitted if "ENTER" was pressed
                                e.preventDefault();
                                // prevent event from being handled again by document
                                e.stopPropagation();
                                _this4._showDropdown();
                            }
                            // allow navigation from dropdown to input on TAB
                            if (e.key === "Tab") _this4._closeDropdown();
                        };
                        this.flagsContainer.addEventListener("keydown", this._handleFlagsContainerKeydown);
                    }
                }, {
                    key: "_initRequests",
                    value: function _initRequests() {
                        var _this5 = this;
                        // if the user has specified the path to the utils script, fetch it on window.load, else resolve
                        if (this.options.utilsScript && !window.intlTelInputUtils) {
                            // if the plugin is being initialised after the window.load event has already been fired
                            if (window.intlTelInputGlobals.documentReady()) {
                                window.intlTelInputGlobals.loadUtils(this.options.utilsScript);
                            } else {
                                // wait until the load event so we don't block any other requests e.g. the flags image
                                window.addEventListener("load", function() {
                                    window.intlTelInputGlobals.loadUtils(_this5.options.utilsScript);
                                });
                            }
                        } else this.resolveUtilsScriptPromise();
                        if (this.options.initialCountry === "auto") this._loadAutoCountry();
                        else this.resolveAutoCountryPromise();
                    }
                }, {
                    key: "_loadAutoCountry",
                    value: function _loadAutoCountry() {
                        // 3 options:
                        // 1) already loaded (we're done)
                        // 2) not already started loading (start)
                        // 3) already started loading (do nothing - just wait for loading callback to fire)
                        if (window.intlTelInputGlobals.autoCountry) {
                            this.handleAutoCountry();
                        } else if (!window.intlTelInputGlobals.startedLoadingAutoCountry) {
                            // don't do this twice!
                            window.intlTelInputGlobals.startedLoadingAutoCountry = true;
                            if (typeof this.options.geoIpLookup === "function") {
                                this.options.geoIpLookup(function(countryCode) {
                                    window.intlTelInputGlobals.autoCountry = countryCode.toLowerCase();
                                    // tell all instances the auto country is ready
                                    // this should just be the current instances
                                    // UPDATE: use setTimeout in case their geoIpLookup function calls this callback straight
                                    // away (e.g. if they have already done the geo ip lookup somewhere else). Using
                                    // setTimeout means that the current thread of execution will finish before executing
                                    // this, which allows the plugin to finish initialising.
                                    setTimeout(function() {
                                        return forEachInstance("handleAutoCountry");
                                    });
                                }, function() {
                                    return forEachInstance("rejectAutoCountryPromise");
                                });
                            }
                        }
                    }
                }, {
                    key: "_initKeyListeners",
                    value: function _initKeyListeners() {
                        var _this6 = this;
                        // update flag on keyup
                        this._handleKeyupEvent = function() {
                            if (_this6._updateFlagFromNumber(_this6.telInput.value)) {
                                _this6._triggerCountryChange();
                            }
                        };
                        this.telInput.addEventListener("keyup", this._handleKeyupEvent);
                        // update flag on cut/paste events (now supported in all major browsers)
                        this._handleClipboardEvent = function() {
                            // hack because "paste" event is fired before input is updated
                            setTimeout(_this6._handleKeyupEvent);
                        };
                        this.telInput.addEventListener("cut", this._handleClipboardEvent);
                        this.telInput.addEventListener("paste", this._handleClipboardEvent);
                    }
                }, {
                    key: "_cap",
                    value: function _cap(number) {
                        var max = this.telInput.getAttribute("maxlength");
                        return max && number.length > max ? number.substr(0, max) : number;
                    }
                }, {
                    key: "_initBlurListeners",
                    value: function _initBlurListeners() {
                        var _this7 = this;
                        // on blur or form submit: if just a dial code then remove it
                        this._handleSubmitOrBlurEvent = function() {
                            _this7._removeEmptyDialCode();
                        };
                        if (this.telInput.form) this.telInput.form.addEventListener("submit", this._handleSubmitOrBlurEvent);
                        this.telInput.addEventListener("blur", this._handleSubmitOrBlurEvent);
                    }
                }, {
                    key: "_removeEmptyDialCode",
                    value: function _removeEmptyDialCode() {
                        if (this.telInput.value.charAt(0) === "+") {
                            var numeric = this._getNumeric(this.telInput.value);
                            // if just a plus, or if just a dial code
                            if (!numeric || this.selectedCountryData.dialCode === numeric) {
                                this.telInput.value = "";
                            }
                        }
                    }
                }, {
                    key: "_getNumeric",
                    value: function _getNumeric(s) {
                        return s.replace(/\D/g, "");
                    }
                }, {
                    key: "_trigger",
                    value: function _trigger(name) {
                        // have to use old school document.createEvent as IE11 doesn't support `new Event()` syntax
                        var e = document.createEvent("Event");
                        e.initEvent(name, true, true);
                        // can bubble, and is cancellable
                        this.telInput.dispatchEvent(e);
                    }
                }, {
                    key: "_showDropdown",
                    value: function _showDropdown() {
                        this.countryList.classList.remove("iti__hide");
                        this.selectedFlag.setAttribute("aria-expanded", "true");
                        this._setDropdownPosition();
                        // update highlighting and scroll to active list item
                        if (this.activeItem) {
                            this._highlightListItem(this.activeItem, false);
                            this._scrollTo(this.activeItem, true);
                        }
                        // bind all the dropdown-related listeners: mouseover, click, click-off, keydown
                        this._bindDropdownListeners();
                        // update the arrow
                        this.dropdownArrow.classList.add("iti__arrow--up");
                        this._trigger("open:countrydropdown");
                    }
                }, {
                    key: "_toggleClass",
                    value: function _toggleClass(el, className, shouldHaveClass) {
                        if (shouldHaveClass && !el.classList.contains(className)) el.classList.add(className);
                        else if (!shouldHaveClass && el.classList.contains(className)) el.classList.remove(className);
                    }
                }, {
                    key: "_setDropdownPosition",
                    value: function _setDropdownPosition() {
                        var _this8 = this;
                        if (this.options.dropdownContainer) {
                            this.options.dropdownContainer.appendChild(this.dropdown);
                        }
                        if (!this.isMobile) {
                            var pos = this.telInput.getBoundingClientRect();
                            // windowTop from https://stackoverflow.com/a/14384091/217866
                            var windowTop = window.pageYOffset || document.documentElement.scrollTop;
                            var inputTop = pos.top + windowTop;
                            var dropdownHeight = this.countryList.offsetHeight;
                            // dropdownFitsBelow = (dropdownBottom < windowBottom)
                            var dropdownFitsBelow = inputTop + this.telInput.offsetHeight + dropdownHeight < windowTop + window.innerHeight;
                            var dropdownFitsAbove = inputTop - dropdownHeight > windowTop;
                            // by default, the dropdown will be below the input. If we want to position it above the
                            // input, we add the dropup class.
                            this._toggleClass(this.countryList, "iti__country-list--dropup", !dropdownFitsBelow && dropdownFitsAbove);
                            // if dropdownContainer is enabled, calculate postion
                            if (this.options.dropdownContainer) {
                                // by default the dropdown will be directly over the input because it's not in the flow.
                                // If we want to position it below, we need to add some extra top value.
                                var extraTop = !dropdownFitsBelow && dropdownFitsAbove ? 0 : this.telInput.offsetHeight;
                                // calculate placement
                                this.dropdown.style.top = "".concat(inputTop + extraTop, "px");
                                this.dropdown.style.left = "".concat(pos.left + document.body.scrollLeft, "px");
                                // close menu on window scroll
                                this._handleWindowScroll = function() {
                                    return _this8._closeDropdown();
                                };
                                window.addEventListener("scroll", this._handleWindowScroll);
                            }
                        }
                    }
                }, {
                    key: "_getClosestListItem",
                    value: function _getClosestListItem(target) {
                        var el = target;
                        while (el && el !== this.countryList && !el.classList.contains("iti__country")) {
                            el = el.parentNode;
                        }
                        // if we reached the countryList element, then return null
                        return el === this.countryList ? null : el;
                    }
                }, {
                    key: "_bindDropdownListeners",
                    value: function _bindDropdownListeners() {
                        var _this9 = this;
                        // when mouse over a list item, just highlight that one
                        // we add the class "highlight", so if they hit "enter" we know which one to select
                        this._handleMouseoverCountryList = function(e) {
                            // handle event delegation, as we're listening for this event on the countryList
                            var listItem = _this9._getClosestListItem(e.target);
                            if (listItem) _this9._highlightListItem(listItem, false);
                        };
                        this.countryList.addEventListener("mouseover", this._handleMouseoverCountryList);
                        // listen for country selection
                        this._handleClickCountryList = function(e) {
                            var listItem = _this9._getClosestListItem(e.target);
                            if (listItem) _this9._selectListItem(listItem);
                        };
                        this.countryList.addEventListener("click", this._handleClickCountryList);
                        // click off to close
                        // (except when this initial opening click is bubbling up)
                        // we cannot just stopPropagation as it may be needed to close another instance
                        var isOpening = true;
                        this._handleClickOffToClose = function() {
                            if (!isOpening) _this9._closeDropdown();
                            isOpening = false;
                        };
                        document.documentElement.addEventListener("click", this._handleClickOffToClose);
                        // listen for up/down scrolling, enter to select, or letters to jump to country name.
                        // use keydown as keypress doesn't fire for non-char keys and we want to catch if they
                        // just hit down and hold it to scroll down (no keyup event).
                        // listen on the document because that's where key events are triggered if no input has focus
                        var query = "";
                        var queryTimer = null;
                        this._handleKeydownOnDropdown = function(e) {
                            // prevent down key from scrolling the whole page,
                            // and enter key from submitting a form etc
                            e.preventDefault();
                            // up and down to navigate
                            if (e.key === "ArrowUp" || e.key === "Up" || e.key === "ArrowDown" || e.key === "Down") _this9._handleUpDownKey(e.key);
                            else if (e.key === "Enter") _this9._handleEnterKey();
                            else if (e.key === "Escape") _this9._closeDropdown();
                            else if (/^[a-zA-ZÀ-ÿа-яА-Я ]$/.test(e.key)) {
                                // jump to countries that start with the query string
                                if (queryTimer) clearTimeout(queryTimer);
                                query += e.key.toLowerCase();
                                _this9._searchForCountry(query);
                                // if the timer hits 1 second, reset the query
                                queryTimer = setTimeout(function() {
                                    query = "";
                                }, 1e3);
                            }
                        };
                        document.addEventListener("keydown", this._handleKeydownOnDropdown);
                    }
                }, {
                    key: "_handleUpDownKey",
                    value: function _handleUpDownKey(key) {
                        var next = key === "ArrowUp" || key === "Up" ? this.highlightedItem.previousElementSibling : this.highlightedItem.nextElementSibling;
                        if (next) {
                            // skip the divider
                            if (next.classList.contains("iti__divider")) {
                                next = key === "ArrowUp" || key === "Up" ? next.previousElementSibling : next.nextElementSibling;
                            }
                            this._highlightListItem(next, true);
                        }
                    }
                }, {
                    key: "_handleEnterKey",
                    value: function _handleEnterKey() {
                        if (this.highlightedItem) this._selectListItem(this.highlightedItem);
                    }
                }, {
                    key: "_searchForCountry",
                    value: function _searchForCountry(query) {
                        for (var i = 0; i < this.countries.length; i++) {
                            if (this._startsWith(this.countries[i].name, query)) {
                                var listItem = this.countryList.querySelector("#iti-".concat(this.id, "__item-").concat(this.countries[i].iso2));
                                // update highlighting and scroll
                                this._highlightListItem(listItem, false);
                                this._scrollTo(listItem, true);
                                break;
                            }
                        }
                    }
                }, {
                    key: "_startsWith",
                    value: function _startsWith(a, b) {
                        return a.substr(0, b.length).toLowerCase() === b;
                    }
                }, {
                    key: "_updateValFromNumber",
                    value: function _updateValFromNumber(originalNumber) {
                        var number = originalNumber;
                        if (this.options.formatOnDisplay && window.intlTelInputUtils && this.selectedCountryData) {
                            var useNational = !this.options.separateDialCode && (this.options.nationalMode || number.charAt(0) !== "+");
                            var _intlTelInputUtils$nu = intlTelInputUtils.numberFormat,
                                NATIONAL = _intlTelInputUtils$nu.NATIONAL,
                                INTERNATIONAL = _intlTelInputUtils$nu.INTERNATIONAL;
                            var format = useNational ? NATIONAL : INTERNATIONAL;
                            number = intlTelInputUtils.formatNumber(number, this.selectedCountryData.iso2, format);
                        }
                        number = this._beforeSetNumber(number);
                        this.telInput.value = number;
                    }
                }, {
                    key: "_updateFlagFromNumber",
                    value: function _updateFlagFromNumber(originalNumber) {
                        // if we're in nationalMode and we already have US/Canada selected, make sure the number starts
                        // with a +1 so _getDialCode will be able to extract the area code
                        // update: if we dont yet have selectedCountryData, but we're here (trying to update the flag
                        // from the number), that means we're initialising the plugin with a number that already has a
                        // dial code, so fine to ignore this bit
                        var number = originalNumber;
                        var selectedDialCode = this.selectedCountryData.dialCode;
                        var isNanp = selectedDialCode === "1";
                        if (number && this.options.nationalMode && isNanp && number.charAt(0) !== "+") {
                            if (number.charAt(0) !== "1") number = "1".concat(number);
                            number = "+".concat(number);
                        }
                        // update flag if user types area code for another country
                        if (this.options.separateDialCode && selectedDialCode && number.charAt(0) !== "+") {
                            number = "+".concat(selectedDialCode).concat(number);
                        }
                        // try and extract valid dial code from input
                        var dialCode = this._getDialCode(number, true);
                        var numeric = this._getNumeric(number);
                        var countryCode = null;
                        if (dialCode) {
                            var countryCodes = this.countryCodes[this._getNumeric(dialCode)];
                            // check if the right country is already selected. this should be false if the number is
                            // longer than the matched dial code because in this case we need to make sure that if
                            // there are multiple country matches, that the first one is selected (note: we could
                            // just check that here, but it requires the same loop that we already have later)
                            var alreadySelected = countryCodes.indexOf(this.selectedCountryData.iso2) !== -1 && numeric.length <= dialCode.length - 1;
                            var isRegionlessNanpNumber = selectedDialCode === "1" && this._isRegionlessNanp(numeric);
                            // only update the flag if:
                            // A) NOT (we currently have a NANP flag selected, and the number is a regionlessNanp)
                            // AND
                            // B) the right country is not already selected
                            if (!isRegionlessNanpNumber && !alreadySelected) {
                                // if using onlyCountries option, countryCodes[0] may be empty, so we must find the first
                                // non-empty index
                                for (var j = 0; j < countryCodes.length; j++) {
                                    if (countryCodes[j]) {
                                        countryCode = countryCodes[j];
                                        break;
                                    }
                                }
                            }
                        } else if (number.charAt(0) === "+" && numeric.length) {
                            // invalid dial code, so empty
                            // Note: use getNumeric here because the number has not been formatted yet, so could contain
                            // bad chars
                            countryCode = "";
                        } else if (!number || number === "+") {
                            // empty, or just a plus, so default
                            countryCode = this.defaultCountry;
                        }
                        if (countryCode !== null) {
                            return this._setFlag(countryCode);
                        }
                        return false;
                    }
                }, {
                    key: "_isRegionlessNanp",
                    value: function _isRegionlessNanp(number) {
                        var numeric = this._getNumeric(number);
                        if (numeric.charAt(0) === "1") {
                            var areaCode = numeric.substr(1, 3);
                            return regionlessNanpNumbers.indexOf(areaCode) !== -1;
                        }
                        return false;
                    }
                }, {
                    key: "_highlightListItem",
                    value: function _highlightListItem(listItem, shouldFocus) {
                        var prevItem = this.highlightedItem;
                        if (prevItem) prevItem.classList.remove("iti__highlight");
                        this.highlightedItem = listItem;
                        this.highlightedItem.classList.add("iti__highlight");
                        if (shouldFocus) this.highlightedItem.focus();
                    }
                }, {
                    key: "_getCountryData",
                    value: function _getCountryData(countryCode, ignoreOnlyCountriesOption, allowFail) {
                        var countryList = ignoreOnlyCountriesOption ? allCountries : this.countries;
                        for (var i = 0; i < countryList.length; i++) {
                            if (countryList[i].iso2 === countryCode) {
                                return countryList[i];
                            }
                        }
                        if (allowFail) {
                            return null;
                        }
                        throw new Error("No country data for '".concat(countryCode, "'"));
                    }
                }, {
                    key: "_setFlag",
                    value: function _setFlag(countryCode) {
                        var prevCountry = this.selectedCountryData.iso2 ? this.selectedCountryData : {};
                        // do this first as it will throw an error and stop if countryCode is invalid
                        this.selectedCountryData = countryCode ? this._getCountryData(countryCode, false, false) : {};
                        // update the defaultCountry - we only need the iso2 from now on, so just store that
                        if (this.selectedCountryData.iso2) {
                            this.defaultCountry = this.selectedCountryData.iso2;
                        }
                        this.selectedFlagInner.setAttribute("class", "iti__flag iti__".concat(countryCode));
                        // update the selected country's title attribute
                        var title = countryCode ? "".concat(this.selectedCountryData.name, ": +").concat(this.selectedCountryData.dialCode) : "Unknown";
                        this.selectedFlag.setAttribute("title", title);
                        if (this.options.separateDialCode) {
                            var dialCode = this.selectedCountryData.dialCode ? "+".concat(this.selectedCountryData.dialCode) : "";
                            this.selectedDialCode.innerHTML = dialCode;
                            // offsetWidth is zero if input is in a hidden container during initialisation
                            var selectedFlagWidth = this.selectedFlag.offsetWidth || this._getHiddenSelectedFlagWidth();
                            // add 6px of padding after the grey selected-dial-code box, as this is what we use in the css
                            this.telInput.style.paddingLeft = "".concat(selectedFlagWidth + 6, "px");
                        }
                        // and the input's placeholder
                        this._updatePlaceholder();
                        // update the active list item
                        if (this.options.allowDropdown) {
                            var prevItem = this.activeItem;
                            if (prevItem) {
                                prevItem.classList.remove("iti__active");
                                prevItem.setAttribute("aria-selected", "false");
                            }
                            if (countryCode) {
                                // check if there is a preferred item first, else fall back to standard
                                var nextItem = this.countryList.querySelector("#iti-".concat(this.id, "__item-").concat(countryCode, "-preferred")) || this.countryList.querySelector("#iti-".concat(this.id, "__item-").concat(countryCode));
                                nextItem.setAttribute("aria-selected", "true");
                                nextItem.classList.add("iti__active");
                                this.activeItem = nextItem;
                                this.selectedFlag.setAttribute("aria-activedescendant", nextItem.getAttribute("id"));
                            }
                        }
                        // return if the flag has changed or not
                        return prevCountry.iso2 !== countryCode;
                    }
                }, {
                    key: "_getHiddenSelectedFlagWidth",
                    value: function _getHiddenSelectedFlagWidth() {
                        // to get the right styling to apply, all we need is a shallow clone of the container,
                        // and then to inject a deep clone of the selectedFlag element
                        var containerClone = this.telInput.parentNode.cloneNode();
                        containerClone.style.visibility = "hidden";
                        document.body.appendChild(containerClone);
                        var flagsContainerClone = this.flagsContainer.cloneNode();
                        containerClone.appendChild(flagsContainerClone);
                        var selectedFlagClone = this.selectedFlag.cloneNode(true);
                        flagsContainerClone.appendChild(selectedFlagClone);
                        var width = selectedFlagClone.offsetWidth;
                        containerClone.parentNode.removeChild(containerClone);
                        return width;
                    }
                }, {
                    key: "_updatePlaceholder",
                    value: function _updatePlaceholder() {
                        var shouldSetPlaceholder = this.options.autoPlaceholder === "aggressive" || !this.hadInitialPlaceholder && this.options.autoPlaceholder === "polite";
                        if (window.intlTelInputUtils && shouldSetPlaceholder) {
                            var numberType = intlTelInputUtils.numberType[this.options.placeholderNumberType];
                            var placeholder = this.selectedCountryData.iso2 ? intlTelInputUtils.getExampleNumber(this.selectedCountryData.iso2, this.options.nationalMode, numberType) : "";
                            placeholder = this._beforeSetNumber(placeholder);
                            if (typeof this.options.customPlaceholder === "function") {
                                placeholder = this.options.customPlaceholder(placeholder, this.selectedCountryData);
                            }
                            this.telInput.setAttribute("placeholder", placeholder);
                        }
                    }
                }, {
                    key: "_selectListItem",
                    value: function _selectListItem(listItem) {
                        // update selected flag and active list item
                        var flagChanged = this._setFlag(listItem.getAttribute("data-country-code"));
                        this._closeDropdown();
                        this._updateDialCode(listItem.getAttribute("data-dial-code"), true);
                        // focus the input
                        this.telInput.focus();
                        // put cursor at end - this fix is required for FF and IE11 (with nationalMode=false i.e. auto
                        // inserting dial code), who try to put the cursor at the beginning the first time
                        var len = this.telInput.value.length;
                        this.telInput.setSelectionRange(len, len);
                        if (flagChanged) {
                            this._triggerCountryChange();
                        }
                    }
                }, {
                    key: "_closeDropdown",
                    value: function _closeDropdown() {
                        this.countryList.classList.add("iti__hide");
                        this.selectedFlag.setAttribute("aria-expanded", "false");
                        // update the arrow
                        this.dropdownArrow.classList.remove("iti__arrow--up");
                        // unbind key events
                        document.removeEventListener("keydown", this._handleKeydownOnDropdown);
                        document.documentElement.removeEventListener("click", this._handleClickOffToClose);
                        this.countryList.removeEventListener("mouseover", this._handleMouseoverCountryList);
                        this.countryList.removeEventListener("click", this._handleClickCountryList);
                        // remove menu from container
                        if (this.options.dropdownContainer) {
                            if (!this.isMobile) window.removeEventListener("scroll", this._handleWindowScroll);
                            if (this.dropdown.parentNode) this.dropdown.parentNode.removeChild(this.dropdown);
                        }
                        this._trigger("close:countrydropdown");
                    }
                }, {
                    key: "_scrollTo",
                    value: function _scrollTo(element, middle) {
                        var container = this.countryList;
                        // windowTop from https://stackoverflow.com/a/14384091/217866
                        var windowTop = window.pageYOffset || document.documentElement.scrollTop;
                        var containerHeight = container.offsetHeight;
                        var containerTop = container.getBoundingClientRect().top + windowTop;
                        var containerBottom = containerTop + containerHeight;
                        var elementHeight = element.offsetHeight;
                        var elementTop = element.getBoundingClientRect().top + windowTop;
                        var elementBottom = elementTop + elementHeight;
                        var newScrollTop = elementTop - containerTop + container.scrollTop;
                        var middleOffset = containerHeight / 2 - elementHeight / 2;
                        if (elementTop < containerTop) {
                            // scroll up
                            if (middle) newScrollTop -= middleOffset;
                            container.scrollTop = newScrollTop;
                        } else if (elementBottom > containerBottom) {
                            // scroll down
                            if (middle) newScrollTop += middleOffset;
                            var heightDifference = containerHeight - elementHeight;
                            container.scrollTop = newScrollTop - heightDifference;
                        }
                    }
                }, {
                    key: "_updateDialCode",
                    value: function _updateDialCode(newDialCodeBare, hasSelectedListItem) {
                        var inputVal = this.telInput.value;
                        // save having to pass this every time
                        var newDialCode = "+".concat(newDialCodeBare);
                        var newNumber;
                        if (inputVal.charAt(0) === "+") {
                            // there's a plus so we're dealing with a replacement (doesn't matter if nationalMode or not)
                            var prevDialCode = this._getDialCode(inputVal);
                            if (prevDialCode) {
                                // current number contains a valid dial code, so replace it
                                newNumber = inputVal.replace(prevDialCode, newDialCode);
                            } else {
                                // current number contains an invalid dial code, so ditch it
                                // (no way to determine where the invalid dial code ends and the rest of the number begins)
                                newNumber = newDialCode;
                            }
                        } else if (this.options.nationalMode || this.options.separateDialCode) {
                            // don't do anything
                            return;
                        } else {
                            // nationalMode is disabled
                            if (inputVal) {
                                // there is an existing value with no dial code: prefix the new dial code
                                newNumber = newDialCode + inputVal;
                            } else if (hasSelectedListItem || !this.options.autoHideDialCode) {
                                // no existing value and either they've just selected a list item, or autoHideDialCode is
                                // disabled: insert new dial code
                                newNumber = newDialCode;
                            } else {
                                return;
                            }
                        }
                        this.telInput.value = newNumber;
                    }
                }, {
                    key: "_getDialCode",
                    value: function _getDialCode(number, includeAreaCode) {
                        var dialCode = "";
                        // only interested in international numbers (starting with a plus)
                        if (number.charAt(0) === "+") {
                            var numericChars = "";
                            // iterate over chars
                            for (var i = 0; i < number.length; i++) {
                                var c = number.charAt(i);
                                // if char is number (https://stackoverflow.com/a/8935649/217866)
                                if (!isNaN(parseInt(c, 10))) {
                                    numericChars += c;
                                    // if current numericChars make a valid dial code
                                    if (includeAreaCode) {
                                        if (this.countryCodes[numericChars]) {
                                            // store the actual raw string (useful for matching later)
                                            dialCode = number.substr(0, i + 1);
                                        }
                                    } else {
                                        if (this.dialCodes[numericChars]) {
                                            dialCode = number.substr(0, i + 1);
                                            // if we're just looking for a dial code, we can break as soon as we find one
                                            break;
                                        }
                                    }
                                    // stop searching as soon as we can - in this case when we hit max len
                                    if (numericChars.length === this.countryCodeMaxLen) {
                                        break;
                                    }
                                }
                            }
                        }
                        return dialCode;
                    }
                }, {
                    key: "_getFullNumber",
                    value: function _getFullNumber() {
                        var val = this.telInput.value.trim();
                        var dialCode = this.selectedCountryData.dialCode;
                        var prefix;
                        var numericVal = this._getNumeric(val);
                        if (this.options.separateDialCode && val.charAt(0) !== "+" && dialCode && numericVal) {
                            // when using separateDialCode, it is visible so is effectively part of the typed number
                            prefix = "+".concat(dialCode);
                        } else {
                            prefix = "";
                        }
                        return prefix + val;
                    }
                }, {
                    key: "_beforeSetNumber",
                    value: function _beforeSetNumber(originalNumber) {
                        var number = originalNumber;
                        if (this.options.separateDialCode) {
                            var dialCode = this._getDialCode(number);
                            // if there is a valid dial code
                            if (dialCode) {
                                // in case _getDialCode returned an area code as well
                                dialCode = "+".concat(this.selectedCountryData.dialCode);
                                // a lot of numbers will have a space separating the dial code and the main number, and
                                // some NANP numbers will have a hyphen e.g. +1 684-733-1234 - in both cases we want to get
                                // rid of it
                                // NOTE: don't just trim all non-numerics as may want to preserve an open parenthesis etc
                                var start = number[dialCode.length] === " " || number[dialCode.length] === "-" ? dialCode.length + 1 : dialCode.length;
                                number = number.substr(start);
                            }
                        }
                        return this._cap(number);
                    }
                }, {
                    key: "_triggerCountryChange",
                    value: function _triggerCountryChange() {
                        this._trigger("countrychange");
                    }
                }, {
                    key: "handleAutoCountry",
                    value: function handleAutoCountry() {
                        if (this.options.initialCountry === "auto") {
                            // we must set this even if there is an initial val in the input: in case the initial val is
                            // invalid and they delete it - they should see their auto country
                            this.defaultCountry = window.intlTelInputGlobals.autoCountry;
                            // if there's no initial value in the input, then update the flag
                            if (!this.telInput.value) {
                                this.setCountry(this.defaultCountry);
                            }
                            this.resolveAutoCountryPromise();
                        }
                    }
                }, {
                    key: "handleUtils",
                    value: function handleUtils() {
                        // if the request was successful
                        if (window.intlTelInputUtils) {
                            // if there's an initial value in the input, then format it
                            if (this.telInput.value) {
                                this._updateValFromNumber(this.telInput.value);
                            }
                            this._updatePlaceholder();
                        }
                        this.resolveUtilsScriptPromise();
                    }
                }, {
                    key: "destroy",
                    value: function destroy() {
                        var form = this.telInput.form;
                        if (this.options.allowDropdown) {
                            // make sure the dropdown is closed (and unbind listeners)
                            this._closeDropdown();
                            this.selectedFlag.removeEventListener("click", this._handleClickSelectedFlag);
                            this.flagsContainer.removeEventListener("keydown", this._handleFlagsContainerKeydown);
                            // label click hack
                            var label = this._getClosestLabel();
                            if (label) label.removeEventListener("click", this._handleLabelClick);
                        }
                        // unbind hiddenInput listeners
                        if (this.hiddenInput && form) form.removeEventListener("submit", this._handleHiddenInputSubmit);
                        // unbind autoHideDialCode listeners
                        if (this.options.autoHideDialCode) {
                            if (form) form.removeEventListener("submit", this._handleSubmitOrBlurEvent);
                            this.telInput.removeEventListener("blur", this._handleSubmitOrBlurEvent);
                        }
                        // unbind key events, and cut/paste events
                        this.telInput.removeEventListener("keyup", this._handleKeyupEvent);
                        this.telInput.removeEventListener("cut", this._handleClipboardEvent);
                        this.telInput.removeEventListener("paste", this._handleClipboardEvent);
                        // remove attribute of id instance: data-intl-tel-input-id
                        this.telInput.removeAttribute("data-intl-tel-input-id");
                        // remove markup (but leave the original input)
                        var wrapper = this.telInput.parentNode;
                        wrapper.parentNode.insertBefore(this.telInput, wrapper);
                        wrapper.parentNode.removeChild(wrapper);
                        delete window.intlTelInputGlobals.instances[this.id];
                    }
                }, {
                    key: "getExtension",
                    value: function getExtension() {
                        if (window.intlTelInputUtils) {
                            return intlTelInputUtils.getExtension(this._getFullNumber(), this.selectedCountryData.iso2);
                        }
                        return "";
                    }
                }, {
                    key: "getNumber",
                    value: function getNumber(format) {
                        if (window.intlTelInputUtils) {
                            var iso2 = this.selectedCountryData.iso2;
                            return intlTelInputUtils.formatNumber(this._getFullNumber(), iso2, format);
                        }
                        return "";
                    }
                }, {
                    key: "getNumberType",
                    value: function getNumberType() {
                        if (window.intlTelInputUtils) {
                            return intlTelInputUtils.getNumberType(this._getFullNumber(), this.selectedCountryData.iso2);
                        }
                        return -99;
                    }
                }, {
                    key: "getSelectedCountryData",
                    value: function getSelectedCountryData() {
                        return this.selectedCountryData;
                    }
                }, {
                    key: "getValidationError",
                    value: function getValidationError() {
                        if (window.intlTelInputUtils) {
                            var iso2 = this.selectedCountryData.iso2;
                            return intlTelInputUtils.getValidationError(this._getFullNumber(), iso2);
                        }
                        return -99;
                    }
                }, {
                    key: "isValidNumber",
                    value: function isValidNumber() {
                        var val = this._getFullNumber().trim();
                        var countryCode = this.options.nationalMode ? this.selectedCountryData.iso2 : "";
                        return window.intlTelInputUtils ? intlTelInputUtils.isValidNumber(val, countryCode) : null;
                    }
                }, {
                    key: "setCountry",
                    value: function setCountry(originalCountryCode) {
                        var countryCode = originalCountryCode.toLowerCase();
                        // check if already selected
                        if (!this.selectedFlagInner.classList.contains("iti__".concat(countryCode))) {
                            this._setFlag(countryCode);
                            this._updateDialCode(this.selectedCountryData.dialCode, false);
                            this._triggerCountryChange();
                        }
                    }
                }, {
                    key: "setNumber",
                    value: function setNumber(number) {
                        // we must update the flag first, which updates this.selectedCountryData, which is used for
                        // formatting the number before displaying it
                        var flagChanged = this._updateFlagFromNumber(number);
                        this._updateValFromNumber(number);
                        if (flagChanged) {
                            this._triggerCountryChange();
                        }
                    }
                }, {
                    key: "setPlaceholderNumberType",
                    value: function setPlaceholderNumberType(type) {
                        this.options.placeholderNumberType = type;
                        this._updatePlaceholder();
                    }
                }]);
                return Iti;
            }();
        /********************
         *  STATIC METHODS
         ********************/
        // get the country data object
        intlTelInputGlobals.getCountryData = function() {
            return allCountries;
        };
        // inject a <script> element to load utils.js
        var injectScript = function injectScript(path, handleSuccess, handleFailure) {
            // inject a new script element into the page
            var script = document.createElement("script");
            script.onload = function() {
                forEachInstance("handleUtils");
                if (handleSuccess) handleSuccess();
            };
            script.onerror = function() {
                forEachInstance("rejectUtilsScriptPromise");
                if (handleFailure) handleFailure();
            };
            script.className = "iti-load-utils";
            script.async = true;
            script.src = path;
            document.body.appendChild(script);
        };
        // load the utils script
        intlTelInputGlobals.loadUtils = function(path) {
            // 2 options:
            // 1) not already started loading (start)
            // 2) already started loading (do nothing - just wait for the onload callback to fire, which will
            // trigger handleUtils on all instances, invoking their resolveUtilsScriptPromise functions)
            if (!window.intlTelInputUtils && !window.intlTelInputGlobals.startedLoadingUtilsScript) {
                // only do this once
                window.intlTelInputGlobals.startedLoadingUtilsScript = true;
                // if we have promises, then return a promise
                if (typeof Promise !== "undefined") {
                    return new Promise(function(resolve, reject) {
                        return injectScript(path, resolve, reject);
                    });
                }
                injectScript(path);
            }
            return null;
        };
        // default options
        intlTelInputGlobals.defaults = defaults;
        // version
        intlTelInputGlobals.version = "17.0.8";
        // convenience wrapper
        return function(input, options) {
            var iti = new Iti(input, options);
            iti._init();
            input.setAttribute("data-intl-tel-input-id", iti.id);
            window.intlTelInputGlobals.instances[iti.id] = iti;
            return iti;
        };
    }();
});

/* ====================================
=======================================
    Tags Input Js 
=======================================
=======================================*/

(function() {

    "use strict"

    $(document).ready(function() {

        // Plugin Constructor
        var TagsInputs = function(opts) {
            this.options = Object.assign(TagsInputs.defaults, opts);
            this.init();
        }

        // Initialize the plugin
        TagsInputs.prototype.init = function(opts) {
            this.options = opts ? Object.assign(this.options, opts) : this.options;

            if (this.initialized)
                this.destroy();

            if (!(this.orignal_input = document.getElementById(this.options.selector))) {
                console.error("tags-input couldn't find an element with the specified ID");
                return this;
            }

            this.arr = [];
            this.wrapper = document.createElement('div');
            this.input = document.createElement('input');
            init(this);
            initEvents(this);

            this.initialized = true;
            return this;
        }

        // Add Tags
        TagsInputs.prototype.addTag = function(string) {

            if (this.anyErrors(string))
                return;

            this.arr.push(string);
            var tagInputs = this;

            var tag = document.createElement('span');
            tag.className = this.options.tagClass;
            tag.innerText = string;

            var closeIcon = document.createElement('a');
            closeIcon.innerHTML = '&times;';

            // delete the tag when icon is clicked
            closeIcon.addEventListener('click', function(e) {
                e.preventDefault();
                var tag = this.parentNode;

                for (var i = 0; i < tagInputs.wrapper.childNodes.length; i++) {
                    if (tagInputs.wrapper.childNodes[i] == tag)
                        tagInputs.deleteTag(tag, i);
                }
            })


            tag.appendChild(closeIcon);
            this.wrapper.insertBefore(tag, this.input);
            this.orignal_input.value = this.arr.join(',');

            return this;
        }

        // Delete Tags
        TagsInputs.prototype.deleteTag = function(tag, i) {
            tag.remove();
            this.arr.splice(i, 1);
            this.orignal_input.value = this.arr.join(',');
            return this;
        }

        // Make sure input string have no error with the plugin
        TagsInputs.prototype.anyErrors = function(string) {
            if (this.options.max != null && this.arr.length >= this.options.max) {
                return true;
            }

            if (!this.options.duplicate && this.arr.indexOf(string) != -1) {
                console.log('duplicate found " ' + string + ' " ')
                return true;
            }

            return false;
        }

        // Add tags programmatically 
        TagsInputs.prototype.addData = function(array) {
            var plugin = this;

            array.forEach(function(string) {
                plugin.addTag(string);
            })
            return this;
        }

        // Get the Input String
        TagsInputs.prototype.getInputString = function() {
            return this.arr.join(',');
        }


        // destroy the plugin
        TagsInputs.prototype.destroy = function() {
            this.orignal_input.removeAttribute('hidden');

            delete this.orignal_input;
            var self = this;

            Object.keys(this).forEach(function(key) {
                if (self[key] instanceof HTMLElement)
                    self[key].remove();

                if (key != 'options')
                    delete self[key];
            });

            this.initialized = false;
        }

        // Private function to initialize the tag input plugin
        function init(tags) {
            tags.wrapper.append(tags.input);
            tags.wrapper.classList.add(tags.options.wrapperClass);
            tags.orignal_input.setAttribute('hidden', 'true');
            tags.orignal_input.parentNode.insertBefore(tags.wrapper, tags.orignal_input);
        }

        // initialize the Events
        function initEvents(tags) {
            tags.wrapper.addEventListener('click', function() {
                tags.input.focus();
            });


            tags.input.addEventListener('keydown', function(e) {
                var str = tags.input.value.trim();

                if (!!(~[9, 13, 188].indexOf(e.keyCode))) {
                    e.preventDefault();
                    tags.input.value = "";
                    if (str != "")
                        tags.addTag(str);
                }

            });
        }


        // Set All the Default Values
        TagsInputs.defaults = {
            selector: '',
            wrapperClass: 'tags-input-wrapper',
            tagClass: 'tag',
            max: null,
            duplicate: false
        }

        window.TagsInputs = TagsInputs;

    });
})();