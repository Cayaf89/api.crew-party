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
                    <div class="d-flex align-items-center justify-content-center" v-if="loading">
                        <span class="spinner-grow spinner-grow-sm text-blue" role="status" aria-hidden="true">
                            <span class="sr-only">Loading...</span>
                        </span>
                    </div>
                    <div class="container" v-else>
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                                <img :src="user.logo" height="150" width="150" class="user-logo" alt="logo">
                                <div class="h4">
                                    {{ user.username }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <div>{{ user.firstname }} {{ user.lastname }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                <div>{{ user.email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalUser",
    props: {
        userId: Number,
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

        this.getUser();
    },
    data: function () {
        return {
            user: {
                logo: '/storage/images/default-logo.png',
                username: '',
                firstname: '',
                lastname: '',
                email: '',
            },
            loading: false,
        }
    },
    methods: {
        getUser: function () {
            this.loading = true;
            axios.get('/api/user/' + this.$props.userId)
                .then(res => {
                    this.user = res.data.data;
                    this.loading = false;
                })
                .catch(error => {
                    toastr.error('L\'utilisateur n\'a pas été trouvé.');
                    $(this.$el).modal('hide');
                })
        }
    }
}
</script>

<style scoped>
.user-logo {
    border-radius: 50%;
}
</style>
