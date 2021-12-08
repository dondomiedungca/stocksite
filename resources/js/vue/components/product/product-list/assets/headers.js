const headers = [
    {
        text: "Actions",
        align: "center",
        value: "actions",
        width: 200
    },
    {
        text: "Stock No.",
        align: "center",
        value: "stock_number",
        width: 200
    },
    {
        text: "Product Type",
        value: "product_type",
        align: "center",
        width: 200
    },
    {
        text: "ITEM STABLITITY DETAILS",
        hasChild: true,
        width: 600,
        children: [
            {
                text: "Cosmetic Details",
                value: "cosmetic",
                hasChild: true,
                width: 400,
                children: [
                    {
                        text: "Cosmetic Type",
                        value: "cosmetic",
                        width: 200
                    },
                    {
                        text: "Cosmetic Description",
                        value: "item_cosmetic_description",
                        width: 200
                    }
                ]
            },
            {
                text: "Inventory Status",
                value: "item_description",
                width: 200
            }
        ]
    },
    {
        text: "Description",
        value: "item_description",
        align: "center",
        width: 200
    },
    {
        text: "Origin Price",
        value: "origin_price",
        align: "center",
        width: 200
    },
    {
        text: "Selling Price",
        value: "selling_price",
        align: "center",
        width: 200
    },
    {
        text: "Discout Percentage",
        value: "discount_percentage",
        align: "center",
        width: 200
    },
    {
        text: "Date Added",
        value: "date_created",
        align: "center",
        width: 200
    }
]

export default headers
