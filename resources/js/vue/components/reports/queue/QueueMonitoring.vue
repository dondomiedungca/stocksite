<template>
    <div>
        <v-main id="v-main">
            <breadcrumbs-vue :items="items"></breadcrumbs-vue>
            <v-container fluid>
                <v-row>
                    <v-col class="running" lg="6" md="6" sm="12" xs="12">
                        <small>Running Job - {{ current.length }} Total</small>
                        <v-data-table no-data-text="There is no queue running as of now" disable-pagination :headers="headersRunning" :items="current" hide-default-footer class="elevation-1">
                            <template v-slot:item="{ item }">
                                <tr>
                                    <td align="center"><img src="/assets/processing.gif" height="25px" alt="" /></td>
                                    <td align="center">{{ name(item.name) }}</td>
                                    <td align="center">{{ tag(item.name) }}</td>
                                    <td align="center">{{ item.created_date }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-col>
                    <v-col class="queue" lg="6" md="6" sm="12" xs="12">
                        <small>For Processing - {{ onProcess.length }} Total</small>
                        <v-data-table no-data-text="There is no queue for processing as of now" disable-pagination :headers="headersQueue" :items="onProcess" hide-default-footer class="elevation-1">
                            <template v-slot:item="{ item }">
                                <tr>
                                    <td align="center"><img src="/assets/queue.gif" height="25px" alt="" /></td>
                                    <td align="center">{{ name(item.name) }}</td>
                                    <td align="center">{{ tag(item.name) }}</td>
                                    <td align="center">{{ item.created_date }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-col>
                    <v-col class="failed" lg="6" md="6" sm="12" xs="12">
                        <small v-if="Object.keys(faileds).length">Failed Jobs - {{ faileds.total }} Total</small>
                        <v-data-table :options.sync="optionsFailed" no-data-text="There is no failed queue as of now" disable-pagination :loading="loadingFailed" :server-items-length="faileds.total" :headers="headersFailed" :items="faileds.data" :page.sync="pageFailed" :items-per-page="itemsPerPageFailed" hide-default-footer class="elevation-1" @page-count="pageCountFailed = $event">
                            <template v-slot:item="{ index, item }">
                                <tr>
                                    <td align="center">
                                        <small>{{ index + 1 }}</small>
                                    </td>
                                    <td align="center">{{ name(item.name) }}</td>
                                    <td align="center">{{ tag(item.name) }}</td>
                                    <td align="center">
                                        <v-btn @click="generateFailedDetail(index)" icon small>
                                            <v-icon>mdi-eye</v-icon>
                                        </v-btn>
                                    </td>
                                    <td align="center">{{ item.created_date }}</td>
                                    <td align="center">
                                        <v-btn @click="generateRetry(index)" icon small>
                                            <v-icon>mdi-reload</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>
                        </v-data-table>
                        <v-layout class="d-flex justify-space-between align-center pt-3">
                            <v-pagination :total-visible="7" v-model="pageFailed" :length="pageCountFailed"></v-pagination>
                        </v-layout>
                    </v-col>
                    <v-col lg="6" md="6" sm="12" xs="12">
                        <small v-if="Object.keys(completed).length">Successful Jobs - {{ completed.total }} Total</small>
                        <v-data-table :options.sync="optionsCompleted" no-data-text="No completed queue as of now" disable-pagination :loading="loadingCompleted" :server-items-length="completed.total" :headers="headersCompleted" :items="completed.data" :page.sync="pageCompleted" :items-per-page="itemsPerPageCompleted" hide-default-footer class="elevation-1" @page-count="pageCountCompleted = $event">
                            <template v-slot:item="{ index, item }">
                                <tr>
                                    <td align="center">
                                        <small>{{ index + 1 }}</small>
                                    </td>
                                    <td align="center">{{ name(item.name) }}</td>
                                    <td align="center">{{ tag(item.name) }}</td>
                                    <td align="center">
                                        {{ item.duration }}
                                    </td>
                                    <td align="center">
                                        <v-btn @click="showMessage(item.message.message)" icon small>
                                            <v-icon>mdi-message-outline</v-icon>
                                        </v-btn>
                                    </td>
                                    <td align="center">{{ item.created_date }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                        <v-dialog v-model="isShowMessage" max-width="550px">
                            <v-card style="width: 100%;">
                                <v-card-title>Success Message</v-card-title>
                                <v-card-text>{{ message }}</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" small @click="isShowMessage = false">
                                        Close
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-layout class="d-flex justify-space-between align-center pt-3">
                            <v-pagination :total-visible="7" v-model="pageCompleted" :length="pageCountCompleted"></v-pagination>
                        </v-layout>
                    </v-col>
                </v-row>
                <v-dialog v-model="retryDialog" max-width="350px">
                    <v-card style="width: 100%;">
                        <v-card-title>Retry Failed Job</v-card-title>
                        <v-card-text>Do you really want to retry this?</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="secondary" small @click="retryDialog = false">
                                Close
                            </v-btn>
                            <v-btn color="primary" small @click="retry">
                                Retry
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="showFailed" max-width="650px">
                    <v-card style="width: 100%;">
                        <v-card-title v-if="Object.keys(failedDetails).length">{{ tag(failedDetails.name) }} - Failure Detail</v-card-title>
                        <v-card-text
                            ><ul>
                                <li v-for="(details, i) in failedDetails.failed_jobs" v-bind:key="i" v-html="shorter(details.exception)"></li>
                            </ul>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="secondary" small @click="showFailed = false">
                                Close
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-container>
        </v-main>
    </div>
</template>

<script>
import { mapMutations } from "vuex"
export default {
    data() {
        return {
            items: [
                {
                    text: "Reports",
                    disabled: false,
                    to: "/admin/reports"
                },
                {
                    text: "Queue Monitoring",
                    disabled: true
                }
            ],
            onProcess: [],
            faileds: {},
            completed: {},
            current: [],
            //
            incoming_queues: [],
            //
            for_processing_queues_isManaging: false,
            failedDetails: {},
            for_retry: {},
            message: "",
            //
            itemsPerPageFailed: 10,
            pageFailed: 1,
            pageCountFailed: 0,
            loadingFailed: true,
            optionsFailed: {},
            headersFailed: [
                {
                    text: "#",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "red lighten-3"
                },
                {
                    text: "Queue Name",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "red lighten-3"
                },
                {
                    text: "Queue Tag",
                    align: "center",
                    sortable: false,
                    value: "actions",
                    class: "red lighten-3"
                },
                {
                    text: "Failure Details",
                    align: "center",
                    sortable: false,
                    value: "stock_number",
                    class: "red lighten-3"
                },
                { text: "Date Queued", value: "product_type", align: "center", sortable: false, class: "red lighten-3" },
                { text: "Retry", value: "status", align: "center", sortable: false, class: "red lighten-3" }
            ],
            itemsPerPageRunning: 10,
            pageRunning: 1,
            pageCountRunning: 0,
            loadingRunning: true,
            optionsRunning: {},
            headersRunning: [
                {
                    text: "Status",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "primary white--text"
                },
                {
                    text: "Queue Name",
                    align: "center",
                    sortable: false,
                    value: "actions",
                    class: "primary white--text"
                },
                {
                    text: "Queue Tag",
                    align: "center",
                    sortable: false,
                    value: "stock_number",
                    class: "primary white--text"
                },
                { text: "Date Queued", value: "product_type", align: "center", sortable: false, class: "primary white--text" }
            ],
            headersQueue: [
                {
                    text: "Status",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "grey lighten-1"
                },
                {
                    text: "Queue Name",
                    align: "center",
                    sortable: false,
                    value: "actions",
                    class: "grey lighten-1"
                },
                {
                    text: "Queue Tag",
                    align: "center",
                    sortable: false,
                    value: "stock_number",
                    class: "grey lighten-1"
                },
                { text: "Date Queued", value: "product_type", align: "center", sortable: false, class: "grey lighten-1" }
            ],
            itemsPerPageCompleted: 10,
            pageCompleted: 1,
            pageCountCompleted: 0,
            loadingCompleted: true,
            optionsCompleted: {},
            headersCompleted: [
                {
                    text: "#",
                    align: "center",
                    sortable: false,
                    value: "index",
                    class: "green accent-2 black--text"
                },
                {
                    text: "Queue Name",
                    align: "center",
                    sortable: false,
                    value: "actions",
                    class: "green accent-2 black--text"
                },
                {
                    text: "Queue Tag",
                    align: "center",
                    sortable: false,
                    value: "stock_number",
                    class: "green accent-2 black--text"
                },
                { text: "Process Duration", value: "product_type", align: "center", sortable: false, class: "green accent-2 black--text" },
                { text: "Message", value: "product_type", align: "center", sortable: false, class: "green accent-2 black--text" },
                { text: "Date Queued", value: "product_type", align: "center", sortable: false, class: "green accent-2 black--text" }
            ],
            isShowMessage: false,
            retryDialog: false,
            showFailed: false
        }
    },
    notifications: {
        showNotif: {
            title: "",
            message: "",
            type: "",
            timeout: 3000,
            position: "bottomLeft"
        }
    },
    created() {
        this.currentProcessing()
        this.getBatches()
        this.getBatchesFailed()
        this.getBatchesCompleted()
    },
    mounted() {
        window.Echo.channel("queueprocess").listen(".queueprocess.create", e => {
            this.onProcess.push(e.queue_payload)
        })

        window.Echo.channel("queueprocess").listen(".queueprocess.processing", e => {
            this.fromQueueToCurrent(e.queue_payload)
        })

        window.Echo.channel("queueprocess").listen(".queueprocess.complete", e => {
            this.fromCurrentToComplete(e.queue_payload)
        })

        window.Echo.channel("queueprocess").listen(".queueprocess.failed", e => {
            this.fromCurrentToFailed(e.queue_payload)
        })
    },
    watch: {
        optionsFailed() {
            this.getBatchesFailed()
        },
        optionsCompleted() {
            this.getBatchesCompleted()
        }
    },
    methods: {
        ...mapMutations(["setSnackbar"]),
        currentProcessing: function() {
            var url = "/admin/reports/get-current-queue-processing"
            axios.get(url).then(res => {
                this.current = res.data.current
            })
        },
        getBatches: function() {
            var url = "/admin/reports/get-queue-batches"
            axios.get(url).then(res => {
                this.onProcess = res.data.forProcess
            })
        },
        getBatchesFailed: function() {
            this.loadingFailed = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.optionsFailed

            var url = "/admin/reports/get-queue-batches-failed"
            axios
                .get(url + "?page=" + page)
                .then(res => {
                    this.faileds = res.data.failed
                })
                .then(() => (this.loadingFailed = false))
        },
        getBatchesCompleted: function() {
            this.loadingCompleted = true
            let { sortBy, sortDesc, page = 1, itemsPerPage } = this.optionsCompleted

            var url = "/admin/reports/get-queue-batches-completed"
            axios
                .get(url + "?page=" + page)
                .then(res => {
                    this.completed = res.data.completed
                })
                .then(() => (this.loadingCompleted = false))
        },
        name: function(n) {
            return n.split("*_*")[0]
        },
        tag: function(n) {
            return n.split("*_*")[1]
        },
        shorter: function(description) {
            var end_line = description.indexOf("|")
            return description.substring(0, end_line)
        },
        retry: function() {
            this.setSnackbar({
                isVisible: true,
                color: "#5cb85c",
                text: "New column was added!"
            })
            this.fromFailedToQueue(this.for_retry.id)
            this.retryDialog = false
        },
        fromQueueToCurrent: function(job) {
            this.onProcess = this.onProcess.filter(queue => {
                return queue.id != job.id
            })
            this.current = [job]
        },
        fromCurrentToComplete: function(job) {
            this.current = this.current.filter(queue => {
                return queue.id != job.id
            })
            this.completed.total++
            if (this.completed.total < 10) {
                this.completed.data.unshift(job)
            } else {
                this.completed.data.unshift(job)
                this.completed.data.splice(this.completed.data.length - 1, 1)
            }
        },
        fromCurrentToFailed: function(job) {
            this.current = this.current.filter(queue => {
                return queue.id != job.id
            })
            this.faileds.total++
            if (this.faileds.total < 10) {
                this.faileds.data.unshift(job)
            } else {
                this.faileds.data.unshift(job)
                this.faileds.data.splice(this.faileds.data.length - 1, 1)
            }
        },
        fromFailedToQueue: function(id) {
            this.faileds.data = this.faileds.data.filter(queue => {
                return queue.id != id
            })
            this.faileds.total--
            axios.get("/admin/reports/queue-retry/" + id).then(res => {})
        },
        generateFailedDetail: function(index) {
            this.failedDetails = this.faileds.data[index]
            this.showFailed = true
        },
        generateRetry: function(index) {
            this.for_retry = this.faileds.data[index]
            this.retryDialog = true
        },
        showMessage: function(message) {
            this.message = message
            this.isShowMessage = true
        }
    }
}
</script>

<style>
.table-simple {
    width: 100%;
    min-width: 300px;
    border-collapse: collapse;
    border: 1px solid #404040;
}
.table-simple > thead {
    background: transparent;
    color: #404040;
}
.table-simple > thead > tr > th {
    padding: 5px 10px;
    border: 1px solid #404040;
    vertical-align: middle;
}
.table-simple > tbody > tr > td {
    padding: 5px 10px;
    border: 1px solid #404040;
    vertical-align: middle;
}

.tables-margin {
    margin-top: -15px;
}

.completed {
    min-height: 465px;
}

.failed {
    min-height: 465px;
}

.queue {
    min-height: 465px;
}

.running {
    min-height: 465px;
}
</style>
