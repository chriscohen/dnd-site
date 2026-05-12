/// <reference @dnd5e="vite/client" />

import type { TooltipDirectiveBinding } from 'primevue/tooltip';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        vTooltip: TooltipDirectiveBinding;
    }
}
