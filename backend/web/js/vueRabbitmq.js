new Vue({
    el: '#appRabbitmq',
    vuetify: new Vuetify(),

    data: () => ({
        connectionRabbit: false,
        valid: true,
        command: '',
        commandRules: [
            v => !!v || 'Command is required',
        ],

    }),

    created () {

    },

    computed: {

    },

    watch: {

    },

    methods: {

        connectionRabbitmq() {
            axios.post('/admin/rabbitmq/rabbitmq', {
                'data': 'test'
            })
                .then( (response) => {
                    this.connectionRabbit = true;
                    console.log(response)
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        async validate () {
            const { valid } = await this.$refs.form.validate()

            if (valid) alert('Form is valid')
        },
    }

})