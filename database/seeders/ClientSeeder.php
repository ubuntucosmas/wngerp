<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            [
                'FullName' => 'Acme Corporation',
                'ContactPerson' => 'John Doe',
                'Email' => 'contact@acme.com',
                'Phone' => '123-456-7890',
                'AltContact' => '234-567-8901',
                'Address' => '123 Business St',
                'City' => 'Metropolis',
                'County' => 'Metro County',
                'PostalAddress' => 'PO Box 123',
                'CustomerType' => 'Business',
                'LeadSource' => 'Referral',
                'PreferredContact' => 'Email',
                'Industry' => 'Technology',
                'CreatedBy' => 'admin',
            ],
            [
                'FullName' => 'Jane Smith',
                'ContactPerson' => null,
                'Email' => 'jane.smith@example.com',
                'Phone' => '987-654-3210',
                'AltContact' => null,
                'Address' => '456 Home Ave',
                'City' => 'Gotham',
                'County' => 'Gotham County',
                'PostalAddress' => null,
                'CustomerType' => 'Individual',
                'LeadSource' => 'Social Media',
                'PreferredContact' => 'WhatsApp',
                'Industry' => null,
                'CreatedBy' => 'admin',
            ],
            [
                'FullName' => 'Global Events Ltd',
                'ContactPerson' => 'Sarah Johnson',
                'Email' => 'info@globalevents.com',
                'Phone' => '345-678-9012',
                'AltContact' => null,
                'Address' => '789 Event Rd',
                'City' => 'Eventown',
                'County' => 'Event County',
                'PostalAddress' => 'PO Box 789',
                'CustomerType' => 'Organization',
                'LeadSource' => 'Website',
                'PreferredContact' => 'Phone',
                'Industry' => 'Event Management',
                'CreatedBy' => 'system',
            ],
            [
                'FullName' => 'Green Solutions Inc',
                'ContactPerson' => 'Michael Brown',
                'Email' => 'michael@greensolutions.com',
                'Phone' => '456-789-0123',
                'AltContact' => '567-890-1234',
                'Address' => '101 Green Way',
                'City' => 'Ecotown',
                'County' => 'Eco County',
                'PostalAddress' => null,
                'CustomerType' => 'Business',
                'LeadSource' => 'Advertisement',
                'PreferredContact' => 'Email',
                'Industry' => 'Renewable Energy',
                'CreatedBy' => 'admin',
            ],
            [
                'FullName' => 'Robert Wilson',
                'ContactPerson' => null,
                'Email' => 'robert.wilson@example.com',
                'Phone' => '678-901-2345',
                'AltContact' => null,
                'Address' => '202 Oak St',
                'City' => 'Springfield',
                'County' => 'Spring County',
                'PostalAddress' => null,
                'CustomerType' => 'Individual',
                'LeadSource' => 'Walk-in',
                'PreferredContact' => 'Phone',
                'Industry' => null,
                'CreatedBy' => 'system',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['Email' => $clientData['Email']], // Prevent duplicates based on email
                $clientData
            );
        }
    }
}