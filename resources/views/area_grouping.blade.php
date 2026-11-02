@extends("layout")
@section("additional_head")
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link href="{{ asset('assets/src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <style>
        .tracking-tight  { letter-spacing: -0.5px; }
        .tbtn { width:24px; height:24px; }
        .area-card { border-radius: 14px; }
        .area-card .card-header {
            font-weight: 700; font-size: 0.95rem;
            background: rgba(67, 97, 238, 0.06);
            border-bottom: 1px solid var(--bs-border-color, #e0e6ed);
        }
        .branch-chip {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 10px; border-radius: 10px;
            background: rgba(67, 97, 238, 0.07);
            font-size: 0.82rem; font-weight: 600;
        }
        .branch-chip .tbtn { width: 18px; height: 18px; }

        #addAreaModal .ts-wrapper .item,
        .edit-group-form .ts-wrapper .item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .ts-item-remove {
            cursor: pointer;
            font-weight: 700;
            opacity: 0.65;
            line-height: 1;
            padding: 0 2px;
        }
        .ts-item-remove:hover { opacity: 1; color: #dc3545; }

        .see-more-link {
            display: inline-block;
            border: none;
            background: transparent;
            padding: 0;
            font-size: 0.78rem;
            font-weight: 600;
            color: #4361ee;
            text-decoration: underline;
        }
        .see-more-link:hover { color: #2b3cc9; }
        body.dark .see-more-link { color: #7986f8; }
        body.dark .see-more-link:hover { color: #9aa4fa; }
    </style>
@endsection

@section("content")
<div class="row layout-top-spacing g-3">

    {{-- HEADER --}}
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark tracking-tight mb-1">Area Grouping</h3>
            <p class="text-muted small mb-0">Group branches by area, with a dated history of each assignment.</p>
        </div>
        <div class="d-flex flex-column align-items-start align-items-sm-end gap-2">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Area Grouping</li>
                </ol>
            </nav>
            @if (in_array('ADD AREA GROUPING', session('permissions', [])))
            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 px-3 shadow-sm mt-1"
                    data-bs-toggle="modal" data-bs-target="#addAreaModal" onclick="prefillAddArea('')">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Assign Branch to Area
            </button>
            @endif
        </div>
    </div>

    {{-- ALERTS --}}
    @if ($errors->any())
    <div class="col-12">
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    </div>
    @endif
    @if(session('error'))
    <div class="col-12">
        <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
    </div>
    @endif

    {{-- GROUPED BY AREA --}}
    <div class="col-12 mb-2">
        <h5 class="fw-bold text-dark mb-0">Current Area Groupings</h5>
    </div>
    @forelse($grouped as $areaNum => $branchesInArea)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card area-card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Area {{ $areaNum }}</span>
                <span class="badge bg-light-primary">{{ $branchesInArea->count() }} branch(es)</span>
            </div>
            <div class="card-body d-flex flex-wrap gap-2">
                @foreach($branchesInArea as $branch)
                <span class="branch-chip">
                    {{ $branch->brwp_code }} — {{ $branch->name }}
                    @if (in_array('UPDATE AREA GROUPING', session('permissions', [])))
                    <button type="button" class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                        data-bs-toggle="modal" data-bs-target="#editAreaModal{{ $branch->current_area_movement->bam_id }}" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-light border text-muted mb-0">No branches have been assigned to an area yet.</div>
    </div>
    @endforelse

    {{-- UNASSIGNED --}}
    @if($unassigned->isNotEmpty())
    <div class="col-12">
        <div class="card area-card border-0 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Unassigned Branches</span>
                <span class="badge bg-light-warning">{{ $unassigned->count() }} branch(es)</span>
            </div>
            <div class="card-body d-flex flex-wrap gap-2">
                @foreach($unassigned as $branch)
                <span class="branch-chip">
                    {{ $branch->brwp_code }} — {{ $branch->name }}
                    @if (in_array('ADD AREA GROUPING', session('permissions', [])))
                    <button type="button" class="border-0 bg-transparent text-success d-inline-flex align-items-center justify-content-center p-0 tbtn"
                        data-bs-toggle="modal" data-bs-target="#addAreaModal" onclick="prefillAddArea({{ $branch->id }})" title="Assign to Area">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </button>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ALL RECORDS --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="p-3 pb-0"><h5 class="mb-0"><b>Assignment History</b></h5></div>
                <div class="table-responsive">
                    <table id="zero-config" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead class="table-light text-uppercase font-12 tracking-wider">
                            <tr>
                                <th class="border-0 ps-4 py-3">Effective Date</th>
                                <th class="border-0 py-3">End Date</th>
                                <th class="border-0 py-3">Area Groups</th>
                                <th class="border-0 pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dateGroups as $i => $dateGroup)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($dateGroup->effective_date)->format('M d, Y') }}</td>
                                <td>
                                    @if($dateGroup->end_date)
                                        {{ \Carbon\Carbon::parse($dateGroup->end_date)->format('M d, Y') }}
                                    @else
                                        <span class="badge bg-success">Present</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @php $firstAreaNum = $dateGroup->areas->keys()->first(); @endphp
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                        <span class="badge bg-light-primary">Area {{ $firstAreaNum }}</span>
                                        @foreach($dateGroup->areas->get($firstAreaNum) as $record)
                                        <span class="branch-chip">
                                            {{ $record->brwp_code }} — {{ $record->branch_name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @if($dateGroup->areas->count() > 1)
                                    <button type="button" class="see-more-link mt-1" data-bs-toggle="modal" data-bs-target="#viewAreasModal{{ $i }}">
                                        See more…
                                    </button>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    @if (in_array('UPDATE AREA GROUPING', session('permissions', [])))
                                    <button type="button" class="border-0 bg-transparent text-info d-inline-flex align-items-center justify-content-center p-0 tbtn"
                                        data-bs-toggle="modal" data-bs-target="#editGroupModal{{ $i }}" title="Edit group">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No area assignment records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section("additional_footer")

{{-- ADD MODAL --}}
<div class="modal fade" id="addAreaModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="{{ route('area-grouping.store') }}" id="addAreaForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Assign Branch to Area</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label>Effective Date</label>
                    <input type="date" name="effective_date" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-6">
                    <label>End Date <span class="text-muted small">(leave blank = Present)</span></label>
                    <input type="date" name="end_date" class="form-control form-control-sm">
                </div>
            </div>

            <label class="form-label fw-medium text-secondary small mb-1">Area Groups</label>
            <table class="table table-sm align-middle mb-2">
                <thead>
                    <tr>
                        <th style="width:120px;">Area</th>
                        <th>Branches</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody id="areaRowsBody"></tbody>
            </table>
            <button type="button" id="addAreaRowBtn" class="btn btn-sm btn-outline-primary">+ Add Row</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- EDIT MODALS --}}
@foreach($records as $record)
<div class="modal fade" id="editAreaModal{{ $record->bam_id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('area-grouping.update', $record->bam_id) }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Edit Area Assignment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-2">
                <label>Branch</label>
                <input class="form-control form-control-sm" value="{{ $record->brwp_code }} — {{ $record->branch_name }}" disabled>
            </div>
            <div class="mb-2">
                <label>Area</label>
                <input type="number" name="area" class="form-control form-control-sm" min="1" step="1" value="{{ $record->area }}" required>
            </div>
            <div class="mb-2">
                <label>Effective Date</label>
                <input type="date" name="effective_date" class="form-control form-control-sm"
                    value="{{ \Carbon\Carbon::parse($record->effective_date)->format('Y-m-d') }}" required>
            </div>
            <div class="mb-2">
                <label>End Date <span class="text-muted small">(leave blank = Present)</span></label>
                <input type="date" name="end_date" class="form-control form-control-sm"
                    value="{{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('Y-m-d') : '' }}">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

{{-- EDIT GROUP MODALS — one Effective/End Date editing every area/branch in it at once --}}
@foreach($dateGroups as $i => $dateGroup)
@php
    $groupBamIds = $dateGroup->areas->flatten(1)->pluck('bam_id');
    $groupPrefill = $dateGroup->areas->map(function ($recs, $areaNum) {
        return ['area' => (int) $areaNum, 'brwp_ids' => $recs->pluck('brwp_id')->values()];
    })->values();
@endphp
<div class="modal fade" id="editGroupModal{{ $i }}" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="{{ route('area-grouping.update-group') }}" class="edit-group-form"
            data-prefill="{{ $groupPrefill }}">
        @csrf
        @foreach($groupBamIds as $bamId)
        <input type="hidden" name="bam_ids[]" value="{{ $bamId }}">
        @endforeach
        <div class="modal-header">
          <h5 class="modal-title">Edit Area Assignment Group</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label>Effective Date</label>
                    <input type="date" name="effective_date" class="form-control form-control-sm"
                        value="{{ \Carbon\Carbon::parse($dateGroup->effective_date)->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-6">
                    <label>End Date <span class="text-muted small">(leave blank = Present)</span></label>
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="{{ $dateGroup->end_date ? \Carbon\Carbon::parse($dateGroup->end_date)->format('Y-m-d') : '' }}">
                </div>
            </div>

            <label class="form-label fw-medium text-secondary small mb-1">Area Groups</label>
            <table class="table table-sm align-middle mb-2">
                <thead>
                    <tr>
                        <th style="width:120px;">Area</th>
                        <th>Branches</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody class="edit-area-rows-body"></tbody>
            </table>
            <button type="button" class="btn btn-sm btn-outline-primary add-edit-row-btn">+ Add Row</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

{{-- VIEW ALL AREAS MODALS — read-only, triggered by "See more…" --}}
@foreach($dateGroups as $i => $dateGroup)
<div class="modal fade" id="viewAreasModal{{ $i }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
            All Areas — {{ \Carbon\Carbon::parse($dateGroup->effective_date)->format('M d, Y') }}
            to {{ $dateGroup->end_date ? \Carbon\Carbon::parse($dateGroup->end_date)->format('M d, Y') : 'Present' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @foreach($dateGroup->areas as $areaNum => $recordsInArea)
        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
            <span class="badge bg-light-primary">Area {{ $areaNum }}</span>
            @foreach($recordsInArea as $record)
            <span class="branch-chip">{{ $record->brwp_code }} — {{ $record->branch_name }}</span>
            @endforeach
        </div>
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<script src="{{ asset('assets/src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('assets/src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
// ── Area Groups repeater — shared by the Add modal and every Edit Group
// modal, each with its own independent set of rows/selections. ──────────
const areaBranchOptions = @json($branches->map(fn ($b) => ['value' => (string) $b->id, 'text' => $b->brwp_code . ' - ' . $b->name]));

function createAreaRepeater(tbody, autoNumber) {
    let rowCounter = 0;
    let rowSelects = [];

    function renumber() {
        if (!autoNumber) return;
        tbody.querySelectorAll('.area-row').forEach(function (row, i) {
            row.querySelector('.area-row-number').value = i + 1;
        });
    }

    // A branch picked in one row must disappear from every other row's
    // dropdown in this same repeater (never from its own — it must stay
    // there to show as a selected tag).
    function refreshRowOptions(ts) {
        const selectedElsewhere = new Set();
        rowSelects.forEach(function (other) {
            if (other !== ts) other.items.forEach(function (v) { selectedElsewhere.add(v); });
        });
        areaBranchOptions.forEach(function (opt) {
            const key = opt.value;
            if (selectedElsewhere.has(key)) {
                if (ts.options[key] && !ts.items.includes(key)) ts.removeOption(key);
            } else if (!ts.options[key]) {
                ts.addOption(opt);
            }
        });
    }

    function refreshAll() {
        rowSelects.forEach(refreshRowOptions);
    }

    function addRow(presetArea, presetBrwpIds) {
        const idx = rowCounter++;
        const tr = document.createElement('tr');
        tr.className = 'area-row';
        const areaFieldAttrs = autoNumber ? 'readonly tabindex="-1"' : 'min="1" step="1" required';
        tr.innerHTML =
            '<td><input type="number" name="rows[' + idx + '][area]" class="form-control form-control-sm area-row-number" ' + areaFieldAttrs + ' value="' + (presetArea || '') + '"></td>' +
            '<td><select name="rows[' + idx + '][brwp_ids][]" multiple class="area-row-branches"></select></td>' +
            '<td class="text-center"><button type="button" class="border-0 bg-transparent text-danger remove-row-btn" title="Remove row">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button></td>';
        tbody.appendChild(tr);

        const select = tr.querySelector('.area-row-branches');
        const ts = new TomSelect(select, {
            options: areaBranchOptions,
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            maxItems: null,
            placeholder: 'Select branch(es)…',
            onChange: function () { refreshAll(); },
            render: {
                item: function (data, escape) {
                    return '<div>' + escape(data.text) +
                        '<span class="ts-item-remove" data-value="' + escape(data.value) + '" title="Remove">&times;</span></div>';
                }
            }
        });
        // No remove_button plugin in this bundle — the × above is rendered by hand,
        // so removing an item has to be wired up the same way, via delegation.
        ts.control.addEventListener('click', function (e) {
            if (e.target.classList.contains('ts-item-remove')) {
                e.preventDefault();
                e.stopPropagation();
                ts.removeItem(e.target.getAttribute('data-value'));
            }
        });
        rowSelects.push(ts);
        if (presetBrwpIds && presetBrwpIds.length) ts.setValue(presetBrwpIds.map(String));
        refreshAll();

        tr.querySelector('.remove-row-btn').addEventListener('click', function () {
            if (tbody.querySelectorAll('.area-row').length <= 1) return;
            rowSelects = rowSelects.filter(function (s) { return s !== ts; });
            ts.destroy();
            tr.remove();
            renumber();
            refreshAll();
        });

        renumber();
    }

    function reset() {
        rowSelects.forEach(function (ts) { ts.destroy(); });
        rowSelects = [];
        tbody.innerHTML = '';
        rowCounter = 0;
    }

    return { addRow: addRow, reset: reset };
}

// Add modal — area number is auto-assigned by row position (1..N), readonly.
const addRepeater = createAreaRepeater(document.getElementById('areaRowsBody'), true);
document.getElementById('addAreaRowBtn').addEventListener('click', function () {
    addRepeater.addRow();
});
window.prefillAddArea = function (brwpId) {
    addRepeater.reset();
    addRepeater.addRow(null, brwpId ? [brwpId] : null);
};

// Edit Group modals — area number is the real, editable value; rows are
// pre-filled from that group's current areas/branches, built lazily the
// first time each modal is opened.
document.querySelectorAll('.edit-group-form').forEach(function (form) {
    const tbody = form.querySelector('.edit-area-rows-body');
    const addBtn = form.querySelector('.add-edit-row-btn');
    const repeater = createAreaRepeater(tbody, false);
    let built = false;

    form.closest('.modal').addEventListener('show.bs.modal', function () {
        if (built) return;
        built = true;
        let prefill = [];
        try { prefill = JSON.parse(form.dataset.prefill || '[]'); } catch (e) {}
        if (prefill.length) {
            prefill.forEach(function (row) { repeater.addRow(row.area, row.brwp_ids); });
        } else {
            repeater.addRow();
        }
    });

    addBtn.addEventListener('click', function () {
        repeater.addRow();
    });
});

$('#zero-config').DataTable({
    dom:
        "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
        "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'" +
        "<'dt--pages-count mb-sm-0 mb-3'i>" +
        "<'dt--pagination'p>>",
    oLanguage: {
        oPaginate: {
            sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
            sNext:     '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
        },
        sInfo: "Showing entries _START_ to _END_ of _TOTAL_",
        sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        sSearchPlaceholder: "Quick filter...",
        sLengthMenu: "Display: _MENU_"
    },
    stripeClasses: [],
    lengthMenu: [10, 20, 50],
    pageLength: 10,
    ordering: false
});

document.querySelectorAll('.btn[title], a.btn[title], button[title]').forEach(function(el) {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
});
</script>
@endsection
