<template>
    <v-container>
        <v-row class="mt-5">
            <v-col lg="12" md="12" sm="12" xs="12">
                <h5>Required Headers (base on your product type)</h5>
                <small>Note: Please make sure that the header was exactly the same as your fields</small>
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="4" md="4" sm="6" xs="12"> </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: {
        basis: {
            type: String,
            required: true
        },
        product_type: {
            type: Object,
            default: null
        },
        purchasing_type_id: {
            type: Number,
            default: null
        },
        transaction_id: {
            type: Number,
            default: null
        }
    },
    created() {
        this.initialize()
        this.getPurchasing()
    },
    data() {
        return {
            date: "",
            modal: false,
            saving: false,
            loaded: 0,
            file: "",
            fileName: "",
            base_product_type: {},
            purchasing_type: {},
            photo: "",
            product_types: [],
            selected_product_type: "",
            product_types_selection: []
        }
    },
    watch: {
        product_types: function() {
            this.selected_product_type = 0
        },
        selected_product_type: function(newVal, oldVal) {
            this.base_product_type = this.product_types[newVal]
        }
    },
    methods: {
        willShow: function() {
            if (this.basis != "purchasing") {
                return true
            } else {
                if (Object.keys(this.purchasing_type).length) {
                    if (this.purchasing_type.photo == null) {
                        return true
                    } else {
                        return false
                    }
                }
            }
        },
        initialize: function() {
            if (this.basis == "purchasing") {
                this.base_product_type = this.product_type
            } else {
                this.getProductTypes()
            }
        },
        getProductTypes: function() {
            axios.get("/admin/products/get-all-product-types").then(res => {
                this.product_types = res.data.product_types
                this.product_types_selection = res.data.product_types.map((product_type, i) => {
                    return {
                        text: product_type.product_name,
                        value: i
                    }
                })
            })
        },
        getPurchasing: function() {
            axios.get("/admin/purchasing/get-purchasing-type/" + this.purchasing_type_id).then(res => {
                this.purchasing_type = res.data.purchasing
            })
        },
        parseFile: function() {
            let uploadFiles = document.getElementById("upload-file")
            let file_Name = uploadFiles.value.split("\\")
            this.fileName = file_Name[file_Name.length - 1]
            this.file = this.$refs.uploadFile.files[0]
        },
        uploadItems: function() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                let formData = new FormData()
                formData.append("photo", this.photo)
                formData.append("file", this.file)
                formData.append("file_name", this.fileName)
                formData.append("product_type_id", this.base_product_type.id)
                formData.append("basis", this.basis)
                formData.append("purchasing_type_id", this.purchasing_type_id)

                var vm = this
                this.saving = true
                axios
                    .post("/admin/products/save-file", formData, {
                        headers: { "Content-Type": "multipart/form-data" },
                        onUploadProgress: progressEvent => {
                            let newLoad = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total)) - (Math.floor(Math.random() * 50) + 1)
                            if (newLoad > vm.loaded) {
                                vm.loaded = newLoad
                            }
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            vm.loaded = 100
                        }
                        setTimeout(() => {
                            this.saving = false
                            swal.fire({ title: response.data.heading, icon: response.data.success ? "success" : "error", html: response.data.message })
                            document.getElementById("upload-file").value = ""
                            vm.loaded = 0
                            vm.file = ""
                            vm.fileName = ""
                        }, 1000)
                    })
                    .catch(error => {
                        swal.fire("Something went wrong", "Something error occured while uploading", "error")
                    })
            }
        },
        createValidationPhoto: function() {
            var validations = {}

            if (this.basis == "purchasing") {
                if (this.purchasing_type.photo == null) {
                    validations = {
                        required
                    }
                }
            } else {
                validations = {
                    required
                }
            }

            return validations
        },
        parseFilePhoto: function() {
            let uploadFiles = document.getElementById("upload-photo")
            this.photo = this.$refs.uploadPhoto.files[0]
        }
    },
    validations() {
        return {
            photo: this.createValidationPhoto(),
            file: {
                required
            }
        }
    }
}
</script>

<style></style>
