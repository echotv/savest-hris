/* ==========================================================================
   SAVEST-HRIS User Manual — Application Logic
   Hash-based routing, sidebar w/ filter search, global page-finder search,
   theme toggle, and client-side PDF export (auto-download) via html2pdf.js.
   ========================================================================== */

(function () {
  "use strict";

  const $content   = document.getElementById("content");
  const $sidebarNav = document.getElementById("sidebarNav");
  const $breadcrumb = document.getElementById("breadcrumb");

  document.getElementById("verNum").textContent = MANUAL_DATA.version;

  /* ---------------------------------------------------------------------
     Role-based visibility — a function is shown only if the signed-in
     account holds at least one of its listed permissions. Functions with
     no permission field (or "None — ...") are available to everyone.
     window.USER_PERMISSIONS is injected by the server; when it's absent
     (e.g. viewing this manual outside the app) nothing is restricted.
     --------------------------------------------------------------------- */
  function hasPermission(permStr) {
    if (!permStr) return true;
    const p = String(permStr).trim();
    if (!p || /^none\b/i.test(p)) return true;
    if (!Array.isArray(window.USER_PERMISSIONS)) return true;
    return p.split("·").map((s) => s.trim()).filter(Boolean)
      .some((perm) => window.USER_PERMISSIONS.includes(perm));
  }

  /* Modules/functions filtered down to what this account is allowed to see */
  const VISIBLE_MODULES = MANUAL_DATA.modules
    .map((mod) => ({ ...mod, functions: mod.functions.filter((fn) => hasPermission(fn.permission)) }))
    .filter((mod) => mod.functions.length > 0);

  const VISIBLE_INDEX = {};
  VISIBLE_MODULES.forEach((mod) => {
    mod.functions.forEach((fn) => {
      VISIBLE_INDEX[`${mod.id}/${fn.id}`] = { ...fn, moduleId: mod.id, moduleTitle: mod.title, moduleIcon: mod.icon };
    });
  });

  /* Flat ordered list of every visible function key, for prev/next navigation */
  const FLAT_ORDER = [];
  VISIBLE_MODULES.forEach((mod) => {
    mod.functions.forEach((fn) => FLAT_ORDER.push(`${mod.id}/${fn.id}`));
  });

  /* Pre-built lowercase search blob per function, for the global page-finder */
  const SEARCH_DOCS = FLAT_ORDER.map((key) => {
    const fn = VISIBLE_INDEX[key];
    const parts = [
      fn.title, fn.moduleTitle, fn.description || "",
      (fn.features || []).join(" "),
      (fn.steps || []).join(" "),
      (fn.tips || []).join(" "),
      (fn.fields || []).map(f => f.name + " " + f.description).join(" "),
      (fn.faq || []).map(f => f.q + " " + f.a).join(" "),
      fn.route || ""
    ];
    return { key, text: parts.join(" \n ").toLowerCase(), fn };
  });

  /* ---------------------------------------------------------------------
     Utilities
     --------------------------------------------------------------------- */
  function esc(str) {
    return String(str || "").replace(/[&<>"']/g, (c) => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
    }[c]));
  }

  function iconSvg(name) {
    const icons = {
      home: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>',
      "share-2": '<circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>',
      users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
      clock: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
      settings: '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
      doc: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline>',
      briefcase: '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
      award: '<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>'
    };
    return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icons[name] || icons.doc}</svg>`;
  }

  function screenshotPath(name) {
    var base = (typeof window !== 'undefined' && window.MANUAL_ASSET_BASE) ? window.MANUAL_ASSET_BASE : 'assets';
    return `${base}/screenshots/${name}`;
  }

  /* ---------------------------------------------------------------------
     Sidebar
     --------------------------------------------------------------------- */
  function buildSidebar() {
    $sidebarNav.innerHTML = "";
    VISIBLE_MODULES.forEach((mod) => {
      const li = document.createElement("div");
      li.className = "nav-module";
      li.dataset.moduleId = mod.id;

      const btn = document.createElement("button");
      btn.className = "nav-module-btn";
      btn.innerHTML = `
        <span class="mod-icon">${iconSvg(mod.icon)}</span>
        <span class="mod-title">${esc(mod.title)}</span>
        <span class="chevron"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
      `;
      btn.addEventListener("click", () => {
        li.classList.toggle("open");
      });
      li.appendChild(btn);

      const ul = document.createElement("ul");
      ul.className = "nav-fn-list";
      mod.functions.forEach((fn) => {
        const fli = document.createElement("li");
        fli.dataset.fnId = fn.id;
        const a = document.createElement("a");
        a.href = `#/${mod.id}/${fn.id}`;
        a.textContent = fn.title;
        a.dataset.key = `${mod.id}/${fn.id}`;
        fli.appendChild(a);
        ul.appendChild(fli);
      });
      li.appendChild(ul);

      $sidebarNav.appendChild(li);
    });
  }

  function setActiveSidebarLink(key) {
    $sidebarNav.querySelectorAll(".nav-fn-list a").forEach((a) => {
      a.classList.toggle("active", a.dataset.key === key);
    });
    $sidebarNav.querySelectorAll(".nav-module").forEach((mod) => {
      const hasActive = !!mod.querySelector(".nav-fn-list a.active");
      if (hasActive) mod.classList.add("open");
    });
  }

  /* ---- Sidebar filter search ------------------------------------------ */
  const $sidebarSearch = document.getElementById("sidebarSearch");
  const $sidebarSearchClear = document.getElementById("sidebarSearchClear");

  function highlightText(text, query) {
    if (!query) return esc(text);
    const idx = text.toLowerCase().indexOf(query.toLowerCase());
    if (idx === -1) return esc(text);
    return esc(text.slice(0, idx)) + "<mark class='hl'>" + esc(text.slice(idx, idx + query.length)) + "</mark>" + esc(text.slice(idx + query.length));
  }

  function filterSidebar() {
    const q = $sidebarSearch.value.trim().toLowerCase();
    $sidebarSearchClear.hidden = q.length === 0;

    $sidebarNav.querySelectorAll(".nav-module").forEach((modEl) => {
      const modId = modEl.dataset.moduleId;
      const mod = VISIBLE_MODULES.find(m => m.id === modId);
      let modMatches = q === "" || mod.title.toLowerCase().includes(q);
      let anyFnMatch = false;

      modEl.querySelectorAll(".nav-fn-list li").forEach((fli) => {
        const fnId = fli.dataset.fnId;
        const fn = mod.functions.find(f => f.id === fnId);
        const match = q === "" || fn.title.toLowerCase().includes(q) || modMatches;
        const a = fli.querySelector("a");
        a.innerHTML = highlightText(fn.title, q === "" ? "" : (fn.title.toLowerCase().includes(q) ? q : ""));
        fli.hidden = !match;
        if (match && fn.title.toLowerCase().includes(q)) anyFnMatch = true;
      });

      const show = q === "" || modMatches || anyFnMatch;
      modEl.hidden = !show;
      if (q !== "" && show) modEl.classList.add("open");
      if (q === "") {
        // restore collapsed state except active
        const hasActive = !!modEl.querySelector(".nav-fn-list a.active");
        if (!hasActive) modEl.classList.remove("open");
      }
    });
  }

  $sidebarSearch.addEventListener("input", filterSidebar);
  $sidebarSearchClear.addEventListener("click", () => {
    $sidebarSearch.value = "";
    filterSidebar();
    $sidebarSearch.focus();
  });

  /* ---- Mobile sidebar toggle -------------------------------------------- */
  const $sidebar = document.getElementById("sidebar");
  const $backdrop = document.getElementById("sidebarBackdrop");
  document.getElementById("mobileSidebarToggle").addEventListener("click", () => {
    $sidebar.classList.toggle("open");
  });
  $backdrop.addEventListener("click", () => $sidebar.classList.remove("open"));
  $sidebarNav.addEventListener("click", (e) => {
    if (e.target.closest("a")) $sidebar.classList.remove("open");
  });

  /* ---------------------------------------------------------------------
     Page-finder (global content search across the whole manual)
     --------------------------------------------------------------------- */
  const $pfInput = document.getElementById("pageFinderInput");
  const $pfResults = document.getElementById("pfResults");
  let pfActiveIndex = -1;
  let pfCurrentMatches = [];

  function snippetAround(text, query) {
    const lower = text.toLowerCase();
    const idx = lower.indexOf(query);
    if (idx === -1) return text.slice(0, 110) + (text.length > 110 ? "…" : "");
    const start = Math.max(0, idx - 40);
    const end = Math.min(text.length, idx + query.length + 60);
    return (start > 0 ? "…" : "") + text.slice(start, end).trim() + (end < text.length ? "…" : "");
  }

  function runPageFinder(query) {
    const q = query.trim().toLowerCase();
    if (!q) {
      $pfResults.hidden = true;
      $pfResults.innerHTML = "";
      pfCurrentMatches = [];
      return;
    }
    const matches = SEARCH_DOCS.filter(d => d.text.includes(q)).slice(0, 20);
    pfCurrentMatches = matches;
    pfActiveIndex = matches.length ? 0 : -1;

    if (!matches.length) {
      $pfResults.innerHTML = `<div class="pf-empty">No matches for “${esc(query)}”.</div>`;
      $pfResults.hidden = false;
      return;
    }

    $pfResults.innerHTML = `<div class="pf-group-label">${matches.length} result${matches.length > 1 ? "s" : ""}</div>` +
      matches.map((m, i) => {
        const fn = m.fn;
        const rawSnippetSource = (fn.description || fn.features?.join(" ") || "");
        const snippet = snippetAround(rawSnippetSource || fn.title, q);
        return `
          <a href="#/${m.key}" class="pf-item${i === 0 ? " active" : ""}" data-idx="${i}">
            <div class="pf-item-title">${esc(fn.title)}</div>
            <div class="pf-item-path">${esc(fn.moduleTitle)} → ${esc(fn.title)}</div>
            <div class="pf-item-snippet">${esc(snippet)}</div>
          </a>`;
      }).join("");
    $pfResults.hidden = false;
  }

  function updatePfActiveClass() {
    $pfResults.querySelectorAll(".pf-item").forEach((el, i) => {
      el.classList.toggle("active", i === pfActiveIndex);
      if (i === pfActiveIndex) el.scrollIntoView({ block: "nearest" });
    });
  }

  $pfInput.addEventListener("input", () => runPageFinder($pfInput.value));
  $pfInput.addEventListener("focus", () => { if ($pfInput.value.trim()) runPageFinder($pfInput.value); });
  $pfInput.addEventListener("keydown", (e) => {
    if (!pfCurrentMatches.length) return;
    if (e.key === "ArrowDown") {
      e.preventDefault();
      pfActiveIndex = Math.min(pfActiveIndex + 1, pfCurrentMatches.length - 1);
      updatePfActiveClass();
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      pfActiveIndex = Math.max(pfActiveIndex - 1, 0);
      updatePfActiveClass();
    } else if (e.key === "Enter") {
      e.preventDefault();
      const m = pfCurrentMatches[pfActiveIndex];
      if (m) {
        location.hash = `#/${m.key}`;
        $pfResults.hidden = true;
        $pfInput.blur();
      }
    } else if (e.key === "Escape") {
      $pfResults.hidden = true;
      $pfInput.blur();
    }
  });
  document.addEventListener("click", (e) => {
    if (!document.getElementById("pageFinder").contains(e.target)) $pfResults.hidden = true;
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "/" && document.activeElement !== $pfInput && document.activeElement !== $sidebarSearch) {
      e.preventDefault();
      $pfInput.focus();
    }
  });

  /* ---------------------------------------------------------------------
     Renderers
     --------------------------------------------------------------------- */
  function renderHome() {
    document.title = `${MANUAL_DATA.appName} — User Manual`;
    $breadcrumb.innerHTML = `<span class="crumb-current">Home</span>`;

    const totalFns = FLAT_ORDER.length;
    const cards = VISIBLE_MODULES.map((mod) => `
      <a class="module-card" href="#/${mod.id}">
        <div class="mc-icon">${iconSvg(mod.icon)}</div>
        <h3>${esc(mod.title)}</h3>
        <p>${esc(mod.intro)}</p>
        <div class="mc-count">${mod.functions.length} function${mod.functions.length > 1 ? "s" : ""}</div>
      </a>
    `).join("");

    $content.innerHTML = `
      <div class="hero-card">
        <h1>${esc(MANUAL_DATA.appName)} User Manual</h1>
        <p>A complete, screenshot-illustrated guide to every module and function in the SAVEST-HRIS system —
        from employee onboarding and attendance tracking to user roles and permissions.
        Browse by module in the sidebar, jump straight to a function with the search bar above,
        or export any page to PDF to keep offline.</p>
      </div>
      <div class="module-grid">${cards}</div>
      ${renderFAQSection(MANUAL_DATA.homeFaq)}
    `;
  }

  function renderModule(modId) {
    const mod = VISIBLE_MODULES.find(m => m.id === modId);
    if (!mod) return render404();

    document.title = `${mod.title} — ${MANUAL_DATA.appName} Manual`;
    $breadcrumb.innerHTML = `<a href="#/">Home</a><span class="crumb-sep">/</span><span class="crumb-current">${esc(mod.title)}</span>`;

    const fnLinks = mod.functions.map(fn => `<a href="#/${mod.id}/${fn.id}">${esc(fn.title)}</a>`).join("");
    const cards = mod.functions.map(fn => `
      <a class="module-card" href="#/${mod.id}/${fn.id}">
        <div class="mc-icon">${iconSvg("doc")}</div>
        <h3>${esc(fn.title)}</h3>
        <p>${esc((fn.description || "").slice(0, 120))}${(fn.description || "").length > 120 ? "…" : ""}</p>
        <div class="mc-count">View function guide →</div>
      </a>
    `).join("");

    $content.innerHTML = `
      <div class="page-header">
        <div class="page-kicker">Module</div>
        <h1>${esc(mod.title)}</h1>
        <p class="page-lede">${esc(mod.intro)}</p>
      </div>
      <div class="module-fn-nav">${fnLinks}</div>
      <div class="module-grid">${cards}</div>
      ${renderFAQSection(mod.faq)}
    `;
  }

  function metaChips(fn) {
    const chips = [];
    if (fn.route) chips.push(`<span class="meta-chip">🔗 <code>${esc(fn.route)}</code></span>`);
    if (fn.permission) {
      fn.permission.split("·").map(s => s.trim()).filter(Boolean).forEach(p => {
        chips.push(`<span class="meta-chip perm">🔒 ${esc(p)}</span>`);
      });
    }
    return `<div class="meta-row">${chips.join("")}</div>`;
  }

  function renderFunction(modId, fnId) {
    const key = `${modId}/${fnId}`;
    const fn = VISIBLE_INDEX[key];
    if (!fn) return render404();

    document.title = `${fn.title} — ${MANUAL_DATA.appName} Manual`;
    $breadcrumb.innerHTML = `<a href="#/">Home</a><span class="crumb-sep">/</span><a href="#/${modId}">${esc(fn.moduleTitle)}</a><span class="crumb-sep">/</span><span class="crumb-current">${esc(fn.title)}</span>`;

    const idx = FLAT_ORDER.indexOf(key);
    const prevKey = idx > 0 ? FLAT_ORDER[idx - 1] : null;
    const nextKey = idx < FLAT_ORDER.length - 1 ? FLAT_ORDER[idx + 1] : null;

    $content.innerHTML = functionDetailHtml(fn, { prevKey, nextKey });
    setActiveSidebarLink(key);
  }

  /* Builds the full detail markup for one function (screenshot, fields, steps,
     tips, FAQ…). Shared between the single-page router view and the
     full-manual PDF export below, so both stay in sync automatically. */
  function functionDetailHtml(fn, { prevKey, nextKey, showNav = true } = {}) {
    let html = `
      <div class="page-header">
        <div class="page-kicker">${esc(fn.moduleTitle)}</div>
        <h1>${esc(fn.title)}</h1>
        ${fn.description ? `<p class="page-lede">${esc(fn.description)}</p>` : ""}
      </div>
      ${metaChips(fn)}
    `;

    if (fn.screenshot) {
      html += `
        <div class="section-block">
          <h2>Screenshot</h2>
          <div class="shot-frame"><img src="${screenshotPath(fn.screenshot)}" alt="${esc(fn.title)} screenshot" loading="lazy"></div>
        </div>
      `;
    }

    if (fn.features && fn.features.length) {
      html += `
        <div class="section-block">
          <h2>What you can do here</h2>
          <ul class="feature-list">${fn.features.map(f => `<li>${esc(f)}</li>`).join("")}</ul>
        </div>
      `;
    }

    if (fn.fields && fn.fields.length) {
      html += `
        <div class="section-block">
          <h2>Field reference</h2>
          <div class="field-table-wrap">
            <table class="field-table">
              <thead><tr><th>Field</th><th>Description</th></tr></thead>
              <tbody>${fn.fields.map(f => `<tr><td>${esc(f.name)}</td><td>${esc(f.description)}</td></tr>`).join("")}</tbody>
            </table>
          </div>
        </div>
      `;
    }

    if (fn.steps && fn.steps.length) {
      html += `
        <div class="section-block">
          <h2>Step-by-step</h2>
          <ol class="steps-list">${fn.steps.map(s => `<li>${esc(s)}</li>`).join("")}</ol>
        </div>
      `;
    }

    if (fn.modalScreenshot) {
      html += `
        <div class="section-block">
          <h2>${esc(fn.modalLabel || "Dialog")}</h2>
          <div class="shot-frame"><img src="${screenshotPath(fn.modalScreenshot)}" alt="${esc(fn.modalLabel || fn.title)} screenshot" loading="lazy"></div>
        </div>
      `;
    }

    if (fn.tips && fn.tips.length) {
      html += `
        <div class="section-block">
          <div class="tips-box">
            <h3>💡 Tips</h3>
            <ul class="tips-list">${fn.tips.map(t => `<li>${esc(t)}</li>`).join("")}</ul>
          </div>
        </div>
      `;
    }

    // prev / next
    if (showNav && (prevKey || nextKey)) {
      html += `<div class="page-nav-links">`;
      html += prevKey
        ? `<a href="#/${prevKey}"><span class="dir">← Previous</span>${esc(VISIBLE_INDEX[prevKey].title)}</a>`
        : `<span></span>`;
      html += nextKey
        ? `<a href="#/${nextKey}" class="next"><span class="dir">Next →</span>${esc(VISIBLE_INDEX[nextKey].title)}</a>`
        : `<span></span>`;
      html += `</div>`;
    }

    html += renderFAQSection(fn.faq);

    return html;
  }

  function renderFAQSection(faqList) {
    if (!faqList || !faqList.length) return "";

    const items = faqList.map((item) => `
      <details class="faq-item">
        <summary>
          <span class="faq-q">${esc(item.q)}</span>
          <span class="faq-chevron"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
        </summary>
        <div class="faq-a">${esc(item.a)}</div>
      </details>
    `).join("");

    return `
      <div class="section-block faq-section">
        <h2>Frequently asked questions</h2>
        <div class="faq-list">${items}</div>
      </div>
    `;
  }

  function render404() {
    $content.innerHTML = `<div class="not-found"><h1>Page not found</h1><p>Use the sidebar or search bar to find what you're looking for.</p></div>`;
  }

  /* ---------------------------------------------------------------------
     Router
     --------------------------------------------------------------------- */
  function router() {
    const hash = location.hash.replace(/^#\/?/, "").replace(/\/$/, "");
    $content.scrollTop = 0;
    window.scrollTo(0, 0);

    if (!hash) { renderHome(); setActiveSidebarLink(null); return; }

    const parts = hash.split("/");
    if (parts.length === 1) {
      renderModule(parts[0]);
      setActiveSidebarLink(null);
    } else {
      renderFunction(parts[0], parts[1]);
    }
  }

  window.addEventListener("hashchange", router);

  /* ---------------------------------------------------------------------
     Theme toggle
     --------------------------------------------------------------------- */
  const THEME_KEY = "savest-manual-theme";
  function applyTheme(theme) {
    if (theme) document.documentElement.setAttribute("data-theme", theme);
    else document.documentElement.removeAttribute("data-theme");
  }
  const savedTheme = localStorage.getItem(THEME_KEY);
  if (savedTheme) applyTheme(savedTheme);

  document.getElementById("themeToggle").addEventListener("click", () => {
    const current = document.documentElement.getAttribute("data-theme") ||
      (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
    const next = current === "dark" ? "light" : "dark";
    applyTheme(next);
    localStorage.setItem(THEME_KEY, next);
  });

  /* ---------------------------------------------------------------------
     Export to PDF (client-side, source-bundled html2pdf.js, auto-download)
     --------------------------------------------------------------------- */
  const $exportBtn = document.getElementById("exportPdfBtn");
  const $toast = document.getElementById("pdfToast");
  let toastTimer = null;

  function showToast(msg) {
    $toast.textContent = msg;
    $toast.hidden = false;
    requestAnimationFrame(() => $toast.classList.add("show"));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      $toast.classList.remove("show");
      setTimeout(() => { $toast.hidden = true; }, 250);
    }, 2600);
  }

  /* Print-safe CSS applied to every captured chunk below (forces light,
     print-friendly colors regardless of the active theme). */
  const PDF_EXPORT_CSS = `
    [data-pdf-export] { color: #1e2433 !important; background: #ffffff !important; }
    [data-pdf-export] .hero-card { background: #4361ee !important; color: #fff !important; }
    [data-pdf-export] .module-card, [data-pdf-export] .shot-frame, [data-pdf-export] .tips-box,
    [data-pdf-export] .field-table-wrap, [data-pdf-export] .page-nav-links a,
    [data-pdf-export] .faq-list, [data-pdf-export] .faq-item summary { background: #fff !important; border-color: #e6e8f0 !important; }
    [data-pdf-export] .faq-item .faq-q { color: #1e2433 !important; }
    [data-pdf-export] .faq-item .faq-a { color: #6b7280 !important; }
    [data-pdf-export] .tips-box { background: #eef1ff !important; }
    [data-pdf-export] .meta-chip { background: #f1f3f9 !important; color: #6b7280 !important; }
    [data-pdf-export] a { color: #4361ee !important; }
    /* html2canvas approximates text layout itself rather than reusing the
       browser's own line boxes, so unitless line-heights often render
       tighter than they look on screen — pin them to explicit px values
       so lines in the exported PDF don't crowd or overlap. */
    [data-pdf-export] { line-height: 1.6 !important; }
    [data-pdf-export] h1 { line-height: 34px !important; }
    [data-pdf-export] h2, [data-pdf-export] h3 { line-height: 22px !important; }
    [data-pdf-export] p, [data-pdf-export] .page-lede, [data-pdf-export] .module-card p,
    [data-pdf-export] .faq-a, [data-pdf-export] td { line-height: 22px !important; }
    [data-pdf-export] .feature-list li, [data-pdf-export] .steps-list li,
    [data-pdf-export] .tips-list li { line-height: 22px !important; }
  `;

  /* Builds the full manual as a single multi-page PDF, one function (or
     module header) captured per canvas rather than one giant canvas for
     the whole document. A full-access account's manual can run to dozens
     of screenshots stacked end to end — rasterizing that in one shot
     easily exceeds the browser's maximum canvas dimension (~32,767px in
     Chromium) and silently comes back blank. Capturing chunk by chunk
     keeps every single canvas well within that limit no matter how large
     the whole manual gets. */
  async function exportFullManualPdf(onProgress) {
    // This bundle doesn't expose the underlying jsPDF/html2canvas
    // constructors directly — only the high-level html2pdf() Worker chain.
    // Worker.get('canvas'/'pdf') exposes those internals after the
    // matching step runs, which is enough to drive our own per-chunk
    // pagination without needing the raw constructors.
    const html2canvasOpts = { scale: 2, useCORS: true, backgroundColor: "#ffffff" };
    const jsPdfOpts = { unit: "pt", format: "a4", orientation: "portrait" };

    // A single hidden, on-screen (but covered) stage whose innerHTML is
    // replaced for every chunk — kept genuinely attached and laid out so
    // html2canvas has something real to rasterize.
    const stage = document.createElement("div");
    stage.setAttribute("data-pdf-export", "true");
    stage.style.background = "#ffffff";
    stage.style.width = "820px";
    stage.style.margin = "0 auto";
    stage.style.position = "relative";

    const styleOverride = document.createElement("style");
    styleOverride.textContent = PDF_EXPORT_CSS;

    const cover = document.createElement("div");
    cover.style.cssText = "position:fixed;inset:0;z-index:999999;background:#f7f8fb;" +
      "display:flex;flex-direction:column;gap:10px;align-items:center;justify-content:center;" +
      "color:#1e2433;font:600 15px system-ui,-apple-system,sans-serif;";
    const progressLabel = document.createElement("div");
    progressLabel.textContent = "Generating full manual PDF…";
    cover.appendChild(progressLabel);

    document.body.appendChild(styleOverride);
    document.body.appendChild(stage);
    document.body.appendChild(cover);
    const prevHtmlOverflow = document.documentElement.style.overflow;
    const prevBodyOverflow = document.body.style.overflow;
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";

    const cleanup = () => {
      stage.remove();
      styleOverride.remove();
      cover.remove();
      document.documentElement.style.overflow = prevHtmlOverflow;
      document.body.style.overflow = prevBodyOverflow;
    };

    async function fillStage(html) {
      stage.innerHTML = html;
      const images = Array.from(stage.querySelectorAll("img"));
      images.forEach((img) => img.removeAttribute("loading"));
      await Promise.all(images.map((img) => (img.complete ? Promise.resolve() : new Promise((resolve) => {
        img.addEventListener("load", resolve, { once: true });
        img.addEventListener("error", resolve, { once: true });
      }))));
    }

    // Renders the current stage contents into its own canvas via a
    // throwaway Worker — small enough per chunk to stay well under the
    // browser's max canvas dimension even for the largest single function.
    async function captureStageCanvas() {
      const worker = html2pdf().set({ html2canvas: html2canvasOpts }).from(stage);
      await worker.toCanvas();
      return worker.get("canvas");
    }

    let pdf = null;
    let pageW = 0, pageH = 0;
    const marginX = 28, marginY = 28;
    let usableW = 0, usableH = 0;

    function addCanvasPages(canvas) {
      const imgW = usableW;
      const pxPerPt = canvas.width / imgW;
      const sliceHeightPx = Math.floor(usableH * pxPerPt);
      // A near-empty leftover at the very end (e.g. a few px of trailing
      // margin) would otherwise land alone on its own blank-looking page —
      // fold it into the previous slice instead of starting a new page.
      const minSlicePx = Math.max(40, Math.floor(20 * pxPerPt));

      let renderedPx = 0;
      while (renderedPx < canvas.height) {
        let thisSlicePx = Math.min(sliceHeightPx, canvas.height - renderedPx);
        const remainderAfter = canvas.height - (renderedPx + thisSlicePx);
        if (remainderAfter > 0 && remainderAfter < minSlicePx) {
          thisSlicePx += remainderAfter;
        }
        const sliceCanvas = document.createElement("canvas");
        sliceCanvas.width = canvas.width;
        sliceCanvas.height = thisSlicePx;
        sliceCanvas.getContext("2d").drawImage(
          canvas, 0, renderedPx, canvas.width, thisSlicePx, 0, 0, canvas.width, thisSlicePx
        );
        const sliceImgH = thisSlicePx / pxPerPt;

        pdf.addPage();
        pdf.addImage(sliceCanvas.toDataURL("image/jpeg", 0.95), "JPEG", marginX, marginY, imgW, sliceImgH);

        renderedPx += thisSlicePx;
      }
    }

    const totalUnits = 1 + VISIBLE_MODULES.reduce((n, mod) =>
      n + 1 + mod.functions.length + (mod.faq && mod.faq.length ? 1 : 0), 0) +
      (MANUAL_DATA.homeFaq && MANUAL_DATA.homeFaq.length ? 1 : 0);
    let done = 0;
    const tick = () => { done++; if (onProgress) onProgress(done, totalUnits); };

    // First chunk drives html2pdf's own full pipeline (container → canvas
    // → pdf) so we can pull out a real jsPDF instance via .get('pdf') —
    // this bundle gives no other way to reach the jsPDF constructor.
    await fillStage(`
      <div class="hero-card">
        <h1>${esc(MANUAL_DATA.appName)} — Complete User Manual</h1>
        <p>Generated for your account on ${esc(new Date().toLocaleDateString())} — includes only the modules and
        functions your account's permissions grant access to.</p>
      </div>
    `);
    const firstWorker = html2pdf().set({ html2canvas: html2canvasOpts, jsPDF: jsPdfOpts, margin: [marginY, marginX] }).from(stage);
    await firstWorker.toPdf();
    pdf = await firstWorker.get("pdf");
    pageW = pdf.internal.pageSize.getWidth();
    pageH = pdf.internal.pageSize.getHeight();
    usableW = pageW - marginX * 2;
    usableH = pageH - marginY * 2;
    tick();

    for (const mod of VISIBLE_MODULES) {
      progressLabel.textContent = `Generating full manual PDF… (${mod.title})`;

      await fillStage(`
        <div class="page-header">
          <div class="page-kicker">Module</div>
          <h1>${esc(mod.title)}</h1>
          <p class="page-lede">${esc(mod.intro)}</p>
        </div>
      `);
      addCanvasPages(await captureStageCanvas());
      tick();

      for (const fn of mod.functions) {
        await fillStage(functionDetailHtml(fn, { showNav: false }));
        addCanvasPages(await captureStageCanvas());
        tick();
      }

      if (mod.faq && mod.faq.length) {
        await fillStage(renderFAQSection(mod.faq));
        addCanvasPages(await captureStageCanvas());
        tick();
      }
    }

    if (MANUAL_DATA.homeFaq && MANUAL_DATA.homeFaq.length) {
      progressLabel.textContent = "Generating full manual PDF… (FAQ)";
      await fillStage(renderFAQSection(MANUAL_DATA.homeFaq));
      addCanvasPages(await captureStageCanvas());
      tick();
    }

    cleanup();
    return pdf;
  }

  $exportBtn.addEventListener("click", () => {
    if ($exportBtn.disabled) return;
    $exportBtn.disabled = true;
    const originalLabel = $exportBtn.querySelector("span").textContent;
    $exportBtn.querySelector("span").textContent = "Preparing…";
    showToast("Generating full manual PDF…");

    exportFullManualPdf((done, total) => {
      $exportBtn.querySelector("span").textContent = `Preparing… (${done}/${total})`;
    }).then((pdf) => {
      pdf.save("SAVEST-HRIS-Manual-Complete.pdf");
      $exportBtn.disabled = false;
      $exportBtn.querySelector("span").textContent = originalLabel;
      showToast("✓ PDF downloaded");
    }).catch((err) => {
      console.error("PDF export failed:", err);
      $exportBtn.disabled = false;
      $exportBtn.querySelector("span").textContent = originalLabel;
      showToast("PDF export failed — see console");
    });
  });

  /* ---------------------------------------------------------------------
     Boot
     --------------------------------------------------------------------- */
  buildSidebar();
  router();
})();
