<template>
    <div>
        <md-toolbar md-theme="nav" class="md-flex-100">
            <div class="md-toolbar-container">
                <md-button class="md-icon-button" @click="toggleLeftSidenav">
                  <md-icon>menu</md-icon>
                </md-button>

                <span style="flex: 1;"></span>

                <md-button class="md-icon-button">
                  <md-icon>search</md-icon>
                </md-button>

                <md-button class="md-icon-button">
                  <md-icon>filter_list</md-icon>
                </md-button>
              </div>
        </md-toolbar>

        <md-sidenav class="md-left" ref="leftSidenav">
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