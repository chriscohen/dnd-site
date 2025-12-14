<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Enums\SourceType;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\CharacterClasses\CharacterClassEdition;
use App\Models\Creatures\CreatureType;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\Feats\Feature;
use App\Models\Feats\FeatureEdition;
use App\Models\Items\ItemType;
use App\Models\Items\ItemTypeEdition;
use App\Models\Languages\Language;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use App\Models\Species\Species;
use App\Models\Species\SpeciesEdition;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEdition4e;
use App\Models\Spells\SpellEditionLevel;
use GraphQL\Type\Definition\PhpEnumType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\LighthouseServiceProvider;
use Nuwave\Lighthouse\OrderBy\OrderByServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(LighthouseServiceProvider::class);

        $typeRegistry = app(TypeRegistry::class);

        $this->app->register(OrderByServiceProvider::class);

        foreach (
            [
                Binding::class,
                GameEdition::class,
                SourceType::class,
            ] as $enum
        ) {
            $typeRegistry->register(new PhpEnumType($enum));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'character_class' => CharacterClass::class,
            'character_class_edition' => CharacterClassEdition::class,
            'creature_type' => CreatureType::class,
            'creature_type_edition' => CreatureTypeEdition::class,
            'feat' => Feature::class,
            'feat_edition' => FeatureEdition::class,
            'item' => ItemType::class,
            'item_edition' => ItemTypeEdition::class,
            'language' => Language::class,
            'source' => Source::class,
            'source_edition' => SourceEdition::class,
            'species' => Species::class,
            'species_edition' => SpeciesEdition::class,
            'spell' => Spell::class,
            'spell_edition' => SpellEdition::class,
            'spell_edition_4e' => SpellEdition4e::class,
            'spell_edition_level' => SpellEditionLevel::class,
        ]);
    }
}
