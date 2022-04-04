new Vue({
    el: '#personal-area',
    vuetify: new Vuetify(),

    data: () => ({
        file: '',
    }),

    methods: {
        uploadFile() {
            this.file = event.target.files[0];
            let formData = new FormData();
            let file = this.file;
            formData.append('file', file);
            axios.post('/admin/personal-area/upload-file',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
        ).then( (response) => {
                console.log(response)
            }).catch( (error) => {
                console.log(error.message)
            })
        },

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