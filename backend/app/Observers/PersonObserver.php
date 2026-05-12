<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\People\Person;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonObserver
{
    public function updated(Person $person): void
    {
        $contents = Storage::disk('data')->get('people/people.json');
        $entries = json_decode($contents, true);

        $id = (string) $person->id;
        $index = array_search($id, array_column($entries, 'id'));

        if ($index === false) {
            Log::error('Person not found in people.json; JSON not updated.', ['id' => $id]);
            return;
        }

        $existing = $entries[$index];

        $entry = [
            'id'   => $id,
            'slug' => $person->slug,
            'firstName' => $person->name,
            'lastName' => $person->lastName,
        ];

        // logo is not editable via the API — preserve whatever was in the JSON
        if (isset($existing['logo'])) {
            $entry['logo'] = $existing['logo'];
        }

        if (!empty($person->initials)) {
            $entry['initials'] = $person->initials;
        }

        if (!empty($person->middle_names)) {
            $entry['middleNames'] = $person->middle_names;
        }

        if (!empty($person->artstation)) {
            $entry['artstation'] = $person->artstation;
        }

        if (!empty($person->instagram)) {
            $entry['instagram'] = $person->instagram;
        }

        if (!empty($person->twitter)) {
            $entry['twitter'] = $person->twitter;
        }

        if (!empty($person->youtube)) {
            $entry['youtube'] = $person->youtube;
        }

        $entries[$index] = $entry;

        Storage::disk('data')->put(
            'people.json',
            json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n"
        );
    }
}
