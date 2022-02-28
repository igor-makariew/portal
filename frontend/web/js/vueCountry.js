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
        userId: 0,
        ratingUser: 0,
        commentTextRules: [
            v => !!v || 'Комментарий не введен',
            v => (v && v.length >= 10) || 'Комментарий должен быть не менее 10 символов',
        ],
        validCommentUser: true,
        countComment: 0,
        comments: [],
        loaderCountComments: false,
        triggerComments: false,
        resort: {},
        //end raiting tour
    }),

    created () {
        this.getCountry();
        this.getUser();
    },

    watch: {
        // countComment: function(val) {
        //     console.log(val);
        // }
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
                this.listResorts = response.data.listResorts;
                this.loaderCountry = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         *
         * @param object resort
         * @returns {Promise<void>}
         */
        async getHotels(resort,  flag) {
            if (!flag) {
                this.resort = resort;
                this.loaderHotels = true;
                this.lengthComments(this.resort.resorts_id);
                const result = await axios(`http://api-gateway.travelata.ru/directory/resortHotels?resortId=${this.resort.id}`);
                this.listHotels = result['data']['data'];
                if (this.listHotels.length != -1) {
                    this.loaderHotels = false;
                }
                // this.flag = true;
            }
            // else {
            //     this.flag = false;
            // }
        },

        /**
         * получение данных зарегестрированного пользователя
         */
        getUser() {
            axios.post('/destination/get-user', {})
                .then( (response) => {
                    this.nameUser = response.data.user['username'];
                    this.userId = response.data.user['id'];
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * Комментраии пользователей
         * @param object resort
         * @param string comment
         * @param integer user_id
         */
        submitComment(comment, user_id, nameUser) {
            this.dialogComment = false;
            let urlParams = new URLSearchParams(window.location.search);
            let idCountry = urlParams.get('id');
            const data = {
                'resort': this.resort,
                'comment': comment,
                'user_id': user_id,
                'id': idCountry,
                'name': nameUser,
            };
            this.loaderCountry = true;
            axios.post('/destination/create-comment', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    this.commentUser = '';
                    this.listResorts = response.data.listResorts;
                } else {
                    // взять модалку и поставить сюда с ошибкой
                    console.log('error');
                }
                this.loaderCountry = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * количество кмментариев
         *
         * @param integer commentResortsId
         */
        lengthComments(commentResortsId) {
            const data = {
                'commentResortsId': commentResortsId
            };
            this.loaderCountComments = true;
            axios.post('/destination/count-comments', {
                'data': data
            }).then( (response) => {
                this.countComment = response.data.countComment;
                this.comments = response.data.commentTour;
                this.loaderCountComments = false;
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * отображение комментариев
         *
         */
        trigComment() {
            this.triggerComments = this.triggerComments == false ? true : false;
        },

        addRating(value) {
            console.log(value);
            this.ratingUser = value;
        }
    }
})