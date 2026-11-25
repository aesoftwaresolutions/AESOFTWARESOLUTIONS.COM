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

// Phone size in scene units (proportions of a current 6.1 inch phone).
const W = 3, H = 6.18, D = 0.3, B = 0.04, R = 0.5;

function init(root) {
  const canvas = root.querySelector('[data-ae-phone-canvas]');
  const intro = root.querySelector('[data-ae-phone-intro]');
  const steps = [...root.querySelectorAll('[data-ae-step]')];
  const STEPS = SCREENS.length;
  const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    if (!renderer.getContext()) throw new Error('no gl');
  } catch (e) {
    root.classList.add('is-flat');
    return;
  }
  root.classList.add('is-3d');
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
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
  const tex = document.createElement('canvas');
  tex.width = 1290;
  tex.height = Math.round(1290 * SH / SW);
  const ctx = tex.getContext('2d');
  // Second canvas for crossfading one screen into the next.
  const tex2 = document.createElement('canvas');
  tex2.width = tex.width;
  tex2.height = tex.height;
  const ctx2 = tex2.getContext('2d');
  const screenTex = new THREE.CanvasTexture(tex);
  screenTex.colorSpace = THREE.SRGBColorSpace;
  screenTex.anisotropy = renderer.capabilities.getMaxAnisotropy();

  const phone = new THREE.Group();
  scene.add(phone);
  const built = buildPhone(screenTex, SW, SH, SR);
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
    if (!intro || !narrow.matches) { root.style.removeProperty('--ae-ph-band'); return; }
    if (root.classList.contains('is-story') && stepBand) {
      root.style.setProperty('--ae-ph-band', stepBand + 'px');
      return;
    }
    const kids = [...intro.children].filter((el) => el.offsetParent !== null);
    if (!kids.length) return;
    const top = kids[0].getBoundingClientRect().top;
    const bottom = kids[kids.length - 1].getBoundingClientRect().bottom;
    root.style.setProperty('--ae-ph-band', Math.ceil(bottom - top) + 'px');
  }
  if (intro) new ResizeObserver(() => { measureSteps(); measureBand(); }).observe(intro);
  measureSteps();
  measureBand();

  function resize() {
    const w = canvas.clientWidth, h = canvas.clientHeight;
    if (!w || !h) return;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    const tan = Math.tan(THREE.MathUtils.degToRad(camera.fov / 2));
    // Frame tighter on phones, where every pixel of height counts.
    const byH = (H * (narrow.matches ? 1.04 : 1.14)) / (2 * tan);
    const byW = (W * 1.5) / (2 * tan * camera.aspect);
    baseZ = Math.max(byH, byW);
    if (camZ === 20) camZ = baseZ;
    camera.position.set(camX, camY, camZ);
    camera.updateProjectionMatrix();
    dirty = true;
  }
  new ResizeObserver(resize).observe(canvas);

  // ── Scroll ────────────────────────────────────────────────────────────
  // Progress runs over the part of the section where the stage is stuck.
  let progress = 0;
  function readScroll() {
    const r = root.getBoundingClientRect();
    // Where the stage starts sticking. (A sticky element's offsetTop moves
    // while it sticks, so measure from the intro instead.)
    const start = intro && getComputedStyle(intro).position !== 'sticky' ? intro.offsetHeight : 0;
    const total = root.offsetHeight - start - window.innerHeight;
    progress = THREE.MathUtils.clamp((-r.top - start) / Math.max(total, 1), 0, 0.9999);
    const wasStory = root.classList.contains('is-story');
    root.classList.toggle('is-story', -r.top > window.innerHeight * 0.12);
    if (wasStory !== root.classList.contains('is-story')) measureBand();
    dirty = true;
  }
  window.addEventListener('scroll', readScroll, { passive: true });
  window.addEventListener('resize', readScroll);

  let shown = -1, shownT = -1, active = -1, dirty = true, running = false, raf = 0;
  let smooth = 0;

  function update(time) {
    smooth += (progress - smooth) * (calm ? 1 : 0.045);
    const s = smooth * STEPS;
    const i = Math.min(STEPS - 1, Math.floor(s));
    const f = s - i;

    // Each step has its own move (see CHOREO below). Reduced motion: none,
    // the screens just change with scroll.
    const c = calm ? STILL : CHOREO[i](f, time);
    const sway = calm ? 0 : 1 - c.k * 0.6;
    phone.rotation.set(
      c.rx + sway * (Math.sin(time * 0.25) * 0.03 + 0.06),
      c.ry + sway * (Math.sin(time * 0.3) * 0.06 - 0.22),
      c.rz - sway * 0.035
    );
    phone.position.set(c.px, c.py + sway * Math.sin(time * 0.45) * 0.04, 0);

    // Camera: zoom and pan, eased so fast scrolling still glides.
    if (!calm) {
      const zoom = c.zoom ?? 1 + (c.focusZoom - 1) * c.k;
      camZ += (baseZ * zoom - camZ) * 0.06;
      camX += (c.fx * c.k - camX) * 0.06;
      camY += (c.fy * c.k - camY) * 0.06;
      camera.position.set(camX, camY, camZ);
      camera.lookAt(camX, camY, 0);
    }

    // The screen crossfades from the last step to this one during the move.
    const mix = calm ? 1 : c.mix;
    const t = THREE.MathUtils.clamp(c.u / 0.6, 0, 1);
    const tq = Math.round(t * 90) / 90;
    const mq = Math.round(mix * 40) / 40;
    const key = i + mq;
    if (key !== shown || tq !== shownT || SCREENS[i].live || (mq < 1 && SCREENS[i - 1]?.live)) {
      if (mq < 1 && i > 0) {
        drawScreen(ctx, tex.width, tex.height, i - 1, 1, time);
        drawScreen(ctx2, tex.width, tex.height, i, tq, time);
        ctx.globalAlpha = mq;
        ctx.drawImage(tex2, 0, 0);
        ctx.globalAlpha = 1;
      } else {
        drawScreen(ctx, tex.width, tex.height, i, tq, time);
      }
      screenTex.needsUpdate = true;
      shown = key;
      shownT = tq;
    }

    if (i !== active) {
      active = i;
      steps.forEach((el, k) => el.classList.toggle('is-active', k === i));
    }
  }

  const clock = new THREE.Clock();
  function frame() {
    raf = requestAnimationFrame(frame);
    update(clock.elapsedTime);
    renderer.render(scene, camera);
  }
  new IntersectionObserver(([e]) => {
    if (e.isIntersecting && !running) { running = true; frame(); }
    else if (!e.isIntersecting && running) { running = false; cancelAnimationFrame(raf); }
  }, { rootMargin: '120px' }).observe(canvas);

  readScroll();
  smooth = progress;
  resize();
  update(0);
  renderer.render(scene, camera);
}

// ── The phone model ───────────────────────────────────────────────────────
function buildPhone(screenTex, SW, SH, SR) {
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
  const screen = new THREE.Mesh(screenGeo, new THREE.MeshBasicMaterial({ map: screenTex, toneMapped: false }));
  screen.position.z = zFront + 0.009;
  g.add(screen);

  const sheen = new THREE.Mesh(screenGeo, new THREE.MeshStandardMaterial({
    color: 0x000000, metalness: 0, roughness: 0.03, envMapIntensity: 0.18,
    transparent: true, blending: THREE.AdditiveBlending, depthWrite: false,
  }));
  sheen.position.z = zFront + 0.011;
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
// One function per step, each a different, gentle move. f runs 0 to 1 across
// the step. The first MOVE of it is the move into the step (p), while the
// screen crossfades (mix). The rest (u) eases in on the screen, drifts with
// the content as it appears, and eases back out at the end.
const MOVE = 0.4;
const clamp01 = (v) => Math.min(1, Math.max(0, v));
const lerp = (a, b, t) => a + (b - a) * t;
const bump = (p) => Math.sin(p * Math.PI);           // 0 -> 1 -> 0
const STILL = { rx: 0, ry: 0, rz: 0, px: 0, py: 0, k: 0, fx: 0, fy: 0, focusZoom: 1, u: 1, mix: 1 };

function settle(u, outAt = 0.8) {
  return easeInOut(clamp01(u / 0.4)) * (1 - easeInOut(clamp01((u - outAt) / 0.2)));
}
function phase(f) {
  const p = clamp01(f / MOVE);
  const u = clamp01((f - MOVE) / (1 - MOVE));
  return { p, u, moving: f < MOVE, mix: easeInOut(clamp01((p - 0.25) / 0.5)) };
}

const CHOREO = [
  // 1. Ring: a light buzz in short bursts while the camera eases in on the caller.
  (f, time) => {
    const u = clamp01(f / 0.9);
    const burst = (time % 2.2) < 0.5 && u < 0.7 ? 1 : 0;
    return { ...STILL, rz: Math.sin(time * 55) * 0.005 * burst, k: settle(u), fy: 0.45, focusZoom: 0.86, u };
  },
  // 2. Answered: the phone tips gently toward you.
  (f) => {
    const { p, u, moving, mix } = phase(f);
    if (moving) return { ...STILL, rx: -0.22 * bump(p), zoom: 1 + 0.05 * bump(p), u: 0, mix };
    return { ...STILL, k: settle(u), fy: lerp(0.7, 0.2, ease(clamp01(u / 0.7))), focusZoom: 0.84, u };
  },
  // 3. Details: a slow turn to the side and back.
  (f) => {
    const { p, u, moving, mix } = phase(f);
    if (moving) return { ...STILL, ry: 0.55 * bump(p), zoom: 1 + 0.07 * bump(p), u: 0, mix };
    return { ...STILL, k: settle(u), fy: lerp(0.6, -0.2, ease(clamp01(u / 0.75))), focusZoom: 0.85, u };
  },
  // 4. Text back: a soft glide sideways with a slight lean.
  (f) => {
    const { p, u, moving, mix } = phase(f);
    if (moving) return { ...STILL, px: -0.35 * bump(p), rz: 0.06 * bump(p), zoom: 1 + 0.04 * bump(p), u: 0, mix };
    const k = settle(u);
    return { ...STILL, ry: 0.18 * k, k, fx: 0.05, fy: lerp(0.6, 0, ease(clamp01(u / 0.75))), focusZoom: 0.85, u };
  },
  // 5. Summary: leans back, then rises a little toward you, like picking it
  //    up in the morning. Ends on the whole phone.
  (f) => {
    const { p, u, moving, mix } = phase(f);
    if (moving) return { ...STILL, rx: 0.3 * bump(p), py: -0.25 * bump(p), zoom: 1 + 0.06 * bump(p), u: 0, mix };
    return { ...STILL, k: settle(u, 0.6), fy: 0.3, focusZoom: 0.86, u };
  },
];

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
    ctx.strokeStyle = `rgba(255,255,255,${0.35 * (1 - p)})`;
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
  text(ctx, 'Front Desk · live call', 24, 96, 14, 600, 'rgba(255,255,255,.6)');
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
  ctx.fillStyle = '#fff';
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
    text(ctx, v, 40, y + 22, 17, 600, '#fff');
    check(ctx, w - 52, y + 6);
    ctx.globalAlpha = 1;
    if (n < rows.length - 1) { ctx.fillStyle = 'rgba(255,255,255,.08)'; ctx.fillRect(40, y + 36, w - 80, 1); }
  });
  ctx.globalAlpha = ease(THREE.MathUtils.clamp(t * 2 - 1, 0, 1));
  pill(ctx, 20, 582, w - 40, 'Saved to your CRM · tagged “new”', false);
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
  pill(ctx, 24, y + 6, 150, 'Thu 2:00 pm', true);
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
  ctx.fillStyle = '#fff';
  rr(ctx, 32, y0 + 18, 32, 32, 8); ctx.fill();
  ctx.strokeStyle = '#0b0b0c'; ctx.lineWidth = 2.6; ctx.lineCap = 'round';
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
    text(ctx, it, sub ? 48 : 32, y0 + 120 + k * 30, sub ? 14 : 15, sub ? 400 : 600, sub ? 'rgba(255,255,255,.65)' : '#fff');
  });
  ctx.globalAlpha = a * THREE.MathUtils.clamp(t * 3 - 2, 0, 1);
  pill(ctx, 32, y0 + 272, 138, 'Open summary', true);
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
  ctx.fillStyle = mine ? '#ffffff' : 'rgba(255,255,255,.13)';
  rr(ctx, x, by, bw, bh, 18); ctx.fill();
  lines.forEach((l, k) => text(ctx, l, x + 14, by + 25 + k * 21, 15, 400, mine ? '#0b0b0c' : '#fff'));
  return by + bh;
}
function glassCard(ctx, x, y, w, h, r = 20) {
  ctx.fillStyle = 'rgba(255,255,255,.08)';
  rr(ctx, x, y, w, h, r); ctx.fill();
  ctx.strokeStyle = 'rgba(255,255,255,.12)'; ctx.lineWidth = 1;
  rr(ctx, x + 0.5, y + 0.5, w - 1, h - 1, r); ctx.stroke();
}
function pill(ctx, x, y, w, label, solid) {
  ctx.fillStyle = solid ? '#fff' : 'rgba(255,255,255,.13)';
  rr(ctx, x, y, w, 40, 20); ctx.fill();
  text(ctx, label, x + w / 2, y + 26, 14, 600, solid ? '#0b0b0c' : '#fff', 'center');
}
function callBtn(ctx, x, y, accept, label) {
  ctx.fillStyle = accept ? '#ffffff' : 'rgba(255,255,255,.18)';
  ctx.beginPath(); ctx.arc(x, y, 36, 0, Math.PI * 2); ctx.fill();
  ctx.save();
  ctx.translate(x, y);
  if (!accept) ctx.rotate(Math.PI * 0.75);
  ctx.strokeStyle = accept ? '#0b0b0c' : '#fff';
  ctx.lineWidth = 7; ctx.lineCap = 'round';
  ctx.beginPath(); ctx.arc(0, 8, 14, Math.PI * 1.12, Math.PI * 1.88); ctx.stroke();
  ctx.restore();
  text(ctx, label, x, y + 60, 13, 500, 'rgba(255,255,255,.78)', 'center');
}
function check(ctx, x, y) {
  ctx.fillStyle = '#fff';
  ctx.beginPath(); ctx.arc(x, y, 11, 0, Math.PI * 2); ctx.fill();
  ctx.strokeStyle = '#0b0b0c'; ctx.lineWidth = 2.4; ctx.lineCap = 'round';
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
