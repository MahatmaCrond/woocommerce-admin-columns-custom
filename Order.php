<?php

namespace ACA\WC\Settings\User;

use AC;
use AC\View;

class Order extends AC\Settings\Column
	implements AC\Settings\FormatValue {

	/**
	 * @var string
	 */
	private $order_display;

	protected function set_name() {
		$this->name = 'order';
	}

	protected function define_options() {
		return array(
			'order_display' => 'order',
		);
	}

	public function get_dependent_settings() {
		$setting = array();

		switch ( $this->get_order_display() ) {
			case 'date' :
				$setting[] = new AC\Settings\Column\Date( $this->column );
				break;
		}

		return $setting;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		return array(
			'order' => __( 'Order number', 'codepress-admin-columns' ),
			'date'  => __( 'Date' ),
		);
	}

	/**
	 * @return string
	 */
	public function get_order_display() {
		return $this->order_display;
	}

	/**
	 * @param string $order_display
	 *
	 * @return bool
	 */
	public function set_order_display( $order_display ) {
		$this->order_display = $order_display;

		return true;
	}

	/**
	 * @param string $value
	 * @param mixed  $original_value
	 *
	 * @return string|int
	 */
	public function format( $value, $original_value ) {

		switch ( $this->get_order_display() ) {
			case 'order' :
				$value = $this->display_order( $value );

				break;
			case 'date' :
				$order = wc_get_order( $value );
				if ( $order ) {
					$value = $order->get_date_created()->format ('Y-m-d');
				}

				break;
		}

		return $value;
	}

	/**
	 * @param int $order_id
	 *
	 * @return string
	 */
	private function display_order( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}

		$order_number = ac_helper()->html->link( get_edit_post_link( $order_id ), '#' . $order->get_order_number() );
		return sprintf( '%s | %s', $order_number, $value = $order->get_date_created()->format ('Y-m-d') );
	}

}
