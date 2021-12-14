new Vue({
    el: '#appHotels',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        query: 'moscow',
        lang: 'ru',
        defaultLooFor: {name:'both'},
        lookFor: [
            {name: 'both'},
            {name: 'city'},
            {name: 'hotel'}
            ],
        limit: 10,
        hotels: [],
        show: false,
        listHotels: false,
        fieldCity: [
            v => !!v || 'Город не введен',
            v => /^([A-Z,a-z]*)$/g.test(v) || 'Название города введено не корректно, используйте английский язык.'
        ],
        fieldLang: [
            v => !!v || 'Язык не выбран',
            v => /^([A-Z,a-z]{2,5})$/g.test(v) || 'Язык выбран не корректно, используйте английский язык.'
        ],
        fieldLimit: [
            v => !!v || 'Выберите количество результатов.',
            v => v > 0 || 'Количество должно быть больше 0',
        ]

    }),

    methods: {
        validate () {
            this.$refs.formValid.validate();
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
                this.listHotels = true;
                console.log(response.data.results.hotels);
            });
        },

        // testing
        getTesting() {
            console.log('testing');
        }
    }
})