app.component('entity-file', {
    template: $TEMPLATES['entity-file'],

    setup() {
        // os textos estão localizados no arquivo texts.php deste componente 
        const text = Utils.getTexts('entity-file')
        return { text }
    },

    computed: {
        file() {
            return this.entity.files?.[this.groupName] || null
        },
    },

    props: {
        entity: {
            type: Entity,
            required: true
        },
        groupName: {
            type: String,
            required: true
        },
        title: {
            type: String,
            default: ""
        },
        uploadFormTitle: {
            type: String,
            required: false
        },
        required: {
            type: Boolean,
            require: false
        },
        editable: {
            type: Boolean,
            require: false
        },
        disableName: {
            type: Boolean,
            default: false
        },
        enableDescription: {
            type: Boolean,
            default: false
        },
        classes: {
            type: [String, Array, Object],
            required: false
        },
    },

    data() {
        return {
            formData: {},
            newFile: {},
        }
    },

    methods: {
        setFile() {
            this.newFile = this.$refs.file.files[0];
        },

        upload(popover) {
            let data = {
                description: this.formData.description,
                group: this.groupName,
            };

            this.entity.upload(this.newFile, data).then((response) => {
                this.$emit('uploaded', this);
                popover.close()
            });

            return true;
        },
    },
});
