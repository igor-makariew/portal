new Vue ({
    el: '#appTours',
    vuetify: new Vuetify(),

    data: () => ({
        loaderTours: false,
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        listCountries: [],
        listResorts: [],
        listCountriesId: [],
        page: 1,
        countPage: '',
        rowPerPage: 12,
    }),

    watch: {
        page: function (newVal) {
            this.page = newVal;
            this.getTours();
        },
    },
    created () {
        this.getTours();
    },

    methods: {
        getTours() {
            const data = {
                'page': this.page,
                'countPage': this.countPage,
                'rowPerPage': this.rowPerPage
            }
            this.loaderTours = true;
            axios.post('/site/get-tours', {
                'data': data
            }).then( (response) => {
                    this.loaderTours = false;
                    this.countPage = response.data.pagination.countPage
                    this.listCountries = response.data.countries;
                    this.listResorts = response.data.resorts;
                    this.listCountriesId = response.data.ids;
                }).catch( (error) => {
                    console.log(error.message)
            })
        },
    }
})