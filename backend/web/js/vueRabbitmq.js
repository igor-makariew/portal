new Vue({
    el: '#appRabbitmq',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        command: '',
        commandRules: [
            v => !!v || 'Command is required',
            v => /[a-zA-Z]/.test(v) || 'Command must be valid',
        ],
        command: '',

        validRpc: true,
        rpcRules: [
            v => !!v || 'Command is required',
        ],
        rpcValue: '',
        fibonachiValue: 0,

    }),

    created () {

    },

    computed: {

    },

    watch: {

    },

    methods: {
        /**
         * connection rabbit_mq
         */
        connectionRabbitmq() {
            let data = {
                'command': this.command
            };
            axios.post('/admin/rabbitmq/rabbitmq', {
                'data': data
            })
                .then( (response) => {
                    this.command = '';
                    console.log(response);
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * connection rabbit_mq_rpc
         */
        connectionRabbitmqRpc() {
            let data = {
                'rpcValue': this.rpcValue
            };
            axios.post('/admin/rabbitmq/rabbitmq-rpc', {
                'data': data
            }).then( (response) => {
                this.rpcValue = '';
                this.fibonachiValue = response.data;
                console.log(response.data)
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        async validate () {
            const { valid } = await this.$refs.form.validate()

            if (valid) alert('Form is valid')
        },
    }

})