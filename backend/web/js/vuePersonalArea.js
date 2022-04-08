new Vue({
    el: '#personal-area',
    vuetify: new Vuetify(),

    data: () => ({
        file: '',
        uplFile: [],
        loaderUploadFile: false,
        userDatas: {
            'username': '',
            'email': '',
            'phone': '8-900-000-00-00',
        },
        validUserData: false,
        validUserDataCheckbox: false,
        loaderUserDatas: false,
        validateForm: false,

        usernameRule: [
            v => !!v || 'Поле Имя обязательно для заполнения.',
        ],
        emailRule: [
            v => !!v || 'Поле Email обязательно для заполнения',
            value => {
                const pattern = /.+@.+\..+/;
                if (/.+@.+\..+/.test(value)) {
                    return /.+@.+\..+/.test(value);
                } else {
                    return 'Электронная почта не корректная.';
                }
            },
            // v => /.+@.+\..+/.test(v) || 'Электронная почта не корректная.',
        ],
        phoneRule: [
            v => !!v || 'Поле Телефон обязательно для заполнения',
        ],
        avatar: '',
        loaderAvatar: false,
    }),

    created () {
        this.getUserData();
        this.getAvatar();
    },

    computed: {
        btnDisabled() {
            if (this.validUserData == true && this.validUserDataCheckbox == true) {
                return true;
            }
            return false;
        },

        editPersonalDate: function() {
            // return Object.assign({}, this.emailRule);
            return Object.assign({}, this.emailRule);
        },
    },

    watch: {
        validateForm: function(val) {
            console.log(val);
        }
    },

    methods: {
        /**
         * загрузка аватарки
         */
        uploadFile() {
            this.file = event.target.files[0];
            let formData = new FormData();
            let file = this.file;
            formData.append('file', file);
            this.loaderUploadFile = true;
            axios.post('/admin/personal-area/upload-file',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
        ).then( (response) => {
            if (response.data.res) {
                console.log(response);
                let avatars = document.getElementsByClassName('avatar-image');
                Object.values(avatars).forEach( (value) => {
                    this.updateAvatar(value, response.data.delImage, response.data.nameImage)
                })
                this.uplFile = [];
                this.getAvatar();
                this.loaderUploadFile = false;
            }
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * получение аватарки
         */
        getAvatar() {
            this.loaderAvatar = true;
            axios.post('/admin/personal-area/get-avatar-user')
                .then( (response) => {
                    this.avatar = response.data.avatar != '' ? response.data.avatar : response.data.dafault;
                    this.loaderAvatar = false;
                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        /**
         * обнлвление аватарок
         *
         * @param source
         * @param oldStr
         * @param newStr
         */
        updateAvatar(source, oldStr, newStr) {
            if (source != '') {
                let src = source.outerHTML;
                src = src.replaceAll(oldStr, newStr);
                source.outerHTML = src;
            }
        },

        /**
         * редактирование персональных данных
         */
        updateUserData() {
            this.loaderUserDatas = true;
            axios.post('/admin/personal-area/update-user-data', {
                'userDatas': this.userDatas
            }).then( (response) => {
                if (response.data.res) {
                    this.userDatas.username = response.data.userDatas.username;
                    this.userDatas.email = response.data.userDatas.email;
                    this.userDatas.phone = response.data.userDatas.phone;
                }
                this.validUserDataCheckbox == false;
                this.loaderUserDatas = false;
            }).catch( (error) => {
                this.loaderUserDatas = false;
                console.log(error.message)
            })
        },

        /**
         * получение перональных данных
         */
        getUserData() {
            this.loaderUserDatas = true;
            axios.post('/admin/personal-area/get-user-data')
                .then( (response) => {
                    this.loaderUserDatas = false;
                    this.userDatas.username = response.data.username;
                    this.userDatas.email = response.data.email;
                    this.userDatas.phone = response.data.phone;
                }).catch( (error) => {
                    this.loaderUserDatas = false;
                    console.log(error.message);
            })
        }

        // handleFileUploads() {
        //     this.files = this.$refs.files.files;
        //     let formData = new FormData();
        //     for (let i = 0; i < this.files.length; i++) {
        //         let file = this.files[i];
        //         formData.append('files[' + i + ']', file);
        //     }
        //     this.loader = true;
        //     axios.post('/admin-panel/interface-sitemap/upload-files',
        //         formData, {
        //             headers: {
        //                 'Content-Type': 'multipart/form-data'
        //             }
        //         }
        //     ).then((response) => {
        //         this.loader = false;
        //         if (!response.data.res) {
        //             alert('Ошибка загрузки, сообщите программистам!');
        //             console.log(response.data.error)
        //         }
        //         else {
        //             this.getListNameFile();
        //         }
        //     })
        //         .catch(function (error) {
        //             console.log(error);
        //         });
        // },
    }
})