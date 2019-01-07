<?php

/*
Copyright (C) 2016-2017 Pimwick, LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'WC_Email_PW_Gift_Card' ) ) :

class WC_Email_PW_Gift_Card extends WC_Email {

    function __construct() {

        $this->id = 'pwgc_email';
        $this->customer_email = true;
        $this->title = __( 'PW Gift Card Email', 'pw-woocommerce-gift-cards' );
        $this->description = __( 'This email is sent to the recipients of a gift card.', 'pw-woocommerce-gift-cards' );

        $this->default_subject = __( 'You were given a gift card', 'pw-woocommerce-gift-cards' );
        $this->default_heading = __( '{sender} sent you a gift card', 'pw-woocommerce-gift-cards' );

        $this->template_html = 'emails/customer-pw-gift-card.php';
        $this->template_plain = 'emails/plain/customer-pw-gift-card.php';

        add_action( 'pw_gift_cards_pending_email_notification', array( $this, 'queue_order_notification' ) );
        add_action( 'pw_gift_cards_recipient_email_notification', array( $this, 'trigger' ), 10, 3 );

        parent::__construct();

        $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );

        $this->template_base = PWGC_PLUGIN_ROOT . 'templates/woocommerce/';
    }

    public function queue_order_notification( $order_id ) {

        $order = new WC_Order( $order_id );
        foreach ( $order->get_items( 'line_item' ) as $order_item_id => $order_item ) {
            if ( ! is_a( $order_item->get_product(), 'WC_Product' ) ) {
                continue;
            }

            $product_id = !empty( $order_item->get_product()->get_parent_id() ) ? $order_item->get_product()->get_parent_id() : $order_item->get_product()->get_id();
            $product =  wc_get_product( $product_id );
            if ( is_a( $product, 'WC_Product_PW_Gift_Card' ) ) {

                $notifications = (array) wc_get_order_item_meta( $order_item_id, PWGC_GIFT_CARD_NOTIFICATIONS_META_KEY );
                $gift_card_numbers = wc_get_order_item_meta( $order_item_id, PWGC_GIFT_CARD_NUMBER_META_KEY, false );
                $pwgc_to = wc_get_order_item_meta( $order_item_id, PWGC_TO_META_KEY );
                if ( !empty( $pwgc_to ) ) {
                    $recipients = preg_split('/[\s,]+/', $pwgc_to, PWGC_RECIPIENT_LIMIT, PREG_SPLIT_NO_EMPTY);
                }
                $recipient = get_option( 'admin_email' );

                foreach ( $gift_card_numbers as $gift_card_number ) {
                    if ( !isset( $notifications[ $gift_card_number ] ) ) {
                        // Get the next email address from the list, or continue sending to the last (or default) address.
                        if ( isset( $recipients ) && !empty( $recipients ) ) {
                            $recipient = trim( array_shift( $recipients ) );
                        }

                        $notifications[ $gift_card_number ] = $recipient;

                        if ( 'yes' === get_option( 'pwgc_use_wc_transactional_emails', 'yes' ) ) {
                            wp_schedule_single_event( time(), 'pw_gift_cards_recipient_email', array( $order_item_id, $gift_card_number, $recipient ) );
                        } else {
                            $this->trigger( $order_item_id, $gift_card_number, $recipient );
                        }
                    }
                }

                wc_update_order_item_meta( $order_item_id, PWGC_GIFT_CARD_NOTIFICATIONS_META_KEY, $notifications );
            }
        }
    }

    function trigger( $order_item_id, $gift_card_number, $recipient ) {
        if ( empty( $order_item_id ) ) {
            return;
        }

        if ( !empty( $recipient ) ) {
            $this->recipient = $recipient;
        }

        $pwgc_from = wc_get_order_item_meta( $order_item_id, PWGC_FROM_META_KEY );
        $pwgc_message = wc_get_order_item_meta( $order_item_id, PWGC_MESSAGE_META_KEY );

        if ( empty( $pwgc_from ) ) {
            $pwgc_from = 'Anonymous';
        }

        $this->object = $this->create_object( $order_item_id, $gift_card_number, $recipient );

        $this->find[]    = '{gift_card_number}';
        $this->replace[] = $this->object->gift_card_number;

        $this->find[]    = '{amount}';
        $this->replace[] = $this->object->amount;

        $this->find[]    = '{sender}';
        $this->replace[] = $this->object->from;

        $this->find[]    = '{message}';
        $this->replace[] = $this->object->message;

        if ( is_a( $this->object->product, 'WC_Product' ) ) {
            $this->find[]    = '{product_title}';
            $this->replace[] = $this->object->product->get_title();
        }

        if ( ! $this->get_recipient() ) {
            return;
        }

        $attachments = array();

        if ( $this->is_enabled() ) {
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $attachments );
        }
    }

    public static function create_object( $order_item_id, $gift_card_number, $recipient ) {
        global $pw_gift_cards;

        $item_data = new stdClass();

        $order_item = new WC_Order_Item_Product( $order_item_id );

        $item_data->product_id = $order_item->get_product_id();
        $item_data->variation_id = $order_item->get_variation_id();
        $item_data->product = $order_item->get_product();

        $item_data->gift_card_number = $gift_card_number;
        $item_data->recipient = $recipient;
        $item_data->from = wc_get_order_item_meta( $order_item_id, PWGC_FROM_META_KEY );
        $item_data->message = wc_get_order_item_meta( $order_item_id, PWGC_MESSAGE_META_KEY );
        $item_data->amount = wc_get_order_item_meta( $order_item_id, PWGC_AMOUNT_META_KEY );
        if ( !is_numeric( $item_data->amount ) || empty( $item_data->amount ) ) {
            // Previously we didn't store the PWGC_AMOUNT_META_KEY so we need to calculate based on purchase price.
            $item_data->amount = round( $order_item->get_subtotal() / $order_item->get_quantity(), wc_get_price_decimals() );
        }

        // Aelia Currency Switcher - display gift card in the ordered currency.
        if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) && isset( $GLOBALS['woocommerce-aelia-currencyswitcher'] ) ) {
            $cs = $GLOBALS['woocommerce-aelia-currencyswitcher'];
            $cs->get_order( $order_item->get_order_id() );
            $cs->track_order_notification( $order_item->get_order_id() );
            if ( !has_filter( 'woocommerce_currency', array( $cs, 'woocommerce_currency' ), 5 ) ) {
                add_filter( 'woocommerce_currency', array( $cs, 'woocommerce_currency' ), 5 );
            }
        }

        $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
        if ( empty( $shop_url ) ) {
            $shop_url = site_url();
        }
        $redeem_url = add_query_arg( 'pw_gift_card_number', urlencode( $item_data->gift_card_number ), $shop_url );

        $item_data->redeem_url = apply_filters( 'pwgc_redeem_url', $redeem_url, $item_data );

        return $item_data;
    }

    function get_content_html() {
        ob_start();

        wc_get_template(
            $this->template_html,
            array(
                'email' => $this,
                'item_data' => $this->object,
                'email_heading' => $this->get_heading()
            ),
            '',
            $this->template_base
        );

        return ob_get_clean();
    }

    function get_content_plain() {
        ob_start();

        wc_get_template(
            $this->template_plain,
            array(
                'email' => $this,
                'item_data' => $this->object,
                'email_heading' => $this->get_heading()
            ),
            '',
            $this->template_base
        );

        return ob_get_clean();
    }

    // form fields that are displayed in WooCommerce->Settings->Emails
    function init_form_fields() {
        $this->form_fields = array(
            'enabled'    => array(
                'title'   => __( 'Enable/Disable', 'woocommerce' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable this email notification', 'woocommerce' ),
                'default' => 'yes',
            ),
            'subject' => array(
                'title'         => __( 'Subject', 'pw-woocommerce-gift-cards' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'pw-woocommerce-gift-cards' ), $this->subject ),
                'placeholder'   => '',
                'default'       => $this->default_subject
            ),
            'heading' => array(
                'title'         => __( 'Email Heading', 'pw-woocommerce-gift-cards' ),
                'type'          => 'text',
                'description'   => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'pw-woocommerce-gift-cards' ), $this->heading ),
                'placeholder'   => '',
                'default'       => $this->default_heading
            ),
            'email_type' => array(
                'title'         => __( 'Email type', 'pw-woocommerce-gift-cards' ),
                'type'          => 'select',
                'description'   => __( 'Choose which format of email to send.', 'pw-woocommerce-gift-cards' ),
                'default'       => 'html',
                'class'         => 'email_type',
                'options'       => array(
                    'plain'         => __( 'Plain text', 'pw-woocommerce-gift-cards' ),
                    'html'          => __( 'HTML', 'pw-woocommerce-gift-cards' ),
                    'multipart'     => __( 'Multipart', 'pw-woocommerce-gift-cards' ),
                )
            )
        );
    }
}

endif;

return new WC_Email_PW_Gift_Card();
