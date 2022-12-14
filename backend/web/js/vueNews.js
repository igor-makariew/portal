new Vue({
    el: '#appNews',
    vuetify: new Vuetify(),
    data: () => ({
        apiKey: 'ca6e5ba339d034392f27a5967673ecce',
        url: 'https://gnews.io/api/v4/search?q=example&token=ca6e5ba339d034392f27a5967673ecce&lang=en&country=us&max=10',
        show: false,
        articles: 0,
        totalArticles: 0,
        //start preloder
        interval: {},
        value: 0,
        //end preloder
        loader: false,
        crtSelectedItem: '',
        countArticles: '20',
        valid: true,
        countArticlesRules: [
            v => !!v || 'Count articles is required',
        ],
    }),

    created() {
        this.getNews();
    },

    methods:{
        getNews() {
            console.log(this.countArticles);
             this.loader = true;
             axios.get('https://gnews.io/api/v4/search?q=example&token=ca6e5ba339d034392f27a5967673ecce&lang=en&country=us&max='+this.countArticles, {})
                 .then( (response) => {
                     this.totalArticles = response.data.totalArticles;
                     this.articles = response.data.articles;
                     this.loader = false;
                     console.log(response);
            }).catch( (error) => {
                console.log(error.message);
             })

            // вывод ошибки
            // axios.get('https://gnews.io/api/v4/search?q=example&token=ca6e5ba339d034392f27a5967673ecce&lang=en&country=us&max=10')
            //     .catch(function (error) {
            //         if (error.response) {
            //             console.log(error.response.data);
            //             console.log(error.response.status);
            //             console.log(error.response.headers);
            //         }
            //     });
        },

        validate () {
            this.$refs.form.validate()
        },

        getTest() {
            console.log(this.countArticles);
        }
    }

})