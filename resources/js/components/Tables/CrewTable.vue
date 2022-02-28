<template>
    <vue-good-table
        mode="remote"
        :columns="columns"
        :rows="crews"
        :totalRows="totalPage"
        :isLoading.sync="loading"
        :pagination-options="{enabled: true}"
        :search-options="searchOptions"
        @on-row-click="onRowClick"
        @on-search="searchCrew"
    >
        <template slot="pagination-bottom" slot-scope="props">
            <custom-pagination
                class="mt-2"
                :nb-page-total="totalPage"
                :on-update-page="updatePage">
            </custom-pagination>
        </template>
        <template slot="loadingContent">
            <span class="spinner-grow spinner-grow-sm text-blue" role="status" aria-hidden="true">
                <span class="sr-only">Loading...</span>
            </span>
        </template>
        <template slot="table-row" slot-scope="props">
            <span v-if="props.column.field === 'delete'">
                <loading-button type="button" class="btn btn-danger btn-round"
                                :loading="deleting" :on_click="() => deleteCrew(props.row.id)">
                    <i class="fa fa-close"></i>
                </loading-button>
            </span>
            <span v-else>
                {{ props.formattedRow[props.column.field] }}
            </span>
        </template>
    </vue-good-table>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import CustomPagination from "../CustomPagination";

export default {
    name: "CrewTable",
    store: Store,
    components: {
        CustomPagination,
        VueGoodTable,
    },
    props: {
        crews: Array,
        getCrews: Function,
        loading: Boolean
    },
    data: function () {
        return {
            page: 1,
            totalPage: 1,
            columns: [
                {
                    field: 'id',
                    label: 'ID',
                    type: 'number',
                    sortable: false,
                },
                {
                    field: 'owner_name',
                    label: 'Nom du propriétaire',
                    sortable: false,
                },
                {
                    field: 'name',
                    label: 'Nom',
                    sortable: false,
                },
                {
                    label: '',
                    field: 'delete',
                    sortable: false,
                    width: '30px'
                },
            ],
            searchOptions: {
                enabled: true,
                placeholder: 'Rechercher',
            },
            filter: '',
            deleting: false
        }
    },
    mounted() {
        this.$props.getCrews();
    },
    methods: {
        updatePage: function (activePage) {
            this.page = activePage;
            this.$props.getCrews(this.page, this.filter);
        },
        deleteCrew: function (id) {
            this.$dialog
                .confirm('Voulez-vous vraiment supprimer ce crew ?')
                .then(function(dialog) {
                    this.deleting = true;
                    axios.delete('/api/crew/' + id)
                        .then(res => {
                            this.deleting = false;
                            this.$props.getCrews(this.page, this.filter);
                            this.$store.state.updateSideBarCrews();
                            toastr.error('Le crew a bien été supprimé');
                        })
                        .catch(() => {
                            toastr.error('Un problème est survenu');
                        })
                })
        },
        onRowClick: function (params) {
            location.href = '/crew/' + params.row.id;
        },
        searchCrew: function (params) {
            this.filter = params.searchTerm
            this.page = 1
            this.$props.getCrews(this.page, params.searchTerm);
        }
    }
}
</script>

<style scoped>

</style>
