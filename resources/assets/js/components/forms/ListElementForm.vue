<template>
    <div>
        <form :method="method" :action="action">

            <md-input-container>
                <label>Titel</label>
                <md-input v-model="el.title"></md-input>
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
                    this.$emit('update', response.data)

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
