export default {
	data() {
		return {
			functionName: 'fetch'
		}
	},
	created() {
		this.filters = {...this.filters, ...{
			total: 0,
			size: 25,
			page: 1
		}}
	},
	methods: {
		handleSize(size) {
			this.filters.size = size
			this.filters.page = 1

			this.callFetchFunction()
		},
		handlePage(page) {

			this.filters.page = page

			this.callFetchFunction()

		},
        handleTotal(total) {

            this.filters.total = total

            this.callFetchFunction();
        },
		callFetchFunction() {
			if (this.functionName) {
				setTimeout(_ => {
					// this.[this.functionName]()
                    this+'.'+[this.functionName]+"()"
				}, 100)
			}
		}
	}
}
