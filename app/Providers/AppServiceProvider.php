<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Enums\SourceType;
use App\Models\CharacterClasses\CharacterClass;
use App\Models\CharacterClasses\CharacterClassEdition;
use App\Models\Creatures\CreatureType;
use App\Models\Creatures\CreatureTypeEdition;
use App\Models\Feats\Feat;
use App\Models\Feats\FeatEdition;
use App\Models\Items\Item;
use App\Models\Items\ItemEdition;
use App\Models\Sources\Source;
use App\Models\Sources\SourceEdition;
use App\Models\Spells\Spell;
use App\Models\Spells\SpellEdition;
use App\Models\Spells\SpellEdition4e;
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
            'feat' => Feat::class,
            'feat_edition' => FeatEdition::class,
            'item' => Item::class,
            'item_edition' => ItemEdition::class,
            'source' => Source::class,
            'source_edition' => SourceEdition::class,
            'spell' => Spell::class,
            'spell_edition' => SpellEdition::class,
            'spell_edition_4e' => SpellEdition4e::class,
        ]);
    }
}
