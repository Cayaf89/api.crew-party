<template>
    <vue-good-table
        mode="remote"
        :columns="$props.columns"
        :rows="$props.data"
        :totalRows="$props.totalPage"
        :isLoading.sync="$props.loading"
        :pagination-options="{enabled: true}"
        :search-options="searchOptions"
        @on-row-click="$props.onRowClick"
        @on-search="searchEvent"
    >
        <template slot="pagination-bottom" slot-scope="props">
            <custom-pagination
                class="mt-2"
                :nb-page-total="$props.totalPage"
                :on-update-page="updatePage">
            </custom-pagination>
        </template>

        <template slot="loadingContent">
            <span class="spinner-grow spinner-grow-sm text-blue" role="status" aria-hidden="true">
                <span class="sr-only">Loading...</span>
            </span>
        </template>

        <template slot="table-row" slot-scope="props">
            <slot name="data-table-row" :table-props="props"></slot>
        </template>

        <div slot="table-actions">
            <button type="button" class="btn btn-primary" @click="$props.addAction">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-primary data-table-refresh-button" @click="refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </vue-good-table>
</template>

<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import CustomPagination from "../CustomPagination";

export default {
    name: "EventTable",
    store: Store,
    components: {
        CustomPagination,
        VueGoodTable,
    },
    props: {
        data: Array,
        columns: Array,
        totalPage: Number,
        loading: Boolean,
        getData: Function,
        onRowClick: Function,
        addAction: Function,
    },
    data: function () {
        return {
            page: 1,
            filter: '',
            searchOptions: {
                enabled: true,
                placeholder: 'Rechercher',
            },
        }
    },
    mounted() {
        this.$props.getData(1, this.filter);
    },
    methods: {
        refresh: function () {
            this.$props.getData(this.page, this.filter);
        },
        updatePage: function (activePage) {
            this.page = activePage;
            this.$props.getData(activePage, this.filter);
        },
        searchEvent: function (params) {
            this.filter = params.searchTerm
            this.page = 1
            this.$props.getData(1, params.searchTerm);
        }
    }
}
</script>

<style scoped>
table.vgt-table td {
    vertical-align: middle;
}
.data-table-refresh-button {
    margin-right: 10px;
}
.vgt-global-search.vgt-clearfix {
    align-items: center;
}
</style>
