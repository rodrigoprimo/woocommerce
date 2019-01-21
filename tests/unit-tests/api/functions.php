<?php
/**
 * File for the WC_Tests_API_Functions class.
 *
 * @package WooCommerce\Tests\API
 */

/**
 * REST API Functions.
 * @since 2.6.0
 */
class WC_Tests_API_Functions extends WC_Unit_Test_Case {

	/**
	 * @var string path to the WP upload dir.
	 */
	private $upload_dir_path;

	/**
	 * @var string WP upload dir URL.
	 */
	private $upload_dir_url;

	/**
	 * @var string Name of the file used in wc_rest_upload_image_from_url() tests.
	 */
	private $file_name;

	/**
	 * Test wc_rest_prepare_date_response().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_prepare_date_response() {
		$this->assertEquals( '2016-06-06T06:06:06', wc_rest_prepare_date_response( '2016-06-06 06:06:06' ) );
	}

	/**
	 * Test wc_rest_upload_image_from_url() should return error when unable to download image.
	 */
	public function test_wc_rest_upload_image_from_url_should_return_error_when_unable_to_download_image() {
		$expected_error_message = 'Error getting remote image http://cldup.com/nonexistent-image.png. Error: Not Found';
		$result                 = wc_rest_upload_image_from_url( 'http://cldup.com/nonexistent-image.png' );

		$this->assertIsWPError( $result );
		$this->assertEquals( $expected_error_message, $result->get_error_message() );
	}

	/**
	 * Test wc_rest_upload_image_from_url() should return error when invalid image is passed.
	 */
	public function test_wc_rest_upload_image_from_url_should_return_error_when_invalid_image_is_passed() {
		// empty file.
		$expected_error_message = 'Invalid image: File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.';
		$result                 = wc_rest_upload_image_from_url( 'https://cldup.com/raDaVDNJBy.txt' );

		$this->assertIsWPError( $result );
		$this->assertEquals( $expected_error_message, $result->get_error_message() );

		// unsupported mime type.
		$expected_error_message = 'Invalid image: Sorry, this file type is not permitted for security reasons.';
		$result                 = wc_rest_upload_image_from_url( 'https://cldup.com/pHSxq-xBwH.txt' );

		$this->assertIsWPError( $result );
		$this->assertEquals( $expected_error_message, $result->get_error_message() );
	}

	/**
	 * Test wc_rest_upload_image_from_url() should download image and return an array containing
	 * information about it.
	 */
	public function test_wc_rest_upload_image_from_url_should_download_image_and_return_array() {
		$upload_dir_info       = wp_upload_dir();

		$expected_result = array(
			'file' => $upload_dir_info['path'] . '/Dr1Bczxq4q.png',
			'url'  => $upload_dir_info['url'] . '/Dr1Bczxq4q.png',
			'type' => 'image/png',
		);
		$result          = wc_rest_upload_image_from_url( 'http://cldup.com/Dr1Bczxq4q.png' );

		$this->assertEquals( $expected_result, $result );
	}

	/**
	 * Test wc_rest_set_uploaded_image_as_attachment().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_set_uploaded_image_as_attachment() {
		$this->assertInternalType(
			'int',
			wc_rest_set_uploaded_image_as_attachment(
				array(
					'file' => '',
					'url'  => '',
				)
			)
		);
	}

	/**
	 * Test wc_rest_validate_reports_request_arg().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_validate_reports_request_arg() {
		$request = new WP_REST_Request(
			'GET',
			'/wc/v3/foo',
			array(
				'args' => array(
					'date' => array(
						'type'   => 'string',
						'format' => 'date',
					),
				),
			)
		);

		// Success.
		$this->assertTrue( wc_rest_validate_reports_request_arg( '2016-06-06', $request, 'date' ) );

		// Error.
		$error = wc_rest_validate_reports_request_arg( 'foo', $request, 'date' );
		$this->assertEquals( 'The date you provided is invalid.', $error->get_error_message() );
	}

	/**
	 * Test wc_rest_urlencode_rfc3986().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_urlencode_rfc3986() {
		$this->assertEquals( 'https%3A%2F%2Fwoocommerce.com%2F', wc_rest_urlencode_rfc3986( 'https://woocommerce.com/' ) );
	}

	/**
	 * Test wc_rest_check_post_permissions().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_check_post_permissions() {
		$this->assertFalse( wc_rest_check_post_permissions( 'shop_order' ) );
	}

	/**
	 * Test wc_rest_check_user_permissions().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_check_user_permissions() {
		$this->assertFalse( wc_rest_check_user_permissions() );
	}

	/**
	 * Test wc_rest_check_product_term_permissions().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_check_product_term_permissions() {
		$this->assertFalse( wc_rest_check_product_term_permissions( 'product_cat' ) );
	}

	/**
	 * Test wc_rest_check_manager_permissions().
	 *
	 * @since 2.6.0
	 */
	public function test_wc_rest_check_manager_permissions() {
		$this->assertFalse( wc_rest_check_manager_permissions( 'reports' ) );
	}
}
