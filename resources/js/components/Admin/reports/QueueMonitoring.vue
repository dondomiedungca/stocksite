<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Manage Product Types</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <div class="row">
            <div class="running col-md-6 pt-2" align="center">
                <table class="table table-bordered table-sm mt-3">
                    <thead align="center">
                        <th colspan="4">
                            <h4>Running Queue(s)</h4>
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td>Queue Name</td>
                            <td>Queue Tag</td>
                            <td>Date Queued</td>
                        </tr>
                        <tr v-if="!current.length">
                            <td colspan="4" align="center">There is no queue running as of now</td>
                        </tr>
                        <tr v-else>
                            <td align="center"><img src="/assets/processing.gif" height="25px" alt="" /></td>
                            <td>{{ name(current[0].name) }}</td>
                            <td>{{ tag(current[0].name) }}</td>
                            <td>{{ current[0].created_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="queue col-md-6 pt-2">
                <table class="table table-bordered table-sm mt-3">
                    <thead align="center">
                        <th colspan="4">
                            <h4>For Processing Queue(s)</h4>
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td>Queue Name</td>
                            <td>Queue Tag</td>
                            <td>Date Queued</td>
                        </tr>
                        <tr v-if="!onProcess.length">
                            <td colspan="4" align="center">There is no queue for processing as of now</td>
                        </tr>
                        <tr v-else v-for="(queue, i) in onProcess" v-bind:key="i">
                            <td align="center"><img src="/assets/queue.gif" height="25px" alt="" /></td>
                            <td>{{ name(queue.name) }}</td>
                            <td>{{ tag(queue.name) }}</td>
                            <td>{{ queue.created_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="failed col-md-6 pt-2">
                <h4 align="center"><i class="lni lni-warning"></i> Failed Queues</h4>
                <br />
                Total failed queue(s): <b v-if="Object.keys(faileds).length">{{ faileds.total }}</b>
                <table class="table table-bordered table-sm mt-3">
                    <thead>
                        <th>Queue Name</th>
                        <th>Queue Tag</th>
                        <th>Failure Details</th>
                        <th>Date Queued</th>
                        <th>Actions</th>
                    </thead>
                    <tbody v-if="Object.keys(faileds).length">
                        <tr v-if="!faileds.data.length" colspan="4">
                            <td colspan="5" align="center">There is no failed queue as of now</td>
                        </tr>
                        <tr v-else v-for="(queue, i) in faileds.data" v-bind:key="i">
                            <td>{{ name(queue.name) }}</td>
                            <td>{{ tag(queue.name) }}</td>
                            <td align="center">
                                <button @click="generateFailedDetail(i)" class="btn btn-sm btn-info">
                                    <i class="lni lni-eye"></i>
                                </button>
                            </td>
                            <td>{{ queue.created_date }}</td>
                            <td align="center">
                                <button @click="generateRetry(i)" class="btn btn-sm btn-info">
                                    <i class="lni lni-reload"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal fade" id="failure_details" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-if="Object.keys(failedDetails).length">{{ tag(failedDetails.name) }} - Failure Detail</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li v-for="(details, i) in failedDetails.failed_jobs" v-bind:key="i">{{ shorter(details.exception) }}</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <modal ref="retry" :keyId="'retry'" :title="'Retry Queue'" :show_submit="true" :icon="'fa fa-check'" :submit_name="'Retry'" @met="retry">
                    <p>Do you want to retry the process of this queue?</p>
                </modal>
                <paginate :data="faileds" :limit="3" v-on:pagination-change-page="getBatchesFailed"></paginate>
            </div>
            <div class="completed col-md-6 pt-2">
                <h4 align="center"><i class="lni lni-checkmark-circle"></i> Completed Queues</h4>
                <br />
                Total completed queue(s): <b v-if="Object.keys(completed).length">{{ completed.total }}</b>
                <table class="table table-hover table-bordered table-sm mt-3">
                    <thead>
                        <th>#</th>
                        <th>Queue Name</th>
                        <th>Queue Tag</th>
                        <th>Process Duration</th>
                        <th>Date Queued</th>
                    </thead>
                    <tbody v-if="Object.keys(completed).length">
                        <tr v-if="!completed.data.length">
                            <td colspan="4" align="center">No completed queue as of now</td>
                        </tr>
                        <tr v-else v-for="(queue, i) in completed.data" v-bind:key="i">
                            <td>{{ i + 1 }}</td>
                            <td>{{ name(queue.name) }}</td>
                            <td>{{ tag(queue.name) }}</td>
                            <td>{{ queue.duration }}</td>
                            <td>{{ queue.created_date }}</td>
                        </tr>
                    </tbody>
                </table>
                <paginate :data="completed" :limit="3" v-on:pagination-change-page="getBatchesCompleted"></paginate>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../Reusable/Pagination"

export default {
    components: {
        paginate: Pagination
    },
    data() {
        return {
            onProcess: [],
            faileds: {},
            completed: {},
            current: [],
            //
            incoming_queues: [],
            //
            for_processing_queues_isManaging: false,
            failedDetails: {},
            for_retry: {}
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
    methods: {
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
        getBatchesFailed: function(page) {
            if (typeof page === "undefined") {
                page = 1
            }
            var url = "/admin/reports/get-queue-batches-failed"
            axios.get(url + "?page=" + page).then(res => {
                this.faileds = res.data.failed
            })
        },
        getBatchesCompleted: function(page) {
            if (typeof page === "undefined") {
                page = 1
            }
            var url = "/admin/reports/get-queue-batches-completed"
            axios.get(url + "?page=" + page).then(res => {
                this.completed = res.data.completed
            })
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
            this.$refs.retry.close()
            this.showNotif({ title: "Added To Queue", message: "Your queue was added again to queue system", type: "success" })
            this.fromFailedToQueue(this.for_retry.id)
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
            axios.get("/admin/reports/queue-retry/" + id).then(res => {
                console.log(res.data)
            })
        },
        generateFailedDetail: function(index) {
            this.failedDetails = this.faileds.data[index]
            $("#failure_details").modal("show")
        },
        generateRetry: function(index) {
            this.for_retry = this.faileds.data[index]
            this.$refs.retry.trigger()
        }
    }
}
</script>

<style>
.completed {
    background: #b3f5d9;
    min-height: 465px;
}

.failed {
    background: #f7dcdc;
    min-height: 465px;
}

.queue {
    background: #f2f2f2;
    min-height: 465px;
}

.running {
    background: #fafafa;
    min-height: 465px;
}

table.table-bordered {
    border: 1px solid #404040;
    margin-top: 20px;
}
table.table-bordered > thead > th {
    border: 1px solid #404040;
    vertical-align: middle;
}
table.table-bordered > tbody > tr > td {
    border: 1px solid #404040;
    vertical-align: middle;
}
</style>
