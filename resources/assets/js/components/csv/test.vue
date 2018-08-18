<template>
<v-content>

    <v-container fluid fill-height>

        <v-layout justify-center align-center>
            <v-btn flat color="primary" @click="test">Test</v-btn>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
export default {
    data() {
        return {
            tests: {},
        }
    },
    methods: {
        test() {
            axios.post('carbon')
                .then((response) => {
                this.tests = response.data
                })
                .catch((error) => {
                this.errors = error.response.data.errors
                })
        }
    }
}
</script>
