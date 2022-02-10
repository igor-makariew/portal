new Vue({
    el: '#appHotel',
    vuetify: new Vuetify(),

    data: () => ({
        loaderHotel: false,
        loaderSlider: false,
        information: '',
        defaultImg: '/images/hotels.jpg',
        paramHotel: {
            'hotelName': '',
            'hotelId': '',
            'hotelLocationName': '',
            'hotelLocationId': '',
            'hotelStars': '',
            'hotelPriceAvg': '',
            'hotelPriceFrom': '',
            'hotelLabel': '',
            'location': ''
        },
        show: false,
        viewSelectedItem:'',
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        // start slider
        items: 0,
        // end slider

    }),

    created () {
        this.loadHotel('', '');
        this.getParamSlider();
        // $(function() {
        //     // Owl Carousel
        //     const owl = $(".owl-carousel");
        //     owl.owlCarousel({
        //         items: 4,
        //         margin: 10,
        //         nav: true
        //     });
        // });
    },

    methods: {
        /**
         *  Загружаем карочку отеля
         */
        loadHotel(id, hotelId) {
            let urlParams = new URLSearchParams(window.location.search);
            let cardId = id != '' ? id : hotelId;
            let paramId = cardId != '' ? cardId : urlParams.get('id');
            const data = {
                'id': paramId,
            };
            this.loaderHotel = true;
            axios.post('/favorite/get-hotel', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    this.loaderHotel = false;
                    let location = JSON.parse(response.data.dataHotel.location);
                    this.paramHotel.hotelName = response.data.dataHotel.full_name != '' ? response.data.dataHotel.full_name : response.data.dataHotel.hotel_name + ', ' + location.name + ", " + location.country;
                    this.paramHotel.hotelId = response.data.dataHotel.hotel_id;
                    this.paramHotel.hotelLocationName = response.data.dataHotel.location_name != '' ? response.data.dataHotel.location_name : location.name + ". " + location.country;
                    this.paramHotel.hotelLocationId = response.data.dataHotel.location_id;
                    this.paramHotel.hotelStars = response.data.dataHotel.stars != ''  ? Number(response.data.dataHotel.stars) : 0;
                    this.paramHotel.hotelPriceAvg = response.data.dataHotel.price_avg != '' ? response.data.dataHotel.price_avg : 'Не указана';
                    this.paramHotel.hotelPriceFrom = response.data.dataHotel.price_form != null ? response.data.dataHotel.price_form : 'Не указана';
                    this.paramHotel.hotelLabel = response.data.dataHotel.label != '' ? response.data.dataHotel.label : response.data.dataHotel.hotel_name;
                    this.paramHotel.location = location;
                } else {
                    this.information = 'Данные временно отсутствуют. Извините за неудобства';
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * Загружаем слайдер
         */
        getParamSlider() {
            this.loaderSlider = true;
            axios.post('/favorite/get-sliders', {})
                .then( (response) => {
                    this.loaderSlider = false;
                    this.items = response.data.filterHotel.length;
                    this.sliders = response.data.filterHotel;
                    // console.log(this.sliders);
                    $(function() {
                        // Owl Carousel
                        const owl = $(".owl-carousel");
                        owl.owlCarousel({
                            items: 4,
                            margin: 10,
                            nav: true
                        });
                    });
                }).catch( (error) => {
                    console.log(error.message)
            })
        },
    }
})