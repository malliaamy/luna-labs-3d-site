import {
    r as e
} from "./rolldown-runtime-S-ySWqyJ.js?r=3";
import {
    i as t,
    r as n
} from "./framework-CXnKph_e.js?r=3";
import {
    c as r
} from "./index-Bbr6xAeW.js?r=3";
import {
    t as i
} from "./UiArrow-DULi32Yj.js?r=3";
import a from "./link-eY7mD1BO.js?r=3";
var o = e(t(), 1),
    s = n(),
    c = [{
        href: `/work`,
        label: `Work`,
        index: `01`
    }, {
        href: `/services`,
        label: `Services`,
        index: `02`
    }, {
        href: `/shop`,
        label: `Shop`,
        index: `03`
    }, {
        href: `/about`,
        label: `About`,
        index: `04`
    }, {
        href: `/services#quote`,
        label: `Start a project`,
        index: `05`,
        cta: !0
    }];

function l() {
    let [e, t] = (0, o.useState)(!1), n = (0, o.useRef)(null), l = r();
    return (0, o.useEffect)(() => {
        if (!e) return;
        let r = e => {
                e.key === `Escape` && t(!1)
            },
            i = e => {
                n.current?.contains(e.target) || t(!1)
            };
        return window.addEventListener(`keydown`, r), window.addEventListener(`pointerdown`, i), () => {
            window.removeEventListener(`keydown`, r), window.removeEventListener(`pointerdown`, i)
        }
    }, [e]), (0, s.jsxs)(`div`, {
        className: `mobile-menu`,
        ref: n,
        children: [(0, s.jsxs)(`button`, {
            className: `mobile-menu-trigger`,
            type: `button`,
            "aria-expanded": e,
            "aria-controls": `mobile-navigation`,
            onClick: () => t(e => !e),
            children: [(0, s.jsx)(`span`, {
                children: e ? `Close` : `Menu`
            }), (0, s.jsxs)(`span`, {
                className: `menu-glyph`,
                "aria-hidden": `true`,
                children: [(0, s.jsx)(`i`, {}), (0, s.jsx)(`i`, {})]
            })]
        }), (0, s.jsx)(`div`, {
            className: `mobile-menu-panel${e?` is-open`:``}`,
            id: `mobile-navigation`,
            "aria-hidden": !e,
            children: c.map(n => {
                let r = n.href.split(`#`)[0],
                    o = r === `/shop` && (l.startsWith(`/product/`) || l === `/cart` || l === `/checkout` || l === `/account`),
                    c = l === r || l.startsWith(`${r}/`) || o;
                return (0, s.jsxs)(a, {
                    className: [c ? `is-active` : ``, n.cta ? `is-cta` : ``].filter(Boolean).join(` `) || void 0,
                    href: n.href,
                    tabIndex: e ? 0 : -1,
                    "aria-current": c ? `page` : void 0,
                    onClick: () => t(!1),
                    children: [(0, s.jsx)(`span`, {
                        children: n.index
                    }), (0, s.jsx)(`strong`, {
                        children: n.label
                    }), n.cta ? (0, s.jsx)(i, {}) : null]
                }, n.href)
            })
        })]
    })
}
var u = [{
    href: `/work`,
    label: `Work`
}, {
    href: `/services`,
    label: `Services`
}, {
    href: `/shop`,
    label: `Shop`
}, {
    href: `/about`,
    label: `About`
}];

function d() {
    let e = r(),
        t = t => t === `/shop` && (e.startsWith(`/product/`) || e === `/cart` || e === `/checkout` || e === `/account`);
    return (0, s.jsx)(`header`, {
        className: `site-header${e===`/about`?` site-header-about`:``}`,
        children: (0, s.jsxs)(`nav`, {
            className: `nav`,
            "aria-label": `Main navigation`,
            children: [(0, s.jsxs)(a, {
                className: `brand`,
                href: `/`,
                "aria-label": `Luna Labs home`,
                children: [(0, s.jsx)(`span`, {
                    className: `brand-mark`,
                    "aria-hidden": `true`,
                    children: (0, s.jsx)(`img`, {
                        src: `./assets/luna-labs-mark.png`,
                        alt: ``
                    })
                }), (0, s.jsxs)(`span`, {
                    className: `brand-type`,
                    children: [(0, s.jsx)(`strong`, {
                        children: `LUNA LABS`
                    }), (0, s.jsx)(`small`, {
                        children: `SCULPT · PRINT · PAINT`
                    })]
                })]
            }), (0, s.jsx)(`div`, {
                className: `nav-links`,
                children: u.map(n => {
                    let r = e === n.href || e.startsWith(`${n.href}/`) || t(n.href);
                    return (0, s.jsx)(a, {
                        className: r ? `is-active` : void 0,
                        href: n.href,
                        "aria-current": r ? `page` : void 0,
                        children: n.label
                    }, n.href)
                })
            }), (0, s.jsxs)(`div`, {
                className: `nav-actions`,
                children: [(0, s.jsxs)(a, {
                    className: `nav-cta`,
                    href: `/services#quote`,
                    children: [`Start a project `, (0, s.jsx)(i, {})]
                }), (0, s.jsx)(l, {})]
            })]
        })
    })
}
export {
    d as
    default
};