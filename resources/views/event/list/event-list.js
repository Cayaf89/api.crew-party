
const app = new Vue({
    el: "#event-list-page",
    components: {

    },
    data: function () {
        return {
            events: [],
            totalPage: 1,
            loadingCrews: false
        }
    },
    methods: {
        getEvents: function (page, filter) {
            this.loading = true;
            axios.get('/api/events', {
                params: {
                    page: page,
                    filter: filter,
                }
            })
                .then(res => {
                    this.events = res.data.data;
                    this.totalPage = res.data.meta.last_page;
                    this.loading = false;
                })
        },
    }
})
