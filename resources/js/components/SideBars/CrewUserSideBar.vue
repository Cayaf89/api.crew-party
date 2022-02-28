<template>
    <div class="sidebar" :class="{open}">
        <div class="sidebar-body">
            <div class="sidebar-part">
                <div class="sidebar-title">
                    Membres
                </div>
                <a class="sidebar-link" :title="user.username" v-for="user in users" @click="() => onUserClick(user.id)">
                    <div class="sidebar-link-logo">
                        <img :src="user.logo" class="sidebar-link-logo-image">
                    </div>
                    <div class="sidebar-link-text">
                        {{ user.username }}
                    </div>
                </a>

                <infinite-loading @infinite="getCrewUserList">
                    <div slot="no-results" class="text-center text-muted">
                        Aucun utilisateur pour le moment
                    </div>
                    <div slot="no-more"></div>
                </infinite-loading>
            </div>

        </div>
        <div class="sidebar-footer">
            <button type="button" class="btn-sidebar-opener" @click="toggleOpen">
                <i class="fas fa-chevron-right" v-if="open"></i>
                <i class="fas fa-chevron-left" v-else></i>
            </button>
        </div>
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';

export default {
    name: "CrewSideBar",
    store: Store,
    components: {
        InfiniteLoading
    },
    props: {
        crewId: Number
    },
    data: function () {
        return {
            users: [],
            filter: '',
            page: 1,
            open: false,
        }
    },
    methods: {
        getCrewUserList: function ($state) {
            axios.get('/api/crew/' + this.$props.crewId + '/users',{
                params: {
                    page: this.page,
                    filter: this.filter,
                }
            })
                .then(res => {
                    if (res.data.data.length) {
                        this.page++;
                        this.users.push(...res.data.data);
                        $state.loaded();
                    }
                    else {
                        $state.complete();
                    }
                })
        },
        onUserClick: function (userId) {
            this.$store.commit('setModal', {
                type: 'user',
                value: { show: true, userId }
            })
        },
        toggleOpen: function () {
            this.open = !this.open;
        }
    },
}
</script>

<style scoped>
.sidebar {
    position: fixed;
    display: flex;
    flex-direction: column;
    top: 72px;
    right: 0;
    width: 80px;
    height: calc(100% - 72px);
    background-color: #455a64;
    transition: width 0.5s ease;
    overflow: hidden;
    z-index: 1;
}

.sidebar.open {
    width: 200px;
    transition: width 0.5s ease;
}

.sidebar-body {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-title {
    width: 100%;
    overflow: hidden;
    text-align: center;
    text-overflow: ellipsis;
    color: white;
    border-bottom: solid 1px #7593a1;
    padding: 10px 0;
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
    cursor: pointer;
}

.sidebar-link-text {
    align-self: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-link-logo {
    width: 80px;
    min-width: 80px;
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
    text-align: left;
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
