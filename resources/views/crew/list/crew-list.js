import CrewTable from "../../../js/components/Tables/CrewTable";

const app = new Vue({
    el: "#crew-list-page",
    components: {
        CrewTable
    },
    data: function () {
        return {
            crews: [],
            totalPage: 1,
            loadingCrews: false
        }
    },
    methods: {
        getCrews: function (page, filter) {
            this.loading = true;
            axios.get('/api/crews', {
                params: {
                    page: page,
                    filter: filter,
                }
            })
                .then(res => {
                    this.crews = res.data.data;
                    this.totalPage = res.data.meta.last_page;
                    this.loading = false;
                })
        },
    }
})
