new Vue({
    el: '#appGame2048',
    vuetify: new Vuetify(),

    data: () => ({
        valuesSquares: [],
        converting: {
            0: '',
            2: 'two',
            4: 'four',
            8: 'eight',
            16: 'sixteen',
            32: 'thirty-two',
            64: 'sixty-four',
            128: 'hundred-twenty-eight',
            256: 'two-hundred-fifty-six',
            512: 'five-hundred-twelve'
        },
        total: 0,
    }),

    created() {
        this.start();
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
            axios.post('/admin/game-2048/start')
                .then( (response) => {
                    this.valuesSquares = response.data;
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * set class blocl DOM
         *
         * @param num
         * @returns {string}
         */
        nameClass(num) {
            if (num == 0) {
                return 'game-cell';
            } else {
                return `game-cell-${this.converting[num]}`;
            }
        },

        up() {
            this.control('up');
        },

        left() {
            this.control('left');
        },

        right() {
            this.control('right');
        },

        down() {
            this.control('down');
        },

        /**
         * control game
         *
         * @param url
         * @param direction
         */
        control(direction) {
            let data = {
                'valuesSquares': this.valuesSquares,
                'param': direction,
                'total': this.total
            };
            console.log(this.total);
            axios.post('/admin/game-2048/control', {
                'data': data
            }).then( (response) => {
                this.valuesSquares = response.data.value;
                console.log(response.data.total);
                this.total = response.data.total;
            }).catch( (error) => {
                console.log(error.message)
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