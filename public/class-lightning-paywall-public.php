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
	private function collect_is_enabled($arr)
	{

		if (!is_array($arr)) {
			return;
		}

		foreach ($arr as $key => $value) {

			if ($arr[$key]['collect'] === 'true') {

				return true;
			}
		}
		return false;
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
			'title_text_color'	=> '#000000',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'description_color'	=> '#000000',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'logo_id'		=> '',
			'background_id'	=> '',
			'display_name'	=> 'false',
			'mandatory_name' => 'false',
			'display_email'	=> 'false',
			'mandatory_email' => 'false',
			'display_phone'	=> 'false',
			'mandatory_phone' => 'false',
			'display_address'	=> 'false',
			'mandatory_address' => 'false',
			'display_message'	=> 'false',
			'mandatory_message' => 'false',
			'widget'		=> 'false',
		), $atts);

		$dimension = explode('x', $atts['dimension']);
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']);
		$background = wp_get_attachment_image_src($atts['background_id']);
		$collect = array(
			array(
				'label' => 'Full_name',
				'display' => $atts['display_name'],
				'mandatory' => $atts['mandatory_name']
			),
			array(
				'label' => 'Email',
				'display' => $atts['display_email'],
				'mandatory' => $atts['mandatory_email']
			),
			array(
				'label' => 'Address',
				'display' => $atts['display_address'],
				'mandatory' => $atts['mandatory_address']
			),
			array(
				'label' => 'Phone',
				'display' => $atts['display_phone'],
				'mandatory' => $atts['mandatory_phone']
			),
			array(
				'label' => 'Message',
				'display' => $atts['display_message'],
				'mandatory' => $atts['mandatory_message']
			),

		);
		$collect_data = Lightning_Paywall_Public::display_is_enabled($collect);
		$is_widget = $atts['widget'] === 'true' ? 'lnpw_widget' : '';
		$form = $is_widget === 'lnpw_widget' ? 'tipping_form_box_widget' : 'tipping_form_box';
		$suffix = $is_widget === 'lnpw_widget' ? '_lnpw_widget' : '';
		$version = $atts['widget'] === 'true' ? 'widget' : 'basic';

		ob_start();
	?>
<style>
<?php if ($version==='widget') : ?>.lnpw_tipping_box_container.lnpw_widget {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);

}


#lnpw_tipping__button_lnpw_widget {
    color: <?php echo $atts['button_text_color'];
    ?>;
    background: <?php echo $atts['button_color'];
    ?>;
}

.lnpw_tipping_box_container_header_container.lnpw_widget h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}

.lnpw_tipping_container_info_container {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_tipping_box_container_info_container.lnpw_widget p {
    color: <?php echo $atts['description_color'];
    ?>
}

.lnpw_tipping_box_info_container fieldset h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

<?php else : ?>.lnpw_tipping_box_container {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);

}


#lnpw_tipping__button {
    color: <?php echo $atts['button_text_color'];
    ?>;
    background: <?php echo $atts['button_color'];
    ?>;
}

.lnpw_tipping_box_container_header_container h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}

.lnpw_tipping_container_info_container {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_tipping_box_container_info_container p {
    color: <?php echo $atts['description_color'];
    ?>
}

.lnpw_tipping_box_info_container fieldset h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

<?php endif;
?>
</style>


<div class="<?php echo trim("lnpw_tipping_box_container {$is_widget}"); ?>">

    <form method="POST" action="" id="<?php echo $form; ?>">
        <fieldset>
            <div class="lnpw_tipping_box_header_container">
                <?php if ($logo) : ?>
                <div class="lnpw_logo_wrap">
                    <img width="50" height="50" alt="Tipping logo" src=<?php echo esc_url($logo[0]); ?> />
                </div>
                <?php endif; ?>
                <?php if (!empty($atts['title'])) : ?>
                <div>
                    <h6><?php echo esc_html($atts['title']); ?></h6>
                </div>
                <?php endif; ?>
            </div>
            <div class="lnpw_tipping_box_info_container">
                <?php if (!empty($atts['description'])) : ?>
                <p><?php echo esc_html($atts['description']); ?></p>
                <?php endif; ?>
            </div>
            <h6><?php echo (!empty($atts['tipping_text']) ? $atts['tipping_text'] : 'Enter Tipping Amount'); ?></h6>
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


                </div>
                <div class="lnpw_tipping_converted_values">
                    <input type="text" id="<?php echo "lnpw_converted_amount{$suffix}"; ?>" name="lnpw_converted_amount"
                        readonly />
                    <input type="text" id="<?php echo "lnpw_converted_currency{$suffix}"; ?>"
                        name="lnpw_converted_currency" readonly />
                </div>
            </div>
            <input type="hidden" id="lnpw_redirect_link" name="lnpw_redirect_link"
                value=<?php echo $atts['redirect']; ?> />
            <div id="button">
                <?php if ($collect_data == 'true') : ?>
                <input type="button" name="next" class="<?php echo "next-form{$suffix}"; ?>" value="Next" />
                <?php else : ?>
                <button type="submit"
                    id="<?php echo "lnpw_tipping__button{$suffix}" ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                <?php endif; ?>
            </div>
        </fieldset>
        <?php if ($collect_data == 'true') : ?>
        <fieldset>
            <h6>Personal info</h6>
            <div class="lnpw_donor_information">
                <?php foreach ($collect as $key => $value) : ?>
                <?php if ($collect[$key]['display'] == 'true') : ?>
                <label for="<?php echo "lnpw_tipping_donor_{$collect[$key]['label']}{$suffix}"; ?>">
                    <?php echo $collect[$key]['label']; ?></label>
                <input type="text" id="<?php echo "lnpw_tipping_donor_{$collect[$key]['label']}{$suffix}"; ?>"
                    name="lnpw_tipping_donor_name"
                    <?php echo $collect[$key]['mandatory'] === 'true' ? 'required' : ''; ?> />
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div id="button">
                <input type="button" name="previous" class="<?php echo "previous-form{$suffix}"; ?>" value="Previous" />
                <button type="submit"
                    id="<?php echo "lnpw_tipping__button{$suffix}" ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
            </div>
        </fieldset>
        <?php endif; ?>
    </form>
</div>
<?php

		return ob_get_clean();
	}
	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public function render_shortcode_skyscraper_tipping($atts)
	{
		$atts = shortcode_atts(array(
			'dimension' 	=> '160x600',
			'title'			=> 'Support my work',
			'description'	=> '',
			'currency'		=> 'SATS',
			'background_color'	=> '#E6E6E6',
			'title_text_color'	=> '#000000',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'description_color'	=> '#000000',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'logo_id'		=> '',
			'background_id'	=> '',
			'free_input'	=> 'true',
			'fixed_background'	=> '',
			'value1_enabled' => 'false',
			'value1_amount' => '',
			'value1_currency' => '',
			'value1_icon'	=> '',
			'value2_enabled' => 'false',
			'value2_amount' => '',
			'value2_currency' => '',
			'value2_icon'	=> '',
			'value3_enabled' => 'false',
			'value3_amount' => '',
			'value3_currency' => '',
			'value3_icon'	=> '',
			'display_name'	=> 'false',
			'mandatory_name' => 'false',
			'display_email'	=> 'false',
			'mandatory_email' => 'false',
			'display_phone'	=> 'false',
			'mandatory_phone' => 'false',
			'display_address'	=> 'false',
			'mandatory_address' => 'false',
			'display_message'	=> 'false',
			'mandatory_message' => 'false',
			'widget'			=> 'false'
		), $atts);

		$dimension = explode('x', $atts['dimension']);
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']);
		$background = wp_get_attachment_image_src($atts['background_id']);
		$collect = array(
			array(
				'label' => 'Full_name',
				'display' => $atts['display_name'],
				'mandatory' => $atts['mandatory_name']
			),
			array(
				'label' => 'Email',
				'display' => $atts['display_email'],
				'mandatory' => $atts['mandatory_email']
			),
			array(
				'label' => 'Address',
				'display' => $atts['display_address'],
				'mandatory' => $atts['mandatory_address']
			),
			array(
				'label' => 'Phone',
				'display' => $atts['display_phone'],
				'mandatory' => $atts['mandatory_phone']
			),
			array(
				'label' => 'Message',
				'display' => $atts['display_message'],
				'mandatory' => $atts['mandatory_message']
			),

		);
		$collect_data = $this->display_is_enabled($collect);
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$predefined_enabled = get_option('lnpw_tipping_enter_amount');

		$fixed_amount = array(
			'value_1' => array(
				'enabled' 	=> $atts['value1_enabled'],
				'currency' => $atts['value1_currency'],
				'amount'	=> $atts['value1_amount'],
				'icon'		=> $atts['value1_icon']
			),
			'value_2' => array(
				'enabled' 	=> $atts['value2_enabled'],
				'currency' => $atts['value2_currency'],
				'amount'	=> $atts['value2_amount'],
				'icon'		=> $atts['value2_icon']
			),
			'value_3' => array(
				'enabled' 	=> $atts['value3_enabled'],
				'currency' => $atts['value3_currency'],
				'amount'	=> $atts['value3_amount'],
				'icon'		=> $atts['value3_icon']
			),
		);
		//$collect_data = $this->collect_is_enabled($collect);
		$first_enabled = array_column($fixed_amount, 'enabled');
		$d = array_search('true', $first_enabled);
		$index = 'value' . ($d + 1);
		$is_widget = $atts['widget'] === 'true' ? 'lnpw_widget' : '';
		$is_widget_id = $atts['widget'] === 'true' ? 'lnpw_widget_' : '';

		$is_wide = $dimension[0] === '600' ? 'wide' : 'high';
		$form_prefix = $is_widget === 'lnpw_widget' ? 'lnpw_widget_' : '';
		$form_suffix = ($is_widget === 'lnpw_widget' && $dimension[0] === '160') ? '_high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? '_wide' : '');

		$container_suffix = ($is_widget === 'lnpw_widget' && $dimension[0] === '160') ? 'high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? 'wide' : '');

		$version = ($is_widget === 'lnpw_widget' && $dimension[0] === '160') ? 'high' : (($is_widget === 'lnpw_widget' && $dimension[0] === '600') ? 'wide' : 'basic');

		ob_start();
	?>
<style>
<?php if ($version==='wide') : ?>.lnpw_widget.lnpw_skyscraper_tipping_container.wide {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
}

#lnpw_widget_lnpw_skyscraper_tipping__button_wide,
.skyscraper-next-form {
    color: <?php echo $atts['button_text_color'];
    ?>;
    background: <?php echo $atts['button_color'];
    ?>;
}

.lnpw_widget.lnpw_skyscraper_header_container.wide h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_container.info_container.wide {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_widget.lnpw_skyscraper_info_container.wide p {
    color: <?php echo $atts['description_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_info.wide fieldset h6,
.lnpw_widget.lnpw_skyscraper_tipping_info.wide h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_amount_value_1.wide,
.lnpw_widget.lnpw_skyscraper_amount_value_2.wide,
.lnpw_widget.lnpw_skyscraper_amount_value_3.wide {
    background: <?php echo $atts['fixed_background'];
    ?>;
}




<?php elseif ($version==='high') : ?>.lnpw_widget.lnpw_skyscraper_tipping_container.high {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
}

#lnpw_widget_lnpw_skyscraper_tipping__button_high,
.skyscraper-next-form.high {
    color: <?php echo $atts['button_text_color'];
    ?>;
    background: <?php echo $atts['button_color'];
    ?>;
}

.lnpw_widget.lnpw_skyscraper_header_container.high h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_container.info_container.high {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_widget.lnpw_skyscraper_info_container.high p {
    color: <?php echo $atts['description_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_tipping_info.high fieldset h6,
.lnpw_widget.lnpw_skyscraper_tipping_info.high h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

.lnpw_widget.lnpw_skyscraper_amount_value_1.high,
.lnpw_widget.lnpw_skyscraper_amount_value_2.high,
.lnpw_widget.lnpw_skyscraper_amount_value_3.high {
    background: <?php echo $atts['fixed_background'];
    ?>;

}

<?php else : ?>.lnpw_skyscraper_tipping_container {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
}

#lnpw_skyscraper_tipping__button,
.skyscraper-next-form {
    color: <?php echo $atts['button_text_color'];
    ?>;
    background: <?php echo $atts['button_color'];
    ?>;
}

.lnpw_skyscraper_header_container h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}

.lnpw_skyscraper_tipping_container.info_container {
    display: <?php echo (empty($atts['description'])) ? 'none': 'block';
    ?>
}

.lnpw_skyscraper_info_container p {
    color: <?php echo $atts['description_color'];
    ?>
}

.lnpw_skyscraper_tipping_info fieldset h6,
.lnpw_skyscraper_tipping_info h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

.lnpw_skyscraper_amount_value_1,
.lnpw_skyscraper_amount_value_2,
.lnpw_skyscraper_amount_value_3 {
    background: <?php echo $atts['fixed_background'];
    ?>;

}

<?php endif;
?>
</style>

<?php if ($dimension[0] === '600') : ?>
<div class="<?php echo "{$is_widget} lnpw_skyscraper_banner {$is_wide}"; ?>">
    <div class="<?php echo "{$is_widget} lnpw_skyscraper_header_container {$is_wide}"; ?>">
        <?php if ($logo) : ?>
        <div class="lnpw_logo_wrap">
            <img width="160" height="160" alt="Tipping logo" src=<?php echo esc_url($logo[0]); ?> />
        </div>
        <?php endif; ?>
        <?php if (!empty($atts['title'])) : ?>
        <div>
            <h6><?php echo esc_html($atts['title']); ?></h6>
        </div>
        <?php endif; ?>
    </div>
    <div class="<?php echo "{$is_widget} lnpw_skyscraper_info_container {$is_wide}"; ?>">
        <?php if (!empty($atts['description'])) : ?>
        <p><?php echo esc_html($atts['description']); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="<?php echo trim("{$is_widget} lnpw_skyscraper_tipping_container {$container_suffix}"); ?>">
        <form method="POST" action="" id="<?php echo "{$form_prefix}skyscraper_tipping_form{$form_suffix}"; ?>">
            <fieldset>
                <div>
                    <?php if ($dimension[0] === '160') : ?>

                    <div class="<?php echo "{$is_widget} lnpw_skyscraper_header_container {$is_wide}"; ?>">
                        <?php if ($logo) : ?>
                        <div class="lnpw_logo_wrap">
                            <img width="160" height="160" alt="Tipping logo" src=<?php echo esc_url($logo[0]); ?> />
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($atts['title'])) : ?>
                        <div>
                            <h6><?php echo esc_html($atts['title']); ?></h6>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="<?php echo "{$is_widget} lnpw_skyscraper_info_container {$is_wide}"; ?>">
                        <?php if (!empty($atts['description'])) : ?>
                        <p><?php echo esc_html($atts['description']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php endif; ?>
                </div>
                <h6><?php echo (!empty($atts['tipping_text']) ? $atts['tipping_text'] : 'Enter Tipping Amount'); ?></h6>
                <div class="<?php echo "{$is_widget} lnpw_skyscraper_amount {$is_wide}"; ?>">
                    <?php foreach ($fixed_amount as $key => $value) : ?>

                    <?php if ($fixed_amount[$key]['enabled'] === 'true') : ?>
                    <div class="<?php echo $is_widget . ' ' . 'lnpw_skyscraper_amount_' . $key . ' ' . $is_wide; ?>">
                        <div>
                            <input type="radio"
                                class="<?php echo "{$is_widget} lnpw_skyscraper_tipping_default_amount {$is_wide}"; ?>"
                                id="<?php echo $is_widget_id . $key . '_' . $is_wide; ?>"
                                name="<?php echo "{$is_widget}_lnpw_skyscraper_tipping_default_amount_{$is_wide}"; ?>"
                                <?php echo $key == $index ? 'required' : ''; ?>
                                value="<?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?>">
                            <?php if (!empty($fixed_amount[$key]['amount'])) : ?>
                            <i class="<?php echo $fixed_amount[$key]['icon']; ?>"></i>
                            <?php endif; ?>
                        </div>
                        <label
                            for="<?php echo $key; ?>"><?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?></label>

                    </div>
                    <?php endif; ?>

                    <?php endforeach; ?>
                    <?php if ('true' === $atts['free_input'] && $dimension[0] === '600') : ?>
                    <div class="<?php echo "{$is_widget} lnpw_skyscraper_tipping_free_input {$is_wide}"; ?>">
                        <input type="number"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_tipping_amount{$form_suffix}"; ?>"
                            name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_amount_{$is_wide}"; ?>"
                            placeholder="0.00" required />


                        <select required
                            name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_currency_ {$is_wide}"; ?>"
                            id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_currency{$form_suffix}"; ?>">
                            <option disabled value="">Select currency</option>
                            <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                                value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>

                    </div>
                    <?php if ('true' === $atts['free_input'] && $dimension[0] === '160') : ?>
                    <div class="<?php echo "{$is_widget} lnpw_skyscraper_tipping_free_input {$is_wide}"; ?>">
                        <input type="number"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_tipping_amount{$form_suffix}"; ?>"
                            name="<?php echo  "{$is_widget_id}lnpw_skyscraper_tipping_amount_{$is_wide}"; ?>"
                            placeholder="0.00" required />


                        <select required
                            name="<?php echo  "{$is_widget_id}lnpw_skyscraper_tipping_currency_{$is_wide}"; ?>"
                            id="<?php echo  "{$is_widget_id}lnpw_skyscraper_tipping_currency{$form_suffix}"; ?>">
                            <option disabled value="">Select currency</option>
                            <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                                value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    <div class="<?php echo "{$is_widget}  lnpw_skyscraper_tipping_converted_values {$is_wide}"; ?>">
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
                            value=<?php echo $atts['redirect']; ?> />
                        <?php if ($collect_data == 'true') : ?>
                        <input type="button" name="next"
                            class="<?php echo  "{$is_widget} skyscraper-next-form {$is_wide}"; ?>" value="Next" />
                        <?php else : ?>
                        <button type="submit"
                            id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping__button{$form_suffix}"; ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                        <?php endif; ?>
                    </div>

            </fieldset>
            <?php if ($collect_data == 'true') : ?>
            <fieldset>
                <div class="<?php echo "{$is_widget} lnpw_skyscraper_donor_information {$is_wide}"; ?>">
                    <?php foreach ($collect as $key => $value) : ?>
                    <?php if ($collect[$key]['display'] == 'true') : ?>
                    <div
                        class="<?php echo "{$is_widget} lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_wrap {$is_wide}"; ?>">
                        <label
                            for="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_{$is_wide}"; ?>">
                            <?php echo $collect[$key]['label']; ?></label>
                        <input type="text"
                            id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_{$is_wide}"; ?>"
                            name="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping_donor_{$collect[$key]['label']}_{$is_wide}"; ?>"
                            <?php echo $collect[$key]['mandatory'] === 'true' ? 'required' : ''; ?> />
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="<?php echo ltrim("{$is_widget_id}lnpw_skyscraper_button{$form_suffix}"); ?>">
                    <input type="button" name="previous"
                        class="<?php echo "{$is_widget_id}skyscraper-previous-form{$form_suffix}"; ?>"
                        value="Previous" />
                    <button type="submit"
                        id="<?php echo "{$is_widget_id}lnpw_skyscraper_tipping__button_{$is_wide}"; ?>"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
                </div>
            </fieldset>
            <?php endif; ?>
        </form>
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
			'title_text_color'	=> '#000000',
			'tipping_text'	=> 'Enter Tipping Amount',
			'tipping_text_color'	=> '#000000',
			'redirect'		=> get_site_url(),
			'amount'		=> '',
			'button_text'	=> 'Tipping now',
			'button_text_color'	=> '#FFFFFF',
			'button_color'	=> '#FE642E',
			'logo_id'		=> '',
			'background_id'	=> '',
			'free_input'	=> 'true',
			'show_icon'		=> 'false',
			'fixed_background'	=> '#ffa500',
			'header_background'	=> '#ffa500',
			'value1_enabled' => 'false',
			'value1_amount' => '',
			'value1_currency' => 'SATS',
			'value1_icon'	=> '',
			'value2_enabled' => 'false',
			'value2_amount' => '',
			'value2_currency' => 'SATS',
			'value2_icon'	=> '',
			'value3_enabled' => 'false',
			'value3_amount' => '',
			'value3_currency' => 'SATS',
			'value3_icon'	=> '',
			'display_name'	=> 'false',
			'mandatory_name' => 'false',
			'display_email'	=> 'false',
			'mandatory_email' => 'false',
			'display_phone'	=> 'false',
			'mandatory_phone' => 'false',
			'display_address'	=> 'false',
			'mandatory_address' => 'false',
			'display_message'	=> 'false',
			'mandatory_message' => 'false',
			'step1' 	=> 'Pledge',
			'active_color'	=> '',
			'step2' 	=> 'Info',
			'inactive_color'	=> '',
		), $atts);

		$dimension = explode('x', $atts['dimension']);
		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$logo = wp_get_attachment_image_src($atts['logo_id']) ? wp_get_attachment_image_src($atts['logo_id'])[0] : $atts['logo_id'];

		$background = wp_get_attachment_image_src($atts['background_id']);
		$collect = array(
			array(
				'id'    => 'name',
				'label' => 'Full name',
				'display' => $atts['display_name'],
				'mandatory' => $atts['mandatory_name']
			),
			array(
				'id'    => 'email',
				'label' => 'Email',
				'display' => $atts['display_email'],
				'mandatory' => $atts['mandatory_email']
			),
			array(
				'id'    => 'address',
				'label' => 'Address',
				'display' => $atts['display_address'],
				'mandatory' => $atts['mandatory_address']
			),
			array(
				'id'    => 'phone',
				'label' => 'Phone',
				'display' => $atts['display_phone'],
				'mandatory' => $atts['mandatory_phone']
			),
			array(
				'id'    => 'message',
				'label' => 'Message',
				'display' => $atts['display_message'],
				'mandatory' => $atts['mandatory_message']
			),

		);
		$collect_data = $this->display_is_enabled($collect);

		$fixed_amount = array(
			'value_1' => array(
				'enabled' 	=> $atts['value1_enabled'],
				'currency' => $atts['value1_currency'],
				'amount'	=> $atts['value1_amount'],
				'icon'		=> $atts['value1_icon']
			),
			'value_2' => array(
				'enabled' 	=> $atts['value2_enabled'],
				'currency' => $atts['value2_currency'],
				'amount'	=> $atts['value2_amount'],
				'icon'		=> $atts['value2_icon']
			),
			'value_3' => array(
				'enabled' 	=> $atts['value3_enabled'],
				'currency' => $atts['value3_currency'],
				'amount'	=> $atts['value3_amount'],
				'icon'		=> $atts['value3_icon']
			),
		);
		$first_enabled = array_column($fixed_amount, 'enabled');
		$d = array_search('true', $first_enabled);
		$index = 'value' . ($d + 1);


		ob_start();
		?>
<style>
.lnpw_page_tipping_container {
    background-color: <?php echo ($atts['background_color'] ? $atts['background_color'] : '');
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
}

#lnpw_page_tipping__button,
#lnpw_page_button>input.page-next-form,
#lnpw_page_button>input.page-previous-form {
    color: <?php echo $atts['button_text_color'];
    ?>;
}
#lnpw_page_button > *{
	background-color: <?php echo $atts['header_background'];
    ?>;
}
.lnpw_page_header_container h6 {
    color: <?php echo $atts['title_text_color'];
    ?>
}


.lnpw_page_tipping_info fieldset h6,
.lnpw_page_tipping_info h6 {
    color: <?php echo $atts['tipping_text_color'];
    ?>
}

.lnpw_page_amount_value_1,
.lnpw_page_amount_value_2,
.lnpw_page_amount_value_3,
.lnpw_page_tipping_free_input {
    background-color: <?php echo $atts['fixed_background'];
    ?>;

}

.lnpw_page_header_container{
    background-color: <?php echo $atts['header_background'];
    ?>;
}

.lnpw_page_bar_container.active {
    background-color: <?php echo $atts['active_color'];
    ?>;
}

.lnpw_page_bar_container div {
    background-color: <?php echo $atts['inactive_color'];
    ?>;
}
</style>


<div class="lnpw_page_tipping_container">
    <form method="POST" action="" id="page_tipping_form">
        <div class="lnpw_page_header_container">
            <?php if ($logo) : ?>
            <div class="lnpw_logo_wrap">
                <img width="90" height="90" alt="Tipping page logo" src=<?php echo esc_url($logo); ?> />
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
            <h6><?php echo (!empty($atts['tipping_text']) ? esc_html($atts['tipping_text']) : 'Enter Tipping Amount'); ?>
            </h6>
            <div class="lnpw_page_amount">
                <?php foreach ($fixed_amount as $key => $value) : ?>

                <?php if ($fixed_amount[$key]['enabled'] === 'true') : ?>
                <div class="<?php echo 'lnpw_page_amount_' . $key; ?>">
                    <div>
                        <input type="radio" class="lnpw_page_tipping_default_amount" id="<?php echo "{$key}_page"; ?>"
                            name="lnpw_page_tipping_default_amount" <?php echo $key == $index ? 'required' : ''; ?>
                            value="<?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?>">
                        <?php if (!empty($fixed_amount[$key]['amount'])) : ?>
                        <?php if ('true' === $atts['show_icon']) : ?>
                        <i class="<?php echo $fixed_amount[$key]['icon']; ?>"></i>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <label
                        for="<?php echo "{$key}_page" ?>"><?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?></label>

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
                </div>
                <?php endif; ?>

            </div>
            <?php if ('true' === $atts['free_input'] && $collect === 'false') : ?>
            <div class="lnpw_page_tipping_free_input">
                <input type="number" id="lnpw_page_tipping_amount" name="lnpw_page_tipping_amount" placeholder="0.00"
                    required />


                <select required name="lnpw_page_tipping_currency" id="lnpw_page_tipping_currency">
                    <option disabled value="">Select currency</option>
                    <?php foreach ($supported_currencies as $currency) : ?>
                    <option <?php echo $atts['currency'] === $currency ? 'selected' : ''; ?>
                        value="<?php echo $currency; ?>">
                        <?php echo $currency; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
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
                <button type="submit" style="width:100%;"
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
                <input type="button" name="previous" class="page-previous-form" value="< previous" />
                <button type="submit"
                    id="lnpw_page_tipping__button"><?php echo (!empty($atts['button_text']) ? esc_html($atts['button_text']) : 'Tip'); ?></button>
            </div>
        </fieldset>
        <?php endif; ?>
    </form>
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
	public function render_shortcode_tipping()
	{

		$supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
		$predefined_enabled = get_option('lnpw_tipping_enter_amount');
		$used_currency  = get_option('lnpw_tipping_currency');
		$used_dimension      = get_option('lnpw_tipping_dimension', '250x500');
		$dimension = explode('x', $used_dimension);
		$redirect = get_option('lnpw_tipping_redirect');
		$collect = get_option('lnpw_tipping_collect');
		$fixed_amount = get_option('lnpw_tipping_fixed_amount');
		$text = get_option('lnpw_tipping_text', array(
			'title'			=> 'Support my work',
			'description'	=> '',
			'info'			=> 'Enter Tipping Amount',
			'button'		=> 'Tipping now'
		));
		$color = get_option('lnpw_tipping_color');
		$image = get_option('lnpw_tipping_image');
		$logo = wp_get_attachment_image_src($image['logo']);
		$background = wp_get_attachment_image_src($image['background']);
		$fixed_amount = get_option('lnpw_tipping_fixed_amount');
		$collect_data = $this->collect_is_enabled($collect);
		$first_enabled = array_column($fixed_amount, 'enabled');
		$d = array_search('true', $first_enabled);
		$index = 'value' . ($d + 1);

		ob_start();
		?>
<style>
.lnpw_tipping_container {
    background-color: <?php echo ($color['background'] ? $color['background'] : '');
    ?>;
    width: <?php echo $dimension[0] . 'px !important';
    ?>;
    height: <?php echo $dimension[1] . 'px !important';
    ?>;
    background-image: url(<?php echo ($background ? $background[0] : '');
    ?>);
    background-size: cover;
    background-repeat: no-repeat;
}

#lnpw_tipping__button {
    color: <?php echo $color['button_text'];
    ?>;
    background: <?php echo $color['button'];
    ?>;
}

.header_container h4 {
    color: <?php echo $color['title'];
    ?>
}

.lnpw_tipping_container.info_container {
    display: <?php echo (empty($text['description'])) ? 'none': 'block';
    ?>
}

.info_container p {
    color: <?php echo $color['description'];
    ?>
}

.lnpw_tipping_info fieldset h4 {
    color: <?php echo $color['tipping'];
    ?>
}

#lnpw_converted_amount,
#lnpw_tipping_currency,
#lnpw_converted_currency {
    background: <?php echo ($color['background'] ? $color['background'] : '');
    ?>;
}
</style>


<div class="lnpw_tipping_container">

    <div class="lnpw_tipping_info">
        <form method="POST" action="" id="tipping_form">
            <fieldset>
                <div class="header_container">
                    <?php if ($logo) : ?>
                    <div class="lnpw_logo_wrap">
                        <img width="50" height="50" alt="Tipping logo" src=<?php echo esc_url($logo[0]); ?> />
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($text['title'])) : ?>
                    <div>
                        <h6><?php echo esc_html($text['title']); ?></h6>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="info_container">
                    <?php if (!empty($text['description'])) : ?>
                    <p><?php echo esc_html($text['description']); ?></p>
                    <?php endif; ?>
                </div>
                <h6><?php echo (!empty($text['info']) ? $text['info'] : 'Enter Tipping Amount'); ?></h6>
                <div class="lnpw_tipping_values">
                    <div class="predefined_container">
                        <?php foreach ($fixed_amount as $key => $value) : ?>

                        <?php if ($fixed_amount[$key]['enabled'] === 'true') : ?>
                        <div>
                            <input type="radio" class="lnpw_tipping_default_amount" id="<?php echo $key; ?>"
                                name="lnpw_tipping_default_amount" <?php echo $key == $index ? 'required' : ''; ?>
                                value="<?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?>">
                            <?php if (!empty($fixed_amount[$key]['amount'])) : ?>
                            <i class="<?php echo $fixed_amount[$key]['icon']; ?>"></i>
                            <?php endif; ?>
                            <label style="display: <?php echo empty($fixed_amount[$key]['icon']) ? 'block' : 'none'; ?>"
                                for="<?php echo $key; ?>"><?php echo esc_html($fixed_amount[$key]['amount'] . ' ' . $fixed_amount[$key]['currency']); ?></label>

                        </div>
                        <?php endif; ?>

                        <?php endforeach; ?>
                        <div class="lnpw_tipping_free_input">
                            <input type="number" id="lnpw_tipping_amount" name="lnpw_tipping_amount" placeholder="0.00"
                                required />

                            <select required name="lnpw_tipping_currency" id="lnpw_tipping_currency">
                                <option disabled value="">Select currency</option>
                                <?php foreach ($supported_currencies as $currency) : ?>
                                <option <?php echo $used_currency === $currency ? 'selected' : ''; ?>
                                    value="<?php echo $currency; ?>">
                                    <?php echo $currency; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="lnpw_tipping_converted_values">
                        <input type="text" id="lnpw_converted_amount" name="lnpw_converted_amount" readonly />
                        <input type="text" id="lnpw_converted_currency" name="lnpw_converted_currency" readonly />
                    </div>
                </div>
                <input type="hidden" id="lnpw_redirect_link" name="lnpw_redirect_link" value=<?php echo $redirect; ?> />
                <div id="button">
                    <?php if ($collect_data == 'true') : ?>
                    <input type="button" name="next" class="next-form" value="Next" />
                    <?php else : ?>
                    <button type="submit"
                        id="lnpw_tipping__button"><?php echo (!empty($text['button']) ? $text['button'] : 'Tip'); ?></button>
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php if ($collect_data == 'true') : ?>
            <fieldset>
                <h4>Personal info</h4>
                <div class="lnpw_donor_information">
                    <?php foreach ($collect as $key => $value) : ?>
                    <?php if ($collect[$key]['collect'] == 'true') : ?>
                    <label for="<?php echo "lnpw_tipping_donor_{$collect[$key]['label']}"; ?>">
                        <?php echo $collect[$key]['label']; ?></label>
                    <input type="text" id="<?php echo "lnpw_tipping_donor_{$collect[$key]['label']}"; ?>"
                        name="lnpw_tipping_donor_name"
                        <?php echo $collect[$key]['mandatory'] === 'true' ? 'required' : ''; ?> />
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="button">
                    <input type="button" name="previous" class="previous-form" value="Previous" />
                    <button type="submit"
                        id="lnpw_tipping__button"><?php echo (!empty($text['button']) ? $text['button'] : 'Tip'); ?></button>
                </div>
            </fieldset>
            <?php endif; ?>
        </form>
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