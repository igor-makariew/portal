new Vue({
    el: '#appHotels',
    vuetify: new Vuetify(),

    data: () => ({
        loaderCountry: false,
        itemsCountry: [],
        selectedCountries: [],
        // start table resorts
        loaderResorts: false,
        page: 1,
        pageCount: 0,
        itemsPerPage: 30,
        headers: [
            {
                text: 'Идентификатор',
                align: 'start',
                sortable: false,
                value: 'resorts_id',
            },
            { text: 'Название', value: 'name' },
            { text: 'Поулярность', value: 'is_popular', sortable: true },
            { text: 'Рейтинг', value: 'rating' },
            { text: 'Действие', value: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            resorts_id: '',
            name: null,
            is_popular: '',
            rating: 0,
        },
        crtSelectedItem: '',
        loaderUpdate: false,
        // start dialog
        dialog: false,
        dialogAlert: false,
        dialogAlertTitle: '',
        // end dialog
        popularResort: [
            {key: null, value: 'НЕТ'},
            {key: 1, value: 'ДА'}
        ],
        item: [],
        crtSelectedDelItem: '',
        loaderDelete: false,
        dialogDelete: false,
        itemDel: [],
        allResorts: false,

        //  end table resorts
    }),

    created() {
        this.getListCountries();
    },

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Создание новой записи' : 'Редактирование записи'
        },
        likesAllCountries () {
            return this.selectedCountries.length === this.itemsCountry.length
        },
        likesSomeCountries () {
            return this.selectedCountries.length > 0 && !this.likesAllCountries
        },
        icon () {
            if (this.likesAllCountries) return 'mdi-close-box'
            if (this.likesSomeCountries) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
    },

    watch: {
        dialog (val) {
            val || this.close()
        },
    },

    methods: {
        /**
         * полуение списка стран с их идентификаторами
         */
        getListCountries() {
            this.loaderCountry = true;
            axios.post('/admin/resorts/get-country', {})
                .then( (response) => {
                    this.loaderCountry = false;
                    response.data.forEach( (value) => {
                    this.itemsCountry.push({'key': value.country_id, 'value': value.name});
                })
            }).catch( (error) => {
                this.loaderCountry = false;
                console.log(error.message)
            })
        },

        /**
         * выбрать (отменить) все страны
         */
        toggle () {
            this.$nextTick(() => {
                if (this.likesAllCountries) {
                    this.selectedCountries = [];
                } else {
                    this.selectedCountries = this.itemsCountry.map( (item) => {
                        return item.key;
                    })
                }
            })
        },

        /**
         * получение туров
         */
        getResorts() {
            this.loaderResorts = true;
            axios.post('/admin/resorts/get-resorts', {
                'selectedCountries': this.selectedCountries,
                'allResorts': this.allResorts
            }).then( (response) => {
                this.loaderResorts = false;
                this.desserts = response.data;
            }).catch( (error) => {
                this.loaderResorts = false;
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
            this.crtSelectedDelItem = item.resorts_id;
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
            this.itemDel = item;
        },

        /**
         * хакрытие окна новй записи/редактирования
         */
        close () {
            this.dialog = false;
            this.editedIndex = -1;
        },

        /**
         * хакрытие окна удаления
         */
        closeDelete() {
            this.dialogDelete = false;
            this.editedIndex = -1;
        },

        /**
         * удаление строки курорт
         */
        deleteItemConfirm() {
            this.loaderDelete = true;
            axios.post('/admin/resorts/delete-resort', {
                'delItem': this.editedItem
            }).then( (response) => {
                if(response.data.res) {
                    this.editedIndex = this.desserts.indexOf(this.itemDel);
                    this.desserts.splice(this.editedIndex, 1);
                } else {
                    this.dialogAlert = true;
                    this.dialogAlertTitle = 'Не удалось удалить строку. Сообщите прогаммистам.';
                }
                this.loaderDelete = false;
                this.closeDelete();
            }).catch( (error) => {
                this.loaderDelete = false;
                this.closeDelete();
                this.dialogAlert = true;
                this.dialogAlertTitle = error.message;
            })
        },

        /**
         * выбор пути отправки данных
         */
        selectRequest() {
            if (this.editedIndex > -1) {
                this.save('update-resort', this.item);
            } else {
                if (this.editedItem.country_id == this.editedItem.resort_country_id) {
                    this.save('create-row', this.item = null);
                } else {
                    this.dialogAlert = true;
                    this.dialogAlertTitle = 'Идентификатор и внешний идентификатор должны совпадать!';
                }
            }
        },

        /**
         * сохранение данных
         *
         * @param path
         * @param item
         */
        save (path, item) {
            this.loaderUpdate = true;
            axios.post(`/admin/resorts/${path}`, {
                'editedItem': this.editedItem
            }).then( (response) => {
                if(response.data.res) {
                    this.editedIndex = this.desserts.indexOf(item)
                    this.desserts[this.editedIndex]['is_popular'] =  this.editedItem['is_popular'];
                    this.desserts[this.editedIndex]['name'] =  this.editedItem['name'];
                    this.desserts[this.editedIndex]['rating'] =  this.editedItem['rating'];
                } else {
                    this.dialogAlert = true;
                    this.dialogAlertTitle = 'Данные не отредактированы. Сообщите программистам.';
                }
                this.loaderUpdate = false;
                this.close()
            }).catch( (error) => {
                this.loaderUpdate = false;
                this.close()
                this.dialogAlert = true;
                this.dialogAlertTitle = error.message;
            })
        }
    }
})