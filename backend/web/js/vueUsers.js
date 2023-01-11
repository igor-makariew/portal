new Vue({
    el: '#appUsers',
    vuetify: new Vuetify(),

    data: () => ({
        valid: true,
        tab: null,
        items: [
            'Список пользователей',
            'Роли и права',
        ],
        links: {
            'users': true,
            'roles': false,
        },
        loaderRoles: false,
        validRoles: false,
        createRolePermission: {
            'newRole': '',
            'descriptionRole': '',
            'newPermission': '',
            'descriptionPermission': '',
            'userId': null
        },
        newRoleRule: [
            v => !!v || 'Данное поле должно быть заполнено'
        ],
        descriptionRoleRule: [
            v => !!v || 'Данное поле должно быть заполнено'
        ],
        newPermissionRule: [
            v => !!v || 'Данное поле должно быть заполнено'
        ],
        descriptionPermissionRule: [
            v => !!v || 'Данное поле должно быть заполнено'
        ],
        date:  (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        event: 'stock',
        events: ['stock', 'holidays', 'sale'],
        descriptionEvent: '',
        fieldDescriptionEvent: [
            v => !!v || 'Данное поле должно быть заполнено',
            v => (v && v.length) > 30 || 'Количество должно быть больше 30 символов'
        ]
    }),

    methods: {
        pageNavigation(event) {
            if (event.target.innerText == 'СПИСОК ПОЛЬЗОВАТЕЛЕЙ') {
                this.links.users = true;
                this.links.roles = false;
            } else if (event.target.innerText == 'РОЛИ И ПРАВА') {
                this.links.users = false;
                this.links.roles = true;
            }
        },

        createRoles() {
            this.loaderRoles = true;
            axios.post('/admin/users/create-role-permission', {
                'data': this.createRolePermission
            }).then( (response) => {
                this.loaderRoles = false;
                console.log(response);
            }).catch( (error) => {
                this.loaderRoles = false;
                console.log(error.message)
            })

        },

        setEvent() {
            this.loaderRoles = true;
            let data = {
                'date': this.date,
                'descriptionEvent': this.descriptionEvent,
                'event': this.event
            };
            axios.post('/admin/users/createcalendarevent', {
                'data': data
            }).then( (response) => {
                this.loaderRoles = false;
                if (response) {
                    alert('ok');
                    this.descriptionEvent = '';
                } else {
                    alert('error')
                }
                console.log(response);
            }).catch( (error) => {
                this.loaderRoles = false;
                console.log(error.message);
            })
        }

    }
})