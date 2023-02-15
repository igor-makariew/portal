new Vue({
    el: '#appRabbit',
    vuetify: new Vuetify(),
    data: () => ({
        dataServer: '',

    }),

    created() {

    },

    methods:{
        getDataServer(interval) {
            axios.post('/admin/rabbit/server', {
                 // 'data': 'test'
            })
                .then( (response) => {
                    console.log(response)
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        Send() {
            this.getDataServer();
        }

    }

});