
class TestModule {

	constructor() {
		this.message = `${this.constructor.name} was bundled properly.`;
	}

	log() {
		// eslint-disable-next-line no-console
		console.log(this.message); 
	}
}

export default TestModule;
