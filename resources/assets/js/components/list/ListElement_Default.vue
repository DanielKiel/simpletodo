<template>
     <md-list-item md-expand-multiple v-bind:class="{ updated: updated }">
        <md-icon>whatshot</md-icon>
        <span>{{data.title}}</span>

        <md-list-expand>

            <md-card>

              <md-card-header>
                <div class="md-title">{{data.title}}</div>
                <div class="md-subhead">Version: {{data.version}} / Erstdatalt: {{data.created_at}} <span v-if="data.version > 1">/ Bearbeitet: {{data.updated_at}}</span></div>
              </md-card-header>



              <md-card-content>
                <div class="row">
                    <div class="col-md-1">
                        <md-list class="md-dense">
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'description'" :disabled="show === 'description'"> <md-icon>description</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="openDialog('commentDialog')" :disabled="show === 'comments'"> <md-icon>insert_comment</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'history'" :disabled="show === 'history'"> <md-icon>history</md-icon> </md-button>
                            </md-list-item>
                            <md-list-item>
                                <md-button class="md-icon-button md-list-action" @click="show = 'edit'" :disabled="show === 'edit'"> <md-icon>mode_edit</md-icon> </md-button>
                            </md-list-item>
                        </md-list>

                    </div>
                    <div class="col-md-7">

                        <div v-if="show === 'description'">
                            <div v-on:mouseup="getHighlighted()"" v-html="data.description" :id="'desc_' + data.id" class="description"></div>

                            <list-files :el="el"></list-files>
                        </div>

                        <div v-if="show === 'history'">
                            <md-button v-for="(history, index) in data.history" :disabled="showHistory.id === history.id" class="md-fab md-clean md-mini" @click="setHistoryDisplay(index, history)">
                                  {{history.version}}
                            </md-button>
                            <div v-if="historyDisplay">
                                <diff-history :original="getOriginal()" :history="showHistory"></diff-history>
                            </div>
                        </div>

                        <div v-if="show === 'edit'" class="md-flex-100">
                            <list-form :el="data" @update="onElementUpdate" method="PUT" :action="'/api/lists/' + data.id"></list-form>
                        </div>

                    </div>
                    <div class="col-md-4">

                        <md-subheader>Kommentare</md-subheader>

                        <md-card class="md-flex-100" md-theme="comment_card" v-if="comments.length === 0">
                            <md-card-content>
                                <div class="md-caption">(Keine Kommentare vorhanden)</div>
                            </md-card-content>

                        </md-card>

                        <md-card class="md-flex-100" md-theme="comment_card" v-for="comment in comments">
                          <md-card-header>
                            <div class="md-title">{{comment.by_user.name}}</div>
                            <div class="md-subhead">Am: {{comment.created_at}} (Für Version {{comment.version}})</div>
                          </md-card-header>

                          <md-card-content>
                            <div class="md-body-1">{{comment.content}}</div>

                            <md-subheader>Antworten</md-subheader>
                            <div v-for="reply in comment.replies" :class="getReplyClass(reply)">
                                <div class="md-caption">von {{reply.by_user.name}} am {{reply.created_at}}</div>
                                <div class="md-body-1">{{reply.content}}</div>
                                <hr/>
                            </div>

                          </md-card-content>

                          <md-card-actions>
                              <md-button @click="reply(comment.id)"> <md-icon>reply</md-icon> </md-button>
                          </md-card-actions>
                          <md-card-actions v-if="hasCommentFooter(comment) === true">
                            <md-button  @click="getCommentedMark(comment)"><md-icon>search</md-icon></md-button>
                            <span class="md-caption"><span class="marked-text">Markierter Text:</span> {{comment.position.description.text}}</span>
                          </md-card-actions>
                        </md-card>

                    </div>

                </div>


                <md-dialog ref="commentDialog">
                  <md-dialog-title>{{commentDialogTitle}}</md-dialog-title>

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
                data: this.el,
                comments: [],
                replies: {},
                updated: false,
                commentDialogTitle: 'Kommentiere Textabschnitt'
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
            show: function(newVal, oldVal) {
               this.recalculateComments()
            },

            updated: function(newVal, oldVal) {
                if (newVal === true) {
                    setTimeout(
                        function() {
                            this.updated = false
                        }, 5000)
                }
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

        mounted() {
            Echo.private(`lists.${this.el.id}`)
                .listen('ListsUpdated', (e) => {
                    this.data = e.lists
                    this.updated = true

                })

            Echo.private(`comments.${this.el.id}`)
                .listen('CommentCreated', (e) => {
                    let comment = e.comment

                    comment['__new'] = true

                    if (comment.reply_to === undefined || comment.reply_to === null || comment.reply_to === '') {
                        this.allComments.push(comment)
                    }
                    else {
                        this.replies[comment.reply_to] = comment;
                    }

                    this.recalculateComments()
                })
        },

        methods: {
            recalculateComments() {
                if (this.show === 'description') {
                    this.comments = new Array
                    this.allComments.forEach( comment => {
                        if (this.replies[comment.id] !== undefined) {
                            comment.replies.push(this.replies[comment.id])

                            this.$delete(this.replies, comment.id)
                        }

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
                let el = this.data

                this.data.history.unshift(el)

                this.data = data
            },

            onCommentCreated(data) {
                if (data.reply_to === undefined || data.reply_to === null || data.reply_to === '') {
                    this.allComments.push(data)
                }
                else {
                    this.replies[data.reply_to] = data;
                }

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

            reply(commentId) {
                this.$set(this.commentObj, 'reply_to', commentId)

                this.openDialog('commentDialog')
            },

            getReplyClass(reply) {
                let cssClass = 'row col-md-offset-1'

                if (reply['__new'] === true) {
                    cssClass = cssClass + ' new'
                }

                return cssClass
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
                this.$delete(this.commentObj, 'reply_to')

                if (this.$refs[ref] !== undefined) {
                    this.$refs[ref].close();
                }

            },
            hasCommentFooter(comment) {
                if (this.show !== 'description') {
                    return false
                }

                if (comment.position === undefined) {
                    return false
                }

                if (comment.position.description === undefined) {
                    return false
                }

                if (comment.position.description.text === undefined) {
                    return false
                }

                return true
            }
        }
    }
</script>

<style>

.commented {
    background: yellow;
}

.marked-text {
    font-weight: bold;
    color: #000;
}

.updated {
    border: 2px solid red;
}

.new {
    border: 2px solid red;
}

</style>
