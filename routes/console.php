<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('accounts:delete-unverified-users')
    ->daily();

Schedule::command('app:mark-reservations-as-done')
    ->everyMinute();

Schedule::command('app:send-today-reservation-notifications')
    ->dailyAt('6:30');

Schedule::command('app:send-remaining-balance-notifications')
    ->dailyAt('8:00');

Schedule::command('app:delete-deactivated-users')
    ->daily();

Schedule::command('app:backup-daily')
    ->daily();
