<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CompanySeeder::class,
            SkillSeeder::class,
            CharacterClassSeeder::class,
            MagicSchoolSeeder::class,

            AttackTypeSeeder::class,
            CampaignSettingSeeder::class,
            SourceSeeder::class,
            SpellComponentSeeder::class,

            ItemSeeder::class,
            SpellSeeder::class,
        ]);
    }
}
