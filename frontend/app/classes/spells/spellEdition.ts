import {type Area, type AreaApiResponse, createArea} from "~/classes/units/area";
import {type Range, createRange, type RangeApiResponse} from "~/classes/units/range";
import {createReference, type Reference, type ReferenceApiResponse} from "~/classes/reference";
import {
    createSpellCharacterClassLevel,
    type SpellCharacterClassLevel,
    type SpellCharacterClassLevelApiResponse
} from "~/classes/spells/SpellCharacterClassLevel";
import {createMagicSchool, type MagicSchool, type MagicSchoolApiResponse} from "~/classes/magic/magicSchool";
import {createSpellLevel, type SpellLevel, type SpellLevelApiResponse} from "~/classes/spells/spellLevel";
import {createSavingThrow, type SavingThrow} from "~/classes/savingThrow";

export interface SpellEditionApiResponse {
    id: string
    area?: AreaApiResponse
    castingTime: string
    classLevels: SpellCharacterClassLevelApiResponse[]
    description: string
    domains: string[]
    gameEdition: string
    hasSpellResistance?: boolean
    higherLevel: string
    isDefault: boolean
    levels: SpellLevelApiResponse[]
    lowestLevel: number
    range: RangeApiResponse
    references: ReferenceApiResponse[]
    savingThrow?: SavingThrow
    school?: MagicSchoolApiResponse
    spellComponents?: string
    spellId: string
}

export type SpellEditionState = {
    id?: string
    area?: Area
    castingTime?: string
    classLevels?: SpellCharacterClassLevel[]
    description?: string
    domains?: string[]
    gameEdition?: string
    hasSpellResistance?: boolean
    higherLevel?: string
    isDefault?: boolean
    levels?: SpellLevel[]
    lowestLevel?: number
    range?: Range
    references?: Reference[]
    savingThrow?: SavingThrow
    school?: MagicSchool
    spellComponents?: string
    spellId?: string
}

export const createSpellEdition = (data?: SpellEditionApiResponse) => {
    const state: SpellEditionState = {
        id: data?.id,
        area: createArea(data?.area),
        castingTime: data?.castingTime,
        classLevels: data?.classLevels?.map(createSpellCharacterClassLevel),
        description: data?.description,
        domains: data?.domains,
        gameEdition: data?.gameEdition,
        hasSpellResistance: data?.hasSpellResistance,
        higherLevel: data?.higherLevel,
        isDefault: data?.isDefault,
        levels: data?.levels?.map(createSpellLevel),
        lowestLevel: data?.lowestLevel,
        range: createRange(data?.range),
        references: data?.references?.map(createReference),
        savingThrow: createSavingThrow(data?.savingThrow),
        school: createMagicSchool(data?.school),
        spellComponents: data?.spellComponents,
        spellId: data?.spellId
    }

    function hasSavingThrow(): boolean {
        return !!state.savingThrow;
    }

    return {
        ...state,

        hasSavingThrow
    }
}

export type SpellEdition = ReturnType<typeof createSpellEdition>;
