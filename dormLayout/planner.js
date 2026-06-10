/**
 * BroncoLink Dorm Room Planner
 * Measurements sourced from SCU residence hall room guides.
 */

const INCH = 3.2; // pixels per inch — keeps rooms readable on screen
const ft = (feet) => feet * 12 * INCH;
const inch = (inches) => inches * INCH;

const STORAGE_PREFIX = 'broncolink-layout-v5-';

/** Footprint: w = horizontal px, h = vertical px (top-down view) */
const SIZES = {
  bedSide: { w: inch(40.5), h: inch(85) },
  desk: { w: inch(24), h: inch(42) },
  chair: { w: inch(18), h: inch(19) },
  dresser: { w: inch(24), h: inch(26) },
  closetSide: { w: inch(22.5), h: inch(56) },
  closetBottom: { w: inch(56), h: inch(22.5) },
  closetFinnBottom: { w: inch(54.5), h: inch(24.5) },
  armoireSide: { w: inch(23), h: inch(35.5) },
  vanitySide: { w: inch(21), h: inch(59) },
};

/** Build non-overlapping default layouts (zones: window → desks → beds → dressers → closets) */
function buildLayouts() {
  const B = SIZES.bedSide;
  const D = SIZES.desk;
  const C = SIZES.chair;
  const Dr = SIZES.dresser;
  const ClBottom = SIZES.closetBottom;
  const ClFinnBottom = SIZES.closetFinnBottom;
  const Arm = SIZES.armoireSide;
  const Van = SIZES.vanitySide;

  const gW = ft(21);
  const gH = ft(16);
  const sW = ft(9);
  const sH = ft(11.5);
  const swW = ft(12);
  const swH = ft(15);
  const fW = ft(20);
  const fH = ft(13.85);

  const m = 10;

  return {
    graham: {
      title: 'Graham Hall',
      subtitle: 'Mini Suite Double · 21\' L × 16\' W',
      roomW: gW,
      roomH: gH,
      visible: [
        'bed-a', 'bed-b', 'desk-a', 'desk-b', 'chair-a', 'chair-b',
        'dresser-a', 'dresser-b', 'closet-a', 'closet-b',
      ],
      defaults: {
        'desk-a': { left: 250, top: 28, ...D },
        'desk-b': { left: 490, top: 28, ...D },
        'chair-a': { left: 260, top: 168, ...C },
        'chair-b': { left: 500, top: 168, ...C },
        'bed-a': { left: m, top: 240, ...B },
        'bed-b': { left: gW - B.w - m, top: 240, ...B },
        'dresser-a': { left: m, top: 522, ...Dr },
        'dresser-b': { left: gW - Dr.w - m, top: 522, ...Dr },
        'closet-a': { left: 160, top: gH - ClBottom.h - m, ...ClBottom },
        'closet-b': { left: 500, top: gH - ClBottom.h - m, ...ClBottom },
      },
    },

    sobrato: {
      title: 'Sobrato Hall',
      subtitle: 'Suite Single · 11.5\' L × 9\' W',
      roomW: sW,
      roomH: sH,
      visible: ['bed-a', 'desk-a', 'chair-a', 'dresser-a', 'closet-a'],
      defaults: {
        'desk-a': { left: m, top: m + 14, ...D },
        'chair-a': { left: m + 6, top: m + 14 + D.h + 10, ...C },
        'dresser-a': { left: m, top: m + 14 + D.h + C.h + 20, ...Dr },
        'bed-a': { left: sW - B.w - m, top: m + 30, ...B },
        'closet-a': { left: (sW - ClBottom.w) / 2, top: sH - ClBottom.h - m, ...ClBottom },
      },
    },

    swig: {
      title: 'Swig Hall',
      subtitle: 'Design Double · 15\' L × 12\' W',
      roomW: swW,
      roomH: swH,
      visible: [
        'bed-a', 'bed-b', 'desk-a', 'desk-b', 'chair-a', 'chair-b',
        'vanity', 'armoire-a', 'armoire-b', 'dresser-a',
      ],
      defaults: {
        'vanity': { left: m, top: m, ...Van },
        'armoire-a': { left: m, top: m + Van.h + 12, ...Arm },
        'armoire-b': { left: m, top: m + Van.h + Arm.h + 24, ...Arm },
        'bed-a': { left: swW - B.w - m, top: m, ...B },
        'bed-b': { left: swW - B.w - m, top: swH - B.h - m, ...B },
        'desk-a': { left: 210, top: 36, ...D },
        'desk-b': { left: 210, top: 370, ...D },
        'chair-a': { left: 222, top: 36 + D.h + 10, ...C },
        'chair-b': { left: 222, top: 370 + D.h + 10, ...C },
        'dresser-a': { left: m, top: 458, ...Dr },
      },
    },

    finn: {
      title: 'Finn Hall',
      subtitle: 'Design Double · 20\' L × 13.85\' W',
      roomW: fW,
      roomH: fH,
      visible: [
        'bed-a', 'bed-b', 'desk-a', 'desk-b', 'chair-a', 'chair-b',
        'dresser-a', 'dresser-b', 'closet-a', 'closet-b',
      ],
      defaults: {
        'desk-a': { left: 220, top: 24, ...D },
        'desk-b': { left: 470, top: 24, ...D },
        'chair-a': { left: 230, top: 162, ...C },
        'chair-b': { left: 480, top: 162, ...C },
        'bed-a': { left: m, top: 230, ...B },
        'bed-b': { left: fW - B.w - m, top: 230, ...B },
        'dresser-a': { left: 310, top: 355, ...Dr },
        'dresser-b': { left: 310 + Dr.w + 12, top: 355, ...Dr },
        'closet-a': { left: 200, top: fH - ClFinnBottom.h - m, ...ClFinnBottom },
        'closet-b': { left: 390, top: fH - ClFinnBottom.h - m, ...ClFinnBottom },
      },
    },
  };
}

const DORM_CONFIG = buildLayouts();

const TYPE_SIZES = {
  bed: SIZES.bedSide,
  desk: SIZES.desk,
  chair: SIZES.chair,
  dresser: SIZES.dresser,
  wardrobe: SIZES.closetSide,
  closet: SIZES.closetSide,
  armoire: SIZES.armoireSide,
  vanity: SIZES.vanitySide,
};

const roomContainer = document.getElementById('room-container');
const dormSelect = document.getElementById('dorm-select');
const resetBtn = document.getElementById('reset-layout');
const roomTitle = document.getElementById('room-title');
const roomSubtitle = document.getElementById('room-subtitle');
const furnitureItems = Array.from(document.querySelectorAll('.furniture'));

let isDragging = false;
let activeItem = null;
let startMouseX = 0;
let startMouseY = 0;
let startLeft = 0;
let startTop = 0;
let roomWidth = 0;
let roomHeight = 0;

function getCurrentDorm() {
  return dormSelect.value;
}

function getDormConfig() {
  return DORM_CONFIG[getCurrentDorm()];
}

function getItemSize(item, config) {
  const def = config.defaults[item.id];
  if (def && def.w && def.h) {
    return { w: def.w, h: def.h };
  }
  const type = [...item.classList].find((c) => TYPE_SIZES[c]);
  return TYPE_SIZES[type] || { w: 80, h: 80 };
}

function setBaseSize(item, w, h) {
  item.dataset.baseW = w;
  item.dataset.baseH = h;
}

function getRotation(item) {
  return parseInt(item.dataset.rotation || '0', 10) % 360;
}

function applyRotation(item) {
  const baseW = parseFloat(item.dataset.baseW);
  const baseH = parseFloat(item.dataset.baseH);
  const swapped = (getRotation(item) / 90) % 2 === 1;
  item.style.width = (swapped ? baseH : baseW) + 'px';
  item.style.height = (swapped ? baseW : baseH) + 'px';
}

function applyItemSize(item, size) {
  setBaseSize(item, size.w, size.h);
  applyRotation(item);
}

function rotateItem(item) {
  const pos = getItemPosition(item);
  const oldW = item.offsetWidth;
  const oldH = item.offsetHeight;

  item.dataset.rotation = String((getRotation(item) + 90) % 360);
  applyRotation(item);

  const newW = item.offsetWidth;
  const newH = item.offsetHeight;
  setItemPosition(
    item,
    pos.left + (oldW - newW) / 2,
    pos.top + (oldH - newH) / 2
  );
}

function clampPosition(left, top, item) {
  const maxLeft = roomWidth - item.offsetWidth;
  const maxTop = roomHeight - item.offsetHeight;
  return {
    left: Math.max(0, Math.min(maxLeft, left)),
    top: Math.max(0, Math.min(maxTop, top)),
  };
}

function setItemPosition(item, left, top) {
  const pos = clampPosition(left, top, item);
  item.style.left = pos.left + 'px';
  item.style.top = pos.top + 'px';
}

function getItemPosition(item) {
  return {
    left: parseInt(item.style.left, 10) || 0,
    top: parseInt(item.style.top, 10) || 0,
  };
}

function saveLayout() {
  const layout = {};
  furnitureItems.forEach((item) => {
    if (item.classList.contains('hidden')) return;
    const pos = getItemPosition(item);
    layout[item.id] = {
      left: pos.left,
      top: pos.top,
      baseW: parseFloat(item.dataset.baseW),
      baseH: parseFloat(item.dataset.baseH),
      rotation: getRotation(item),
    };
  });
  localStorage.setItem(STORAGE_PREFIX + getCurrentDorm(), JSON.stringify(layout));
}

function applyDefault(item, def) {
  item.dataset.rotation = '0';
  if (def.w && def.h) {
    applyItemSize(item, { w: def.w, h: def.h });
  }
  setItemPosition(item, def.left, def.top);
}

function loadLayout() {
  const config = getDormConfig();
  const saved = localStorage.getItem(STORAGE_PREFIX + getCurrentDorm());

  roomWidth = config.roomW;
  roomHeight = config.roomH;
  roomContainer.style.width = roomWidth + 'px';
  roomContainer.style.height = roomHeight + 'px';
  roomContainer.style.backgroundSize = ft(1) + 'px ' + ft(1) + 'px';

  let layout = null;
  if (saved) {
    try {
      layout = JSON.parse(saved);
    } catch (e) {
      layout = null;
    }
  }

  furnitureItems.forEach((item) => {
    const isVisible = config.visible.includes(item.id);
    item.classList.toggle('hidden', !isVisible);

    if (!isVisible) return;

    const def = config.defaults[item.id];

    if (layout && layout[item.id]) {
      const savedItem = layout[item.id];
      if (savedItem.baseW && savedItem.baseH) {
        setBaseSize(item, savedItem.baseW, savedItem.baseH);
      } else if (def) {
        applyItemSize(item, { w: def.w, h: def.h });
      } else {
        applyItemSize(item, getItemSize(item, config));
      }
      item.dataset.rotation = String(savedItem.rotation || 0);
      applyRotation(item);
      setItemPosition(item, savedItem.left, savedItem.top);
    } else if (def) {
      applyDefault(item, def);
    }
  });

  roomTitle.textContent = config.title;
  roomSubtitle.textContent = config.subtitle + ' · Drag to move · Double-click or ↻ to rotate';
}

function resetLayout() {
  localStorage.removeItem(STORAGE_PREFIX + getCurrentDorm());
  loadLayout();
}

function onMouseDown(event) {
  if (!event.target.classList.contains('furniture')) return;
  if (event.target.classList.contains('hidden')) return;

  isDragging = true;
  activeItem = event.target;
  startMouseX = event.clientX;
  startMouseY = event.clientY;
  startLeft = getItemPosition(activeItem).left;
  startTop = getItemPosition(activeItem).top;

  activeItem.classList.add('dragging');
  event.preventDefault();
}

function onMouseMove(event) {
  if (!isDragging || !activeItem) return;

  const dx = event.clientX - startMouseX;
  const dy = event.clientY - startMouseY;
  setItemPosition(activeItem, startLeft + dx, startTop + dy);
}

function onMouseUp() {
  if (!isDragging) return;

  isDragging = false;
  if (activeItem) {
    activeItem.classList.remove('dragging');
    activeItem = null;
    saveLayout();
  }
}

function addRotateControl(item) {
  if (item.querySelector('.furniture__rotate')) return;

  const btn = document.createElement('button');
  btn.type = 'button';
  btn.className = 'furniture__rotate';
  btn.setAttribute('aria-label', `Rotate ${item.textContent.trim()}`);
  btn.textContent = '↻';
  btn.addEventListener('mousedown', (e) => e.stopPropagation());
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    rotateItem(item);
    saveLayout();
  });
  item.appendChild(btn);
}

furnitureItems.forEach((item) => {
  addRotateControl(item);
  item.addEventListener('mousedown', onMouseDown);
  item.addEventListener('dblclick', (e) => {
    e.preventDefault();
    if (item.classList.contains('hidden')) return;
    rotateItem(item);
    saveLayout();
  });
});

document.addEventListener('mousemove', onMouseMove);
document.addEventListener('mouseup', onMouseUp);

dormSelect.addEventListener('change', loadLayout);
resetBtn.addEventListener('click', resetLayout);

loadLayout();
