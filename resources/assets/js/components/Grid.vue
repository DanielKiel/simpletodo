<template>
    <md-table-card>
        <md-toolbar>
            <vlink v-for="(link, idx) in toolbar" :vclass="link.class" :href="link.href" :icon="link.icon" :ajax="link.ajax" :method="link.method" :target="link.target" :key="idx"></vlink>
        </md-toolbar>
        <md-table v-show="display">
          <md-table-header>
            <md-table-row>
              <md-table-head v-for="head in header"> {{ head.title }} </md-table-head>
              <md-table-head md-numeric> Actions </md-table-head>
            </md-table-row>
          </md-table-header>

          <md-table-body>
            <md-table-row v-for="(row, index) in data.data" :key="index">
              <md-table-cell v-for="head in header"> {{ getValue(row, head.name) }} </md-table-cell>
              <md-table-cell>
                    <vlink v-for="(link, idx) in getLinks()" :vclass="link.class" :href="getLinkUrl(link, row[link.replace])" :icon="link.icon" :ajax="link.ajax" :method="link.method" :target="link.target" :key="idx"></vlink>
              </md-table-cell>
            </md-table-row>
          </md-table-body>
        </md-table>

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
</template>

<script>
    export default {
        props: [
            'api',
            'header',
            'links',
            'toolbar'
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
                let str = link.href;
                return str.replace('_id_', id);
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
