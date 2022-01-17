<?php

namespace Contexis\Funding;

class Donors_Table extends \WP_List_Table
{
    public function __construct() {
        parent::__construct(
            array(
                'singular' => __('donor', 'funding'),
                'plural'   => __('donors', 'funding'),
                'ajax'     => false
            )
        );
    }

    function get_columns(){
        $columns = array();
        $columns['cb'] = '<input type="checkbox">';
        $columns['customer_name'] = __('Name', 'funding');
        $columns['customer_email'] = __('Email address', 'funding');

        $columns['customer_id'] = __('Customer ID', 'funding');

        return $columns;
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            'dmm_donor',
            $item['id']
        );
    }

    function column_customer_id($item){
        $url_delete = wp_nonce_url('?page=funding-donateurs&action=delete&payment=' . $item['customer_id'], 'delete-donor_' . $item['customer_id']);

        $actions = array();
        $actions['delete'] = sprintf('<a href="%s" style="color:#a00;" onclick="return confirm(\'' . __('Are you sure? Active subscriptions will be cancelled.', 'funding') . '\')">' . esc_html__('Delete', 'funding') . '</a>', $url_delete);

        //Return the title contents
        return sprintf('%1$s %2$s',
            $item['customer_id'],
            $this->row_actions($actions)
        );
    }

    function prepare_items() {
        global $wpdb;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $donors = $wpdb->get_results("SELECT * FROM " . DMM_TABLE_DONORS, ARRAY_A);

        $per_page = 25;
        $current_page = $this->get_pagenum();
        $total_items = count($donors);

        $d = array_slice($donors,(($current_page-1)*$per_page),$per_page);

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ) );
        $this->items = $d;

        $this->process_bulk_action();
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );

        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;

        if ('delete' === $this->current_action()) {
            foreach ($_POST['dmm_donor'] as $donation) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . DMM_TABLE_DONORS . " WHERE id = %d",
                    $donation
                ));
            }

            wp_redirect('?page=' . sanitize_text_field($_REQUEST['page']) . '&msg=delete-ok');
        }
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            default:
                return $item[ $column_name ];
        }
    }

    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <?php $this->pagination( $which );?>
            <br class="clear" />
        </div>
        <?php
    }
}