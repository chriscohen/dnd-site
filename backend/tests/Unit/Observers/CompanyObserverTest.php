<?php

declare(strict_types=1);

namespace Tests\Unit\Observers;

use App\Models\Company;
use App\Observers\CompanyObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\FeatureTestCase;

final class CompanyObserverTest extends FeatureTestCase
{
    private const string COMPANY_ID = '32f833db-8acb-4bba-ac3b-1e1d1f95d1c6';
    private const string OTHER_ID   = '11111111-1111-1111-1111-111111111111';

    private function makeCompany(array $attributes = []): Company
    {
        $company = new Company();
        $company->id = self::COMPANY_ID;
        $company->slug = $attributes['slug'] ?? 'acme-corp';
        $company->name = $attributes['name'] ?? 'Acme Corp';
        $company->short_name = $attributes['short_name'] ?? null;
        $company->website = $attributes['website'] ?? null;
        $company->product_url = $attributes['product_url'] ?? null;
        return $company;
    }

    /**
     * Mock the data disk, returning $initialJson from get() and capturing put() output.
     * Returns a stdClass so the closure and caller share the same object reference.
     */
    private function mockDisk(string $initialJson): \stdClass
    {
        $captured = new \stdClass();
        $captured->content = null;

        $disk = Mockery::mock();
        $disk->shouldReceive('get')->with('companies.json')->andReturn($initialJson);
        $disk->shouldReceive('put')->andReturnUsing(function (string $path, string $content) use ($captured): void {
            $captured->content = $content;
        });

        Storage::shouldReceive('disk')->with('data')->andReturn($disk);

        return $captured;
    }

    private function decodeCapture(\stdClass $captured): array
    {
        return json_decode($captured->content, true);
    }

    public function testUpdatedWritesChangedFieldsToJson(): void
    {
        $json = json_encode([['id' => self::COMPANY_ID, 'slug' => 'acme-corp', 'name' => 'Acme Corp']]);
        $captured = $this->mockDisk($json);

        (new CompanyObserver())->updated($this->makeCompany(['name' => 'New Name', 'slug' => 'new-slug']));

        $entry = $this->decodeCapture($captured)[0];
        $this->assertEquals('New Name', $entry['name']);
        $this->assertEquals('new-slug', $entry['slug']);
    }

    public function testUpdatedPreservesLogoField(): void
    {
        $json = json_encode([['id' => self::COMPANY_ID, 'slug' => 'acme-corp', 'name' => 'Acme Corp', 'logo' => 'acme.webp']]);
        $captured = $this->mockDisk($json);

        (new CompanyObserver())->updated($this->makeCompany());

        $this->assertEquals('acme.webp', $this->decodeCapture($captured)[0]['logo']);
    }

    public function testUpdatedDoesNotModifyOtherEntries(): void
    {
        $otherEntry = ['id' => self::OTHER_ID, 'slug' => 'other', 'name' => 'Other Company'];
        $json = json_encode([$otherEntry, ['id' => self::COMPANY_ID, 'slug' => 'acme-corp', 'name' => 'Acme Corp']]);
        $captured = $this->mockDisk($json);

        (new CompanyObserver())->updated($this->makeCompany(['name' => 'Updated']));

        $result = $this->decodeCapture($captured);
        $unchanged = collect($result)->firstWhere('id', self::OTHER_ID);
        $this->assertEquals($otherEntry, $unchanged);
    }

    public function testUpdatedLogsErrorAndSkipsPutWhenIdNotInJson(): void
    {
        Log::spy();
        $json = json_encode([['id' => self::OTHER_ID, 'slug' => 'other', 'name' => 'Other Company']]);

        $disk = Mockery::mock();
        $disk->shouldReceive('get')->with('companies.json')->andReturn($json);
        $disk->shouldNotReceive('put');
        Storage::shouldReceive('disk')->with('data')->andReturn($disk);

        (new CompanyObserver())->updated($this->makeCompany());

        Log::shouldHaveReceived('error')
            ->once()
            ->with('Company not found in companies.json; JSON not updated.', ['id' => self::COMPANY_ID]);
    }

    #[DataProvider('provideOptionalFields')]
    public function testUpdatedOptionalFieldHandling(
        ?string $shortName,
        ?string $website,
        ?string $productUrl,
        array $expectedPresent,
        array $expectedAbsent,
    ): void {
        $json = json_encode([['id' => self::COMPANY_ID, 'slug' => 'acme-corp', 'name' => 'Acme Corp']]);
        $captured = $this->mockDisk($json);

        (new CompanyObserver())->updated($this->makeCompany([
            'short_name'  => $shortName,
            'website'     => $website,
            'product_url' => $productUrl,
        ]));

        $entry = $this->decodeCapture($captured)[0];

        foreach ($expectedPresent as $key => $value) {
            $this->assertArrayHasKey($key, $entry);
            $this->assertEquals($value, $entry[$key]);
        }

        foreach ($expectedAbsent as $key) {
            $this->assertArrayNotHasKey($key, $entry);
        }
    }

    public static function provideOptionalFields(): array
    {
        return [
            'all optional fields present' => [
                'shortName'       => 'Acme',
                'website'         => 'https://acme.com',
                'productUrl'      => 'product/{{id}}',
                'expectedPresent' => ['short_name' => 'Acme', 'website' => 'https://acme.com', 'product_url' => 'product/{{id}}'],
                'expectedAbsent'  => [],
            ],
            'all optional fields null' => [
                'shortName'       => null,
                'website'         => null,
                'productUrl'      => null,
                'expectedPresent' => [],
                'expectedAbsent'  => ['short_name', 'website', 'product_url'],
            ],
            'website only' => [
                'shortName'       => null,
                'website'         => 'https://acme.com',
                'productUrl'      => null,
                'expectedPresent' => ['website' => 'https://acme.com'],
                'expectedAbsent'  => ['short_name', 'product_url'],
            ],
            'short name and product url only' => [
                'shortName'       => 'Acme',
                'website'         => null,
                'productUrl'      => 'product/{{id}}',
                'expectedPresent' => ['short_name' => 'Acme', 'product_url' => 'product/{{id}}'],
                'expectedAbsent'  => ['website'],
            ],
        ];
    }
}
