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
            PersonSeeder::class,
            LanguageSeeder::class,
            CategorySeeder::class,
            CompanySeeder::class,
            DeitySeeder::class,
            SpeciesSeeder::class,
            SkillSeeder::class,
            StatusConditionSeeder::class,
            MagicSchoolSeeder::class,
            MagicDomainSeeder::class,
            CreatureMajorTypeSeeder::class,

            AttackTypeSeeder::class,
            CampaignSettingSeeder::class,
            SourceSeeder::class,

            FeatSeeder::class,
            CharacterClassSeeder::class,
            ItemSeeder::class,
            SpellSeeder::class,
        ]);
    }
}
