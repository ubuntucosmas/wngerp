@extends('layouts.master')

@section('title', 'Inventory Dashboard')
@section('navbar-title', 'Inventory Dashboard')

@section('content')

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

</style>

<div class="container mt-4">
    <div class="row">
        <!-- Left Side: Cards -->
            <div class="col-md-3">
                <!-- Check-In Card -->
                <div class="card mb-3" style="background-color: #0fa3b1; color: #ffffff;"> <!-- Corporate teal -->
                    <div class="card-body text-center">
                        <h5>Check-In</h5>
                    </div>
                </div>

                <!-- Check-Out Card -->
                <div class="card mb-3" style="background-color: #b5e2fa; color: #0056b3;"> <!-- Light blue with dark text -->
                    <div class="card-body text-center">
                        <h5>Check-Out</h5>
                    </div>
                </div>

                <!-- Inventory Card -->
                <div class="card mb-3" style="background-color: #eddea4; color: #2c7da0"> <!-- Light gold with brown text -->
                    <div class="card-body text-center">
                        <h5>Inventory</h5>
                    </div>
                </div>

                <!-- Returns Card -->
                <div class="card mb-3" style="background-color: #0fa3b1; color: #ffffff;"> <!-- Reuse teal for brand consistency -->
                    <div class="card-body text-center">
                        <h5>Returns</h5>
                    </div>
                </div>

                <!-- Defectives Card -->
                <div class="card mb-3" style="background-color: #b5e2fa; color: #0056b3;"> <!-- Light blue -->
                    <div class="card-body text-center">
                        <h5>Defectives</h5>
                    </div>
                </div>

                <!-- Rentals Card -->
                <div class="card mb-3" style="background-color: #eddea4; color: #7a5c00;"> <!-- Light gold -->
                    <div class="card-body text-center">
                        <h5>Rentals</h5>
                    </div>
                </div>
    </div>

        <!-- Right Side -->
        <div class="col-md-9">
            <!-- Recent Activities Table -->
            <div class="card mb-4">
                <div class="card-header">Recent Activities</div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Performe by</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogs as $log)
                                <tr>
                                    <td>{{ $log->performed_by }}</td> <!-- Assuming 'action' represents the module -->
                                    <td>{{ $log->action }}</td> <!-- Capture the user or action -->
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td> <!-- Format the date -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Section: Split into Two -->
            <div class="row">
                <!-- Pie Chart (Left) -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Returns, Defectives, Rentals</div>
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bar Graph (Right) -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Check-In vs Check-Out vs Inventory</div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="barChart" width="400" height="200"></canvas>
<script>

    // Pie Chart: Returns, Defectives, Rentals
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Returns', 'Defectives', 'Rentals'], // Categories
            datasets: [{
                data: [30, 20, 50], // Replace with actual data
                backgroundColor: ['#8BBEB2', '#0FDE35', '#46E7E7'], // Colors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Bar Graph: Check-In, Check-Out, Inventory
    const monthlyData = @json($monthlyData);
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const totalStock = [], quantityCheckedOut = [], quantityCheckedIn = [];

for (let i = 1; i <= 12; i++) {
    if (monthlyData[i]) {
        totalStock.push(monthlyData[i].total_stock);
        quantityCheckedOut.push(monthlyData[i].total_checked_out);
        quantityCheckedIn.push(monthlyData[i].total_checked_in);
    } else {
        totalStock.push(0);
        quantityCheckedOut.push(0);
        quantityCheckedIn.push(0);
    }
}

const barCtx = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [
            {
                label: 'Total Stock',
                data: totalStock,
                backgroundColor: '#04F6E2'
            },
            {
                label: 'Check-Out',
                data: quantityCheckedOut,
                backgroundColor: '#0FDE35'
            },
            {
                label: 'Check-In',
                data: quantityCheckedIn,
                backgroundColor: '#FFFC99'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>


@endsection