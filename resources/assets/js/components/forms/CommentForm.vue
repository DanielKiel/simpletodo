<template>
    <div>
        <form :method="method" :action="action">
        <fieldset><legend>Neuen Kommentar anlegen</legend>
            <md-input-container>
                <label>Kommentar</label>
                <md-textarea v-model="obj.content"></md-textarea>
            </md-input-container>

            <md-button md-theme="button" class="md-raised md-primary" @click="onFormSubmit()">
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
                errors: [],
                obj: this.el
            }
        },
        watch: {
            'obj.position': function(newVal, oldVal) {
               console.log(newVal)
            }
        },

        methods: {
            onFormSubmit() {
                axios({
                    method: this.method,
                    url: this.action,
                    data: this.obj
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
