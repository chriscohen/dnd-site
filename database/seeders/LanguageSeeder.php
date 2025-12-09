<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;

class LanguageSeeder extends AbstractYmlSeeder
{
    protected string $path = 'languages.json';
    protected string $model = Language::class;

    public function run(): void
    {
        $data = $this->getDataFromFile();

        // For each item in the JSON file...
        foreach ($data as $datum) {
            $language = new Language();
            $language->name = $datum['name'];
            $language->slug = self::makeSlug($datum['name']);
            $language->id = $language->slug;
            $language->isExotic = $datum['isExotic'];
            $language->scriptName = $datum['scriptName'] ?? null;
            $language->save();
        }
    }
}
