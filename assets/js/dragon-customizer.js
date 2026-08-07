import {
  _ as Vector3,
  g as Scene,
  d as MeshPhysicalMaterial,
  p as PerspectiveCamera,
  r as WebGLRenderer,
  c as AmbientLight,
  o as PointLight,
  n as GLTFLoader,
  s as Group,
  a as Clock,
  i as Box3,
  t as MeshoptDecoder,
  u as Mesh
} from "../../lab-home/assets/meshopt_decoder.module-CoX9bj3I.js";

const root = document.querySelector("[data-dragon-configurator]");
const canvasHost = root?.querySelector("[data-dragon-canvas]");
if (root && canvasHost) {
  const status = root.querySelector("[data-dragon-status]");
  const scene = new Scene();
  const camera = new PerspectiveCamera(29, 1, .1, 100);
  camera.position.set(0, .1, 6.7);
  const renderer = new WebGLRenderer({antialias: true, alpha: true, powerPreference: "high-performance"});
  renderer.setPixelRatio(Math.min(devicePixelRatio, 1.75));
  renderer.setClearColor(0x000000, 0);
  canvasHost.append(renderer.domElement);

  scene.add(new AmbientLight(0xffffff, 2.3));
  const key = new PointLight(0xffffff, 3.6);
  key.position.set(4, 5, 5);
  scene.add(key);
  const fill = new PointLight(0xffffff, 2.4);
  fill.position.set(-4, 2, -3);
  scene.add(fill);

  const group = new Group();
  group.rotation.set(-.04, -.08, 0);
  scene.add(group);
  const partMeshes = {};
  const partMaterials = {};
  let drag = null;
  const defaults = {
    dice_tray: "#272c30", stone_tower: "#5b6168", dragon_body: "#ad2d2d",
    dragon_head: "#ad2d2d", dragon_tail: "#ad2d2d", front_wing: "#ba7c26", back_wing: "#c23c3c"
  };
  const mapName = name => {
    const value = (name || "").toLowerCase();
    if (value.includes("wing1")) return "front_wing";
    if (value.includes("wing2")) return "back_wing";
    if (value.includes("head")) return "dragon_head";
    if (value.includes("tail")) return "dragon_tail";
    if (value.includes("body")) return "dragon_body";
    if (value.includes("tower")) return "stone_tower";
    if (value.includes("base") || value.includes("stairs")) return "dice_tray";
    return "stone_tower";
  };
  const materialFor = part => {
    if (!partMaterials[part]) {
      partMaterials[part] = new MeshPhysicalMaterial({
        color: defaults[part], roughness: .56, metalness: .02,
        clearcoat: .18, clearcoatRoughness: .55
      });
    }
    return partMaterials[part];
  };

  const loader = new GLTFLoader();
  loader.setMeshoptDecoder(MeshoptDecoder);
  loader.load(root.dataset.modelUrl, gltf => {
    const model = gltf.scene;
    model.rotation.x = 0;
    model.updateMatrixWorld(true);
    const box = new Box3().setFromObject(model);
    const size = box.getSize(new Vector3());
    const center = box.getCenter(new Vector3());
    const scale = 3 / Math.max(size.x, size.y, size.z);
    model.scale.setScalar(scale);
    model.position.sub(center.multiplyScalar(scale));
    model.position.y -= .05;
    model.traverse(object => {
      if (!(object instanceof Mesh)) return;
      const part = mapName(object.name || object.parent?.name);
      object.material = materialFor(part);
      (partMeshes[part] ||= []).push(object);
      object.frustumCulled = true;
    });
    group.add(model);
    status?.remove();
  }, undefined, () => {
    if (status) status.textContent = "The 3D preview could not be loaded.";
  });

  const resize = () => {
    const width = canvasHost.clientWidth;
    const height = canvasHost.clientHeight;
    renderer.setSize(width, height, false);
    camera.aspect = width / Math.max(height, 1);
    camera.updateProjectionMatrix();
  };
  new ResizeObserver(resize).observe(canvasHost);
  resize();

  canvasHost.addEventListener("pointerdown", event => {
    drag = {id: event.pointerId, x: event.clientX, rotation: group.rotation.y};
    canvasHost.setPointerCapture(event.pointerId);
  });
  canvasHost.addEventListener("pointermove", event => {
    if (!drag || drag.id !== event.pointerId) return;
    group.rotation.y = drag.rotation + (event.clientX - drag.x) * .012;
  });
  const stop = event => {
    if (!drag || drag.id !== event.pointerId) return;
    drag = null;
    if (canvasHost.hasPointerCapture(event.pointerId)) canvasHost.releasePointerCapture(event.pointerId);
  };
  canvasHost.addEventListener("pointerup", stop);
  canvasHost.addEventListener("pointercancel", stop);
  root.querySelector("[data-dragon-reset]")?.addEventListener("click", () => {
    group.rotation.set(-.04, -.08, 0);
  });

  let selectedPart = "dice_tray";
  const partButtons = [...root.querySelectorAll("[data-dragon-part]")];
  const selectPart = button => {
    selectedPart = button.dataset.dragonPart;
    partButtons.forEach(item => item.classList.toggle("active", item === button));
    root.querySelector("[data-selected-label]").textContent = button.querySelector("strong").textContent;
    root.querySelector("[data-selected-description]").textContent = button.querySelector("small").textContent;
    const swatch = root.querySelector(`[data-part-swatch="${selectedPart}"]`);
    root.querySelector("[data-selected-swatch]").style.background = swatch.style.background;
  };
  partButtons.forEach(button => button.addEventListener("click", () => selectPart(button)));
  root.querySelectorAll("[data-dragon-colour]").forEach(button => button.addEventListener("click", () => {
    const name = button.dataset.dragonColour;
    const hex = button.dataset.colourHex;
    materialFor(selectedPart).color.set(hex);
    root.querySelector(`[data-dragon-input="${selectedPart}"]`).value = name;
    root.querySelector(`[data-part-swatch="${selectedPart}"]`).style.background = hex;
    root.querySelector("[data-selected-swatch]").style.background = hex;
    root.querySelector("[data-colour-name]").textContent = name;
    root.querySelector("[data-colour-swatch]").style.background = hex;
    root.querySelectorAll("[data-dragon-colour]").forEach(item => item.classList.toggle("active", item === button));
  }));

  const clock = new Clock();
  const render = () => {
    const elapsed = clock.getElapsedTime();
    group.position.y = Math.sin(elapsed * .75) * .025;
    renderer.render(scene, camera);
    requestAnimationFrame(render);
  };
  render();
}
