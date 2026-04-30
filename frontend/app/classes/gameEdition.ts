export type GameEdition = {
    name: string,
    shortName: string
    className: string
}

export function getGameEditions(): GameEdition[] {
    return [
        {name: 'Original Edition', shortName: '0e', className: 'edition-0'},
        {name: '1st Edition', shortName: '1e', className: 'edition-1'},
        {name: '2nd Edition', shortName: '2e', className: 'edition-2'},
        {name: '3rd Edition', shortName: '3e', className: 'edition-3'},
        {name: '3.5 Edition', shortName: '3.5', className: 'edition-3'},
        {name: '4th Edition', shortName: '4e', className: 'edition-4'},
        {name: '5th Edition', shortName: '5e', className: 'edition-5'},
        {name: '5.5 Edition', shortName: '5.5', className: 'edition-2024'},
    ];
}

export function getGameEdition(key: string): GameEdition | undefined {
    return getGameEditions().find((gameEdition: GameEdition) => {
        return gameEdition.shortName === key || gameEdition.name === key || gameEdition.className === key;
    });
}
