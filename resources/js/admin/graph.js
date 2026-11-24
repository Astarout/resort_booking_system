import Chart from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";

Chart.register(ChartDataLabels);

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("salesChart");
    if (!ctx) return;

    const stats = JSON.parse(ctx.dataset.stats || "[]");

    if (stats.length === 0) return;

    const labels = stats.map(item => item.accommodation_name.charAt(0).toUpperCase() + item.accommodation_name.slice(1));
    const dataValues = stats.map(item => Number(item.monthly_bookings));

    const colors = [
        "rgba(200, 50, 80, 0.9)", "rgba(25, 105, 185, 0.9)", "rgba(220, 170, 40, 0.9)",
        "rgba(110, 70, 220, 0.9)", "rgba(210, 100, 20, 0.9)", "rgba(40, 150, 150, 0.9)",
        "rgba(200, 70, 140, 0.9)", "rgba(100, 150, 50, 0.9)", "rgba(220, 160, 20, 0.9)",
        "rgba(70, 90, 100, 0.9)", "rgba(190, 30, 80, 0.9)", "rgba(0, 150, 180, 0.9)",
        "rgba(170, 180, 40, 0.9)", "rgba(90, 60, 50, 0.9)", "rgba(50, 60, 150, 0.9)"
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monthly Bookings',
                data: dataValues,
                backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                borderColor: labels.map((_, i) => colors[i % colors.length]),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#ffffff',
                    font: { weight: 'bold' },
                    formatter: (value) => value
                },
                legend: { display: false },
                title: {
                    display: true,
                    text: '',
                    font: { size: 18, weight: 'bold' },
                    color: "#374151"
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
