import CrewTable from "../../js/components/Tables/CrewTable";

const app = new Vue({
    el: '#dashboard-page',
    components: {
        CrewTable
    },
    store: Store,
    methods: {
        openModalCreateUpdateCrew: function () {
            this.$store.commit('setModal', {
                type: 'createUpdateCrew',
                value: { show: true }
            })
        }
    }
})
