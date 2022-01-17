 new Vue({
    el: '#appBasket',
    vuetify: new Vuetify(),

    data: () => ({
        dialog: false,
        listHotels: [],
        headers: [
            {
                text: 'Название',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            { text: 'Отель', sortable: false, value: 'label' },
            { text: 'Рейтинг', sortable: false, value: 'stars' },
            { text: 'Цена', sortable: false, value: 'price'},
            { text: 'Подтверждение', sortable: false, value: 'check' },
        ],
        validBuy: false,
        loader: false,
        countHotels: [],
        customer: [],
        countBasket: {
            count: 0,
            visible: false
        },
        allRows: 0,
        //testingLet: ''
    }),

    created() {
        this.getBasket();
        this.countInBasket();
    },

    // computed: {
    //
    // },
    //
    // mounted: function() {
    //
    // },

    watch: {
        // testingLet(newVal, oldVal) {
        //     console.log(newVal, oldVal);
        //     deep: true
        // },

        // item: {
        //     handler: function(newVal, oldVal) {
        //         // console.log(newVal, oldVal);
        //         console.log('test');
        //     },
        //     deep: true
        // },

        // testing(newVal, oldVal) {
        //     console.log(newVal, oldVal)
        // }
    },

    methods: {
        windowBasket() {
            this.dialog = true;
        },

        buyHotels() {
            const data = {
                'countHotels': this.countHotels,
                'customer': this.customer
            }
            axios.post('/basket/buy', {
                'data': data
            }).then( (response) => {
                let buyHotels = Object.values(response.data.buy);
                buyHotels.forEach( (buyHotel) => {
                    this.listHotels.forEach( (hotel, index) => {
                        if (buyHotel.hotel == hotel.name) {
                            this.listHotels.splice(index, 1)
                        }
                    });
                });
                this.dialog = false;
                console.log(response);
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * подсчет выбранных строк в корзине
         */
        countRows(check, name, price, stars) {
            if (check) {
                let obj = Object.assign({}, {'name': name}, {'price': price}, {'raiting': stars});
                this.countHotels.push(obj);
            } else {
                this.countHotels.forEach( (hotel, index) => {
                    if (hotel.name == name && hotel.price == price) {
                        this.countHotels.splice(index,1);
                    }
                });
            }

            if (this.countHotels.length > 0) {
                this.countBasket.count = this.countHotels.length;
                this.countBasket.visible = true;
            } else {
                this.countBasket.visible = false;
            }

            if (this.countHotels.length > 0) {
                this.validBuy = true;
            } else {
                this.validBuy = false;
            }

        },

        /**
         * получение содержимого корзины
         */
        getBasket() {
            this.loader = true;
            axios.post('/basket/get-basket', {})
                .then( (response) => {
                    let userId = response.data.userId;
                    let hotels = Object.values(response.data.basket[userId]);
                    console.log(response.data.basket[userId]);
                    hotels.forEach( (hotel, index) => {
                        if (Object.keys(hotel).indexOf('fullName') !== -1) {
                            this.listHotels[index] = {
                                'name': hotel.fullName != '' ? hotel.fullName : hotel.hotelName,
                                'label': hotel.label != '' ? hotel.label : hotel.hotelName,
                                'stars': hotel.stars != '' ? parseInt(hotel.stars) : 0,
                                'price': hotel.price != undefined ? hotel.price : 'Уточняется',
                                'check': false,
                            }
                        }
                        if (Object.keys(hotel).indexOf('email') !== -1) {
                            this.customer = {
                                'email': hotel.email,
                                'username': hotel.username
                            }
                        }

                        this.allRows = this.listHotels.length
                        if (this.listHotels.length > 0) {
                            this.countBasket.count = this.listHotels.length;
                            this.countBasket.visible = true;
                        } else {
                            this.countBasket.visible = false;
                        }
                    });
                    this.loader = false;
                }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * количество заказов в корзине
         */
        async countInBasket() {
            return await axios('/basket/get-basket', {})
                .then( (response) => {
                    const countBasket = document.getElementById('countBasket');
                    countBasket.innerText = Object.keys(response.data.basket[countBasket.getAttribute("data-userid")]).length - 1;
                })
        }
    }

})