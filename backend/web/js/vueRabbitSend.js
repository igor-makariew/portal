new Vue({
    el: '#appRabbitSend',
    vuetify: new Vuetify(),
    data: () => ({
        dataServer: '',
        message: '',
        storage: false,
        rootDir: '',
        currentDir: '',
        // отмена предыдущего запроса
        requests: [],
        request: null,
        //
        dialog: false,
        nameFolder: '',
        valid: true,
        nameFolderRules: [
            v => !!v || 'Name folder is required',
        ],
        siteMaps: [],
        files: [],
        dirSize: 0,
        tmpTitle: '',
        // context menu
        showMenu: false,
        x: 0,
        y: 0,
        items: [
            { title: 'Поделиться' },
            { title: 'Настроить доступ' },
            { title: 'Скачать' },
            { title: 'Переименовать' },
            { title: 'Переместить' },
            { title: 'Копировать' },
            { title: 'Удалить' },
        ],
        countIndex: 0,
        mdiIcons: [
            'mdi-monitor-share',
            'mdi-account-details',
            'mdi-arrow-down-bold-box-outline',
            'mdi-rename-box',
            'mdi-folder-move-outline',
            'mdi-content-copy',
            'mdi-delete-forever'
        ],

        keyMenuTask: [
            'monitor-share',
            'account-details',
            'arrow-down-bold-box-outline',
            'rename-box',
            'folder-move-outline',
            'content-copy',
            'delete-forever'
        ],

        nameFunc: '',
        dialogCopy: false,
        dialogCopySiteMaps: [],
        currentDirDialog: '',
        fileCopy: '',
        from: '',
        to: '',
        dialogRename: false,
        renameFolder: '',
    }),

    created() {
        this.getDiskBusySpace();
        this.isDir();
        this.getScandir(true);
    },

    mounted() {

    },

    computed: {
        shortTitle() {
            let length = 15;
            return (str) => {
                if (length >= `${str.length}`) {
                    return `${str.slice(0, length)}`;
                } else {
                    return `${str.slice(0, length)}` + '...';
                }
            }
        },
    },

    methods:{
        setDataServer() {
            axios.post('/admin/rabbit-send/client', {
                'data': this.message
            }).then( (response) => {
                console.log(response)
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        Send() {
            this.setDataServer();
        },

        createStorage() {
            axios.post('/admin/rabbit-send/storage', {
                'data': 'test'
            }).then( (response) => {
                if (response.data.data) {
                    alert(response.data.response)
                }

            }).catch( (error) => {
                console.log(error.message)
            })
        },

        isDir() {
            axios.post('/admin/rabbit-send/is-dir')
                .then( (response) => {
                    this.storage = response.data.isDir;
                    this.rootDir = response.data.rootDir;
                    this.currentDir = response.data.rootDir;
                    this.currentDirDialog = response.data.rootDir;
                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        createDir() {
            if (this.request) {
                this.cancel();
            }

            const axiosSource = axios.CancelToken.source();
            this.request = {
                cancel: axiosSource.cancel,
                msg: "Loading..."
            };

            axios.post('/admin/rabbit-send/create-dir', {
                'data': this.nameFolder
            }, {
                cancelToken: axiosSource.token
            }).then( (response) => {
                if (response.data) {
                    this.nameFolder = '';
                    this.dialog = false;
                    this.getScandir(true);
                }

                this.clearOldRequest("Success");
            })
        },

        /**
         * получение каталогов и файлов
         */
        getScandir(check) {
            let checkParam = check
            axios.post('/admin/rabbit-send/get', {
                'data': checkParam
            })
                .then( (response) => {
                    if (checkParam) {
                        this.siteMaps = response.data;
                    } else {
                        this.dialogCopySiteMaps = response.data;
                    }

                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        /**
         * Выбор дочерних директорий
         *
         * @param dir
         */
        dirSelectionChildren(dir, trigger) {
            let data = {
                'dir': dir,
                'trigger': trigger
            };
            axios.post('/admin/rabbit-send/dir-selection-children', {
                'data': data
            }).then( (response) => {
                if (response.data.trigger) {
                    this.currentDir = response.data.path;
                    this.getScandir(true);
                } else {
                    this.currentDirDialog = response.data.pathDialog;
                    this.getScandir( false);
                }
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * выбор родительских директорий
         *
         * @param dir
         */
        dirSelectionParent(dir, check) {
            let data = {
                'dir': dir,
                'check': check
            };
            axios.post('/admin/rabbit-send/dir-selection-parent', {
                'data': data
            }).then( (response) => {
                if (check) {
                    this.currentDir = response.data.path;
                    this.getScandir(true);
                } else {
                    this.currentDirDialog = response.data.pathDialog;
                    this.getScandir(false);
                }

            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * загрузка файлов на сервер
         */
        handleFileUploads() {
            // снятие фокуса для предотвращения перезапска загрузки файлов
            this.$refs.files.blur();
            let formData = new FormData();
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }
            axios.post('/admin/rabbit-send/upload-files', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( (response) => {
                if (response.data.response) {
                    this.files = [];
                    this.getScandir(true);
                    this.getDiskBusySpace();
                } else {
                    this.files = [];
                    if (response.data.message != '') {
                        alert(response.data.message);
                    }
                }
            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * получение занятого пространства диска
         */
        getDiskBusySpace() {
            axios.post('/admin/rabbit-send/free-disk-space')
                .then( (response) => {
                    this.dirSize = response.data;
                }).catch( (error) => {
                    console.log(error.message)
            })
        },

        /**
         * полное название файла
         *
         * @param title
         * @param index
         */
        fullTitle(title, index) {
            this.tmpTitle = this.$refs[index][0].innerText;
            this.$refs[index][0].innerText = title;
        },

        /**
         * урезанное название файла
         *
         * @param index
         */
        shortiesTitle(index) {
            this.$refs[index][0].innerText = this.tmpTitle;
        },

        /**
         * отображение контексного меню
         *
         * @param e
         * @param index
         */
        show (e, index) {
            e.preventDefault();
            this.countIndex = index
            this.showMenu = false
            this.x = e.clientX
            this.y = e.clientY
            this.$nextTick(() => {
                this.showMenu = true
            })
        },

        /**
         * выбранный пункт контексного меню
         *
         * @param task
         * @param currentDir
         * @param filename
         */
        taskMenu(task, currentDir, filename) {
            let nameFunction = this.keyMenuTask[task];
            let words = nameFunction.split('-');
            let name = this.capitalize(words[1]);
            let nameFunc = words[0] + name;
            this.nameFunc = words[0];
            this.callFunc(nameFunc, currentDir, filename)
        },

        /**
         * вызов функции
         *
         * @param nameFunc
         * @param currentDir
         * @param filename
         */
        callFunc(nameFunc, currentDir, filename) {
            this[nameFunc](currentDir, filename);
        },

        /**
         * удаление файла из категории
         *
         * @param currentDir
         * @param filename
         */
        deleteForever(currentDir, filename) {
            let data = {
                'currentDir': currentDir,
                'filename': filename
            };
            axios.post('/admin/rabbit-send/delete', {
                data: data
            }).
                then( (response) => {
                    console.log(response);
                    if (response.data.response) {
                        this.getScandir(true);
                    } else {
                        let res = confirm('Do you delete current directory and attached files?');
                        if (res) {
                            axios.post('/admin/rabbit-send/delete', {
                                data: data
                            }).then( (response) => {
                                console.log(response.data);
                                if (response.data) {
                                    this.getScandir(true);
                                }
                            }).catch( (error) => {
                                console.log(error.message);
                            });
                        }
                        // console.log(response.data.errorException.error);
                        // console.log(response.data.errorException.line);
                    }

            }).catch( (error) => {
                console.log(error.message)
            })
        },

        /**
         * параметры файла
         *
         * @param currentDir
         * @param filename
         */
        paramsFile(currentDir, filename) {
            if (this.nameFunc != 'rename') {
                this.dialogCopy = true;
            }

            let data = {
                'filename': filename,
                'dir': currentDir
            };

            this.getScandir(false);
            this.fileCopy =  filename;
            this.from =  currentDir;
            this.to = this.currentDirDialog;
        },

        /**
         * запись скопированного файла
         *
         * @param currentDir
         * @param filename
         */
        contentCopy(currentDir, filename) {
            this.paramsFile(currentDir, filename);
        },

        /**
         * перемещение файла
         *
         * @param currentDir
         * @param filename
         */
        folderMove(currentDir, filename) {
            this.paramsFile(currentDir, filename);
        },

        renameBox(currentDir, filename) {
            this.dialogRename = true;
            this.paramsFile(currentDir, filename);
        },

        /**
         * действие над файлом
         *
         * @param to
         */
        action(to) {
            let data = {
                'filecopy': this.fileCopy,
                'from': this.from,
                'to': to,
                'action': this.nameFunc,
                'rename': this.renameFolder
            };

            axios.post('/admin/rabbit-send/effect', {
                'data': data
            }).then( (response) => {
                this.getScandir(false);
                this.getScandir(true);
                this.dialogCopy = false;
                this.dialogRename = false;
            }).catch( (error) => {
                console.log(error.message);
            })
        },

        /**
         * делает первую букву в слове заглавной
         *
         * @param s
         * @returns {string}
         */
        capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        },


        /**
         * Отмена предыдущего
         * запроса
         */
        cancel() {
            if (this.request != null) {
                this.request.cancel();
            }

            this.clearOldRequest("Cancelled");
        },

        /**
         * Очитска запроса от данных
         * @param {String} msg
         */
        clearOldRequest(msg) {
            if (this.request) {
                this.request.msg = msg;
                this.requests.push(this.request);
                this.request = null;
            }
        },

        validate () {
            this.$refs.form.validate()
        },
    }

});