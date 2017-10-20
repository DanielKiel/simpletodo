<template>
    <div class="alert-flash alert alert-info alert-with-icon" data-notify="container" v-show="show">
        <i class="material-icons shake" data-notify="icon">notifications</i>
        <span data-notify="message">{{ body }}</span>
    </div>
</template>

<script>
    export default {
        props: [
            'message'
        ],
        data () {
            return {
                body: '',
                show: false
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message)
            }

            window.events.$on('flash', message => this.flash(message))
        },

        methods: {
            flash(message) {
                window.flash(message)
                /*
                this.body = message
                this.show = true

                this.hide()
                */
            },
            hide() {
                setTimeout(() => {
                    this.show = false
                }, 3000)
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        width: 350px;
        right: 25px;
        bottom: 25px;
    }
    
    .shake {
        -webkit-animation-name: spaceboots;
        -webkit-animation-duration: 0.8s;
        -webkit-transform-origin:50% 50%;
        -webkit-animation-iteration-count: infinite;
        -webkit-animation-timing-function: linear;
    }

    @-webkit-keyframes spaceboots {
    0% { -webkit-transform: translate(2px, 1px) rotate(0deg); }
    10% { -webkit-transform: translate(-1px, -2px) rotate(-1deg); }
    20% { -webkit-transform: translate(-3px, 0px) rotate(1deg); }
    30% { -webkit-transform: translate(0px, 2px) rotate(0deg); }
    40% { -webkit-transform: translate(1px, -1px) rotate(1deg); }
    50% { -webkit-transform: translate(-1px, 2px) rotate(-1deg); }
    60% { -webkit-transform: translate(-3px, 1px) rotate(0deg); }
    70% { -webkit-transform: translate(2px, 1px) rotate(-1deg); }
    80% { -webkit-transform: translate(-1px, -1px) rotate(1deg); }
    90% { -webkit-transform: translate(2px, 2px) rotate(0deg); }
    100% { -webkit-transform: translate(1px, -2px) rotate(-1deg); }
}
</style>
