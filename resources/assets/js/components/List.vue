<template>
    <div>
         <div class="col-md-1">
            <ul class="list-group clearfix">
              <li class="list-group-item"><button type="button" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-plus"></i></button></li>
              <li class="list-group-item"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-floppy-save"></i></button></li>
              <li class="list-group-item"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-user"></i></button></li>
            </ul>
         </div>

         <div class="col-md-11">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Liste: {{token}}</div>
                </div>
                <div class="panel-body">

                </div>
            </div>

            <div v-for="(el, index) in data.data" class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-right clearfix">
                        <div class="btn-toolbar" role="toolbar" aria-label="...">
                            <div class="btn-group" role="group" aria-label="list-comment">
                                <button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-comment"></i></button>
                            </div>
                            <div class="btn-group" role="group" aria-label="list-edit">
                                <button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>
                            </div>
                            <div class="btn-group" role="group" aria-label="list-move">
                                <button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-arrow-up"></i></button>
                                <button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-arrow-down"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-title">{{el.title}}</div>
                </div>
                <div class="panel-body">
                    <div>
                        {{el.description}}
                    </div>

                    <div v-if="el.history.length > 0">
                        <h4>Versionierung</h4>
                        <div v-for="history in el.history">
                            <button class="btn btn-xs btn-default"><span class="badge">{{history.version}}</span></button>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <h4>Kommentare</h4>
                    <div v-for="comment in el.comments" v-if="comment.version === el.version">
                        <div class="clearfix">
                            <div class="pull-right">
                                <div class="label label-info">{{comment.by_user.name}} (am {{comment.created_at}})</div>
                            </div>
                            {{comment.content}}
                        </div>
                    </div>
                </div>
            </div>
         </div>

        <div>{{ data.from }} - {{ data.to }} von {{ data.total }}</div>

        <nav aria-label="Page navigation" v-if="data.total > data.per_page">
            <ul class="pagination">
                <li v-bind:class="[data.prev_page_url != null ? '' : 'disabled']">
                    <a href="#" aria-label="Previous" @click.prevent="selectPage(1)">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li v-bind:class="[data.prev_page_url != null ? '' : 'disabled']">
                    <a href="#" aria-label="Previous" @click.prevent="showPrevPage">
                        <span aria-hidden="true">&lsaquo;</span>
                    </a>
                </li>
                <li class="page-item" v-for="n in getPages()" :class="{ 'active': n == data.current_page }">
                    <a class="page-link" href="#" @click.prevent="selectPage(n)">{{ n }}</a>
                </li>
                <li v-bind:class="[data.next_page_url != null ? '' : 'disabled']">
                    <a aria-label="Next" @click.prevent="showNextPage">
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                </li>
                <li v-bind:class="[data.next_page_url != null ? '' : 'disabled']">
                    <a aria-label="Next" @click.prevent="selectPage(data.last_page)">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
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
                data: []
            }
        },

        created() {
            this.fetchData()
        },

        methods: {
            selectPage(n) {
                axios.get(this.data.path,{
                    params : {
                        page:n
                    }
                }).then((r) => {
                    this.data = r.data
                    flash('Page ' + this.data.current_page + ' fetched')
                })
            },
            getPages: function() {

                var start = 1,
                    end = this.data.last_page,
                    current = this.data.current_page,
                    pages = [],
                    index

                for (index = start; index <= end; index++) {
                    pages.push(index)
                }

                return pages
            },
            show() {
                this.display = true
            },
            hide() {
                this.display = false
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
