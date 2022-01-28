<template>
    <div>
        <v-list-item class="my-n5" @click="miniRev = !miniRev">
            <v-list-item-content class="mt-1 pa-3">
                <p class="body-1 primary--text">{{ rev.user && rev.user.fullname }} on {{ rev.published }}</p>
                <v-rating dense small color="primary" readonly v-model="rev.ratings" class="mt-2" half-increments></v-rating>
                <p class="body-1 font-weight-bold mt-2">{{ rev.title | capFirstLetter }}</p>
                <p class="body-1 black--text lighten-5 mb-3" :class="miniRev ? 'text-truncate' : ''">{{ rev.detail | capFirstLetter }}</p>
            </v-list-item-content>
        </v-list-item>
    </div>
</template>

<script>
export default {
    props: ['rev'],
    data() {
        return {
            miniRev: true
        }
    },
}
</script>
