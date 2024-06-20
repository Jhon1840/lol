<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Productos Más Vendidos</h3>
            <div class="btn-group float-right" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" onclick="updateChart('day')">Día</button>
                <button type="button" class="btn btn-secondary" onclick="updateChart('week')">Semana</button>
                <button type="button" class="btn btn-secondary" onclick="updateChart('month')">Mes</button>
            </div>
        </div>
        <canvas id="productosMasVendidosChart"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('productosMasVendidosChart').getContext('2d');
            let productosMasVendidosChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: [],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            async function updateChart(period) {
                const response = await fetch(`/productos-mas-vendidos/${period}`);
                const data = await response.json();
                productosMasVendidosChart.data.labels = data.labels;
                productosMasVendidosChart.data.datasets[0].data = data.data;
                productosMasVendidosChart.update();
            }

            // Cargar datos iniciales para el periodo 'día'
            updateChart('day');
        </script>
    </div>
</div>
