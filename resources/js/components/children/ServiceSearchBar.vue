<template>
    <div class="d-flex">
        <v-text-field tile solo placeholder="Search for service..." v-model="q" dense prepend-inner-icon="search" @keypress.enter="search"></v-text-field>
        <v-btn dark tile class="primary" @click.prevent="search">Go</v-btn>
    </div>
</template>

<script>
export default {
    data() {
        return {
            q: ''
        }
    },
    methods: {
        search(){
            if(this.q.trim() !== ''){
                this.$router.push({name: 'ServiceSearchResult', query:{q: this.q}})
            }
        }
    }
}
</script>
