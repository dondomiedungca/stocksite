<template>
    <v-container fluid style="margin-left: 0;">
        <v-row>
            <v-col lg="12">
                <div class="d-flex justify-space-between mb-2">
                    <span v-if="Object.keys(receivers).length">
                        <small v-if="receivers.total">{{ receivers.from }} - {{ receivers.to }} of {{ receivers.total }} records</small>
                    </span>
                    <v-btn small @click="isAddOpen = true" color="primary"> <v-icon>mdi-pencil</v-icon> Add Receiver </v-btn>
                </div>
                <v-data-table :options.sync="options" disable-pagination :loading="loading" :server-items-length="receivers.total" :headers="headers" :items="receivers.data" :page.sync="page" :items-per-page="itemsPerPage" hide-default-footer class="elevation-1" @page-count="pageCount = $event">
                    <template v-slot:item="{ index, item }">
                        <tr>
                            <td align="center">
                                <small># {{ index + 1 }}</small>
                            </td>
                            <td align="center">
                                <small>
                                    <v-btn @click="removeReceiver(item)" icon>
                                        <v-icon>mdi-trash-can</v-icon>
                                    </v-btn>
                                    <v-btn @click="editReceiver(item)" icon>
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                </small>
                            </td>
                            <td align="center">{{ item.receiver_last_name }}, {{ item.receiver_first_name }}</td>
                            <td align="center">{{ item.receiver_phone }}</td>
                            <td align="center">{{ item.receiver_email }}</td>
                            <td align="center">
                                <v-btn @click="renderAddress(item.address)" small color="primary">
                                    Address Details
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
                <add-receiver @close="isAddOpen = false" :isAddOpen="isAddOpen"></add-receiver>
                <address-details :details="address_details" :isViewOpen="isAddressOpen" @close="isAddressOpen = false"></address-details>
                <edit-receiver :countries="countries" :address_types="address_types" :isEditOpen="isEditOpen" :forEdit="forEdit" @close="isEditOpen = false"></edit-receiver>
                <v-layout class="d-flex justify-space-between align-center pt-3">
                    <v-pagination :total-visible="7" v-model="page" :length="pageCount"></v-pagination>
                </v-layout>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import EditReceiver from "./EditReceiver.vue"
import AddReceiver from "./AddReceiver.vue"
export default {
    components: {
        EditReceiver,
        AddReceiver
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
                    text: "Receiver Name",
                    align: "center",
                    sortable: false,
                    value: "receiver_name"
                },
                { text: "Receiver Phone", value: "receiver_phone", align: "center", sortable: false },
                { text: "Receiver Email", value: "receiver_email", align: "center", sortable: false },
                { text: "Address Details", value: "address", align: "center", sortable: false }
            ],
            receivers: {},
            address_types: [],
            countries: [],
            isAddOpen: false
        }
    },
    created() {
        this.getReceivers()
        this.getCountries()
        this.getAddressTypes()
    },
    methods: {
        getReceivers(page_continue) {
            this.loading = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.options

            axios
                .post("/admin/receiver/get-paginate-receivers", {
                    page: typeof page_continue == "undefined" ? page : 1
                })
                .then(result => {
                    this.receivers = result.data.receivers
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
        editReceiver(item) {
            this.forEdit = item
            this.isEditOpen = true
        },
        removeReceiver(receiver) {
            var self = this
            swal.queue([
                {
                    title: "Remove Receiver",
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
                            .post("/admin/receiver/remove-receiver", {
                                receiver
                            })
                            .then(response => {
                                swal.fire({
                                    title: response.data.heading,
                                    text: response.data.message,
                                    icon: response.data.isSuccess ? "success" : "error"
                                })
                                self.getReceivers(self.receivers.current_page)
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
