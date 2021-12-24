<template>
    <v-snackbar
        v-model="snackbar"
        :timeout="5000"
        top 
        right
    >
        {{ msg }}
    </v-snackbar>
</template>


<script>
export default {
    data (){
        return{
            snackbar : true
        }
    },
    props: ['msg'],
}
</script>