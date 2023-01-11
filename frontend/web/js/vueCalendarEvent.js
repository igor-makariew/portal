new Vue({
    el: '#appCalendarEvent',
    vuetify: new Vuetify(),

    data: () => ({
        paramsColor: [0, 0, 0],
        today: '2023-01-20',
        tracked: { },
        colors: ['#c01211', '#3afb28', '#fd3a0f'],
        category: ['sale', 'holidays', 'stock'],
    }),

    created () {
        this.getCalendarEvents();
    },

    methods: {
        getCalendarEvents() {
            axios.post('/site/get-calendar-event')
                .then( (response) => {
                    this.today = response.data.today;
                    response.data.calendarEvent.forEach((element, index) => {
                        this.paramsColor[this.getKeyByValue(this.category, element['event'])] = 100;
                        this.tracked[element['created_at']] = this.paramsColor;
                        this.paramsColor = [0, 0, 0];
                    })
                }).catch( (error) => {
                    console.log(error.message);
            })
        },

        /**
         * получение ключа по значению массива
         *
         * @param object
         * @param value
         * @returns {string}
         */
        getKeyByValue(object, value) {
            return Object.keys(object).find(key => object[key] === value);
        }

}
})