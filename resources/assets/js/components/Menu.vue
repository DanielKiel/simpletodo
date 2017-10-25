<template>
    <div>

        <md-toolbar md-theme="nav" class="md-flex-100">
            <md-button class="md-icon-button" @click="toggleLeftSidenav">
              <md-icon>menu</md-icon>
            </md-button>

            <h2 class="md-title">My App</h2>

            <md-menu>
              <md-button md-menu-trigger>Bottom Right</md-button>

              <md-menu-content>
                <md-menu-item>My Item 1</md-menu-item>
                <md-menu-item>My Item 2</md-menu-item>
                <md-menu-item>My Item 3</md-menu-item>
              </md-menu-content>
            </md-menu>
        </md-toolbar>

        <md-sidenav class="md-left" ref="leftSidenav" @open="open('Left')" @close="close('Left')">
            <md-toolbar class="md-large">
              <div class="md-toolbar-container">
                <h3 class="md-title">Sidenav content</h3>
              </div>
            </md-toolbar>
        </md-sidenav>
    </div>
</template>

<script>
    export default {
        methods: {
            toggleLeftSidenav() {
              this.$refs.leftSidenav.toggle();
            }
        }
    }
</script>

<style>
</style>