<?php

// General
define('DMM_PLUGIN_ROLE', 'manage_options');
define('DMM_WEBHOOK', '/dmm-webhook/');

// Database
define('DMM_TABLE_DONATIONS', $wpdb->prefix . 'donate_mollie');
define('DMM_TABLE_DONORS', $wpdb->prefix . 'donate_mollie_donors');
define('DMM_TABLE_SUBSCRIPTIONS', $wpdb->prefix . 'donate_mollie_subscriptions');

// Language
define('DMM_TXT_DOMAIN', 'funding');

// Pages
define('DMM_PAGE_EXPORT', 'funding-export');
define('DMM_PAGE_DONATION', 'funding-donation');
define('DMM_PAGE_DONATIONS', 'funding');
define('DMM_PAGE_DONORS', 'funding-donators');
define('DMM_PAGE_SUBSCRIPTIONS', 'funding-subscriptions');
define('DMM_PAGE_SETTINGS', 'funding-settings');