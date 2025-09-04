<?php

declare(strict_types=1);

namespace App\Models\Spells;

use App\Models\AbstractModel;
use App\Models\CharacterClass;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $id
 *
 * @property CharacterClass $characterClass
 * @property Uuid $character_class_id
 * @property int $level
 * @property SpellEdition $spellEdition
 * @property Uuid $spell_edition_id
 */
class SpellEditionCharacterClassLevel extends AbstractModel
{
    use HasUuids;

    public $table = 'spell_edition_cc_levels';
    public $timestamps = false;

    public function characterClass(): BelongsTo
    {
        return $this->belongsTo(CharacterClass::class, 'character_class_id');
    }

    public function spellEdition(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'spell_edition_id');
    }
}
