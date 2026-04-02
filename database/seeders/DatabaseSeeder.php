<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OnboardingItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin account ─────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@mmedigital.ca'],
            [
                'name'     => 'MME Admin',
                'password' => bcrypt('changeme123!'),
                'is_admin' => true,
                'is_active'=> true,
            ]
        );

        // ── Default onboarding checklist items ────────────────────────────
        $items = [
            ['label' => 'Business logo',               'description' => 'Upload your logo in PNG or SVG format (transparent background preferred).', 'requires_file' => true,  'sort_order' => 1],
            ['label' => 'Brand colours',               'description' => 'Share your primary and secondary colours (hex codes or describe them).', 'requires_file' => false, 'sort_order' => 2],
            ['label' => 'Business photos',             'description' => 'Upload any photos of your team, location, or work you'd like on the site.', 'requires_file' => true,  'sort_order' => 3],
            ['label' => 'Website copy / text',         'description' => 'Provide the text for your homepage and key pages, or let us know if you need help writing it.', 'requires_file' => false, 'sort_order' => 4],
            ['label' => 'Social media links',          'description' => 'Share links to your Facebook, Instagram, LinkedIn, or other profiles.', 'requires_file' => false, 'sort_order' => 5],
            ['label' => 'Google account access',       'description' => 'Invite creative@mmedigital.ca to your Google Business Profile and Google Analytics (if you have them).', 'requires_file' => false, 'sort_order' => 6],
            ['label' => 'Existing domain / hosting',   'description' => 'Let us know your domain registrar and any existing hosting provider so we can coordinate access.', 'requires_file' => false, 'sort_order' => 7],
            ['label' => 'Competitor websites',         'description' => 'Share 2–3 competitor websites or sites you like the look of for design inspiration.', 'requires_file' => false, 'sort_order' => 8],
        ];

        foreach ($items as $item) {
            OnboardingItem::firstOrCreate(['label' => $item['label']], $item);
        }
    }
}
