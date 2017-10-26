<template>
    <div>
        <form :method="method" :action="action">
            <md-input-container v-if="el.token === '' || el.token === undefined ">
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
                <label>Beschreibung</label>
                <md-textarea v-model="el.description"></md-textarea>
            </md-input-container>

            <md-button @click="onFormSubmit()">
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
                errors: []
            }
        },

        created() {

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
