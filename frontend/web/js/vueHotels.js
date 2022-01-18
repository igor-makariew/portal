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
        //dateStart: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        dateStart: new Date().getFullYear() + '-' + (new Date().getMonth() + 1).toString().padStart(2, "0") + '-' + new Date().getDate(),
        dateEnd:  new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + (new Date().getDate() + 1),
        // end calendar
        hotels: [],
        show: false,
        visiblyHotels: false,
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

        //start preloder
        interval: {},
        value: 0,
        //end preloder
        page: 1,
        countPage: '',
        rowPerPage: 6,
    }),

    mounted () {

    },

    watch: {
        page: function(newVal) {
            this.page = newVal;
            this.getHotels();
        }
    },

    methods: {
        validate() {
            this.$refs.formValid.validate();
        },

        /**
         * проверка корректности заполныямых дат
         */
        validateDate() {
            const start = new Date(this.dateStart);
            const end = new Date(this.dateEnd);
            const current = new Date(this.currentDate);

            if (start < current) {
                this.dateStart = current.getFullYear() + '-' + (current.getMonth() + 1).toString().padStart(2, "0") + '-' + current.getDate();
            }

            if (end <= start ) {
                this.dateStart = start.getFullYear() + '-' + (start.getMonth() + 1).toString().padStart(2, "0") + '-' + start.getDate();
                start.setDate(start.getDate() + 1);
                this.dateEnd = start.getFullYear() + '-' + (start.getMonth() + 1).toString().padStart(2, "0") + '-' + start.getDate();
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
                'page': this.page,
                'countPage': this.countPage,
                'rowPerPage' : this.rowPerPage,
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
                this.dialog = true;
                if (response.data.hotels.length > 0) {
                    this.hotels = response.data.hotels;
                    this.countPage = response.data.pagination.countPage
                    this.visiblyHotels = true;
                    this.showMessage = false;
                } else {
                    this.visiblyHotels = false;
                    this.showMessage = true;
                    this.message = 'По вашему запросу данных не найдено!!!'
                }
                this.loader = false;
            });
        },

        /**
         * отмена событий доделать данное
         */
        stopClick(event) {
            console.log(event)
        },

        /**
         * addToBasket
         */
        addToBasket(id, userId) {
            const data = {
                'hotelId': id,
                'userId': userId
            }

             axios.post('/basket/add-basket', {
                 data: data
             }).then( (response) => {
                 const countBasket = document.getElementById('countBasket');
                 countBasket.innerText = Object.keys(response.data.basket[userId]).length - 1;
                 //this.$emit('toggle', response); // название события
             }).catch((error) => {
                 console.log(error.message);
             })
        },

    }
})
