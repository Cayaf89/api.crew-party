const app = new Vue({
    el: '#register-page',
    data: function () {
        return {
            username: '',
            email: '',
            password: '',
            passwordConfirm: '',
            errors: {}
        }
    },
    methods: {
        submit: function () {
            axios.post('/api/register', {
                username: this.username,
                email: this.email,
                password: this.password,
                password_confirmation: this.passwordConfirm
            })
                .then(res => {
                    window.location.href = '/dashboard';
                })
                .catch(error => {
                    if (error?.response?.data?.errors) {
                        this.errors = error.response.data.errors;
                    }
                })
        }
    }
})
