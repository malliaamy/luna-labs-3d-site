const __vite__mapDeps = (i, m = __vite__mapDeps, d = (m.f || (m.f = ["assets/router-DDfC9wtX.js", "assets/rolldown-runtime-S-ySWqyJ.js?r=3", "assets/index-Bbr6xAeW.js?r=3", "assets/framework-CXnKph_e.js?r=3"]))) => i.map(i => d[i]);
import {
    r as e
} from "./rolldown-runtime-S-ySWqyJ.js?r=3";
import {
    i as t,
    r as n
} from "./framework-CXnKph_e.js?r=3";
import {
    _ as r,
    a as i,
    b as a,
    d as o,
    f as s,
    g as c,
    h as l,
    i as u,
    m as d,
    n as f,
    o as p,
    p as m,
    s as h,
    t as g,
    u as ee,
    v as _,
    x as v
} from "./index-Bbr6xAeW.js?r=3";
var y = /([/#?\\]|%(2f|23|3f|5c))/gi;

function b(e) {
    return e.replace(y, e => encodeURIComponent(e))
}

function x(e) {
    try {
        return b(decodeURIComponent(e))
    } catch {
        return e
    }
}

function S(e) {
    return e.split(`/`).map(e => x(e)).join(`/`)
}

function C(e) {
    try {
        return decodeURIComponent(e)
    } catch {
        return e
    }
}

function w(e) {
    for (let t of Object.keys(e)) {
        let n = e[t];
        Array.isArray(n) ? e[t] = n.map(C) : e[t] = C(n)
    }
}

function T() {
    return {
        staticChildren: new Map,
        dynamicChild: null,
        catchAllChild: null,
        optionalCatchAllChild: null,
        route: null
    }
}

function E(e) {
    let t = T();
    for (let n of e) {
        let e = n.patternParts;
        if (e.length === 0) {
            t.route === null && (t.route = n);
            continue
        }
        let r = t;
        for (let t = 0; t < e.length; t++) {
            let i = e[t];
            if (i.endsWith(`+`) && i.startsWith(`:`)) {
                if (t !== e.length - 1) break;
                let a = i.slice(1, -1);
                r.catchAllChild === null && (r.catchAllChild = {
                    paramName: a,
                    route: n
                });
                break
            }
            if (i.endsWith(`*`) && i.startsWith(`:`)) {
                if (t !== e.length - 1) break;
                let a = i.slice(1, -1);
                r.optionalCatchAllChild === null && (r.optionalCatchAllChild = {
                    paramName: a,
                    route: n
                });
                break
            }
            if (i.startsWith(`:`)) {
                let a = i.slice(1);
                r.dynamicChild === null && (r.dynamicChild = {
                    paramName: a,
                    node: T()
                }), r = r.dynamicChild.node, t === e.length - 1 && r.route === null && (r.route = n);
                continue
            }
            let a = r.staticChildren.get(i);
            a || (a = T(), r.staticChildren.set(i, a)), r = a, t === e.length - 1 && r.route === null && (r.route = n)
        }
    }
    return t
}

function D(e, t) {
    let n = k(e, t, 0);
    return n && w(n.params), n
}

function O() {
    return Object.create(null)
}

function k(e, t, n) {
    if (n === t.length) return e.route === null ? e.optionalCatchAllChild === null ? null : {
        route: e.optionalCatchAllChild.route,
        params: O()
    } : {
        route: e.route,
        params: O()
    };
    let r = t[n],
        i = e.staticChildren.get(r);
    if (i) {
        let e = k(i, t, n + 1);
        if (e !== null) return e
    }
    if (e.dynamicChild !== null) {
        let i = k(e.dynamicChild.node, t, n + 1);
        if (i !== null) return i.params[e.dynamicChild.paramName] = r, i
    }
    if (e.catchAllChild !== null) {
        let r = t.slice(n),
            i = O();
        return i[e.catchAllChild.paramName] = r, {
            route: e.catchAllChild.route,
            params: i
        }
    }
    if (e.optionalCatchAllChild !== null) {
        let r = t.slice(n),
            i = O();
        return i[e.optionalCatchAllChild.paramName] = r, {
            route: e.optionalCatchAllChild.route,
            params: i
        }
    }
    return null
}

function A() {
    return new WeakMap
}

function j(e, t) {
    let n = e.get(t);
    return n || (n = E(t), e.set(t, n)), n
}

function M(e, t, n) {
    let r = e.split(`?`)[0],
        i = r === `/` ? `/` : r.replace(/\/$/, ``);
    i = S(i);
    let a = i.split(`/`).filter(Boolean);
    return D(j(n, t), a)
}

function N(e) {
    if (e) return e.split(`,`, 1)[0]?.trim().split(`:`, 1)[0]?.toLowerCase() || void 0
}

function P(e, t, n) {
    if (!e?.length) return;
    let r = N(t),
        i = n?.toLowerCase();
    for (let t of e)
        if (r === N(t.domain) || i === t.defaultLocale.toLowerCase() || t.locales?.some(e => e.toLowerCase() === i)) return t
}

function F(e, t, n) {
    let r = t.toLowerCase();
    if (r === n.toLowerCase()) return e;
    let i = e.startsWith(`/`) ? e : `/${e}`,
        a = (i.split(/[?#]/, 1)[0] ?? i).toLowerCase(),
        o = `/${r}`;
    return a === o || a.startsWith(`${o}/`) ? e.startsWith(`/`) ? e : i : `/${t}${i}`
}

function I(e, t = ``) {
    return t ? t + e : e
}

function L(e, t, {
    basePath: n,
    currentHostname: r,
    domainItems: i
}) {
    if (!i?.length) return;
    let a = P(i, void 0, t);
    if (!a) return;
    let o = P(i, r ?? void 0),
        s = F(e, t, a.defaultLocale);
    if (!(o && N(o.domain) === N(a.domain))) return `${`http${a.http?``:`s`}://`}${a.domain}${I(s,n)}`
}

function R(e, t, n) {
    Object.defineProperty(e, t, {
        value: n,
        enumerable: !0,
        writable: !0,
        configurable: !0
    })
}

function te(e, t, n) {
    if (Object.hasOwn(e, t)) {
        let r = e[t];
        R(e, t, Array.isArray(r) ? r.concat(n) : [r, n])
    } else R(e, t, n)
}

function z(e) {
    return typeof e == `string` ? e : typeof e == `number` && !isNaN(e) || typeof e == `boolean` ? String(e) : ``
}

function B(e) {
    let t = new URLSearchParams;
    for (let [n, r] of Object.entries(e)) {
        if (Array.isArray(r)) {
            for (let e of r) t.append(n, z(e));
            continue
        }
        t.set(n, z(r))
    }
    return t
}

function V(e, t) {
    let n = e.indexOf(`#`),
        r = n === -1 ? e : e.slice(0, n),
        i = n === -1 ? `` : e.slice(n),
        a = r.indexOf(`?`),
        o = a === -1 ? r : r.slice(0, a),
        s = a === -1 ? `` : r.slice(a + 1),
        c = new URLSearchParams(s);
    for (let [e, n] of t) c.append(e, n);
    let l = c.toString();
    return `${o}${l?`?${l}`:``}${i}`
}
var H = () => globalThis.__VINEXT_DEFAULT_LOCALE__ == null && globalThis.__VINEXT_LOCALE__ == null ? null : {
    locale: globalThis.__VINEXT_LOCALE__,
    locales: globalThis.__VINEXT_LOCALES__,
    defaultLocale: globalThis.__VINEXT_DEFAULT_LOCALE__,
    domainLocales: globalThis.__VINEXT_DOMAIN_LOCALES__,
    hostname: globalThis.__VINEXT_HOSTNAME__
};

function U() {
    return H()
}

function ne(e) {
    return e.nodeEnv === `production` && e.prefetch !== !1 && !e.isDangerous
}

function W(e) {
    let {
        href: t,
        basePath: n,
        currentOrigin: r
    } = e;
    if (!re(t)) return t;
    if (r === void 0) return null;
    try {
        let e = new URL(r),
            i = t.startsWith(`//`) ? new URL(t, e.origin) : new URL(t);
        return i.origin === e.origin ? n ? d(i.pathname, n) ? l(i.pathname, n) + i.search + i.hash : null : i.pathname + i.search + i.hash : null
    } catch {
        return null
    }
}

function re(e) {
    return e.startsWith(`http://`) || e.startsWith(`https://`) || e.startsWith(`//`)
}
var G = e(t(), 1),
    K = n(),
    q = (0, G.createContext)({
        pending: !1
    });

function ie() {
    return (0, G.useContext)(q)
}
var J = ``,
    ae = A();

function oe(e) {
    if (typeof e == `string`) return e;
    let t = e.pathname ?? `/`;
    if (e.query) {
        let n = B(e.query);
        t = V(t, n)
    }
    return t
}

function Y(e, t) {
    return t || e === !1 ? `disabled` : e === !0 ? `full` : `auto`
}

function se(e) {
    if (typeof window > `u`) return null;
    let t;
    try {
        t = new URL(e, window.location.href)
    } catch {
        return null
    }
    return t.origin === window.location.origin ? `${l(t.pathname,J)}${t.search}` : null
}

function X(e) {
    if (typeof window > `u`) return !1;
    let t = window.__VINEXT_LINK_PREFETCH_ROUTES__;
    if (!t) return !1;
    let n = se(e);
    if (n === null) return !1;
    let r = M(n, t, ae);
    return r ? !r.route.isDynamic : !1
}

function Z(e, t, n = `low`) {
    if (typeof window > `u`) return;
    let a = W({
        href: e,
        basePath: J,
        currentOrigin: window.location.origin
    });
    if (a == null) return;
    let s = o(a, window.location.href, J);
    (window.requestIdleCallback ?? (e => setTimeout(e, 100)))(() => {
        (async () => {
            if (typeof window.__VINEXT_RSC_NAVIGATE__ == `function`) {
                if (t === `auto` && !X(a)) return;
                let e = f(),
                    o = u(),
                    l = c({
                        interceptionContext: e
                    });
                o && l.set(v, o);
                let d = await r(s, l),
                    p = _.encodeCacheKey(d, e),
                    m = i();
                if (m.has(p)) return;
                m.add(p), h(d, fetch(d, {
                    headers: l,
                    credentials: `include`,
                    priority: n,
                    purpose: `prefetch`
                }), e, o)
            } else if (window.__NEXT_DATA__?.__vinext?.pageModuleUrl) {
                let e = document.createElement(`link`);
                e.rel = `prefetch`, e.href = s, e.as = `document`, document.head.appendChild(e)
            }
        })().catch(e => {
            console.error(`[vinext] RSC prefetch setup error:`, e)
        })
    })
}
var Q = null,
    $ = new WeakMap;

function ce() {
    return typeof window > `u` || typeof IntersectionObserver > `u` ? null : Q || (Q = new IntersectionObserver(e => {
        for (let t of e)
            if (t.isIntersecting) {
                let e = $.get(t.target);
                e && (e(), Q?.unobserve(t.target), $.delete(t.target))
            }
    }, {
        rootMargin: `250px`
    }), Q)
}

function le() {
    return typeof window < `u` ? window.__VINEXT_DEFAULT_LOCALE__ : U()?.defaultLocale
}

function ue() {
    return typeof window < `u` ? window.__NEXT_DATA__?.domainLocales : U()?.domainLocales
}

function de() {
    return typeof window < `u` ? window.location.hostname : U()?.hostname
}

function fe(e, t) {
    return L(e, t, {
        basePath: J,
        currentHostname: de(),
        domainItems: ue()
    })
}

function pe(e, t) {
    return t === !1 || t === void 0 || e.startsWith(`http://`) || e.startsWith(`https://`) || e.startsWith(`//`) ? e : fe(e, t) || F(e, t, le() ?? ``)
}
var me = (0, G.forwardRef)(function({
    href: e,
    as: t,
    replace: n = !1,
    prefetch: r,
    scroll: i = !0,
    children: c,
    onClick: l,
    onMouseEnter: u,
    onTouchStart: d,
    onNavigate: f,
    ...h
}, _) {
    let {
        locale: v,
        ...y
    } = h, b = t ?? oe(e), x = typeof b == `string` && a(b), S = pe(x ? `/` : b, v), C = m(S, J), [w, T] = (0, G.useState)(!1), E = (0, G.useRef)(!0);
    (0, G.useEffect)(() => (E.current = !0, () => {
        E.current = !1
    }), []);
    let D = (0, G.useRef)(null),
        O = Y(r, x),
        k = ne({
            nodeEnv: `production`,
            prefetch: r,
            isDangerous: x
        }),
        A = (0, G.useCallback)(e => {
            D.current = e, typeof _ == `function` ? _(e) : _ && (_.current = e)
        }, [_]);
    (0, G.useEffect)(() => {
        if (!k || typeof window > `u`) return;
        let e = D.current;
        if (!e) return;
        let t = W({
            href: S,
            basePath: J,
            currentOrigin: window.location.origin
        });
        if (t == null) return;
        let n = ce();
        if (n) return $.set(e, () => Z(t, O, `low`)), n.observe(e), () => {
            n.unobserve(e), $.delete(e)
        }
    }, [k, O, S]);
    let j = (0, G.useCallback)(() => {
            k && Z(S, O, `high`)
        }, [k, O, S]),
        M = (0, G.useCallback)(e => {
            u?.(e), j()
        }, [u, j]),
        N = (0, G.useCallback)(e => {
            d?.(e), j()
        }, [d, j]),
        P = async e => {
            if (l && l(e), e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.currentTarget.target && e.currentTarget.target !== `_self`) return;
            let t = S;
            if (b.startsWith(`http://`) || b.startsWith(`https://`) || b.startsWith(`//`)) {
                let e = s(b, J);
                if (e == null) return;
                t = e
            }
            e.preventDefault();
            let r = ee(t, window.location.href, J),
                a = o(t, window.location.href, J);
            if (f) try {
                let e = new URL(a, window.location.origin),
                    t = !1,
                    n = {
                        url: e,
                        preventDefault() {
                            t = !0
                        },
                        get defaultPrevented() {
                            return t
                        }
                    };
                if (f(n), n.defaultPrevented) return
            } catch {}
            if (typeof window.__VINEXT_RSC_NAVIGATE__ == `function`) {
                T(!0), G.startTransition(() => {
                    p(t, n ? `replace` : `push`, i, !0).finally(() => {
                        E.current && T(!1)
                    })
                });
                return
            } else try {
                let e = (await g(async () => {
                    let {
                        default: e
                    } = await import(`./router-DDfC9wtX.js`);
                    return {
                        default: e
                    }
                }, __vite__mapDeps([0, 1, 2, 3]))).default;
                n ? await e.replace(r, void 0, {
                    scroll: i
                }) : await e.push(r, void 0, {
                    scroll: i
                })
            } catch {
                n ? window.history.replaceState({}, ``, a) : window.history.pushState({}, ``, a), window.dispatchEvent(new PopStateEvent(`popstate`))
            }
        }, {
            passHref: F,
            ...I
        } = y, L = G.useMemo(() => ({
            pending: w
        }), [w]);
    return x ? (0, K.jsx)(`a`, {
        ...I,
        onMouseEnter: M,
        onTouchStart: N,
        children: c
    }) : (0, K.jsx)(q.Provider, {
        value: L,
        children: (0, K.jsx)(`a`, {
            ref: A,
            href: C,
            onClick: e => {
                P(e)
            },
            onMouseEnter: M,
            onTouchStart: N,
            ...I,
            children: c
        })
    })
});
export {
    L as a, X as canAutoPrefetchFullAppRoute, me as
    default, F as i, V as n, B as r, Y as resolveLinkPrefetchMode, te as t, ie as useLinkStatus
};