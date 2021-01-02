<template>
    <div class="row">
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
                                <div class="col-md-12">
                                    <label for="">Choose file</label>
                                    <input id="upload-file" ref="uploadFile" @change="parseFile($event)" type="file" class="form-control form-control-sm" />
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
export default {
    data() {
        return {
            saving: false,
            loaded: 0,
            file: "",
            fileName: ""
        }
    },
    methods: {
        parseFile: function() {
            let uploadFiles = document.getElementById("upload-file")
            let file_Name = uploadFiles.value.split("\\")
            this.fileName = file_Name[file_Name.length - 1]
            this.file = this.$refs.uploadFile.files[0]
        },
        uploadItems: function() {
            let formData = new FormData()
            formData.append("file", this.file)
            formData.append("file_name", this.fileName)

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
    }
}
</script>

<style></style>
