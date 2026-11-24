import Chart from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";

Chart.register(ChartDataLabels);

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("salesChart");
    if (!ctx) return;

    const reservationStats = window.reservationStats || [];

    // labels = accommodation names
    const labels = reservationStats.map(item => item.accommodation_name.charAt(0).toUpperCase() + item.accommodation_name.slice(1));

    // datasets (numbers only!)
    const datasets = {
        weekly: reservationStats.map(item => Number(item.weekly_bookings)),
        monthly: reservationStats.map(item => Number(item.monthly_bookings)),
        yearly: reservationStats.map(item => Number(item.yearly_bookings)),
    };

    const colors = [
        "rgba(200, 50, 80, 0.9)", "rgba(25, 105, 185, 0.9)", "rgba(220, 170, 40, 0.9)",
        "rgba(110, 70, 220, 0.9)", "rgba(210, 100, 20, 0.9)", "rgba(40, 150, 150, 0.9)",
        "rgba(200, 70, 140, 0.9)", "rgba(100, 150, 50, 0.9)", "rgba(220, 160, 20, 0.9)",
        "rgba(70, 90, 100, 0.9)", "rgba(190, 30, 80, 0.9)", "rgba(0, 150, 180, 0.9)",
        "rgba(170, 180, 40, 0.9)", "rgba(90, 60, 50, 0.9)", "rgba(50, 60, 150, 0.9)"
    ];

    let chartType = "bar";
    let currentFilter = "weekly";

    function getChartConfig(type, filter) {
        const salesValue = window.totalSales?.[filter + "_sales"] ?? 0;

        let subtitleText = "";
        const now = new Date();

        if (filter === "weekly") {
            // Get start & end of this week
            const start = new Date(now);
            start.setDate(now.getDate() - now.getDay()); // Sunday
            const end = new Date(start);
            end.setDate(start.getDate() + 6); // Saturday
            subtitleText = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;
        } else if (filter === "monthly") {
            const month = now.toLocaleString("default", { month: "long" });
            subtitleText = `${month} ${now.getFullYear()}`;
        } else if (filter === "yearly") {
            subtitleText = `${now.getFullYear()}`;
        }

        return {
            type,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: filter.charAt(0).toUpperCase() + filter.slice(1) + " Bookings",
                        data: datasets[filter],
                        backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                        borderColor: labels.map((_, i) => colors[i % colors.length]),
                        fill: type === "line" ? false : true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        display: true,
                        position: "top",
                        labels: {
                            generateLabels(chart) {
                                if (type === "pie") {
                                    return Chart.overrides.pie.plugins.legend.labels.generateLabels(chart);
                                }

                                const dataset = chart.data.datasets[0];
                                return chart.data.labels.map((label, i) => ({
                                    text: label,
                                    fillStyle: dataset.backgroundColor[i],
                                    strokeStyle: dataset.borderColor[i],
                                    hidden: false,
                                    index: i
                                }));
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: `Sales: ₱${Number(salesValue).toLocaleString()}`,
                        font: {
                            size: 20,
                            weight: 'bold'
                        },
                        color: "#16a34a",
                    },
                    subtitle: {
                        display: true,
                        text: subtitleText,
                        font: {
                            size: 12,
                            style: "italic"
                        },
                        color: "#374151"
                    },
                    datalabels: {
                        color: "#ffffff",
                        backgroundColor: "rgba(0, 0, 0, 0.5)",
                        borderRadius: 4,
                        font: {
                            weight: "bold",
                            size: 14
                        },
                        formatter: (value, ctx) => {
                            return value;
                        }
                    },
                },
                scales: type === "pie" ? {} : { y: { beginAtZero: true } }
            }
        };
    }

    let salesChart = new Chart(ctx, getChartConfig(chartType, currentFilter));

    // 🔹 Handle chart type buttons
    document.querySelectorAll("[data-chart-type]").forEach(btn => {
        btn.addEventListener("click", () => {
            chartType = btn.dataset.chartType;
            salesChart.destroy();
            salesChart = new Chart(ctx, getChartConfig(chartType, currentFilter));
        });
    });

    // 🔹 Handle Weekly/Monthly/Yearly select
    const filterSelect = document.getElementById("sales");
    if (filterSelect) {
        filterSelect.addEventListener("change", (e) => {
            currentFilter = e.target.value;
            salesChart.destroy();
            salesChart = new Chart(ctx, getChartConfig(chartType, currentFilter));
        });
    }
});
