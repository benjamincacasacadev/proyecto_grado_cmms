! function (t) {
	"function" == typeof define && define.amd ? define(t) : t()
}(function () {
	"use strict";

    class dn {
        constructor(t) {
            (t = Be(t)) && (this._element = t, cn.set(this._element, this.constructor.DATA_KEY, this))
        }
        dispose() {
            cn.remove(this._element, this.constructor.DATA_KEY), un.off(this._element, this.constructor.EVENT_KEY), Object.getOwnPropertyNames(this).forEach(t => {
                this[t] = null
            })
        }
        _queueCallback(t, e, n = !0) {
            We(t, e, n)
        }
        static getInstance(t) {
            return cn.get(Be(t), this.DATA_KEY)
        }
        static getOrCreateInstance(t, e = {}) {
            return this.getInstance(t) || new this(t, "object" == typeof e ? e : null)
        }
        static get VERSION() {
            return hn
        }
        static get NAME() {
            throw new Error('You have to implement the static method "NAME", for each component!')
        }
        static get DATA_KEY() {
            return `bs.${this.NAME}`
        }
        static get EVENT_KEY() {
            return `.${this.DATA_KEY}`
        }
    }

    const Zs = "offcanvas",
    Js = "Escape",
    tr = {
        backdrop: !0,
        keyboard: !0,
        scroll: !1
    },
    er = {
        backdrop: "boolean",
        keyboard: "boolean",
        scroll: "boolean"
    },
    nr = "show",
    ir = "offcanvas-backdrop",
    sr = "show.bs.offcanvas",
    rr = "shown.bs.offcanvas",
    or = "hide.bs.offcanvas",
    ar = "hidden.bs.offcanvas",
    ur = "keydown.dismiss.bs.offcanvas";

    class lr extends dn {
        constructor(t, e) {
            super(t), this._config = this._getConfig(e), this._isShown = !1, this._backdrop = this._initializeBackDrop(), this._focustrap = this._initializeFocusTrap(), this._addEventListeners()
        }
        static get NAME() {
            return Zs
        }
        static get Default() {
            return tr
        }
        toggle(t) {
            return this._isShown ? this.hide() : this.show(t)
        }
        show(t) {
            if (this._isShown) return;
            if (un.trigger(this._element, sr, {
                    relatedTarget: t
                }).defaultPrevented) return;
            this._isShown = !0, this._element.style.visibility = "visible", this._backdrop.show(), this._config.scroll || (new hs).hide(), this._element.removeAttribute("aria-hidden"), this._element.setAttribute("aria-modal", !0), this._element.setAttribute("role", "dialog"), this._element.classList.add(nr);
            this._queueCallback(() => {
                this._config.scroll || this._focustrap.activate(), un.trigger(this._element, rr, {
                    relatedTarget: t
                })
            }, this._element, !0)
        }
        hide() {
            if (!this._isShown) return;
            if (un.trigger(this._element, or).defaultPrevented) return;
            this._focustrap.deactivate(), this._element.blur(), this._isShown = !1, this._element.classList.remove(nr), this._backdrop.hide();
            this._queueCallback(() => {
                this._element.setAttribute("aria-hidden", !0), this._element.removeAttribute("aria-modal"), this._element.removeAttribute("role"), this._element.style.visibility = "hidden", this._config.scroll || (new hs).reset(), un.trigger(this._element, ar)
            }, this._element, !0)
        }
        dispose() {
            this._backdrop.dispose(), this._focustrap.deactivate(), super.dispose()
        }
        _getConfig(t) {
            return t = {
                ...tr,
                ...Cn.getDataAttributes(this._element),
                ..."object" == typeof t ? t : {}
            }, Ie(Zs, t, er), t
        }
        _initializeBackDrop() {
            return new _s({
                className: ir,
                isVisible: this._config.backdrop,
                isAnimated: !0,
                rootElement: this._element.parentNode,
                clickCallback: () => this.hide()
            })
        }
        _initializeFocusTrap() {
            return new Ds({
                trapElement: this._element
            })
        }
        _addEventListeners() {
            un.on(this._element, ur, t => {
                this._config.keyboard && t.key === Js && this.hide()
            })
        }
        static jQueryInterface(t) {
            return this.each(function () {
                const e = lr.getOrCreateInstance(this, t);
                if ("string" == typeof t) {
                    if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                    e[t](this)
                }
            })
        }
    }




    const Cn = {
        setDataAttribute(t, e, n) {
            t.setAttribute(`data-bs-${An(e)}`, n)
        },
        removeDataAttribute(t, e) {
            t.removeAttribute(`data-bs-${An(e)}`)
        },
        getDataAttributes(t) {
            if (!t) return {};
            const e = {};
            return Object.keys(t.dataset).filter(t => t.startsWith("bs")).forEach(n => {
                let i = n.replace(/^bs/, "");
                i = i.charAt(0).toLowerCase() + i.slice(1, i.length), e[i] = wn(t.dataset[n])
            }), e
        },
        getDataAttribute: (t, e) => wn(t.getAttribute(`data-bs-${An(e)}`)),
        offset(t) {
            const e = t.getBoundingClientRect();
            return {
                top: e.top + window.pageYOffset,
                left: e.left + window.pageXOffset
            }
        },
        position: t => ({
            top: t.offsetTop,
            left: t.offsetLeft
        })
    },
    Ie = (t, e, n) => {
        Object.keys(n).forEach(i => {
            const s = n[i],
                r = e[i],
                o = r && xe(r) ? "element" : (t => null == t ? `${t}` : {}.toString.call(t).match(/\s([a-z]+)/i)[1].toLowerCase())(r);
            if (!new RegExp(s).test(o)) throw new TypeError(`${t.toUpperCase()}: Option "${i}" provided type "${o}" but expected type "${s}".`)
        })
    };

    const un = {
        on(t, e, n, i) {
            rn(t, e, n, i, !1)
        },
        one(t, e, n, i) {
            rn(t, e, n, i, !0)
        },
        off(t, e, n, i) {
            if ("string" != typeof e || !t) return;
            const [s, r, o] = sn(e, n, i), a = o !== e, u = en(t), l = e.startsWith(".");
            if (void 0 !== r) {
                if (!u || !u[o]) return;
                return void on(t, u, o, r, s ? n : null)
            }
            l && Object.keys(u).forEach(n => {
                ! function (t, e, n, i) {
                    const s = e[n] || {};
                    Object.keys(s).forEach(r => {
                        if (r.includes(i)) {
                            const i = s[r];
                            on(t, e, n, i.originalHandler, i.delegationSelector)
                        }
                    })
                }(t, u, n, e.slice(1))
            });
            const c = u[o] || {};
            Object.keys(c).forEach(n => {
                const i = n.replace(Ke, "");
                if (!a || e.includes(i)) {
                    const e = c[n];
                    on(t, u, o, e.originalHandler, e.delegationSelector)
                }
            })
        },
        trigger(t, e, n) {
            if ("string" != typeof e || !t) return null;
            const i = je(),
                s = an(e),
                r = e !== s,
                o = Je.has(s);
            let a, u = !0,
                l = !0,
                c = !1,
                h = null;
            return r && i && (a = i.Event(e, n), i(t).trigger(a), u = !a.isPropagationStopped(), l = !a.isImmediatePropagationStopped(), c = a.isDefaultPrevented()), o ? (h = document.createEvent("HTMLEvents")).initEvent(s, u, !0) : h = new CustomEvent(e, {
                bubbles: u,
                cancelable: !0
            }), void 0 !== n && Object.keys(n).forEach(t => {
                Object.defineProperty(h, t, {
                    get: () => n[t]
                })
            }), c && h.preventDefault(), l && t.dispatchEvent(h), h.defaultPrevented && void 0 !== a && a.preventDefault(), h
        }
    },
    qe = /[^.]*(?=\..*)\.|.*/,
    Ze = /^(mouseenter|mouseleave)/i,
    Je = new Set(["click", "dblclick", "mouseup", "mousedown", "contextmenu", "mousewheel", "DOMMouseScroll", "mouseover", "mouseout", "mousemove", "selectstart", "selectend", "keydown", "keypress", "keyup", "orientationchange", "touchstart", "touchmove", "touchend", "touchcancel", "pointerdown", "pointermove", "pointerup", "pointerleave", "pointercancel", "gesturestart", "gesturechange", "gestureend", "focus", "blur", "change", "reset", "select", "submit", "focusin", "focusout", "load", "unload", "beforeunload", "resize", "move", "DOMContentLoaded", "readystatechange", "error", "abort", "scroll"]),
    Ye = /\..*/,
    Qe = {
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    },
    Ke = /::\d+$/,
    Xe = {};
    let Ge = 1;

    const fn = (t, e = "hide") => {
        const n = `click.dismiss${t.EVENT_KEY}`,
            i = t.NAME;
        un.on(document, n, `[data-bs-dismiss="${i}"]`, function (n) {
            if (["A", "AREA"].includes(this.tagName) && n.preventDefault(), Pe(this)) return;
            const s = Oe(this) || this.closest(`.${i}`);
            t.getOrCreateInstance(s)[e]()
        })
    },
    Sn = {
        find: (t, e = document.documentElement) => [].concat(...Element.prototype.querySelectorAll.call(e, t)),
        findOne: (t, e = document.documentElement) => Element.prototype.querySelector.call(e, t),
        children: (t, e) => [].concat(...t.children).filter(t => t.matches(e)),
        parents(t, e) {
            const n = [];
            let i = t.parentNode;
            for (; i && i.nodeType === Node.ELEMENT_NODE && 3 !== i.nodeType;) i.matches(e) && n.push(i), i = i.parentNode;
            return n
        },
        prev(t, e) {
            let n = t.previousElementSibling;
            for (; n;) {
                if (n.matches(e)) return [n];
                n = n.previousElementSibling
            }
            return []
        },
        next(t, e) {
            let n = t.nextElementSibling;
            for (; n;) {
                if (n.matches(e)) return [n];
                n = n.nextElementSibling
            }
            return []
        },
        focusableChildren(t) {
            const e = ["a", "button", "input", "textarea", "select", "details", "[tabindex]", '[contenteditable="true"]'].map(t => `${t}:not([tabindex^="-"])`).join(", ");
            return this.find(e, t).filter(t => !Pe(t) && Le(t))
        }
    },
    Te = t => {
        let e = t.getAttribute("data-bs-target");
        if (!e || "#" === e) {
            let n = t.getAttribute("href");
            if (!n || !n.includes("#") && !n.startsWith(".")) return null;
            n.includes("#") && !n.startsWith("#") && (n = `#${n.split("#")[1]}`), e = n && "#" !== n ? n.trim() : null
        }
        return e
    },
    Le = t => !(!xe(t) || 0 === t.getClientRects().length) && "visible" === getComputedStyle(t).getPropertyValue("visibility"),
    Pe = t => !t || t.nodeType !== Node.ELEMENT_NODE || (!!t.classList.contains("disabled") || (void 0 !== t.disabled ? t.disabled : t.hasAttribute("disabled") && "false" !== t.getAttribute("disabled"))),
    xe = t => !(!t || "object" != typeof t) && (void 0 !== t.jquery && (t = t[0]), void 0 !== t.nodeType),
    Be = t => xe(t) ? t.jquery ? t[0] : t : "string" == typeof t && t.length > 0 ? document.querySelector(t) : null,
    ln = new Map,
    $e = t => {
        (t => {
            "loading" === document.readyState ? (Ve.length || document.addEventListener("DOMContentLoaded", () => {
                Ve.forEach(t => t())
            }), Ve.push(t)) : t()
        })(() => {
            const e = je();
            if (e) {
                const n = t.NAME,
                    i = e.fn[n];
                e.fn[n] = t.jQueryInterface, e.fn[n].Constructor = t, e.fn[n].noConflict = (() => (e.fn[n] = i, t.jQueryInterface))
            }
        })
    },
    Oe = t => {
        const e = Te(t);
        return e ? document.querySelector(e) : null
    },
    je = () => {
        const {
            jQuery: t
        } = window;
        return t && !document.body.hasAttribute("data-bs-no-jquery") ? t : null
    },
    Re = t => {
        t.offsetHeight
    },
    We = (t, e, n = !0) => {
        if (!n) return void ze(t);
        const i = (t => {
            if (!t) return 0;
            let {
                transitionDuration: e,
                transitionDelay: n
            } = window.getComputedStyle(t);
            const i = Number.parseFloat(e),
                s = Number.parseFloat(n);
            return i || s ? (e = e.split(",")[0], n = n.split(",")[0], 1e3 * (Number.parseFloat(e) + Number.parseFloat(n))) : 0
        })(e) + 5;
        let s = !1;
        const r = ({
            target: n
        }) => {
            n === e && (s = !0, e.removeEventListener("transitionend", r), ze(t))
        };
        e.addEventListener("transitionend", r), setTimeout(() => {
            s || Fe(e)
        }, i)
    },
    Fe = t => {
        t.dispatchEvent(new Event("transitionend"))
    },
    ze = t => {
        "function" == typeof t && t()
    },
    cn = {
        set(t, e, n) {
            ln.has(t) || ln.set(t, new Map);
            const i = ln.get(t);
            i.has(e) || 0 === i.size ? i.set(e, n) : console.error(`Bootstrap doesn't allow more than one instance per element. Bound instance: ${Array.from(i.keys())[0]}.`)
        },
        get: (t, e) => ln.has(t) && ln.get(t).get(e) || null,
        remove(t, e) {
            if (!ln.has(t)) return;
            const n = ln.get(t);
            n.delete(e), 0 === n.size && ln.delete(t)
        }
    },
    Ve = [];

    const ls = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",
    cs = ".sticky-top";

	const ds = {
        className: "modal-backdrop",
        isVisible: !0,
        isAnimated: !1,
        rootElement: "body",
        clickCallback: null
    },
    fs = {
        className: "string",
        isVisible: "boolean",
        isAnimated: "boolean",
        rootElement: "(element|string)",
        clickCallback: "(function|null)"
    },
    ps = "backdrop",
    gs = "fade",
    ms = "show",
    vs = `mousedown.bs.${ps}`;

    const ys = {
        trapElement: null,
        autofocus: !0
    },
    bs = {
        trapElement: "element",
        autofocus: "boolean"
    },
    ks = "focustrap",
    Es = ".bs.focustrap",
    ws = `focusin${Es}`,
    As = `keydown.tab${Es}`,
    Cs = "Tab",
    Ss = "forward",
    Ts = "backward";

    class _s {
		constructor(t) {
			this._config = this._getConfig(t), this._isAppended = !1, this._element = null
		}
		show(t) {
			this._config.isVisible ? (this._append(), this._config.isAnimated && Re(this._getElement()), this._getElement().classList.add(ms), this._emulateAnimation(() => {
				ze(t)
			})) : ze(t)
		}
		hide(t) {
			this._config.isVisible ? (this._getElement().classList.remove(ms), this._emulateAnimation(() => {
				this.dispose(), ze(t)
			})) : ze(t)
		}
		_getElement() {
			if (!this._element) {
				const t = document.createElement("div");
				t.className = this._config.className, this._config.isAnimated && t.classList.add(gs), this._element = t
			}
			return this._element
		}
		_getConfig(t) {
			return (t = {
				...ds,
				..."object" == typeof t ? t : {}
			}).rootElement = Be(t.rootElement), Ie(ps, t, fs), t
		}
		_append() {
			this._isAppended || (this._config.rootElement.append(this._getElement()), un.on(this._getElement(), vs, () => {
				ze(this._config.clickCallback)
			}), this._isAppended = !0)
		}
		dispose() {
			this._isAppended && (un.off(this._element, vs), this._element.remove(), this._isAppended = !1)
		}
		_emulateAnimation(t) {
			We(t, this._getElement(), this._config.isAnimated)
		}
	}

    class Ds {
		constructor(t) {
			this._config = this._getConfig(t), this._isActive = !1, this._lastTabNavDirection = null
		}
		activate() {
			const {
				trapElement: t,
				autofocus: e
			} = this._config;
			this._isActive || (e && t.focus(), un.off(document, Es), un.on(document, ws, t => this._handleFocusin(t)), un.on(document, As, t => this._handleKeydown(t)), this._isActive = !0)
		}
		deactivate() {
			this._isActive && (this._isActive = !1, un.off(document, Es))
		}
		_handleFocusin(t) {
			const {
				target: e
			} = t, {
				trapElement: n
			} = this._config;
			if (e === document || e === n || n.contains(e)) return;
			const i = Sn.focusableChildren(n);
			0 === i.length ? n.focus() : this._lastTabNavDirection === Ts ? i[i.length - 1].focus() : i[0].focus()
		}
		_handleKeydown(t) {
			t.key === Cs && (this._lastTabNavDirection = t.shiftKey ? Ts : Ss)
		}
		_getConfig(t) {
			return t = {
				...ys,
				..."object" == typeof t ? t : {}
			}, Ie(ks, t, bs), t
		}
	}
    class hs {
		constructor() {
			this._element = document.body
		}
		getWidth() {
			const t = document.documentElement.clientWidth;
			return Math.abs(window.innerWidth - t)
		}
		hide() {
			const t = this.getWidth();
			this._disableOverFlow(), this._setElementAttributes(this._element, "paddingRight", e => e + t), this._setElementAttributes(ls, "paddingRight", e => e + t), this._setElementAttributes(cs, "marginRight", e => e - t)
		}
		_disableOverFlow() {
			this._saveInitialAttribute(this._element, "overflow"), this._element.style.overflow = "hidden"
		}
		_setElementAttributes(t, e, n) {
			const i = this.getWidth();
			this._applyManipulationCallback(t, t => {
				if (t !== this._element && window.innerWidth > t.clientWidth + i) return;
				this._saveInitialAttribute(t, e);
				const s = window.getComputedStyle(t)[e];
				t.style[e] = `${n(Number.parseFloat(s))}px`
			})
		}
		reset() {
			this._resetElementAttributes(this._element, "overflow"), this._resetElementAttributes(this._element, "paddingRight"), this._resetElementAttributes(ls, "paddingRight"), this._resetElementAttributes(cs, "marginRight")
		}
		_saveInitialAttribute(t, e) {
			const n = t.style[e];
			n && Cn.setDataAttribute(t, e, n)
		}
		_resetElementAttributes(t, e) {
			this._applyManipulationCallback(t, t => {
				const n = Cn.getDataAttribute(t, e);
				void 0 === n ? t.style.removeProperty(e) : (Cn.removeDataAttribute(t, e), t.style[e] = n)
			})
		}
		_applyManipulationCallback(t, e) {
			xe(t) ? e(t) : Sn.find(t, this._element).forEach(e)
		}
		isOverflowing() {
			return this.getWidth() > 0
		}
	}

    function on(t, e, n, i, s) {
		const r = nn(e[n], i, s);
		r && (t.removeEventListener(n, r, Boolean(s)), delete e[n][r.uidEvent])
	}
    function An(t) {
		return t.replace(/[A-Z]/g, t => `-${t.toLowerCase()}`)
	}
    function wn(t) {
		return "true" === t || "false" !== t && (t === Number(t).toString() ? Number(t) : "" === t || "null" === t ? null : t)
	}
    function an(t) {
		return t = t.replace(Ye, ""), Qe[t] || t
	}
	function sn(t, e, n) {
		const i = "string" == typeof e,
			s = i ? n : e;
		let r = an(t);
		return Je.has(r) || (r = t), [i, s, r]
	}
    function rn(t, e, n, i, s) {
		if ("string" != typeof e || !t) return;
		if (n || (n = i, i = null), Ze.test(e)) {
			const t = t => (function (e) {
				if (!e.relatedTarget || e.relatedTarget !== e.delegateTarget && !e.delegateTarget.contains(e.relatedTarget)) return t.call(this, e)
			});
			i ? i = t(i) : n = t(n)
		}
		const [r, o, a] = sn(e, n, i), u = en(t), l = u[a] || (u[a] = {}), c = nn(l, o, r ? n : null);
		if (c) return void(c.oneOff = c.oneOff && s);
		const h = tn(o, e.replace(qe, "")),
			d = r ? function (t, e, n) {
				return function i(s) {
					const r = t.querySelectorAll(e);
					for (let {
							target: o
						} = s; o && o !== this; o = o.parentNode)
						for (let a = r.length; a--;)
							if (r[a] === o) return s.delegateTarget = o, i.oneOff && un.off(t, s.type, e, n), n.apply(o, [s]);
					return null
				}
			}(t, n, i) : function (t, e) {
				return function n(i) {
					return i.delegateTarget = t, n.oneOff && un.off(t, i.type, e), e.apply(t, [i])
				}
			}(t, n);
		d.delegationSelector = r ? n : null, d.originalHandler = o, d.oneOff = s, d.uidEvent = h, l[h] = d, t.addEventListener(a, d, r)
	}

    function tn(t, e) {
		return e && `${e}::${Ge++}` || t.uidEvent || Ge++
	}
    function en(t) {
		const e = tn(t);
		return t.uidEvent = e, Xe[e] = Xe[e] || {}, Xe[e]
	}
    function nn(t, e, n = null) {
		const i = Object.keys(t);
		for (let s = 0, r = i.length; s < r; s++) {
			const r = t[i[s]];
			if (r.originalHandler === e && r.delegationSelector === n) return r
		}
		return null
	}

    un.on(document, "click.bs.offcanvas.data-api", '[data-bs-toggle="offcanvas"]', function (t) {
		const e = Oe(this);
		if (["A", "AREA"].includes(this.tagName) && t.preventDefault(), Pe(this)) return;
		un.one(e, ar, () => {
			Le(this) && this.focus()
		});
		const n = Sn.findOne(".offcanvas.show");
		n && n !== e && lr.getInstance(n).hide(), lr.getOrCreateInstance(e).toggle(this)
	}), un.on(window, "load.bs.offcanvas.data-api", () => Sn.find(".offcanvas.show").forEach(t => lr.getOrCreateInstance(t).show())), fn(lr), $e(lr);
});