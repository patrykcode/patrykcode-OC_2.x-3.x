<?php
require_once "./core/connection.php";

class Import
{


    private $db = null;
    private $dbfrom = null;

    private static $CUSTOMER = "INSERT INTO `oc_customer` (`customer_id`, `customer_group_id`, `store_id`, `firstname`, `lastname`, `email`, `telephone`, `fax`, `password`, `salt`, `cart`, `wishlist`, `newsletter`, `address_id`, `custom_field`, `ip`, `status`, `approved`, `safe`,  `token`, `date_added`) VALUES ";
    private static $ADDRESS = "INSERT INTO `oc_address` (`address_id`, `customer_id`, `firstname`, `lastname`, `company`, `address_1`, `address_2`, `city`, `postcode`, `country_id`, `zone_id`, `custom_field`) VALUES ";
    private static $ORDERS = "INSERT INTO `oc_order`(`order_id`, `invoice`, `invoice_no`, `invoice_prefix`, `store_id`, `store_name`, `store_url`, `customer_id`, `customer_group_id`, `firstname`, `lastname`, `email`, `telephone`, `fax`, `custom_field`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_country`, `payment_country_id`, `payment_zone`, `payment_zone_id`, `payment_address_format`, `payment_custom_field`, `payment_method`, `payment_code`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_country`, `shipping_country_id`, `shipping_zone`, `shipping_zone_id`, `shipping_address_format`, `shipping_custom_field`, `shipping_method`, `shipping_code`, `comment`, `total`, `order_status_id`, `affiliate_id`, `commission`, `marketing_id`, `tracking`, `language_id`, `currency_id`, `currency_code`, `currency_value`, `ip`, `forwarded_ip`, `user_agent`, `accept_language`, `date_added`, `date_modified`) VALUES ";
    private static $ORDERS_HISTORY = "INSERT INTO `oc_order_history`(`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ";
    private static $ORDERS_OPTION = "INSERT INTO `oc_order_option`(`order_option_id`, `order_id`, `order_product_id`, `product_option_id`, `product_option_value_id`, `name`, `value`, `type`) VALUES ";
    private static $ORDERS_PRODUCT = "INSERT INTO `oc_order_product`(`order_product_id`, `order_id`, `product_id`, `name`, `model`, `quantity`, `price`, `total`, `tax`, `reward`) VALUES ";
    private static $ORDERS_TOTAL = "INSERT INTO `oc_order_total`(`order_total_id`, `order_id`, `code`, `title`, `value`, `sort_order`) VALUES ";
    private static $PRODUCTS = "INSERT INTO `oc_product`(`product_id`, `model`, `sku`, `upc`, `ean`, `jan`, `isbn`, `mpn`, `location`, `quantity`, `stock_status_id`, `image`, `manufacturer_id`, `shipping`, `price`, `points`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `length`, `width`, `height`, `length_class_id`, `subtract`, `minimum`, `sort_order`, `status`, `free_shipping`, `viewed`, `date_added`, `date_modified`, `giftproduct`) VALUES ";
    private static $PRODUCTS_DESC = "INSERT INTO `oc_product_description`(`product_id`, `language_id`, `name`, `description`, `ingredients`, `about`, `tag`, `meta_title`, `meta_description`, `meta_keyword`) VALUES ";
    private static $PRODUCTS_IMG = "INSERT INTO `oc_product_image`(`product_image_id`, `product_id`, `image`, `sort_order`) VALUES ";

    private static $PRODUCTS_OPTION = "INSERT INTO `oc_product_option`(`product_option_id`, `product_id`, `option_id`, `value`, `required`) VALUES ";
    private static $PRODUCTS_OPTION_VALUE = "INSERT INTO `oc_product_option_value`(`product_option_value_id`, `product_option_id`, `product_id`, `option_id`, `option_value_id`, `quantity`, `subtract`, `price`, `price_prefix`, `points`, `points_prefix`, `weight`, `weight_prefix`) VALUES ";
    private static $PRODUCTS_RELATED = "INSERT INTO `oc_product_related`(`product_id`, `related_id`) VALUES ";
    private static $PRODUCTS_SPECIAL = "INSERT INTO `oc_product_special`(`product_special_id`, `product_id`, `customer_group_id`, `priority`, `price`, `date_start`, `date_end`) VALUES ";
    private static $PRODUCT_TO_CATEGORY = "INSERT INTO `oc_product_to_category`(`product_id`, `category_id`) VALUES ";

    private static $COUPON = "INSERT INTO `oc_coupon`(`coupon_id`, `name`, `code`, `type`, `discount`, `logged`, `shipping`, `total`, `date_start`, `date_end`, `uses_total`, `uses_customer`, `status`, `date_added`) VALUES ";
    private static $COUPON_CATEGORY = "INSERT INTO `oc_coupon_category`(`coupon_id`, `category_id`) VALUES ";
    private static $COUPON_HISTORY = "INSERT INTO `oc_coupon_history`(`coupon_history_id`, `coupon_id`, `order_id`, `customer_id`, `amount`, `date_added`) VALUES ";
    private static $COUPON_PRODUCT = "INSERT INTO `oc_coupon_product`(`coupon_product_id`, `coupon_id`, `product_id`) VALUES ";

    // private static $SEO_URL = "INSERT INTO `oc_seo_url`(`seo_url_id`, `store_id`, `language_id`, `query`, `keyword`) VALUES ";

    public function __construct()
    {
        ini_set('memory_limit','1000M');
        $this->db = db();

        $this->dbfrom = db([
            'DB_USERNAME' => '',
            'DB_PASSWORD' => '',
            'DB_HOSTNAME' => '',
            'DB_DATABASE' => ''
        ]);
    }
    public function run()
    {
        //ALTER TABLE `oc_product` CHANGE `to_integration` `to_integration` TINYINT(1) NOT NULL DEFAULT '0';

        $this->reloadTable('oc_customer', 'customer_id', 21, self::$CUSTOMER);
        $this->reloadTable('oc_address', 'address_id', 12, self::$ADDRESS);

        $this->reloadTable('oc_order', 'order_id', 62, self::$ORDERS);
        $this->reloadTable('oc_order_history', 'order_history_id', 6, self::$ORDERS_HISTORY);
        $this->reloadTable('oc_order_option', 'order_option_id', 8, self::$ORDERS_OPTION);
        $this->reloadTable('oc_order_product', 'order_product_id', 10, self::$ORDERS_PRODUCT);
        $this->reloadTable('oc_order_total', 'order_total_id', 6, self::$ORDERS_TOTAL);

        $this->reloadTable('oc_product', 'product_id', 33, self::$PRODUCTS);
        $this->reloadTable('oc_product_description', 'product_id', 10, self::$PRODUCTS_DESC);
        $this->reloadTable('oc_product_image', 'product_image_id', 4, self::$PRODUCTS_IMG);

        $this->reloadTable('oc_product_option', 'product_option_id', 5, self::$PRODUCTS_OPTION);
        $this->reloadTable('oc_product_option_value', 'product_option_value_id', 13, self::$PRODUCTS_OPTION_VALUE);
        $this->reloadTable('oc_product_related', null, 2, self::$PRODUCTS_RELATED);
        $this->reloadTable('oc_product_special', 'product_special_id', 7, self::$PRODUCTS_SPECIAL);
        $this->reloadTable('oc_product_to_category', null, 2, self::$PRODUCT_TO_CATEGORY);

        $this->reloadTable('oc_coupon', 'coupon_id', 14, self::$COUPON);
        $this->reloadTable('oc_coupon_category', null, 2, self::$COUPON_CATEGORY);
        $this->reloadTable('oc_coupon_history', 'coupon_history_id', 6, self::$COUPON_HISTORY);
        $this->reloadTable('oc_coupon_product', 'coupon_product_id', 3, self::$COUPON_PRODUCT);


        // $this->reloadTable('oc_seo_url', 'seo_url_id', 5, self::$SEO_URL);
    }

    private function makeQ($sql, $max)
    {

        $q = '';
        for ($i = 1; $i < $max; $i++) {
            $q .= '?,';
        }
        $q .= '?';

        return $sql . "($q)";
    }

    private function  reloadTable($table = '', $column = '', $i = 10, $sql)
    {
        echo "$table \n";
        if ($rows =  $this->dbfrom->query("SELECT * FROM `$table` WHERE 1")->get()) {
            $this->db->query("TRUNCATE TABLE `$table`");
            if ($column) {
                $this->db->query("ALTER TABLE `$table` CHANGE `$column` `$column` INT(11) NOT NULL");
            }

            foreach ($rows as $row) {
                $this->db->query($this->makeQ($sql, $i), $row);
            }

            if ($column) {
                $this->db->query("ALTER TABLE `$table` CHANGE `$column` `$column` INT(11) NOT NULL AUTO_INCREMENT");
            }
        }
    }
}

$obj = new Import();
$obj->run();
