new Vue({
    el: '#appCountries',
    vuetify: new Vuetify(),

    data: () => ({
        search: '',
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
            { text: 'Тур', value: 'name_resort', align: 'center' },
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
        }
    },

    methods: {
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

        getColor(popular) {
            if (popular == 1) {
                return 'green';
            } else if (popular == 0 || popular == null){
                return 'red';
            }
        },

        /**
         * поиск
         *
         * @param event
         */
        searchList(event) {
            let search = this.search.match(/(^\D+$)/g);
            if (search != null) {
                this.listCountries = this.desserts.filter( (country) => {
                    if (country.name.toLowerCase().indexOf(event.toLowerCase()) != -1) {
                        return country;
                    }
                })
            } else {
                this.listCountries = [];
            }
        }
    }
})