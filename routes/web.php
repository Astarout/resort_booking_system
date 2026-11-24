<?php

# Landing Page Routes
require __DIR__ . '/Home/LandingPage.php';

# Authentication Routes
require __DIR__ . '/Auth/login.php';
require __DIR__ . '/Auth/register.php';
require __DIR__ . '/Auth/logout.php';
require __DIR__ . '/Auth/emailVerification.php';
require __DIR__ . '/Auth/forgotPassword.php';

# User Routes
require __DIR__ . '/User/dashboard.php';
require __DIR__ . '/User/profile.php';
require __DIR__ . '/User/notification.php';
require __DIR__ . '/User/reservation.php';
require __DIR__ . '/User/record.php';
require __DIR__ . '/User/reschedule_request.php';
require __DIR__ . '/User/payment.php';
require __DIR__ . '/User/refund_request.php';

# Admin Routes
require __DIR__ . '/Admin/dashboard.php';
require __DIR__ . '/Admin/profile.php';
require __DIR__ . '/Admin/rating.php';
require __DIR__ . '/Admin/account.php';
require __DIR__ . '/Admin/gallery.php';
require __DIR__ . '/Admin/accommodation.php';
require __DIR__ . '/Admin/notification.php';
require __DIR__ . '/Admin/reservation.php';
require __DIR__ . '/Admin/calendar.php';
require __DIR__ . '/Admin/expectedGuests.php';
require __DIR__ . '/Admin/reschedule_request.php';
require __DIR__ . '/Admin/payments.php';
require __DIR__ . '/Admin/report.php';
require __DIR__ . '/Admin/backup_and_recovery.php';
require __DIR__ . '/Admin/refund_request.php';
