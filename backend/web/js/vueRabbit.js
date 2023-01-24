new Vue({
    el: '#appRabbit',
    vuetify: new Vuetify(),
    data: () => ({
        dataServer: '',
        items: ['Days', 'Weeks', 'Months'],
        search: '',
        headers: [
            {
                text: 'Date',
                align: 'start',
                filterable: true,
                value: 'date',
            },
            {
                text: 'Avg Temperature',
                value: 'temp',
                filterable: true,
            },
        ],
        desserts: [],
        selectInterval: '',
    }),

    created() {
        //this.getDataServer();
    },

    methods:{
        getDataServer(interval) {
            axios.post('/admin/rabbit/server', {
                'data': interval
            }).then( (response) => {
                this.desserts = response.data;
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        get() {
            this.getDataServer(this.selectInterval);
        }
    }

});