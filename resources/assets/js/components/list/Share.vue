<template>

    <div>
        <md-subheader>Nutzer einbeziehen</md-subheader>
        <div v-for="(user, index) in filteredUsers" class="row">
            <div><md-switch v-model="user.sharing" @change="share(user, index)" id="my-test8" name="my-test8">{{user.name}}</md-switch></div>
        </div>

    </div>

</template>

<script>
    export default {
        props: [
            'token'
        ],
        data () {
            return {
                users: [],
                filteredUsers: [],
                errors: []
            }
        },

        created() {
            this.fetchData()
        },

        watch: {
            users: function(newVal, oldVal) {
                newVal.forEach( (user, index) => {

                    let shared = user.shared.filter(obj => {
                        return obj.token === this.token
                    })

                    if (shared.length > 0) {
                        user.sharing = true
                    }
                    else {
                        user.sharing = false
                    }

                } )

                this.filteredUsers = newVal
            }
        },

        methods: {
            share(user, index) {
                if (user.sharing === false) {
                    axios({
                        method: 'GET',
                        url: '/api/share/' + this.token + '/' + user.id
                    }).then( response => {

                        this.$set(this.users[index], 'sharing', true)

                    } ).catch( err => {

                    })
                }
                else {
                    axios({
                        method: 'GET',
                        url: '/api/unshare/' + this.token + '/' + user.id
                    }).then( response => {

                        if (response.data.success === true) {
                            this.$set(this.users[index], 'sharing', false)
                        }



                    } ).catch( err => {

                    })
                }


            },
            fetchData() {
                axios({
                    method: 'GET',
                    url: '/api/users'
                }).then( response => {
                    this.users = response.data
                } ).catch( err => {

                })
            }
        }
    }
</script>

<style>
</style>
