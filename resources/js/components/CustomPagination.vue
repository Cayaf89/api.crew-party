<template>
    <nav aria-label="pagination">
        <ul class="pagination justify-content-center">
            <li :class="{'page-item': true, 'disabled': isPreviousDisabled}">
                <a aria-label="Go to first page" aria-disabled="true" href="#" target="_self"
                   tabindex="-1" :class="{'page-link': true, 'disabled': isPreviousDisabled}"
                   @click.prevent="goToFirstPage">«</a>
            </li>
            <li :class="{'page-item': true, 'disabled': isPreviousDisabled}">
                <a aria-label="Go to previous page" aria-disabled="true" href="#"
                   target="_self" tabindex="-1" :class="{'page-link': true, 'disabled': isPreviousDisabled}"
                   @click.prevent="goToPreviousPage">‹</a>
            </li>
            <li v-if="activePage - Math.floor(nbPagesDisplay / 2) > 1" class="page-item disabled">
                <a aria-label="Go to previous page" aria-disabled="true" href="#"
                   target="_self" tabindex="-1" class="page-link disabled"
                   @click.prevent="goToPreviousPage">...</a>
            </li>
            <li :class="{'page-item': true, 'active': isPageActive(i)}" @click.prevent="goToPage($event, i)"
                v-for="i in getPaginationArray">
                <a :aria-label="'Current page ' . i" href="#" target="_self"
                   class="page-link c-page-link-number"> {{ i }} </a>
            </li>
            <li v-if="activePage + Math.floor(nbPagesDisplay / 2) < nbPageTotal" class="page-item disabled">
                <a aria-label="Go to previous page" aria-disabled="true" href="#"
                   target="_self" tabindex="-1" class="page-link disabled"
                   @click.prevent="goToPreviousPage">...</a>
            </li>
            <li :class="{'page-item': true, 'disabled': isNextDisabled}">
                <a aria-label="Go to next page" href="#" target="_self"
                   :class="{'page-link': true, 'disabled': isNextDisabled}" @click.prevent="goToNextPage">›</a>
            </li>
            <li :class="{'page-item': true, 'disabled': isNextDisabled}">
                <a aria-label="Go to last page" href="#" target="_self"
                   :class="{'page-link': true, 'disabled': isNextDisabled}" @click.prevent="goToLastPage">»</a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "CustomPagination",
    props: {
        nbPageTotal: Number,
        onUpdatePage: Function,
        nbPagesDisplay: {
            type: Number,
            default: 10,
        }
    },
    data: function () {
        return {
            activePage: 1,
        }
    },
    methods: {
        goToPage: function (e, index) {
            this.activePage = index;
            this.$props.onUpdatePage(this.activePage);
        },
        goToNextPage: function (e) {
            if (this.activePage < this.$props.nbPageTotal) {
                this.activePage++;
                this.$props.onUpdatePage(this.activePage);
            }
        },
        goToPreviousPage: function (e) {
            if (this.activePage > 1) {
                this.activePage--;
                this.$props.onUpdatePage(this.activePage);
            }
        },
        goToLastPage: function (e) {
            this.activePage = this.$props.nbPageTotal;
            this.$props.onUpdatePage(this.activePage);
        },
        goToFirstPage: function (e) {
            this.activePage = 1;
            this.$props.onUpdatePage(this.activePage);
        },
        isPageActive: function (index) {
            return index === this.activePage;
        }
    },
    computed: {
        isPreviousDisabled: function () {
            return this.activePage === 1;
        },
        isNextDisabled: function () {
            return this.activePage === this.$props.nbPageTotal;
        },
        getPaginationArray: function () {
            let pages = [];
            if (this.activePage) {
                let min = this.activePage - Math.floor(this.nbPagesDisplay / 2);
                if (min < 1) {
                    min = 1;
                }

                let max = this.activePage + Math.floor(this.nbPagesDisplay / 2);
                if (max > this.nbPageTotal) {
                    max = this.nbPageTotal;
                }

                for (let i = min; i <= max; i++) {
                    pages.push(i);
                }
            }
            return pages;
        },
    }
}
</script>

<style scoped>

</style>
