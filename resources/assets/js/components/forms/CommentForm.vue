<template>
    <div>
        <form :method="method" :action="action">
        <fieldset><legend>Neuen Kommentar anlegen</legend>
            <md-input-container>
                <label>Kommentar</label>
                <md-textarea v-model="el.content"></md-textarea>
            </md-input-container>

            <md-button @click="onFormSubmit()">
                Speichern
            </md-button>
        </fieldset>
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
                    this.$set(this.el, 'content', '')
                    this.$emit('create', response.data)

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
