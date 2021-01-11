<template>
    <div>
        <div class="modal fade" :ref="keyId" id="exampleModal" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                        <button v-if="show_submit" type="button" @click="saveNow" class="btn btn-primary"><i :class="icon"></i> {{ submit_name }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["title", "submit_name", "show_submit", "icon", "keyId"],
    methods: {
        trigger: function() {
            $(this.$refs[this.keyId]).modal("show")
        },
        saveNow: function() {
            this.$emit("met")
        },
        close: function() {
            $(this.$refs[this.keyId]).modal("hide")
        }
    }
}
</script>

<style></style>
