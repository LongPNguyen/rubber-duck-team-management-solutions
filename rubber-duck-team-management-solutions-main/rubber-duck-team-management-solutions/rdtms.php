<?php

/**
 * Plugin Name: Rubber Duck Team Management Solutions
 * Plugin URI:  https://rubberducktech.com/products/rubber-duck-team-management-solutions
 * Description: A add-on plugin to Rubber Duck Tech Solutions Suites to manage team members.
 * Version:     1.0
 * Author:      Long Nguyen
 * Author URI:  https://rubberducktech.com
 * Text Domain: rubber-duck-team-management-solutions
 */

function clock_in_out_enqueue_scripts()
{
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_script('clock-in-out-script', plugins_url('/assets/js/clock-in-out.js', __FILE__), array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'clock_in_out_enqueue_scripts');

function add_custom_roles()
{
    add_role('employee_hourly', 'Employee (Hourly)', array('read' => true));
    add_role('employee_commission', 'Employee (Commission)', array('read' => true, 'edit_posts' => true));
    add_role('owner', 'Owner', array('read' => true, 'edit_posts' => true, 'edit_pages' => true));
}
add_action('init', 'add_custom_roles');

// Add Custom Capabilities
function add_custom_capabilities()
{
    $role_hourly = get_role('employee_hourly');
    $role_commission = get_role('employee_commission');
    $role_owner = get_role('owner');

    $role_hourly->add_cap('manage_options'); // Allow to edit pages

    $role_commission->add_cap('manage_options'); // Allow to manage options
    $role_commission->add_cap('edit_pages'); // Allow to edit pages
    $role_commission->add_cap('edit_posts'); // Allow to edit posts
    $role_commission->add_cap('edit_others_posts'); // Allow to edit posts by others
    $role_commission->add_cap('edit_others_pages'); // Allow to edit pages by others
    $role_commission->add_cap('edit_others_rd_inventory'); // Custom capability
    $role_commission->add_cap('edit_posts_rd_inventory'); // Custom capability
    $role_commission->add_cap('create_posts'); // Allow to create posts
    $role_commission->add_cap('delete_others_posts'); // Allow to delete posts by others
    $role_commission->add_cap('delete_posts'); // Allow to delete posts
    $role_commission->add_cap('delete_private_posts'); // Allow to delete private posts
    $role_commission->add_cap('delete_published_posts'); // Allow to delete published posts
    $role_commission->add_cap('edit_private_posts'); // Allow to edit private posts
    $role_commission->add_cap('edit_published_posts'); // Allow to edit published posts
    $role_commission->add_cap('publish_posts'); // Allow to publish posts
    $role_commission->add_cap('read_private_posts'); // Allow to read private posts
    $role_commission->add_cap('create_pages'); // Allow to create posts
    $role_commission->add_cap('delete_others_pages'); // Allow to delete posts by others
    $role_commission->add_cap('delete_pages'); // Allow to delete posts
    $role_commission->add_cap('delete_private_pages'); // Allow to delete private posts
    $role_commission->add_cap('delete_published_pages'); // Allow to delete published posts
    $role_commission->add_cap('edit_private_pages'); // Allow to edit private posts
    $role_commission->add_cap('edit_published_pages'); // Allow to edit published posts
    $role_commission->add_cap('publish_pages'); // Allow to publish posts
    $role_commission->add_cap('read_private_pages'); // Allow to read private posts
    $role_commission->add_cap('upload_files'); // Allow to read private posts
    $role_commission->add_cap('edit_files'); // Allow to read private posts
    

    // Owner
    $role_owner->add_cap('manage_options'); // Allow to manage options
    $role_owner->add_cap('edit_pages'); // Allow to edit pages
    $role_owner->add_cap('edit_posts'); // Allow to edit posts
    $role_owner->add_cap('edit_others_posts'); // Allow to edit posts by others
    $role_owner->add_cap('edit_others_pages'); // Allow to edit pages by others
    $role_owner->add_cap('edit_others_rd_inventory'); // Custom capability
    $role_owner->add_cap('edit_posts_rd_inventory'); // Custom capability
    $role_owner->add_cap('create_posts'); // Allow to create posts
    $role_owner->add_cap('delete_others_posts'); // Allow to delete posts by others
    $role_owner->add_cap('delete_posts'); // Allow to delete posts
    $role_owner->add_cap('delete_private_posts'); // Allow to delete private posts
    $role_owner->add_cap('delete_published_posts'); // Allow to delete published posts
    $role_owner->add_cap('edit_private_posts'); // Allow to edit private posts
    $role_owner->add_cap('edit_published_posts'); // Allow to edit published posts
    $role_owner->add_cap('publish_posts'); // Allow to publish posts
    $role_owner->add_cap('read_private_posts'); // Allow to read private posts
    $role_owner->add_cap('create_pages'); // Allow to create posts
    $role_owner->add_cap('delete_others_pages'); // Allow to delete posts by others
    $role_owner->add_cap('delete_pages'); // Allow to delete posts
    $role_owner->add_cap('delete_private_pages'); // Allow to delete private posts
    $role_owner->add_cap('delete_published_pages'); // Allow to delete published posts
    $role_owner->add_cap('edit_private_pages'); // Allow to edit private posts
    $role_owner->add_cap('edit_published_pages'); // Allow to edit published posts
    $role_owner->add_cap('publish_pages'); // Allow to publish posts
    $role_owner->add_cap('read_private_pages'); // Allow to read private posts
    $role_owner->add_cap('upload_files'); // Allow to read private posts
    $role_owner->add_cap('edit_files'); // Allow to read private posts
    
}
add_action('admin_init', 'add_custom_capabilities');

function modify_menu_visibility()
{
    $user = wp_get_current_user();
    $owners = in_array('owner', $user->roles);
    $comission = in_array('employee_hourly', $user->roles);
    $hourly = in_array('employee_hourly', $user->roles);

    if (in_array('employee_hourly', $user->roles) || in_array('employee_commission', $user->roles)) {
        // Remove menus for Hourly and Commission Employees
        remove_menu_page('edit.php?post_type=page'); // Dashboard
        // Add any other menu pages that need to be removed.
    }

    if ( $owners || $comission || $hourly ) {
        // Remove all menus for the Owner
        global $menu;
        $restricted = array(__('Dashboard'), __('Posts') , __('Comments'), __('Appearance'), __('Plugins'), __('Users'), __('Tools'), __('Settings'), __('Contact'), $hourly ? __('Media') : "");
        end($menu);
        while (prev($menu)) {
            $value = explode(' ', $menu[key($menu)][0]);
            if (in_array($value[0] != NULL ? $value[0] : "", $restricted)) {
                unset($menu[key($menu)]);
            }
        }
        remove_menu_page('edit.php?post_type=acf-field-group');
    }
}
add_action('admin_menu', 'modify_menu_visibility');

function redirect_to_custom_dashboard($redirect_to, $request, $user)
{
    // Check if the user is logging in
    if (isset($user->roles) && is_array($user->roles)) {
        // Check if the user has one of the custom roles
        if (in_array('employee_hourly', $user->roles) || in_array('employee_commission', $user->roles) || in_array('owner', $user->roles)) {
            // Redirect them to the rdts menu
            return admin_url('admin.php?page=rdts_main_menu');
        }
    }

    return $redirect_to;
}
add_filter('login_redirect', 'redirect_to_custom_dashboard', 10, 3);
