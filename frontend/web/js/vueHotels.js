new Vue({
    el: '#appHotels',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        query: 'moscow',
        lang: 'ru',
        lookFor: 'both',
        limit: 10,
        hotels: [],
        show: false,
    }),

    methods: {
        validate () {
            this.$refs.form.validate();
        },

        /**
         * получение списка отелей
         */
        getHotels() {
            const filter = {
                'query': this.query,
                'lang': this.lang,
                'lookFor': this.lookFor,
                'limit': this.limit,
            };

            axios.post('/site/get', {
                'filter': filter
            }).then( (response) => {
                this.hotels = response.data.results.hotels;
                console.log(response.data.results.hotels);
            });
        },

        // testing
        getTesting() {
            console.log('testing');
        }
    }
})