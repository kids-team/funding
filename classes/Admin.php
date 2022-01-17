<?php

namespace Contexis\Funding;
use Contexis\Funding\MollieApi;
use Contexis\Funding\List_Table;

class Admin {

    private $wpdb;

    /**
     * Dmm_Admin constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('admin_menu', array($this, 'dmm_admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'admin_style'));
        add_action('admin_init', array($this, 'dmm_register_settings'));
        add_action('admin_post_dmm_export', array($this, 'dmm_export_donations'));
        //add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);

        if (!get_option('permalink_structure'))
            add_action('admin_notices', array($this, 'dmm_admin_notice__warning'));
    }

    /**
     * Admin notices
     *
     * @since 2.4.1
     */
    function dmm_admin_notice__warning() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('In order for the plugin "Donate with Mollie" to function properly, it is necessary to enable permalinks in your <a href="options-permalink.php">Wordpress settings</a>.', 'funding'); ?></p>
        </div>
        <?php
    }

	/**
	 * Add Admin Style
	 *
	 * @since 3.0
	 */
	function admin_style() {
		wp_enqueue_style('funding-style-admin', plugin_dir_url( __FILE__ ) .'../funding.css');
	}

    

    /**
     * Admin menu
     *
     * @since 1.0.0
     */
    public function dmm_admin_menu() {
        add_menu_page(
            __('Donate with Mollie', 'funding'),
            __('Donations', 'funding'),
            get_option('dmm_rights_donations', DMM_PLUGIN_ROLE),
            DMM_PAGE_DONATIONS,
            array(
                $this,
                'dmm_page_donations'
            ),
            'dashicons-heart'
        );

        if (get_option('dmm_recurring'))
        {
            add_submenu_page(
                DMM_PAGE_DONATIONS,
                __('Subscriptions', 'funding') . ' | ' . __('Donate with Mollie', 'funding'),
                __('Subscriptions', 'funding'),
                get_option('dmm_rights_subscriptions', DMM_PLUGIN_ROLE),
                DMM_PAGE_SUBSCRIPTIONS,
                array(
                    $this,
                    'dmm_page_subscriptions'
                )
            );
            add_submenu_page(
                DMM_PAGE_DONATIONS,
                __('Donors', 'funding') . ' | ' . __('Donate with Mollie', 'funding'),
                __('Donors', 'funding'),
                get_option('dmm_rights_subscriptions', DMM_PLUGIN_ROLE),
                DMM_PAGE_DONORS,
                array(
                    $this,
                    'dmm_page_donors'
                )
            );
        }

		add_submenu_page(
            DMM_PAGE_DONATIONS,
            __('Projects', 'funding') . ' | ' . __('Projects', 'funding'),
            __('Projects', 'funding'),
            DMM_PLUGIN_ROLE,
            'edit.php?post_type=ctx-project'
        );

		add_submenu_page(
            DMM_PAGE_DONATIONS,
            __('Countries', 'funding') . ' | ' . __('Countries', 'funding'),
            __('Countries', 'funding'),
            DMM_PLUGIN_ROLE,
            'edit.php?post_type=ctx-country'
        );

		add_submenu_page(
            DMM_PAGE_DONATIONS,
            __('Settings', 'funding') . ' | ' . __('Donate with Mollie', 'funding'),
            __('Settings', 'funding'),
            DMM_PLUGIN_ROLE,
            DMM_PAGE_SETTINGS,
            array(
                $this,
                'dmm_page_settings'
            )
        );

        // Hidden
        add_submenu_page(
            null,
            __('Donation', 'funding'),
            __('Donation', 'funding'),
            get_option('dmm_rights_donations', DMM_PLUGIN_ROLE),
            DMM_PAGE_DONATION,
            array(
                $this,
                'dmm_page_donation'
            )
        );
    }

    /**
     * Register settings
     *
     * @since 1.0.0
     */
    public function dmm_register_settings() {
        register_setting('dmm-settings-mollie', 'dmm_mollie_apikey');

        register_setting('dmm-settings-recurring', 'dmm_recurring');
        register_setting('dmm-settings-recurring', 'dmm_recurring_interval');
        register_setting('dmm-settings-recurring', 'dmm_default_interval');
        register_setting('dmm-settings-recurring', 'dmm_name_foundation');

        register_setting('dmm-settings-general', 'dmm_amount');
        register_setting('dmm-settings-general', 'dmm_currency');
        register_setting('dmm-settings-general', 'dmm_currency_switch');
        register_setting('dmm-settings-general', 'dmm_free_input');
        register_setting('dmm-settings-general', 'dmm_default_amount');
        register_setting('dmm-settings-general', 'dmm_minimum_amount');
        register_setting('dmm-settings-general', 'dmm_payment_description');
        register_setting('dmm-settings-general', 'dmm_methods_display');
        register_setting('dmm-settings-general', 'dmm_redirect_success');
        register_setting('dmm-settings-general', 'dmm_redirect_failure');
        //register_setting('dmm-settings-general', 'dmm_projects');
        register_setting('dmm-settings-general', 'dmm_rights_donations');
        register_setting('dmm-settings-general', 'dmm_rights_subscriptions');
        register_setting('dmm-settings-general', 'dmm_metadata');

        register_setting('dmm-settings-form', 'dmm_form_fields');
        
        register_setting('dmm-settings-form', 'dmm_gdpr_link');
    }

    /**
     * Donations table
     *
     * @return string
     * @since 1.0.0
     */
    public function dmm_page_donations()
    {
        if (!get_option('dmm_mollie_apikey')) {
            echo '<div class="error notice"><p>' . esc_html__('No API-key set', 'funding') . '</p></div>';
            return;
        }

        $mollie = new MollieApi(get_option('dmm_mollie_apikey'));

        if (isset($_GET['action']) && $_GET['action'] == 'refund' && isset($_GET['payment']) && check_admin_referer('refund-donation_' . $_GET['payment']))
        {
            try {
                $payment = $mollie->get('payments/' . sanitize_text_field($_GET['payment']));
                $mollie->post('payments/' . $payment->id . '/refunds', array(
                    'amount' => array(
                        'currency'  => $payment->amount->currency,
                        'value'     => $payment->amount->value
                    )
                ));

                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=refund-ok');
            } catch (\Exception $e) {
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=refund-nok');
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['payment']) && check_admin_referer('delete-donation_' . $_GET['payment']))
        {
            $update = $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . DMM_TABLE_DONATIONS . " WHERE payment_id = %s",
                $_GET['payment']
            ));

            if ($update)
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=delete-ok');
            else
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=delete-nok');
        }

        if (isset($_GET['msg']))
        {
            switch ($_GET['msg'])
            {
                case 'refund-ok':
                    $dmm_msg = '<div class="updated notice"><p>' . esc_html__('The donation is successful refunded to the donator', 'funding') . '</p></div>';
                    break;
                case 'refund-nok':
                    $dmm_msg = '<div class="error notice"><p>' . esc_html__('The donation can not be refunded', 'funding') . '</p></div>';
                    break;
                case 'delete-ok':
                    $dmm_msg = '<div class="updated notice"><p>' . esc_html__('The donation is successful deleted', 'funding') . '</p></div>';
                    break;
                case 'delete-nok':
                    $dmm_msg = '<div class="error notice"><p>' . esc_html__('The donation can not be deleted', 'funding') . '</p></div>';
                    break;
                case 'truncate-ok':
                    $dmm_msg = '<div class="updated notice"><p>' . esc_html__('The donations have been successfully removed from the database', 'funding') . '</p></div>';
                    break;
            }
        }

        $dmmTable = new Donations_Table();
        $dmmTable->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Donations', 'funding') ?></h2>

            <?php echo isset($dmm_msg) ? $dmm_msg : '';?>

            <form action="admin.php" style="float: right;">
                <input type="hidden" name="page" value="<?php echo DMM_PAGE_DONATIONS;?>">

                <?php if (current_user_can('export')): ?>
                    <a href="<?php echo admin_url('admin-post.php?action=dmm_export' . (isset($_GET['subscription']) ? '&subscription=' . sanitize_text_field($_GET['subscription']) : '') . (isset($_GET['search']) ? '&search=' . $_GET['search'] : ''));?>"><?php esc_html_e('Export', 'funding') ?></a>
                <?php endif ?>

                <input type="text" name="search" value="<?php echo esc_attr(isset($_GET['search']) ? $_GET['search'] : '');?>" placeholder="<?php esc_html_e('Search') ?>">
                <input type="submit" class="button action" value="<?php esc_html_e('Search') ?>">
            </form>

            <form method="post">
                <div class="alignleft actions">
                    <select name="action">
                        <option value="" selected='selected'>-------</option>
                        <option value="delete"><?php esc_html_e('Delete selected donations') ?></option>
                    </select>
                    <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
                    <input type="submit" id="doaction" class="button action" value="Submit"  />
                </div>

                <?php $dmmTable->display();?>
            </form>
        </div>
    <?php
    }

    public function dmm_export_donations()
    {
        if (!current_user_can('export')) {
            exit('No permissions');
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=donations.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            __('Date/time', 'funding'),
            __('Name', 'funding'),
            __('Company name', 'funding'),
            __('Email address', 'funding'),
            __('Phone number', 'funding'),
            __('Address', 'funding'),
            __('Zipcode', 'funding'),
            __('City', 'funding'),
            __('Country', 'funding'),
            __('Project', 'funding'),
            __('Message', 'funding'),
            __('Currency', 'funding'),
            __('Amount', 'funding'),
            __('Status', 'funding'),
            __('Payment method', 'funding'),
            __('Recurring payment', 'funding'),
            __('Donation ID', 'funding'),
            __('Payment ID', 'funding'),
        ));

        $where = '';
        if (isset($_GET['subscription'])) {
            $subscription = sanitize_title_for_query($_GET['subscription']);
            $where .= ' WHERE subscription_id="' . esc_sql($subscription) . '"';
        }

        if (isset($_GET['search'])) {
            $search = sanitize_title_for_query($_GET['search']);
            $where .= ($where ? ' AND' : ' WHERE') . ' (dm_name LIKE "%' . esc_sql($search) . '%" OR dm_email LIKE "%' . esc_sql($search) . '%" OR dm_company LIKE "%' . esc_sql($search) . '%" OR donation_id LIKE "%' . esc_sql($search) . '%" OR payment_id LIKE "%' . esc_sql($search) . '%")';
        }

        $donations = $this->wpdb->get_results("SELECT * FROM " . DMM_TABLE_DONATIONS . $where . " ORDER BY time DESC");
        foreach ($donations as $donation) {
            fputcsv($output, array(
                $donation->time,
                $donation->dm_name,
                $donation->dm_company,
                $donation->dm_email,
                $donation->dm_phone,
                $donation->dm_address,
                $donation->dm_zipcode,
                $donation->dm_city,
                $donation->dm_country,
                $donation->dm_project,
                trim(preg_replace('/\s+/', ' ', $donation->dm_message)),
                $donation->dm_currency,
                $donation->dm_amount,
                $donation->dm_status,
                $donation->payment_method,
                $donation->customer_id ? __('Yes', 'funding') : __('No', 'funding'),
                $donation->donation_id,
                $donation->payment_id,
            ));
        }
    }

    public function dmm_page_donation()
    {
        $donation = $this->wpdb->get_row("SELECT * FROM " . DMM_TABLE_DONATIONS . " WHERE id = '" . esc_sql($_REQUEST['id']) . "'");
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Donation', 'funding') ?></h2>

            <table class="widefat fixed striped">
                <thead>
                <tr valign="top">
                    <th id="empty" class="manage-column column-empty" style="width:5px;">&nbsp;</th>
                    <th id="a" class="manage-column column-a" style="width: 200px;">&nbsp;</th>
                    <th id="b" class="manage-column column-b">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Name', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_name);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Email address', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_email);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Company name', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_company);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Phone number', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_phone);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Address', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_address);?><br><?php echo esc_html($donation->dm_zipcode . ' ' . $donation->dm_city);?><br><?php echo esc_html($donation->dm_country);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Project', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_project);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Message', 'funding');?></strong></th>
                        <td class="column-b"><?php echo nl2br(esc_html($donation->dm_message));?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Amount', 'funding');?></strong></th>
                        <td class="column-b"><?php echo dmm_get_currency_symbol($donation->dm_currency) . ' ' . number_format($donation->dm_amount, dmm_get_currencies($donation->dm_currency), ',', '');?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Settlement amount', 'funding');?></strong></th>
                        <td class="column-b"><?php echo dmm_get_currency_symbol($donation->dm_settlement_currency) . ' ' . number_format($donation->dm_settlement_amount, dmm_get_currencies($donation->dm_settlement_currency ?: 'EUR'), ',', '');?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Payment method', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->payment_method);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Recurring payment', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->customer_id ? __('Yes', 'funding') : __('No', 'funding'));?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Payment status', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->dm_status);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Donation ID', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->donation_id);?></td>
                    </tr>
                    <tr>
                        <th class="column-empty"></th>
                        <th class="column-a" scope="row"><strong><?php esc_html_e('Payment ID', 'funding');?></strong></th>
                        <td class="column-b"><?php echo esc_html($donation->payment_id);?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function dmm_page_donors()
    {
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['customer']) && check_admin_referer('delete-donor_' . $_GET['customer']))
        {
            $mollie = new MollieApi(get_option('dmm_mollie_apikey'));

            try {
                $mollie->delete('customers/' . sanitize_text_field($_GET['customer']));

                $this->wpdb->query($this->wpdb->prepare("DELETE FROM " . DMM_TABLE_DONORS . " WHERE customer_id = %s",
                    $_GET['customer']
                ));

                $this->wpdb->query($this->wpdb->prepare("UPDATE " . DMM_TABLE_SUBSCRIPTIONS . " SET sub_status = 'canceled' WHERE customer_id = %s",
                    $_GET['customer']
                ));

                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']));
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }

        $dmmTable = new Donors_Table();
        $dmmTable->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Donors', 'funding') ?></h2>

            <form method="post">
                <div class="alignleft actions">
                    <select name="action">
                        <option value="" selected='selected'>-------</option>
                        <option value="delete"><?php esc_html_e('Delete selected donors') ?></option>
                    </select>
                    <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
                    <input type="submit" id="doaction" class="button action" value="Submit"  />
                </div>

                <?php $dmmTable->display();?>
            </form>
        </div>
        <?php
    }

    public function dmm_page_subscriptions()
    {
        if (!get_option('dmm_mollie_apikey')) {
            echo '<div class="error notice"><p>' . esc_html__('No API-key set', 'funding') . '</p></div>';
            return;
        }

        $mollie = new MollieApi(get_option('dmm_mollie_apikey'));

        if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['subscription']) && check_admin_referer('cancel-subscription_' . $_GET['subscription']))
        {
            $customer = $this->wpdb->get_row("SELECT * FROM " . DMM_TABLE_DONORS . " WHERE id = '" . esc_sql(sanitize_title_for_query($_GET['customer'])) . "'");

            try {
                $cancelledSub = $mollie->delete('customers/' . $customer->customer_id . '/subscriptions/' . sanitize_text_field($_GET['subscription']));
            } catch (\Exception $e) {
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=cancel-nok&status=unknown');
            }

            if ($cancelledSub->status == 'canceled')
            {
                $this->wpdb->query($this->wpdb->prepare("UPDATE " . DMM_TABLE_SUBSCRIPTIONS . " SET sub_status = %s WHERE subscription_id = %s",
                    $cancelledSub->status,
                    $_GET['subscription']
                ));
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=cancel-ok');
            }
            else
                wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=cancel-nok&status=' . $cancelledSub->status);
        }

        if (isset($_GET['msg']))
        {
            switch ($_GET['msg'])
            {
                case 'cancel-ok':
                    $dmm_msg = '<div class="updated notice"><p>' . esc_html__('The subscription is successful cancelled', 'funding') . '</p></div>';
                    break;
                case 'cancel-nok':
                    $dmm_msg = '<div class="error notice"><p>' . esc_html__('The subscription is not cancelled', 'funding') . '</p></div>';
                    break;
            }
        }

        $dmmTable = new Dmm_Subscriptions_Table();
        $dmmTable->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Subscriptions', 'funding') ?></h2>

            <?php
            echo isset($dmm_msg) ? $dmm_msg : '';

            $dmmTable->display();
            ?>
        </div>
        <?php
    }

    public function dmm_page_settings()
    {
        if (!isset($_GET['tab']))
            $tab = 'general';
        else
            $tab = sanitize_text_field($_GET['tab']);
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo DMM_PAGE_SETTINGS ?>" class="nav-tab<?php echo $tab === 'general' ? ' nav-tab-active' : '';?>"><?php esc_html_e('General', 'funding');?></a>
                <a href="?page=<?php echo DMM_PAGE_SETTINGS ?>&tab=form" class="nav-tab<?php echo $tab === 'form' ? ' nav-tab-active' : '';?>"><?php esc_html_e('Form', 'funding');?></a>
                <a href="?page=<?php echo DMM_PAGE_SETTINGS ?>&tab=mollie" class="nav-tab<?php echo $tab === 'mollie' ? ' nav-tab-active' : '';?>"><?php esc_html_e('Mollie settings', 'funding');?></a>
                <a href="?page=<?php echo DMM_PAGE_SETTINGS ?>&tab=recurring" class="nav-tab<?php echo $tab === 'recurring' ? ' nav-tab-active' : '';?>"><?php esc_html_e('Recurring payments', 'funding');?></a>
            </h2>
            <?php
            settings_errors();

            switch ($tab)
            {
                case 'recurring':
                    $this->dmm_tab_settings_recurring();
                    break;
                case 'mollie':
                    $this->dmm_tab_settings_mollie();
                    break;
                case 'form':
                    $this->dmm_tab_settings_form();
                    break;
                default:
                    $this->dmm_tab_settings_general();
            }
            ?>
        </div>
        <?php
    }

    private function dmm_tab_settings_general()
    {
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('dmm-settings-general');?>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Currency', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_currency">
                                <?php foreach (dmm_get_currencies() as $currency => $decimals): ?>
                                    <option value="<?php echo esc_attr($currency);?>" <?php echo (get_option('dmm_currency') === $currency ? 'selected' : '');?>><?php echo esc_attr($currency);?></option>
                                <?php endforeach;?>
                            </select><br>
                            <small><?php esc_html_e('Default currency used for preset amounts', 'funding');?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Amounts', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" size="50" name="dmm_amount" value="<?php echo esc_attr(get_option('dmm_amount'));?>"><br>
                            <small><?php printf(esc_html__('Separate amounts with /. Example: "%s"', 'funding'), '5,00/10,00/25,00/50,00');?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Donor can choose currency', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_currency_switch">
                                <option value="0"><?php esc_html_e('No', 'funding');?></option>
                                <option value="1" <?php echo (get_option('dmm_currency_switch') == '1' ? 'selected' : '');?>><?php esc_html_e('Yes', 'funding');?></option>
                            </select><br>
                            <small><?php esc_html_e('If enabled, the donor can choose their own currency for donations', 'funding');?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Free input', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="checkbox" name="dmm_free_input" <?php echo (get_option('dmm_free_input', 0) ? 'checked' : '');?>>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Default amount', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" size="50" name="dmm_default_amount" value="<?php echo esc_attr(get_option('dmm_default_amount'));?>">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Minimum amount', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" size="50" name="dmm_minimum_amount" value="<?php echo esc_attr(get_option('dmm_minimum_amount'));?>">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Payment description', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" size="50" name="dmm_payment_description" value="<?php echo esc_attr(get_option('dmm_payment_description', __('Donation') . ' {id}'));?>"><br>
                            <small><?php printf(esc_html__('You can use: %s', 'funding'), '{id} {name} {email} {project} {amount} {company} {interval}');?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Payment methods', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_methods_display">
                                <option value="list"><?php esc_html_e('Icons & text', 'funding');?></option>
                                <option value="list_no_icons" <?php echo (get_option('dmm_methods_display') === 'list_no_icons' ? 'selected' : '');?>><?php esc_html_e('Only text', 'funding');?></option>
                                <option value="list_icons" <?php echo (get_option('dmm_methods_display') === 'list_icons' ? 'selected' : '');?>><?php esc_html_e('Only icons', 'funding');?></option>
                                <option value="dropdown" <?php echo (get_option('dmm_methods_display') === 'dropdown' ? 'selected' : '');?>><?php esc_html_e('Dropdown', 'funding');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc"><?php esc_html_e('Page after successful donation', 'funding');?></th>
                        <td class="forminp"><?php $dmm_redirect_success = $this->get_page_id_by_slug(get_option('dmm_redirect_success'));wp_dropdown_pages(array('value_field' => 'post_name', 'selected' => $dmm_redirect_success, 'name' => 'dmm_redirect_success', 'show_option_no_change' => '-- ' . __('Default') . ' --'));?></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc"><?php esc_html_e('Page after failed donation', 'funding');?></th>
                        <td class="forminp"><?php $dmm_redirect_failure = $this->get_page_id_by_slug(get_option('dmm_redirect_failure'));wp_dropdown_pages(array('value_field' => 'post_name', 'selected' => $dmm_redirect_failure, 'name' => 'dmm_redirect_failure', 'show_option_no_change' => '-- ' . __('Default') . ' --'));?></td>
                    </tr>
                   
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Send metadata to Mollie', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_metadata">
                                <option value="1"><?php esc_html_e('Yes', 'funding');?></option>
                                <option value="0" <?php echo (get_option('dmm_metadata') == '0' ? 'selected' : '');?>><?php esc_html_e('No', 'funding');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Rights donations', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_rights_donations">
                                <option value="edit_dashboard"><?php esc_html_e('Administrator', 'funding');?></option>
                                <option value="edit_pages" <?php echo (get_option('dmm_rights_donations') === 'edit_pages' ? 'selected' : '');?>><?php esc_html_e('Editor', 'funding');?></option>
                                <option value="edit_posts" <?php echo (get_option('dmm_rights_donations') === 'edit_posts' ? 'selected' : '');?>><?php esc_html_e('Author', 'funding');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Rights subscriptions', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_rights_subscriptions">
                                <option value="edit_dashboard"><?php esc_html_e('Administrator', 'funding');?></option>
                                <option value="edit_pages" <?php echo (get_option('dmm_rights_subscriptions') === 'edit_pages' ? 'selected' : '');?>><?php esc_html_e('Editor', 'funding');?></option>
                                <option value="edit_posts" <?php echo (get_option('dmm_rights_subscriptions') === 'edit_posts' ? 'selected' : '');?>><?php esc_html_e('Author', 'funding');?></option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php submit_button();?>
        </form>
        <?php
    }

    private function dmm_tab_settings_form()
    {
        $dmm_form_fields = get_option('dmm_form_fields');
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('dmm-settings-form');?>

            <h3><?php esc_html_e('Fields', 'funding');?></h3>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <td class="forminp">
                        <table class="widefat fixed striped">
                            <thead>
                                <tr valign="top">
                                    <th id="empty" class="manage-column column-empty" style="width:5px;">&nbsp;</th>
                                    <th id="field" class="manage-column column-field"><?php esc_html_e('Field', 'funding');?></th>
                                    <th id="active" class="manage-column column-active"><?php esc_html_e('Active', 'funding');?></th>
                                    <th id="required" class="manage-column column-required"><?php esc_html_e('Required', 'funding');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Name', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Name][active]" <?php echo (isset($dmm_form_fields['Name']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Name][required]" <?php echo (isset($dmm_form_fields['Name']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Email address', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Email address][active]" <?php echo (isset($dmm_form_fields['Email address']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Email address][required]" <?php echo (isset($dmm_form_fields['Email address']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Phone number', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Phone number][active]" <?php echo (isset($dmm_form_fields['Phone number']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Phone number][required]" <?php echo (isset($dmm_form_fields['Phone number']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Address', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Address][active]" <?php echo (isset($dmm_form_fields['Address']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Address][required]" <?php echo (isset($dmm_form_fields['Address']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Company name', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Company name][active]" <?php echo (isset($dmm_form_fields['Company name']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Company name][required]" <?php echo (isset($dmm_form_fields['Company name']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Message', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Message][active]" <?php echo (isset($dmm_form_fields['Message']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Message][required]" <?php echo (isset($dmm_form_fields['Message']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('Project', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[Project][active]" <?php echo (isset($dmm_form_fields['Project']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[Project][required]" <?php echo (isset($dmm_form_fields['Project']['required']) ? 'checked' : '');?>></td>
                                </tr>
                                <tr>
                                    <th class="column-empty"></th>
                                    <th class="column-field" scope="row"><?php esc_html_e('GDPR checkbox', 'funding');?></th>
                                    <td class="column-active"><input type="checkbox" name="dmm_form_fields[GDPR checkbox][active]" <?php echo (isset($dmm_form_fields['GDPR checkbox']['active']) ? 'checked' : '');?>></td>
                                    <td class="column-required"><input type="checkbox" name="dmm_form_fields[GDPR checkbox][required]" <?php echo (isset($dmm_form_fields['GDPR checkbox']['required']) ? 'checked' : '');?>></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>

            <h3><?php esc_html_e('GDPR checkbox', 'funding');?></h3>
            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label><?php esc_html_e('Link to privacy policy', 'funding');?></label>
                    </th>
                    <td class="forminp">
                        <input type="text" size="50" name="dmm_gdpr_link" value="<?php echo esc_attr(get_option('dmm_gdpr_link'));?>">
                    </td>
                </tr>
                </tbody>
            </table>

            <?php submit_button();?>
        </form>
        <?php
    }

    private function dmm_tab_settings_recurring()
    {
        $recurring = false;
        try {
            if (!get_option('dmm_mollie_apikey')) {
                echo '<div class="error notice"><p>' . esc_html__('No API-key set', 'funding') . '</p></div>';
                return;
            }

            $mollie = new MollieApi(get_option('dmm_mollie_apikey'));

            if (count($mollie->all('methods', array('sequenceType' => 'recurring')))) {
                $recurring = true;
            }

        } catch (\Exception $e) {
            echo "<div class=\"error notice\"><p>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
        }
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('dmm-settings-recurring');?>

            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label><?php esc_html_e('Activate recurring payments', 'funding');?></label>
                    </th>
                    <td class="forminp">
                        <input type="checkbox" name="dmm_recurring" <?php echo get_option('dmm_recurring') ? 'checked' : '';?> value="1" <?php echo $recurring ? '' : 'disabled';?>><br>
                        <small><?php esc_html_e('Creditcard or SEPA Direct Debit is necessary', 'funding');?></small>
                    </td>
                </tr>


                <?php if (get_option('dmm_recurring')) {
                    $intervals = get_option('dmm_recurring_interval');
                    ?>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Name of the foundation', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <input type="text" size="50" name="dmm_name_foundation" value="<?php echo esc_attr(get_option('dmm_name_foundation'));?>">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Possible intervals', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <label><input type="checkbox" name="dmm_recurring_interval[month]" <?php echo isset($intervals['month']) ? 'checked' : '';?> value="1"> <?php esc_html_e('Monthly', 'funding');?></label><br>
                            <label><input type="checkbox" name="dmm_recurring_interval[quarter]" <?php echo isset($intervals['quarter']) ? 'checked' : '';?> value="1"> <?php esc_html_e('Each quarter', 'funding');?></label><br>
                                <label><input type="checkbox" name="dmm_recurring_interval[year]" <?php echo isset($intervals['year']) ? 'checked' : '';?> value="1"> <?php esc_html_e('Annually', 'funding');?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label><?php esc_html_e('Default interval', 'funding');?></label>
                        </th>
                        <td class="forminp">
                            <select name="dmm_default_interval">
                                <option value="one"><?php esc_html_e('One-time donation', 'funding');?></option>
                                <option value="month" <?php echo get_option('dmm_default_interval') === 'month' ? 'selected' : '';?>><?php esc_html_e('Monthly', 'funding');?></option>
                                <option value="quarter" <?php echo get_option('dmm_default_interval') === 'quarter' ? 'selected' : '';?>><?php esc_html_e('Each quarter', 'funding');?></option>
                                <option value="year" <?php echo get_option('dmm_default_interval') === 'year' ? 'selected' : '';?>><?php esc_html_e('Annually', 'funding');?></option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>

            <?php submit_button();?>
        </form>
        <?php
    }

    private function dmm_tab_settings_mollie()
    {
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('dmm-settings-mollie');?>

            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label><?php esc_html_e('API-key', 'funding');?></label>
                    </th>
                    <td class="forminp">
                        <input type="text" size="50" name="dmm_mollie_apikey" value="<?php echo esc_attr(get_option('dmm_mollie_apikey'));?>"><br>
                        <small><?php esc_html_e('Starts with live_ or test_', 'funding');?></small>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php submit_button();?>
        </form>
        <?php
    }

    public function get_page_id_by_slug($slug)
    {
        return $this->wpdb->get_var("SELECT id FROM " . $this->wpdb->posts . " WHERE post_name = '" . esc_sql(sanitize_title_for_query($slug)) . "' AND post_type = 'page'");
    }
}