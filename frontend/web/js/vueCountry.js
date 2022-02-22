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
        //start raiting tour
        dialogComment: false,
        commentUser: '',
        rating: 3,
        nameUser: '',
        ratingUser: 0,
        commentTextRules: [
            v => !!v || 'Комментарий не введен',
            v => (v && v.length >= 10) || 'Комментарий должен быть не менее 10 символов',
        ],
        validCommentUser: true,
        //end raiting tour
    }),

    created () {
        this.getCountry();
        this.getUser()
    },

    methods: {
        /**
         *
         */
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

        /**
         *
         * @param integer id
         * @param boolean flag
         * @returns {Promise<void>}
         */
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
        },

        /**
         * получение данных зарегестрированного пользователя
         */
        getUser() {
            axios.post('/destination/get-user', {})
                .then( (response) => {
                    this.nameUser = response.data.user['username'];
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        submitComment(resort, comment) {
            this.dialogComment = false;
            const data = {
                'resort': resort,
                'comment': comment
            };
            this.loaderCountry = true;
            axios.post('/destination/create-comment', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    console.log(response.data);
                } else {
                    console.log('error');
                }
                this.loaderCountry = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        addRating(value) {
            console.log(value);
            this.ratingUser = value;
        }
    }
})