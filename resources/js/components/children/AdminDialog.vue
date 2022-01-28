<template>
    <v-dialog v-model="confirmDelDial" max-width="480">
        <v-card min-height="150">
            <v-card-title class="subtitle-1 admin white--text justify-center">Delete this user?</v-card-title>
            <v-card-text class="mt-5 subtitle-1">
                If you proceed to delete, the user will be deleted irrecoverably.
            </v-card-text>
            <v-card-actions class="pb-8 justify-center">
                <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                <v-btn dark color="primary" :loading="isUpdating" @click="deleteUser" width="40%">Yes, Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: [],
    data() {
        return {

        }
    },
}
</script>
