<template>
    <md-layout class="md-align-center">
        <md-card>
            <md-card-content>
                <md-table-card>
                    <md-toolbar>
                        <h1 class="md-title">Liste: {{token}}</h1>

                        <md-button class="md-icon-button md-fab md-mini" @click="openDialog('elDialog')">
                          <md-icon>add</md-icon>
                        </md-button>
                    </md-toolbar>
                    <md-list>
                        <list-el-default v-for="(item, index) in data.data" :el="item"></list-el-default>
                    </md-list>
                    <md-table-pagination
                        :md-size="data.per_page"
                        :md-total="data.total"
                        :md-page="data.current_page"
                        md-label="Element"
                        md-separator="von"
                        :md-page-options="[5, 15, 50]"
                        @pagination="onPagination">
                    </md-table-pagination>
                </md-table-card>
            </md-card-content>
        </md-card>


        <md-dialog ref="elDialog">
          <md-dialog-content>
            <list-form method="POST" action="/api/lists" :el="newObj" @create="onElementCreated"></list-form>
          </md-dialog-content>

          <md-dialog-actions>
            <md-button class="md-primary" @click="closeDialog('elDialog')">X</md-button>
          </md-dialog-actions>
        </md-dialog>
    </md-layout>

</template>

<script>
    export default {
        props: [
            'api',
            'token'
        ],
        data () {
            return {
                url: this.api,
                display: false,
                error: '',
                newObj: {
                    title: '',
                    token: this.token,
                    description: ''
                },
                data: {}
            }
        },

        created() {
            this.fetchData()
        },

        mounted() {
            Echo.private(`lists.${this.token}`)
                .listen('ListsCreated', (e) => {

                    if (this.data.data.length < this.data.per_page) {
                        this.data.data.push(e.lists)
                        this.$forceUpdate()
                        console.log('pushed')
                    }

                    this.data.total = this.data.total + 1
                })
        },

        methods: {
            onElementCreated() {
                this.fetchData()

                this.closeDialog('elDialog')
            },
            show() {
                this.display = true
            },
            hide() {
                this.display = false
            },
            onPagination(event) {

                axios.get(this.data.path,{
                    params : {
                        page: event.page,
                        per_page: event.size
                    }
                }).then((response) => {
                    this.data = response.data
                })
            },

            shortTitle(title) {
                return title.slice(0, 25) + ' ...'
            },

            fetchData() {
                axios.get(this.url)
                    .then(response => {
                        this.loading = false
                        this.data = response.data

                        this.$set(this.newObj, 'weight', response.data.total)
                        this.show()
                    }, ( /* response */ ) => {
                    // error callback
                    this.loading = false
                    this.error = 'something went wrong'
                    flash(this.error, 'error')
                    this.hide()
                })
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
</style>
