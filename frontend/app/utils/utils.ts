

export const DATA_TYPES: Record<string, {
    name: string,
    plural: string,
    path: string
}> = {
    campaignSetting: {
        name: 'Campaign Setting',
        plural: 'Campaign Settings',
        path: '/campaign-settings/'
    },
    company: {
        name: 'Company',
        plural: 'Companies',
        path: '/companies/'
    },
    creaturetype: {
        name: 'Creature Type',
        plural: 'Creature Types',
        path: '/creature-types/'
    },
    person: {
        name: 'Person',
        plural: 'People',
        path: '/people/'
    },
    source: {
        name: 'Sourcebook',
        plural: 'Sourcebooks',
        path: '/sources/'
    }
};

export function ucFirst(input: string): string {
    return input.charAt(0).toUpperCase() + input.slice(1);
}

export function toOrdinal(input: string): string {
    switch (input) {
        case '1':
            return '1st'
        case '2':
            return '2nd'
        case '3':
            return '3rd';
        default:
            return input + 'th'
    }
}

export function editionToClass(input: string): string {
    switch (input) {
        case '0e':
            return 'edition-0';
        case '1e':
            return 'edition-1';
        case '2e':
            return 'edition-2';
        case '3e':
        case '3.5':
            return 'edition-3';
        case '4e':
            return 'edition-4';
        case '5e':
            return 'edition-5';
        case '5.5':
            return 'edition-2024';
        default:
            return '1e';
    }
}

export function formatEdition(input: string) {
    switch (input) {
        case '0e':
            return 'Original Edition';
        case '1e':
            return '1st Edition';
        case '2e':
            return '2nd Edition';
        case '3e':
            return '3rd Edition';
        case '3.5':
            return '3.5 Edition';
        case '4e':
            return '4th Edition';
        case '5e':
            return '5th Edition (2014)';
        case '5.5':
            return '5th Edition (2024)';
        default:
            return 'unknown edition';
    }
}

export function formatEditionShort(input: string, sup: boolean = false): string {
    switch (input) {
        case '0e':
            return sup ? '0<sup>th</sup>' : '0th';
        case '1e':
            return sup ? '1<sup>st</sup>' : '1st';
        case '2e':
            return sup ? '2<sup>nd</sup>' : '2nd';
        case '3e':
            return sup ? '3<sup>rd</sup>' : '3rd';
        case '3.5':
            return '3.5';
        case '4e':
            return sup ? '4<sup>th</sup>' : '4th';
        case '5e':
            return sup ? '5<sup>th</sup>' : '5th';
        case '5.5':
            return '5.5';
    }

    return '';
}

export function formatEditionNumber(input: string): string {
    switch (input) {
        case '0e':
            return '0';
        case '1e':
            return '1';
        case '2e':
            return '2';
        case '3e':
            return '3';
        case '3.5':
            return '3.5';
        case '4e':
            return '4';
        case '5e':
            return '5';
        case '5.5':
            return '5.5';
    }

    return '';
}

export function formatIsbn(input: string): string {
    if (input.length == 10) {
        return [
            input.substring(0, 1),
            input.substring(1, 5),
            input.substring(5, 9),
            input.substring(9)
        ].join('-');
    } else {
        return [
            input.substring(0, 3),
            input.substring(3, 4),
            input.substring(4, 6),
            input.substring(6, 12),
            input.substring(12, 13)
        ].join('-');
    }
}

export function formatPages(from?: number, to?: number): string {
    return 'pp. ' + from + (to ? '—' + to : '');
}

export function orList(items: string[]): string {
    if (items.length === 1) {
        return items?.[0] || '';
    }
    const last = items.pop();
    const penultimate = items.pop();
    const orList = last + ', or ' + penultimate;

    if (items.length === 0) {
        return orList;
    }

    return items.join(', ') + orList;
}
