new Vue({
    el: '#appGame2048',
    vuetify: new Vuetify(),

    data: () => ({

    }),

    created() {
        this.start();
    },

    computed: {

    },

    watch: {

    },

    methods: {

        start() {
            axios.post('/admin/game-2048/start')
                .then( (response) => {
                    console.log(response);
                }).catch( (error) => {
                    console.log(error.message);
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