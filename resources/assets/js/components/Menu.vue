<template>
    <div class="md-flex-100">
        <md-toolbar md-theme="nav" class="md-flex-100">
            <div class="md-toolbar-container">
                <md-button class="md-icon-button" @click="toggleLeftSidenav">
                  <md-icon>menu</md-icon>
                </md-button>

                <span style="flex: 1;"></span>

                <md-button class="md-icon-button md-align-end">
                  <md-icon>search</md-icon>
                </md-button>
              </div>
        </md-toolbar>

        <md-sidenav class="md-left" ref="leftSidenav">
            <md-toolbar class="md-large">
              <div class="md-toolbar-container">
                <h3 class="md-title">Actions</h3>
              </div>
            </md-toolbar>

            <md-list>

                <md-list-item href="/backend/lists">
                    <span>Listen</span>
                    <md-button class="md-icon-button md-list-action">
                        <md-icon>list</md-icon>
                    </md-button>
                </md-list-item>

                <md-list-item href="/backend/account">
                    <span>Account</span>
                    <md-button class="md-icon-button md-list-action">
                        <md-icon>account_box</md-icon>
                    </md-button>
                </md-list-item>

                <md-list-item @click="logout()">
                    <span>Abmelden</span>
                    <md-button class="md-icon-button md-list-action">
                        <md-icon>exit_to_app</md-icon>
                    </md-button>
                </md-list-item>

            </md-list>

        </md-sidenav>
    </div>
</template>

<script>
    export default {
        methods: {
            toggleLeftSidenav() {
              this.$refs.leftSidenav.toggle();
            },

            logout() {
                axios.post('/logout')
                    .then( response => {
                        window.location.href = '/'
                    } )
            }
        }
    }
</script>

<style>
</style>