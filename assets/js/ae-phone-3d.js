/**
 * AE phone hero: a 3D phone that turns as you scroll and shows, screen by
 * screen, how Front Desk handles a call.
 *
 * Markup (front-page.php): section[data-ae-phone] holds the hero intro
 * [data-ae-phone-intro], a sticky stage with one [data-ae-step] per step, and
 * canvas[data-ae-phone-canvas]. Between steps the phone makes one full turn
 * and the screen changes while its back faces you.
 *
 * The phone is built in code (no model file to download): flat metal frame,
 * frosted glass back, camera plateau, capsule buttons, antenna lines, a
 * reflective screen. No branding anywhere on it.
 *
 * Optional: to use a licensed .glb model instead, set
 * data-ae-phone-model="/path/phone.glb" on the section. The mesh whose name
 * contains "screen" or "display" gets the live screen (its UVs must span 0-1).
 *
 * Reduced motion: no turning or floating; screens still change with scroll.
 * No WebGL: the section gets .is-flat and the steps read as a plain list.
 */
import * as THREE from 'three';

const FONT = "-apple-system, BlinkMacSystemFont, 'SF Pro Text', system-ui, 'Segoe UI', Roboto, Arial, sans-serif";
const BUSINESS = 'Reyes Plumbing';
// Phone UI colours (generic dark-mode system colours), only on the screen.
const RED = '#FF453A', GREEN = '#30D158', BLUE = '#0A84FF', ORANGE = '#FF9F0A';

// Phone size in scene units (proportions of a current 6.1 inch phone).
const W = 3, H = 6.18, D = 0.3, B = 0.04, R = 0.5;

function init(root) {
  const canvas = root.querySelector('[data-ae-phone-canvas]');
  const intro = root.querySelector('[data-ae-phone-intro]');
  const stage = root.querySelector('.ae-ph__stage');
  const steps = [...root.querySelectorAll('[data-ae-step]')];
  const STEPS = SCREENS.length;
  const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  let renderer;
  try {
    // Edge smoothing (MSAA) always on: the phone's thin bright edges (frame,
    // glass bevels, buttons) stair-step without it, even at 2x, and more so
    // when adaptive resolution steps down. 'high-performance' asks laptops
    // for their faster GPU.
    renderer = new THREE.WebGLRenderer({
      canvas, alpha: true, antialias: true, powerPreference: 'high-performance',
    });
    if (!renderer.getContext()) throw new Error('no gl');
  } catch (e) {
    root.classList.add('is-flat');
    return;
  }
  root.classList.add('is-3d');
  // Render resolution starts at the screen's (up to 2x) and adapts: see frame().
  const maxDpr = Math.min(window.devicePixelRatio || 1, 2);
  // Never below 1.5x on high density screens, where 1x looks soft and jagged.
  const minDpr = Math.min(maxDpr, (window.devicePixelRatio || 1) >= 2 ? 1.5 : 1);
  let dpr = maxDpr;
  renderer.setPixelRatio(dpr);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.0;

  const scene = new THREE.Scene();
  const pmrem = new THREE.PMREMGenerator(renderer);
  // A dark photo studio with softboxes: crisp, controlled highlights on the
  // metal and glass, the way product shots are lit.
  scene.environment = pmrem.fromScene(studio(), 0.012).texture;
  scene.environmentIntensity = 1;
  const key = new THREE.DirectionalLight(0xffffff, 1.1);
  key.position.set(5, 7, 9);
  const rim = new THREE.DirectionalLight(0xffffff, 1.6);
  rim.position.set(-7, 3, -5);
  scene.add(key, rim, new THREE.AmbientLight(0xffffff, 0.08));

  const camera = new THREE.PerspectiveCamera(28, 1, 0.1, 100);

  // ── Live screen texture (full phone resolution) ───────────────────────
  const SW = W - 0.15, SH = H - 0.15, SR = R - 0.07;
  // Full phone resolution on desktop; lighter on phones so redraws stay smooth.
  const TW = window.matchMedia('(max-width: 860px)').matches ? 900 : 1290;
  const tex = document.createElement('canvas');
  tex.width = TW;
  tex.height = Math.round(TW * SH / SW);
  const ctx = tex.getContext('2d');
  // Second canvas: the next screen, which slides in from below.
  const tex2 = document.createElement('canvas');
  tex2.width = tex.width;
  tex2.height = tex.height;
  const ctx2 = tex2.getContext('2d');
  const screenTex = new THREE.CanvasTexture(tex);
  screenTex.colorSpace = THREE.SRGBColorSpace;
  // No mipmaps: they were rebuilt on every screen redraw (the costliest part
  // of an upload). The texture is sized close to the screen's largest size on
  // the display, so plain linear filtering stays sharp.
  screenTex.generateMipmaps = false;
  screenTex.minFilter = THREE.LinearFilter;
  const screenTex2 = new THREE.CanvasTexture(tex2);
  screenTex2.colorSpace = THREE.SRGBColorSpace;
  screenTex2.generateMipmaps = false;
  screenTex2.minFilter = THREE.LinearFilter;

  const phone = new THREE.Group();
  scene.add(phone);
  const built = buildPhone(screenTex, screenTex2, SW, SH, SR);
  const screenMat = built.userData.screenMat;
  phone.add(built);

  // Optional licensed model in place of the built phone.
  const modelUrl = root.dataset.aePhoneModel;
  if (modelUrl) {
    import('./vendor/loaders/GLTFLoader.js').then(({ GLTFLoader }) => {
      new GLTFLoader().load(modelUrl, (gltf) => {
        const model = gltf.scene;
        const box = new THREE.Box3().setFromObject(model);
        const size = box.getSize(new THREE.Vector3());
        model.scale.setScalar(H / size.y);
        box.setFromObject(model);
        model.position.sub(box.getCenter(new THREE.Vector3()));
        model.traverse((o) => {
          if (o.isMesh && /screen|display/i.test(o.name)) {
            o.material = new THREE.MeshBasicMaterial({ map: screenTex, toneMapped: false });
          }
        });
        phone.remove(built);
        phone.add(model);
        dirty = true;
      });
    }).catch(() => {});
  }

  // ── Layout ────────────────────────────────────────────────────────────
  let baseZ = 20, camX = 0, camY = 0, camZ = 20;
  // Phones: measure the intro's text so the phone's area ends where it starts.
  const narrow = window.matchMedia('(max-width: 860px)');
  // Once the steps take over, the band shrinks to the tallest step, so the
  // phone grows into the space the headline needed.
  const story = root.querySelector('.ae-ph__story');
  let stepBand = 0;
  let bandTarget = 0, band = -1; // px of text under the phone (phones only)
  function measureSteps() {
    let max = 0;
    for (const el of steps) {
      const was = el.classList.contains('is-active');
      el.classList.add('is-active');
      max = Math.max(max, el.getBoundingClientRect().height);
      if (!was) el.classList.remove('is-active');
    }
    stepBand = Math.ceil(max) + 8;
  }
  function measureBand() {
    if (!intro || !narrow.matches) { root.style.removeProperty('--ae-ph-band'); bandTarget = 0; return; }
    if (root.classList.contains('is-story') && stepBand) {
      root.style.setProperty('--ae-ph-band', stepBand + 'px');
      bandTarget = stepBand;
      return;
    }
    const kids = [...intro.children].filter((el) => el.offsetParent !== null);
    if (!kids.length) return;
    const top = kids[0].getBoundingClientRect().top;
    const bottom = kids[kids.length - 1].getBoundingClientRect().bottom;
    bandTarget = Math.ceil(bottom - top);
    root.style.setProperty('--ae-ph-band', bandTarget + 'px');
  }
  if (intro) new ResizeObserver(() => { measureSteps(); measureBand(); }).observe(intro);
  measureSteps();
  measureBand();

  // The canvas keeps one size (resized only when the screen itself changes).
  // On phones it spans the whole stage and the camera frames the phone in
  // the space above the text, so changing that space just glides the camera.
  let canvasH = 1, tan = 0.25, byW = 0;
  function frameFor(bandPx) {
    if (!narrow.matches) return { z: Math.max((H * 1.14) / (2 * tan), byW), dy: 0 };
    const avail = Math.max(canvasH * 0.3, canvasH - bandPx - 12);
    const z = Math.max((H * 1.04 * canvasH) / (2 * tan * avail), byW);
    const dy = ((canvasH - avail) / 2 / canvasH) * 2 * z * tan; // lift the phone above the text
    return { z, dy };
  }
  function resize() {
    const w = canvas.clientWidth, h = canvas.clientHeight;
    if (!w || !h) return;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    canvasH = h;
    tan = Math.tan(THREE.MathUtils.degToRad(camera.fov / 2));
    byW = (W * 1.5) / (2 * tan * camera.aspect);
    baseZ = frameFor(band < 0 ? bandTarget : band).z;
    if (camZ === 20) camZ = baseZ;
    camera.position.set(camX, camY, camZ);
    camera.updateProjectionMatrix();
    dirty = true;
  }
  new ResizeObserver(resize).observe(canvas);

  // ── Scroll ────────────────────────────────────────────────────────────
  // Progress runs over the part of the section where the stage is stuck.
  let progress = 0;
  // Page geometry is measured once (and again on resize); a scroll then only
  // reads window.scrollY, so scrolling never forces a layout pass.
  let rootTop = 0, start = 0, vh = 1, total = 1;
  function measureScroll() {
    rootTop = root.getBoundingClientRect().top + window.scrollY;
    // Where the stage starts sticking. (A sticky element's offsetTop moves
    // while it sticks, so measure from the intro instead.)
    start = intro && getComputedStyle(intro).position !== 'sticky' ? intro.offsetHeight : 0;
    // The stage's own height (100svh) stays put while Safari's bars slide in
    // and out, unlike window.innerHeight, so progress never jumps.
    vh = stage ? stage.clientHeight : window.innerHeight;
    total = Math.max(1, root.offsetHeight - start - vh);
  }
  function readScroll() {
    const y = window.scrollY - rootTop;
    progress = THREE.MathUtils.clamp((y - start) / total, 0, 0.9999);
    const story = y > vh * 0.12;
    if (story !== root.classList.contains('is-story')) {
      root.classList.toggle('is-story', story);
      measureBand();
    }
  }
  window.addEventListener('scroll', readScroll, { passive: true });
  window.addEventListener('resize', () => { if (Math.abs((stage ? stage.clientHeight : 0) - lastStageH) > 1) { lastStageH = stage ? stage.clientHeight : 0; measureScroll(); readScroll(); } });
  new ResizeObserver(() => { measureScroll(); readScroll(); }).observe(root);
  let lastStageH = stage ? stage.clientHeight : 0;

  let shownBase = -1, shownBaseT = -1, shownTop = -1, shownTopT = -1, liveBase = -1, liveTop = -1;
  let active = -1, dirty = true, running = false, raf = 0, lastDraw = -1;
  let smooth = 0, lastTime = 0;
  // Exponential easing per second, the same at 60 Hz and 120 Hz.
  const ease_ = (rate, dt) => 1 - Math.exp(-rate * dt);

  function update(time) {
    const dt = Math.min(0.1, Math.max(0, time - lastTime));
    lastTime = time;
    smooth += (progress - smooth) * (calm ? 1 : ease_(2.6, dt));
    // One sequence across the whole hero (see storyPose() below): a spin in at
    // the start, one slow sweep while the steps play out, a spin out at the end.
    const c = storyPose(smooth, STEPS, calm);
    const { i, f } = c;
    const sway = calm ? 0 : 1 - c.k * 0.6;
    phone.rotation.set(
      c.rx + 0.06 + sway * Math.sin(time * 0.22) * 0.02,
      c.ry - 0.22 + sway * Math.sin(time * 0.26) * 0.04,
      -0.035
    );
    phone.position.set(0, sway * Math.sin(time * 0.35) * 0.025, 0);

    // Camera: zoom and pan, eased so fast scrolling still glides.
    band = band < 0 ? bandTarget : band + (bandTarget - band) * ease_(3, dt);
    const fit = frameFor(band);
    baseZ = fit.z;
    const zoom = calm ? 1 : 1 + (c.focusZoom - 1) * c.k;
    const k = calm ? 1 : ease_(3.2, dt);
    camZ += (baseZ * zoom - camZ) * k;
    camX += ((calm ? 0 : c.fx * c.k) - camX) * k;
    camY += ((calm ? 0 : c.fy * c.k) - camY) * k;
    const lift = fit.dy * (camZ / baseZ);
    camera.position.set(camX, camY - lift, camZ);
    camera.lookAt(camX, camY - lift, 0);

    // Screens: this step's fills in as it goes; near the end of the step the
    // next one slides up from below (shader above). Redraws are capped at 30
    // a second (a new screen draws at once) and animated screens (ringing,
    // waveform) tick at 24 fps, so the GPU stays free for 120 Hz and above.
    const tq = Math.round(c.t * 40) / 40;
    const tick = Math.floor(time * 20);
    const due = time - lastDraw >= 1 / 30;
    if (i !== shownBase || (due && (tq !== shownBaseT || (SCREENS[i].live && tick !== liveBase)))) {
      lastDraw = time;
      drawScreen(ctx, tex.width, tex.height, i, tq, time);
      screenTex.needsUpdate = true;
      shownBase = i; shownBaseT = tq; liveBase = tick;
    }
    const nextI = Math.min(STEPS - 1, i + 1);
    if (c.slide > 0 && (nextI !== shownTop || (SCREENS[nextI].live && due && tick !== liveTop))) {
      drawScreen(ctx2, tex2.width, tex2.height, nextI, 0, time);
      screenTex2.needsUpdate = true;
      shownTop = nextI; liveTop = tick;
    }
    screenMat.uniforms.slide.value = c.slide;

    if (i !== active) {
      active = i;
      steps.forEach((el, k) => el.classList.toggle('is-active', k === i));
    }
  }

  // One frame per display refresh (60, 120, 144 Hz and up), with adaptive
  // render resolution against the measured refresh rate.
  const clock = new THREE.Clock();
  // The display's refresh interval, measured once with empty frames before
  // the scene renders, so a heavy first second cannot hide it.
  let refresh = 1 / 60;
  (function measure() {
    const ts = [];
    const tickR = (t) => {
      ts.push(t);
      if (ts.length < 24) { requestAnimationFrame(tickR); return; }
      const d = ts.slice(1).map((t2, k) => t2 - ts[k]).sort((x, y) => x - y);
      const med = d[Math.floor(d.length / 2)] / 1000;
      if (med > 1 / 400 && med < 1 / 20) refresh = med;
    };
    requestAnimationFrame(tickR);
  })();
  let last = 0, acc = 0, n = 0, good = 0, hold = 0;
  function frame(ts) {
    raf = requestAnimationFrame(frame);
    if (last) {
      const d = (ts - last) / 1000;
      if (d > 0 && d < 0.25) { acc += d; n++; }
    }
    last = ts;
    // Every second or so: frames running long (missing refreshes) step the
    // render resolution down a quarter; a few seconds at full rate step it
    // back up. Steps are rare, so there is no flicker.
    if (n >= Math.round(1 / refresh)) {
      const avg = acc / n;
      acc = 0; n = 0;
      if (hold > 0) hold--;
      else if (avg > refresh * 1.2 && dpr > minDpr) { dpr = Math.max(minDpr, dpr - 0.25); renderer.setPixelRatio(dpr); resize(); good = 0; hold = 1; }
      else if (avg < refresh * 1.05 && dpr < maxDpr && ++good >= 4) { dpr = Math.min(maxDpr, dpr + 0.25); renderer.setPixelRatio(dpr); resize(); good = 0; hold = 2; }
    }
    update(clock.getElapsedTime());
    renderer.render(scene, camera);
  }
  new IntersectionObserver(([e]) => {
    if (e.isIntersecting && !running) { running = true; last = 0; acc = 0; n = 0; raf = requestAnimationFrame(frame); }
    else if (!e.isIntersecting && running) { running = false; cancelAnimationFrame(raf); }
  }, { rootMargin: '120px' }).observe(canvas);

  measureScroll();
  readScroll();
  smooth = progress;
  resize();
  update(0);
  renderer.render(scene, camera);
}

// ── The phone model ───────────────────────────────────────────────────────
function buildPhone(screenTex, screenTex2, SW, SH, SR) {
  const g = new THREE.Group();
  const zFront = D / 2 + B, zBack = -(D / 2 + B);

  // Brushed titanium: anisotropic highlights that streak along the frame.
  const frameMat = new THREE.MeshPhysicalMaterial({
    color: 0x9a9a9f, metalness: 1, roughness: 0.27,
    anisotropy: 0.65, anisotropyRotation: Math.PI / 2,
    clearcoat: 0.25, clearcoatRoughness: 0.2,
  });
  const darkMat = new THREE.MeshStandardMaterial({ color: 0x0a0a0b, metalness: 0.2, roughness: 0.6 });

  // Flat sided frame with softly rounded edges.
  const frame = new THREE.ExtrudeGeometry(roundRect(W, H, R), {
    depth: D, bevelEnabled: true, bevelThickness: B, bevelSize: B, bevelSegments: 10, curveSegments: 64,
  });
  frame.translate(0, 0, -D / 2);
  g.add(new THREE.Mesh(frame, frameMat));

  // Front: black glass, then the screen, then a clear reflective layer.
  // Front glass: a thin slab whose rounded edge catches a line of light.
  const glassGeo = new THREE.ExtrudeGeometry(roundRect(W - 0.02, H - 0.02, R - 0.01), {
    depth: 0.004, bevelEnabled: true, bevelThickness: 0.014, bevelSize: 0.022, bevelSegments: 8, curveSegments: 64,
  });
  const glass = new THREE.Mesh(glassGeo, new THREE.MeshPhysicalMaterial({ color: 0x020202, metalness: 0, roughness: 0.02, clearcoat: 1, clearcoatRoughness: 0, ior: 1.52, specularIntensity: 1 }));
  glass.position.z = zFront - 0.012;
  g.add(glass);

  const screenGeo = new THREE.ShapeGeometry(roundRect(SW, SH, SR), 64);
  fitUV(screenGeo, SW, SH);
  // The screen slides from one step to the next like scrolling in an app:
  // the current screen (mapA) moves up and the next (mapB) comes in from
  // below. Done in the shader, so the slide is as smooth as the display.
  const screenMat = new THREE.ShaderMaterial({
    uniforms: { mapA: { value: screenTex }, mapB: { value: screenTex2 }, slide: { value: 0 } },
    vertexShader: 'varying vec2 vUv; void main(){ vUv = uv; gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0); }',
    fragmentShader: [
      'uniform sampler2D mapA; uniform sampler2D mapB; uniform float slide; varying vec2 vUv;',
      'void main(){',
      '  float y = vUv.y - slide;',
      '  gl_FragColor = y >= 0.0 ? texture2D(mapA, vec2(vUv.x, y)) : texture2D(mapB, vec2(vUv.x, y + 1.0));',
      '  #include <colorspace_fragment>',
      '}',
    ].join('\n'),
  });
  const screen = new THREE.Mesh(screenGeo, screenMat);
  g.userData.screenMat = screenMat;
  screen.position.z = zFront + 0.009;
  g.add(screen);

  const sheen = new THREE.Mesh(screenGeo, new THREE.MeshStandardMaterial({
    color: 0x000000, metalness: 0, roughness: 0.03, envMapIntensity: 0.025,
    transparent: true, blending: THREE.AdditiveBlending, depthWrite: false,
  }));
  sheen.position.z = zFront + 0.011;
  // Off: while zoomed in on the screen, the studio's overhead light showed in
  // it as a grey band across the top. Kept for reference.
  sheen.visible = false;
  g.add(sheen);

  // Earpiece slit at the very top of the glass.
  const ear = new THREE.Mesh(new THREE.CapsuleGeometry(0.012, 0.5, 4, 12), darkMat);
  ear.rotation.z = Math.PI / 2;
  ear.position.set(0, H / 2 + 0.002, zFront + 0.008);
  g.add(ear);

  // Back: frosted glass.
  // Back: frosted glass with a fine grain, polished at the edge.
  const backGeo = new THREE.ExtrudeGeometry(roundRect(W - 0.02, H - 0.02, R - 0.01), {
    depth: 0.004, bevelEnabled: true, bevelThickness: 0.012, bevelSize: 0.02, bevelSegments: 6, curveSegments: 64,
  });
  const back = new THREE.Mesh(backGeo, new THREE.MeshPhysicalMaterial({
    color: 0x34353a, metalness: 0.05, roughness: 0.6, roughnessMap: grain(),
    clearcoat: 0.35, clearcoatRoughness: 0.55, sheen: 0.3, sheenRoughness: 0.6, sheenColor: 0x9a9aa0,
  }));
  back.position.z = zBack + 0.012;
  back.rotation.y = Math.PI;
  g.add(back);

  // Camera plateau, top left of the back.
  const cam = new THREE.Group();
  const plateau = new THREE.ExtrudeGeometry(roundRect(1.22, 1.22, 0.34), {
    depth: 0.04, bevelEnabled: true, bevelSize: 0.03, bevelThickness: 0.03, bevelSegments: 6, curveSegments: 32,
  });
  cam.add(new THREE.Mesh(plateau, new THREE.MeshPhysicalMaterial({ color: 0x3a3b40, metalness: 0.2, roughness: 0.12, clearcoat: 1, clearcoatRoughness: 0.05 })));
  const ringMat = new THREE.MeshStandardMaterial({ color: 0x6a6b70, metalness: 1, roughness: 0.22 });
  const barrelMat = new THREE.MeshStandardMaterial({ color: 0x050506, metalness: 0.5, roughness: 0.3 });
  // Anti-reflective coating: a faint violet-green shift as the lens turns.
  const lensMat = new THREE.MeshPhysicalMaterial({
    color: 0x05060a, metalness: 0, roughness: 0.02, clearcoat: 1, clearcoatRoughness: 0,
    iridescence: 1, iridescenceIOR: 1.6, iridescenceThicknessRange: [180, 420],
  });
  const irisMat = new THREE.MeshStandardMaterial({ color: 0x141826, metalness: 0.8, roughness: 0.15 });
  for (const y of [0.27, -0.27]) {
    const lens = new THREE.Group();
    const ring = new THREE.Mesh(new THREE.CylinderGeometry(0.235, 0.24, 0.1, 64), ringMat);
    const barrel = new THREE.Mesh(new THREE.CylinderGeometry(0.2, 0.2, 0.104, 64), barrelMat);
    const glassL = new THREE.Mesh(new THREE.CylinderGeometry(0.165, 0.165, 0.108, 64), lensMat);
    const iris = new THREE.Mesh(new THREE.CircleGeometry(0.07, 48), irisMat);
    [ring, barrel, glassL].forEach((m) => { m.rotation.x = Math.PI / 2; });
    iris.position.z = 0.055;
    lens.add(ring, barrel, glassL, iris);
    lens.position.set(-0.27, y, 0.1);
    cam.add(lens);
  }
  const flash = new THREE.Mesh(new THREE.CylinderGeometry(0.07, 0.07, 0.03, 40), new THREE.MeshStandardMaterial({ color: 0xe8e4d8, roughness: 0.45 }));
  flash.rotation.x = Math.PI / 2;
  flash.position.set(0.3, 0.27, 0.075);
  const mic = new THREE.Mesh(new THREE.CircleGeometry(0.025, 24), darkMat);
  mic.position.set(0.3, -0.05, 0.072);
  cam.add(flash, mic);
  // Sit the plateau on the back, facing out.
  cam.rotation.y = Math.PI;
  cam.position.set(W / 2 - 0.86, H / 2 - 0.86, zBack - 0.004);
  g.add(cam);

  // Buttons: action + volume on the left, power on the right.
  const btn = (len, y, side) => {
    const b = new THREE.Mesh(new THREE.CapsuleGeometry(0.035, len, 6, 24), frameMat);
    b.scale.z = 1.7;
    b.position.set(side * (W / 2 + B + 0.012), y, 0);
    g.add(b);
  };
  btn(0.32, 1.95, -1);
  btn(0.55, 1.25, -1);
  btn(0.55, 0.5, -1);
  btn(0.85, 1.0, 1);

  // Antenna lines across the frame.
  const bandMat = new THREE.MeshStandardMaterial({ color: 0x1c1c1f, metalness: 0.3, roughness: 0.6 });
  for (const side of [-1, 1]) {
    for (const y of [H / 2 - 0.55, -H / 2 + 0.55]) {
      const band = new THREE.Mesh(new THREE.BoxGeometry(0.006, 0.022, D + B), bandMat);
      band.position.set(side * (W / 2 + B + 0.001), y, 0);
      g.add(band);
    }
  }
  for (const yS of [-1, 1]) {
    const band = new THREE.Mesh(new THREE.BoxGeometry(0.022, 0.006, D + B), bandMat);
    band.position.set(W / 2 - 0.9, yS * (H / 2 + B + 0.001), 0);
    g.add(band);
  }

  // Bottom edge: port and speaker holes.
  const port = new THREE.Mesh(new THREE.CapsuleGeometry(0.055, 0.32, 6, 24), darkMat);
  port.rotation.z = Math.PI / 2;
  port.scale.y = 1;
  port.position.set(0, -(H / 2 + B - 0.012), 0);
  port.scale.set(1, 1, 0.9);
  g.add(port);
  const hole = new THREE.CylinderGeometry(0.022, 0.022, 0.03, 16);
  for (const sx of [-1, 1]) {
    for (let k = 0; k < 5; k++) {
      const h = new THREE.Mesh(hole, darkMat);
      h.position.set(sx * (0.5 + k * 0.09), -(H / 2 + B - 0.01), 0);
      g.add(h);
    }
  }
  return g;
}

// ── Screens ───────────────────────────────────────────────────────────────
// ── Choreography ──────────────────────────────────────────────────────────
// One animation across the whole hero, driven by scroll (p runs 0 to 1):
//   Spin in: one full turn while the camera zooms in on the screen.
//   The call: one slow sweep of the phone while the five steps play out.
//     Each screen fills in, then the next slides up from below, like
//     scrolling in an app. No turns between steps.
//   Spin out: one full turn back while the camera zooms out to the whole
//     phone, on the morning summary.
// Reduced motion: no turns, zoom or sweep; the screens still fill in and slide.
const clamp01 = (v) => Math.min(1, Math.max(0, v));
const lerp = (a, b, t) => a + (b - a) * t;
const TAU = Math.PI * 2;

const INTRO = [0, 0.1];        // spin in, zoom in
const OUTRO = [0.9, 1];        // spin out, zoom out
const SLIDE_AT = 0.84;         // share of a step after which the next slides in
const CLOSE = 0.74;            // zoom while reading (1 = whole phone)

function span(p, [a, b]) { return easeInOut(clamp01((p - a) / (b - a))); }

function storyPose(p, steps, calm) {
  // Steps share the middle of the scroll evenly.
  const mid = clamp01((p - INTRO[1]) / (OUTRO[0] - INTRO[1]));
  const s = mid * steps;
  const i = Math.min(steps - 1, Math.floor(s));
  const f = i === steps - 1 && mid >= 1 ? 1 : s - i;
  const t = clamp01(f / 0.7);
  const slide = i < steps - 1 ? easeInOut(clamp01((f - SLIDE_AT) / (1 - SLIDE_AT))) : 0;
  if (calm) return { i, f, t, slide, rx: 0, ry: 0, k: 0, fx: 0, fy: 0, focusZoom: 1 };

  const inE = span(p, INTRO);
  const outE = span(p, OUTRO);
  const k = inE * (1 - outE);
  // One slow sweep from one side to the other across all five steps.
  const sweep = easeInOut(mid);
  const ry = -TAU * (1 - inE) - TAU * outE + lerp(-0.32, 0.32, sweep) * k;
  const rx = lerp(0.05, -0.05, sweep) * k;
  // The camera drifts gently down the screen over the whole call.
  const fy = lerp(0.7, 0.1, sweep);
  return { i, f, t, slide, rx, ry, k, fx: 0, fy, focusZoom: CLOSE };
}

const SCREENS = [
  { draw: incoming, live: true, clock: '6:42' },
  { draw: answering, live: true, clock: '6:42' },
  { draw: details, clock: '6:44' },
  { draw: textBack, clock: '6:47' },
  { draw: summary, clock: '7:00' },
];

function drawScreen(ctx, w, h, idx, t, time) {
  ctx.save();
  const grd = ctx.createLinearGradient(0, 0, 0, h);
  grd.addColorStop(0, '#141416');
  grd.addColorStop(1, '#09090a');
  ctx.fillStyle = grd;
  ctx.fillRect(0, 0, w, h);
  const s = w / 360; // laid out at 360 wide
  ctx.scale(s, s);
  const Hh = h / s;
  SCREENS[idx].draw(ctx, 360, Hh, t, time);
  statusBar(ctx, SCREENS[idx].clock);
  // Front camera: a small punch hole.
  ctx.fillStyle = '#000';
  ctx.beginPath(); ctx.arc(180, 30, 10, 0, Math.PI * 2); ctx.fill();
  ctx.fillStyle = 'rgba(40,50,80,.6)';
  ctx.beginPath(); ctx.arc(182, 28, 3, 0, Math.PI * 2); ctx.fill();
  // Home indicator.
  ctx.fillStyle = 'rgba(255,255,255,.85)';
  rr(ctx, 120, Hh - 16, 120, 5, 3); ctx.fill();
  ctx.restore();
}

function statusBar(ctx, clock) {
  text(ctx, clock, 40, 36, 16, 600, '#fff');
  ctx.fillStyle = '#fff';
  for (let k = 0; k < 4; k++) { rr(ctx, 262 + k * 6, 31 - k * 3, 4, 6 + k * 3, 1); ctx.fill(); }
  // wifi
  ctx.strokeStyle = '#fff'; ctx.lineWidth = 2; ctx.lineCap = 'round';
  for (let k = 0; k < 3; k++) { ctx.beginPath(); ctx.arc(296, 38, 4 + k * 4, Math.PI * 1.25, Math.PI * 1.75); ctx.stroke(); }
  ctx.lineWidth = 1.2;
  ctx.strokeStyle = 'rgba(255,255,255,.5)';
  rr(ctx, 310, 25, 26, 13, 4); ctx.stroke();
  ctx.fillStyle = '#fff';
  rr(ctx, 312, 27, 19, 9, 2.5); ctx.fill();
}

function incoming(ctx, w, h, t, time) {
  text(ctx, 'Incoming call', w / 2, 132, 17, 500, 'rgba(255,255,255,.7)', 'center');
  const cy = 260;
  for (let k = 0; k < 3; k++) {
    const p = (time * 0.7 + k / 3) % 1;
    ctx.strokeStyle = `rgba(48,209,88,${0.45 * (1 - p)})`;
    ctx.lineWidth = 1.5;
    ctx.beginPath(); ctx.arc(w / 2, cy, 52 + p * 64, 0, Math.PI * 2); ctx.stroke();
  }
  const av = ctx.createLinearGradient(0, cy - 52, 0, cy + 52);
  av.addColorStop(0, '#4a4a4f'); av.addColorStop(1, '#2a2a2d');
  ctx.fillStyle = av;
  ctx.beginPath(); ctx.arc(w / 2, cy, 52, 0, Math.PI * 2); ctx.fill();
  // person glyph
  ctx.fillStyle = 'rgba(255,255,255,.85)';
  ctx.beginPath(); ctx.arc(w / 2, cy - 12, 15, 0, Math.PI * 2); ctx.fill();
  ctx.beginPath(); ctx.ellipse(w / 2, cy + 26, 28, 18, 0, Math.PI, 0); ctx.fill();
  text(ctx, '(717) 555-0148', w / 2, 372, 30, 700, '#fff', 'center');
  text(ctx, 'Mobile · New caller', w / 2, 400, 15, 400, 'rgba(255,255,255,.6)', 'center');

  ctx.globalAlpha = ease(t);
  glassCard(ctx, 24, 440, w - 48, 92);
  text(ctx, 'You’re on a job site.', 44, 476, 16, 600, '#fff');
  text(ctx, 'Front Desk picks up by ring 2.', 44, 502, 15, 400, 'rgba(255,255,255,.72)');
  ctx.globalAlpha = 1;

  const by = h - 160;
  callBtn(ctx, 96, by, false, 'Decline');
  callBtn(ctx, w - 96, by, true, 'Front Desk');
}

function answering(ctx, w, h, t, time) {
  ctx.fillStyle = GREEN;
  ctx.beginPath(); ctx.arc(29, 91, 4.5, 0, Math.PI * 2); ctx.fill();
  text(ctx, 'Front Desk · live call', 40, 96, 14, 600, GREEN);
  text(ctx, BUSINESS, 24, 124, 24, 700, '#fff');
  const secs = 3 + Math.floor(t * 41);
  text(ctx, `0:${String(secs).padStart(2, '0')}`, w - 24, 124, 16, 500, 'rgba(255,255,255,.6)', 'right');
  const lines = [
    ['ai', 'Thanks for calling Reyes Plumbing. How can I help?'],
    ['caller', 'My water heater is leaking. Can someone come tomorrow?'],
    ['ai', 'I can set that up. What’s your name and address?'],
    ['caller', 'Dana Whitfield, 42 Elm Street.'],
  ];
  let y = 164;
  lines.forEach(([who, msg], k) => {
    const a = THREE.MathUtils.clamp(t * 4.2 - k, 0, 1);
    if (a <= 0) return;
    ctx.globalAlpha = a;
    y = bubble(ctx, who === 'ai' ? 'left' : 'right', msg, y + (1 - a) * 12, who === 'ai' ? 'Front Desk' : 'Caller') + 14;
    ctx.globalAlpha = 1;
  });
  const wy = h - 120;
  ctx.fillStyle = GREEN;
  for (let k = 0; k < 36; k++) {
    const amp = 5 + Math.abs(Math.sin(time * 6 + k * 0.7) * Math.cos(time * 2.3 + k * 0.3)) * 28;
    rr(ctx, 33 + k * 8.2, wy - amp / 2, 4, amp, 2); ctx.fill();
  }
  text(ctx, 'Answering in your business name', w / 2, h - 66, 13, 500, 'rgba(255,255,255,.55)', 'center');
}

function details(ctx, w, h, t) {
  text(ctx, 'Details captured', 24, 108, 26, 700, '#fff');
  text(ctx, 'From the call, while you work', 24, 134, 14, 400, 'rgba(255,255,255,.6)');
  glassCard(ctx, 20, 158, w - 40, 400);
  const rows = [
    ['Name', 'Dana Whitfield'], ['Phone', '(717) 555-0148'], ['Address', '42 Elm Street'],
    ['Job', 'Water heater leak'], ['Urgency', 'High'], ['Wants', 'Tomorrow morning'],
  ];
  rows.forEach(([k, v], n) => {
    const y = 202 + n * 60;
    const a = THREE.MathUtils.clamp(t * 7 - n, 0, 1);
    text(ctx, k, 40, y, 13, 500, 'rgba(255,255,255,.55)');
    ctx.globalAlpha = a;
    text(ctx, v, 40, y + 22, 17, 600, k === 'Urgency' ? ORANGE : '#fff');
    check(ctx, w - 52, y + 6);
    ctx.globalAlpha = 1;
    if (n < rows.length - 1) { ctx.fillStyle = 'rgba(255,255,255,.08)'; ctx.fillRect(40, y + 36, w - 80, 1); }
  });
  ctx.globalAlpha = ease(THREE.MathUtils.clamp(t * 2 - 1, 0, 1));
  pill(ctx, 20, 582, w - 40, 'Saved to your CRM · tagged “new”', true, GREEN);
  ctx.globalAlpha = 1;
}

function textBack(ctx, w, h, t) {
  text(ctx, 'Missed call text back', 24, 108, 24, 700, '#fff');
  text(ctx, 'Hung up before the greeting? They still hear from you.', 24, 134, 13, 400, 'rgba(255,255,255,.6)');
  let y = 172;
  const msgs = [
    ['right', 'Reyes Plumbing: Sorry we missed your call. What can we help with? Reply here and we’ll get you a quote.', 'Sent automatically · 6:46 pm'],
    ['left', 'Kitchen sink is clogged. Any chance this week?', 'Caller · 6:47 pm'],
    ['right', 'We can do Thursday at 2 pm or Friday at 10 am. Tap one to book.', 'Front Desk · 6:47 pm'],
  ];
  msgs.forEach(([side, m, label], k) => {
    const a = THREE.MathUtils.clamp(t * 3.6 - k, 0, 1);
    if (a <= 0) return;
    ctx.globalAlpha = a;
    y = bubble(ctx, side, m, y + (1 - a) * 12, label) + 16;
    ctx.globalAlpha = 1;
  });
  ctx.globalAlpha = ease(THREE.MathUtils.clamp(t * 3 - 2.2, 0, 1));
  pill(ctx, 24, y + 6, 150, 'Thu 2:00 pm', true, BLUE);
  pill(ctx, 186, y + 6, 150, 'Fri 10:00 am', false);
  ctx.globalAlpha = 1;
}

function summary(ctx, w, h, t) {
  text(ctx, 'Thursday, November 26', w / 2, 112, 16, 500, 'rgba(255,255,255,.75)', 'center');
  text(ctx, '7:00', w / 2, 196, 84, 700, '#fff', 'center');
  const a = ease(t);
  const y0 = 238 + (1 - a) * 30;
  ctx.globalAlpha = a;
  glassCard(ctx, 14, y0, w - 28, 334, 24);
  // Generic app icon: a white tile with a phone glyph.
  ctx.fillStyle = GREEN;
  rr(ctx, 32, y0 + 18, 32, 32, 8); ctx.fill();
  ctx.strokeStyle = '#fff'; ctx.lineWidth = 2.6; ctx.lineCap = 'round';
  ctx.beginPath(); ctx.arc(48, y0 + 38, 8, Math.PI * 1.1, Math.PI * 1.9); ctx.stroke();
  text(ctx, 'FRONT DESK', 76, y0 + 40, 12, 600, 'rgba(255,255,255,.7)');
  text(ctx, 'now', w - 32, y0 + 40, 12, 500, 'rgba(255,255,255,.55)', 'right');
  text(ctx, 'Overnight: 3 calls, 2 booked', 32, y0 + 84, 19, 700, '#fff');
  const items = [
    ['Dana W. · water heater leak · urgent', 0], ['Booked tomorrow 9:30 am', 1],
    ['Marcus T. · clogged sink', 0], ['Booked Thursday 2:00 pm', 1],
    ['Unknown · hung up · texted back', 0],
  ];
  items.forEach(([it, sub], k) => {
    ctx.globalAlpha = a * THREE.MathUtils.clamp(t * 6 - 1 - k * 0.6, 0, 1);
    text(ctx, it, sub ? 48 : 32, y0 + 120 + k * 30, sub ? 14 : 15, sub ? 500 : 600, sub ? GREEN : '#fff');
  });
  ctx.globalAlpha = a * THREE.MathUtils.clamp(t * 3 - 2, 0, 1);
  pill(ctx, 32, y0 + 272, 138, 'Open summary', true, BLUE);
  pill(ctx, 182, y0 + 272, 128, 'Call back', false);
  ctx.globalAlpha = 1;
}

// ── 2D helpers ────────────────────────────────────────────────────────────
function text(ctx, s, x, y, size, weight, color, align = 'left') {
  ctx.font = `${weight} ${size}px ${FONT}`;
  ctx.fillStyle = color;
  ctx.textAlign = align;
  ctx.textBaseline = 'alphabetic';
  ctx.fillText(s, x, y);
}
function wrap(ctx, s, max) {
  const out = [];
  let line = '';
  for (const wd of s.split(' ')) {
    const test = line ? line + ' ' + wd : wd;
    if (ctx.measureText(test).width > max && line) { out.push(line); line = wd; } else line = test;
  }
  if (line) out.push(line);
  return out;
}
function bubble(ctx, side, msg, y, label) {
  ctx.font = `400 15px ${FONT}`;
  const lines = wrap(ctx, msg, 232);
  const bw = Math.max(...lines.map((l) => ctx.measureText(l).width)) + 28;
  const bh = lines.length * 21 + 20;
  const x = side === 'left' ? 20 : 340 - bw;
  text(ctx, label, side === 'left' ? x + 4 : x + bw - 4, y, 11, 500, 'rgba(255,255,255,.5)', side);
  const by = y + 8;
  const mine = side === 'right';
  ctx.fillStyle = mine ? BLUE : 'rgba(255,255,255,.13)';
  rr(ctx, x, by, bw, bh, 18); ctx.fill();
  lines.forEach((l, k) => text(ctx, l, x + 14, by + 25 + k * 21, 15, 400, '#fff'));
  return by + bh;
}
function glassCard(ctx, x, y, w, h, r = 20) {
  ctx.fillStyle = 'rgba(255,255,255,.08)';
  rr(ctx, x, y, w, h, r); ctx.fill();
  ctx.strokeStyle = 'rgba(255,255,255,.12)'; ctx.lineWidth = 1;
  rr(ctx, x + 0.5, y + 0.5, w - 1, h - 1, r); ctx.stroke();
}
function pill(ctx, x, y, w, label, solid, color) {
  ctx.fillStyle = solid ? (color || '#fff') : 'rgba(255,255,255,.13)';
  rr(ctx, x, y, w, 40, 20); ctx.fill();
  text(ctx, label, x + w / 2, y + 26, 14, 600, solid && !color ? '#0b0b0c' : '#fff', 'center');
}
function callBtn(ctx, x, y, accept, label) {
  ctx.fillStyle = accept ? GREEN : RED;
  ctx.beginPath(); ctx.arc(x, y, 36, 0, Math.PI * 2); ctx.fill();
  ctx.save();
  ctx.translate(x, y);
  if (!accept) ctx.rotate(Math.PI * 0.75);
  ctx.strokeStyle = '#fff';
  ctx.lineWidth = 7; ctx.lineCap = 'round';
  ctx.beginPath(); ctx.arc(0, 8, 14, Math.PI * 1.12, Math.PI * 1.88); ctx.stroke();
  ctx.restore();
  text(ctx, label, x, y + 60, 13, 500, 'rgba(255,255,255,.78)', 'center');
}
function check(ctx, x, y) {
  ctx.fillStyle = GREEN;
  ctx.beginPath(); ctx.arc(x, y, 11, 0, Math.PI * 2); ctx.fill();
  ctx.strokeStyle = '#fff'; ctx.lineWidth = 2.4; ctx.lineCap = 'round';
  ctx.beginPath(); ctx.moveTo(x - 5, y); ctx.lineTo(x - 1, y + 4); ctx.lineTo(x + 5, y - 4); ctx.stroke();
}
function rr(ctx, x, y, w, h, r) {
  ctx.beginPath();
  if (ctx.roundRect) ctx.roundRect(x, y, w, h, r); else ctx.rect(x, y, w, h);
}

// ── 3D helpers ────────────────────────────────────────────────────────────
// Continuous corner rounding (a squircle like curve) rather than a plain arc.
function roundRect(w, h, r) {
  const s = new THREE.Shape();
  const x = -w / 2, y = -h / 2, k = 0.12 * r;
  s.moveTo(x + r, y);
  s.lineTo(x + w - r, y);
  s.bezierCurveTo(x + w - k, y, x + w, y + k, x + w, y + r);
  s.lineTo(x + w, y + h - r);
  s.bezierCurveTo(x + w, y + h - k, x + w - k, y + h, x + w - r, y + h);
  s.lineTo(x + r, y + h);
  s.bezierCurveTo(x + k, y + h, x, y + h - k, x, y + h - r);
  s.lineTo(x, y + r);
  s.bezierCurveTo(x, y + k, x + k, y, x + r, y);
  return s;
}
// Dark studio for reflections: softbox above, strip lights at the sides,
// a rim light behind. Values above 1 make them bright in the reflections.
function studio() {
  const room = new THREE.Scene();
  room.add(new THREE.Mesh(new THREE.BoxGeometry(40, 40, 40), new THREE.MeshBasicMaterial({ color: 0x161618, side: THREE.BackSide })));
  const box = (w, h, x, y, z, ry, rx, power) => {
    const m = new THREE.MeshBasicMaterial({ color: new THREE.Color(1, 1, 1).multiplyScalar(power), side: THREE.DoubleSide });
    const p = new THREE.Mesh(new THREE.PlaneGeometry(w, h), m);
    p.position.set(x, y, z);
    p.rotation.set(rx, ry, 0);
    room.add(p);
  };
  box(18, 10, 0, 14, 4, 0, Math.PI / 2, 7);         // overhead softbox
  box(4, 22, -14, 2, 4, Math.PI / 2, 0, 9);         // left strip
  box(4, 22, 14, 2, 2, -Math.PI / 2, 0, 6);         // right strip
  box(16, 8, 4, 6, 14, Math.PI, 0, 3);              // front fill, high
  box(20, 3, 0, 3, -14, 0, 0, 7);                   // rim, behind
  box(30, 6, 0, -14, 0, 0, -Math.PI / 2, 1.2);     // floor bounce
  return room;
}
// Fine noise for the frosted back's roughness.
function grain() {
  const c = document.createElement('canvas');
  c.width = c.height = 256;
  const g = c.getContext('2d');
  const img = g.createImageData(256, 256);
  for (let i = 0; i < img.data.length; i += 4) {
    const v = 150 + Math.random() * 60;
    img.data[i] = img.data[i + 1] = img.data[i + 2] = v;
    img.data[i + 3] = 255;
  }
  g.putImageData(img, 0, 0);
  const t = new THREE.CanvasTexture(c);
  t.wrapS = t.wrapT = THREE.RepeatWrapping;
  t.repeat.set(6, 12);
  return t;
}
function fitUV(geo, w, h) {
  const pos = geo.attributes.position, uv = geo.attributes.uv;
  for (let i = 0; i < pos.count; i++) uv.setXY(i, pos.getX(i) / w + 0.5, pos.getY(i) / h + 0.5);
  uv.needsUpdate = true;
}
function ease(t) { return 1 - Math.pow(1 - t, 3); }
function easeInOut(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }

const root = document.querySelector('[data-ae-phone]');
if (root) init(root);
