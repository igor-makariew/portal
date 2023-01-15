new Vue({
    el: '#appGoods',
    vuetify: new Vuetify(),

    data: () => ({
        loaderGoods: false,
        goods:{},
        interval: {},
        value: 0,

    }),

    created () {
        this.getGoods();
    },

    mounted () {

    },

    watch: {

    },

    computed: {

    },

    methods: {
        getGoods(){
            this.loaderGoods = true;

            axios.post('/site/get-goods')
                .then( (response) => {
                    this.goods = response.data;
                    this.loaderGoods = false;
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        buy(id) {
            axios.post('/site/set-basket', {
                'id': id
            }).then( (response) => {
                console.log(response)
            });
        }
    }
});


