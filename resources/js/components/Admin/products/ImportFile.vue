<template>
    <div class="row">
        <div class="col-md-12">
            <h5>Required Headers (base on your product type)</h5>
            <small>Note: Please make sure that the header was exactly the same as your fields</small>
            <br />
            <br />
        </div>
        <div v-if="Object.keys(base_product_type).length" class="col-md-12">
            <ul class="list-inline">
                <li v-for="(field, i) in base_product_type.product_attributes" v-bind:key="i" class="list-inline-item">
                    <b>{{ field.product_column_name }}</b>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-upload"></i> Upload File</button>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" enctype="multipart/form-data" @submit.prevent="uploadItems">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div v-if="basis != 'purchasing' && product_types.length" class="col-12">
                                    <h4>* Select Product Type</h4>
                                    <select class="custom-select custom-select-sm" v-model="selected_product_type">
                                        <option v-for="(product_type, i) in product_types" v-bind:key="i" :value="i">-- {{ product_type.product_name }} --</option>
                                    </select>
                                </div>
                                <div v-if="willShow()" class="col-md-12">
                                    <h4>* Product Photo</h4>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="">Choose file</label>
                                            <input id="upload-photo" ref="uploadPhoto" @change="parseFilePhoto($event)" type="file" class="form-control form-control-sm" :class="{ 'is-invalid': $v.photo.$error }" />
                                            <div class="invalid-feedback" v-if="$v.photo.$error">This photo is required</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4>* File To Upload</h4>
                                    <label for="">Choose file</label>
                                    <input id="upload-file" ref="uploadFile" @change="parseFile($event)" type="file" class="form-control form-control-sm" :class="{ 'is-invalid': $v.photo.$error }" />
                                    <div class="invalid-feedback" v-if="$v.file.$error">This file is required</div>
                                </div>
                            </div>
                            <div v-if="saving" class="row mt-1 mb-1">
                                <div class="col-md-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" :style="{ width: loaded + '%' }" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">{{ loaded }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                            <button type="submit" class="btn btn-sm btn-primary" :disabled="saving"><i class="fa fa-upload"></i> Upload File File</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { required } from "vuelidate/lib/validators"
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
            saving: false,
            loaded: 0,
            file: "",
            fileName: "",
            base_product_type: {},
            purchasing_type: {},
            photo: "",
            product_types: [],
            selected_product_type: ""
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
