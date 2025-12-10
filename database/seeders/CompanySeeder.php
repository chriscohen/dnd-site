<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Media;

class CompanySeeder extends AbstractYmlSeeder
{
    protected string $path = 'companies.json';
    protected string $model = Company::class;
    protected array $schema = [
        'id',
        'slug',
        'name',
        'short_name',
        'website',
    ];

    public function run(): void
    {
        $data = $this->getDataFromFile();

        foreach ($data as $datum) {
            $company = new Company();
            $company->id = $datum['id'];
            $company->slug = $datum['slug'];
            $company->name = $datum['name'];
            $company->product_url = $datum['product_url'] ?? null;
            $company->short_name = $datum['short_name'] ?? null;
            $company->website = $datum['website'] ?? null;

            if (!empty($datum['logo'])) {
                $media = Media::createFromExisting([
                    'filename' => '/companies/' . $datum['logo'],
                    'disk' => 's3',
                    'collection_name' => 'logos'
                ]);
                $company->logo()->associate($media);
            }

            $company->save();
        }
    }
}
