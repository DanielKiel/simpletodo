<template>
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
            <div v-for="reply in comment.replies" class="row col-md-offset-1">
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

        <md-dialog ref="commentDialog">
          <md-dialog-title>{{commentDialogTitle}}</md-dialog-title>

          <md-dialog-content>
            <comment-form @create="onCommentCreated" :el="commentObj" method="POST" action="/api/comments/"></comment-form>
          </md-dialog-content>

          <md-dialog-actions>
            <md-button class="md-primary" @click="closeDialog('commentDialog')">X</md-button>
          </md-dialog-actions>
        </md-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            'el'
        ],

        data () {
            return {
                allComments: this.el.comments,
                comments: [],
                replies: {},
                updated: false,
                commentDialogTitle: 'Kommentiere Textabschnitt'
            }
        },

        created() {
            this.allComments.forEach( (comment) => {
                if (this.el.version === comment.version) {
                    this.comments.push(comment)
                }
            } )
        },

        watch() {
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
            Echo.private(`comments.${this.el.id}`)
                .listen('CommentCreated', (e) => {
                    let comment = e.comment
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
        }
    }
</script>

<style>
</style>
