<template>
    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                                <input type="text" class="form-control" v-model="value">
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center" v-if="loading">
                            <loader></loader>
                        </div>
                        <div class="row" v-else-if="data.length === 0">
                            <div class="col-12 text-center">
                                Aucun utilisateur trouvé.
                            </div>
                        </div>
                        <div class="row" v-else>
                            <template v-if="invite_type === userType">
                                <div class="col-md-6 col-12" v-for="item in data">
                                    <div class="border rounded-lg mb-3 p-3">
                                        <a @click="() => onUserClick(item.id)" target="_blank"
                                           class="d-flex align-items-center text-decoration-none">
                                            <img v-lazy="item.logo" height="55" class="rounded-circle shadow-sm mr-3">
                                            <div>
                                                <h6 class="m-0">
                                                    {{ item.username }}
                                                </h6>
                                            </div>
                                        </a>
                                        <button-invite class="mt-3" btn-size="sm" :user-id="item.id"
                                                       :crew-id="item.id" :is-in-crew.sync="item.is_in_crew">
                                        </button-invite>
                                    </div>
                                </div>
                            </template>
                            <template v-if="invite_type === eventType">
                                <div class="col-md-6 col-12" v-for="item in data">
                                </div>
                            </template>
                            <template v-if="invite_type === crewType">
                                <div class="col-md-6 col-12" v-for="item in data">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ButtonInvite from "../Buttons/ButtonInvite";
import { CREW, EVENT, USER } from "../../constants/InvitationTypes";

export default {
    name: "ModalInvite",
    components: { ButtonInvite },
    props: {
        crew_id: Number,
        event_id: Number,
        invite_type: String,
        invite_id: Number,
        onClose: Function,
        callback: Function
    },
    mounted() {
        $(this.$el).modal();
        $(this.$el).on('hidden.bs.modal', () => {
            if (this.$props.callback) {
                this.$props.callback();
            }
            this.$props.onClose();
        });
    },
    data: function () {
        return {
            data: [],
            value: '',
            loading: false,
            userType: USER,
            eventType: EVENT,
            crewType: CREW,
        }
    },
    methods: {
        getSearch: function () {
            this.loading = true;
            let params = {
                value: this.value
            };
            if (this.$props.crew_id) {
                params.crew_id = this.$props.crew_id;
            }
            if (this.$props.event_id) {
                params.event_id = this.$props.event_id;
            }
            axios.get('/api/search/' + this.invite_type, {
                params
            })
                .then(res => {
                    this.list = res.data.data;
                    this.loading = false;
                })
        },
        onUserClick: function (userId) {
            this.$store.commit('setModal', {
                type: 'user',
                value: { show: true, userId }
            })
        },
    },
    watch: {
        'value': function (value) {
            this.loading = true;
            if (!value) {
                this.data = [];
            }
            this.getSearch(value);
        }
    }
}
</script>

<style scoped>
.user-logo {
    border-radius: 50%;
}
</style>
