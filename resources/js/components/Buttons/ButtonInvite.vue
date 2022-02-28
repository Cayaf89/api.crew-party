<template>
    <div class="btn-group" role="group">
        <button type="button" :disabled="loading" @click="inviteUser" @mouseover="hover = true" @mouseout="hover = false"
                class="text-truncate btn text-uppercase"
                :class="[ selected ? btnClassSelected : btnClassNotSelected, btnClass, getSizeClass ]">
            <span v-if="loading === false" v-html="getLabel"></span>
            <kd-loader class="spinner-grow-sm" v-else></kd-loader>
        </button>
    </div>

</template>

<script>

export default {
    name: "ButtonInvite",
    props: {
        userId: Number,
        crewId: Number,
        disabled: {
            type: Boolean,
            default: false,
        },
        callback: {
            type: Function,
            default: function () {
            }
        },
        noLabel: Boolean,
        btnSize: String,
        btnClass: {
            type: String,
            default: 'rounded-xl font-weight-bold '
        },
        btnClassNotSelected: {
            type: String,
            default: 'btn-outline-primary'
        },
        btnClassSelected: {
            type: String,
            default: 'border-2 btn-primary'
        },
        status: String,
    },
    data: function () {
        return {
            loading: false,
            hover: false,
        }
    },
    computed: {
        getSizeClass: function () {
            return this.btnSize ? 'btn-' + this.btnSize : '';
        },
        getLabel: function () {
            let icon = "<i class='icon fas fa-user-plus'></i>";
            let label = "Inviter dans le crew";

            if (this.status === 'JOINED') {
                icon = "<i class='icon fas fa-user-check'></i>";
                if (this.hover) {
                    icon = "<i class='icon fas fa-times'></i>";
                    label = "Enlever du crew";
                }
                else {
                    label = "Dans le crew";
                }
            }
            else if (this.status === 'PENDING') {
                icon = '<i class="far fa-clock"></i>';
                label = "Invitation envoyée";
            }

            if (this.noLabel) {
                return icon;
            }
            return icon + ' ' + label;
        },
        selected: function () {
            return this.status === 'JOINED' || this.status === 'PENDING';
        }
    },
    methods: {
        inviteUser: function () {
            this.loading = true;
            axios.post('/api/crew/' + this.crewId + '/invite-user', {
                user_id: this.$props.userId
            })
                .then((response) => {
                    this.isInCrew = response.data;
                    this.loading = false;
                })
                .catch((error) => {
                    toastr.error(manage_errors(error.response.data));
                    this.loading = false;
                });

            if (this.callback) {
                this.callback(this.isInCrew);
            }
        },
    },

}
</script>
