new Vue({
    el: '#appCountry',
    vuetify: new Vuetify(),

    data: () => ({
        loaderCountry: false,
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        country:{},
        listResorts: [],
    }),

    created () {
        this.getCountry();
    },

    methods: {
        getCountry() {
            let urlParams = new URLSearchParams(window.location.search);
            let idCountry = urlParams.get('id');
            const data = {
                'id': idCountry
            };
            this.loaderCountry = true;
            axios.post('/destination/get-country', {
                'data': data
            }).then( (response) => {
                this.listResorts = response.data;
                this.loaderCountry = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        }
    }
})