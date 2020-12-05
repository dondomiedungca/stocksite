import moment from "moment"

const date_format = date => {
    return moment(date).format("MMMM DD, YYYY")
}

export { date_format }
