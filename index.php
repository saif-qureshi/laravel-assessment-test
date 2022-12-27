<?php 

    include_once('connection.php');


    $cat_ids = [];

    // Category
    $queryCategory = $connection->prepare("select * from categories");
    $queryCategory->execute();
    $categories = $queryCategory->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categories as $category) $cat_ids[] = $category['id'];

    $cat_ids = implode(',',$cat_ids);

    // Product
    $queryProduct = $connection->prepare("select products.*, category_product.category_id as category_id, category_product.product_id as product_id from products inner join category_product on products.id = category_product.product_id where category_product.category_id in ($cat_ids)");
    $queryProduct->execute();
    $products = $queryProduct->fetchAll(PDO::FETCH_ASSOC);
    

    // Building the data array
    $data = [];
    foreach ($categories as $index => $category) {

        $data[$index]['id'] = $category['id'];
        $data[$index]['name'] = $category['name'];

        foreach($products as $product) {
            if($category['id'] === $product['category_id']) {
                $data[$index]['products'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                ];
            }
        }
    }

    // Returning the data
    echo json_encode($data);

?>