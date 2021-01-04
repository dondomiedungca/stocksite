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
                <h4><i class="lni lni-tag"></i> Queue List / Running Queues</h4>
                <br />
                <table class="table table-bordered table-sm mt-3">
                    <thead>
                        <th>Status</th>
                        <th>Queue Name</th>
                        <th>Date Queued</th>
                        <th>Actions</th>
                    </thead>
                    <tbody v-if="Object.keys(onProcess).length">
                        <tr v-if="!onProcess.data.length">
                            <td colspan="4" align="center">There is no queue running as of now</td>
                        </tr>
                        <tr v-else v-for="(queue, i) in onProcess.data" v-bind:key="i">
                            <td></td>
                            <td>{{ queue.name }}</td>
                            <td>{{ queue.created_date }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <paginate :data="onProcess" :limit="3" v-on:pagination-change-page="getBatches"></paginate>
            </div>
            <div class="failed col-md-6 pt-2" align="center">
                <h4><i class="lni lni-warning"></i> Failed Queues</h4>
                <br />
                <table class="table table-bordered table-sm mt-3">
                    <thead>
                        <th>Queue Name</th>
                        <th>Failure Details</th>
                        <th>Date Queued</th>
                        <th>Actions</th>
                    </thead>
                    <tbody v-if="Object.keys(faileds).length">
                        <tr v-if="!faileds.data.length" colspan="4">
                            <td colspan="4" align="center">There is no failed queue as of now</td>
                        </tr>
                        <tr v-else v-for="(queue, i) in faileds.data" v-bind:key="i">
                            <td>{{ queue.name }}</td>
                            <td align="center">
                                <button class="btn btn-sm btn-info">
                                    <i class="lni lni-eye"></i>
                                </button>
                            </td>
                            <td>{{ queue.created_date }}</td>
                            <td align="center">
                                <button class="btn btn-sm btn-info">
                                    <i class="lni lni-reload"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <paginate :data="faileds" :limit="3" v-on:pagination-change-page="getBatchesFailed"></paginate>
            </div>
            <div class="completed col-md-12 pt-2" align="center">
                <h4><i class="lni lni-checkmark-circle"></i> Completed Queues</h4>
                <br />
                <table class="table table-hover table-bordered table-sm mt-3">
                    <thead>
                        <th>#</th>
                        <th>Queue Name</th>
                        <th>Date Queued</th>
                    </thead>
                    <tbody v-if="Object.keys(completed).length">
                        <tr v-for="(queue, i) in completed.data" v-bind:key="i">
                            <td>{{ i + 1 }}</td>
                            <td>{{ queue.name }}</td>
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
            onProcess: {},
            faileds: {},
            completed: {}
        }
    },
    created() {
        this.getBatches()
        this.getBatchesFailed()
        this.getBatchesCompleted()
    },
    methods: {
        getBatches: function(page) {
            if (typeof page === "undefined") {
                page = 1
            }
            var url = "/admin/reports/get-queue-batches"
            axios.get(url + "?page=" + page).then(res => {
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
    background: #f0cece;
    min-height: 465px;
}

.running {
    background: #f2f2f2;
    min-height: 465px;
}

table.table-bordered {
    border: 1px solid #404040;
    margin-top: 20px;
}
table.table-bordered > thead > th {
    border: 1px solid #404040;
}
table.table-bordered > tbody > tr > td {
    border: 1px solid #404040;
}
</style>
