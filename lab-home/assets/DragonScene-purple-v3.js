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
    c as a,
    d as o,
    f as s,
    g as c,
    h as l,
    i as u,
    l as d,
    m as f,
    n as p,
    o as m,
    p as h,
    r as g,
    s as _,
    t as v,
    u as y,
    w as q
} from "./meshopt_decoder.module-CoX9bj3I.js?r=3";
var b = e(t(), 1),
    x = n(),
    S = [{
        id: `sculpt`,
        label: `Wireframe`,
        index: `01`,
        note: `Lime topology`
    }, {
        id: `resin`,
        label: `Solid`,
        index: `02`,
        note: `Neutral surface render`
    }, {
        id: `painted`,
        label: `Colour`,
        index: `03`,
        note: `Experimental colour study`
    }];

function C() {
    let e = new o({
        color: 16777215,
        roughness: .53,
        metalness: .06,
        clearcoat: .28,
        clearcoatRoughness: .52,
        emissive: 1049856,
        emissiveIntensity: .22,
        vertexColors: !1
    });
    return e.onBeforeCompile = e => {
        e.vertexShader = e.vertexShader.replace(`void main() {`, `
varying vec3 vLunaPosition;
varying vec3 vLunaNormal;

void main() {`).replace(`#include <begin_vertex>`, `#include <begin_vertex>
vLunaPosition = position;
vLunaNormal = normal;`), e.fragmentShader = e.fragmentShader.replace(`#include <common>`, `#include <common>

varying vec3 vLunaPosition;
varying vec3 vLunaNormal;

float lunaHash(vec3 p) {
  p = fract(p * 0.3183099 + vec3(0.11, 0.17, 0.23));
  p *= 17.0;
  return fract(p.x * p.y * p.z * (p.x + p.y + p.z));
}

float lunaNoise(vec3 p) {
  vec3 i = floor(p);
  vec3 f = fract(p);
  f = f * f * (3.0 - 2.0 * f);
  return mix(
    mix(
      mix(lunaHash(i), lunaHash(i + vec3(1.0, 0.0, 0.0)), f.x),
      mix(lunaHash(i + vec3(0.0, 1.0, 0.0)), lunaHash(i + vec3(1.0, 1.0, 0.0)), f.x),
      f.y
    ),
    mix(
      mix(lunaHash(i + vec3(0.0, 0.0, 1.0)), lunaHash(i + vec3(1.0, 0.0, 1.0)), f.x),
      mix(lunaHash(i + vec3(0.0, 1.0, 1.0)), lunaHash(i + vec3(1.0, 1.0, 1.0)), f.x),
      f.y
    ),
    f.z
  );
}

float lunaFbm(vec3 p) {
  float value = 0.0;
  float amplitude = 0.55;
  for (int i = 0; i < 4; i++) {
    value += amplitude * lunaNoise(p);
    p = p * 2.03 + vec3(1.7, 2.1, 0.8);
    amplitude *= 0.48;
  }
  return value;
}
`).replace(`vec4 diffuseColor = vec4( diffuse, opacity );`, `
vec3 lunaP = (vLunaPosition - vec3(-24.65, 122.20, -7.51))
  / vec3(231.06, 241.47, 276.55);
float lunaHeight = clamp((90.88 - vLunaPosition.x) / 231.08, 0.0, 1.0);
float lunaRadius = length(
  vec2(vLunaPosition.y - 122.20, vLunaPosition.z + 7.51)
);
float lunaSurface = lunaFbm(lunaP * 8.0);
float lunaDetail = lunaFbm(lunaP * 23.0 + vec3(0.0, lunaHeight * 2.0, 0.0));

vec3 lunaStone = mix(
  vec3(0.12, 0.135, 0.16),
  vec3(0.31, 0.34, 0.42),
  clamp(lunaSurface * 0.95 + lunaHeight * 0.18, 0.0, 1.0)
);
float lunaOuter = smoothstep(46.0, 86.0, lunaRadius);
float lunaBase = 1.0 - smoothstep(0.12, 0.22, lunaHeight);
float lunaCreature = lunaOuter * smoothstep(0.16, 0.35, lunaHeight) * (1.0 - lunaBase);

vec3 lunaCrimson = mix(
  vec3(0.055, 0.004, 0.16),
  vec3(0.32, 0.035, 0.70),
  clamp(lunaSurface + 0.18, 0.0, 1.0)
);
float lunaGoldMask = smoothstep(
  0.58,
  0.87,
  lunaDetail + lunaHeight * 0.18 + abs(vLunaNormal.z) * 0.14
);
vec3 lunaGold = mix(
  vec3(0.13, 0.006, 0.34),
  vec3(0.52, 0.10, 0.94),
  lunaSurface
);
vec3 lunaCreaturePaint = mix(lunaCrimson, lunaGold, lunaGoldMask);

float lunaMossMask = smoothstep(0.69, 0.88, lunaDetail + (1.0 - lunaHeight) * 0.14)
  * (1.0 - lunaCreature) * smoothstep(0.12, 0.78, lunaHeight);
vec3 lunaMoss = lunaStone;

vec3 lunaPaint = lunaCreaturePaint;

float lunaBrush = 0.93 + 0.09 * sin(
  lunaP.y * 94.0 + lunaSurface * 8.0 + lunaP.x * 13.0
);
lunaPaint *= lunaBrush;
lunaPaint += vec3(0.18, 0.05, 0.28)
  * smoothstep(0.74, 0.95, lunaDetail)
  * (0.25 + lunaCreature * 0.75);

vec4 diffuseColor = vec4(lunaPaint, opacity);`)
    }, e.customProgramCacheKey = () => `luna-purple-dragon-v4`, e
}

function w() {
    let e = (0, b.useRef)(null),
        t = (0, b.useRef)(null),
        n = (0, b.useRef)([]),
        w = (0, b.useRef)({}),
        T = (0, b.useRef)({
            x: 0,
            y: 0
        }),
        E = (0, b.useRef)({
            active: !1,
            x: 0,
            y: 0,
            intent: null,
            rotation: 0
        }),
        D = (0, b.useRef)(0),
        [O, k] = (0, b.useState)(`sculpt`),
        [A, j] = (0, b.useState)(`loading`);
    return (0, b.useEffect)(() => {
        let b = e.current;
        if (!b) return;
        "scrollRestoration" in history && (history.scrollRestoration = `manual`), window.scrollTo(0, 0), E.current.active = !1, E.current.intent = null, E.current.rotation = 0, D.current = 0, T.current = {
            x: 0,
            y: 0
        };
        requestAnimationFrame(() => {
            window.scrollTo(0, 0), D.current = 0
        });
        let x = new c,
            S = new h(29, 1, .1, 100);
        S.position.set(0, .15, 6.2);
        let O;
        try {
            O = new g({
                antialias: !0,
                alpha: !0,
                powerPreference: `high-performance`
            })
        } catch {
            window.setTimeout(() => j(`error`), 0);
            return
        }
        O.setPixelRatio(Math.min(window.devicePixelRatio, 1.8)), O.outputColorSpace = l, O.toneMapping = 4, O.toneMappingExposure = 1.1, b.appendChild(O.domElement);
        let J = new URLSearchParams(window.location.search),
            G = e => {
                let t = Number.parseFloat(J.get(e));
                return Number.isFinite(t) ? t * Math.PI / 180 : null
            },
            X = G(`desktopAngle`),
            K = G(`mobileAngle`),
            Y = G(`scrollRotation`),
            B = window.matchMedia(`(max-width: 700px), (pointer: coarse)`),
            Q = () => B.matches ? K ?? -.8654 : X ?? -.08;
        let k = new _;
        k.rotation.set(-.04, Q(), 0), x.add(k), t.current = k;
        let A = {
            sculpt: new q({
                color: 13172541,
                wireframe: !0,
                transparent: !1,
                opacity: 1,
                vertexColors: !1,
                fog: !1,
                toneMapped: !1
            }),
            resin: new o({
                color: 13026234,
                roughness: .62,
                metalness: .02,
                clearcoat: .16,
                clearcoatRoughness: .58,
                emissive: 1118735,
                emissiveIntensity: .08,
                vertexColors: !1
            }),
            painted: C()
        };
        w.current = A, x.add(new a(16183524, 1513746, 2.4));
        let M = new m(16765869, 4.8);
        M.position.set(4, 5, 5), x.add(M);
        let N = new m(12975929, 4.2);
        N.position.set(-5, 2, -3), x.add(N);
        let P = new f(10974975, 6, 8);
        P.position.set(-3, -1, 3), x.add(P);
        let F = new f(16734767, 4.5, 7);
        F.position.set(3.4, -1.7, 2.6), x.add(F);
        let I = !1,
            Z = window.setTimeout(() => {
                I || j(`error`)
            }, 12e3),
            L = new p;
        L.setMeshoptDecoder(v), L.load(`./assets/dragon-tower-web-v2.glb?v=meshopt-20260808`, e => {
            if (I) return;
            window.clearTimeout(Z);
            let t = e.scene;
            t.rotation.x = 0, t.updateMatrixWorld(!0);
            [`Head`, `Tail`, `Body`, `Wing1`, `Wing2`].forEach(e => {
                let n = t.getObjectByName(e);
                n && n.traverse(e => {
                    e instanceof y && (e.userData.lunaDragon = !0)
                })
            });
            let d = t.getObjectByName(`Tower 3`);
            d && d.traverse(e => {
                e instanceof y && (e.userData.lunaDragon = !1)
            });
            let i = new u().setFromObject(t),
                a = i.getSize(new r),
                o = i.getCenter(new r),
                s = 3.25 / Math.max(a.x, a.y, a.z);
            t.scale.setScalar(s), t.position.sub(o.multiplyScalar(s)), t.position.x += .18, t.position.y -= .04;
            let c = [];
            t.traverse(e => {
                e instanceof y && (e.material = A.sculpt, e.frustumCulled = !0, c.push(e))
            }), n.current = c, window.__lunaMeshInfo = c.map((e, t) => ({
                i: t,
                name: e.name,
                parent: e.parent?.name || ``
            })), k.add(t), j(`ready`)
        }, void 0, () => {
            window.clearTimeout(Z), j(`error`)
        });
        let R = () => {
                let e = b.clientWidth,
                    t = b.clientHeight;
                O.setSize(e, t, !1), S.aspect = e / Math.max(t, 1), S.updateProjectionMatrix()
            },
            z = new ResizeObserver(R);
        z.observe(b), R();
        let V = () => {
                
                let e = b.closest(`.hero`);
                if (!(e instanceof HTMLElement)) return;
                let t = e.getBoundingClientRect();
                D.current = d.clamp((e.ownerDocument.documentElement.scrollTop || 0) / Math.max(t.height * .5, 1), 0, 1) * (Y ?? 1.45)
            };
        window.addEventListener(`scroll`, V, {
            passive: !0
        }), B.addEventListener(`change`, V), V();
        let H = 0,
            U = new i,
            $ = !0,
            W = () => {
                if (!$) {
                    H = 0;
                    return
                }
                let e = U.getElapsedTime(),
                    n = t.current;
                if (n) {
                    let t = T.current.y * .12 - .04,
                        r = E.current.rotation + D.current + T.current.x * .23 + Q();
          n.rotation.x += (t - n.rotation.x) * .08, n.rotation.y += (r - n.rotation.y) * (E.current.active ? .28 : .14), n.position.y = Math.sin(e * .75) * .045
                }
                O.render(x, S), H = requestAnimationFrame(W)
            },
            ee = new IntersectionObserver(e => {
                $ = !!e[0]?.isIntersecting, $ && H === 0 && W()
            }, {
                rootMargin: `120px`
            });
        ee.observe(b);
        return W(), () => {
            I = !0, window.clearTimeout(Z), cancelAnimationFrame(H), ee.disconnect(), z.disconnect(), window.removeEventListener(`scroll`, V), B.removeEventListener(`change`, V), O.dispose(), O.domElement.remove(), Object.values(A).forEach(e => e.dispose()), t.current = null, n.current = []
        }
    }, []), (0, b.useEffect)(() => {
        let e = w.current;
        e.resin && n.current.forEach(t => {
            let r = t.userData.lunaDragon === !0;
            t.material = O === `painted` ? r ? e.painted : e.resin : e[O]
        })
    }, [O]), (0, x.jsxs)(`div`, {
        className: A === `error` ? `dragon-experience is-fallback` : A === `ready` ? `dragon-experience is-ready` : `dragon-experience`,
        children: [(0, x.jsxs)(`div`, {
            className: `dragon-canvas`,
            ref: e,
            onPointerMove: e => {
                if (e.pointerType === `touch`) return;
                let t = e.currentTarget.getBoundingClientRect();
                if (T.current = {
                        x: (e.clientX - t.left) / t.width * 2 - 1,
                        y: (e.clientY - t.top) / t.height * 2 - 1
                    }, E.current.active) {
                    let t = e.clientX - E.current.x;
                    E.current.rotation += t * .009, E.current.x = e.clientX, E.current.y = e.clientY
                }
            },
            onPointerDown: e => {
                if (e.pointerType === `touch`) return;
                E.current.active = !0, E.current.intent = `horizontal`, E.current.x = e.clientX, E.current.y = e.clientY, e.currentTarget.setPointerCapture(e.pointerId)
            },
            onPointerUp: e => {
                E.current.active = !1, e.currentTarget.hasPointerCapture(e.pointerId) && e.currentTarget.releasePointerCapture(e.pointerId)
            },
            onPointerCancel: () => {
                E.current.active = !1
            },
            onLostPointerCapture: () => {
                E.current.active = !1
            },
            onPointerLeave: () => {
                T.current = {
                    x: 0,
                    y: 0
                }, E.current.active = !1
            },
            "aria-label": A === `error` ? `Finished Luna Labs dragon dice tower photograph.` : `Interactive 3D model of the Luna Labs dragon dice tower. Drag to rotate.`,
            children: [A === `error` && (0, x.jsx)(`img`, {
                className: `model-fallback model-poster`,
                src: `./assets/dragon-tower-01-v2.jpg`,
                alt: `Hand-painted Luna Labs dragon dice tower`
            }), A === `loading` && (0, x.jsxs)(`div`, {
                className: `model-status`,
                "aria-live": `polite`,
                children: [(0, x.jsx)(`span`, {}), `Preparing the sculpt`]
            }), A === `error` && (0, x.jsx)(`div`, {
                className: `model-status fallback-note`,
                children: `Interactive view unavailable · showing finished piece`
            })]
        }), (0, x.jsxs)(`div`, {
            className: `finish-picker`,
            "aria-label": `Choose model view`,
            children: [(0, x.jsx)(`span`, {
                className: `finish-picker-label`,
                children: `View model as`
            }), S.map(e => (0, x.jsxs)(`button`, {
                type: `button`,
                className: O === e.id ? `active` : ``,
                onClick: () => k(e.id),
                "aria-pressed": O === e.id,
                children: [(0, x.jsx)(`span`, {
                    children: e.index
                }), e.label]
            }, e.id))]
        }), (0, x.jsxs)(`p`, {
            className: `finish-note finish-note-${O}`,
            children: [(0, x.jsx)(`span`, {
                "aria-hidden": `true`
            }), (S.find(e => e.id === O) || {}).note]
        })]
    })
}
export {
    w as
    default
};
