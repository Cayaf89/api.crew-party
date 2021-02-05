<template>
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-else>
        <span class="sr-only">Loading...</span>
    </span>
</template>

<script>
export default {
    name: "Loader",
    props: {
        classNames: {
            type: String,
            default: 'spinner-grow spinner-grow-sm'
        }
    }
}
</script>

<style scoped>

</style>
