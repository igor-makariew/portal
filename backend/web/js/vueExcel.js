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
        headers: [],
        desserts: [],
        page: 1,
        pageCount: 0,
        itemsPerPage: 20,

        intervalCountriesRuls: [
            v => !!v || 'Поле не должна быть пустым',
            v => v > 0 || 'Значение не должно быть меньше 1',
            v => v < 51 || 'Значение не должно быть больше 50',
        ],

        fullName: '',
        allTime: '',
        breakTime: '',
        sotrudnic: '',
    }),

    created() {
        this.getNamesModels();
    },

    methods: {
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
                    this.createColumns(this.dataProvider[0]);
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
        }
    }
})