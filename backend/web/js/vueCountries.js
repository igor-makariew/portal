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
        loaderUpdate: false,
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
            { text: 'Действие', value: 'actions', sortable: false },
        ],
        desserts: [],
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        listCountries: [],
        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {
            country_name: '',
            country_id: null,
            popular: null,
            resort_name: '',
            is_popular: null,
            rating: 0,
        },
        defaultItem: {
            country_name: '',
            country_id: null,
            popular: null,
            resort_name: '',
            is_popular: null,
            rating: 0,
        },
        popularCountry: [
            {key: 0, value: 'НЕТ'},
            {key: 1, value: 'ДА'}
        ],
        popularTour: [
            {key: null, value: 'НЕТ'},
            {key: 1, value: 'ДА'}
        ],
        crtSelectedItem: '',
        item: [],
        dialogAlert: false,
    }),

    created() {
        this.getCountries();
    },

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Создание новой записи' : 'Редактирование записи'
        },
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
        },

        dialog (val) {
            val || this.close()
        },

        // desserts: {
        //     handler(val) {
        //        console.log(val)
        //     },
        //     deep: true
        // },
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
        },

        /**
         * редактирование строки
         *
         * @param item
         */
        editItem (item) {
            this.crtSelectedItem = item.resorts_id;
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
            this.item = item;
        },

        deleteItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        /**
         * хакрытие окна новй записи/редактирования
         */
        close () {
            this.dialog = false;
            this.editedIndex = -1
            // this.$nextTick(() => {
            //     this.editedItem = Object.assign({}, this.defaultItem)
            //     this.editedIndex = -1
            // })
        },

        /**
         * хакрытие окна удаления
         */
        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        /**
         * выбор пути отправки данных
         */
        selectRequest() {
            if (this.editedIndex > -1) {
                this.save('update-row', this.item);
            } else {
                this.save('create-row', this.item = null);
            }
        },

        /**
         * сохранение записи
         */
        save (path, item) {
            this.loaderUpdate = true;
            axios.post(`/admin/countries/${path}`, {
                'item': this.editedItem,
            }).then( (response) => {
                this.loaderUpdate = false;
                if (response.data.response) {
                    this.editedIndex = this.desserts.indexOf(item)
                    if (this.editedIndex > -1) {
                        Object.assign(this.desserts[this.editedIndex], this.editedItem);
                        Object.keys(this.desserts).forEach( (key) => {
                            if (this.desserts[key]['country_id'] == this.editedItem['country_id']) {
                                this.desserts[key]['popular'] =  this.editedItem['popular']
                            }
                        })
                    } else {
                        this.getCountries();
                    }
                } else {
                    this.dialogAlert = true;
                    this.dialogAlertTitle = 'Произошла ошибка обновления. Попробыуйте позже!';
                }
            }).catch( (error) => {
                console.log(error.message);
            })
            // this.desserts.push(this.editedItem)
            this.close()
        },
    }
})