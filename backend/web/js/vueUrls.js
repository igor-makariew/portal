new Vue({
    el: '#appUrls',
    vuetify: new Vuetify(),

    data: () => ({
        // start url
        nameUrl: '',
        listNameUrls: [],
        // end url
    }),

    created() {
        this.setUrlsTextOnServer();
    },

    methods: {
        /**
         * получение массива уникальных url
         */
        getMenuUrls() {
            let ulAdminMenu = document.getElementById('adminMenu');
            let listA = ulAdminMenu.querySelectorAll('a');
            listA.forEach( (value, key) => {
                if (value.href != '') {
                    if (this.checkUniqUrlName(this.getUrlText(value.href, value.origin)) == undefined) {
                        this.listNameUrls[key] = this.getUrlText(value.href, value.origin);
                    }
                }
            })
        },

        /**
         * получение имени контроллера и экшена
         *
         * @param baseUrl
         * @returns {string}
         */
        getUrlText(href, origin){
            let strUrl = href.replace(origin, "");
            let arrayUrl = strUrl.split('/');
            let count = arrayUrl.length;
            this.nameUrl = arrayUrl[count - 2] + '/' + arrayUrl[count - 1];
            return this.nameUrl;
        },

        /**
         * проверка на уникальность
         *
         * @param name
         * @returns {T}
         */
        checkUniqUrlName(name) {
            return this.listNameUrls.find( (item) => {
                return item == name;
            })
        },

        /**
         * отправка данных на сервер
         */
        setUrlsTextOnServer() {
            this.getMenuUrls();
            axios.post('/admin/site/urls', {
                'listNameUrls': this.listNameUrls
            }).then().catch( (error) => {
                console.log(error.message)
            })
        }
    }
})