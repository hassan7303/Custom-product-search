[contributors-shield]: https://img.shields.io/github/contributors/hassan7303/Custom-product-search.svg?style=for-the-badge
[contributors-url]: https://github.com/hassan7303/Custom-product-search/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/hassan7303/Custom-product-search.svg?style=for-the-badge&label=Fork
[forks-url]: https://github.com/hassan7303/Custom-product-search/network/members
[stars-shield]: https://img.shields.io/github/stars/hassan7303/Custom-product-search.svg?style=for-the-badge
[stars-url]: https://github.com/hassan7303/Custom-product-search/stargazers
[license-shield]: https://img.shields.io/github/license/hassan7303/Custom-product-search.svg?style=for-the-badge
[license-url]: https://github.com/hassan7303/Custom-product-search/blob/master/LICENSE.md
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-blue.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/hassan-ali-askari-280bb530a/
[telegram-shield]: https://img.shields.io/badge/-Telegram-blue.svg?style=for-the-badge&logo=telegram&colorB=555
[telegram-url]: https://t.me/hassan7303
[instagram-shield]: https://img.shields.io/badge/-Instagram-red.svg?style=for-the-badge&logo=instagram&colorB=555
[instagram-url]: https://www.instagram.com/hasan_ali_askari
[github-shield]: https://img.shields.io/badge/-GitHub-black.svg?style=for-the-badge&logo=github&colorB=555
[github-url]: https://github.com/hassan7303
[email-shield]: https://img.shields.io/badge/-Email-orange.svg?style=for-the-badge&logo=gmail&colorB=555
[email-url]: mailto:hassanali7303@gmail.com

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]
[![Telegram][telegram-shield]][telegram-url]
[![Instagram][instagram-shield]][instagram-url]
[![GitHub][github-shield]][github-url]
[![Email][email-shield]][email-url]


# Custom Product Search Plugin

**Version:** 1.0.0  
**Author:** Hassan Ali Askari  
**Author URI:** [Telegram](https://t.me/hassan7303)  
**Plugin URI:** [GitHub](https://github.com/hassan7303)  
**License:** MIT  
**License URI:** [MIT License](https://opensource.org/licenses/MIT)  
**Email:** hassanali7303@gmail.com  
**Domain Path:** [hsnali.ir](https://hsnali.ir)  

---

## Description
The **Custom Product Search Plugin** provides an advanced search functionality for WooCommerce products. It supports real-time AJAX search with debounce, as well as a fallback for full search submission. Users can search products by title or meta values (e.g., `_chemical_formula`).

---

## Features
- Customizable search box with shortcode support.
- AJAX-based real-time product search.
- Responsive design with adaptive grid layout for product results.
- Debounce mechanism to prevent excessive AJAX requests.
- Default image fallback for products without a thumbnail.
- WooCommerce integration for fetching product details.

---

## Shortcode
Use the following shortcode to display the search form anywhere on your site:

```plaintext
[custom_product_search]
```
## Installation

1.Download the plugin files or clone the repository.
2.Place the files in the `/wp-content/plugins/custom-product-search` directory.
3.Activate the plugin through the Plugins menu in WordPress.

## Usage
-Add the [custom_product_search] shortcode to any page or post.
-The search form will automatically handle AJAX requests for product search.
-If the user clicks the submit button without valid results, they are redirected to the `/product-request-form` page.

## Code Overview
1. Prevent Direct Access
```php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

```
2. Search Box Shortcode
The `custom_product_search_box` function renders a search form with real-time AJAX functionality.

3. Styles and Scripts
-The plugin enqueues required JavaScript and CSS styles to handle responsive behavior and AJAX calls.
-Includes media queries for responsive layout below 540px and 400px.
4. AJAX Handler
Handles search requests via `custom_product_search_ajax`.
Queries WooCommerce products by title and custom meta fields using `$wpdb`.

5. AJAX Frontend Script
Uses jQuery to listen for user input and perform AJAX requests. Results are displayed dynamically.

## AJAX Configuration
The plugin uses `wp_localize_script` to pass data from PHP to JavaScript:

```php
wp_localize_script('custom-search-ajax', 'customSearch', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('custom_search_nonce')
]);

```


---



markdown
Copy code
# افزونه جستجوی محصولات سفارشی

**نسخه:** 1.0.0  
**نویسنده:** حسن علی عسکری  
**لینک تلگرام:** [Telegram](https://t.me/hassan7303)  
**لینک گیت‌هاب:** [GitHub](https://github.com/hassan7303)  
**مجوز:** MIT  
**لینک مجوز:** [MIT License](https://opensource.org/licenses/MIT)  
**ایمیل:** hassanali7303@gmail.com  
**لینک دامنه:** [hsnali.ir](https://hsnali.ir)  

---

## توضیحات
افزونه **جستجوی محصولات سفارشی** امکان جستجوی پیشرفته محصولات ووکامرس را فراهم می‌کند. این افزونه از جستجوی زنده (AJAX) با تأخیر کنترل‌شده (debounce) پشتیبانی می‌کند و برای جستجوهای کامل دکمه ارسال نیز دارد. کاربران می‌توانند محصولات را بر اساس عنوان یا مقادیر متا (مانند `_chemical_formula`) جستجو کنند.

---

## ویژگی‌ها
- فرم جستجوی قابل سفارشی‌سازی با پشتیبانی از شورت‌کد.
- جستجوی زنده محصولات با استفاده از AJAX.
- طراحی واکنش‌گرا با چیدمان شبکه‌ای برای نتایج.
- مکانیزم تأخیر برای جلوگیری از ارسال درخواست‌های مکرر.
- تصویر پیش‌فرض برای محصولات بدون تصویر.
- ادغام کامل با ووکامرس برای دریافت جزئیات محصولات.

---

## شورت‌کد
برای نمایش فرم جستجو در هر صفحه یا نوشته، از شورت‌کد زیر استفاده کنید:

```plaintext
[custom_product_search]