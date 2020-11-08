<template>
    <vue-good-table
        mode="remote"
        :columns="columns"
        :rows="users"
        :totalRows="totalPage"
        :isLoading.sync="loading"
        :pagination-options="{enabled: true}"
        :search-options="searchOptions"
        @on-row-click="onRowClick"
        @on-search="searchUser"
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
                                :loading="deleting" :on_click="() => removeUserFromCrew(props.row.id)">
                    <i class="fa fa-close"></i>
                </loading-button>
            </span>
            <span v-if="props.column.field === 'owner' && $props.ownerId == props.row.id">
                <i class="fas fa-crown"></i>
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
    name: "CrewUserTable",
    store: Store,
    components: {
        CustomPagination,
        VueGoodTable,
    },
    props: {
        users: Array,
        getUsers: Function,
        loading: Boolean,
        ownerId: Number,
        crewId: Number
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
                    field: 'owner',
                    label: '',
                    sortable: false,
                    width: '30px'
                },
                {
                    field: 'username',
                    label: 'Pseudo',
                    sortable: false,
                },
                {
                    field: 'firstname',
                    label: 'Prénom',
                    sortable: false,
                },
                {
                    field: 'lastname',
                    label: 'Nom',
                    sortable: false,
                },
                {
                    field: 'email',
                    label: 'Email',
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
        this.$props.getUsers(this.page, this.filter);
    },
    methods: {
        updatePage: function (activePage) {
            this.page = activePage;
            this.$props.getUsers(this.page, this.filter);
        },
        removeUserFromCrew: function (userId) {
            this.$dialog
                .confirm('Voulez-vous vraiment retiré cet utilisateur du crew ?')
                .then(function(dialog) {
                    this.deleting = true;
                    axios.delete('/api/crew/' + this.$props.crewId + '/user/' + userId)
                        .then(res => {
                            this.deleting = false;
                            toastr.error('L\'utilisateur a été retiré du crew.');
                        })
                        .catch(() => {
                            toastr.error('Un problème est survenu');
                        })
                })
        },
        onRowClick: function (params) {
            this.$store.commit('setModal', {
                type: 'user',
                value: { show: true, userId: params.row.id }
            })
        },
        searchUser: function (params) {
            this.filter = params.searchTerm
            this.page = 1
            this.$props.getUsers(this.page, params.searchTerm);
        }
    }
}
</script>

<style scoped>

</style>
