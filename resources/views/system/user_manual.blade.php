<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }}</title>
<link rel="icon" type="image/x-icon" href="{{ asset('assets/src/assets/img/savesthris_logo.svg')}}"/>
<link rel="stylesheet" href="{{ asset('assets/manual/css/style.css') }}?v={{ filemtime(public_path('assets/manual/css/style.css')) }}">
<script>
  window.MANUAL_ASSET_BASE = "{{ asset('assets/manual') }}";
  window.USER_PERMISSIONS = @json(session('permissions', []));
</script>
</head>
<body>

<!-- Mobile top bar -->
<div class="mobile-topbar">
  <button id="mobileSidebarToggle" class="icon-btn" aria-label="Toggle menu">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
  </button>
  <span class="mobile-title">SAVEST-HR Manual</span>
</div>

<div class="app-shell">

  <!-- ============================= SIDEBAR ============================= -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <div class="brand-logo"><img src="{{ asset('assets/src/assets/img/savesthris_logo.svg') }}" alt="SAVEST-HRIS logo"></div>
      <div class="brand-text">
        <div class="brand-name">SAVEST-HR</div>
        <div class="brand-sub">User Manual</div>
      </div>
    </div>

    <div class="sidebar-search">
      <svg class="sidebar-search-icon" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" id="sidebarSearch" placeholder="Filter modules & functions…" autocomplete="off">
      <button id="sidebarSearchClear" class="clear-btn" title="Clear" hidden>&times;</button>
    </div>

    <nav class="sidebar-nav" id="sidebarNav"><!-- injected by app.js --></nav>

    <div class="sidebar-footer" style="flex-direction:column;align-items:flex-start;gap:8px;">
      <a href="{{ route('system.dashboard') }}" style="display:flex;align-items:center;gap:6px;text-decoration:none;color:inherit;">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path></svg>
        <span>Back to HRIS</span>
      </a>
      <div>
        <span>v<span id="verNum"></span></span>
        <span class="dot">•</span>
        <span>SAVEST-HR Docs</span>
      </div>
    </div>
  </aside>

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- ============================== MAIN =============================== -->
  <div class="main-col">

    <header class="topbar">
      <div class="topbar-left">
        <nav class="breadcrumb" id="breadcrumb" aria-label="Breadcrumb"></nav>
      </div>

      <div class="topbar-right">
        <div class="page-finder" id="pageFinder">
          <svg class="pf-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" id="pageFinderInput" placeholder="Search the whole manual…  (Press / to focus)" autocomplete="off">
          <kbd class="pf-kbd">/</kbd>
          <div class="pf-results" id="pfResults" hidden></div>
        </div>

        <button id="themeToggle" class="icon-btn" title="Toggle light / dark theme">
          <svg class="icon-sun" viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
          <svg class="icon-moon" viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        </button>

        <button id="exportPdfBtn" class="btn-export" title="Export the complete manual to PDF — includes only what your account has permission to see">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
          <span>Export Full Manual</span>
        </button>
      </div>
    </header>

    <main class="content" id="content">
      <!-- injected by app.js -->
    </main>

  </div>
</div>

<div id="pdfToast" class="pdf-toast" hidden></div>

<!-- Vendored (source, not CDN) PDF export engine -->
<script src="{{ asset('assets/manual/js/vendor/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('assets/manual/js/content-data.js') }}?v={{ filemtime(public_path('assets/manual/js/content-data.js')) }}"></script>
<script src="{{ asset('assets/manual/js/app.js') }}?v={{ filemtime(public_path('assets/manual/js/app.js')) }}"></script>
</body>
</html>
