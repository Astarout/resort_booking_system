import jsPDF from "jspdf";

document.addEventListener("DOMContentLoaded", () => {
    const exportBtn = document.getElementById("exportPdfBtn");
    const printBtn = document.getElementById("printChartBtn");
    const chartCanvas = document.getElementById("salesChart");

    if (!chartCanvas) return;

    function generatePdf(callback) {
        const pdf = new jsPDF("landscape", "mm", "a4");
        const pageWidth = pdf.internal.pageSize.getWidth();
        const padding = 15;
        let y = 20;

        const labelX = padding;

        const logo = new Image();
        logo.src = "/storage/icons/Logo-Favicon.png";

        logo.onload = () => {
            // --- Header ---
            pdf.addImage(logo, "PNG", labelX, y, 20, 20);
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(18);
            pdf.text("Casa Alvadon", labelX + 25, y + 7);
            pdf.setFontSize(12);
            pdf.setFont("helvetica", "normal");
            pdf.text("Purok 1, Brgy Limao, Calauan, Laguna", labelX + 25, y + 14);

            const now = new Date();
            const dateStr = now.toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
            pdf.setFontSize(10);
            pdf.text(`Generated: ${dateStr}`, pageWidth - padding, y + 5, {
                align: "right",
            });

            y += 30;
            pdf.setDrawColor(180);
            pdf.line(padding, y, pageWidth - padding, y);
            y += 15;

            // --- Chart ---
            const chartImage = chartCanvas.toDataURL("image/png", 1.0);
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(14);
            pdf.text("Sales Chart", labelX, y);
            y += 10;

            pdf.addImage(chartImage, "PNG", padding, y, pageWidth - padding * 2, 120);

            callback(pdf);
        };

        logo.onerror = () => {
            alert("Failed to load logo.");
        };
    }

    exportBtn?.addEventListener("click", () => {
        window.dispatchEvent(new CustomEvent("open-confirmation-modal", { detail: { action: "Export" } }));
    });

    printBtn?.addEventListener("click", () => {
        window.dispatchEvent(new CustomEvent("open-confirmation-modal", { detail: { action: "Print" } }));
    });

    // Listen for Alpine-generated events
    document.addEventListener("generate-pdf", (e) => {
        const type = e.detail.type; // 'download' or 'print'
        generatePdf((pdf) => {
            if (type === "download") pdf.save("sales_chart.pdf");
            if (type === "print") {
                pdf.autoPrint();
                window.open(pdf.output("bloburl"), "_blank");
            }
        });
    });
});
