<template>

    <md-button :href="href" :class="vclass" :title="title" :target="getTarget()" v-on:click='onClick'>
        <md-icon>{{icon}}</md-icon>
    </md-button>

</template>

<script>
    export default {
        props: [
            'href',
            'vclass',
            'title',
            'icon',
            'method',
            'ajax',
            'target'
        ],

        methods: {
            onClick(event) {
                if (this.ajax === false) {
                    return
                }

                event.preventDefault()

                axios({
                  method: this.method,
                  url: this.href
                }).then( (response) => {
                      // success
                      flash('Success.')
                      location.reload()
                  })
                  .catch( (error) => {
                    flash('Error')
                  })
            },

            getTarget() {

                if (this.target === undefined) {
                    return '_self'
                }

                return this.target
            }
        }
    }
</script>

<style>
</style>
