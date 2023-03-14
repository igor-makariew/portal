new Vue({
    el: '#appGame',
    vuetify: new Vuetify(),

    data: () => ({
        // отмена предыдущего запроса
        requests: [],
        request: null,
        //
        valid: true,
        paramsGame: 3,
        paramsGameRules: [
            v => !!v || 'Поле не должна быть пустым',
            v => v >= 3 || 'Значение не должно быть меньше 3',
            v => v <= 10 || 'Значение не должно быть больше 10',
        ],
        colsRules: [
            v => !!v || 'Поле не должна быть пустым',
            v => v >= 3 || 'Значение не должно быть меньше 3',
            v => v <= 10 || 'Значение не должно быть больше 10',
        ],
        begin: false,
        game: '',
        story: [],
        getRef: '',
        gameEvent: false,
    }),

    created() {

    },

    computed: {

    },

    watch: {

    },

    methods: {
        /**
         * start game
         */
        start() {
            let data = {
                'paramsGame': this.paramsGame,
            }

            if (this.request) {
                this.cancel();
            }

            const axiosSource = axios.CancelToken.source();
            this.request = {
                cancel: axiosSource.cancel,
                msg: "Loading..."
            };

            axios.post('/admin/game/start', {
                'data': data
            }, {
                cancelToken: axiosSource.token
            })
                .then( (response) => {
                    this.clearOldRequest("Success");
                    this.game = '';
                    this.begin = response.data.begin;
                    this.game = response.data.game;
                    this.story = response.data.story;
                    this.getRefs();
                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        /**
         * get html block
         *
         * @returns {Promise<void>}
         */
        async getRefs() {
            let promise = new Promise( (resolve, reject) => {
                setTimeout(() => resolve(this.$refs['elemGame']), 1);
                // not work
                // let res = () => resolve(this.$refs['elemGame']);
                // res();
            });

            this.getRef = await promise;
            this.getRef.removeEventListener('click', {handleEvent: this.joisticGame, story: this.story});
            this.view(this.story);
            setTimeout(this.hide, 3000, this.story);
            this.getRef.addEventListener('click', {handleEvent: this.joisticGame, story: this.story});
        },

        /**
         * управление игрой
         *
         * @param event
         */
        joisticGame(event) {
            event.preventDefault();
            // console.log(this.story);
            let findElem = this.story.indexOf(Number(event.target.id));
            // console.log(findElem);
            const cardId = document.getElementById(event.target.id);
            if (findElem != -1) {
                cardId.classList.remove('flex-item-color');
                cardId.classList.add('flex-item-success-color');
            } else {
                cardId.classList.remove('flex-item-color');
                cardId.classList.add('flex-item-error-color');
            }
        },

        viewCard(id) {
            const cardId = document.getElementById(id);
            console.log(cardId);
        },

        /**
         * показ спрятанных слайдов
         *
         * @param array
         */
        view(array) {
            array.forEach( (value) => {
                let cardId = document.getElementById(value);
                cardId.classList.remove('flex-item-color');
                cardId.classList.add('flex-item-success-color');
            })
        },

        /**
         * скрытие спрятанных слайдов
         *
         * @param array
         */
        hide(array) {
            array.forEach( (value) => {
                let cardId = document.getElementById(value);
                cardId.classList.remove('flex-item-success-color');
                cardId.classList.add('flex-item-color');
            })
        },

        /**
         * Отмена предыдущего
         * запроса
         */
        cancel() {
            if (this.request != null) {
                this.request.cancel();
            }

            this.clearOldRequest("Cancelled");
        },

        /**
         * Очитска запроса от данных
         * @param {String} msg
         */
        clearOldRequest(msg) {
            if (this.request) {
                this.request.msg = msg;
                this.requests.push(this.request);
                this.request = null;
            }
        },

        validate () {
            this.$refs.form.validate()
        },
    }
})