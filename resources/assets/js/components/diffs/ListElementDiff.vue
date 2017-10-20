<template>
    <div class="diff">
        <h4>Diff Version {{original.version}} zu Version {{history.version}}</h4>
        <div v-html="titleDiff"></div>
        <div v-html="descDiff"></div>
    </div>
</template>

<script>
    export default {
        props: [
            'original',
            'history'
        ],
        data () {
            return {
                jsdiff: require('diff'), // see here to implement some stuff: https://github.com/kpdecker/jsdiff,
            }
        },
        computed: {

            titleDiff() {
                let string = ''

                let diff = this.jsdiff.diffWords(this.original.title, this.history.title)

                diff.forEach(function(part){
                   let color = part.added ? 'red' : part.removed ? 'green' : 'grey';
                   if (part.value !== undefined) {
                        string = string + '<span style="color: ' + color + ';">' + part.value + '</span>'
                   }

                });

                return string
            },
            descDiff() {
                let string = ''

                let diff = this.jsdiff.diffChars(this.original.description, this.history.description)

                diff.forEach(function(part){
                   let color = part.added ? 'red' : part.removed ? 'green' : 'grey';

                   if (part.value !== undefined) {
                       string = string + '<span style="color: ' + color + ';">' + part.value + '</span>'
                   }
                });

                return string
            }
        },
        methods: {

        }
    }
</script>

<style>
    .diff {
        background: #fff;
        padding: 8px;
    }

    .diff h4 {
        color: #000;
    }
</style>