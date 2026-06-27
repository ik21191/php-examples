<!-- views/admin/dashboard.php -->
<div class="max-w-6xl mx-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Analytics Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Audit operational platform revenues, volume pipelines, and metrics.</p>
        </div>
        <span class="bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 px-3 py-1.5 rounded-full flex items-center space-x-1.5">
            <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
            <span>Live Audit Console</span>
        </span>
    </div>

    <!-- 1. KPI Metric Grid Layout Cards Panel Layer -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl text-xl font-bold">₹</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Revenue</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5">₹<?= number_format($kpi['revenue'], 2); ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">📦</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Orders Processed</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5"><?= $kpi['orders']; ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl text-xl">🛒</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Items Dispatched</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5"><?= $kpi['items']; ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl text-xl">👤</div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Registered Users</p>
                <h3 class="text-xl font-black text-gray-900 mt-0.5"><?= $kpi['users']; ?></h3>
            </div>
        </div>
    </div>

    <!-- 2. Dual Graph Layout Visual Elements Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Chronological Sales Trend Chart Area Container Canvas (2/3 width) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-md font-bold text-gray-800 mb-4">Revenue & Transaction Volume Over Time</h3>
            <div id="trendChartContainer" class="relative w-full h-64">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Payment Mode Pie Chart Area Allocation Container Canvas (1/3 width) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <h3 class="text-md font-bold text-gray-800 mb-4">Payment Methods Share</h3>
            <div id="pieChartContainer" class="relative w-full h-48 flex items-center justify-center">
                <canvas id="paymentShareChart"></canvas>
            </div>
            <div class="mt-4 border-t border-gray-50 pt-3 text-xs text-gray-400 space-y-1">
                <p>* Ratios evaluate aggregated channel usage instances natively.</p>
            </div>
        </div>
    </div>
</div>

<!-- Load highly compatible UMD Chart.js layout framework via unblocked CDN mirror -->


<script>
document.addEventListener("DOMContentLoaded", function() {
    // -------------------------------------------------------------
    // Data Unpacking Logic Elements Setup Injection
    // -------------------------------------------------------------
    <?php
    $trendLabels = [];
    $trendData = [];
    foreach ($revenueTrend as $row) {
        $trendLabels[] = date('d M', strtotime($row['order_date']));
        $trendData[] = (float)$row['revenue'];
    }

    $pieLabels = [];
    $pieData = [];
    foreach ($paymentDistribution as $row) {
        $pieLabels[] = strtoupper($row['payment_method']);
        $pieData[] = (int)$row['usage_count'];
    }
    ?>

    // Fail-safe protection check: Intercept missing global variable block errors safely
    if (typeof Chart === 'undefined') {
        console.error("Critical: Chart.js framework was blocked from loading on localhost.");
        
        var fallbackHTML = "<div class='p-4 text-xs text-amber-700 bg-amber-50 rounded-xl border border-amber-100 text-center w-full mt-12'>" +
            "⚠️ Graph script asset blocked by browser privacy/ad-block filter. Please temporarily toggle off extensions or use Incognito Mode to render analytics." +
            "</div>";
            
        document.getElementById('trendChartContainer').innerHTML = fallbackHTML;
        document.getElementById('pieChartContainer').innerHTML = fallbackHTML;
        return; // Halt script execution safely
    }

    // 1. Initialize Linear Revenue Trend Line Graph Canvas Configuration
    var trendCtx = document.getElementById('revenueTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trendLabels); ?>,
            datasets: [{
                label: 'Gross Daily Revenues (₹)',
                data: <?= json_encode($trendData); ?>,
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#2563EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F3F4F6' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Initialize Pie Framework Breakdown Visualization Canvas Configuration
    var paymentCtx = document.getElementById('paymentShareChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($pieLabels); ?>,
            datasets: [{
                data: <?= json_encode($pieData); ?>,
                backgroundColor: ['#2563EB', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'],
                borderWidth: 2,
                borderColor: '#FFFFFF'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
<script src="/chart.umd.js"></script>