export const breadCrumbs = [
    {
        text: "Product",
        disabled: true,
        icon: "mdi-warehouse"
    }
]

export const modules = [
    {
        url: "/admin/products/add-product-type",
        icon: "mdi-pencil",
        title: "Create New Product Type",
        description: "Add new product types including the important columns that will hold the attributes/specifications of the product."
    },
    {
        url: "/admin/products/product-list",
        icon: "mdi-format-list-text",
        title: "Product List",
        description: "Show the entire inventories, managed by the product types."
    },
    {
        url: "/admin/products/product-import",
        icon: "mdi-application-import",
        title: "Import Product",
        description: "Import item(s) base on their product type, import the items via CSV, XLSX or manually."
    }
]
