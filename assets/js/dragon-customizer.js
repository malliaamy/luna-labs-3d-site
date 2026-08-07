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
const canvasHost = root ? root.querySelector("[data-dragon-canvas]") : null;

if (root && canvasHost) {
  const status = root.querySelector("[data-dragon-status]");
  const defaults = {
    dice_tray: "#272c30", stone_tower: "#5b6168", dragon_body: "#ad2d2d",
    dragon_head: "#ad2d2d", dragon_tail: "#ad2d2d", front_wing: "#ba7c26", back_wing: "#c23c3c"
  };
  const partMaterials = {};

  const showStatus = (message, isError = false) => {
    if (!status || !status.isConnected) return;
    status.classList.toggle("is-error", isError);
    status.replaceChildren();
    if (!isError) status.append(document.createElement("span"));
    status.append(document.createTextNode(message));
  };

  const showFallback = message => {
    canvasHost.classList.add("has-model-fallback");
    canvasHost.setAttribute("aria-label", "Finished hand-painted Dragon Dice Tower photograph.");
    let image = canvasHost.querySelector(".tower-model-fallback");
    if (!image && root.dataset.fallbackUrl) {
      image = new Image();
      image.className = "tower-model-fallback";
      image.src = root.dataset.fallbackUrl;
      image.alt = "Finished hand-painted Dragon Dice Tower";
      canvasHost.prepend(image);
    }
    showStatus(message, true);
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

  try {
    const scene = new Scene();
    const camera = new PerspectiveCamera(29, 1, .1, 100);
    camera.position.set(0, .1, 6.7);
    const renderer = new WebGLRenderer({antialias: true, alpha: true, powerPreference: "high-performance"});
    renderer.setPixelRatio(Math.min(devicePixelRatio, 1.75));
    renderer.setClearColor(0x000000, 0);
    canvasHost.append(renderer.domElement);

    renderer.domElement.addEventListener("webglcontextlost", event => {
      event.preventDefault();
      showFallback("The interactive preview paused. You can still choose colours and order.");
    }, {once: true});

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
    let drag = null;
    let modelReady = false;

    const slowTimer = window.setTimeout(() => {
      if (!modelReady) showStatus("Loading the detailed sculpt · this may take a moment");
    }, 8000);
    const timeoutTimer = window.setTimeout(() => {
      if (!modelReady) showFallback("The 3D preview is taking too long. You can still choose colours and order.");
    }, 45000);

    const loader = new GLTFLoader();
    loader.setMeshoptDecoder(MeshoptDecoder);
    loader.load(root.dataset.modelUrl, gltf => {
      modelReady = true;
      window.clearTimeout(slowTimer);
      window.clearTimeout(timeoutTimer);
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
        const part = mapName(object.name || (object.parent && object.parent.name));
        object.material = materialFor(part);
        object.frustumCulled = true;
      });
      group.add(model);
      canvasHost.classList.remove("has-model-fallback");
      const fallbackImage = canvasHost.querySelector(".tower-model-fallback");
      if (fallbackImage) fallbackImage.remove();
      if (status) status.remove();
    }, event => {
      if (!status || modelReady || !event.lengthComputable || !event.total) return;
      const percent = Math.min(99, Math.round(event.loaded / event.total * 100));
      showStatus("Loading the sculpt · " + percent + "%");
    }, () => {
      window.clearTimeout(slowTimer);
      window.clearTimeout(timeoutTimer);
      showFallback("The 3D preview could not be loaded. You can still choose colours and order.");
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
    const resetButton = root.querySelector("[data-dragon-reset]");
    if (resetButton) resetButton.addEventListener("click", () => group.rotation.set(-.04, -.08, 0));

    const clock = new Clock();
    const render = () => {
      group.position.y = Math.sin(clock.getElapsedTime() * .75) * .025;
      renderer.render(scene, camera);
      requestAnimationFrame(render);
    };
    render();
  } catch (error) {
    showFallback("3D is unavailable on this device. You can still choose colours and order.");
  }
}
