<?php

// Config for Alphaolomi/LaravelApprovals
return [
    'models' => [
        'approval' => \Alphaolomi\LaravelApprovals\Models\Approval::class,
    ],
    'table_names' => [
        'approvals' => 'approvals',
    ],
    'approval_types' => [
        'project_submission',
        'project_approval',
    ],
    'approval_workflow_states' => [
        'project_submission' => [
            'prev_approver_role' => 'editor', // 'project_manager
            'next_approver_role' => 'project_manager',
            'next_possible_state' => ['project_approval', 'request_changes'],
            'next_state' => 'project_approval',
        ],
        'project_approval' => [
            'prev_approver_role' => 'project_manager',
            'next_approver_role' => 'project_director',
            'next_possible_state' => ['project_director_approval', 'request_changes'],
            'next_state' => 'project_director_approval',
        ],
        'project_director_approval' => [
            'prev_approver_role' => 'project_director',
            'next_approver_role' => null,
            'next_possible_state' => ['approved', 'request_changes'],
            'next_state' => 'approved',
        ],
        'approved' => [
            'prev_approver_role' => 'project_director',
            'next_approver_role' => null,
            'next_possible_state' => [],
            'next_state' => null,
        ],
    ],
];
