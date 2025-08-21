<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameEdition;
use App\Models\Spell;

class SpellSeeder extends AbstractYmlSeeder
{
    protected string $path = 'spells.json';
    protected string $model = Spell::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $spell = new Spell();
            $spell->id = $datum['id'];
            $spell->slug = $datum['slug'];
            $spell->name = $datum['name'];
            $spell->game_edition = GameEdition::tryFrom($datum['game_edition']);
            $spell->school = $datum['school'];
            $spell->description = $datum['description'] ?? null;
            $spell->higher_level = $datum['higher_level'] ?? null;
            $spell->range_number = $datum['range_number'] ?? null;
            $spell->range_unit = $datum['range_unit'] ?? null;
            $spell->range_is_self = $datum['range_is_self'] ?? false;
            $spell->range_is_touch = $datum['range_is_touch'] ?? false;
        }
    }
}
