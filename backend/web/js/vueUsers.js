new Vue({
    el: '#appUsers',
    vuetify: new Vuetify(),

    data: () => ({
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
                console.log(error.message)
            })

        }

    }
})