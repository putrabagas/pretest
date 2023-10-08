<template>
    <Navigation/>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script>
import Navigation from '../components/Navigation.vue'

export default {
  components: {
    Navigation
  }
}
</script>