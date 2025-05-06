import { announceEnqueued } from './modules/ts/utils';

(function() {

	const { wp } = window;

	announceEnqueued('customizer-preview.js');

	function updateModules(_container: HTMLElement) {
		// Update all modules in the updated container here.
	}

	document.addEventListener('DOMContentLoaded', () => {

		if (wp?.customize?.selectiveRefresh) {

			// Update customizer partials.
			wp.customize.selectiveRefresh.bind('partial-content-rendered', (partial: any) => {
				if (partial?.container?.[0] instanceof HTMLElement) {
					updateModules(partial.container[0]);
				}
			});
			
			// Update customizer widgets.
			wp.customize.selectiveRefresh.bind('sidebar-updated', (partial: any) => {
				const container = document.getElementById(partial.sidebarId);
				if (container instanceof HTMLElement) {
					updateModules(container);
				}
			});

		}
	});

})();
