<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lightning_Paywall
 * @subpackage Lightning_Paywall/public
 * @author     Coincharge <https://lightning-paywall.coincharge.io>
 */
class Lightning_Paywall_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of the plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 */
	public function enqueue_styles()
	{

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lightning-paywall-public.css', array(), null, 'all');

		wp_enqueue_style('load-fa', 'https://use.fontawesome.com/releases/v5.12.1/css/all.css');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 */
	public function enqueue_scripts()
	{
		if (!is_admin()) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lightning-paywall-public.js', array('jquery'), null, false);

			wp_enqueue_script('btcpay', get_option('lnpw_btcpay_server_url', '') . '/modal/btcpay.js', array(), null, true);
		}
	}

	private function is_paid_content($post_id = null)
	{

		if (empty($post_id)) {
			$post_id = get_the_ID();
		}

		if (empty($_COOKIE['lnpw_' . $post_id])) {
			return false;
		}

		$order = get_posts([
			'post_type'     => 'lnpw_order',
			'fields'        => 'ids',
			'no_found_rows' => true,
			'meta_query'    => [
				'relation' => 'AND',
				[
					'key'   => 'lnpw_post_id',
					'value' => $post_id,
					'type'  => 'NUMERIC',
				],
				[
					'key'   => 'lnpw_secret',
					'value' => sanitize_text_field($_COOKIE['lnpw_' . $post_id]),
				],
			],
		]);

		if (empty($order)) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * @param  string  $content
	 *
	 * @return string
	 */
	public function filter_the_content($content)
	{


		if ($this->is_paid_content()) {
			return $content;
		}

		if (($start_pos = strpos($content, '<!-- lnpw:start_content -->')) === false) {
			return $content;
		}

		$content_start = substr($content, 0, $start_pos);

		if (($end_pos = strpos($content, '<!-- /lnpw:end_content -->')) === false) {
			$content_end = '';
		} else {
			$content_end = substr($content, $end_pos + 26);
		}


		return $content_start . $content_end;
	}

	/**
	 *
	 */
	public function ajax_get_invoice_id()
	{

		if (empty($_GET['post_id'])) {
			wp_send_json_error(['message' => 'post_id required']);
		}

		$post_id    = sanitize_text_field($_GET['post_id']);
		$order_id   = $this->generate_order_id($post_id);
		$invoice_id = $this->generate_invoice_id($post_id, $order_id);

		if (is_wp_error($invoice_id)) {
			wp_send_json_error([
				'message' => $invoice_id->get_error_message(),
			]);
		}

		wp_send_json_success([
			'amount'	 =>	$invoice_id['amount'],
			'invoice_id' => $invoice_id['id'],
			'order_id'   => $order_id,
		]);
	}

	public static function get_post_info_string($post_id = null)
	{

		if (!$post_id) {
			$post_id = get_the_ID();
		}

		if (get_post_meta($post_id, 'lnpw_price', true)) {
			$price = get_post_meta($post_id, 'lnpw_price', true);
		} else {
			$price = get_option('lnpw_default_price');
		}

		if (get_post_meta($post_id, 'lnpw_duration', true)) {
			$duration = get_post_meta($post_id, 'lnpw_duration', true);
		} else {
			$duration = get_option('lnpw_default_duration');
		}

		if (get_post_meta($post_id, 'lnpw_duration_type', true)) {
			$duration_type = get_post_meta($post_id, 'lnpw_duration_type', true);
		} else {
			$duration_type = get_option('lnpw_default_duration_type', 'unlimited');
		}
		if (get_post_meta($post_id, 'lnpw_currency', true)) {
			$currency = get_post_meta($post_id, 'lnpw_currency', true);
		} else {
			$currency = get_option('lnpw_default_currency', 'SATS');
		}
		$btc_format = get_post_meta(get_the_ID(), 'lnpw_btc_format', true) ?: get_option('lnpw_default_btc_format');

		if ($currency === 'SATS' && $btc_format === 'BTC') {

			$price = $price / 100000000;

			$price = sprintf('%.8f', $price);

			$price = rtrim($price, '0');

			$currency = 'BTC';
		}

		$payblock_info = get_option('lnpw_default_payblock_info');

		if (!empty($payblock_info)) {

			$search = array('[price]', '[duration]', '[dtype]', '[currency]');

			$replace = array($price, $duration, $duration_type, $currency);

			return str_replace($search, $replace, $payblock_info);
		}
		$non_number = $duration_type === 'unlimited' || $duration_type === 'onetime';

		$duration_type = ($duration > 1 && !$non_number) ? "{$duration_type}s" : $duration_type;

		$unlimited = "For {$price} {$currency} you will have unlimited access to the post.";

		$onetime = "For {$price} {$currency} you will have access to the post only once.";

		$other = "For {$price} {$currency} you will have access to the post for {$duration} {$duration_type}.";

		return $duration_type === 'unlimited' ? $unlimited : ($duration_type === 'onetime' ? $onetime : $other);
	}

	public static function get_payblock_header_string()
	{
		return get_option('lnpw_default_payblock_text') ?: 'For access to ' . get_post_meta(get_the_ID(), 'lnpw_invoice_content', true)['project'] . ' first pay';
	}
	public static function get_payblock_button_string()
	{
		return get_option('lnpw_default_payblock_button') ?: 'Pay';
	}

	private function calculate_price_for_invoice($post_id)
	{
		$currency_scope = get_post_meta($post_id, 'lnpw_currency', true) ?: get_option('lnpw_default_currency', 'SATS');

		if ($currency_scope === 'SATS') {

			if (get_post_meta($post_id, 'lnpw_price', true)) {

				$price = get_post_meta($post_id, 'lnpw_price', true);
			} else {

				$price = get_option('lnpw_default_price');
			}

			$value = $price / 100000000;

			$value = sprintf('%.8f', $value);

			$value = rtrim($value, '0');

			return $value;
		}

		return get_post_meta($post_id, 'lnpw_price', true) ?: get_option('lnpw_default_price');
	}

	public function generate_invoice_id($post_id, $order_id)
	{
		$amount = $this->calculate_price_for_invoice($post_id);

		$url = get_option('lnpw_btcpay_server_url') . '/api/v1/stores/' . get_option('lnpw_btcpay_store_id') . '/invoices';

		$currency_scope = get_post_meta($post_id, 'lnpw_currency', true) ?: get_option('lnpw_default_currency', 'SATS');
		$currency = $currency_scope != 'SATS' ? $currency_scope : 'BTC';

		$data = array(
			'amount'    => $amount,
			'currency' => $currency,
			'metadata' => array(
				'orderId'  => $order_id,
				'itemDesc' => get_post_meta($post_id, 'lnpw_invoice_content', true)['title'],
				'buyer'    => array(
					'name'   => (string) $_SERVER['REMOTE_ADDR']
				)
			)
		);

		$args = array(
			'headers'     => array(
				'Authorization' => 'token ' . get_option('lnpw_btcpay_auth_key_create'),
				'Content-Type'  => 'application/json',
			),
			'body'        => json_encode($data),
			'method'      => 'POST',
			'timeout'     => 60,
		);

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response;
		}

		if ($response['response']['code'] != 200) {
			return new WP_Error($response['response']['code'], 'HTTP Error ' . $response['response']['code']);
		}

		$body = json_decode($response['body'], true);

		if (empty($body) || !empty($body['error'])) {
			return new WP_Error('invoice_error', $body['error'] ?? 'Something went wrong');
		}

		update_post_meta($order_id, 'lnpw_invoice_id', $body['id']);

		return array(
			'id'     => $body['id'],
			'amount' => $body['amount'] . $body['currency']
		);
	}

	public function ajax_convert_currencies()
	{

		$url = 'https://api.coingecko.com/api/v3/exchange_rates';

		$args = array(
			'headers'     => array(
				'Content-Type'  => 'application/json',
			),
			'method'      => 'GET',
			'timeout'     => 10,
		);

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response;
		}

		if ($response['response']['code'] != 200) {
			return new WP_Error($response['response']['code'], 'HTTP Error ' . $response['response']['code']);
		}

		$body = json_decode($response['body'], true);

		if (empty($body) || !empty($body['error'])) {
			return new WP_Error('converter_error', $body['error'] ?? 'Something went wrong');
		}

		wp_send_json_success(
			$body['rates'],
		);
	}

	public function ajax_tipping()
	{
		$collect = '';

		if (!empty($_POST['name'])) {
			$name = sanitize_text_field($_POST['name']);
			$collect .= "Name: {$name}; ";
		}
		if (!empty($_POST['email'])) {
			$email = sanitize_text_field($_POST['email']);
			$collect .= "Email: {$email}; ";
		}
		if (!empty($_POST['address'])) {
			$address = sanitize_text_field($_POST['address']);
			$collect .= "Address: {$address}; ";
		}
		if (!empty($_POST['phone'])) {
			$phone = sanitize_text_field($_POST['phone']);
			$collect .= "Phone: {$phone}; ";
		}
		if (!empty($_POST['message'])) {
			$message = sanitize_text_field($_POST['message']);
			$collect .= "Message: {$message}; ";
		}

		$currency = sanitize_text_field($_POST['currency']);
		$amount = sanitize_text_field($_POST['amount']);


		if (!empty($_POST['predefined_amount'])) {
			$extract = explode(' ', sanitize_text_field($_POST['predefined_amount']));
			$amount = $extract[0];
			$currency = $extract[1];
		}

		$collect .= "Amount: {$amount} {$currency}";
		$collect .= 'Time:' . ' ' . date('Y-m-d H:i:s', current_time('timestamp', 0));


		$url = get_option('lnpw_btcpay_server_url') . '/api/v1/stores/' . get_option('lnpw_btcpay_store_id') . '/invoices';

		$data = array(
			'amount'    => $amount,
			'currency' => $currency,
			'metadata' => array(
				'itemDesc' => 'Donation from: ' . $_SERVER['REMOTE_ADDR'],
				'donor'    => $collect,
			)
		);
		$args = array(
			'headers'     => array(
				'Authorization' => 'token ' . get_option('lnpw_btcpay_auth_key_create'),
				'Content-Type'  => 'application/json',
			),
			'body'        => json_encode($data),
			'method'      => 'POST',
			'timeout'     => 60,
		);

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response;
		}

		if ($response['response']['code'] != 200) {
			return new WP_Error($response['response']['code'], 'HTTP Error ' . $response['response']['code']);
		}

		$body = json_decode($response['body'], true);


		if (empty($body) || !empty($body['error'])) {
			return new WP_Error('invoice_error', $body['error'] ?? 'Something went wrong');
		}

		wp_send_json_success([
			'invoice_id' => $body['id'],
			'donor'	 => $body['metadata']['donor'],
		]);
	}
	public function ajax_notify_administrator()
	{


		$admin = get_bloginfo('admin_email');
		$body = sanitize_text_field($_POST['donor_info']);

		wp_mail($admin, 'You have received a donation', $body);
	}
	/**
	 * @param $post_id
	 *
	 * @return int
	 * @throws Exception
	 */
	private function generate_order_id($post_id)
	{

		$order_id = wp_insert_post([
			'post_title'  => 'Pay ' . $post_id . ' from ' . $_SERVER['REMOTE_ADDR'],
			'post_status' => 'publish',
			'post_type'   => 'lnpw_order',
		]);

		update_post_meta($order_id, 'lnpw_status', 'waiting');
		update_post_meta($order_id, 'lnpw_post_id', $post_id);
		update_post_meta($order_id, 'lnpw_from_ip', $_SERVER['REMOTE_ADDR']);
		update_post_meta($order_id, 'lnpw_secret', bin2hex(random_bytes(5)));

		return $order_id;
	}

	/**
	 * 
	 */
	public function ajax_paid_invoice()
	{

		if (empty($_POST['order_id'])) {
			wp_send_json_error();
		}

		$order_id   = sanitize_text_field($_POST['order_id']);
		$post_id    = get_post_meta($order_id, 'lnpw_post_id', true);
		$invoice_id = get_post_meta($order_id, 'lnpw_invoice_id', true);
		$secret     = get_post_meta($order_id, 'lnpw_secret', true);
		$content_title = get_post_meta($post_id, 'lnpw_invoice_content', true)['title'];
		$store_id = get_option('lnpw_btcpay_store_id');


		if (empty($post_id) || empty($invoice_id)) {
			wp_send_json_error();
		}

		$url = get_option('lnpw_btcpay_server_url') . '/api/v1/stores/' . get_option('lnpw_btcpay_store_id') . '/invoices/' . $invoice_id;

		$args = array(
			'headers'     => array(
				'Authorization' => 'token ' . get_option('lnpw_btcpay_auth_key_view'),
				'Content-Type'  => 'application/json',
			),
			'method'      => 'GET',
			'timeout'     => 60,
		);

		$response = wp_remote_request($url, $args);

		if (is_wp_error($response)) {
			return $response;
		}

		if ($response['response']['code'] != 200) {
			return new WP_Error($response['response']['code'], 'HTTP Error ' . $response['response']['code']);
		}

		$body = json_decode($response['body'], true);

		if (empty($body) || !empty($body['error'])) {
			return new WP_Error('invoice_error', $body['error'] ?? 'Something went wrong');
		}
		$amount = sanitize_text_field($_POST['amount']);
		$message = '';
		$message .= "Invoice was confirmed paid ";
		$message .= "Store id: {$store_id}";
		$message .= $content_title;
		$message .= "Amount: {$amount}";

		if ($body['status'] === 'Settled') {
			$cookie_path = parse_url(get_permalink($post_id), PHP_URL_PATH);

			setcookie('lnpw_' . $post_id, $secret, $this->get_cookie_duration($post_id), $cookie_path);

			update_post_meta($order_id, 'lnpw_status', 'success');

			wp_send_json_success(['notify' => $message]);
		}
		wp_send_json_error(['message' => 'invoice is not paid']);
	}

	/**
	 * @param $post_id
	 *
	 * @return false|int
	 */
	public function get_cookie_duration($post_id)
	{

		$duration = get_post_meta($post_id, 'lnpw_duration', true);

		if (empty($duration)) {
			$duration = get_option('lnpw_default_duration');
		}

		$duration_type = get_post_meta($post_id, 'lnpw_duration_type', true);

		if (empty($duration_type)) {
			$duration_type = get_option('lnpw_default_duration_type');
		}

		return $duration_type === 'unlimited' ? strtotime("14 Jan 2038") : ($duration_type === 'onetime' ? 0 : strtotime("+{$duration} {$duration_type}"));
	}


	private function update_meta_settings($atts)
	{
		$valid_currency = in_array($atts['currency'], Lightning_Paywall_Admin::CURRENCIES);
		$valid_duration = in_array($atts['duration_type'], Lightning_Paywall_Admin::DURATIONS);
		$valid_btc_format = in_array($atts['btc_format'], Lightning_Paywall_Admin::BTC_FORMAT);


		if (!empty($atts['currency']) && $valid_currency) {
			update_post_meta(get_the_ID(), 'lnpw_currency', sanitize_text_field($atts['currency']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_currency');
		}
		if ($atts['currency'] === 'SATS' && $valid_btc_format) {
			update_post_meta(get_the_ID(), 'lnpw_btc_format', sanitize_text_field($atts['btc_format']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_btc_format');
		}
		if (!empty($atts['price'])) {
			update_post_meta(get_the_ID(), 'lnpw_price', sanitize_text_field($atts['price']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_price');
		}
		if (!empty($atts['duration'])) {
			update_post_meta(get_the_ID(), 'lnpw_duration', sanitize_text_field($atts['duration']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_duration');
		}
		if (!empty($atts['duration_type']) && $valid_duration) {
			update_post_meta(get_the_ID(), 'lnpw_duration_type', sanitize_text_field($atts['duration_type']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_duration_type');
		}
	}
	/**
	 * @param  array  $atts
	 *
	 * @return false|string
	 */
	public function render_shortcode_lnpw_start_content($atts)
	{

		$atts = shortcode_atts(array(
			'pay_block' => 'false',
			'btc_format' => '',
			'price'     => '',
			'currency'  => '',
			'duration'  => '',
			'duration_type' => '',
		), $atts);


		$this->update_meta_settings($atts);

		$invoice_content = array('title' => 'Pay-per-post: ' . get_the_title(get_the_ID()), 'project' => 'post');
		update_post_meta(get_the_ID(), 'lnpw_invoice_content', $invoice_content);

		$s_data = '<!-- lnpw:start_content -->';

		$payblock = $atts['pay_block'] === 'true';

		if ($payblock) {
			return do_shortcode('[lnpw_pay_block]') . $s_data;
		}
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */

	public function render_shortcode_lnpw_start_video($atts)
	{
		$img_preview = plugin_dir_url(__FILE__) . 'img/preview.png';

		$atts = shortcode_atts(array(
			'pay_view_block' => 'false',
			'btc_format' => '',
			'title' => 'Untitled',
			'description' => 'No description',
			'preview' => $img_preview,
			'currency' => '',
			'price'     => '',
			'duration'  => '',
			'duration_type' => ''
		), $atts);

		$this->update_meta_settings($atts);

		$invoice_content = array('title' => 'Pay-per-view: ' . sanitize_text_field($atts['title']), 'project' => 'video');
		update_post_meta(get_the_ID(), 'lnpw_invoice_content', $invoice_content);

		$payblock = $atts['pay_view_block'] === 'true';


		$s_data = '<!-- lnpw:start_content -->';

		if ($payblock) {
			return do_shortcode("[lnpw_pay_video_block title='{$atts['title']}' description='{$atts['description']}' preview='{$atts['preview']}']") . $s_data;
		}
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function render_shortcode_lnpw_file($atts)
	{
		$img_preview = plugin_dir_url(__FILE__) . 'img/file_preview.png';

		$atts = shortcode_atts(array(
			'pay_file_block' => 'false',
			'btc_format' => '',
			'file'		=>	'',
			'title' => 'Untitled',
			'description' => 'No description',
			'preview' => $img_preview,
			'currency' => '',
			'price'     => '',
			'duration'  => '',
			'duration_type' => ''
		), $atts);

		$this->update_meta_settings($atts);

		$invoice_content = array('title' => 'Pay-per-file: ' . sanitize_text_field($atts['title']), 'project' => 'file');

		update_post_meta(get_the_ID(), 'lnpw_invoice_content', $invoice_content);

		$payblock = $atts['pay_file_block'] === 'true';
		$file = !empty($atts['file']);

		$required_attributes = $payblock && $file;

		$s_data = '<!-- lnpw:start_content -->';
		$e_data = '<!-- /lnpw:end_content -->';

		if ($required_attributes) {
			$output =  do_shortcode("[lnpw_pay_file_block title='{$atts['title']}' description='{$atts['description']}' preview='{$atts['preview']}']");
			$output .= $s_data;
			$output .= do_shortcode("[lnpw_protected_file file='{$atts['file']}']");
			$output .= $e_data;
			return  $output;
		}

		return do_shortcode("[lnpw_protected_file file='{$atts['file']}']");
	}
	/**
	 * @param  array  $atts
	 *
	 * @return string
	 */
	public function render_shortcode_lnpw_end_content($atts)
	{

		return '<!-- /lnpw:end_content -->';
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function render_shortcode_lnpw_pay_block($atts)
	{
		if ($this->is_paid_content()) {
			return '';
		}

		ob_start();

		include 'partials/lnpw-pay-block.php';

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_lnpw_pay_view_block($atts)
	{
		if ($this->is_paid_content()) {
			return '';
		}

		$atts = shortcode_atts(array(
			'title' => '',
			'description' => '',
			'preview' => '',
		), $atts);

		$image = wp_get_attachment_image_src($atts['preview']);

		$preview_url = $image ? $image[0] : $atts['preview'];

		ob_start();

?>
<div class="lnpw_pay">
    <div class="lnpw_pay__preview">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <p><?php echo esc_html($atts['description']); ?></p>
        <img src=<?php echo esc_url($preview_url); ?> alt="Video preview">
    </div>
    <div class="lnpw_pay__content">
        <h2><?php echo Lightning_Paywall_Public::get_payblock_header_string() ?></h2>
        <p>
            <?php echo Lightning_Paywall_Public::get_post_info_string() ?>
        </p>
    </div>
    <div class="lnpw_pay__footer">
        <div>
            <button type="button" id="lnpw_pay__button"
                data-post_id="<?php echo  get_the_ID(); ?>"><?php echo Lightning_Paywall_Public::get_payblock_button_string() ?></button>
        </div>
        <div class="lnpw_pay__loading">
            <p class="loading"></p>
        </div>
        <div class="lnpw_help">
            <a class="lnpw_help__link" href="https://lightning-paywall.coincharge.io/how-to-pay-the-lightning-paywall/"
                target="_blank">Help</a>
        </div>
    </div>
</div>
<?php


		return ob_get_clean();
	}
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_protected_file($atts)
	{

		$atts = shortcode_atts(array(
			'file' => ''
		), $atts);

		$href = $atts['file'];

		if (function_exists('vc_build_link')) {
			$href = vc_build_link($atts['file'])['url'] ?: $atts['file'];
		}
		ob_start();
	?>

<a class="lnpw_pay__download" href=<?php echo esc_url($href) ?> target="_blank" download>Download</a>

<?php


		return ob_get_clean();
	}
	public function render_shortcode_lnpw_pay_file_block($atts)
	{
		if ($this->is_paid_content()) {
			return '';
		}

		$atts = shortcode_atts(array(
			'title' => '',
			'description' => '',
			'preview' => '',
		), $atts);

		$image = wp_get_attachment_image_src($atts['preview']);

		$preview_url = $image ? $image[0] : $atts['preview'];

		ob_start();

	?>
<div class="lnpw_pay">
    <div class="lnpw_pay__preview">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <p><?php echo esc_html($atts['description']); ?></p>
        <img src=<?php echo esc_url($preview_url); ?> alt="Video preview">
    </div>
    <div class="lnpw_pay__content">
        <h2><?php echo Lightning_Paywall_Public::get_payblock_header_string() ?></h2>
        <p>
            <?php echo Lightning_Paywall_Public::get_post_info_string() ?>
        </p>
    </div>
    <div class="lnpw_pay__footer">
        <div>
            <button type="button" id="lnpw_pay__button"
                data-post_id="<?php echo  get_the_ID(); ?>"><?php echo Lightning_Paywall_Public::get_payblock_button_string() ?></button>
        </div>
        <div class="lnpw_pay__loading">
            <p class="loading"></p>
        </div>
        <div class="lnpw_help">
            <a class="lnpw_help__link" href="https://lightning-paywall.coincharge.io/how-to-pay-the-lightning-paywall/"
                target="_blank">Help</a>
        </div>
    </div>
</div>
<?php


		return ob_get_clean();
	}


	public static function display_is_enabled($arr)
	{

		if (!is_array($arr)) {
			return;
		}

		foreach ($arr as $key => $value) {

			if ($arr[$key]['display'] === 'true') {

				return true;
			}
		}
		return false;
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_box_tipping($atts)
	{
		$atts = shortcode_atts(array(
			'dimension' 	=> '250x300',
			'title'			=> 'Support my work',
			'description'	=> '',
			'currency'		=> 'SATS',
			'background_color'	=> '#E6E6E6',
			'title_text_color'	=> '#ffffff',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'description_color'	=> '#000000',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'input_background'	=> '#f6b330',
			'logo_id'		=> 'https://btcpaywall.com/wp-content/uploads/2021/07/BTCPayWall-logo_square.jpg',
			'background_id'	=> '',
			'background' => '#1d5aa3',
			'widget'		=> 'false',
		), $atts);

		$dimension = explode('x', ($atts['dimension'] == '250x300' ? '250x300' : '300x300'));
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']) ? wp_get_attachment_image_src($atts['logo_id'])[0] : $atts['logo_id'];
		$background = wp_get_attachment_image_src($atts['background_id']) ? wp_get_attachment_image_src($atts['background_id'])[0] : $atts['background_id'];

		$is_widget = $atts['widget'] === 'true' ? 'lnpw_widget' : '';
		$form = $is_widget === 'lnpw_widget' ? 'tipping_form_box_widget' : 'tipping_form_box';
		$suffix = $is_widget === 'lnpw_widget' ? '_lnpw_widget' : '';
		$version = $atts['widget'] === 'true' ? 'widget' : 'basic';

		ob_start();
	?>
<style>
<?php if ($version==='widget') : ?>.lnpw_tipping_box_container.lnpw_widget {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    width: <?php echo esc_html($dimension[0]) . 'px !important';
    ?>;
    height: <?php echo esc_html($dimension[1]) . 'px !important';
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);

}


#lnpw_tipping__button_lnpw_widget {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
    background: <?php echo esc_html($atts['button_color']);
    ?>;
}

#tipping_form_box_widget fieldset div.lnpw_tipping_box_header_container h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}

.lnpw_tipping_container_info_container {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

#tipping_form_box_widget fieldset div.lnpw_tipping_box_info_container p {
    color: <?php echo esc_html($atts['description_color']);
    ?>
}

#tipping_form_box_widget fieldset div.lnpw_tipping_box_header_container,
#button {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

#tipping_form_box_widget fieldset h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>;
}

.lnpw_tipping_free_input.lnpw_widget {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;
}

<?php else : ?>.lnpw_tipping_box_container {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    width: <?php echo esc_html($dimension[0]) . 'px !important';
    ?>;
    height: <?php echo esc_html($dimension[1]) . 'px !important';
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);

}


#lnpw_tipping__button {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
    background: <?php echo esc_html($atts['button_color']);
    ?>;
}

#tipping_form_box fieldset div.lnpw_tipping_box_header_container div h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}



.lnpw_tipping_box_header_container,
#button {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

#tipping_form_box fieldset div p {
    color: <?php echo esc_html($atts['description_color']);
    ?>
}

#tipping_form_box fieldset h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>
}

.lnpw_tipping_free_input {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;
}

<?php endif;
?>
</style>

<div id="lnpw_page">
    <div class="<?php echo trim("lnpw_tipping_box_container {$is_widget}"); ?>">

        <form method="POST" action="" id="<?php echo $form; ?>">
            <fieldset>
                <div class="lnpw_tipping_box_header_container">
                    <?php if ($logo) : ?>
                    <div id="lnpw_box_logo_wrap">
                        <img width="70" height="70" alt="Tipping logo" src="<?php echo esc_url($logo); ?>" />
                    </div>
                    <?php endif; ?>
                    <div>
                        <?php if (!empty($atts['title'])) : ?>
                        <h6><?php echo esc_html($atts['title']); ?></h6>
                        <?php endif; ?>
                        <?php if (!empty($atts['description'])) : ?>
                        <p><?php echo esc_html($atts['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <h6><?php echo (!empty($atts['tipping_text']) ? $atts['tipping_text'] : ''); ?></h6>
                <div class="lnpw_tipping_box_amount">

                    <div class="<?php echo "lnpw_tipping_free_input {$is_widget}"; ?>">
                        <input type="number" id="<?php echo "lnpw_tipping_amount{$suffix}"; ?>"
                            name="<?php echo "lnpw_tipping_amount{$suffix}"; ?>" placeholder="0.00" required />


                        <select required name="<?php echo "lnpw_tipping_currency{$suffix}"; ?>"
                            id="<?php echo "lnpw_tipping_currency{$suffix}"; ?>">
                            <option disabled value="">Select currency</option>
                            <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                                value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-arrows-alt-v"></i>

                    </div>
                    <div class="lnpw_tipping_converted_values">
                        <input type="text" id="<?php echo "lnpw_converted_amount{$suffix}"; ?>"
                            name="lnpw_converted_amount" readonly />
                        <input type="text" id="<?php echo "lnpw_converted_currency{$suffix}"; ?>"
                            name="lnpw_converted_currency" readonly />
                    </div>
                </div>
                <input type="hidden" id="lnpw_redirect_link" name="lnpw_redirect_link"
                    value=<?php echo $atts['redirect']; ?> />
                <div id="button">

                    <button type="submit"
                        id="<?php echo "lnpw_tipping__button{$suffix}" ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                </div>
            </fieldset>

        </form>
    </div>
    <div id="powered_by_box">
        <p>Powered by <a href='https://btcpaywall.com/' target='_blank'>BTCPayWall</a></p>
    </div>
</div>
<?php

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	private function getCollect($atts)
	{
		return array(
			array(
				'id' => 'name',
				'label' => 'Full name' . ($atts['mandatory_name'] == 'true' ? '*' : ''),
				'display' => $atts['display_name'] == 'true' ? 'true' : 'false',
				'mandatory' => $atts['mandatory_name'] == 'true' ? 'true' : 'false'
			),
			array(
				'id' => 'email',
				'label' => 'Email' . ($atts['mandatory_email'] == 'true' ? '*' : ''),
				'display' => $atts['display_email'] == 'true' ? 'true' : 'false',
				'mandatory' => $atts['mandatory_email'] == 'true' ? 'true' : 'false'
			),
			array(
				'id' => 'address',
				'label' => 'Address' . ($atts['mandatory_address'] == 'true' ? '*' : ''),
				'display' => $atts['display_address'] == 'true' ? 'true' : 'false',
				'mandatory' => $atts['mandatory_address'] == 'true' ? 'true' : 'false'
			),
			array(
				'id' => 'phone',
				'label' => 'Phone' . ($atts['mandatory_phone'] == 'true' ? '*' : ''),
				'display' => $atts['display_phone'] == 'true' ? 'true' : 'false',
				'mandatory' => $atts['mandatory_phone'] == 'true' ? 'true' : 'false'
			),
			array(
				'id' => 'message',
				'label' => 'Message' . ($atts['mandatory_message'] == 'true' ? '*' : ''),
				'display' => $atts['display_message'] == 'true' ? 'true' : 'false',
				'mandatory' => $atts['mandatory_message'] == 'true' ? 'true' : 'false'
			),
		);
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	private function getFixedAmount($atts)
	{
		return array(
			'value_1' => array(
				'enabled' 	=> ($atts['value1_enabled'] == 'true' ? 'true' : 'false'),
				'currency' => in_array($atts['value1_currency'], Lightning_Paywall_Admin::TIPPING_CURRENCIES) === true ? $atts['value1_currency'] : 'SATS',
				'amount'	=> esc_html($atts['value1_amount']),
				'icon'		=> esc_html($atts['value1_icon'])
			),
			'value_2' => array(
				'enabled' 	=> ($atts['value2_enabled'] == 'true' ? 'true' : 'false'),
				'currency' => in_array($atts['value2_currency'], Lightning_Paywall_Admin::TIPPING_CURRENCIES) === true ? $atts['value2_currency'] : 'SATS',
				'amount'	=> esc_html($atts['value2_amount']),
				'icon'		=> esc_html($atts['value2_icon'])
			),
			'value_3' => array(
				'enabled' 	=> ($atts['value3_enabled'] == 'true' ? 'true' : 'false'),
				'currency' => in_array($atts['value3_currency'], Lightning_Paywall_Admin::TIPPING_CURRENCIES) === true ? $atts['value3_currency'] : 'SATS',
				'amount'	=> esc_html($atts['value3_amount']),
				'icon'		=> esc_html($atts['value3_icon'])
			),
		);
	}
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_skyscraper_tipping($atts)
	{
		$atts = shortcode_atts(array(
			'dimension' 	=> '200x600',
			'title'			=> 'Support my work',
			'description'	=> '',
			'currency'		=> 'SATS',
			'background_color'	=> '#E6E6E6',
			'title_text_color'	=> '#ffffff',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'description_color'	=> '#000000',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'logo_id'		=> 'https://btcpaywall.com/wp-content/uploads/2021/07/BTCPayWall-logo_square.jpg',
			'background_id'	=> '',
			'free_input'	=> 'true',
			'input_background'	=> '#f6b330',
			'background' => '#1d5aa3',
			'value1_enabled' => 'true',
			'value1_amount' => '1000',
			'value1_currency' => 'SATS',
			'value1_icon'	=> 'fas fa-coffee',
			'value2_enabled' => 'true',
			'value2_amount' => '2000',
			'value2_currency' => 'SATS',
			'value2_icon'	=> 'fas fa-beer',
			'value3_enabled' => 'true',
			'value3_amount' => '3000',
			'value3_currency' => 'SATS',
			'value3_icon'	=> 'fas fa-cocktail',
			'display_name'	=> 'true',
			'mandatory_name' => 'false',
			'display_email'	=> 'true',
			'mandatory_email' => 'false',
			'display_phone'	=> 'true',
			'mandatory_phone' => 'false',
			'display_address'	=> 'true',
			'mandatory_address' => 'false',
			'display_message'	=> 'true',
			'mandatory_message' => 'false',
			'widget'			=> 'false'
		), $atts);
		$dimension = explode('x', ($atts['dimension'] == '200x600' ? '200x600' : '600x200'));
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']) ? wp_get_attachment_image_src($atts['logo_id'])[0] : $atts['logo_id'];
		$background = wp_get_attachment_image_src($atts['background_id']) ? wp_get_attachment_image_src($atts['background_id'])[0] : $atts['background_id'];
		$collect = $this->getCollect($atts);
		$collect_data = $this->display_is_enabled($collect);

		$fixed_amount = $this->getFixedAmount($atts);

		$first_enabled = array_column($fixed_amount, 'enabled');
		$d = array_search('true', $first_enabled);
		$index = 'value' . ($d + 1);
		$is_widget = $atts['widget'] === 'true' ? 'lnpw_widget' : '';
		$is_widget_id = $atts['widget'] === 'true' ? 'lnpw_widget_' : '';

		$is_wide = $dimension[0] === '600' ? 'wide' : 'high';
		$form_prefix = $is_widget === 'lnpw_widget' ? 'lnpw_widget_' : '';
		$form_suffix = ($is_widget === 'lnpw_widget' && $dimension[0] === '200') ? '_high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? '_wide' : '');
		$width = $dimension[0] === '600' ? '70' : '180';
		$height = $dimension[0] === '600' ? '70' : '100';
		$container_suffix = ($is_widget === 'lnpw_widget' && $dimension[0] === '200') ? 'high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? 'wide' : '');

		$version = ($is_widget === 'lnpw_widget' && $dimension[0] === '200') ? 'high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? 'wide' : 'basic');

		ob_start();
	?>
<style>
<?php if ($version==='wide') : ?>.lnpw_widget.lnpw_skyscraper_tipping_container.wide {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);
}

.lnpw_skyscraper_header_container.wide,
#lnpw_skyscraper_button_wide,
#lnpw_widget_lnpw_skyscraper_button_wide,
#lnpw_widget_lnpw_skyscraper_button_wide>div:nth-child(1)>input {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

.lnpw_skyscraper_amount_value_1.wide,
.lnpw_skyscraper_amount_value_2.wide,
.lnpw_skyscraper_amount_value_3.wide,
.lnpw_skyscraper_tipping_free_input.wide {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;
}

#lnpw_widget_lnpw_skyscraper_tipping__button_wide,
#lnpw_widget_lnpw_skyscraper_button_wide>div>input.lnpw_widget.skyscraper-next-form {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
    background: <?php echo esc_html($atts['button_color']);
    ?>;
}

.lnpw_widget.lnpw_skyscraper_header_container.wide h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_container.info_container.wide {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_widget.lnpw_skyscraper_info_container.wide p,
div.lnpw_widget.lnpw_skyscraper_banner.wide>div.lnpw_widget.lnpw_skyscraper_header_container.wide p {
    color: <?php echo esc_html($atts['description_color']);
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_info.wide fieldset h6,
.lnpw_widget.lnpw_skyscraper_tipping_info.wide h6,
#lnpw_widget_skyscraper_tipping_form_wide>fieldset:nth-child(1)>h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>
}

.lnpw_widget.lnpw_skyscraper_amount_value_1.wide,
.lnpw_widget.lnpw_skyscraper_amount_value_2.wide,
.lnpw_widget.lnpw_skyscraper_amount_value_3.wide {
    background: <?php echo esc_html($atts['fixed_background']);
    ?>;
}





<?php elseif ($version==='high') : ?>.lnpw_widget.lnpw_skyscraper_tipping_container.high {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    background-image: url(<?php echo ($background ? $background : '');
    ?>);
    width: <?php echo esc_html($dimension[0]) . 'px !important';
    ?>;
    height: <?php echo esc_html($dimension[1]) . 'px !important';
    ?>;
}

#lnpw_widget_lnpw_skyscraper_tipping__button_high,
#lnpw_widget_lnpw_skyscraper_button_high>div>input.lnpw_widget.skyscraper-next-form.high {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
    background: <?php echo esc_html($atts['button_color']);
    ?>;
}

.lnpw_widget.lnpw_skyscraper_header_container.high,
#lnpw_widget_lnpw_skyscraper_button_high,
#lnpw_widget_lnpw_skyscraper_button_high>div:nth-child(1)>input {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

.lnpw_widget.lnpw_skyscraper_header_container.high h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}

.lnpw_skyscraper_amount_value_1.high,
.lnpw_skyscraper_amount_value_2.high,
.lnpw_skyscraper_amount_value_3.high,
.lnpw_skyscraper_tipping_free_input.high {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;
}

.lnpw_widget.lnpw_skyscraper_tipping_container.info_container.high {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_widget.lnpw_skyscraper_info_container.high p,
div.lnpw_widget.lnpw_skyscraper_banner.wide>div.lnpw_widget.lnpw_skyscraper_header_container.high p {
    color: <?php echo esc_html($atts['description_color']);
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_info.high fieldset h6,
.lnpw_widget.lnpw_skyscraper_tipping_info.high h6,
#lnpw_widget_skyscraper_tipping_form_high>fieldset:nth-child(1)>h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>
}

.lnpw_widget.lnpw_skyscraper_amount_value_1.high,
.lnpw_widget.lnpw_skyscraper_amount_value_2.high,
.lnpw_widget.lnpw_skyscraper_amount_value_3.high {
    background: <?php echo esc_html($atts['fixed_background']);
    ?>;

}



<?php else : ?>.lnpw_skyscraper_tipping_container {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);
    width: <?php echo esc_html($dimension[0]) . 'px !important';
    ?>;
    height: <?php echo esc_html($dimension[1]) . 'px !important';
    ?>;
}

.lnpw_skyscraper_banner.wide {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);
}

.lnpw_skyscraper_amount_value_1,
.lnpw_skyscraper_amount_value_2,
.lnpw_skyscraper_amount_value_3,
.lnpw_skyscraper_tipping_free_input {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;
}

.lnpw_skyscraper_header_container,
#lnpw_skyscraper_button,
#lnpw_skyscraper_button>div:nth-child(1)>input {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

#lnpw_skyscraper_tipping__button,
#lnpw_skyscraper_button>div>input.skyscraper-next-form {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
    background: <?php echo esc_html($atts['button_color']);
    ?>;
}

.lnpw_skyscraper_header_container h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}

.lnpw_skyscraper_tipping_container.info_container {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_skyscraper_info_container p,
div.lnpw_skyscraper_banner.wide>div.lnpw_skyscraper_header_container.wide p,
div.lnpw_skyscraper_banner.high>div.lnpw_skyscraper_header_container.high p {
    color: <?php echo esc_html($atts['description_color']);
    ?>
}

.lnpw_skyscraper_tipping_info fieldset h6,
.lnpw_skyscraper_tipping_info h6,
#skyscraper_tipping_form>fieldset:nth-child(1)>h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>
}

.lnpw_skyscraper_amount_value_1,
.lnpw_skyscraper_amount_value_2,
.lnpw_skyscraper_amount_value_3 {
    background: <?php echo esc_html($atts['fixed_background']);
    ?>;

}



<?php endif;
?>
</style>
<div id="lnpw_page">
    <div class="<?php echo ltrim("{$is_widget} lnpw_skyscraper_banner {$is_wide}"); ?>">
        <div class="<?php echo trim("{$is_widget} lnpw_skyscraper_header_container {$is_wide}"); ?>">
            <?php if ($logo) : ?>
            <div id="<?php echo "lnpw_skyscraper_logo_wrap_{$is_wide}"; ?>">
                <img width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="Tipping logo"
                    src=<?php echo esc_url($logo); ?> />
            </div>
            <?php endif; ?>
            <div>
                <?php if (!empty($atts['title'])) : ?>
                <h6><?php echo esc_html($atts['title']); ?></h6>
                <?php endif; ?>
                <?php if (!empty($atts['description'])) : ?>
                <p><?php echo esc_html($atts['description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="<?php echo trim("{$is_widget} lnpw_skyscraper_tipping_container {$container_suffix}"); ?>">
            <form method="POST" action="" id="<?php echo "{$form_prefix}skyscraper_tipping_form{$form_suffix}"; ?>">
                <fieldset>
                    <h6><?php echo (!empty($atts['tipping_text']) ? esc_html($atts['tipping_text']) : ''); ?>
                    </h6>
                    <div class="<?php echo trim("{$is_widget} lnpw_skyscraper_amount {$is_wide}"); ?>">
                        <?php foreach ($fixed_amount as $key => $value) : ?>

                        <?php if ($fixed_amount[$key]['enabled'] === 'true') : ?>
                        <div
                            class="<?php echo trim($is_widget . ' ' . 'lnpw_skyscraper_amount_' . $key . ' ' . $is_wide); ?>">
                            <div>
                                <input type="radio"
                                    class="<?php echo trim("{$is_widget} lnpw_skyscraper_tipping_default_amount {$is_wide}"); ?>"
                                    id="<?php echo $is_widget_id . $key . '_' . $is_wide; ?>"
                                    name="<?php echo "{$form_prefix}lnpw_skyscraper_tipping_default_amount_{$is_wide}"; ?>"
                                    <?php echo $key == $index ? 'required' : ''; ?>
                                    value="<?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?>">
                                <?php if (!empty($fixed_amount[$key]['amount'])) : ?>
                                <i class="<?php echo esc_html($fixed_amount[$key]['icon']); ?>"></i>
                                <?php endif; ?>
                            </div>
                            <label
                                for="<?php echo $key; ?>"><?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?></label>

                        </div>
                        <?php endif; ?>

                        <?php endforeach; ?>
                        <?php if ('true' === $atts['free_input']) : ?>
                        <div class="<?php echo trim("{$is_widget} lnpw_skyscraper_tipping_free_input {$is_wide}"); ?>">
                            <input type="number"
                                id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_amount{$form_suffix}"; ?>"
                                name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_amount_{$is_wide}"; ?>"
                                placeholder="0.00" required />

                            <select required
                                name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_currency_{$is_wide}"; ?>"
                                id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_currency{$form_suffix}"; ?>">
                                <option disabled value="">Select currency</option>
                                <?php foreach ($supported_currencies as $currency) : ?>
                                <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                                    value="<?php echo $currency; ?>">
                                    <?php echo $currency; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-arrows-alt-v"></i>
                            <?php endif; ?>

                        </div>

                    </div>
                    <div
                        class="<?php echo trim("{$is_widget}  lnpw_skyscraper_tipping_converted_values {$is_wide}"); ?>">
                        <input type="text"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_converted_amount{$form_suffix}"; ?>"
                            name="<?php echo "{$is_widget_id}lnpw_skyscraper_converted_amount_{$is_wide}"; ?>"
                            readonly />
                        <input type="text"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_converted_currency{$form_suffix}"; ?>"
                            name="<?php echo "{$is_widget_id}lnpw_skyscraper_converted_currency_{$is_wide}"; ?>"
                            readonly />
                    </div>


                    <div id="<?php echo "{$is_widget_id}lnpw_skyscraper_button{$form_suffix}"; ?>">
                        <input type="hidden"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_redirect_link_{$is_wide}"; ?>"
                            name="<?php echo  "{$is_widget_id}lnpw_skyscraper_redirect_link_{$is_wide}"; ?>"
                            value=<?php echo esc_attr($atts['redirect']); ?> />
                        <?php if ($collect_data == 'true') : ?>
                        <div>
                            <input type="button" name="next"
                                class="<?php echo  trim("{$is_widget} skyscraper-next-form {$is_wide}"); ?>"
                                value="Continue >" />
                        </div>
                        <?php else : ?>
                        <div>
                            <button type="submit"
                                id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping__button{$form_suffix}"; ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                        </div>
                        <?php endif; ?>
                    </div>

                </fieldset>
                <?php if ($collect_data == 'true') : ?>
                <fieldset>
                    <div class="<?php echo ltrim("{$is_widget} lnpw_skyscraper_donor_information {$is_wide}"); ?>">
                        <?php foreach ($collect as $key => $value) : ?>
                        <?php if ($collect[$key]['display'] == 'true') : ?>
                        <div
                            class="<?php echo "{$is_widget} lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_wrap {$is_wide}"; ?>">

                            <input type="text" placeholder="<?php echo $collect[$key]['label']; ?>"
                                id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_donor_{$collect[$key]['id']}_{$is_wide}"; ?>"
                                name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_{$is_wide}"; ?>"
                                <?php echo $collect[$key]['mandatory'] === 'true' ? 'required' : ''; ?> />

                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div id="<?php echo ltrim("{$is_widget_id}lnpw_skyscraper_button{$form_suffix}"); ?>">
                        <div>
                            <input type="button" name="previous"
                                class="<?php echo trim("{$form_prefix} skyscraper-previous-form {$is_wide}"); ?>"
                                value="< Previous" />
                        </div>
                        <div>
                            <button type="submit"
                                id=<?php echo "{$is_widget_id}lnpw_skyscraper_tipping__button{$form_suffix}"; ?>><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                        </div>
                    </div>
                </fieldset>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div id="powered_by_skyscraper">
        <p>Powered by <a href='https://btcpaywall.com/' target='_blank'>BTCPayWall</a></p>
    </div>
</div>
<?php

		return ob_get_clean();
	}




	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_page_tipping($atts)
	{
		$atts = shortcode_atts(array(
			'dimension' 	=> '520x600',
			'title'			=> 'Support my work',
			'currency'		=> 'SATS',
			'background_color'	=> '#E6E6E6',
			'title_text_color'	=> '#ffffff',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'logo_id'		=> 'https://btcpaywall.com/wp-content/uploads/2021/07/BTCPayWall-logo_square.jpg',
			'background_id'	=> '',
			'free_input'	=> 'true',
			'show_icon'		=> 'true',
			'input_background'	=> '#f6b330',
			'background'	=> '#1d5aa3',
			'value1_enabled' => 'true',
			'value1_amount' => '1000',
			'value1_currency' => 'SATS',
			'value1_icon'	=> 'fas fa-coffee',
			'value2_enabled' => 'true',
			'value2_amount' => '2000',
			'value2_currency' => 'SATS',
			'value2_icon'	=> 'fas fa-beer',
			'value3_enabled' => 'true',
			'value3_amount' => '3000',
			'value3_currency' => 'SATS',
			'value3_icon'	=> 'fas fa-cocktail',
			'display_name'	=> 'true',
			'mandatory_name' => 'false',
			'display_email'	=> 'true',
			'mandatory_email' => 'false',
			'display_phone'	=> 'true',
			'mandatory_phone' => 'false',
			'display_address'	=> 'true',
			'mandatory_address' => 'false',
			'display_message'	=> 'true',
			'mandatory_message' => 'false',
			'step1' 	=> 'Pledge',
			'active_color'	=> '',
			'step2' 	=> 'Info',
			'inactive_color'	=> '',
		), $atts);

		$dimension = explode('x', '520x600');
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']) ? wp_get_attachment_image_src($atts['logo_id'])[0] : $atts['logo_id'];

		$background = wp_get_attachment_image_src($atts['background_id']) ? wp_get_attachment_image_src($atts['background_id'])[0] : $atts['background_id'];
		$collect = $this->getCollect($atts);
		$collect_data = $this->display_is_enabled($collect);

		$fixed_amount = $this->getFixedAmount($atts);
		$first_enabled = array_column($fixed_amount, 'enabled');
		$d = array_search('true', $first_enabled);
		$index = 'value' . ($d + 1);


		ob_start();
	?>
<style>
.lnpw_page_tipping_container {
    background-color: <?php echo ($atts['background_color'] ? esc_html($atts['background_color']) : '');
    ?>;
    background-image: url(<?php echo ($background ? esc_html($background): '');
    ?>);
    width: <?php echo esc_html($dimension[0]) . 'px !important';
    ?>;
    height: <?php echo esc_html($dimension[1]) . 'px !important';
    ?>;
}

#lnpw_page_tipping__button,
#lnpw_page_button>input.page-next-form,
#lnpw_page_button>input.page-previous-form {
    color: <?php echo esc_html($atts['button_text_color']);
    ?>;
}

#lnpw_page_button {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

.lnpw_page_header_container h6 {
    color: <?php echo esc_html($atts['title_text_color']);
    ?>
}


.lnpw_page_tipping_info fieldset h6,
.lnpw_page_tipping_info h6 {
    color: <?php echo esc_html($atts['tipping_text_color']);
    ?>
}

.lnpw_page_amount_value_1,
.lnpw_page_amount_value_2,
.lnpw_page_amount_value_3,
.lnpw_page_tipping_free_input {
    background-color: <?php echo esc_html($atts['input_background']);
    ?>;

}

.lnpw_page_header_container,
#lnpw_page_button>div:nth-child(1)>input {
    background-color: <?php echo esc_html($atts['background']);
    ?>;
}

.lnpw_page_bar_container.active {
    background-color: <?php echo esc_html($atts['active_color']);
    ?>;
}

#lnpw_page_tipping__button,
#lnpw_page_button>input.page-next-form {
    background-color: <?php echo esc_html($atts['button_color']);
    ?>;
}

.lnpw_page_bar_container div {
    background-color: <?php echo esc_html($atts['inactive_color']);
    ?>;
}
</style>

<div id="lnpw_page">
    <div class="lnpw_page_tipping_container">
        <form method="POST" action="" id="page_tipping_form">
            <div class="lnpw_page_header_container">
                <?php if ($logo) : ?>
                <div id="lnpw_page_logo_wrap">
                    <img width="180" height="180" alt="Tipping page logo" src=<?php echo esc_url($logo); ?> />
                </div>
                <?php endif; ?>
                <?php if (!empty($atts['title'])) : ?>
                <div>
                    <h6><?php echo esc_html($atts['title']); ?></h6>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($collect_data == 'true') : ?>
            <div class='lnpw_page_bar_container'>
                <div class='lnpw_page_bar_container bar-1 active'>
                    <?php echo (!empty($atts['step1']) ? esc_html($atts['step1']) : '1.Pledge'); ?></div>
                <div class='lnpw_page_bar_container bar-2'>
                    <?php echo (!empty($atts['step2']) ? esc_html($atts['step2']) : '2.Info'); ?></div>
            </div>
            <?php endif; ?>
            <fieldset>
                <h6><?php echo (!empty($atts['tipping_text']) ? esc_html($atts['tipping_text']) : ''); ?>
                </h6>
                <div class="lnpw_page_amount">
                    <?php foreach ($fixed_amount as $key => $value) : ?>

                    <?php if ($fixed_amount[$key]['enabled'] === 'true') : ?>
                    <div class="<?php echo 'lnpw_page_amount_' . $key; ?>">
                        <div>
                            <input type="radio" class="lnpw_page_tipping_default_amount"
                                id="<?php echo "{$key}_page"; ?>" name="lnpw_page_tipping_default_amount"
                                <?php echo $key == $index ? 'required' : ''; ?>
                                value="<?php echo esc_html($fixed_amount[$key]['amount']  . ' ' . (!empty($fixed_amount[$key]['currency']) ? $fixed_amount[$key]['currency'] : 'SATS')); ?>">
                            <?php if (!empty($fixed_amount[$key]['amount'])) : ?>
                            <?php if ('true' === $atts['show_icon']) : ?>
                            <i class="<?php echo esc_html($fixed_amount[$key]['icon']); ?>"></i>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <label
                            for="<?php echo "{$key}_page" ?>"><?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . (!empty($fixed_amount[$key]['currency']) ? $fixed_amount[$key]['currency'] : 'SATS')); ?></label>

                    </div>
                    <?php endif; ?>

                    <?php endforeach; ?>
                    <?php if ('true' === $atts['free_input']) : ?>
                    <div class="lnpw_page_tipping_free_input">
                        <input type="number" id="lnpw_page_tipping_amount" name="lnpw_page_tipping_amount"
                            placeholder="0.00" required />


                        <select required name="lnpw_page_tipping_currency" id="lnpw_page_tipping_currency">
                            <option disabled value="">Select currency</option>
                            <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                                value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-arrows-alt-v"></i>

                    </div>
                    <?php endif; ?>

                </div>
                <?php if ('true' === $atts['free_input'] && $collect === 'false') : ?>
                <div class="lnpw_page_tipping_free_input">
                    <input type="number" id="lnpw_page_tipping_amount" name="lnpw_page_tipping_amount"
                        placeholder="0.00" required />


                    <select required name="lnpw_page_tipping_currency" id="lnpw_page_tipping_currency">
                        <option disabled value="">Select currency</option>
                        <?php foreach ($supported_currencies as $currency) : ?>
                        <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                            value="<?php echo $currency; ?>">
                            <?php echo $currency; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fas fa-arrows-alt-v"></i>
                </div>
                <?php endif; ?>

                <div class="lnpw_page_tipping_converted_values">
                    <input type="text" id="lnpw_page_converted_amount" name="lnpw_page_converted_amount" readonly />
                    <input type="text" id="lnpw_page_converted_currency" name="lnpw_page_converted_currency" readonly />
                </div>


                <div id="lnpw_page_button">
                    <input type="hidden" id="lnpw_page_redirect_link" name="lnpw_page_redirect_link"
                        value=<?php echo $atts['redirect']; ?> />
                    <?php if ($collect_data == 'true') : ?>
                    <input type="button" name="next" class="page-next-form" value="continue >" />
                    <?php else : ?>
                    <button type="submit"
                        id="lnpw_page_tipping__button"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                    <?php endif; ?>
                </div>

            </fieldset>
            <?php if ($collect_data == 'true') : ?>
            <fieldset>
                <div class="lnpw_page_donor_information">
                    <?php foreach ($collect as $key => $value) : ?>
                    <?php if ($collect[$key]['display'] == 'true') : ?>
                    <div class="<?php echo "lnpw_page_tipping_donor_{$collect[$key]['id']}_wrap"; ?>">

                        <input type="text" placeholder="<?php echo esc_html($collect[$key]['label']); ?>"
                            id="<?php echo "lnpw_page_tipping_donor_{$collect[$key]['id']}"; ?>"
                            name="<?php echo "lnpw_page_tipping_donor_{$collect[$key]['label']}"; ?>"
                            <?php echo $collect[$key]['mandatory'] === 'true' ? 'required' : ''; ?> />

                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="lnpw_page_button">
                    <div>
                        <input type="button" name="previous" class="page-previous-form" value="< previous" />
                    </div>
                    <div>
                        <button type="submit"
                            id="lnpw_page_tipping__button"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                    </div>
                </div>
            </fieldset>
            <?php endif; ?>
        </form>
    </div>
    <div id="powered_by">
        <p>Powered by <a href='https://btcpaywall.com/' target='_blank'>BTCPayWall</a></p>
    </div>
</div>
<?php

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function render_shortcode_lnpw_video_catalog()
	{
		if ($this->is_paid_content()) {
			return '';
		}

		global $post;

		$args = array(
			'post_type' => 'post',
		);
		$myposts = get_posts($args);
		ob_start();

	?>
<div class="lnpw_store">
    <?php foreach ($myposts as $post) : setup_postdata($post); ?>
    <?php
				$gutenberg = $this->extract_gutenberg_preview($post);
				$elementor = $this->extract_elementor_preview($post);
				$bakery = $this->extract_bakery_preview($post, 'lnpw_start_video');
				$shortcode = $this->extract_shortcode_preview($post, 'lnpw_start_video');
				$integrated = $this->integrate_preview_functions($gutenberg, $elementor, $bakery, $shortcode);

				if (null !== $integrated) : ?>
    <div class="lnpw_store_video">
        <div class="lnpw_store_video_preview">
            <img src="<?php echo esc_url($integrated['preview']) ?>" alt="Video preview" />
        </div>
        <div class="lnpw_store_video_information">
            <a href="<?php the_permalink($post); ?>">
                <h5><?php echo esc_html($integrated['title']); ?></h5>

                <h6><?php echo esc_html($integrated['description']); ?></h6>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach;
			wp_reset_postdata(); ?>
</div>
<?php

		return ob_get_clean();
	}
	private function extract_bakery_preview($post, $shortcode_attr)
	{

		$preview_data = array();
		$preview_data['title'] = 'Untitled';
		$preview_data['description'] = 'No description';
		$preview_data['preview'] = plugin_dir_url(__FILE__) . 'img/preview.png';

		$regex_pattern = get_shortcode_regex();

		preg_match('/' . $regex_pattern . '/s', $post->post_content, $regex_matches);

		if (empty($regex_matches[2])) {

			return;
		}

		if (substr($regex_matches[5], 12, 16) == $shortcode_attr) {

			$attributes = shortcode_parse_atts($regex_matches[0]);


			if (isset($attributes['title'])) {

				$preview_data['title'] = $attributes['title'];
			}


			if (isset($attributes['description'])) {

				$preview_data['description'] = $attributes['description'];
			}


			if (isset($attributes['preview'])) {

				$preview_data['preview'] = $attributes['preview'];
			}

			return $preview_data;
		}
	}
	private function extract_shortcode_preview($post, $shortcode_attr)
	{

		$preview_data = array();
		$preview_data['title'] = 'Untitled';
		$preview_data['description'] = 'No description';
		$preview_data['preview'] = plugin_dir_url(__FILE__) . 'img/preview.png';
		$regex_pattern = get_shortcode_regex();

		preg_match('/' . $regex_pattern . '/s', $post->post_content, $regex_matches);

		if (empty($regex_matches[2])) {

			return;
		}

		if ($regex_matches[2] == $shortcode_attr) {

			$attributes = shortcode_parse_atts($regex_matches[0]);

			if (isset($attributes['title'])) {
				$preview_data['title'] = $attributes['title'];
			}

			if (isset($attributes['description'])) {

				$preview_data['description'] = $attributes['description'];
			}

			if (isset($attributes['preview'])) {

				$preview_data['preview'] = $attributes['preview'];
			}
			return $preview_data;
		}
	}
	private function extract_gutenberg_preview($post)
	{
		$preview_data = array();
		if (has_blocks($post->post_content)) {

			$blocks = parse_blocks($post->post_content);

			if ($blocks[0]['blockName'] === 'lightning-paywall/gutenberg-start-video-block') {

				$preview_data['title'] = $blocks[0]['attrs']['title'] ?? 'Untitled';

				$preview_data['description'] = $blocks[0]['attrs']['description'] ?? 'No description';

				$preview_data['preview'] = $blocks[0]['attrs']['preview'] ?? plugin_dir_url(__FILE__) . 'img/preview.png';

				return $preview_data;
			}
		}
		return;
	}
	private function extract_elementor_preview($post)
	{
		$preview_data = array();

		$doc = new DOMDocument();

		if (!is_object($post->post_content)) {
			return;
		}
		$doc->loadHTML($post->post_content);

		$img = $doc->getElementsByTagName('img')[0];
		if ($doc->getElementsByTagName('h2')->item(0)) {

			$preview_data['title'] = $doc->getElementsByTagName('h2')->item(0)->nodeValue;
		} else {
			return;
		}

		if ($img) {

			$preview_data['preview'] = $img->getAttribute('src');
		}

		if ($doc->getElementsByTagName('p')) {

			$preview_data['description'] = $doc->getElementsByTagName('p')->item(0)->nodeValue;
		}

		return $preview_data;
	}
	public function integrate_preview_functions($gutt, $elem, $wpb, $sc)
	{
		if (isset($gutt)) {
			return $gutt;
		}
		if (isset($elem)) {
			return $elem;
		}
		if (isset($wpb)) {
			return $wpb;
		}
		if (isset($sc)) {
			return $sc;
		}
	}
}