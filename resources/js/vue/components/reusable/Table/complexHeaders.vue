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
            <v-data-table :headers="headersLocal" :loading="table.loading" :items="table.data" :items-per-page="table.limit" height="70vh" hide-default-header disable-pagination hide-default-footer disable-sort>
                <template v-slot:header="{}">
                    <thead class="v-data-table-header">
                        <tr v-for="(header, i) in headersIterator" :key="i">
                            <th
                                v-for="(col, i1) in header"
                                :key="i1"
                                :colspan="col.colspan"
                                :style="{
                                    width: col.width ? `${col.width}px` : undefined,
                                    minWidth: col.width ? `${col.width}px` : undefined,
                                    position: 'sticky',
                                    top: `${i * 48}${i * 48 ? 'px' : ''}`
                                }"
                                class="text-center"
                                :rowspan="col.rowspan"
                            >
                                <slot :name="`header.${col.value}`" :item="col">
                                    <span> {{ col.text }}</span>
                                </slot>
                            </th>
                        </tr>
                    </thead>
                </template>
                <template #item="prop">
                    <tr>
                        <td v-for="(c, ci) in flattenHeaders" :key="ci" :class="'text-center'">
                            <slot :name="`item.${c.value}`" :item="prop.item">
                                <span>{{ prop.item[c.value] }}</span>
                            </slot>
                        </td>
                    </tr>
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
            default: "70vh"
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
        tableContent: null,
        headersIterator: []
    }),
    watch: {
        initLoad(value) {
            if (value) {
                this.load()
            }
        },
        headers(value) {
            this.convertHeadersToRowPattern(value)
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
            let newest = this.headers.map(header => {
                if (header.hasChild) {
                    return {
                        ...header,
                        text: header.text.toUpperCase(),
                        children: deepCapitalize(header.children)
                    }
                }
                return {
                    ...header,
                    text: header.text.toUpperCase()
                }
            })

            function deepCapitalize(h) {
                return h.map(header => {
                    if (header.hasChild) {
                        return {
                            ...header,
                            text: header.text.toUpperCase(),
                            children: deepCapitalize(header.children)
                        }
                    }
                    return {
                        ...header,
                        text: header.text.toUpperCase()
                    }
                })
            }

            return newest
        },
        maxRowSpan() {
            let rowSpan = 1
            let old = 1

            function doCheck(r, o) {
                if (r < o) {
                    rowSpan = o
                }
            }

            function doSearch(headers, layer) {
                headers.map(header => {
                    if (header.children) {
                        if (header.children.length) {
                            layer++
                            if (layer > old) {
                                old++
                            }
                            doSearch(header.children, layer)
                        }
                    } else {
                        doCheck(rowSpan, old)
                    }
                })
            }

            this.headers.map(header => {
                old = 1
                if (header.children) {
                    if (header.children.length) {
                        old++
                        doSearch(header.children, 2)
                    }
                }
            })

            return rowSpan
        },
        flattenHeaders() {
            let res = []

            this.headers.map(header => {
                if (!header.hasChild) {
                    res.push({
                        ...header
                    })
                } else {
                    deepSearch(header)
                }
            })

            function deepSearch(header) {
                if (!header.hasChild) {
                    res.push({
                        ...header
                    })
                } else {
                    header.children.map(h => {
                        if (!h.hasChild) {
                            res.push({
                                ...h
                            })
                        } else {
                            deepSearch(h)
                        }
                    })
                }
            }

            return res
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
        },

        convertHeadersToRowPattern() {
            let layer = 0 // act as row, 0 means first row, 1 means 2nd row and so on
            let newHeadersPattern = []
            let self = this

            function identifyLayer(layerParam) {
                // create new row if not exist then use it
                if (newHeadersPattern[layerParam] == undefined) {
                    newHeadersPattern.push([])
                }

                return newHeadersPattern[layerParam]
            }

            function getRowSpan(layerParam, header) {
                // it means that this column is solo (no child or subcolumns) so grab until the bottom
                if (layerParam == 0) {
                    return self.maxRowSpan
                } else {
                    if (header.children) {
                        return 1 // solo for this column
                    } else {
                        return self.maxRowSpan - layerParam // not a first row but dont have a children, so grab until the bottom
                    }
                }
            }

            function getColSpan(header) {
                let maxColsPan = 0

                function doCollectEmpty(head) {
                    head.children.map(h => {
                        if (!h.hasChild) {
                            maxColsPan++
                        } else {
                            doCollectEmpty(h)
                        }
                    })
                }

                doCollectEmpty(header)

                return maxColsPan
            }

            function doSubchildManipulation(row, layerParam, header) {
                // we still need to include this column in first row but has a colspan computation
                if (layerParam == 0) {
                    row.push({
                        ...header,
                        rowspan: 1,
                        colspan: getColSpan(header)
                    })
                }

                header.children.map(sub => {
                    let nextLayer = layerParam + 1
                    let getRow = identifyLayer(nextLayer)
                    if (!sub.hasChild) {
                        getRow.push({
                            ...sub,
                            colspan: 1, // no child means no sub column
                            rowspan: getRowSpan(nextLayer, sub)
                        })
                    } else {
                        getRow.push({
                            ...sub,
                            colspan: getColSpan(sub),
                            rowspan: 1
                        })
                        doSubchildManipulation(getRow, nextLayer, sub)
                    }
                })
            }

            this.headersLocal.map(header => {
                if (!header.hasChild) {
                    let getRow = identifyLayer(layer)
                    getRow.push({
                        ...header,
                        rowspan: getRowSpan(layer, header),
                        colspan: 1 // no child means no sub column
                    })
                } else {
                    let getRow = identifyLayer(layer)
                    doSubchildManipulation(getRow, 0, header)
                }
            })

            this.headersIterator = newHeadersPattern
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
    },
    mounted() {
        this.convertHeadersToRowPattern(this.headers)
    }
}
</script>

<style scoped lang="scss">
.result {
    margin-bottom: 5px;
}

.v-data-table {
    th {
        background: #fff;
        border-color: rgba(0, 0, 0, 0.12);
        border-style: solid;
        border-left-width: 0px;
        border-right-width: 1px;
        border-top-width: 0px;
        border-bottom-width: 1px;
        z-index: 2;
        font-family: "Work Sans", sans-serif !important;

        &.first-row {
            position: sticky;
            top: 0;
        }

        &.second-row {
            position: sticky;
            top: 48px;
        }

        &.third-row {
            position: sticky;
            top: 96px;
        }
    }
    td {
        border-color: rgba(0, 0, 0, 0.12);
        border-left-width: 0px;
        border-right-width: 1px;
        border-top-width: 0px;
        border-bottom-width: 1px;
        z-index: 1;
    }
}
</style>
