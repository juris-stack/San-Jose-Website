<?php
/** 
 * Navigation template
 * 
 * @package SJM
 * @author 
 */
$items = array(
    'index.php' => array(
        'title' => 'Dashboard',
        'icon' => 'fa-tachometer-alt'
    )
);
if( ! currentuser_is_customer() ) {
    $items['reports.php'] = array(
        'title' => 'Reports',
        'icon' => 'fa-chart-line',
        'subitems' => array(
            'online_reports.php' => 'Online Orders',
            'walkin_reports.php' => 'Walk-in'            
        )
    );
    $items['products.php'] = array(
        'title' => 'Products',
        'icon' => 'fa-box',
        'subitems' => array(
            'products.php' => 'All Products',
            'product_edit.php?action=add' => 'Add New'
        )
    );
    $items['category.php'] = array(
        'title' => 'Category',
        'icon' => 'fa-list',
        'subitems' => array(
            'category.php' => 'All Categories',
            'category_edit.php?action=add' => 'Add New'
        )
    );
    $items['brands.php'] = array(
        'title' => 'Brands',
        'icon' => 'fa-archive',
        'subitems' => array(
            'category.php?type=brand' => 'All Brands',
            'category_edit.php?action=add&type=brand' => 'Add New'
        )
    );
    $items['orders.php'] = array(
        'title' => 'Orders',
        'icon' => 'fa-shipping-fast'
    );
    $items['pos.php'] = array(
        'title' => 'P O S',
        'icon' => 'fa-shopping-basket'
    );
    $items['support.php'] = array(
        'title' => 'Live Chat',
        'icon' => 'fa-comment-alt'
    );
    $items['users.php'] = array(
        'title' => 'Users',
        'icon' => 'fa-users',
        'subitems' => array(
            'users.php' => 'All Users'
        )
    );
    if( currentuser_is_admin() ) {
        $items['users.php']['subitems']['user.php?action=add'] = 'Add New';
    }
    $items['users.php']['subitems']['user.php'] = 'Account';
    if( currentuser_is_admin() ) {
        $items['settings.php'] = array(
            'title' => 'Settings',
            'icon' => 'fa-cog'
        );
    }
}else{
    $items['user.php'] = array(
        'title' => 'Account',
        'icon' => 'fa-user'
    );
    $items[site_url( '/products.php' )] = array(
        'title' => 'Shop Now',
        'icon' => 'fa-shopping-basket'
    );
}
$url_filename = get_currenturl_filename();
$html = '';
foreach( $items as $file => $item ) {
    $class = [];
    $html .= '<li class="';
    if( ! empty( $item['subitems'] ) ) {
        $html .= 'has-sub';
    }
    $html .= '">';
    $link = ! empty( $item['subitems'] ) ? '#' : $file;
    $html .= '<a class="js-arrow" href="' . $link . '"><i class="fas ' . $item['icon'] . '"></i>' . $item['title'] . '</a>';
    if( ! empty( $item['subitems'] ) ) {
        $html .= '<ul class="list-unstyled navbar__sub-list js-sub-list">';
        foreach( $item['subitems'] as $subfile => $subtitle ) {
            $html .= '<li><a href="' . $subfile . '">' . $subtitle . '</a></li>';
        }
        $html .= '</ul>';
    }
    $html .= '</li>';
} 

echo $html;