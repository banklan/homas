<template>
    <div>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="files && files.length > 0">
                <v-carousel height="400">
                    <v-carousel-item v-for="(item, i) in pfFiles" :key="i" :src="item" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                </v-carousel>
            </template>
            <v-img v-else src="/images/shared/no-image.png" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
        </template>
    </div>
</template>

<script>
export default {
    props: ['files'],
    data() {
        return {
            isLoading: false,
            pfFiles: []
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
    methods: {
        getPfImages(){
            axios.get(this.api + `/get_pf_images_from_s3/${this.$route.params.id}`, this.header)
            .then((res) => {
                this.pfFiles = res.data
            })
        }
    },
    created(){
        this.getPfImages()
    }
}
</script>
