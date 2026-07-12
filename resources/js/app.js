import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import registerFrontendComponents from './fe';

Alpine.plugin(collapse);
Alpine.plugin(focus);
registerFrontendComponents(Alpine);

window.Alpine = Alpine;

Alpine.start();
