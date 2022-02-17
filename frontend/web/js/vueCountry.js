new Vue({
    el: '#appCountry',
    vuetify: new Vuetify(),

    data: () => ({
        loaderCountry: false,
        loaderHotels: false,
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        country:{},
        listResorts: [],
        listHotels: [],
        flag: false,
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
        },

        async getHotels(id, flag) {
            if (!flag) {
                this.loaderHotels = true;
                const result = await axios(`http://api-gateway.travelata.ru/directory/resortHotels?resortId=${id}`);
                this.listHotels = result['data']['data'];
                if (this.listHotels.length != -1) {
                    this.loaderHotels = false;
                }
                this.flag = true;
            } else {
                this.flag = false;
            }
        }
    }
})