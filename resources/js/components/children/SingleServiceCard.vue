<template>
    <v-card light elevation="4" min-height="400" class="mt-5 mb-4" :to="{name: 'ServiceShow', params:{id: service.id, slug: service.slug}}">
        <v-img v-if="service.image" :src="`/images/services/${service.image}`" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
        <v-img v-else src="/images/shared/no-image.png" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
        <v-card-title class="subtitle-1 justify-center mt-2 text-truncate">{{ service.title | capFirstLetter}}</v-card-title>
        <v-card-text class="text-center body-1 grey--text mt-n3">{{ service.location.state }} | {{ service && service.category.name }} </v-card-text>
        <v-card-actions class="justify-space-around">
            <v-chip v-if="service.is_approved" class="ma-2" color="green darken-3" text-color="white">
                <v-avatar left><v-icon>mdi-checkbox-marked-circle</v-icon></v-avatar>
                Verified
            </v-chip>
            <v-rating v-model="service.ratings" readonly dense color="primary"></v-rating>
        </v-card-actions>
        <v-card-actions class="mt-3 mb-0 px-3 primary white--text py-5 align-center">
            <v-avatar>
                <v-img v-if="service.user.picture" :src="`/images/profiles/users/${service.user.picture}`" height="50"></v-img>
                <v-img v-else src="/images/shared/user7.jpg" height="50"></v-img>
            </v-avatar>
                <div class="ml-3">{{ service.user.fullname }}</div>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
   props:['service']
}
</script>

<style lang="scss" scoped>
    a{
        text-decoration: none !important;
    }
</style>
