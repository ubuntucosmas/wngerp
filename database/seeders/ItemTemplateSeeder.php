<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategory;
use App\Models\ItemTemplate;
use App\Models\ItemTemplateParticular;
use App\Models\User;

class ItemTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user for created_by
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        // Create sample categories
        $categories = [
            [
                'name' => 'Stages',
                'description' => 'Various types of stages and platforms',
                'templates' => [
                    [
                        'name' => 'Standard Stage 3x4m',
                        'description' => 'Standard stage with 3x4 meter dimensions',
                        'estimated_cost' => 15000.00,
                        'particulars' => [
                            ['particular' => 'Stage Frame', 'unit' => 'pcs', 'default_quantity' => 1, 'comment' => 'Main frame structure'],
                            ['particular' => 'Stage Decking', 'unit' => 'sqm', 'default_quantity' => 12, 'comment' => 'Wooden decking'],
                            ['particular' => 'Stage Skirting', 'unit' => 'm', 'default_quantity' => 14, 'comment' => 'Black fabric skirting'],
                            ['particular' => 'Stage Legs', 'unit' => 'pcs', 'default_quantity' => 4, 'comment' => 'Adjustable legs'],
                        ]
                    ],
                    [
                        'name' => 'Premium Stage 4x6m',
                        'description' => 'Premium stage with 4x6 meter dimensions',
                        'estimated_cost' => 25000.00,
                        'particulars' => [
                            ['particular' => 'Premium Stage Frame', 'unit' => 'pcs', 'default_quantity' => 1, 'comment' => 'Heavy-duty frame'],
                            ['particular' => 'Premium Decking', 'unit' => 'sqm', 'default_quantity' => 24, 'comment' => 'High-quality decking'],
                            ['particular' => 'Premium Skirting', 'unit' => 'm', 'default_quantity' => 20, 'comment' => 'Velvet skirting'],
                            ['particular' => 'Stage Legs', 'unit' => 'pcs', 'default_quantity' => 6, 'comment' => 'Heavy-duty legs'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Booths',
                'description' => 'Exhibition and display booths',
                'templates' => [
                    [
                        'name' => 'Standard Exhibition Booth',
                        'description' => '3x3 meter exhibition booth',
                        'estimated_cost' => 8000.00,
                        'particulars' => [
                            ['particular' => 'Booth Frame', 'unit' => 'pcs', 'default_quantity' => 1, 'comment' => 'Aluminum frame'],
                            ['particular' => 'Booth Panels', 'unit' => 'pcs', 'default_quantity' => 4, 'comment' => 'Fabric panels'],
                            ['particular' => 'Booth Counter', 'unit' => 'pcs', 'default_quantity' => 1, 'comment' => 'Display counter'],
                            ['particular' => 'Lighting Kit', 'unit' => 'pcs', 'default_quantity' => 1, 'comment' => 'LED lighting'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Furniture',
                'description' => 'Event furniture and seating',
                'templates' => [
                    [
                        'name' => 'VIP Seating Set',
                        'description' => 'Premium seating for VIP areas',
                        'estimated_cost' => 12000.00,
                        'particulars' => [
                            ['particular' => 'VIP Chairs', 'unit' => 'pcs', 'default_quantity' => 10, 'comment' => 'Premium chairs'],
                            ['particular' => 'VIP Tables', 'unit' => 'pcs', 'default_quantity' => 5, 'comment' => 'Round tables'],
                            ['particular' => 'Table Cloths', 'unit' => 'pcs', 'default_quantity' => 5, 'comment' => 'Premium fabric'],
                            ['particular' => 'Chair Covers', 'unit' => 'pcs', 'default_quantity' => 10, 'comment' => 'Matching covers'],
                        ]
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = ItemCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'created_by' => $user->id,
            ]);

            foreach ($categoryData['templates'] as $templateData) {
                $template = ItemTemplate::create([
                    'category_id' => $category->id,
                    'name' => $templateData['name'],
                    'description' => $templateData['description'],
                    'estimated_cost' => $templateData['estimated_cost'],
                    'created_by' => $user->id,
                    'is_active' => true,
                ]);

                foreach ($templateData['particulars'] as $particularData) {
                    ItemTemplateParticular::create([
                        'item_template_id' => $template->id,
                        'particular' => $particularData['particular'],
                        'unit' => $particularData['unit'],
                        'default_quantity' => $particularData['default_quantity'],
                        'comment' => $particularData['comment'],
                    ]);
                }
            }
        }

        $this->command->info('Sample item templates created successfully!');
    }
}
