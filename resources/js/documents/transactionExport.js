import jsPDF from "jspdf";

document.addEventListener("DOMContentLoaded", () => {
    function generateTransactionPdf(transaction, type = "download") {
        try {
            const pdf = new jsPDF("p", "mm", "a4");
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const padding = 15;
            let y = 20;

            // ===== Logo & Header =====
            try {
                pdf.addImage("/storage/icons/Logo-Favicon.png", "PNG", padding, y, 20, 20);
            } catch (e) {
                console.warn("Logo not added:", e);
            }

            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(18);
            pdf.text("Casa Alvadon", padding + 25, y + 7);
            pdf.setFont("helvetica", "normal");
            pdf.setFontSize(12);
            pdf.text("Purok 1, Brgy Limao, Calauan, Laguna", padding + 25, y + 14);

            const now = new Date();
            pdf.setFontSize(10);
            pdf.text(
                now.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" }),
                pageWidth - padding,
                y + 5,
                { align: "right" }
            );

            // ===== Divider =====
            y += 30;
            pdf.setDrawColor(200);
            pdf.line(padding, y, pageWidth - padding, y);
            y += 15;

            // ===== Helper function =====
            function addDetailsSection(title, detailsArray) {
                const sectionHeight = detailsArray.length * 8 + 12;

                pdf.setFillColor(230, 230, 250);
                pdf.rect(padding, y - 8, pageWidth - 2 * padding, 12, "F");
                pdf.setFont("helvetica", "bold");
                pdf.setFontSize(14);
                pdf.setTextColor(50, 50, 120);
                pdf.text(title, padding + 2, y);
                y += 12;

                pdf.setFont("helvetica", "normal");
                pdf.setFontSize(11);
                pdf.setTextColor(0, 0, 0);

                detailsArray.forEach(([label, value], index) => {
                    const rowHeight = 8;
                    const rowY = y + index * rowHeight;

                    if (index % 2 === 0) {
                        pdf.setFillColor(245, 245, 245);
                        pdf.rect(padding, rowY - 6, pageWidth - 2 * padding, rowHeight, "F");
                    }

                    pdf.setFont("helvetica", "bold");
                    pdf.text(`${label}:`, padding + 2, rowY);
                    pdf.setFont("helvetica", "normal");
                    pdf.text(String(value), padding + 50, rowY);
                });

                y += detailsArray.length * 8 + 15;
            }

            // ===== Transaction Details =====
            const transactionInfo = [
                ["Transaction ID", transaction.transaction_id],
                ["Customer Name", transaction.reservation.user ? `${transaction.reservation.user.first_name} ${transaction.reservation.user.last_name}` : "N/A"],
                ["Email", transaction.reservation.user?.email || "N/A"],
                ["Payment Method", (transaction.payment_method || "N/A").charAt(0).toUpperCase() + (transaction.payment_method || "N/A").slice(1)],
                ["Amount Paid", `${Number(transaction.amount || 0).toLocaleString("en-PH")}`],
                ["Remaining Balance", `${Number(transaction.reservation.remaining_balance || 0).toLocaleString("en-PH")}`],
                ["Transaction Date",
                    new Date(transaction.created_at).toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                        second: "2-digit",
                        hour12: true
                    })
                ],
                ["Status", (transaction.payment_status || "N/A").charAt(0).toUpperCase() + (transaction.payment_status || "N/A").slice(1)],
                ["Recorded By", transaction.user ? `${transaction.user.first_name} ${transaction.user.last_name}` : "N/A"],
            ];
            addDetailsSection("Transaction Details", transactionInfo);

            // ===== Footer =====
            const footerY = pageHeight - 25;
            pdf.setDrawColor(200);
            pdf.line(padding, footerY, pageWidth - padding, footerY);
            pdf.setFont("helvetica", "italic");
            pdf.setFontSize(10);
            pdf.setTextColor(100);
            pdf.text("Thank you for choosing Casa Alvadon!", pageWidth / 2, footerY + 8, { align: "center" });
            pdf.text("For inquiries: 0961-057-4052 | (049)546-7557 | casaalvadon@gmail.com", pageWidth / 2, footerY + 14, { align: "center" });

            // ===== Output =====
            if (type === "download") pdf.save(`${transaction.transaction_id}.pdf`);
            else if (type === "print") {
                pdf.autoPrint();
                window.open(pdf.output("bloburl"), "_blank");
            }

        } catch (err) {
            console.error("PDF generation error:", err);
        }
    }

    window.addEventListener("generate-transaction-pdf", (e) => {
        const { type, transaction } = e.detail;
        if (!transaction) {
            console.error("No transaction provided");
            return;
        }
        generateTransactionPdf(transaction, type);
    });

    console.log("📄 Transaction PDF export script loaded");
});
