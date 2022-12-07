new Vue({
    el: '#appMailing',
    vuetify: new Vuetify(),
    data: () => ({
        valid: true,
        subject: '',
        subjectRules: [
            v => !!v || 'Subject is required',
            v => (v && v.length <= 50) || 'Subject must be less than 50 characters',
        ],
        textBody: '',
        textBodyRules: [
            v => !!v || 'Textbody is required',
        ],
        dialogAlert: false,
        dialogAlertTitle: '',
    }),

    methods: {
        mailingList() {
            axios.post('/admin/mailing/mailing-list', {
                'subject': this.subject,
                'textbody': this.textBody
            }).then( (response) => {
                if (response.data) {
                    this.subject = '';
                    this.textBody = '';
                }
            }).catch( (error) => {
                this.dialogAlert = true;
                this.dialogAlertTitle = error.message;
            })
        },

        validate () {
            this.$refs.form.validate()
        },

        reset () {
            this.$refs.form.reset()
        },

        resetValidation () {
            this.$refs.form.resetValidation()
        },
    },
})