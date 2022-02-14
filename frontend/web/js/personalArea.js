new Vue({
    el: '#appPersonalArea',
    vuetify: new Vuetify(),

    data: () => ({
        items: [
            { title: "Аккаунт", icon: "mdi-account", action: "Аккаунт"},
            { title: "Заказы", icon: "mdi-cart-outline", action: "Заказы"},
            { title: "Избранное", icon: "mdi-star-circle", action: "Избранное"},
            { title: "Выйти", icon: "mdi-logout", action: "Выйти" }
        ],
        titleMenu: 'Аккаунт',

        dialogWindow: false,
        dialogDelete: false,
        headers: [
            {
                text: '№',
                align: 'start',
                sortable: false,
                value: 'number',
            },
            { text: 'Название', value: 'title' },
            { text: 'Цена', value: 'price' },
            {
                text: 'Дата заказа',
                value: 'date',
                sortable: false
            },
            { text: 'Рейтинг', value: 'raiting' },
            {
                text: 'Действие',
                value: 'actions',
                sortable: false
            },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            number: 1,
            id: 1,
            name: '',
            price: 0,
            date: '',
            raiting: 0,
        },
        defaultItem: {
            number: 1,
            id: 1,
            name: '',
            price: 0,
            date: '',
            raiting: 0,
        },
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        loader: false,
        dialogView: false,
        cardViewItem: {},
        search: '',
        validEdit: true,
        personalDate: {
            'nameEdit': '',
            'emailEdit': '',
            'phoneEdit': ''
        },
        nameEdit: '',
        emailEdit: '',
        phoneEdit: '',
        nameEditRules: [
            v => !!v || 'Имя обязательно для заполнения.',
            v => (v && v.length <= 15) || 'Имя должно быть не больше 15 символов.',
        ],
        emailEditRules: [
            v => !!v || 'E-mail обязателен для заполнения.',
            v => /.+@.+\..+/.test(v) || 'Электронная почта не корректная.',
        ],
        phoneEditRules: [
            // v => !!v || 'Телефон обязателен для заполнения',
            // v => /^\+?[78][-\(]?\d{3}\)?-?\d{3}-?\d{2}-?\d{2}$/.test(v) || 'Телефон не корректный.'
        ],
        dialogEdit: false,
        validNameEdit: '',
        validEmailEdit: '',
        validPhoneEdit: '',
        dialogEditPassword: false,
        editPassword: '',
        newEditPassword: '',
        repeatNewEditPassword: '',
        editPasswordRules: [
            v => !!v || 'Введите ваш пароль.',
        ],
        newEditPasswordRules: [
            v => !!v || 'Введите новый пароль.',
            v => (v && v.length >= 10) || 'Поле пароль должно быть не менее 10 символов',
        ],
        validEdiPassword: true,
        dialogAlert: false,
        dialogAlertTitle: '',
        favoriteProducts: [],
        show: false,
        crtSelectedItem: '',
        dialogConfirm: false,
        dialogConfirmTitle: '',
        hotelId: '',
        onlyStringRules: [
            v => !!v || 'Введите данные для поиска.',
            v => /^[a-zA-Zа-яА-Я]+$/.test(v) || 'Вы ввели данные отличные от строковых.'
        ],
        searchList:[],
        // end personal
    }),

    mounted() {
        this.isGuest = Boolean(Number(document.getElementById('appRegistration').dataset.guest));
    },

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Создать новую запись' : 'Редактировать запись'
        },

        editPersonalDate: function() {
            return Object.assign({}, this.personalDate)
        },

        confirmPasswordRules() {
            // return this.newEditPassword === this.newEditPassword || "Пароли должны совпадать.";
            return this.repeatNewEditPassword;
        },
    },

    watch: {
        dialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.closeDelete()
        },
        editPersonalDate: {
            handler(val) {
                if (this.validNameEdit == val.nameEdit && this.validEmailEdit == val.emailEdit && this.validPhoneEdit == val.phoneEdit) {
                    this.validEdit = false;
                } else {
                    this.validEdit = true;
                }
            },
            deep: true
        },
    },

    created () {
        //this.initialize()
        this.getParamUser();
    },

    methods: {
        validate() {
            this.$refs.formValid.validate();
        },

        updateEdit() {
            const data = {
                'name': this.personalDate.nameEdit,
                'email': this.personalDate.emailEdit,
                'phone': this.personalDate.phoneEdit,
            }
            this.dialogEdit = true;

            axios.post('/user/update', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    this.personalDate.emailEdit = response.data.personalDate.email;
                    this.personalDate.phoneEdit = response.data.personalDate.phone;
                    this.personalDate.nameEdit = response.data.personalDate.username;
                    this.validNameEdit = response.data.personalDate.username;
                    this.validPhoneEdit = response.data.personalDate.phone;
                    this.validEmailEdit = response.data.personalDate.email;
                    this.dialogEdit = false;
                    this.validEdit = false;
                } else {
                    console.log(response.data.error);
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },
        /**
         * редактирование пароля пользователя
         */
        editPasswordUser() {
            const data = {
                'editPassword': this.editPassword,
                'newEditPassword': this.newEditPassword,
                'repeatNewEditPassword': this.repeatNewEditPassword
            }

            axios.post('/user/edit-password', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    this.dialogAlertTitle = 'Пароль был успешно сохранен';
                    this.dialogAlert = true;
                    this.editPassword = '';
                    this.newEditPassword = '';
                    this.repeatNewEditPassword = '';
                    this.dialogEditPassword = false
                } else {
                    this.dialogAlertTitle = response.data.message;
                    this.dialogAlert = true;
                }
            }).catch( (error) => {
                console.log(error.message)
            })

        },

        /**
         * получение параметров зарегестрированного пользователя
         */
        getParamUser() {
            this.loader = true;
            axios.post('/user/get-user', {})
                .then( (response) => {
                    if (response.data.res) {
                        this.personalDate.nameEdit = response.data.user.username;
                        this.personalDate.emailEdit = response.data.user.email;
                        this.personalDate.phoneEdit = response.data.user.phone;
                        this.validNameEdit = response.data.user.username;
                        this.validEmailEdit = response.data.user.email;
                        this.validPhoneEdit = response.data.user.phone;
                    }
                    this.validEdit = false;
                    this.loader = false;
                }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * откытие окна редактирования пароля
         */
        dialogEditPass() {
            this.dialogEditPassword = true;
        },
        /**
         * редактирование пароля пользователя
         */

        /**
         * обрабатывает действия события
         * @param action
         */
        menuActionClick(action) {
            if (action === "Выйти") {
                axios.post('/site/logout', {}).then( (response) => {
                    if (response.data.res) {
                        // location.reload();
                        location.href = '/';
                    }
                }).catch( (error) => {
                    console.log(error);
                })
            } else if (action === "Аккаунт") {
                this.titleMenu = 'Аккаунт';
                this.getParamUser();
            } else if (action === "Заказы") {
                this.titleMenu = 'Заказы';
                this.loader = true;
                axios.post('/orders/orders-user',{})
                    .then( (response) => {
                        Object.keys(response.data.customer).forEach( (key) => {
                            this.desserts[key] = {
                                'number': parseInt(key) + 1,
                                'id': response.data.customer[key].id,
                                'title': response.data.customer[key].title,
                                'price': response.data.customer[key].price,
                                'date': response.data.customer[key].date,
                                'raiting': response.data.customer[key].raiting,
                            }
                        })
                        this.loader = false;
                    }).catch( (error) => {
                    console.log(error.message);
                })
            } else if (action === "Избранное") {
                this.titleMenu = 'Избранное';
                this.loader = true;
                axios.post('/favorite/get-products', {})
                    .then( (response) => {
                        let favorites = []
                        Object.keys(response.data.modelHotel).forEach( (key) => {
                            favorites[key] = {
                                'full_name': response.data.modelHotel[key].full_name,
                                'hotel_id': response.data.modelHotel[key].hotel_id,
                                'hotel_name': response.data.modelHotel[key].hotel_name,
                                'label': response.data.modelHotel[key].label,
                                'location': JSON.parse(response.data.modelHotel[key].location),
                                'location_id': response.data.modelHotel[key].location_id,
                                'location_name': response.data.modelHotel[key].location_name,
                                'stars': response.data.modelHotel[key].stars,
                            }
                        })
                        this.favoriteProducts = favorites;
                        this.loader = false;
                    }).catch( (error) => {
                    console.log(error.message)
                })
            }
        },

        /**
         * модальное окно для подтверждения удаления отеля из избранного
         *
         * @param string hotel
         */
        deleteConfirmFavorite (hotel_id) {
            this.dialogConfirmTitle = 'Вы действительно хотите удалить отель из избранных?';
            this.dialogConfirm = true;
            this.hotelId = hotel_id;
        },

        /**
         * Удаление отелей из избранного
         *
         * @param string hotel
         */
        deleteFavoriteHotel() {
            const data = {
                'hotel_id': this.hotelId
            };
            this.loader = true;
            axios.post('/favorite/delete-favorite', {
                'data': data
            }).then( (response) => {
                if (response.data.res) {
                    const favorites = Object.assign({}, this.favoriteProducts);
                    Object.keys(favorites).forEach( (key) => {
                        if (data.hotel_id == favorites[key].hotel_id) {
                            this.favoriteProducts.splice(key, 1);
                        }
                        this.show = !this.show;
                        this.crtSelectedItem = key
                    });
                    this.loader = false;
                    this.dialogConfirm = false;
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        initialize () {
            this.desserts;
        },

        editItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem (item) {
            this.editedIndex = this.desserts.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            const data = {
                'deleteItem': this.editedItem.id
            };
            axios.post('/orders/delete-item', {
                'data': data
            }).then( (response) => {
                if(response.data.res) {
                    this.desserts.splice(this.editedIndex, 1);
                    Object.keys(this.desserts).forEach( (key) => {
                        this.desserts[key].number = parseInt(key) + 1;

                    });
                    this.closeDelete()
                } else {
                    console.log('Удаление сейчас не возможно!');
                }
            }).catch( (error) => {
                console.log(error.message);
            });
        },

        /**
         * Показ карточки заказа
         *
         * @param item
         */
        viewItem(item) {
            this.cardViewItem = Object.assign({}, item);
            this.dialogView = true;
        },

        close () {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },

        searchHotels(event) {
            let result =  this.search.match(/(^\D+$)/g);
            if (result != null) {
                this.searchList = this.desserts.filter((hotel) => {
                    if (hotel.title.toLowerCase().indexOf(event.toLowerCase()) != -1) {
                        return hotel;
                    }
                })
            } else {
                this.searchList = [];
            }
        }
    }
})