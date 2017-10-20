<template>
    <div>
        <div class="phone-viewport">
            <md-toolbar>
            <span class="md-title">Liste: {{token}}</span>
            </md-toolbar>

            <md-list>
                <list-el-default v-for="(item, index) in data.data" :el="item"></list-el-default>
            </md-list>

            <md-layout md-flex="25" md-align="end">
                <md-table-pagination
                    :md-size="data.per_page"
                    :md-total="data.total"
                    :md-page="data.current_page"
                    md-label="Element"
                    md-separator="von"
                    :md-page-options="[5, 15, 50]"
                    @pagination="onPagination">
                </md-table-pagination>
            </md-layout>
        </div>
    </div>

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
                data: {}
            }
        },

        created() {
            this.fetchData()
        },

        methods: {
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
                }).then((r) => {
                    this.data = r.data
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

                        this.show()
                    }, ( /* response */ ) => {
                    // error callback
                    this.loading = false
                    this.error = 'something went wrong'
                    flash(this.error, 'error')
                    this.hide()
                })
            }
        }
    }
</script>

<style>
</style>
