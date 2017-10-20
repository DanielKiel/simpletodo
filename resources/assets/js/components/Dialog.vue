<template>
    <span>
        <md-button class="md-fab md-clean md-mini" :id="id" @click="openDialog('dialog_' + id)">
          {{button}}
        </md-button>

        <md-dialog :md-open-from="'#' + id" :md-close-to="'#' + id" :ref="'dialog_' + id">
          <md-dialog-title>{{title}}</md-dialog-title>

          <md-dialog-content>{{content}}</md-dialog-content>

          <md-dialog-actions>
            <md-button class="md-primary" @click="closeDialog('dialog_' + id)">Ok</md-button>
          </md-dialog-actions>
        </md-dialog>
    </span>
</template>

<script>
    export default {
        props: [
            'button',
            'id',
            'title',
            'content',
            'diffs'
        ],
        methods: {
            openDialog(ref) {
              this.$refs[ref].open()
            },
            closeDialog(ref) {
              this.$refs[ref].close()
            }
        }
    }
</script>

<style>
</style>