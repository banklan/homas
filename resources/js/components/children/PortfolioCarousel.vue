<template>
    <div>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="files.length > 0">
                <v-carousel height="400">
                    <v-carousel-item v-for="(item, i) in files" :key="i" :src="`/images/portfolios/${item.file}`" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                </v-carousel>
            </template>
        </template>
    </div>
</template>

<script>
export default {
    props: ['files'],
    data() {
        return {
            isLoading: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
    },
}
</script>
