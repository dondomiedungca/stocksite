<template>
    <v-container style="margin-left: 0;">
        <v-row v-if="basis != 'purchasing' && product_types.length" class="mt-5">
            <v-col lg="6" md="6" sm="12" xs="12">
                <Select :items="product_types" v-model="selected_product_type_id_local" label="Inventory Type" />
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="12" md="12" sm="12" xs="12">
                <h5>Required Headers (base on your product type)</h5>
                <small>Note: Please make sure that the header was exactly the same as your fields</small>
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="12" md="12" sm="12" xs="12">
                <v-simple-table v-if="Object.keys(selected_product_type).length">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th v-for="(productType, i) in selected_product_type.product_attributes" v-bind:key="i" class="text-left">
                                    {{ productType.product_column_name }}
                                </th>
                            </tr>
                        </thead>
                    </template>
                </v-simple-table>
            </v-col>
        </v-row>
        <v-row>
            <v-col lg="3" md="3" sm="6" xs="12">
                <v-btn @click="dialog = true" small color="dark"><v-icon>mdi-file-upload</v-icon> Upload File</v-btn>
            </v-col>
            <v-dialog v-model="dialog" width="600">
                <v-form v-model="valid" ref="form" @submit.prevent>
                    <v-card>
                        <v-card-title>
                            Upload File
                        </v-card-title>

                        <div>
                            <v-row class="form-row">
                                <v-col v-if="photoVisibility" lg="12" md="12" sm="12" xs="12">
                                    <InputFile :rules="requiredFile" id="upload-photo" ref="uploadPhoto" accept="image/*" v-model="photo" label="Product Photo" />
                                </v-col>
                                <v-col lg="12" md="12" sm="12" xs="12">
                                    <InputFile id="upload-file" ref="uploadFile" accept=".csv, .xlsx" v-model="file" label="File To Upload" />
                                    <v-progress-linear v-if="loaded" v-model="loaded" height="25">
                                        <strong>{{ loaded }}%</strong>
                                    </v-progress-linear>
                                </v-col>
                            </v-row>
                        </div>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn small color="dark" @click="dialog = false"><v-icon>mdi-close</v-icon>Close</v-btn>
                            <v-btn :loading="isLoading" small color="dark" class="ma-2" @click="uploadItems" fab>
                                <v-icon dark>
                                    mdi-cloud-upload
                                </v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>
        </v-row>
    </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex"
import InputFile from "./../../reusable/InputFile"
import Select from "./../../reusable/Select"

export default {
    components: {
        InputFile,
        Select
    },
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
    data() {
        return {
            dialog: false,
            valid: false,
            file: [],
            fileName: "",
            photo: [],
            requiredFile: [v => !!v || "File is required", v => (v && v.size > 0) || "File is required"]
        }
    },
    watch: {
        file(data) {
            if (data) {
                let last_dot = data.name.lastIndexOf(".")
                let name = data.name.slice(0, last_dot)
                this.fileName = name
            }
        }
    },
    computed: {
        ...mapState("product_import", ["loaded", "selected_product_type_id", "isLoading", "product_types", "photoVisibility", "selected_product_type"]),
        selected_product_type_id_local: {
            get() {
                return this.selected_product_type_id
            },
            set(v) {
                this.SET_PRODUCT_TYPE_ID(v)
            }
        }
    },
    mounted() {
        this.doManipulate()
    },
    methods: {
        ...mapActions("product_import", ["setForPurchasing", "saveFile"]),
        ...mapMutations("product_import", ["SET_PRODUCT_TYPE_ID"]),
        doManipulate() {
            if (this.basis == "purchasing") {
                const data = {
                    product_type: this.product_type,
                    purchasing_type_id: this.purchasing_type_id
                }
                this.setForPurchasing(data)
            }
        },
        parseFile: function() {
            let uploadFiles = document.getElementById("upload-file")
            let file_Name = uploadFiles.value.split("\\")
            this.fileName = file_Name[file_Name.length - 1]
            this.file = this.$refs.uploadFile.files[0]
        },
        uploadItems: function() {
            this.$refs.form.validate()
            if (this.valid) {
                const data = {
                    photo: this.photo,
                    file: this.file,
                    file_name: this.file_name,
                    basis: this.basis
                }

                this.saveFile(data)
            }
        }
    }
}
</script>

<style>
.form-row {
    margin: 7px 1%;
}
</style>
