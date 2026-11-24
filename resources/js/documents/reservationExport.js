import jsPDF from "jspdf";

document.addEventListener("DOMContentLoaded", () => {
    function generateReservationPdf(reservation, type = "download") {
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

            // ===== Helper function for sections =====
            function addDetailsSection(title, detailsArray) {
                const sectionHeight = detailsArray.length * 8 + 12;

                // Header background
                pdf.setFillColor(230, 230, 250);
                pdf.rect(padding, y - 8, pageWidth - 2 * padding, 12, "F");
                pdf.setFont("helvetica", "bold");
                pdf.setFontSize(14);
                pdf.setTextColor(50, 50, 120);
                pdf.text(title, padding + 2, y);
                y += 12;

                // Details
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
                    pdf.setTextColor(0, 0, 0);
                    pdf.text(`${label}:`, padding + 2, rowY);

                    if (title === "Payment Details") {
                        pdf.setTextColor(0, 128, 0);
                    } else {
                        pdf.setTextColor(0, 0, 0);
                    }

                    pdf.setFont("helvetica", "normal");
                    pdf.text(String(value), padding + 50, rowY);
                });

                y += detailsArray.length * 8 + 15;
            }

            // ===== User Information Section =====
            const userInfo = [
                ["Name", reservation.user ? `${reservation.user.first_name} ${reservation.user.last_name}` : "N/A"],
                ["Email", reservation.user?.email || "N/A"],
                ["Address", reservation.user?.address || "N/A"],
                ["Contact", reservation.user?.phone || "N/A"]
            ];
            addDetailsSection("User Information", userInfo);

            // ===== Reservation Details Section =====
            const reservationInfo = [
                ["Reservation ID", reservation.id],
                ["Accommodation", reservation.accommodation?.name || "N/A"],
                ["Tour Type", reservation.accommodation?.tour_type ? reservation.accommodation.tour_type.charAt(0).toUpperCase() + reservation.accommodation.tour_type.slice(1) : "N/A"],
                ["Guests", reservation.no_of_guest || "N/A"],
                ["Check-in", new Date(reservation.check_in_date_time).toLocaleString(undefined,{year:"numeric",month:"long",day:"numeric",hour:"numeric",minute:"2-digit",hour12:true})],
                ["Check-out", new Date(reservation.check_out_date_time).toLocaleString(undefined,{year:"numeric",month:"long",day:"numeric",hour:"numeric",minute:"2-digit",hour12:true})],
                ["Status", reservation.status || "PENDING"]
            ];
            addDetailsSection("Reservation Details", reservationInfo);

            // ===== Payment Details Section =====
            const paymentInfo = [
                ["Price per Day", `PHP ${Number(reservation.accommodation_price || 0).toLocaleString("en-PH")}`],
                ["Discount Applied", `${reservation.discount_applied ? reservation.discount_applied + '%' : '0%'}`],
                ["Days of Stay", reservation.no_of_days_stay || "N/A"],
                ["Entrance Fee per Pax", `PHP ${Number(reservation.entrance_fee_per_pax || 0).toLocaleString("en-PH")}`],
                ["Total Entrance Fee", `PHP ${Number(reservation.total_entrance_fee || 0).toLocaleString("en-PH")}`],
                ["Total Price", `PHP ${Number(reservation.total_price || 0).toLocaleString("en-PH")}`],
                ["Paid Balance", `PHP ${Number(reservation.paid_balance || 0).toLocaleString("en-PH")}`],
                ["Remaining Balance", `PHP ${Number(reservation.remaining_balance || 0).toLocaleString("en-PH")}`]
            ];
            addDetailsSection("Payment Details", paymentInfo);

            // ===== Main Terms & Conditions Header =====
            pdf.addPage();
            y = 20; // reset y for new page

            pdf.setFillColor(230, 230, 250);
            pdf.rect(padding, y - 8, pageWidth - 2 * padding, 12, "F");
            pdf.setFont("helvetica", "bold");
            pdf.setFontSize(14);
            pdf.setTextColor(50, 50, 120);
            pdf.text("Terms and Conditions", padding + 2, y);
            y += 12;

            // ===== addTextBlock Function =====
            function addTextBlock(title, text) {
                const blockPadding = 6;
                const blockWidth = pageWidth - 2 * padding;
                const bulletIndent = 8;
                const subBulletIndent = 16;
                const lineHeight = 5.5;

                const rawLines = text.split(/\n/).map(l => l.trim()).filter(l => l);
                const processedLines = rawLines.map(line => {
                    if (line.startsWith("-")) return { text: line.substring(2), indent: bulletIndent, bullet: true };
                    if (line.startsWith("•")) return { text: line.substring(2), indent: subBulletIndent, bullet: true };
                    return { text: line, indent: 0, bullet: false };
                });

                const estimatedHeight = (processedLines.length + 2) * lineHeight + blockPadding * 2;
                if (y + estimatedHeight > pageHeight - 25) {
                    pdf.addPage();
                    y = 20;
                }

                // Draw block rectangle
                pdf.setFillColor(255, 255, 255);
                pdf.setDrawColor(210, 210, 230);
                pdf.rect(padding, y, blockWidth, estimatedHeight, "FD");

                // Block header bar
                pdf.setFillColor(230, 230, 250);
                pdf.rect(padding, y, blockWidth, 10, "F");
                pdf.setFont("helvetica", "bold");
                pdf.setFontSize(12);
                pdf.setTextColor(40, 40, 120);
                pdf.text(title, padding + blockPadding, y + 7);

                // Render text lines
                pdf.setFont("helvetica", "normal");
                pdf.setFontSize(11);
                pdf.setTextColor(0, 0, 0);
                let textY = y + 16;

                processedLines.forEach(lineObj => {
                    if (textY > pageHeight - 25) {
                        pdf.addPage();
                        textY = 20;
                    }

                    const xPos = padding + blockPadding + lineObj.indent;
                    const splitLines = pdf.splitTextToSize(lineObj.text, blockWidth - blockPadding - lineObj.indent);

                    splitLines.forEach((splitLine, idx) => {
                        if (lineObj.bullet && idx === 0) pdf.text("•", xPos - 4, textY);
                        pdf.text(splitLine, xPos, textY);
                        textY += lineHeight;
                    });
                });

                y = textY + 6;
            }

            // ===== Term Sections =====
            addTextBlock("1. Check-In & Check-Out",
                `- Check-in is at 2pm onwards.
                - Check-out is strictly at 12 noon. Additional fees apply for extensions.
                - Guests must clear all personal belongings before check-out.
                - All registered guests will be given wristbands as proof of stay.
                - Full balance must be settled upon check-in at reception.
                - Visitors are not allowed inside villas, only in common areas until the Front Desk closes.
                - Maintain cleanliness and avoid excessive mess (PHP 500 cleaning charge).
                    • Dispose trash properly
                    • Garbage collection at 8:00 PM & 10:00 AM
                    • Wash used kitchen utensils and cookware
                - Inform management about any damages immediately.
                - Guests must pay for damages or missing items.`
            );

            addTextBlock("2. Swimming Attire Policy",
                `- Proper swimwear is required. Cotton shirts, denim, or undergarments are not allowed.
                - All guests must shower before entering the pool.
                - Children must wear swim diapers in the pool area.
                - Improper attire may result in removal from the pool area.`
            );

            addTextBlock("3. Pet Policy",
                `- Pets are allowed in common areas, except the pool area.
                - Owners are responsible for any mess, damage, or injury caused by their pets.
                - All pets must wear diapers while on the property.
                - If inside the villa, pets must remain in a secure cage.`
            );

            addTextBlock("4. Don’ts",
                `- No use of portable heaters or fire-hazard devices.
                - Smoking only in designated areas. Penalty: PHP 500.
                - No loitering, gambling, excessive alcohol, or inappropriate behavior.
                - No weapons, fireworks, or explosives allowed.
                - Maintain quiet hours after 11:00 PM.`
            );

            addTextBlock("5. Reservation & Cancellation",
                `- Reservations require downpayment within 24 hours.
                - Unpaid bookings within 24 hours are cancelled automatically.
                - Rates may change unless secured with advance payment.
                - All payments are non-refundable, but rescheduling is allowed within 6 months.
                - Maximum of 2 rebookings per booking.
                - Rebooking requests must be made 1 week before stay.
                - Same-day cancellations are not accepted; payments are forfeited.
                - Early check-in/late check-out are subject to fees and availability.
                - No-shows are fully charged and non-refundable.
                - Shortened stays are charged in full.
                - Casa Alvadon reserves the right to cancel suspicious bookings.`
            );

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
            if (type === "download") pdf.save(`reservation_${reservation.id}.pdf`);
            else if (type === "print") {
                pdf.autoPrint();
                window.open(pdf.output("bloburl"), "_blank");
            }

        } catch (err) {
            console.error("PDF generation error:", err);
        }
    }

    window.addEventListener("generate-pdf", (e) => {
        const { type, reservation } = e.detail;
        if (!reservation) {
            console.error("No reservation provided");
            return;
        }
        generateReservationPdf(reservation, type);
    });

    console.log("📄 Reservation PDF export script loaded");
});
