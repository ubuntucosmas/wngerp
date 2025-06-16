<?php

return [
    [
        'title' => 'Client Engagement & Briefing',
        'offsetStart' => 0,
        'offsetEnd' => 5,
        'default_tasks' => [
            [
                'name' => 'Receive Client Brief',
                'description' => 'Capture client needs via email, call, or physical visit.',
                'deliverables' => [
                    'Customer Service captures client needs.',
                    'Assign a Project Officer (PO).',
                    'Log new project entry in system.',
                ],
            ],
            [
                'name' => 'Analyze Requirements',
                'description' => 'Review and allocate project internally.',
                'deliverables' => [
                    'Team leads and PO review client brief.',
                    'Allocate project to relevant departments.',
                    'Schedule internal project briefing.',
                ],
            ],
            [
                'name' => 'Confirm Project Scope',
                'description' => 'Align with client on deliverables and expectations.',
                'deliverables' => [
                    'Document project deliverables.',
                    'Share requirements summary for client confirmation.',
                    'Use official communication channels for confirmation.',
                ],
            ],
        ],
    ],
    [
        'title' => 'Design & Concept Development',
        'offsetStart' => 6,
        'offsetEnd' => 15,
        'default_tasks' => [
            [
                'name' => 'Initial Design Creation',
                'description' => 'Create and share initial design concepts.',
                'deliverables' => [
                    'Designer creates initial concepts.',
                    'Share internally and with client.',
                    'Collect feedback via email or portal.',
                ],
            ],
            [
                'name' => 'Final Design Approval',
                'description' => 'Refine and approve final design.',
                'deliverables' => [
                    'Incorporate revisions from feedback.',
                    'Client provides sign-off.',
                    'Document final designs in ERP.',
                ],
            ],
            [
                'name' => 'Material & Cost Listing',
                'description' => 'Estimate material needs and costs.',
                'deliverables' => [
                    'List all required materials.',
                    'Rough cost estimation.',
                    'Finalize and approve materials list internally.',
                ],
            ],
        ],
    ],

    [
        'title' => 'Quotation & Budget Approval',
        'offsetStart' => 21,
        'offsetEnd' => 25,
        'default_tasks' => [
            [
                'name' => 'Budget Confirmation',
                'description' => 'Validate cost and prepare client quotation.',
                'deliverables' => [
                    'Cross-check cost with scope.',
                    'Generate and send quotation.',
                ],
            ],
            [
                'name' => 'Approval & TAT',
                'description' => 'Follow up and confirm client approval.',
                'deliverables' => [
                    'Follow up within 45 minutes (or as needed).',
                    'Confirm client approval.',
                    'Mark status as “Quote Approved”.',
                ],
            ],
        ],
    ],
    [
        'title' => 'Procurement & Inventory Management',
        'offsetStart' => 16,
        'offsetEnd' => 20,
        'default_tasks' => [
            [
                'name' => 'Inventory Check',
                'description' => 'Ensure necessary stock is available.',
                'deliverables' => [
                    'Store manager checks available stock.',
                ],
            ],
            [
                'name' => 'Procurement Process',
                'description' => 'Raise and track procurement of materials.',
                'deliverables' => [
                    'Raise purchase request.',
                    'Approve via Procurement Officer.',
                    'Track supplier delivery status.',
                ],
            ],
            [
                'name' => 'Inventory Ready for Production',
                'description' => 'Prepare materials for use.',
                'deliverables' => [
                    'Receive and verify materials.',
                    'Notify production team.',
                ],
            ],
        ],
    ],

    [
        'title' => 'Production',
        'offsetStart' => 26,
        'offsetEnd' => 30,
        'default_tasks' => [
            [
                'name' => 'Execute Production',
                'description' => 'Fabricate/brand as per approved design.',
                'deliverables' => [
                    'Log time and material usage.',
                    //'-> call deliverables
                ],
            ],
            [
                'name' => 'Quality Control',
                'description' => 'Ensure deliverables meet standards.',
                'deliverables' => [
                    'QA team inspects output.',
                    'Retouch if needed.',
                    'Approve for delivery.',
                ],
            ],
            [
                'name' => 'Packing & Handover for Setup',
                'description' => 'Prepare items for delivery.',
                'deliverables' => [
                    'Package final items.',
                    'Update delivery docket.',
                    'Handover to logistics.',
                ],
            ],
        ],
    ],
    [
        'title' => 'Event Setup & Execution',
        'offsetStart' => 31,
        'offsetEnd' => 35,
        'default_tasks' => [
            [
                'name' => 'Site Delivery',
                'description' => 'Transport and confirm safe arrival of items.',
                'deliverables' => [
                    'Load and transport items to venue.',
                    'Confirm arrival and condition.',
                ],
            ],
            [
                'name' => 'Setup Execution',
                'description' => 'Install and test setup on-site.',
                'deliverables' => [
                    'Install branding/equipment as per design.',
                    'Test all components.',
                    'Confirm readiness with client.',
                ],
            ],
        ],
    ],

    [
        'title' => 'Client Handover & Feedback',
        'offsetStart' => 41,
        'offsetEnd' => 43,
        'default_tasks' => [
            [
                'name' => 'Final Handover',
                'description' => 'Wrap up project and submit final report.',
                'deliverables' => [
                    'Submit final project report.',
                    'Provide soft copies or photos.',
                ],
            ],
            [
                'name' => 'Feedback Collection',
                'description' => 'Collect feedback and assess satisfaction.',
                'deliverables' => [
                    'Debrief session with client.',
                    'Record satisfaction and lessons learned.',
                ],
            ],
        ],
    ],

    [
        'title' => 'Set Down & Return',
        'offsetStart' => 36,
        'offsetEnd' => 40,
        'default_tasks' => [
            [
                'name' => 'Dismantling',
                'description' => 'Safely uninstall and collect materials.',
                'deliverables' => [
                    'Uninstall props/equipment.',
                    'Account for all items.',
                ],
            ],
            [
                'name' => 'Returns & Storage',
                'description' => 'Return items to workshop and update records.',
                'deliverables' => [
                    'Transport items back.',
                    'Inspect for damage.',
                    'Update return condition.',
                ],
            ],
        ],
    ],
    [
        'title' => 'Archival & Reporting',
        'offsetStart' => 44,
        'offsetEnd' => 45,
        'default_tasks' => [
            [
                'name' => 'Close Project',
                'description' => 'Mark project complete and archive.',
                'deliverables' => [
                    'Mark Project as complete.',
                    'Archive all related documentation.',
                ],
            ],
            [
                'name' => 'Analytics & Reports',
                'description' => 'Generate insights for management review.',
                'deliverables' => [
                    'Create cost, time, and material usage reports.',
                    'Send summary to management.',
                ],
            ],
        ],
    ],
];
