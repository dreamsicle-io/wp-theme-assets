import TestModule from './modules/test-module';

document.addEventListener('DOMContentLoaded', () => {
	new TestModule('site.js').log();
});
