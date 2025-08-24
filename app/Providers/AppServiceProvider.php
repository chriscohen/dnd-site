<?php

namespace App\Providers;

use App\Enums\Binding;
use App\Enums\GameEdition;
use App\Enums\SourceType;
use App\Models\Category;
use App\Models\Items\Item;
use GraphQL\Type\Definition\PhpEnumType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\OrderBy\OrderByServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use Nuwave\Lighthouse\LighthouseServiceProvider;

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

        foreach ([
            Binding::class,
            GameEdition::class,
            SourceType::class,
        ] as $enum) {
            $typeRegistry->register(new PhpEnumType($enum));
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'item' => Item::class,
        ]);
    }
}
