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
        message: '',
        nameRules: [
            v => !!v || 'Поле имя обязательно для заполнения',
            v => (v && v.length <= 16) || 'Имя должно ыть меньше 16 символов',
        ],
        emailRules: [
            v => !!v || 'Поле e-mail обязательно для заполнения',
            v => /.+@.+\..+/.test(v) || 'E-mail не верно заполнен',
        ],
        passwordRules: [
            v => !!v || 'Поле e-mail обязательно для заполнения',
            v => v.length >= 10 || 'Поле пароль должно быть не менее 10 символов',
        ]
    }),

    methods: {
        validate() {
            this.$refs.formValid.validate();
        },

        windowRegistration() {
            this.dialog = true;
        },

        registration() {
            const data = {
                'user': this.name,
                'phone': this.phone,
                'email': this.email,
                'password': this.password,
            };

            axios.post('/site/signup',{
                'data': data
            }).then( (response) => {
                this.dialog = false;
                if(response.data.res_status) {
                    this.message = response.data.message.result_req;
                    this.name = '';
                    this.phone = '';
                    this.email = '';
                    this.password = '';
                    alert(this.message);
                } else {
                    let keys = Object.keys(response.data.message.error);
                    if(keys.length > 0) {
                        let row = '';
                        for (let key of keys) {
                            row += response.data.message.error[key] + '\n';
                        }
                        this.message = row;
                    } else {
                        this.message = response.data.message.error;
                    }
                    alert(this.message);
                }
            });

        },

        authorization() {

        }
    }
})