var urlGetData = '';
var urlStats   = '';
var urlExport  = '';
var chartDist, chartBulan;

$(document).ready(function () {
    var cfg = document.getElementById('face-log-config');
    if (cfg) {
        urlGetData = cfg.getAttribute('data-get-data');
        urlStats   = cfg.getAttribute('data-stats');
        urlExport  = cfg.getAttribute('data-export');
    }

    loadStats();
    loadData(1);

    $('#btn-filter').on('click', function () { loadData(1); });
    $('#btn-reset').on('click', function () {
        $('.filter-field').val('');
        loadData(1);
    });

    $('.filter-field').on('change', function () {
        var params = new URLSearchParams({
            aksi: $('#filter-aksi').val(),
            tanggal_dari: $('#filter-dari').val(),
            tanggal_sampai: $('#filter-sampai').val(),
        });
        $('#btn-export').attr('href', urlExport + '?' + params.toString());
    });

    $(document).on('click', '#pagination-wrap a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if (!href) return;
        var page = new URL(href, window.location.origin).searchParams.get('page') || 1;
        loadData(page);
    });
});

function loadData(page) {
    $('#loading-table').show();
    $.get(urlGetData, {
        aksi: $('#filter-aksi').val(),
        match: $('#filter-match').val(),
        tanggal_dari: $('#filter-dari').val(),
        tanggal_sampai: $('#filter-sampai').val(),
        page: page,
    }, function (res) {
        $('#loading-table').hide();
        $('#table-body').html(res.table || '<tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>');
        $('#pagination-wrap').html(res.pagination);
        $('#total-info').html('Total: <b>' + res.total + '</b> log');
    });
}

function loadStats() {
    $.get(urlStats, function (res) {
        var distLabels = Object.keys(res.distribusi);
        var distData   = Object.values(res.distribusi);
        if (chartDist) chartDist.destroy();
        chartDist = new Chart(document.getElementById('chart-distribusi'), {
            type: 'bar',
            data: {
                labels: distLabels,
                datasets: [{ label: 'Jumlah', data: distData, backgroundColor: '#4a90d9' }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        var bulanLabels = res.per_bulan.map(function (r) { return r.bulan; });
        var matchData   = res.per_bulan.map(function (r) { return parseInt(r.match_count) || 0; });
        var gagalData   = res.per_bulan.map(function (r) { return parseInt(r.gagal_count) || 0; });
        if (chartBulan) chartBulan.destroy();
        chartBulan = new Chart(document.getElementById('chart-bulanan'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [
                    { label: 'Match', data: matchData, backgroundColor: '#28a745' },
                    { label: 'Gagal', data: gagalData, backgroundColor: '#dc3545' },
                ]
            },
            options: { scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } } } }
        });
    });
}
