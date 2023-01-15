new Vue({
    el: '#appBasketGood',
    vuetify: new Vuetify(),

    data: () => ({
        loaderGoods: false,
        basket:[],
        interval: {},
        value: 0,
        dialog: false,
        fullname:'',
        phone:'',
        email:'',
        address:'',
    }),

    created () {
        this.getBasket();
    },

    mounted () {

    },

    watch: {

    },

    computed: {
        total(){
            let sum = 0;
            this.basket.forEach(function(value) {
                sum += (value.count * value.price);
            })

            return sum;
        }
    },

    methods: {
        getBasket(){
            this.loaderGoods = true;

            axios.post('/site/get-basket')
                .then( (response) => {
                    //this.basket = response.data;
                    response.data.forEach(function (value) {
                        value.count = 1;
                    })
                    this.basket = response.data;
                    this.loaderGoods = false;
                }).catch( (error) => {
                console.log(error.message);
            })
        },

        toOrder(goods) {
            this.dialog = false;
            let data = {
                'fullname':this.fullname,
                'email': this.email,
                'phone': this.phone,
                'address': this.address,
                'goods': goods
            };
            axios.post('/site/to-order', {
                'data': data
            }).then( (response) => {
                alert('Спасибо за покупку');
                location.href = '/shop';
            }).catch( (error) => {
                console.log(error.message);
            })
        },

    }
});


