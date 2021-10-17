# Product-catalogue
Codelex PHP Product Catalogue Exercise

Rename config.example.json file to config.json and enter your database configuration

Database contents:

    Table: products

    Columns: 

        product_id (varchar(255), primary_key, not nullable),

        title (varchar(255)),

        category (varchar(255)),

        quantity (integer),

        created_at (DATETIME),

        edited_at (DATETIME),

        user_id (varchar(255))

    Table: Users

    Columns:

        user_id (varchar(255), primary_key, not nullable),

        email (varchar(255)),

        name (varchar(255)),

        password (varchar(255))