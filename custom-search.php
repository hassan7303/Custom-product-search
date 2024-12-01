<?php
/**
 * Plugin Name: Custom Product Search
 *
 * Description: A custom search plugin for WooCommerce products with AJAX functionality.
 *
 * Version: 1.0.0
 *
 * Author: hassan Ali Askari
 * Author URI: https://t.me/hassan7303
 * Plugin URI: https://github.com/hassan7303
 *
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 *
 * Email: hassanali7303@gmail.com
 * Domain Path: https://hsnali.ir
 */

if(!defined('ABSPATH')) {
    exit; // جلوگیری از دسترسی مستقیم
}

// Shortcode برای نمایش جعبه جستجو
function custom_product_search_box()
{
    ob_start();
    ?>
    <div class="container-custom-product-search-form">
        <div class="searchform custom-product-search-form ">
            <input type="text" id="custom-search-input" class="s" placeholder="جستجوی محصولات" value="" name="s"
                   aria-label="جستجو" title="جستجوی محصولات" required="">
            <button type="submit" class="search-submit-btn">
                <span>جستجو</span>
            </button>
        </div>
    </div>
    <div class="container-product-info" style="display: none;padding: 10px">

    </div>
    <style>
        .container-custom-product-search-form {
            border-radius: 12px;
            width: 100%;
            height: 99%;
            padding: 42px;
            display: flex;
            justify-content: center;
        }

        .search-submit-btn {
            background-color: var(--wd-primary-color) !important;
            color: #fff !important;
            border-start-end-radius: var(--wd-form-brd-radius);
            border-end-end-radius: var(--wd-form-brd-radius);
        }

        .custom-product-search-form, #custom-search-input {
            max-width: 100%;
            width: 100%;
        }

        .custom-product-search-form {
            display: flex;
        }

        #custom-search-input {
            border-radius: 0 7px 7px 0 !important;
        }

        .container-product-info {
            /*grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));*/
            grid-template-columns: repeat(4, 1fr);
            margin: 0 40px;
        }

        @media (max-width: 540px) {
            .container-product-info {
                grid-template-columns: unset;
                margin: 0 40px;
            }

            .search-product-item {
                margin: 5px 0 !important;
            }
        }

        @media (max-width: 400px) {
            .container-custom-product-search-form {
                padding: 20px !important;
            }

            .container-product-info {
                grid-template-columns: unset;
                margin: 0 10px;
            }
        }

        .search-product-item {
            padding: 5px;
            border-radius: 7px;
            box-shadow: 0 0 4px 1px #dddd;
            margin: 0 5px;
            text-align: start;
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {
            let searchInput = $('#custom-search-input');
            let searchResults = $('#custom-search-results');
            let containerProductInfo = $('.container-product-info');
            let searchSubmitBtn = $('.search-submit-btn');
            let debounceTimer;
            searchInput.on('keyup', function () {
                let query = $(this).val();
                if (query.length < 3) {
                    searchResults.hide();
                    return;
                }

                clearTimeout(debounceTimer); // جلوگیری از اجرای مکرر
                debounceTimer = setTimeout(() => {
                    ajax(query, false);
                }, 10);
            });

            searchSubmitBtn.on('click', function () {
                let query = searchInput.val();
                if (query.length < 3) {
                    searchResults.hide();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    ajax(query, true);
                }, 10);
            });


            function ajax(query, btn = false) {
                containerProductInfo.empty();
                $.ajax({
                    url: customSearch.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'custom_product_search',
                        nonce: customSearch.nonce,
                        query: query,
                        _: new Date().getTime()
                    },
                    success: function (response) {
                        if (response.success) {
                            containerProductInfo.empty().css("display", "grid");
                            response.data.forEach(function (product) {
                                let productHtml = `
                                <div class="search-product-item">
                                    <a href="${product.link}" style="text-decoration: none; color: inherit;">
                                        <img src="${product.thumbnail}" alt="${product.title}" style="width: 100px; height: auto; border-radius: 7px;">
                                        <span>${product.title}</span>
                                    </a>
                                </div>`;
                                containerProductInfo.append(productHtml);
                            });
                        } else {
                            containerProductInfo.hide();
                            if (btn) {
                                window.location.href = "/product-request-form";
                            }
                        }
                    }
                });
            }

            $(document).on('click', '#custom-search-results li', function () {
                let productLink = $(this).data('url');
                window.location.href = productLink;
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('custom_product_search', 'custom_product_search_box');

// اسکریپت AJAX
function custom_product_search_scripts()
{
    wp_enqueue_script('custom-search-ajax', plugin_dir_url(__FILE__) . 'custom-search.js', ['jquery'], '1.0', true);
    wp_localize_script('custom-search-ajax', 'customSearch', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('custom_search_nonce')
    ]);
}

add_action('wp_enqueue_scripts', 'custom_product_search_scripts');

// هندلر AJAX در PHP
function custom_product_search_ajax()
{
    global $wpdb;
    check_ajax_referer('custom_search_nonce', 'nonce');

    $search_value = sanitize_text_field($_POST['query']);
    $search_term = '%' . sanitize_text_field($search_value) . '%';
    $meta_key = '_chemical_formula';

    // کوئری جستجو
    $query = "
    SELECT p.ID, p.post_title, p.post_name, pm.meta_value
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'product'
    AND (
        LOWER(p.post_title) LIKE %s
        OR LOWER(pm.meta_value) LIKE %s
    )
    GROUP BY p.ID
";

    $results = $wpdb->get_results($wpdb->prepare($query, $search_term, $search_term));

    $matched_posts = [];
    $titles = [];

    if(!empty($results)) {
        foreach($results as $meta) {
            $product_id = $meta->ID;

            if(in_array($meta->post_title, $titles)) {
                continue;
            }

            $titles[] = $meta->post_title;

            $product = get_post($product_id);

            if($product) {
                $price = get_post_meta($product->ID, '_price', true);

                if(empty($price)) {
                    $price = 'قیمت در دسترس نیست';
                }

                $description = get_post_meta($product->ID, '_short_description', true);

                if(empty($description)) {
                    $description = $product->post_content;
                }

                $thumbnail_url = get_the_post_thumbnail_url($product->ID, 'full');

                if(!$thumbnail_url) {
                    $thumbnail_url = 'https://via.placeholder.com/150'; // تصویر پیش‌فرض
                }

                $matched_posts[] = [
                'ID' => $product->ID,
                'title' => $product->post_title,
                'link' => get_permalink($product->ID),
                'price' => $price,
                'description' => $description,
                'thumbnail' => $thumbnail_url
                ];
            }
        }
    }

    if(!empty($matched_posts)) {
        wp_send_json_success($matched_posts);
    } else {
        wp_send_json_error('هیچ محصولی پیدا نشد.');
    }

    //    if($query->have_posts()) {
    //        $results = '<ul>';
    //        while($query->have_posts()) {
    //            $query->the_post();
    //            $results .= '<li data-url="' . get_permalink() . '">' . get_the_title() . '</li>';
    //        }
    //        $results .= '</ul>';
    //        wp_send_json_success($results);
    //    } else {
    //        wp_send_json_error('No products found.');
    //    }
    //
    //    wp_die();
}

add_action('wp_ajax_custom_product_search', 'custom_product_search_ajax');
add_action('wp_ajax_nopriv_custom_product_search', 'custom_product_search_ajax');



