<template>
    <div>
        <div class="table-responsive" role="alert" v-show="display">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th v-for="head in header" class="text-left">
                            {{ head.title }}
                        </th>
                        <th class="text-right">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in data.data">
                        <td v-for="head in header">
                            {{ getValue(row, head.name) }}
                        </td>
                        <td class="td-actions text-right">
                            <vlink v-for="(link, idx) in getLinks()" :vclass="link.class" :href="getLinkUrl(link, row[link.replace])" :icon="link.icon" :ajax="link.ajax" :method="link.method" :target="link.target" :key="idx"></vlink>
                        </td>
                    </tr>
                </tbody>

            </table>

            {{ data.from }} - {{ data.to }} von {{ data.total }}

        </div>
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
            'header',
            'links'
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
            getValue(row, property) {
                return row[property]
            },

            getLinks() {
                if (this.links === undefined) {
                    return new Array
                }

                return JSON.parse(this.links)
            },

            getLinkUrl(link, id) {
                let str = link.href; console.log(id, link);
                return str.replace('_id_', id);
            },

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
