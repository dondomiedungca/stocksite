<template>
    <v-dialog v-model="isOpen" persistent max-width="850px">
        <v-card v-if="Object.keys(details).length" style="width: 100%;">
            <v-card-title>View Address</v-card-title>
            <div class="form-row">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">
                                    Address Details
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Address Type</td>
                                <td>{{ full_details.address_type.address_type_name }}</td>
                            </tr>
                            <tr>
                                <td>Address No. or House Bldg. No.</td>
                                <td>{{ full_details.address_no_or_house_building_no }}</td>
                            </tr>
                            <tr>
                                <td>Address St.</td>
                                <td>{{ full_details.address_st }}</td>
                            </tr>
                            <tr>
                                <td>Address City</td>
                                <td>{{ full_details.address_city }}</td>
                            </tr>
                            <tr>
                                <td>Address State</td>
                                <td>{{ full_details.address_state }}</td>
                            </tr>
                            <tr>
                                <td>Address Country</td>
                                <td>{{ full_details.address_country }}</td>
                            </tr>
                            <tr>
                                <td>Address Post Code</td>
                                <td>{{ full_details.address_post_code }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="secondary" small @click="close">
                    close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["details", "isViewOpen"],
    data() {
        return {
            full_details: {},
            keys: [],
            isOpen: false
        }
    },
    watch: {
        details: function(newVal, oldVal) {
            this.full_details = newVal
        },
        isViewOpen(data) {
            this.isOpen = data
        }
    },
    methods: {
        close: function() {
            this.isOpen = !this.isOpen
            this.$emit("close")
        }
    }
}
</script>

<style></style>
