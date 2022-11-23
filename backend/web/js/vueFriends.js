new Vue({
    el: '#appFriends',
    vuetify: new Vuetify(),

    data: () => ({
        authNameUser: null,
        nameUser: null,
        idAuthUser: null,
        loaderUsers: false,
        itemsUsers: [],
        // selectedUser: null,
        loaderMessages: false,
        itemsMessages: [],

        rules: [
            value => !!value || 'Required.',
            // value => (value || '').length <= 20 || 'Max 20 characters',
            // value => {
            //     const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            //     return pattern.test(value) || 'Invalid e-mail.'
            // },
        ],

        valid: true,
        message: '',
        paramMessage: null,
        nameRules: [
            v => !!v || 'Message is required',
        ],
        idUser: null,
        scrollInvoked: 0,

    //    server
        server: null,
    }),

    created() {
        this.server = new WebSocket('ws://localhost:8080');
        this.server.onopen = function (event) {
            $('#response').text("Connection established!");
        }


        this.getAuthNameUser();
        this.getUsers();
    },

    computed: {
        // chatWindowHeight() {
        //     return document.getElementById("heightWindowChat").scrollHeight;
        // },
        //
        // heightWindow() {
        //     console.log(this.$refs.header);
        //     return this.$refs;
        // }
    },

    watch: {
        // itemsMessages: function(newVal) {
            // this.itemsMessages = newVal;
            // if( newVal > 50) {
            //     this.itemsPerPage = this.maxRows;
            // }
            //
            // if (newVal < 1 || isNaN(newVal)) {
            //     this.itemsPerPage = this.minRow;
            // }
        // },

    },

    methods: {
        validate () {
            this.$refs.form.validate()
        },

        onmessage() {
            const ob = this;
            this.server.onmessage = function(event) {
                let response = JSON.parse(event.data);
                let myRequest = new XMLHttpRequest();

                if (response.type && response.type == 'chat') {
                    ob.message = '';
                    ob.itemsMessages.push( response.messages );
                } else if (response.message) {
                    $('#response').text(response.message);
                }
            };
        },

        /**
         * record username
         */
        setName() {
            if (this.authNameUser) {
                this.server.send(JSON.stringify({
                    'action': 'setName',
                    'name': this.authNameUser,
                }));
            } else {
                alert('Server error: no username');
            }
        },


        setMessage(){
            let date = new Date().format('Y-m-d h:i:s');
            this.paramMessage = {
                'action': 'chat',
                'message': this.message,
                'idUser': this.idUser,
                'idAuthUser': this.idAuthUser,
                'nameFrom': this.authNameUser,
                'nameTo': this.nameUser,
                'date': date
            };

            if (this.message) {
                this.server.send(JSON.stringify(this.paramMessage));
            } else {
                alert('Enter the message');
            }
        },

        getUsers() {
            this.loaderUsers = true;
            axios.post('/admin/chat/get-users', {})
                .then( (response) => {
                    this.itemsUsers = response.data;
                    this.loaderUsers = false;
                    this.message = '';
                }).catch( (error) => {
                    this.loaderUsers = false;
                    console.log(error.message)
                })
        },

        /**
         * получение чата с определенным аккаунтом
         *
         * @param id
         */
        getMessagesUser(id, name) {
            this.idUser = id;
            this.nameUser = name;
            this.loaderMessages = true;
            axios.post('get-messages-write-user', {
                'id': this.idUser
            })
                .then( (response) => {
                    this.itemsMessages = response.data;
                    this.loaderMessages = false;
                    this.scrollBottom();
                }).catch( (error) => {
                    this.loaderMessages = false;
                    console.log(error.message)
            })
        },

        /**
         * record message in db
         */
        sendMessage() {
            if (this.$refs.form.validate()) {
                this.setMessage();
                this.onmessage();
                this.scrollBottom();

                // axios.post('/admin/chat/send-message', {
                //     'id': this.idUser,
                //     'message': this.message
                // }).then( (response) => {
                //     if (!response.data) {
                //         console.log('Error sending message')
                //     }
                // }).catch( (error) => {
                //     console.log(error.message);
                // })
            }
        },

        /**
         * scroll messages
         */
        onScroll (event) {
            this.scrollInvoked = event.target.scrollTop;
        },

        /**
         * get username auth user
         */
        getAuthNameUser() {
            axios.post('/admin/chat/get-auth-username', {})
                .then( (response) => {
                    this.authNameUser = response.data.username;
                    this.idAuthUser = response.data.id;
                    this.setName();
                    this.onmessage();
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * scroll bottom window chat
         */
        scrollBottom() {
            setTimeout(function () {
                let scrollTarget = document.getElementById('scroll-target');
                scrollTarget.scrollTop = scrollTarget.scrollHeight;
            }, 500)
        }
    }

});