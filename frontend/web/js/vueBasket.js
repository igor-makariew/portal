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
        validHotel: false,
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        loader: false,
        countCheckedHotels: [],
        customer: [],
        countBasket: {
            count: 0,
            visible: false
        },
        modalWindow: false,
        prompt: false,
    }),

    created() {
        this.getBasket();
    },

    computed: {

    },

    watch: {

    },

    methods: {
        /**
         * удаление выбранных отелей
         */
        deleteHotels() {
            const data = {
                'countHotels': this.countCheckedHotels,
                'customer': this.customer
            }
            if (this.prompt) {
                this.modalWindow = false;
                this.loader = true;
                axios.post('/basket/delete', {
                    'data': data
                }).then( (response) => {
                    if (response.data.res) {
                        this.prompt = false;
                        this.listHotels.forEach( (hotel, index) => {
                            let found = this.countCheckedHotels.find( param => {
                                return param.name == hotel.name;
                            });
                            if (found != undefined) {
                                this.listHotels.splice(index, 1);
                            }
                            const countBasket = document.getElementById('countBasket');
                            countBasket.innerText =  this.listHotels.length > 0 ? this.listHotels.length : '';
                        })
                        this.validHotel = false;
                        this.loader = false;
                        this.countCheckedHotels = [];
                    } else {
                        alert(response.data.message);
                    }
                }).catch( (error) => {
                    console.log(error.message)
                })
            }
        },

        /**
         * закрытие окна подтвержения удаления
         */
        closeModal(paramBool) {
             if (paramBool) {
                 this.modalWindow = false;
                 this.prompt = true;
                 this.deleteHotels();
             } else {
                 this.modalWindow = false;
             }
        },

        /**
         * зазазать выбранные отели
         */
        buyHotels() {
            const data = {
                'countHotels': this.countCheckedHotels,
                'customer': this.customer
            }
            axios.post('/basket/buy', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    this.listHotels.forEach( (hotel, index) => {
                        let found = this.countCheckedHotels.find( param => {
                            return param.name == hotel.name;
                        });
                        if (found != undefined) {
                            this.listHotels.splice(index, 1);
                        }
                    })
                } else {
                    console.log('error');
                }

                const countBasket = document.getElementById('countBasket');
                countBasket.innerText =  this.listHotels.length > 0 ? this.listHotels.length : '';
                this.countCheckedHotels = [];
                this.dialog = false;
                console.log(this.listHotels);
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * подсчет выбранных строк в корзине
         */
        countCheckedRows(check, name, price, raiting) {
            if (check) {
                let obj = Object.assign({}, {'name': name}, {'price': price}, {'raiting': raiting} );
                this.countCheckedHotels.push(obj);
            } else {
                this.countCheckedHotels.forEach( (hotel, index) => {
                    this.countCheckedHotels.splice(index,1);
                });
            }

            // if (this.countCheckedHotels.length > 0) {
            //     this.countBasket.count = this.listHotels.length - this.countCheckedHotels.length;
            //     this.countBasket.visible = true;
            // } else {
            //     this.countBasket.count = this.listHotels.length;
            //     this.countBasket.visible = false;
            // }

            if (this.countCheckedHotels.length > 0) {
                this.validHotel = true;
            } else {
                this.validHotel = false;
            }

        },

        /**
         * получение содержимого корзины
         */
        getBasket() {
            this.loader = true;
            axios.post('/basket/get-basket', {})
                .then( (response) => {
                    if (response.data.res) {
                        let userId = response.data.userId;
                        if (Object.keys(response.data.basket).length > 0) {
                            let hotels = Object.values(response.data.basket[userId]);
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

                                const countBasket = document.getElementById('countBasket');
                                countBasket.innerText =  this.listHotels.length > 0 ? this.listHotels.length : '';
                            });
                        }
                    }

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
                    countBasket.innerText = Object.keys(response.data.basket[countBasket.getAttribute("data-userid")]).length - 1 > 0 ?
                        Object.keys(response.data.basket[countBasket.getAttribute("data-userid")]).length - 1 : '';
                })
        }
    }

})