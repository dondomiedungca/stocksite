<template>
    <v-container fluid style="margin-left: 0;">
        <v-row>
            <v-col lg="12">
                <div class="d-flex justify-space-between mb-2">
                    <span v-if="Object.keys(suppliers).length">
                        <small v-if="suppliers.total">{{ suppliers.from }} - {{ suppliers.to }} of {{ suppliers.total }} records</small>
                    </span>
                    <v-btn small @click="isAddOpen = true" color="primary"> <v-icon>mdi-pencil</v-icon> Add Supplier </v-btn>
                </div>
                <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="suppliers.total" :headers="headers" :items="suppliers.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                    <template v-slot:item="{ index, item }">
                        <tr>
                            <td align="center">
                                <small># {{ index + 1 }}</small>
                            </td>
                            <td align="center">
                                <small>
                                    <v-btn @click="removeSupplier(item)" icon>
                                        <v-icon>mdi-trash-can</v-icon>
                                    </v-btn>
                                    <v-btn @click="editSupplier(item)" icon>
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </small>
                            </td>
                            <td align="center">{{ item.supplier_name }}</td>
                            <td align="center">{{ item.supplier_email }}</td>
                            <td align="center">{{ item.supplier_phone_number }}</td>
                            <td align="center">{{ item.manufacturer.manufacturer_type_name }}</td>
                            <td align="center">
                                <v-btn @click="renderAddress(item.address)" small color="primary">
                                    Address Details
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
                <add-supplier :isAddOpen="isAddOpen" @close="isAddOpen = false"></add-supplier>
                <address-details :details="address_details" :isViewOpen="isAddressOpen" @close="isAddressOpen = false"></address-details>
                <edit-supplier :countries="countries" :address_types="address_types" :manufacturer_types="manufacturer_types" :isEditOpen="isEditOpen" :forEdit="forEdit" @close="isEditOpen = false"></edit-supplier>
                <v-layout class="d-flex justify-space-between align-center pt-3">
                    <v-pagination :total-visible="7" v-model="page" :length="pageCount"></v-pagination>
                </v-layout>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import EditSupplier from "./EditSupplier.vue"
import AddSupplier from "./AddSupplier.vue"
export default {
    components: {
        EditSupplier,
        AddSupplier
    },
    data() {
        return {
            itemsPerPage: 10,
            page: 1,
            pageCount: 0,
            loading: true,
            options: {},
            //
            isAddressOpen: false,
            address_details: {},
            forEdit: {},
            isEditOpen: false,
            isAddOpen: false,
            headers: [
                {
                    text: "#",
                    align: "center",
                    sortable: false,
                    value: "index"
                },
                {
                    text: "Actions",
                    align: "center",
                    sortable: false,
                    value: "actions"
                },
                {
                    text: "Supplier Name",
                    align: "center",
                    sortable: false,
                    value: "supplier_name"
                },
                { text: "Supplier Email", value: "supplier_email", align: "center", sortable: false },
                { text: "Supplier Phone", value: "supplier_phone_number", align: "center", sortable: false },
                { text: "Manufacturer Type", value: "manufacturer", align: "center", sortable: false },
                { text: "Address Details", value: "address", align: "center", sortable: false }
            ],
            suppliers: {},
            manufacturer_types: [],
            address_types: [],
            countries: []
        }
    },
    created() {
        this.getSuppliers()
        this.getCountries()
        this.getAddressTypes()
        this.getManufacturerTypes()
    },
    methods: {
        getSuppliers(page_continue) {
            this.loading = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.options

            axios
                .post("/admin/supplier/get-paginate-suppliers", {
                    page: typeof page_continue == "undefined" ? page : 1
                })
                .then(result => {
                    this.suppliers = result.data.suppliers
                })
                .then(() => (this.loading = false))
        },
        getCountries() {
            axios.get("/admin/countries/get-countries").then(res => {
                this.countries = res.data.countries.map(country => country.country)
            })
        },
        getAddressTypes: function() {
            axios.get("/admin/address/get-address-type").then(result => {
                this.address_types = result.data.address_types.map(add => {
                    return {
                        text: add.address_type_name,
                        value: add.id
                    }
                })
            })
        },
        getManufacturerTypes: function() {
            axios.get("/admin/manufacturer/get-manufacturer-types").then(result => {
                this.manufacturer_types = result.data.manufacturer_types.map(man => {
                    return {
                        text: man.manufacturer_type_name,
                        value: man.id
                    }
                })
            })
        },
        editSupplier(item) {
            this.forEdit = item
            this.forEdit.manufacturer_id = Number(this.forEdit.manufacturer_id)
            this.isEditOpen = true
        },
        removeSupplier(supplier) {
            var self = this
            swal.queue([
                {
                    title: "Remove " + supplier.supplier_name,
                    text: "This will permanently remove, proceed?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonColor: "#42d1f5",
                    confirmButtonText: "Yes, remove now",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios
                            .post("/admin/supplier/remove-supplier", {
                                supplier
                            })
                            .then(response => {
                                swal.fire({
                                    title: response.data.heading,
                                    text: response.data.message,
                                    icon: response.data.isSuccess ? "success" : "error"
                                })
                                self.getSuppliers(self.suppliers.current_page)
                            })
                    }
                }
            ])
        },
        renderAddress(address) {
            this.address_details = address
            this.isAddressOpen = true
        }
    }
}
</script>

<style></style>
