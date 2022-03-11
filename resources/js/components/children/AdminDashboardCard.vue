<template>
    <v-card light elevation="12" raised width="100%" min-height="200" class="mx-auto scroll">
        <v-card-title class="subtitle-1 justify-center">{{ title }}</v-card-title>
        <v-card-text>
            <table class="table table-hover table-striped">
                <thead></thead>
                <tbody>
                    <tr>
                        <th>No of Admins</th>
                        <td>2</td>
                    </tr>
                    <tr>
                        <th>No of Users</th>
                        <td>25</td>
                    </tr>
                    <tr>
                        <th>No of Services</th>
                        <td>55</td>
                    </tr>
                </tbody>
            </table>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    props: ['title']
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
</style>
