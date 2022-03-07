<template>
    <span>
        <v-list-item :to="{name: 'MyPortFolioShow', params: {id: portfolio.id, slug:portfolio.slug}}" :key="portfolio.title" class="mt-n3 mb-n3 py-n3" light link>
            <v-list-item-avatar v-if="portfolio.file.length > 0" left size="70">
                <!-- <v-img :src="`/images/portfolios/${item.file[0].file}`" alt="Portfolio Image" aspect-ratio="1" contain></v-img> -->
                <v-img :src="pfImg" alt="Portfolio Image" aspect-ratio="1" contain></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title class="subtitle-1 primary--text font-weight-bold">{{ portfolio.title | capFirstLetter }}</v-list-item-title>
                <v-list-item-subtitle class="mt-2 subtitle-1 black--text lighten-3">{{ portfolio.detail | capFirstLetter | truncate(160) }}</v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>
    </span>
</template>

<script>
export default {
    props: ['portfolio'],
    data() {
        return {
            pfImg: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        header(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        }
    },
    methods: {
        getPfImage(){
            axios.get(this.api + `/auth/get_my_pf_image_from_s3/${this.portfolio.id}`, this.header)
            .then((res) => {
                this.pfImg = res.data[0]
            })
        }
    },
    created() {
        this.getPfImage()
    },
}
</script>
