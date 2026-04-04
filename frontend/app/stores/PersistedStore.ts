import { defineStore } from 'pinia';

interface EditionButton {
    id: string
    name: string
    text: string
    sup?: string
    bgClass: string
    active?: boolean
}

export const usePersistedStore = defineStore('persisted', {
    state: () => ({
        editions: <EditionButton[]>[
            {
                id: 'all',
                name: 'all',
                text: '∞',
                bgClass: 'edition-all',
                active: true
            },
            {
                id: 'e1',
                name: '1e',
                text: '1',
                sup: 'st',
                bgClass: 'edition-1',
                active: false
            },
            {
                id: 'e2',
                name: '2e',
                text: '2',
                sup: 'nd',
                bgClass: 'edition-2',
                active: false
            },
            {
                id: 'e3',
                name: '3.5',
                text: '3',
                sup: 'rd',
                bgClass: 'edition-3',
                active: false
            },
            {
                id: 'e4',
                name: '4e',
                text: '4',
                sup: 'th',
                bgClass: 'edition-4',
                active: false
            },
            {
                id: 'e2014',
                name: '5.2014',
                text: '5',
                sup: '2014',
                bgClass: 'edition-5',
                active: false
            },
            {
                id: 'e2024',
                name: '5.2024',
                text: '5',
                sup: '2024',
                bgClass: 'edition-2024',
                active: false
            },
        ],
        reload: 0,
        selectedEdition: '',
        teaserMode: 'grid'
    }),
    persist: true,
    getters: {
        /**
         * Determine if all buttons are active.
         */
        all: (state) => {
            let ret = true;

            state.editions.forEach((item: EditionButton) => {
                if (item.id !== 'all' && !item.active) {
                    ret = false;
                }
            });
            return ret;
        },
        /**
         * Are any editions selected except "all"?
         */
        any: (state) => {
            let ret = false;
            state.editions.forEach((item: EditionButton) => {
                if (item.id !== 'all' && item.active) {
                    ret = true;
                }
            });
            return ret;
        },
        get: (state): EditionButton => {
            return (id: string) => state.editions.find((item: EditionButton) => item.id === id);
        },
        getEditionsQueryString: (state): string => {
            const enabled: string[] = [];

            state.editions.forEach((item: EditionButton) => {
                if (item.active && item.id !== 'all') {
                    enabled.push(item.name);
                }
            });

            return enabled.join(',');
        }
    },
    actions: {

        set(key: string, value: boolean) {
            this.editions.forEach((item: EditionButton) => {
                if (item.id === key) {
                    item.active = value;
                }
            });
        },
        setAll(onOrOff: boolean) {
            this.editions.forEach((item: EditionButton) => {
                item.active = onOrOff;
            });
        },
        toggle(id: string) {
            const currentlyActive = this.get(id)?.active ?? false;

            const editionsClone = this.editions.slice();

            if (id === 'all') {
                editionsClone.forEach((item: EditionButton) => item.active = !currentlyActive);
            } else {
                editionsClone.find((item: EditionButton) => item.id === id).active = !currentlyActive;
            }

            // If all the editions have been selected, toggle the "all" button on or off too.
            let allSelected = true;

            editionsClone.forEach((item: EditionButton) => {
                if (item.id !== 'all' && !item.active) {
                    allSelected = false;
                }
            });

            editionsClone.find((item: EditionButton) => item.id === 'all').active = allSelected;

            this.$patch({editions: editionsClone});
        }
    }
});
