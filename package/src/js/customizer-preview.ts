import { announceEnqueued } from './modules/utils';

(function() {

	const { wp } = window;

	announceEnqueued('customizer-preview.js');

	function updateModules(container: HTMLElement) {
		// Update all modules in the updated container here.
	}

	document.addEventListener('DOMContentLoaded', () => {

		if (wp?.customize?.selectiveRefresh) {

			// Update customizer partials.
			wp.customize.selectiveRefresh.bind('partial-content-rendered', ({ container }) => {
				if (container?.[0] instanceof HTMLElement) {
					updateModules(container[0]);
				}
			});
			
			// Update customizer widgets.
			wp.customize.selectiveRefresh.bind('sidebar-updated', partial => {
				const container = document.getElementById(partial.sidebarId);
				if (container?.[0] instanceof HTMLElement) {
					updateModules(container[0]);
				}
			});

		}
	});

})();
