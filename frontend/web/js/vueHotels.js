new Vue({
    el: '#appHotels',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        query: 'moscow',
        lang: 'ru',
        langs: ['ru', 'en', 'th', 'de', 'es', 'fr', 'it', 'pl'],
        lookFor: 'both',
        lookFors: ['both', 'city', 'hotel'],
        limit: 10,
        currency: 'rub',
        currencies: ['rub', 'usd', 'eur'],
        // start calendar
        currentDate: new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate(),
        //dateStart: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        dateStart: new Date().getFullYear() + '-' + (new Date().getMonth() + 1).toString().padStart(2, "0") + '-' + ('0' + new Date().getDate()).slice(-2),
        dateEnd:  new Date().getFullYear() + '-' + (new Date(Date.now() + 24*60*60*1000).getMonth() + 1) + '-' + (new Date(Date.now() + 24*60*60*1000).getDate()),
        // end calendar
        hotels: [],
        links: [],
        show: false,
        visiblyHotels: false,
        showMessage: false,
        fieldCity: [
            v => !!v || 'Город не введен',
            v => /^([A-Z,a-z]*)$/g.test(v) || 'Название города введено не корректно, используйте английский язык.'
        ],
        fieldLimit: [
            v => !!v || 'Выберите количество результатов.',
            v => v > 0 || 'Количество должно быть больше 0',
        ],
        loader: false,
        crtSelectedItem: '',

        //start preloder
        interval: {},
        value: 0,
        //end preloder
        page: 1,
        countPage: '',
        rowPerPage: 6,
        // satrt favorite
        iconFavorite: '',
        // end favorite
        dialogAlert: false,
        dialogAlertTitle: '',
        // start carousel
        items: 0,
        viewSelectedItem:'',
        dialogHotel: false,
        dialogHotelName: '',
        dialogHotelId: '',
        dialogHotelLocationName: '',
        dialogHotelLocationId: '',
        dialogHotelStars: '',
        dialogHotelPriceAvg: '',
        dialogHotelPriceFrom: '',
        dialogHotelLabel: '',
        loaderListViewed: false,
        listViewedHotels: [],
        visibleFunction: false,
        // end carousel
    }),

    created () {
        this.visibleFunction = true;
        this.showViewedHostels(null);
    },

    mounted () {
        // var addDays = 3;
        // var date = new Date()
        // date.setDate(date.getDate() + addDays);
        // console.log(date);
        // this.dateEnd.setDate(this.dateEnd.getDate() + 1);
        // console.log(this.dateEnd);
    },

    watch: {
        page: function(newVal) {
            this.page = newVal;
            this.getHotels();
        },

        listHotels: {
            handler(after, before) {
                let val = Object.keys(after).length;
                let old = Object.keys(before).length;
                if (val > 1) {
                    if (val > old) {

                    }
                }
            },
            deep: true
        }

    },

    computed: {
        // listHotels() {
        //     return Object.assign({}, this.viewedHotel);
        // }
    },

    methods: {
        validate() {
            this.$refs.formValid.validate();
        },

        /**
         * проверка корректности заполныямых дат
         */
        validateDate() {
            const start = new Date(this.dateStart);
            const end = new Date(this.dateEnd);
            const current = new Date(this.currentDate);

            if (start < current) {
                this.dateStart = current.getFullYear() + '-' + (current.getMonth() + 1).toString().padStart(2, "0") + '-' + current.getDate();
            }

            if (end <= start ) {
                this.dateStart = start.getFullYear() + '-' + (start.getMonth() + 1).toString().padStart(2, "0") + '-' + start.getDate();
                start.setDate(start.getDate() + 1);
                this.dateEnd = start.getFullYear() + '-' + (start.getMonth() + 1).toString().padStart(2, "0") + '-' + start.getDate();
            }
        },

        /**
         * получение списка отелей
         */
        getHotels() {
            const filter = {
                'query': this.query,
                'lang': this.lang,
                'lookFor': this.lookFor,
                'limit': this.limit,
                'currency': this.currency,
                'dateStart': this.dateStart,
                'dateEnd': this.dateEnd,
                'page': this.page,
                'countPage': this.countPage,
                'rowPerPage' : this.rowPerPage,
            };


            // let options = {
            //     method: 'GET',
            //     url: 'https://hotels4.p.rapidapi.com/locations/v2/search',
            //     params: {query: 'new york', locale: 'en_US', currency: 'USD'},
            //     headers: {
            //         'x-rapidapi-host': 'hotels4.p.rapidapi.com',
            //         'x-rapidapi-key': 'SIGN-UP-FOR-KEY'
            //     }
            // };
            this.loader = true;
            axios.post('/site/get', {
                'filter': filter
            }).then((response) => {
                this.dialog = true;
                if (response.data.hotels.length > 0) {
                    this.hotels = response.data.hotels;
                    this.links = this.hotels.map(function(link){
                        let id = link.id != '' ? link.id : link.hotelId;
                        return id;
                    })
                    this.countPage = response.data.pagination.countPage
                    this.visiblyHotels = true;
                    this.showMessage = false;
                } else {
                    this.visiblyHotels = false;
                    this.showMessage = true;
                    this.message = 'По вашему запросу данных не найдено!!!'
                }
                this.loader = false;
            });
        },

        /**
         * отмена событий доделать данное
         */
        stopClick(event) {
            console.log(event)
        },

        /**
         * addToBasket
         */
        addToBasket(id, userId) {
            const data = {
                'hotelId': id,
                'userId': userId
            }

             axios.post('/basket/add-basket', {
                 data: data
             }).then( (response) => {
                 const countBasket = document.getElementById('countBasket');
                 countBasket.innerText = Object.keys(response.data.basket[userId]).length - 1;
             }).catch((error) => {
                 console.log(error.message);
             })
        },

        /**
         * Добавление карточки продукта в избраннное
         *
         * @param hotel
         */
        addFavorite(hotel) {
            this.iconFavorite = this.iconFavorite == '' ? 'green' : '';
            let id = hotel.id == '' ? hotel.hotelId : hotel.id;
            const data = {
                'id': id,
                'date': new Date
            }
            this.loader = true;

            axios.post('/favorite/add-favorite', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    Object.values(this.hotels).forEach( (value) => {
                        if (value.id == data.id || value.hotelId == data.id) {
                            value.favorite = 'green';
                        }
                    });
                } else {
                    this.dialogAlert = true;
                    this.dialogAlertTitle = 'Данный отель уже добавлен в раздел избранное!';
                }
                this.loader = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         *
         * @param hotel
         */
        // informationHotel(hotel) {
        //     const data = {
        //         'id': hotel.id != '' ? hotel.id : hotel.hotelId
        //     }
        //
        //
        //     this.loader = true;
        //     axios.post('/favorite/viewed-hotel', {
        //         'data': data
        //     }).then( (response) => {
        //         this.viewedHotel = response.data;
        //         this.dialogHotel = true;
        //         this.dialogHotelName = hotel.fullName != '' ? hotel.fullName : hotel.hotelName + ', ' + hotel.location.name + ", " + hotel.location.country;
        //         this.dialogHotelId = hotel.id != '' ? hotel.id : hotel.hotelId;
        //         this.dialogHotelLocationName = hotel.locationName != '' ? hotel.locationName : hotel.location.name + ". " + hotel.location.country;
        //         this.dialogHotelLocationId = hotel.locationId;
        //         this.dialogHotelStars = hotel.stars != ''  ? hotel.stars : 0;
        //         this.dialogHotelPriceAvg = hotel.priceAvg != '' ? hotel.priceAvg : 'Не указана';
        //         this.dialogHotelPriceFrom = hotel.priceFrom != '' ? hotel.priceFrom : 'Не указана';
        //         this.dialogHotelLabel = hotel.label != '' ? hotel.label : hotel.hotelName;
        //         this.loader = false;
        //     }).catch( (error) => {
        //         console.log(error.message);
        //     })
        // },

        showViewedHostels(ids = null) {
            const data = {
                'viewedHotel': ids
            }
            this.loaderListViewed = true;
            axios.post('/favorite/get-list-viewed-hotels', {
                'data': data
            }).then( (response) => {
                const listHotels = response.data.map( function(value) {
                    value.location = JSON.parse(value.location);
                    return value;
                });
                let viewedHotel = [];
                this.listViewedHotels = listHotels;
                if (listHotels.length > 1) {
                    for (let i = 0; i < listHotels.length - 1; i++) {
                        viewedHotel[i] = listHotels[i];
                    }
                }

                this.items = viewedHotel.length;
                this.loaderListViewed = false;
                if (this.visibleFunction ) {
                    $(function() {
                        // Owl Carousel
                        const owl = $(".owl-carousel");
                        owl.owlCarousel({
                            items: 4,
                            margin: 10,
                            nav: true
                        });
                    });
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        }
    }
})


$(function() {
    let className = document.getElementsByClassName('owl-stage');
});


