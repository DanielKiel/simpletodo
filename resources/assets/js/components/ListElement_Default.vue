<template>
     <md-list-item md-expand-multiple>
        <md-icon>whatshot</md-icon>
        <span>{{el.title}}</span>

        <md-list-expand>

            <md-card class="md-accent">

              <md-card-header>
                <div class="md-title">{{el.title}}</div>
                <div class="md-subhead">Version: {{el.version}} / Erstellt: {{el.created_at}} <span v-if="el.version > 1">/ Bearbeitet: {{el.updated_at}}</span></div>
              </md-card-header>

              <md-card-actions>
                <md-button @click="show = 'description'" :disabled="show === 'description'"> <md-icon>description</md-icon> </md-button>
                <md-button @click="show = 'comments'" :disabled="show === 'comments'"> <md-icon>comment</md-icon> </md-button>
                <md-button @click="show = 'history'" :disabled="show === 'history'"> <md-icon>history</md-icon> </md-button>
                <md-button @click="show = 'edit'" :disabled="show === 'edit'"> <md-icon>mode_edit</md-icon> </md-button>
              </md-card-actions>

              <md-card-content v-if="show === 'description'">
                {{el.description}}
              </md-card-content>

              <md-card-content v-if="show === 'comments'">

                <comment-form @create="onCommentCreated" :el="commentObj" method="POST" action="/api/comments/"></comment-form>


                 <md-card md-theme="comment_card" v-for="comment in el.comments" v-if="comment.version === el.version">
                  <md-card-header>
                    <div class="md-title">{{comment.by_user.name}}</div>
                    <div class="md-subhead">Am: {{comment.created_at}}</div>
                  </md-card-header>

                  <md-card-content>
                    {{comment.content}}
                  </md-card-content>
                </md-card>



              </md-card-content>

              <md-card-content v-if="show === 'history'">
                    <md-button v-for="(history, index) in el.history" :disabled="showHistory.id === history.id" class="md-fab md-clean md-mini" @click="setHistoryDisplay(index, history)">
                          {{history.version}}
                    </md-button>
                    <div v-if="historyDisplay">
                        <diff-history :original="getOriginal()" :history="showHistory"></diff-history>
                    </div>

              </md-card-content>

              <md-card-content v-if="show === 'edit'">
                <list-form :el="el" @update="onElementUpdate" method="PUT" :action="'/api/lists/' + el.id"></list-form>
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
                historyDisplay:true
            }
        },

        created() {
            if (this.el.history.length > 0) {
                this.showHistory = this.el.history[0]
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
            onElementUpdate(data) {
                this.el = data
            },

            onCommentCreated(data) {
                this.el.comments.push(data)
            },

            setHistoryDisplay(index, history) {
                this.showIndex = index
                this.showHistory = history
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
            }
        }
    }
</script>

<style>
</style>
