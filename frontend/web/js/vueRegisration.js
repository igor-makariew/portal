new Vue({
    el: '#appRegistration',
    vuetify: new Vuetify(),

    data: () => ({
        name: '',
        phone: '',
        email: '',
        password: '',
        checked: 'authorization',
        valid: false,
        dialog: false,
    }),

    methods: {
        windowRegistration(event) {
            event.preventDefault();
            this.dialog = true;
        },

    }
})