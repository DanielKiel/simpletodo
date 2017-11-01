<template>
    <div class="row row-eq-height">
        <md-card v-for="file in existingFiles" :key="file.id" class="col-md-3 margin-bottom">

            <md-card-media md-ratio="16:9" md-medium class="thumb">
                <img :src="'data:image/png;base64,' + file.source" alt="">
            </md-card-media>

            <md-card-media-actions>
                <md-switch v-model="file.published" @change="changeState(file)"  class="md-primary">Published</md-switch>
            </md-card-media-actions>

        </md-card>
        <div class="col-md-12">
            <form enctype="multipart/form-data">
                <md-subheader>Dateien hochladen</md-subheader>
                <md-input-container>
                    <input class="md-input" type="file" @change="filesChange($event.target.files)" multiple> </input>
                </md-input-container>
                <md-list v-if="files.length > 0" class="md-double-line">
                    <md-list-item v-for="file in files">
                        <div class="md-list-text-container">
                            <span>{{file.name}}</span>
                            <md-progress class="md-primary" :md-progress="progress[file.name]"></md-progress>
                        </div>
                    </md-list-item>
                </md-list>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'el'
        ],
        data() {
            return {
                existingFiles: [],
                files: [],
                formData: new FormData(),
                progress: {}
            }
        },

        created() {

            axios({
                method: 'GET',
                url: '/api/list-files/?listId=' + this.el.id
            }).then( response => {
                this.existingFiles = response.data
            } ).catch( err => {
                console.log(err)
            } )

        },
        methods: {
            changeState(file) {
                axios({
                    method: 'PUT',
                    url: '/api/list-files/' + file.id,
                    data: {
                        published: ! file.published
                    }
                }).then( response => {
                    console.log(response)
                } ).catch(err => {
                    console.log(err)
                })
            },
            save() {
                this.formData.set('lists_id', this.el.id)
                this.formData.set('version', this.el.version)

                axios({
                    method: 'POST',
                    url: '/api/list-files/',
                    data: this.formData
                }).then( response => {
                    let data = response.data
                    this.$set(this.progress, data.data.originalName, 100)

                    this.files.forEach( (file, index) => {
                        if (file.name === data.data.originalName) {
                            this.$delete(this.files, index)
                        }
                    } )

                    //next request to get source
                    this.existingFiles.push(data)

                } ).catch( err => {
                    console.log(err)
                })
            },

            filesChange(fileList) {
                this.files = []

                // handle file changes
                if (!fileList.length) return;

                // append the files to FormData
                Array
                  .from(Array(fileList.length).keys())
                  .map(x => {
                    this.files.push(fileList[x])
                    this.formData = new FormData()
                    this.progress[fileList[x].name] = 10
                    this.formData.append('upload', fileList[x], fileList[x].name);
                     // save it
                    this.save();
                  });
            },

            getFilePath(fileId) {
                axios({
                    method: 'GET',
                    url: '/api/list-files/' + fileId
                }).then( response => {
                    return response.data
                } )
            }
        }
    }
</script>

<style>

.row-eq-height {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display:         flex;
  flex-wrap: wrap;
}
.row-eq-height > [class*='col-'] {
  display: flex;
  flex-direction: column;
}

.margin-bottom {
    margin-bottom: 15px;
}
</style>