<template>
    <div id="app">
        <navBar />
        <router-view />
    </div>
</template>

<script>
import navBar from './components/navBar'
export default {
    components: {
        navBar,
    },
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap');
* {
    font-family: 'Work Sans', sans-serif;
}
</style>
