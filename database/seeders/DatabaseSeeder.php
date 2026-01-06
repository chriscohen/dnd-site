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
            UserSeeder::class,

            PersonSeeder::class,
            CategorySeeder::class,
            CompanySeeder::class,
            DeitySeeder::class,
            SkillSeeder::class,
            StatusConditionSeeder::class,
            MagicSchoolSeeder::class,
            MagicDomainSeeder::class,
            CreatureSubtypeSeeder::class,
            CreatureOriginSeeder::class,
            CreatureMainTypeSeeder::class,

            AttackTypeSeeder::class,
            CampaignSettingSeeder::class,
            SourceSeeder::class,
            LanguageSeeder::class,
            CreatureTypeSeeder::class,

            FeatureSeeder::class,
            CharacterClassSeeder::class,
            ItemSeeder::class,
            SpellSeeder::class,
            Spell5eToolsSeeder::class,
        ]);
    }
}
