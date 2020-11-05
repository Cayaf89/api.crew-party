<template>
    <div class="sidebar" :class="{open}">
        <div class="sidebar-header">
            <img class="sidebar-logo" src="/storage/images/logo/Vandal-crew-party.png">
        </div>
        <div class="sidebar-body">
            <div class="sidebar-part" v-if="isMyCrews">
                <div class="sidebar-title">
                    Mes Crews
                </div>
                <div class="sidebar-link d-flex align-items-center justify-content-center" v-if="loadingMyCrew">
                    <div class="spinner-grow" role="status" aria-hidden="true">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <a :href="'/crew/' + crew.id" class="sidebar-link" v-for="crew in myCrews" v-else>
                    <div class="sidebar-link-logo">
                        <img :src="crew.logo" class="sidebar-link-logo-image">
                    </div>
                    <div class="sidebar-link-text">
                        {{ crew.name }}
                    </div>
                </a>
            </div>

            <div class="sidebar-part" v-if="isOtherCrews">
                <div class="sidebar-title">
                    Autres Crews
                </div>
                <div class="sidebar-link d-flex align-items-center justify-content-center" v-if="loadingOtherCrew">
                    <div class="spinner-grow" role="status" aria-hidden="true">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <a :href="'/crew/' + crew.id" class="sidebar-link" v-for="crew in otherCrews" v-else>
                    <div class="sidebar-link-logo">
                        <img :src="crew.logo" class="sidebar-link-logo-image">
                    </div>
                    <div class="sidebar-link-text">
                        {{ crew.name }}
                    </div>
                </a>
            </div>
        </div>
        <div class="sidebar-footer">
            <button type="button" class="btn-sidebar-opener" @click="toggleOpen">
                <i class="fas fa-chevron-left" v-if="open"></i>
                <i class="fas fa-chevron-right" v-else></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "CrewSideBar",
    store: Store,
    data: function () {
        return {
            myCrews: null,
            otherCrews: null,
            open: false,
            loadingOtherCrew: false,
            loadingMyCrew: false,
        }
    },
    mounted() {
        this.getOtherCrewList();
        this.getMyCrewList();
        this.$store.commit('setUpdateSideBarCrews', () => {
            this.getOtherCrewList();
            this.getMyCrewList();
        })
    },
    methods: {
        getOtherCrewList: function () {
            this.loadingOtherCrew = true;
            axios.get('/api/other-crews')
                .then(res => {
                    this.otherCrews = res.data.data;
                    this.loadingOtherCrew = false;
                })
        },
        getMyCrewList: function () {
            this.loadingMyCrew = true;
            axios.get('/api/my-crews')
                .then(res => {
                    this.myCrews = res.data.data;
                    this.loadingMyCrew = false;
                })
        },
        toggleOpen: function () {
            this.open = !this.open;
        }
    },
    computed: {
        isOtherCrews: function () {
            return this.otherCrews?.length > 0;
        },
        isMyCrews: function () {
            return this.myCrews?.length > 0;
        },
    }
}
</script>

<style scoped>
.sidebar {
    position: absolute;
    display: flex;
    flex-direction: column;
    top: 0;
    left: 0;
    width: 60px;
    height: 100%;
    background-color: #455a64;
    transition: width 0.5s ease;
    overflow: hidden;
}

.sidebar.open {
    width: 200px;
    transition: width 0.5s ease;
}

.sidebar-header {
    width: 100%;
    display: flex;
}

.sidebar-logo {
    margin: 5px auto;
    margin-bottom: 30px;
    height: 50px;
    width: 50px;
    transition: height 0.5s ease, width 0.5s ease;
}

.sidebar.open .sidebar-logo {
    margin: 10px auto;
    margin-bottom: 40px;
    height: 180px;
    width: 180px;
    transition: height 0.5s ease, width 0.5s ease;
}

.sidebar-body {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-title {
    width: 100%;
    display: none;
    overflow: hidden;
    text-align: center;
    text-overflow: ellipsis;
    color: #7593a1;
    border-top: solid 1px #7593a1;
    border-bottom: solid 1px #7593a1;
}

.sidebar.open .sidebar-title {
    display: block;
}

.sidebar-link {
    display: flex;
    color: #7593a1;
    padding: 10px 0;
}

.sidebar-link:hover {
    background-color: #7593a1;
    color: #455a64;
    text-decoration: none;
}

.sidebar-link-text {
    display: flex;
    align-self: center;
    margin-left: 15px;
    white-space: nowrap;
}

.sidebar-link-logo {
    width: 60px;
    min-width: 60px;
    display: flex;
}

.sidebar-link-logo-image {
    height: 50px;
    width: 50px;
    border-radius: 50%;
    margin: auto;
}

.sidebar-footer {
    margin-top: auto;
}

.btn-sidebar-opener {
    width: 100%;
    text-align: right;
    height: 50px;
    background-color: #455a64;
    color: #7593a1;
    font-size: 25px;
    border: 0;
}

.btn-sidebar-opener:hover {
    color: #455a64;
    background-color: #7593a1;
}
</style>
