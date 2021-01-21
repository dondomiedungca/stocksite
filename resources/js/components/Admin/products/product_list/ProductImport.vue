<template>
    <div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="h4">Product Imports</h4>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    Schedules
                </button>
            </div>
        </div>
        <bread-crumbs :urls="urls"></bread-crumbs>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="home" aria-selected="true">Import Manually</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#file" role="tab" aria-controls="profile" aria-selected="false">Import CSV / XLSX</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row ml-3 mr-3 mb-5 mt-3">
                            <div class="col-md-12">
                                <import-manual :basis="'local'"></import-manual>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="file" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row ml-3 mr-3 mb-3 mt-3">
                            <div class="col-md-12">
                                <import-file :basis="'local'"></import-file>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            urls: [
                {
                    name: "Products",
                    url: "/admin/products",
                    active: false
                },
                {
                    name: "Product Import",
                    active: true
                }
            ]
        }
    }
}
</script>

<style></style>
