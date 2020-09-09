<template>
    <div id="app">
        <navbar />
        <router-view />
    </div>
</template>

<script>
import navbar from './components/navBar'
export default {
    components: {
        navbar,
    },
}
</script>