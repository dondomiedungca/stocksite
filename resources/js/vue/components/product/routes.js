import AddProductType from "./add-product/AddProductType"

const product_related_routes = [
    {
        path: "/admin/products/add-product-type",
        component: AddProductType
    },
    {
        path: "/admin/products/product-list",
        component: AddProductType
    },
    {
        path: "/admin/products/product-import",
        component: AddProductType
    }
]

export default product_related_routes
