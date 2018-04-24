import TestModule from './modules/test-module';

document.addEventListener('DOMContentLoaded', () => {
	new TestModule('login.js').log();
});
