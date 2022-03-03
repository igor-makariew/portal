new Vue({
    el: '#appCountries',
    vuetify: new Vuetify(),

    data: () => ({
        search: '',
        selectField:[
            {key: 'country_name', value: 'Страна'},
            {key: 'country_id', value: 'Идентификатор'},
            {key: 'resort_name', value: 'Тур'},
            {key: 'rating', value: 'Рейтинг'},
        ],
        field: '',
        page: 1,
        pageCount: 0,
        itemsPerPage: 20,
        maxRows: 50,
        minRow: 1,
        loader: false,
        intervalCountriesRuls: [
            v => !!v || 'Поле не должна быть пустым',
            v => v > 0 || 'Значение не должно быть меньше 1',
            v => v < 51 || 'Значение не должно быть больше 50',
        ],
        headers: [
            {
                text: 'Страна',
                align: 'start',
                sortable: false,
                value: 'country_name',
            },
            { text: 'Идентификатор', value: 'country_id',  align: 'center'},
            { text: 'Популярность страны', value: 'popular', align: 'center' },
            { text: 'Тур', value: 'resort_name', align: 'center' },
            { text: 'Популярность тура', value: 'is_popular', align: 'center' },
            { text: 'Рейтинг', value: 'rating', align: 'center' },
        ],
        desserts: [],
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        listCountries: [],
    }),

    created() {
        this.initialize();
        this.getCountries();
    },

    watch: {
        itemsPerPage: function(newVal) {
            if( newVal > 50) {
                this.itemsPerPage = this.maxRows;
            }

            if (newVal < 1 || isNaN(newVal)) {
                this.itemsPerPage = this.minRow;
            }
        },

        field: function (newVal, oldVal) {
            if (newVal != oldVal) {
                this.search = '';
                this.listCountries = [];
            }
        }
    },

    methods: {
        /**
         * получение стран с из направлениями
         */
        getCountries() {
            this.loader = true;
            axios.post('/admin/countries/get-countries', {})
                .then( (response) => {
                    this.loader = false;
                    this.desserts = response.data;
                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        /**
         * возвращение цвета
         *
         * @param popular
         * @returns {string}
         */
        getColor(popular) {
            if (popular == 1) {
                return 'green';
            } else if (popular == 0 || popular == null){
                return 'red';
            }
        },

        initialize () {
            this.desserts;
        },
        /**
         * поиск
         *
         * @param event
         */
        searchList(event) {
            this.field = this.field == '' ? 'country_name' : this.field;
            let search = null;
            if (this.field == 'country_name' || this.field == 'resort_name') {
                search = this.search.match(/(^\D+$)/g);
            }

            if (this.field == 'country_id') {
                search = this.search.match(/^[0-9]+$/g);
            }

            if (this.field == 'rating') {
                search = this.search.match(/^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$/g);
            }

            if (search != null) {
                this.listCountries = this.desserts.filter( (country) => {
                    if ( country[this.field] != null ) {
                        if (country[this.field].toLowerCase().indexOf(event.toLowerCase()) != -1) {
                            return country;
                        }
                    }
                })
            } else {
                this.listCountries = [];
            }
        }
    }
})