<?php
namespace Contexis\Funding;
use Contexis\Funding\MollieApi;

class Start
{
    private $wpdb;

    /**
     * Dmm_Start constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('init', [$this, 'dmm_do_output_buffer']);
        add_filter('plugin_action_links_' . DMM_PLUGIN_BASE, [$this, 'dmm_settings_links']);
        add_shortcode('doneren_met_mollie', [$this, 'dmm_donate_form']);
        add_shortcode('doneren_met_mollie_total', [$this, 'dmm_donate_total']);
        add_shortcode('doneren_met_mollie_donors', [$this, 'dmm_donate_donors']);
        add_shortcode('doneren_met_mollie_goal', [$this, 'dmm_donate_goal']);

        // Variable translations
        __('iDEAL', 'funding');
        __('Creditcard', 'funding');
        __('Credit card', 'funding');
        __('Bancontact', 'funding');
        __('SOFORT Banking', 'funding');
        __('Bank transfer', 'funding');
        __('SEPA Direct Debit', 'funding');
        __('Belfius Pay Button', 'funding');
        __('PayPal', 'funding');
        __('Bitcoin', 'funding');
        __('Gift cards', 'funding');
        __('Paysafecard', 'funding');
        __('ING Home\'Pay', 'funding');
        __('KBC/CBC Payment Button', 'funding');
        __('Przelewy24', 'funding');
    }

    /**
     * Install/upgrade database
     *
     * @since 1.0.0
     */
    public function dmm_install_database()
    {
        $table_name          = DMM_TABLE_DONATIONS;
        $table_donors        = DMM_TABLE_DONORS;
        $table_subscriptions = DMM_TABLE_SUBSCRIPTIONS;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sqlDonations = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            dm_currency varchar(15) NOT NULL DEFAULT 'EUR',
            dm_amount float(15) NOT NULL,
            dm_settlement_currency varchar(15) NOT NULL DEFAULT 'EUR',
            dm_settlement_amount float(15) NOT NULL,
            payment_id varchar(45) NOT NULL,
            customer_id varchar(45),
            subscription_id varchar(45),
            payment_method varchar(45) NOT NULL,
            payment_mode varchar(45) NOT NULL,
            donation_id varchar(45) NOT NULL,
            dm_status varchar(25) NOT NULL,
            dm_name varchar(255) NOT NULL,
            dm_email varchar(255) NOT NULL,
            dm_phone varchar(255) NOT NULL,
            dm_company varchar(255) NOT NULL,
            dm_project varchar(255) NOT NULL,
            dm_address varchar(255) NOT NULL,
            dm_zipcode varchar(255) NOT NULL,
            dm_city varchar(255) NOT NULL,
            dm_country varchar(255) NOT NULL,
            dm_message text NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlDonations);

        $sqlDonors = "CREATE TABLE $table_donors (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            customer_id varchar(45) NOT NULL,
            customer_mode varchar(45) NOT NULL,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            sub_interval varchar(255) NOT NULL,
            sub_currency varchar(15) NOT NULL DEFAULT 'EUR',
            sub_amount float(15) NOT NULL,
            sub_settlement_currency varchar(15) NOT NULL DEFAULT 'EUR',
            sub_settlement_amount float(15) NOT NULL,
            sub_description varchar(255) NOT NULL,
            customer_locale varchar(15) NOT NULL,
            secret varchar(45) NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlDonors);

        $sqlSubscriptions = "CREATE TABLE $table_subscriptions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            subscription_id varchar(45) NOT NULL,
            customer_id varchar(45) NOT NULL,
            sub_mode varchar(45) NOT NULL,
            sub_currency varchar(15) NOT NULL DEFAULT 'EUR',
            sub_amount float(15) NOT NULL,
            sub_settlement_currency varchar(15) NOT NULL DEFAULT 'EUR',
            sub_settlement_amount float(15) NOT NULL,
            sub_times int(9) NOT NULL,
            sub_interval varchar(45) NOT NULL,
            sub_description varchar(255) NOT NULL,
            sub_method varchar(45) NOT NULL,
            sub_status varchar(25) NOT NULL,
            created_at timestamp NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlSubscriptions);

        if (get_option('dmm_v251_updated') != 2) {
            dbDelta("UPDATE $table_name SET dm_settlement_currency = 'EUR', dm_settlement_amount = dm_amount");
            dbDelta("UPDATE $table_donors SET sub_settlement_currency = 'EUR', sub_settlement_amount = sub_amount");
            dbDelta("UPDATE $table_subscriptions SET sub_settlement_currency = 'EUR', sub_settlement_amount = sub_amount");

            update_option('dmm_v251_updated', 2);
        }

        update_option('dmm_version', DMM_VERSION);
    }

    /**
     * Settings link in plugin list
     *
     * @param $links
     *
     * @return mixed
     * @since 1.0.0
     */
    public function dmm_settings_links($links)
    {
        $settings_link = '<a href="admin.php?page=' . DMM_PAGE_SETTINGS . '">' . __('Settings', 'funding') .
                         '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Output buffer for redirects
     *
     * @since 1.0.0
     */
    public function dmm_do_output_buffer()
    {
        ob_start();
    }

    /**
     * Shortcode for total donations
     *
     * @param $atts
     *
     * @return string
     * @since 2.3.0
     */
    public function dmm_donate_total($atts)
    {
        $atts = shortcode_atts([
                'project' => '',
                'start'   => 0.00,
        ], $atts);

        ob_start();
        $sum = $this->wpdb->get_var("SELECT SUM(dm_settlement_amount) FROM " . DMM_TABLE_DONATIONS .
                                    " WHERE dm_status='paid' AND payment_mode='live'" .
                                    ($atts['project'] ? " AND dm_project='" . esc_sql(trim($atts['project'])) . "'" :
                                            ''));
        echo '&euro; ' . number_format(($sum + (float) $atts['start']), 2, ',', '');

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Shortcode for total donors
     *
     * @param $atts
     *
     * @return string
     * @since 2.8.2
     */
    public function dmm_donate_donors($atts)
    {
        $atts = shortcode_atts(['start' => 0, 'unique_email' => 'true'], $atts);

        ob_start();
        $count = $this->wpdb->get_var("SELECT COUNT(" .
                                      ($atts['unique_email'] == 'true' ? "DISTINCT customer_email" : "*") . ") FROM " .
                                      DMM_TABLE_DONORS . " WHERE customer_mode='live'");
        echo $count + (int) $atts['start'];

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Shortcode for goal donations
     *
     * @param $atts
     *
     * @return string
     * @since 2.4.8
     *
     */
    public function dmm_donate_goal($atts)
    {
        $atts = shortcode_atts([
                'goal' => '',
                'text' => __('Goal reached!', 'funding'),
        ], $atts);

        ob_start();

        if ($atts['goal'] < 0) {
            echo __('Goal must be higher then 0', 'funding');
        } else {
            $sum = $this->wpdb->get_var("SELECT SUM(dm_settlement_amount) FROM " . DMM_TABLE_DONATIONS .
                                        " WHERE dm_status='paid' AND payment_mode='live'");

            $goal = (int) $atts['goal'] - $sum;

            if ($goal <= 0) {
                echo __($atts['text'], 'funding');
            } else {
                echo '&euro; ' . number_format($goal, 2, ',', '');
            }
        }

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Donation form
     *
     * @return string
     * @throws \Mollie\Api\Exceptions\ApiException
     * @throws \Mollie\Api\Exceptions\IncompatiblePlatform
     * @since 1.0.0
     */
    public function dmm_donate_form($data = ["preview" => false])
    {
		$preview = $data['preview'];
        ob_start();
		
        try {
            if (!get_option('dmm_mollie_apikey')) {
                return __('No API-key set', 'funding');
            }

            $mollie = new MollieApi(get_option('dmm_mollie_apikey'));

            $dmm_webhook = get_home_url(null, DMM_WEBHOOK);
            $dmm_fields  = get_option('dmm_form_fields');

            // Submit form, add donation
            if (isset($_POST['dmm_submitted'])) {
                // Validation
                $errors = [];
                if (((isset($dmm_fields['Name']['required']) && $dmm_fields['Name']['required']) ||
                     $_POST['dmm_recurring_interval'] != 'one') && empty($_POST['dmm_name'])) {
                    $errors[] = __('Your name is required', 'funding');
                }

                if (((isset($dmm_fields['Email address']['required']) && $dmm_fields['Email address']['required']) ||
                     $_POST['dmm_recurring_interval'] != 'one') && empty($_POST['dmm_email'])) {
                    $errors[] = __('Your email address is required', 'funding');
                }

                if ($_POST['dmm_recurring_interval'] != 'one' && !isset($_POST['dmm_permission'])) {
                    $errors[] = __('Please give authorization to collect from your account', 'funding');
                }

                if (isset($dmm_fields['GDPR checkbox']['required']) && $dmm_fields['GDPR checkbox']['required'] &&
                    !isset($_POST['dmm_gdpr'])) {
                    $errors[] = __('Please agree to our Privacy Policy', 'funding');
                }

                if (isset($dmm_fields['Phone number']['required']) && $dmm_fields['Phone number']['required'] &&
                    empty($_POST['dmm_phone'])) {
                    $errors[] = __('Your phone number is required', 'funding');
                }

                if (isset($dmm_fields['Company name']['required']) && $dmm_fields['Company name']['required'] &&
                    empty($_POST['dmm_company'])) {
                    $errors[] = __('Your company name is required', 'funding');
                }

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] &&
                    empty($_POST['dmm_address'])) {
                    $errors[] = __('Your street is required', 'funding');
                }

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] &&
                    empty($_POST['dmm_city'])) {
                    $errors[] = __('Your city is required', 'funding');
                }

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] &&
                    empty($_POST['dmm_zipcode'])) {
                    $errors[] = __('Your zipcode is required', 'funding');
                }

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] &&
                    empty($_POST['dmm_country'])) {
                    $errors[] = __('Your country is required', 'funding');
                }

                if (isset($dmm_fields['Message']['required']) && $dmm_fields['Message']['required'] &&
                    empty($_POST['dmm_message'])) {
                    $errors[] = __('A message is required', 'funding');
                }

                if (isset($dmm_fields['Project']['required']) && $dmm_fields['Project']['required'] &&
                    empty($_POST['dmm_project'])) {
                    $errors[] = __('Please choose a project', 'funding');
                }

                if (empty($_POST['dmm_amount'])) {
                    $errors[] = __('Please choose an amount', 'funding');
                }

                if ($_POST['dmm_amount'] < (float) get_option('dmm_minimum_amount', 1)) {
                    $errors[] = __('The amount is too low, please choose a higher amount', 'funding');
                }

                // Hook to validate custom fields
                $errors = apply_filters('dmm_donate_form_validation', $errors);

                if (!empty($errors)) {
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li style="color: red;">' . $error . '</li>';
                    }
                    echo '</ul>';
                } else {
                    $donation_id = uniqid(rand(1, 99));
                    $amount      = number_format(str_replace(',', '.', sanitize_text_field($_POST['dmm_amount'])), 2, '.', '');

                    // Hook to handle POST data for custom fields
                    do_action('dmm_donate_form_posted');

                    $interval = '';
                    if (isset($_POST['dmm_recurring_interval'])) {
                        switch ($_POST['dmm_recurring_interval']) {
                            case 'one':
                                $interval = __('One-time donation', 'funding');
                                break;
                            case 'month':
                                $interval = __('Monthly', 'funding');
                                break;
                            case 'quarter':
                                $interval = __('Each quarter', 'funding');
                                break;
                            case 'year':
                                $interval = __('Annually', 'funding');
                                break;
                        }
                    }

                    $description = str_replace(
                            [
                                    '{id}',
                                    '{name}',
                                    '{project}',
                                    '{amount}',
                                    '{company}',
                                    '{email}',
                                    '{interval}',
                            ],
                            [
                                    $donation_id,
                                    isset($_POST['dmm_name']) ? sanitize_text_field($_POST['dmm_name']) : '',
                                    isset($_POST['dmm_project']) ? sanitize_text_field($_POST['dmm_project']) : '',
                                    $amount,
                                    isset($_POST['dmm_company']) ? sanitize_text_field($_POST['dmm_company']) : '',
                                    isset($_POST['dmm_email']) ? sanitize_email($_POST['dmm_email']) : '',
                                    $interval,
                            ],
                            sanitize_text_field(get_option('dmm_payment_description'))
                    );


                    if (is_home()) {
                        $redirectBaseUrl = home_url() . '/';
                    } else {
                        $redirectBaseUrl = get_page_link();
                    }

                    $metadata = null;
                    if (get_option('dmm_metadata') != '0') {
                        $metadata = [
                                "name"        => isset($_POST['dmm_name']) ? sanitize_text_field($_POST['dmm_name']) :
                                        '',
                                "email"       => isset($_POST['dmm_email']) ? sanitize_email($_POST['dmm_email']) : '',
                                "project"     => isset($_POST['dmm_project']) ?
                                        sanitize_text_field($_POST['dmm_project']) :
                                        '',
                                "company"     => isset($_POST['dmm_company']) ?
                                        sanitize_text_field($_POST['dmm_company']) :
                                        '',
                                "address"     => isset($_POST['dmm_address']) ?
                                        sanitize_text_field($_POST['dmm_address']) :
                                        '',
                                "zipcode"     => isset($_POST['dmm_zipcode']) ?
                                        sanitize_text_field($_POST['dmm_zipcode']) :
                                        '',
                                "city"        => isset($_POST['dmm_city']) ? sanitize_text_field($_POST['dmm_city']) :
                                        '',
                                "country"     => isset($_POST['dmm_country']) ?
                                        sanitize_text_field($_POST['dmm_country']) :
                                        '',
                                "message"     => isset($_POST['dmm_message']) ?
                                        sanitize_textarea_field($_POST['dmm_message']) : '',
                                "phone"       => isset($_POST['dmm_phone']) ? sanitize_text_field($_POST['dmm_phone']) :
                                        '',
                                "donation_id" => $donation_id,
                        ];
                    }

                    $secret   = uniqid();
                    $customer = $mollie->post('customers', [
                            "name"  => isset($_POST['dmm_name']) ? sanitize_text_field($_POST['dmm_name']) : '',
                            "email" => isset($_POST['dmm_email']) ? sanitize_email($_POST['dmm_email']) : '',
                    ]);

                    do_action('dmm_customer_created', $customer);

                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO " . DMM_TABLE_DONORS . "
                    ( customer_id, customer_mode, customer_name, customer_email, sub_interval, sub_currency, sub_amount, sub_description, customer_locale, secret )
                    VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
                            $customer->id,
                            $customer->mode,
                            $customer->name,
                            $customer->email,
                            sanitize_text_field($_POST['dmm_recurring_interval']),
                            sanitize_text_field($_POST['dmm_currency']),
                            $amount,
                            $description,
                            $customer->locale,
                            $secret
                    ));

                    if ($_POST['dmm_recurring_interval'] == 'one') {
                        // One-time donation
                        $payment = $mollie->post('payments', [
                                "amount"      => [
                                        "currency" => sanitize_text_field($_POST['dmm_currency']),
                                        "value"    => (string) $amount,
                                ],
                                "description" => esc_html($description),
                                "redirectUrl" => $redirectBaseUrl . '?dmm_id=' . $donation_id,
                                "webhookUrl"  => $dmm_webhook,
                                "method"      => esc_html($_POST['dmm_method']) ?: null,
                                "metadata"    => $metadata,
                                'customerId'  => $customer->id,
                        ]);
                    } else {
                        $payment = $mollie->post('payments', [
                                "amount"       => [
                                        "currency" => sanitize_text_field($_POST['dmm_currency']),
                                        "value"    => (string) $amount,
                                ],
                                'customerId'   => $customer->id,
                                'sequenceType' => 'first',
                                "description"  => $description,
                                "redirectUrl"  => $redirectBaseUrl . '?dmm_id=' . $donation_id,
                                "webhookUrl"   => $dmm_webhook . 'first/' . $this->wpdb->insert_id . '/secret/' .
                                                  $secret,
                                "method"       => sanitize_text_field($_POST['dmm_method']),
                                "metadata"     => $metadata,
                        ]);
                    }

                    $this->wpdb->query($this->wpdb->prepare("UPDATE " . DMM_TABLE_DONORS .
                                                            " SET sub_settlement_currency = %s, sub_settlement_amount = %s WHERE secret = %s",
                            $payment->settlementAmount->currency,
                            $payment->settlementAmount->value,
                            $secret
                    ));

                    do_action('dmm_payment_created', $payment);

                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO " . DMM_TABLE_DONATIONS . "
                    ( `time`, payment_id, customer_id, donation_id, dm_status, dm_currency, dm_amount, dm_settlement_currency, dm_settlement_amount, dm_name, dm_email, dm_project, dm_company, dm_address, dm_zipcode, dm_city, dm_country, dm_message, dm_phone, payment_method, payment_mode )
                    VALUES ( %s, %s, %s, %s, 'open', %s, %f, %s, %f, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
                            date('Y-m-d H:i:s'),
                            $payment->id,
                            (isset($customer) ? $customer->id : null),
                            $donation_id,
                            $payment->amount->currency,
                            $payment->amount->value,
                            $payment->settlementAmount->currency ?: $payment->amount->currency,
                            $payment->settlementAmount->value ?: $payment->amount->value,
                            isset($_POST['dmm_name']) ? sanitize_text_field($_POST['dmm_name']) : null,
                            isset($_POST['dmm_email']) ? sanitize_email($_POST['dmm_email']) : null,
                            isset($_POST['dmm_project']) ? sanitize_text_field($_POST['dmm_project']) : null,
                            isset($_POST['dmm_company']) ? sanitize_text_field($_POST['dmm_company']) : null,
                            isset($_POST['dmm_address']) ? sanitize_text_field($_POST['dmm_address']) : null,
                            isset($_POST['dmm_zipcode']) ? sanitize_text_field($_POST['dmm_zipcode']) : null,
                            isset($_POST['dmm_city']) ? sanitize_text_field($_POST['dmm_city']) : null,
                            isset($_POST['dmm_country']) ? sanitize_text_field($_POST['dmm_country']) : null,
                            isset($_POST['dmm_message']) ? sanitize_textarea_field($_POST['dmm_message']) : null,
                            isset($_POST['dmm_phone']) ? sanitize_text_field($_POST['dmm_phone']) : null,
                            $payment->method,
                            $payment->mode
                    ));

                    wp_redirect($payment->_links->checkout->href);
                    exit;
                }

            }

            // Return page
            if (isset($_GET['dmm_id'])) {
                $donation = $this->wpdb->get_row("SELECT * FROM " . DMM_TABLE_DONATIONS . " WHERE donation_id = '" .
                                                 esc_sql($_GET['dmm_id']) . "'");
                $payment  = $mollie->get('payments/' . $donation->payment_id);

                if (isset($payment->paidAt) && $payment->paidAt) {
                    if (!isset($_GET['dmm_redirect'])) {
                        wp_redirect(get_option('dmm_redirect_success') != '-1' ?
                                get_permalink($this->get_page_id_by_slug(get_option('dmm_redirect_success'))) :
                                get_page_link() . '?dmm_redirect=true&dmm_id=' . sanitize_text_field($_GET['dmm_id']));
                        exit;
                    }

                    echo '<div class="alert alert--success">' .
                         esc_html__('Thank you for your donation!', 'funding') . '</div>';

                    // Hook to add logic after the donation has been paid
                    do_action('dmm_donate_form_paid', $donation, $payment);
                } else {
                    if (!isset($_GET['dmm_redirect'])) {
                        wp_redirect(get_option('dmm_redirect_failure') != '-1' ?
                                get_permalink($this->get_page_id_by_slug(get_option('dmm_redirect_failure'))) :
                                get_page_link() . '?dmm_redirect=true&dmm_id=' . sanitize_text_field($_GET['dmm_id']));
                        exit;
                    }

                    echo '<div class="alert alert--error">' .
                         esc_html__('The payment was not successful, please try again.', 'funding') . '</div>';
                }
            } else {
                // Donation form

                $intervals = get_option('dmm_recurring_interval');

                $selected_interval = isset($_POST['dmm_recurring_interval']) ?
                        sanitize_text_field($_POST['dmm_recurring_interval']) : get_option('dmm_default_interval');
                ?>
                <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>"
                      class="form grid grid--columns-1 grid--gap-8"
                      method="post">

                    <?php
                    // Hook to add custom form fields on top of the form
                    do_action('dmm_donate_form_top'); ?>

                    <?php if (get_option('dmm_recurring')) { ?>
                        <div class="input">
                            <select id="dmm_interval"
                                    name="dmm_recurring_interval"
                                    style="width: 100%"
                                    onchange="dmm_recurring_methods(this.value);">
                                <option value="one"><?php echo esc_html_e('One-time donation', 'funding'); ?></option>
                                <?php if (isset($intervals['month'])) { ?>
                                    <option value="month" <?php echo($selected_interval === 'month' ? 'selected' :
                                            ''); ?>><?php echo esc_html_e('Monthly', 'funding'); ?></option>
                                <?php } ?>
                                <?php if (isset($intervals['quarter'])) { ?>
                                    <option value="quarter" <?php echo($selected_interval === 'quarter' ? 'selected' :
                                            ''); ?>><?php echo esc_html_e('Each quarter', 'funding'); ?></option>
                                <?php } ?>
                                <?php if (isset($intervals['year'])) { ?>
                                    <option value="year" <?php echo($selected_interval === 'year' ? 'selected' :
                                            ''); ?>><?php echo esc_html_e('Annually', 'funding'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="dmm_recurring_interval" value="one">
                    <?php } ?>

                    <?php if (isset($dmm_fields['Name']['active']) && $dmm_fields['Name']['active']) { ?>
                        <div class="input">
                            <label for="dmm_name"><?php echo esc_html_e('Name', 'funding') .
                                                             (isset($dmm_fields['Name']['required']) &&
                                                              $dmm_fields['Name']['required'] ?
                                                                     '<span style="color:red;">*</span>' :
                                                                     ''); ?></label>
                            <input type="text"
                                   id="dmm_name"
								   required
                                   name="dmm_name"
                                   value="<?php echo(isset($_POST["dmm_name"]) ? esc_attr($_POST["dmm_name"]) : ''); ?>"
                                   style="width: 100%">
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Company name']['active']) &&
                              $dmm_fields['Company name']['active']) { ?>
                        <div class="input">
                            <label for="dmm_company"><?php echo esc_html_e('Company name', 'funding') .
                                                                (isset($dmm_fields['Company name']['required']) &&
                                                                 $dmm_fields['Company name']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <input type="text"
                                   id="dmm_company"
                                   name="dmm_company"
                                   value="<?php echo(isset($_POST["dmm_company"]) ? esc_attr($_POST["dmm_company"]) :
                                           ''); ?>"
                                   style="width: 100%">
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Email address']['active']) &&
                              $dmm_fields['Email address']['active']) { ?>
                        <div class="input">
                            <label for="dmm_email"><?php echo esc_html_e('Email address', 'funding') .
                                                              (isset($dmm_fields['Email address']['required']) &&
                                                               $dmm_fields['Email address']['required'] ?
                                                                      '<span style="color:red;">*</span>' :
                                                                      ''); ?></label>
                            <input type="email"
                                   id="dmm_email"
                                   name="dmm_email"
                                   value="<?php echo(isset($_POST["dmm_email"]) ? esc_attr($_POST["dmm_email"]) :
                                           ''); ?>"
                                   style="width: 100%" required>
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Phone number']['active']) &&
                              $dmm_fields['Phone number']['active']) { ?>
                        <div class="input">
                            <label for="dmm_phone"><?php echo esc_html_e('Phone number', 'funding') .
                                                              (isset($dmm_fields['Phone number']['required']) &&
                                                               $dmm_fields['Phone number']['required'] ?
                                                                      '<span style="color:red;">*</span>' :
                                                                      ''); ?></label>
                            <input type="text"
                                   id="dmm_phone"
                                   name="dmm_phone"
                                   value="<?php echo(isset($_POST["dmm_phone"]) ? esc_attr($_POST["dmm_phone"]) :
                                           ''); ?>"
                                   style="width: 100%">
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Address']['active']) && $dmm_fields['Address']['active']) { ?>
                        <div class="input">
                            <label for="dmm_address"><?php echo esc_html_e('Address', 'funding') .
                                                                (isset($dmm_fields['Address']['required']) &&
                                                                 $dmm_fields['Address']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <input type="text"
                                   id="dmm_address"
                                   name="dmm_address"
                                   value="<?php echo(isset($_POST["dmm_address"]) ? esc_attr($_POST["dmm_address"]) :
                                           ''); ?>"
                                   style="width: 100%">
                        </div>
                        <div class="input input--half">
                            <label for="dmm_zipcode"><?php echo esc_html_e('Zipcode', 'funding') .
                                                                (isset($dmm_fields['Address']['required']) &&
                                                                 $dmm_fields['Address']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <input type="text"
                                   id="dmm_zipcode"
                                   name="dmm_zipcode"
                                   value="<?php echo(isset($_POST["dmm_zipcode"]) ? esc_attr($_POST["dmm_zipcode"]) :
                                           ''); ?>"
                                   style="width: 100%">
                        </div>
                        <div class="input input--half">
                            <label for="dmm_city"><?php echo esc_html_e('City', 'funding') .
                                                             (isset($dmm_fields['Address']['required']) &&
                                                              $dmm_fields['Address']['required'] ?
                                                                     '<span style="color:red;">*</span>' :
                                                                     ''); ?></label>
                            <input type="text"
                                   id="dmm_city"
                                   name="dmm_city"
                                   value="<?php echo(isset($_POST["dmm_city"]) ? esc_attr($_POST["dmm_city"]) : ''); ?>"
                                   style="width: 100%">
                        </div>
                        <div class="input">
                            <label for="dmm_country"><?php echo esc_html_e('Country', 'funding') .
                                                                (isset($dmm_fields['Address']['required']) &&
                                                                 $dmm_fields['Address']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <input type="text"
                                   id="dmm_country"
                                   name="dmm_country"
                                   value="<?php echo(isset($_POST["dmm_country"]) ? esc_attr($_POST["dmm_country"]) :
                                           ''); ?>"
                                   style="width: 100%">
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Project']['active']) && $dmm_fields['Project']['active']) { ?>
                        <div class="select">
                            <label for="dmm_project"><?php echo esc_html_e('Project', 'funding') .
                                                                (isset($dmm_fields['Project']['required']) &&
                                                                 $dmm_fields['Project']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <?php echo $this->dmm_projects(isset($_POST["dmm_project"]) ?
                                    sanitize_text_field($_POST["dmm_project"]) : ''); ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Message']['active']) && $dmm_fields['Message']['active']) { ?>
                        <div class="textarea>
                            <label for="dmm_message"><?php echo esc_html_e('Message', 'funding') .
                                                                (isset($dmm_fields['Message']['required']) &&
                                                                 $dmm_fields['Message']['required'] ?
                                                                        '<span style="color:red;">*</span>' :
                                                                        ''); ?></label>
                            <textarea id="dmm_message"
                                      name="dmm_message"
                                      rows="5"
                                      style="width: 100%"><?php echo(isset($_POST["dmm_message"]) ?
                                        esc_attr($_POST["dmm_message"]) : ''); ?></textarea>
                        </>
                    <?php } ?>

                    <div class="form__group">
                        <?php
                        if (get_option('dmm_currency_switch') == '1') {
							
                            echo '<div class="input"><label for="dmm_currency">' . esc_html_e('Currency', 'funding') .
                                 '</label>';

                            echo '<select name="dmm_currency" class=" id="dmm_currency" onchange="dmm_multicurrency_methods(this.value);" style="width: 100%;">';

                            foreach (dmm_get_currencies() as $currency => $decimals) {
                                $symbol = dmm_get_currency_symbol($currency);
                                echo '<option ' . (get_option('dmm_currency') === $currency ? 'selected' : '') .
                                     ' value="' . esc_html($currency) . '">' . esc_html($currency) .
                                     ($symbol != $currency ? ' (' . esc_html($symbol) . ')' : '') . '</option>';
                            }

                            echo '</select></div>';
                        } else {
                            echo '<input type="hidden" name="dmm_currency" id="dmm_currency" value="' .
                                 esc_attr(get_option('dmm_currency', 'EUR')) . '">';
                        }


                        if (get_option('dmm_amount')) {
							echo '<div class="input">';
							echo '<label for="dmm_amount">' . esc_html_e('Select amount', 'funding') .
                             ' (<span id="dmm_currency_symbol"></span>) </label>';
                            if (get_option('dmm_free_input')) {
                                echo '<select id="dmm_dd" style="width: 100%" onchange="if(this.value!=\'--\'){document.getElementById(\'dmm_amount\').value=this.value.replace(\',\', \'.\');document.getElementById(\'dmm_amount\').style.display = \'none\';}else{document.getElementById(\'dmm_amount\').style.display = \'block\';}">';
                                echo '<option value="--">' . esc_html__('Enter your own amount', 'funding') .
                                     '</option>';
                            } else {
                                echo '<select style="width: 100%" id="dmm_amount" name="dmm_amount">';
                            }

                            foreach (explode('/', get_option('dmm_amount')) as $amount) {
                                echo '<option value="' . trim(esc_attr($amount)) . '"' .
                                     (get_option('dmm_default_amount') == trim($amount) ? ' selected' : '') . '>' .
                                     esc_html($amount) . '</option>';
                            }
                            echo '</select></div>';
                        }

                        if (get_option('dmm_free_input')) {
                            echo '<div class="input">' . '<label for="dmm_amount">' . __('Amount', 'funding') . '</label>';
							     
                             echo     '<input type="number" step="any" min="' . (str_replace(',', '.', esc_attr(get_option('dmm_minimum_amount'))) ?: 1) . '" id="dmm_amount" name="dmm_amount" " value="' .
                                 esc_attr(isset($_POST["dmm_amount"]) ? $_POST["dmm_amount"] :
                                         str_replace(',', '.', get_option('dmm_default_amount'))) .
                                 '"></div>';
                        } else {
                            echo '<input type="hidden" name="dmm_currency" id="dmm_currency" value="' .
                                 esc_attr(get_option('dmm_currency', 'EUR')) . '">';
                        }
                        ?>
                    </div> 

                    <div class="form__group"><?php echo $this->dmm_payment_methods($mollie); ?></div>

                    
                    <script>
                        window.onload = function () {
                            var dmm_dd = document.getElementById('dmm_dd');
                            if (dmm_dd !== null) {
                                if (dmm_dd.value !== '--') {
                                    document.getElementById('dmm_amount').value = document.getElementById('dmm_dd').value.replace(',', '.');
                                    document.getElementById('dmm_amount').style.display = 'none';
                                }
                            }
                            <?php if (get_option('dmm_recurring')) { ?>
                            if (document.getElementById('dmm_interval').value !== 'one') {
                                document.getElementById('dmm_permission').style.display = 'block';
                            }
                            dmm_recurring_methods(document.getElementById('dmm_interval').value);
                            <?php } ?>
                            dmm_multicurrency_methods(document.getElementById('dmm_currency').value);
                        }
                    </script>
                    <label for="dmm_permission_field" id="dmm_permission" style="display:none"><input type="checkbox"
                                                                                                      id="dmm_permission_field"
                                                                                                      name="dmm_permission"> <?php echo sprintf(__('I hereby authorize %s to collect the amount shown above from my account periodically.', 'funding'), esc_html(get_option('dmm_name_foundation'))); ?>
                    </label>

                    <?php if (isset($dmm_fields['GDPR checkbox']['active']) &&
                              $dmm_fields['GDPR checkbox']['active']) { ?>
                        <p>
                            <label for="dmm_gdpr"><input type="checkbox" id="dmm_gdpr" name="dmm_gdpr">
                                <?php echo __('I hereby agree to the', 'funding'); ?>
                                <a target="_blank" href="<?php echo esc_attr(get_option('dmm_gdpr_link', '#')); ?>">
                                    <?php echo __('Privacy Policy', 'funding'); ?>
                                </a>
                            </label>

                        </p>
                    <?php } ?>

                    <?php
                    // Hook to add custom form fields at the bottom of the form
                    do_action('dmm_donate_form_bottom'); ?>
					<div class="button-group button-group--right">
                    <input type="submit"
                           name="dmm_submitted"
                           value="<?php echo esc_attr(__('Donate', 'funding')); ?>"
						   <?php echo $preview ? 'disabled' : '' ?>
					>
					</div>	   
					
                </form>
                <?php

            }


        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Payment methods
     *
     * @param $mollie MollieApi
     *
     * @return string
     * @throws Exception
     * @since 2.0.0
     */
    private function dmm_payment_methods($mollie)
    {
        $option  = get_option('dmm_methods_display', 'list');
        $methods = '';

        if (get_option('dmm_recurring')) {
            $recurring = ['dd' => false, 'cc' => false];
            foreach ($mollie->all('methods', ['sequenceType' => 'recurring']) as $method) {
                if ($method->id === 'directdebit') {
                    $recurring['dd'] = true;
                }
                if ($method->id === 'creditcard') {
                    $recurring['cc'] = true;
                }
            }

            $scriptCC = '';
            if ($recurring['cc'] === false) {
                $scriptCC = '
                var x = document.getElementsByClassName("dmm_cc");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = value!="one" ? "none" : "flex";
                    x[i].disabled = value!="one" ? "disabled" : "";
                }';
            }

            $scriptDD = '';
            if ($recurring['dd'] === false) {
                $scriptDD = '
                var x = document.getElementsByClassName("dmm_dd");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = value!="one" ? "none" : "flex";
                    x[i].disabled = value!="one" ? "disabled" : "";
                }';
            }

            $methods .= '
            <script>
            function dmm_recurring_methods(value) {
                var x = document.getElementsByClassName("dmm_recurring");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = (value!="one" ? "none" : "flex");
                    x[i].disabled = (value!="one" ? "disabled" : "");
                }
                ' . $scriptCC . $scriptDD . '
                document.getElementById("dmm_permission").style.display = (value == "one" ? "none" : "flex");
            }
            </script>';
        }

        $currencySymbol = [];
        foreach (dmm_get_currencies() as $currency => $p) {
            $currencySymbol[$currency] = dmm_get_currency_symbol($currency);
        }

        $methods .= '
        <script>
        function dmm_multicurrency_methods(value) {
            let dmm_currencies = ' . json_encode($currencySymbol) . ';
            document.getElementById("dmm_currency_symbol").innerHTML = dmm_currencies[value];
            
            let x = document.getElementsByClassName("dmm_nomc");
            for (let i = 0; i < x.length; i++) {
                x[i].style.display = (value!="EUR" ? "none" : "flex");
                x[i].disabled = (value!="EUR" ? "disabled" : "");
            }
        }
        </script>';

        $first = true;
        if ($option === 'list') {
            foreach ($mollie->all('methods') as $method) {
                $methods .= '<div class="radio"><label class="' . esc_attr($this->dmm_pm_class($method->id)) .
                            '"><input type="radio" name="dmm_method" value="' . esc_attr($method->id) . '" ' .
                            ($first ? 'checked' : '') .
                            '> <img class="mr-4" src="' .
                            sanitize_text_field($method->image->size1x) . '"> ' .
                            esc_html__($method->description, 'funding') . '</label></div>';
                $first   = false;
            }
        } elseif ($option === 'list_no_icons') {
            foreach ($mollie->all('methods') as $method) {
                $methods .= '<div class="radio"><label class="' . esc_attr($this->dmm_pm_class($method->id)) .
                            '"><input type="radio" name="dmm_method" value="' . esc_attr($method->id) . '" ' .
                            ($first ? 'checked' : '') . '> ' . esc_html__($method->description, 'funding') .
                            '</label></div>';
                $first   = false;
            }
        } elseif ($option === 'list_icons') {
            foreach ($mollie->all('methods') as $method) {
                $methods .= '<div class="radio"><label class="' . esc_attr($this->dmm_pm_class($method->id)) .
                            '"><input type="radio" name="dmm_method" value="' . esc_attr($method->id) . '" ' .
                            ($first ? 'checked' : '') .
                            '> <img class="mr-4" src="' .
                            sanitize_text_field($method->image->size1x) . '"></label></div>';
                $first   = false;
            }
        } elseif ($option === 'dropdown') {
            $methods .= '<div class="select"><select style="width: 100%" name="dmm_method">';
            $methods .= '<option value="">== ' . esc_html__('Choose a payment method', 'funding') .
                        ' ==</option>';
            foreach ($mollie->all('methods') as $method) {
                $methods .= '<option class="' . esc_attr($this->dmm_pm_class($method->id)) . '" value="' . $method->id .
                            '">' . esc_html__($method->description, 'funding') . '</option>';
            }
            $methods .= '</select></div>';
        }

        return $methods;
    }

    /**
     * Recurring method
     *
     * @param $id
     *
     * @return string
     * @since 2.1.1
     */
    private function dmm_recurring_method($id)
    {
        $recurring = ['ideal', 'bancontact', 'kbc', 'belfius', 'sofort', 'creditcard', 'eps', 'giropay'];

        return !in_array($id, $recurring) ? 'dmm_recurring' : ($id == 'creditcard' ? 'dmm_cc' : 'dmm_dd');
    }

    /**
     * Multicurrency method
     *
     * @param $id
     *
     * @return string
     * @return string
     * @since 2.5.0
     */
    private function dmm_multicurrency_method($id)
    {
        $mc = ['paypal', 'creditcard', 'przelewy24'];

        return !in_array($id, $mc) ? 'dmm_nomc' : 'dmm_mc';
    }

    /**
     * Get class
     *
     * @param $id
     *
     * @return string
     * @since 2.5.0
     */
    private function dmm_pm_class($id)
    {
        return $this->dmm_recurring_method($id) . ' ' . $this->dmm_multicurrency_method($id);
    }

    /**
     * Project list
     *
     * @param $selected
     *
     * @return string
     * @since 2.0.0
     */
    private function dmm_projects($selected = '') {

		$query = new \WP_Query( [
			'post_type' => 'ctx-project',
			'post_status' => 'publish',
			'orderby' => 'title', 
			'order' => 'ASC', 
			'posts_per_page' => -1
		] );
        $projects = $query->posts;

        $projectList = '<select style="width: 100%" id="dmm_project" name="dmm_project">';
        foreach ($projects as $project) {
            $projectList .= '<option value="' . $project->post_name . '"'  . ($selected === $project ? ' selected' : '') . '>' . esc_attr($project->post_title) .
                            '</option>';
        }
        $projectList .= '</select>';

        return $projectList;
    }

    /**
     * @param $slug
     *
     * @return mixed
     */
    private function get_page_id_by_slug($slug)
    {
        return $this->wpdb->get_var("SELECT id FROM " . $this->wpdb->posts . " WHERE post_name = '" .
                                    esc_sql(sanitize_title_for_query($slug)) . "' AND post_type = 'page'");
    }
}