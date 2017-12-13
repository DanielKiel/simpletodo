<template>
    <div>
        <form :method="method" :action="action">
            <md-input-container v-if="el.token === '' || el.token === undefined || el.token === null">
                <label>Name der Liste</label>
                <md-input v-model="el.token" required></md-input>
            </md-input-container>

            <md-subheader v-if="method === 'POST'">
                Listenelement anlegen
            </md-subheader>

            <md-input-container>
                <label>Titel</label>
                <md-input v-model="el.title" required></md-input>
            </md-input-container>

            <md-input-container>
                <quill-editor v-model="el.description"
                                ref="myQuillEditor"
                                :options="editorOption">
                </quill-editor>
            </md-input-container>

            <md-button @click="onFormSubmit()" md-theme="button" class="md-raised md-primary">
                Speichern
            </md-button>

        </form>
    </div>

</template>

<script>
    export default {
        props: [
            'el',
            'method',
            'action'
        ],
        data () {
            return {
                errors: [],
                editorOption: {
                  // some quill options
                },
                newList: false
            }
        },

        created() {
            if (this.el.token === '' || this.el.token === undefined || this.el.token === null) {
                this.newList = true
            }
        },

        methods: {
            onFormSubmit() {
                axios({
                    method: this.method,
                    url: this.action,
                    data: this.el
                }).then((response) => {
                    // success
                    this.errors = []

                    if (this.method === 'POST') {
                        this.$emit('create', response.data)

                        if (this.newList === true) {
                            window.location.href = '/backend/list/' + encodeURI(response.data.token)
                        }
                    }

                    if (this.method === 'PUT') {
                        this.$emit('update', response.data)
                    }

                })
                .catch((error) => {
                    let response = error.response
                    this.errors = response.data.errors
                })
            },
        }
    }
</script>

<style>
</style>
