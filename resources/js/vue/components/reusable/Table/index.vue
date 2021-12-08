<template>
    <div>
        <div class="amz-table-result-summary result d-flex justify-space-between align-center">
            <slot name="left-header">
                <div>
                    <span class="d-inline-block text-truncate" v-if="table.total > 0">
                        RESULT: {{ table.from | numWithCommas }}-{{ table.to | numWithCommas }} of
                        {{ table.total | numWithCommas }}
                    </span>
                </div>
            </slot>

            <slot name="right-header" v-if="table.total > 0 || rightHeaderAlwaysVisible" />
        </div>

        <v-card flat style="border: 1px solid #D0D0D0;">
            <v-data-table :headers="headersLocal" :loading="table.loading" :fixed-header="true" :items="table.data" :items-per-page="table.limit" :height="height" :disable-pagination="true" :hide-default-footer="true" :disable-sort="!enableDefaultSort" :item-key="itemKey">
                <template v-for="header in headersLocal" v-slot:[`header.${header.value}`]>
                    <slot :name="`header.${header.value}`" :item="header">
                        <span :key="`header-${header.text}`"> {{ header.text }}</span>
                    </slot>
                </template>

                <template v-for="header in headers" v-slot:[`item.${header.value}`]="{ item }">
                    <slot :name="`item.${header.value}`" :item="item">
                        <span :key="header.text">{{ item[header.value] | emptyFormatter }}</span>
                    </slot>
                </template>

                <template v-slot:no-data>
                    <v-card flat class="d-flex align-center justify-center" height="50vh">
                        <div>
                            <v-icon size="72">mdi-emoticon-sad</v-icon>
                            <p>No data available</p>
                        </div>
                    </v-card>
                </template>

                <template v-slot:loading>
                    <v-card flat class="d-flex align-center justify-center" height="50vh">
                        <v-progress-circular indeterminate color="primary" class="mr-3" size="32" />
                        <span class="text--secondary">Fetching data...</span>
                    </v-card>
                </template>
            </v-data-table>
        </v-card>

        <v-row v-if="!disableCustomPagination">
            <v-col cols="11" class="pt-3 pl-0">
                <div class="d-flex justify-start align-center">
                    <v-pagination class="justify-start" :value="table.page" @input="handlePagination" :length="table.totalPages" :disabled="table.loading" total-visible="10" style="width: auto" />

                    <template v-if="table.total > 0">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <div class="px-3 d-flex align-center" style="width: 90px" v-on="on">
                                    <v-select :items="[10, 50, 100, 500]" :value="table.limit" @input="handleChangeRowsPerPage" :disabled="table.loading" class="hidden-sm-and-down" />
                                </div>
                            </template>
                            <span>Rows per page</span>
                        </v-tooltip>

                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <span class="hidden-sm-and-down" v-on="on">
                                    {{ table.from | numWithCommas }}-{{ table.to | numWithCommas }} of
                                    {{ table.total | numWithCommas }}
                                </span>
                            </template>
                            <span>Total Result</span>
                        </v-tooltip>
                    </template>
                </div>
            </v-col>

            <v-col cols="12" v-if="table.total > 0" class="pt-0  hidden-md-and-up">
                <div class="d-flex justify-start align-center">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <div class="pr-3 d-flex align-center" style="width: 90px" v-on="on">
                                <v-select :items="[10, 50, 100, 500]" :value="table.limit" @input="handleChangeRowsPerPage" :disabled="table.loading" />
                            </div>
                        </template>
                        <span>Rows per page</span>
                    </v-tooltip>

                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <span class="text-total-result" v-on="on">
                                {{ table.from | numWithCommas }}-{{ table.to | numWithCommas }} of
                                {{ table.total | numWithCommas }}
                            </span>
                        </template>
                        <span>Total Result</span>
                    </v-tooltip>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { mapMutations } from "vuex"

//For Validation
const defaultFunc = function() {}

export default {
    name: "stocksite-data-table",
    props: {
        headers: {
            type: Array,
            default: []
        },
        load: {
            type: Function,
            default: () => []
        },
        initLoad: {
            type: Boolean,
            default: true
        },
        height: {
            type: String,
            default: "60vh"
        },
        disableCustomPagination: {
            type: Boolean,
            default: false
        },
        content: {
            type: Object,
            default: null,
            validator(value) {
                if (value === null) {
                    return true
                }

                const validProperties = ["data", "limit", "page", "total", "totalPages", "to", "from", "loading"].sort()

                try {
                    const itemProperties = Object.keys(value).sort()

                    if (validProperties.length !== itemProperties.length) {
                        throw "Error"
                    }

                    const result = validProperties.every((item, index) => item === itemProperties[index])

                    if (result) {
                        return true
                    }

                    throw "Error"
                } catch (error) {
                    console.warn(`


            [Datatable Component]: Content Props must have these following properties : ${validProperties.toString()}


          `)
                }
            }
        },
        onPageChange: {
            type: Function,
            default: defaultFunc
        },
        onRowChange: {
            type: Function,
            default: defaultFunc
        },
        rightHeaderAlwaysVisible: {
            type: Boolean,
            default: false
        },
        enableDefaultSort: {
            type: Boolean,
            default: false
        },
        itemKey: {
            type: String,
            default: "id"
        }
    },
    data: () => ({
        tableContent: null
    }),
    watch: {
        initLoad(value) {
            if (value) {
                this.load()
            }
        }
    },
    computed: {
        table() {
            //This replaces props instead of from vuex(by default)
            if (this.content) {
                return this.content
            }

            return this.$store.state.table
        },
        headersLocal() {
            return this.headers.map(header => {
                return {
                    ...header,
                    text: header.text.toUpperCase()
                }
            })
        }
    },
    methods: {
        ...mapMutations("table", ["SET_PAGE", "SET_ROWS_PER_PAGE"]),

        handlePagination(page) {
            if (this.content) {
                this.onPageChange(page)
            } else {
                this.SET_PAGE(page)
            }

            this.load()
        },

        handleChangeRowsPerPage(rows) {
            if (this.content) {
                this.onRowChange(rows)
            } else {
                this.SET_PAGE(1)
                this.SET_ROWS_PER_PAGE(rows)
            }

            this.load()
        }
    },
    created() {
        if (this.initLoad) {
            this.load()
        }

        if (this.content) {
            if (this.onPageChange === defaultFunc) {
                console.error(`


        [Datatable Component]: onPageChange is Required


      `)
            }

            if (this.onRowChange === defaultFunc) {
                console.error(`


        [Datatable Component]: onRowChange is Required


      `)
            }
        }
    }
}
</script>

<style scoped>
.result {
    margin-bottom: 5px;
}
</style>
