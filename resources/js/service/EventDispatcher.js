import Vue from 'vue'

export default class EventDispatcher{
	constructor(){
		this.vue = new Vue()
	}
	fire(event, data = null) {
		this.vue.$emit(event, data)
	}
	listen(event, callback) {
		this.vue.$on(event, callback)
	}
	// Destroy an event or all events
	destroy(event = null, callback = null)
	{
		if (event == null && callback == null) {
			this.vue.$off()
			return
		} else if (event == null) {
			this.vue.$off([event])
			return
		}
		this.vue.$off([event, callback])
	}
}
