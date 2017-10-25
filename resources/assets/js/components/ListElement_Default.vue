<template>
     <md-list-item md-expand-multiple>
        <md-icon>whatshot</md-icon>
        <span>{{el.title}}</span>

        <md-list-expand>

            <md-card>

              <md-card-header>
                <div class="md-title">{{el.title}}</div>
                <div class="md-subhead">Version: {{el.version}} / Erstellt: {{el.created_at}} <span v-if="el.version > 1">/ Bearbeitet: {{el.updated_at}}</span></div>
              </md-card-header>



              <md-card-content>
                <md-layout md-gutter="8">
                    <md-layout md-flex="10">
                        <md-list class="md-dense">
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'description'" :disabled="show === 'description'"> <md-icon>description</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="openDialog('commentDialog')" :disabled="show === 'comments'"> <md-icon>comment</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'history'" :disabled="show === 'history'"> <md-icon>history</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'edit'" :disabled="show === 'edit'"> <md-icon>mode_edit</md-icon> </md-button>
                            </md-list-item>
                        </md-list>

                    </md-layout>
                    <md-layout md-flex="60">

                        <div v-if="show === 'description'" v-on:mouseup="getHighlighted()" class="description">
                            <div v-html="description" :id="'desc_' + el.id"></div>
                        </div>

                        <div v-if="show === 'history'">
                            <md-button v-for="(history, index) in el.history" :disabled="showHistory.id === history.id" class="md-fab md-clean md-mini" @click="setHistoryDisplay(index, history)">
                                  {{history.version}}
                            </md-button>
                            <div v-if="historyDisplay">
                                <diff-history :original="getOriginal()" :history="showHistory"></diff-history>
                            </div>
                        </div>

                        <div v-if="show === 'edit'" class="md-flex-100">
                            <list-form :el="el" @update="onElementUpdate" method="PUT" :action="'/api/lists/' + el.id"></list-form>
                        </div>

                    </md-layout>
                    <md-layout md-flex="30">

                        <md-subheader>Kommentare</md-subheader>

                        <md-card class="md-flex-100" md-theme="comment_card" v-if="comments.length === 0">
                            (Keine Kommentare vorhanden)
                        </md-card>

                        <md-card class="md-flex-100" md-theme="comment_card" v-for="comment in comments">
                          <md-card-header>
                            <div class="md-title">{{comment.by_user.name}}</div>
                            <div class="md-subhead">Am: {{comment.created_at}} (Für Version {{comment.version}})</div>
                          </md-card-header>

                          <md-card-content>
                            {{comment.content}}
                          </md-card-content>
                          <md-card-actions v-if="show === 'description'">
                            <md-button @click="getCommentedMark(comment)"><md-icon>search</md-icon></md-button>
                          </md-card-actions>
                        </md-card>

                    </md-layout>

                </md-layout>


                <md-dialog ref="commentDialog">
                  <md-dialog-title>Kommentiere Textabschnitt</md-dialog-title>

                  <md-dialog-content>
                    <comment-form @create="onCommentCreated" :el="commentObj" method="POST" action="/api/comments/"></comment-form>
                  </md-dialog-content>

                  <md-dialog-actions>
                    <md-button class="md-primary" @click="closeDialog('commentDialog')">X</md-button>
                  </md-dialog-actions>
                </md-dialog>
              </md-card-content>

            </md-card>

        </md-list-expand>
    </md-list-item>

</template>

<script>
    export default {
        props: [
            'el'
        ],
        data () {
            return {
                show: 'description',
                showHistory: {},
                showIndex: 0,
                historyDisplay:true,
                allComments: this.el.comments,
                comments: [],
                description: this.el.description
            }
        },

        created() {
            if (this.el.history.length > 0) {
                this.showHistory = this.el.history[0]
            }

            this.allComments.forEach( (comment) => {
                if (this.el.version === comment.version) {
                    this.comments.push(comment)
                }
            } )
        },

        watch: {
            'show': function(newVal, oldVal) {
               this.recalculateComments()
            }
        },

        computed: {
            commentObj() {
                return {
                     lists_id: this.el.id,
                     version: this.el.version,
                     content: '',
                     position: {
                        description: 1
                     }
                }
            }
        },
        methods: {
            recalculateComments() {
                if (this.show === 'description') {
                    this.comments = new Array
                    this.allComments.forEach( (comment) => {
                        if (this.el.version === comment.version) {
                            this.comments.push(comment)
                        }
                    } )
                }
                else if (this.show === 'history') {
                    this.comments = new Array
                    this.allComments.forEach( (comment) => {
                        if (this.showHistory.version === comment.version) {
                            this.comments.push(comment)
                        }
                    } )
                }
            },
            onElementUpdate(data) {
                this.el = data
            },

            onCommentCreated(data) {
                this.allComments.push(data)
                this.recalculateComments()

                this.closeDialog('commentDialog')
            },

            getCommentedMark(comment) {
                let desc = new Mark('#desc_' + this.el.id)
                let search = ''

                if (comment.position.description === undefined) {
                    return
                }

                let position = comment.position.description

                if (position !== undefined && position.text !== undefined) {
                    search = position.text
                }

                desc.mark(search, {
                    //accuracy: "exactly",
                    separateWordSearch: false
                });
            },



            getHighlighted() {
                let selection = document.getSelection()

                let selectedText = selection.toString()
                let selectedRange = selection.getRangeAt(0)

                this.$set(this.commentObj, 'position', {
                    description: {
                        start: selectedRange.startOffset,
                        end: selectedRange.endOffset,
                        text: selectedText
                    }
                })

                this.openDialog('commentDialog')
            },

            setHistoryDisplay(index, history) {
                this.showIndex = index
                this.showHistory = history

                this.recalculateComments()
            },

            getOriginal() {
                let index = this.showIndex

                if (this.el.history.length === 1) {
                    return this.el
                }

                if (index === 0) {
                    return this.el
                }

                return this.el.history[index - 1]
            },

            getDiff(history) {
                let jsdiff =  require('diff')

                return  {
                    titleDiff: jsdiff.diffChars(this.el.title, history.title),
                    descDiff: jsdiff.diffChars(this.el.description, history.description)
                }
            },

            openDialog(ref) {
                if (this.$refs[ref] !== undefined) {
                     this.$refs[ref].open();
                }

            },
            closeDialog(ref) {
                if (this.$refs[ref] !== undefined) {
                    this.$refs[ref].close();
                }

            }
        }
    }
</script>

<style>

.commented {
    background: yellow;
}

</style>
