<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('taskora:send-deadline-reminders')->hourly();

