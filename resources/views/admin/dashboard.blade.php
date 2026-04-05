@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark text-uppercase">Statistical Dashboard</h3>
            <p class="text-muted small">Real-time overview of ideas and department activities</p>
        </div>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Export Report
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-primary border-5">
                <div class="card-body px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Total Ideas</div>
                            <div class="h3 fw-bold mb-0">{{ $stats->sum('ideas_count') }}</div>
                        </div>
                        <i class="fas fa-lightbulb fa-2x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-success border-5">
                <div class="card-body px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Departments</div>
                            <div class="h3 fw-bold mb-0">{{ $stats->count() }}</div>
                        </div>
                        <i class="fas fa-building fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-warning border-5">
                <div class="card-body px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold text-uppercase">Staff Contribution</div>
                            <div class="h3 fw-bold mb-0">{{ number_format(($stats->sum('ideas_count') / max(1, $totalUsers)) * 100, 1) }}%</div>
                        </div>
                        <i class="fas fa-users fa-2x text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-table me-2"></i>Ideas by Department</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th class="ps-4">Department Name</th>
                                <th class="text-center">Idea Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats as $stat)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $stat->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3">{{ $stat->ideas_count }}</span>
                                </td>
                                <td style="width: 30%">
                                    @php 
                                        $percent = $stats->sum('ideas_count') > 0 ? ($stat->ideas_count / $stats->sum('ideas_count')) * 100 : 0; 
                                    @endphp
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ round($percent, 1) }}%</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Visual Analytics</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 350px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script vẽ biểu đồ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut', // Biểu đồ vòng cung (Donut) nhìn chuyên nghiệp hơn
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    data: {!! json_encode($data) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                cutout: '70%', // Độ rỗng ở giữa biểu đồ
            }
        });
    });
</script>
@endsection