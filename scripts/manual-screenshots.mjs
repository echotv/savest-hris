/**
 * Regenerates every screenshot used by the in-app User's Manual
 * (public/assets/manual/screenshots/*.png) with Playwright + the
 * locally installed Google Chrome.
 *
 * Prereqs:
 *   npm i -D playwright              # library only, no browser download
 *   php artisan serve --port=8123    # or set BASE_URL
 *   A login with an ADMINISTRATOR user type (all VIEW privileges).
 *
 * Run:
 *   BASE_URL=http://127.0.0.1:8123 \
 *   SHOT_USER=ss_shot SHOT_PASS='ScreenShot#2026' SHOT_EMP=194 \
 *   node scripts/manual-screenshots.mjs
 *
 * Tip: create a throwaway admin user first and delete it afterwards, e.g.
 *   php artisan tinker --execute="\App\Models\User::create(['name'=>'Shot','username'=>'ss_shot','email'=>'ss_shot@example.local','ut_id'=>1,'is_active'=>1,'password'=>'ScreenShot#2026']);"
 */
import { chromium } from 'playwright';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const BASE = process.env.BASE_URL || 'http://127.0.0.1:8123';
const USER = process.env.SHOT_USER || 'ss_shot';
const PASS = process.env.SHOT_PASS || 'ScreenShot#2026';
const EMP  = process.env.SHOT_EMP  || '194';   // profile / edit / movement pages (pick one with movement records)
const SCHED_EMP = process.env.SHOT_SCHED_EMP || EMP; // weekly-schedule pages (pick one with 2+ schedule records)
const ONLY = (process.env.ONLY || '').split(',').map(s => s.trim()).filter(Boolean); // optional: re-shoot just these names

const OUT = path.resolve(fileURLToPath(new URL('.', import.meta.url)), '../public/assets/manual/screenshots');
const VIEWPORT = { width: 1600, height: 950 };

const NO_ANIM = `*,*::before,*::after{transition:none!important;animation:none!important}
.modal.fade .modal-dialog{transition:none!important;transform:none!important}
#load_screen,.loader-container,.preloader{display:none!important}`;

const PAGES = [
  ['dashboard',                  '/system/dashboard'],
  ['employee-registration',      '/employee/registration_form'],
  ['employee-list',              '/employee/employee_list'],
  ['employee-list-actions-menu', '/employee/employee_list', { dropdown: true }],
  ['deleted-employees',          '/employee/deleted'],
  ['employee-profile',           `/employee/employee_profile/${EMP}`],
  ['employee-edit',              `/employee/employee_edit/${EMP}`],
  ['employee-memos',             '/employee-memos'],
  ['employee-memos-add-modal',   '/employee-memos', { modal: true }],
  ['performance-evaluations',            '/performance-evaluations'],
  ['performance-evaluations-add-modal',  '/performance-evaluations', { modal: true }],
  ['upcoming-birthdays',         '/employee/upcoming_birthdays'],
  ['work-anniversaries',         '/employee/work_anniversarries'],
  ['weekly-schedules-list',      '/ews/load_wsl'],
  ['bulk-scheduling',            '/ews/load_bulk_update'],
  ['employee-weekly-schedule',   `/ws/load_schedules/${SCHED_EMP}`],
  ['weekly-schedule-set-new',    `/ws/load/${SCHED_EMP}`],
  ['salary-grade-movement',      `/salary-grade-movement/${EMP}`],
  ['biometric-ids-list',         '/biometric-ids'],
  ['biometric-ids-list-add-modal','/biometric-ids', { modal: true }],
  ['biometric-ids',              `/biometric-ids/${EMP}`],
  ['employee-position-movement', `/${EMP}`],
  ['employment-status-movement', `/employment-status-movement/${EMP}`],
  ['branches',                   '/branches'],
  ['branches-add-modal',         '/branches', { modal: true }],
  ['company-positions',          '/positions/company_positions'],
  ['company-positions-add-modal','/positions/company_positions', { modal: true }],
  ['employment-status',          '/employment_status/load'],
  ['employment-status-add-modal','/employment_status/load', { modal: true }],
  ['salary-grades',              '/salary-grades'],
  ['salary-grades-add-modal',    '/salary-grades', { modal: true }],
  ['holidays',                   '/holidays'],
  ['holidays-add-modal',         '/holidays', { modal: true }],
  ['travel-requests',            '/travel-requests'],
  ['travel-requests-add-modal',  '/travel-requests', { modal: true }],
  ['branch-visits',              '/branch-visits'],
  ['branch-visits-add-modal',    '/branch-visits', { modal: true }],
  ['suspensions',                '/suspensions'],
  ['suspensions-add-modal',      '/suspensions', { modal: true }],
  ['user-accounts',              '/system/user_accounts'],
  ['user-accounts-register-modal','/system/user_accounts', { modal: true }],
  ['user-permissions',           '/system/user_permissions'],
  ['user-permissions-add-modal', '/system/user_permissions', { modal: true }],
  ['system-logs',                '/system/system_logs'],
  ['profile',                    '/system/profile'],
];

const sleep = (ms) => new Promise(r => setTimeout(r, ms));

async function settle(page) {
  try { await page.waitForLoadState('networkidle', { timeout: 20000 }); } catch {}
  await page.addStyleTag({ content: NO_ANIM }).catch(() => {});
  await sleep(1400);
}

async function openAddModal(page) {
  const selectors = [
    '[data-bs-target="#CreateModal"]',
    '#addNewBtn',
    'button[data-bs-toggle="modal"]:not([data-bs-target*="Update"]):not([data-bs-target*="edit"])',
    'a[data-bs-toggle="modal"]:not([data-bs-target*="Update"]):not([data-bs-target*="edit"])',
  ];
  for (const sel of selectors) {
    const el = page.locator(sel).first();
    if (await el.count() && await el.isVisible().catch(() => false)) {
      await el.click();
      await page.waitForSelector('.modal.show', { timeout: 8000 });
      await sleep(700);
      return true;
    }
  }
  return false;
}

const results = [];
const browser = await chromium.launch({ channel: 'chrome' });
const ctx = await browser.newContext({ viewport: VIEWPORT, deviceScaleFactor: 1 });
const page = await ctx.newPage();

await page.goto(`${BASE}/login`, { waitUntil: 'networkidle' });
await settle(page);
await page.screenshot({ path: path.join(OUT, 'login.png') });
results.push('login.png');

await page.fill('#username', USER);
await page.fill('#password', PASS);
await Promise.all([
  page.waitForURL(/dashboard/, { timeout: 20000 }).catch(() => {}),
  page.click('button[type="submit"]'),
]);
await settle(page);
if (!/dashboard/.test(page.url())) {
  console.error('LOGIN FAILED at', page.url());
  await browser.close();
  process.exit(1);
}

for (const [name, route, opts = {}] of PAGES) {
  if (ONLY.length && !ONLY.includes(name)) continue;
  try {
    await page.goto(`${BASE}${route}`, { waitUntil: 'domcontentloaded', timeout: 60000 });
    await settle(page);
    if (opts.modal && !await openAddModal(page)) {
      results.push(`${name}.png  !! no modal trigger`);
      continue;
    }
    if (opts.dropdown) {
      // Open the first row's ⋮ actions menu
      const toggle = page.locator('.dropdown-action button[data-bs-toggle="dropdown"]').first();
      if (!await toggle.count()) { results.push(`${name}.png  !! no row menu`); continue; }
      await toggle.click();
      // Bootstrap moves the open menu out of the row, so find it by its content
      await page.locator('.dropdown-menu.show', { hasText: 'View Employee Info' }).first().waitFor({ state: 'visible', timeout: 8000 });
      await sleep(500);
    }
    // Always a fixed desktop-size (1600x950) viewport shot — never a tall full-page capture.
    await page.screenshot({ path: path.join(OUT, `${name}.png`) });
    results.push(`${name}.png`);
  } catch (e) {
    results.push(`${name}.png  !! ${e.message}`);
  }
}

// System Logs -> "View" details modal (needs at least one log row for the day)
try {
  await page.goto(`${BASE}/system/system_logs`, { waitUntil: 'domcontentloaded' });
  await settle(page);
  await page.evaluate(() => {
    const s = document.getElementById('filter_action');
    if (s) { s.value = 'All'; document.getElementById('filterForm').submit(); }
  });
  await settle(page);
  const btn = await page.evaluateHandle(() => {
    const all = [...document.querySelectorAll('.view-log-btn')];
    return all.find(b => (b.dataset.message || '').includes('{')) || all[0] || null;
  });
  const el = btn.asElement();
  if (el) {
    await el.click();
    await page.waitForSelector('#logDetailsModal.show', { timeout: 8000 });
    await sleep(700);
    await page.screenshot({ path: path.join(OUT, 'system-logs-view-modal.png') });
    results.push('system-logs-view-modal.png');
  } else {
    results.push('system-logs-view-modal.png  !! no log rows');
  }
} catch (e) {
  results.push(`system-logs-view-modal.png  !! ${e.message}`);
}

await browser.close();
console.log(results.join('\n'));
