<template>
    <div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-primary" @click="createCrew">
                    Create Crew
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <vue-good-table
                    :columns="columns"
                    :rows="crews"
                    :pagination-options="{enabled: true}"
                    :isLoading.sync="loading"
                    @on-row-click="onRowClick"
                >
                    <template slot="pagination-bottom" slot-scope="props">
                        <custom-pagination
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
                  <button type="button" class="btn btn-danger btn-round" @click.prevent.stop="deleteCrew(props.row.id)">
                      <i class="fa fa-close"></i>
                  </button>
                </span>
                        <span v-else>
                  {{ props.formattedRow[props.column.field] }}
                </span>
                    </template>
                </vue-good-table>
            </div>
        </div>
    </div>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import CustomPagination from "../CustomPagination";

export default {
    name: "CrewTable",
    components: {
        CustomPagination,
        VueGoodTable,
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
                    sortable: true,
                },
                {
                    field: 'owner_name',
                    label: 'Nom du propriétaire',
                    sortable: true,
                },
                {
                    field: 'name',
                    label: 'Nom',
                    sortable: true,
                },
                {
                    label: '',
                    field: 'delete',
                    sortable: false,
                    width: '30px'
                },
            ],
            idFilter: '',
            usernameFilter: '',
            nameFilter: '',
            crews: [],
            loading: false,
        }
    },
    mounted() {
        this.getCrews();
    },
    methods: {
        getCrews: function () {
            this.loading = true;
            axios.get('/api/crews', {
                page: this.page,
                id: this.idFilter,
                username: this.usernameFilter,
                name: this.nameFilter,
            })
                .then(res => {
                    this.crews = res.data.data;
                    this.totalPage = res.data.meta.last_page;
                    this.loading = false;
                })
        },
        updatePage: function (activePage) {
            this.page = activePage;
            this.getCrews();
        },
        deleteCrew: function (id) {
            axios.delete('/api/crew/' + id)
                .then(res => {
                    this.getCrews();
                })
                .catch(() => {
                    toastr.error('Un problème est survenu');
                })
        },
        onRowClick: function (params) {
            this.$store.commit('setModal', {
                type: 'createUpdateCrew',
                value: { show: true, crew_id: params.row.id, callback: this.getCrews }
            })
        },
        createCrew: function () {
            this.$store.commit('setModal', {
                type: 'createUpdateCrew',
                value: { show: true, callback: this.getCrews }
            })
        },
    }
}
</script>

<style scoped>

</style>
