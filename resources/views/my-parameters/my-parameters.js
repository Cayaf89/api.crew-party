import { imageResize } from "../../js/services/fileService";

const app = new Vue({
    el: '#my-parameters-page',
    store: Store,
    data: function () {
        return {
            firstname: this.$store.state.user.firstname,
            lastname: this.$store.state.user.lastname,
            username: this.$store.state.user.username,
            email: this.$store.state.user.email,
            logo: null,
            logoSrc: this.$store.state.user.logo,
            submitting: false,
            errors: {}
        }
    },
    methods: {
        inputFile: async function (newFile, oldFile) {
            this.logo = newFile;
            this.logo.file = await imageResize(URL.createObjectURL(newFile.file), 400, 400);
            this.logoSrc = URL.createObjectURL(this.logo.file)
            let data = new FormData();
            data.append('logo', this.logo.file);
            axios.post('/api/user/logo/' + this.$store.state.user.id, data)
                .then(res => {
                    this.logoSrc = res.data.logo;
                    this.$store.commit('updateNavBarUser')
                })
                .catch(error => {
                    if (error?.response?.data) {
                        toastr.error(error.response.data);
                    }
                })
        },
        submit: function () {
            this.submitting = true;
            let data = new FormData();
            if (this.logo?.file) {
                data.append('logo', this.logo.file);
            }
            data.append('firstname', this.firstname);
            data.append('lastname', this.lastname);
            data.append('username', this.username);
            data.append('email', this.email);
            axios.post('/api/user/' + this.$store.state.user.id, data)
                .then(() => {
                    this.submitting = false;
                    toastr.success('Vos informations ont bien été enregistrées')
                })
                .catch(error => {
                    if (error?.response?.data?.errors) {
                        this.errors = error.response.data.errors;
                    }
                    this.submitting = false;
                })
        }
    }
})
