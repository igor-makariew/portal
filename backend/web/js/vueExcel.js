new Vue({
    el: '#appExcel',
    vuetify: new Vuetify(),

    data: () => ({
        loaderSelected: false,
        selectedModel:null,
        itemsModel: [],
        tableHidden: false,
        dataProvider: [],
        tableName: '',
        // headers: [],
        // desserts: [],
        // page: 1,
        // pageCount: 0,
        // itemsPerPage: 20,

        intervalCountriesRuls: [
            v => !!v || 'Поле не должна быть пустым',
            v => v > 0 || 'Значение не должно быть меньше 1',
            v => v < 51 || 'Значение не должно быть больше 50',
        ],

        fullName: '',
        allTime: '',
        breakTime: '',
        sotrudnic: '',

        //excel file
        value: 0,
        dialog: false,
        file: [],
        singleSelect: false,
        selected: [],
        headers: [
            // {
            //     text: 'Dessert (100g serving)',
            //     align: 'start',
            //     sortable: false,
            //     value: 'name',
            // },
            // { text: 'Calories', value: 'calories' },
            // { text: 'Fat (g)', value: 'fat' },
            // { text: 'Carbs (g)', value: 'carbs' },
            // { text: 'Protein (g)', value: 'protein' },
            // { text: 'Actions', value: 'actions', sortable: false },
        ],
        desserts: [],
        loader: false,
        hiddenTable: false,
        editedIndex: -1,
        editedItem: {
            // name: '',
            // calories: 0,
            // fat: 0,
            // carbs: 0,
            // protein: 0,
        },
        defaultItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
    }),

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog (val) {
            val || this.close()
        },
    },

    created() {
        // this.getNamesModels();
    },

    methods: {
        /**
         * загрузка файла на сервер
         */
        uploadFile() {
            this.$refs.file.blur();
            let formData = new FormData();
            let file = this.file;
            formData.append('file', file);
            this.loader = true;
            if (file == undefined) {
                this.loader = false;
            }

            axios.post('/admin/exel/upload-file', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( (response) => {
                if (response.data.response) {
                    this.setNameColumns(response.data.namesColumns);
                    this.setValuesRows(response.data.data);
                    this.file = [];
                    this.loader = false;
                    this.hiddenTable = true;
                }

            }).catch( (error) => {
                console.log(error.message);
            })

        },

        /**
         * именуем столбцы таблицы
         *
         * @param arr
         */
        setNameColumns(arr) {
            this.headers = [];
            arr.forEach( (name) => {
                this.headers.push(
                    {
                        text : name,
                        value : name,
                    }
                );
            })

            this.headers.push({ text: 'Actions', value: 'actions', sortable: false });
        },

        /**
         * заполнение строк данными
         *
         * @param arr
         */
        setValuesRows(arr) {
            this.desserts = [];
            for (let index = 1; index <= Object.keys(arr).length; index++) {
                this.desserts[index] = arr[index];
            }

            // this.desserts = Object.entries(arr);
        },

        /**
         * создание полей у объекта
         *
         * @param object
         */
        createFieldObject(object) {
            for ( let index in object) {
                this.editedItem[index] = null;
            }
        },

        editItem (item) {
            this.createFieldObject(item);
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true
        },

        deleteItem (item) {
            const index = this.desserts.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
        },

        close () {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        /**
         * сохраняем данные в массиве
         */
        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem);
            } else {
                this.desserts.push(this.editedItem);
            }
            this.close()
        },


        /**
         * получение списка имен моделей
         */
        getNamesModels() {
            this.loaderSelected = true;
            axios.post('/admin/exel/get-names-models', {})
                .then( (response) => {
                    Object.keys(response.data).forEach( (key) => {
                        this.itemsModel.push({'key': key, 'value': response.data[key]})
                    })
                    this.loaderSelected = false;
                }).catch( (error) => {
                this.loaderSelected = false;
                console.log(error.message)
            })
        },

        /**
         * получние модели
         */
        getModel() {
            axios.post('/admin/exel/get-model', {
                'selectedModel': this.selectedModel
            }).then( (response) => {
                if(response.data.res) {
                    this.tableHidden = response.data.res;
                    this.dataProvider = response.data.dataProvider;
                    this.tableName = response.data.nameTable.from[0];
                    // this.createColumns(this.dataProvider[0]);
                    console.log(this.headers)
                }
                console.log(response);
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * создание заголовка для табицы
         *
         * @param array
         */
        createColumns(array) {
            if(this.headers.length > 0) {
                this.headers = [];
            }
            Object.keys(array).forEach( (key) => {
                this.headers.push( {
                    text: key,
                    align: 'start',
                    sortable: key == 'id' ? false : true,
                    value: key,
                    align: 'center'
                });
            });
        },

        startDay() {
            axios.post('/admin/exel/start-day', {
                'fullname': this.fullName
            }).then( (response) => {
                if(response.data) {
                    this.sotrudnic = this.fullName
                    this.fullName = '';
                    this.allTime = '';
                    this.breakTime = '';
                    alert('ok')
                } else {
                    alert('no')
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        start() {
            axios.post('/admin/exel/start', {
                'fullname': this.fullName
            }).then( (response) => {
                if(response.data) {
                    this.fullName = '';
                    alert('ok')
                } else {
                    alert('no')
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        stop() {
            axios.post('/admin/exel/stop', {
                'fullname': this.fullName
            }).then( (response) => {
                if(response.data) {
                    this.fullName = '';
                    alert('ok')
                } else {
                    alert('no')
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        stopDay() {
            axios.post('/admin/exel/stop-day', {
                'fullname': this.fullName
            }).then( (response) => {
                if(response.data) {
                    this.fullName = '';
                    this.allTime = (response.data.allTime / (60 * 60));
                    console.log(this.allTime);
                    this.breakTime = (response.data.breakTime / (60));
                    console.log(this.breakTime);
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * Отмена предыдущего
         * запроса
         *
         * return undefined
         */
        cancel() {
            if (this.request != null) {
                this.request.cancel();
            }

            this.clearOldRequest("Cancelled");
        },

        /**
         * Очитска запроса от данных
         * @param {String} msg
         *
         * return undefined
         */
        clearOldRequest(msg) {
            if (this.request) {
                this.request.msg = msg;
                this.requests.push(this.request);
                this.request = null;
            }
        },
    }
})