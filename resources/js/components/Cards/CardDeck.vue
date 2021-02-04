<template>
    <div class="row">
        <div class="col-12">
            <div class="card-columns">
                <div class="card" v-for="item in data" @click="() => onCardClick(item.id)">
                    <img :src="item.logo" class="card-img-top" alt="cover">
                    <div class="card-body">
                        <h5 class="card-title">{{ item.name }}</h5>
                        <div class="card-text" v-html="item.description"></div>
                    </div>
                </div>
            </div>

            <infinite-loading :identifier="infiniteId" @infinite="getData">
                <div slot="no-results" class="text-center text-muted">
                    Aucun utilisateur pour le moment
                </div>
                <div slot="no-more"></div>
            </infinite-loading>
        </div>
    </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading";


export default {
    name: "CardDeck",
    components: {
        InfiniteLoading
    },
    props: {
        infiniteId: Number,
        data: Array,
        getData: Function,
        onCardClick: Function
    },
}
</script>

<style scoped>
.card:hover {
    cursor: pointer;
}
</style>
