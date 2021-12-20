new Vue({
    el: '#appHotels',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        query: 'moscow',
        lang: 'ru',
        langs: ['ru', 'en', 'th', 'de', 'es', 'fr', 'it', 'pl'],
        lookFor: 'both',
        lookFors: ['both', 'city', 'hotel'],
        limit: 10,
        currency: 'rub',
        currencies: ['rub', 'usd', 'eur'],
        // start calendar
        currentDate: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate(),
        dateStart: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        dateEnd:  new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + (new Date().getDate() + 1),
        // end calendar
        hotels: [],
        show: false,
        listHotels: false,
        showMessage: false,
        fieldCity: [
            v => !!v || 'Город не введен',
            v => /^([A-Z,a-z]*)$/g.test(v) || 'Название города введено не корректно, используйте английский язык.'
        ],
        fieldLimit: [
            v => !!v || 'Выберите количество результатов.',
            v => v > 0 || 'Количество должно быть больше 0',
        ],
        loader: false,
        crtSelectedItem: '',
    }),

    methods: {
        validate() {
            this.$refs.formValid.validate();
        },

        /**
         * проверка корректности заполныямых дат
         */
        validateDate(dateStart, dateEnd) {
            if (this.dateStart < this.currentDate) {
                this.dateStart = this.currentDate;
            }

            if (this.dateEnd <= this.dateStart) {
                let result = new Date(this.dateStart);
                result.setDate(result.getDate() + 1);
                this.dateEnd = result.getFullYear() + '-' + (result.getMonth() + 1) + '-' + result.getDate();
            }
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
                'currency': this.currency,
                'dateStart': this.dateStart,
                'dateEnd': this.dateEnd,
            };


            // let options = {
            //     method: 'GET',
            //     url: 'https://hotels4.p.rapidapi.com/locations/v2/search',
            //     params: {query: 'new york', locale: 'en_US', currency: 'USD'},
            //     headers: {
            //         'x-rapidapi-host': 'hotels4.p.rapidapi.com',
            //         'x-rapidapi-key': 'SIGN-UP-FOR-KEY'
            //     }
            // };
            this.loader = true;
            axios.post('/site/get', {
                'filter': filter
            }).then((response) => {
                console.log(response.data);
                if (response.data.length > 0) {
                    this.hotels = response.data;
                    this.listHotels = true;
                    this.showMessage = false;
                } else {
                    this.listHotels = false;
                    this.showMessage = true;
                    this.message = 'По вашему запросу данных не найдено!!!'
                }
                this.loader = false;
            });
        },

        // testing
        getTesting() {
            console.log('testing');
        }
    }
})