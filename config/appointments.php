<?php

return [
    'status_transitions' => [
        'requested' => ['scheduled', 'cancelled'],
        'scheduled' => ['completed', 'missed', 'cancelled', 'rescheduled'],
        'rescheduled' => ['completed', 'missed', 'cancelled'],
        'completed' => [],
        'missed' => ['rescheduled'],
        'cancelled' => [],
    ],

    'allowed_status_changes_by_role' => [
        'doctor' => ['scheduled' => ['completed']],
        'reception' => [
            'requested' => ['scheduled', 'cancelled'],
            'scheduled' => ['completed', 'missed', 'cancelled', 'rescheduled'],
        ],
        'admin' => '*', // All changes allowed
    ],

    'visit_types' => [
        'consultation' => 30, // duration in minutes
        'follow-up' => 20,
        'lab_review' => 15,
        'rehab_milestone' => 45,
        'emergency' => 60,
        'other' => 30,
    ],

    'working_hours' => [
        'start' => '08:00',
        'end' => '17:00',
        'break_start' => '12:00',
        'break_end' => '13:00',
    ],

    'reminder_hours_before' => 24,
    'auto_missed_cutoff_time' => '23:59',
];