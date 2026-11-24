import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

// Modal buttons
document.addEventListener("DOMContentLoaded", () => {
    const exportBtn = document.getElementById("exportPdfBtn");
    const printBtn = document.getElementById("printChartBtn");

    exportBtn?.addEventListener("click", () => {
        window.dispatchEvent(new CustomEvent("open-confirmation-modal", { detail: { action: "Export" } }));
    });

    printBtn?.addEventListener("click", () => {
        window.dispatchEvent(new CustomEvent("open-confirmation-modal", { detail: { action: "Print" } }));
    });
});

// Helper: format currency
const formatPHP = (amount) => `₱ ${parseFloat(amount).toLocaleString("en-PH", { minimumFractionDigits: 2 })}`;

// Helper: get period label and date range
const getPeriodAndRange = () => {
    const storedFilter = localStorage.getItem('reportFilter');
    const storedRange = localStorage.getItem('reportRange');
    const today = new Date();
    let periodLabel = "All Time";
    let dateRangeLabel = "-";

    const formatDateReadable = (date) => date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    const formatDateReadableWithYear = (date) => date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    if(storedFilter){
        switch(storedFilter){
            case 'weekly':
                periodLabel = "This Week";
                const sunday = new Date(today);
                sunday.setDate(today.getDate() - today.getDay());
                const saturday = new Date(sunday);
                saturday.setDate(sunday.getDate() + 6);
                dateRangeLabel = `${formatDateReadable(sunday)} – ${formatDateReadableWithYear(saturday)}`;
                break;
            case 'monthly':
                periodLabel = "This Month";
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                dateRangeLabel = `${formatDateReadable(firstDay)} – ${formatDateReadableWithYear(lastDay)}`;
                break;
            case 'yearly':
                periodLabel = "This Year";
                const startYear = new Date(today.getFullYear(), 0, 1);
                const endYear = new Date(today.getFullYear(), 11, 31);
                dateRangeLabel = `${formatDateReadable(startYear)} – ${formatDateReadableWithYear(endYear)}`;
                break;
            case 'range':
                periodLabel = "Custom Range";

                if(storedRange){
                    let [start, end] = storedRange.split(' - ');

                    if (!end || end.trim() === '') {
                        end = start;
                    }

                    const startDate = new Date(start);
                    const endDate = new Date(end);

                    if(start === end) {
                         dateRangeLabel = `${formatDateReadableWithYear(endDate)}`;
                    } else {
                        dateRangeLabel = `${formatDateReadable(startDate)} – ${formatDateReadableWithYear(endDate)}`;
                    }
                } else {
                    dateRangeLabel = "N/A";
                }
                break;
        }
    }

    return { periodLabel, dateRangeLabel };
}

// PDF generation
const generatePdf = (callback) => {
    const data = window.reportData;
    if (!data) return alert("Report data not found.");

    const { periodLabel, dateRangeLabel } = getPeriodAndRange();

    const pdf = new jsPDF("portrait", "mm", "a4");
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();
    const padding = 15;
    let y = 20;

    const logo = new Image();
    logo.src = "/storage/icons/Logo-Favicon.png";

    logo.onload = () => {
        // ===== Header (keeps your colors/design) =====
        pdf.addImage(logo, "PNG", padding, y, 20, 20);

        pdf.setFont("helvetica", "bold");
        pdf.setFontSize(18);
        pdf.setTextColor(0, 0, 0); // default black
        pdf.text("Casa Alvadon", padding + 25, y + 7);

        pdf.setFont("helvetica", "normal");
        pdf.setFontSize(12);
        pdf.setTextColor(0, 0, 0); // keep black
        pdf.text("Purok 1, Brgy Limao, Calauan, Laguna", padding + 25, y + 14);

        const now = new Date();
        pdf.setFontSize(10);
        pdf.setTextColor(100, 100, 100);
        pdf.text(
            `Generated: ${now.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" })}`,
            pageWidth - padding,
            y + 5,
            { align: "right" }
        );

        // Divider
        y += 30;
        pdf.setDrawColor(210, 210, 230);
        pdf.line(padding, y, pageWidth - padding, y);
        y += 15;

        // ===== Sales Summary Table =====
        autoTable(pdf, {
            startY: y,
            head: [["Period", "Date Range", "Total Sales"]],
            body: [[
                periodLabel,
                dateRangeLabel,
                { content: formatPHP(data.total_sales), styles: { textColor: [0, 128, 0] } }
            ]],
            theme: "grid",
        });

        y = pdf.lastAutoTable.finalY + 10;

        // ===== Accommodation Stats Table =====
        const accStats = data.accommodation_stats.map(item => [
            item.name,
            item.bookings,
            { content: formatPHP(item.sales), styles: { textColor: [0, 128, 0] } }
        ]);

        autoTable(pdf, {
            startY: y,
            head: [["Accommodation", "Total Reservations", "Total Sales"]],
            body: accStats,
            theme: "grid",
        });

        y = pdf.lastAutoTable.finalY + 20;

        // ===== Footer =====
        const footerY = pageHeight - 25;
        pdf.setDrawColor(210, 210, 230);
        pdf.line(padding, footerY, pageWidth - padding, footerY);

        pdf.setFont("helvetica", "italic");
        pdf.setFontSize(10);
        pdf.setTextColor(100, 100, 100);
        pdf.text("Thank you for choosing Casa Alvadon!", pageWidth / 2, footerY + 8, { align: "center" });
        pdf.text("For inquiries: 0961-057-4052 | (049)546-7557 | casaalvadon@gmail.com", pageWidth / 2, footerY + 14, { align: "center" });

        callback(pdf);
    };

    logo.onerror = () => alert("Failed to load logo.");
};

// Listen for modal confirmation
document.addEventListener("generate-pdf", (event) => {
    const type = event.detail.type;
    generatePdf((pdf) => {
        if(type === "download"){
            const now = new Date();
            const pad = n => n.toString().padStart(2, "0");
            const filename = `sales_report_${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}.pdf`;
            pdf.save(filename);
        }
        if(type === "print"){
            pdf.autoPrint();
            window.open(pdf.output("bloburl"), "_blank");
        }
    });
});
