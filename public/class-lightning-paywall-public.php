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
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lightning-paywall-public.js', array('jquery'), null, false);
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

		wp_enqueue_script('btcpay', get_option('lnpw_btcpay_server_url', '') . '/modal/btcpay.js', array(), null, true);

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
			'invoice_id' => $invoice_id,
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
		return get_option('lnpw_default_payblock_text') ?: 'For access to content first pay';
	}
	public static function get_payblock_button_string()
	{
		return get_option('lnpw_default_payblock_button') ?: 'Pay';
	}

	private function calculate_price_for_invoice($post_id)
	{

		if (get_option('lnpw_currency') === 'SATS') {

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

		$data = array(
			'amount'    => $amount,
			'currency' => get_option('lnpw_currency') != 'SATS' ? get_option('lnpw_currency') : 'BTC',
			'metadata' => array(
				'orderId'  => $order_id,
				'itemDesc' => 'Pay by view: ' . get_the_title($post_id),
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

		return $body['id'];
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

		$order_id = sanitize_text_field($_POST['order_id']);
		$post_id  = get_post_meta($order_id, 'lnpw_post_id', true);
		$secret   = get_post_meta($order_id, 'lnpw_secret', true);

		if (empty($post_id)) {
			wp_send_json_error();
		}

		$cookie_path = parse_url(get_permalink($post_id), PHP_URL_PATH);

		setcookie('lnpw_' . $post_id, $secret, $this->get_cookie_duration($post_id), $cookie_path);

		update_post_meta($order_id, 'lnpw_status', 'success');

		wp_send_json_success();
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

	/**
	 * @param  array  $atts
	 *
	 * @return string
	 */
	public function render_shortcode_lnpw_start_content($atts)
	{

		$atts = shortcode_atts(array(
			'pay_block' => 'false',
			'price'     => '',
			'currency'  => '',
			'duration'  => '',
			'duration_type' => '',
		), $atts);

		$valid_currency = in_array($atts['currency'], Lightning_Paywall_Admin::CURRENCIES);
		$valid_duration = in_array($atts['duration_type'], Lightning_Paywall_Admin::DURATIONS);

		if (!empty($atts['currency']) && $valid_currency) {
			update_post_meta(get_the_ID(), 'lnpw_currency', sanitize_text_field($atts['currency']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_currency');
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
		$s_data = '<!-- lnpw:start_content -->';

		$payblock = $atts['pay_block'] === 'true';

		if ($payblock) {
			return do_shortcode('[lnpw_pay_block]') . $s_data;
		}
	}

	public function render_shortcode_lnpw_start_video($atts)
	{
		$img_preview = plugin_dir_url(__FILE__) . 'img/preview.png';

		$atts = shortcode_atts(array(
			'pay_view_block' => 'false',
			'title' => 'Untitled',
			'description' => 'No description',
			'preview' => $img_preview,
			'currency' => '',
			'price'     => '',
			'duration'  => '',
			'duration_type' => ''
		), $atts);

		$valid_currency = in_array($atts['currency'], Lightning_Paywall_Admin::CURRENCIES);

		$valid_duration = in_array($atts['duration_type'], Lightning_Paywall_Admin::DURATIONS);

		if (!empty($atts['currency']) && $valid_currency) {
			update_post_meta(get_the_ID(), 'lnpw_currency', sanitize_text_field($atts['currency']));
		} else {
			delete_post_meta(get_the_ID(), 'lnpw_currency');
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

		$payblock = $atts['pay_view_block'] === 'true';


		$s_data = '<!-- lnpw:start_content -->';

		if ($payblock) {
			return do_shortcode("[lnpw_pay_video_block title='{$atts['title']}' description='{$atts['description']}' preview='{$atts['preview']}']") . $s_data;
		}
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
					<button type="button" id="lnpw_pay__button" data-post_id="<?php echo  get_the_ID(); ?>"><?php echo Lightning_Paywall_Public::get_payblock_button_string() ?></button>
				</div>
				<div class="lnpw_pay__loading">
					<p class="loading"></p>
				</div>
				<div class="lnpw_help">
					<a class="lnpw_help__link" href="https://lightning-paywall.coincharge.io/how-to-pay-the-lightning-paywall/" target="_blank">Help</a>
				</div>
			</div>
		</div>
	<?php


		return ob_get_clean();
	}

	public function render_shortcode_lnpw_video_catalog($atts)
	{
		if ($this->is_paid_content()) {
			return '';
		}

		$args = array(
			'post_type' => 'post',
		);


		$myposts = get_posts($args);
		ob_start();

	?>
		<div class="lnpw_store">
			<?php foreach ($myposts as $post) : setup_postdata($post); ?>
				<?php $gutenberg = $this->extract_gutenberg_preview($post);
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


		$regex_pattern = get_shortcode_regex();

		preg_match('/' . $regex_pattern . '/s', $post->post_content, $regex_matches);

		if (empty($regex_matches[2])) {

			return;
		}

		if (substr($regex_matches[5], 12, 16) == $shortcode_attr) {

			$attributes = shortcode_parse_atts($regex_matches[0]);

			$preview_data['title'] = 'Untitled';

			if (isset($attributes['title'])) {

				$preview_data['title'] = $attributes['title'];
			}

			$preview_data['description'] = 'No description';

			if (isset($attributes['description'])) {

				$preview_data['description'] = $attributes['description'];
			}

			$preview_data['preview'] = plugin_dir_url(__FILE__) . 'img/preview.png';

			if (isset($attributes['preview'])) {

				$preview_data['preview'] = $attributes['preview'];
			}

			return $preview_data;
		}
	}
	private function extract_shortcode_preview($post, $shortcode_attr)
	{

		$preview_data = array();

		$regex_pattern = get_shortcode_regex();

		preg_match('/' . $regex_pattern . '/s', $post->post_content, $regex_matches);

		if (empty($regex_matches[2])) {

			return;
		}

		if ($regex_matches[2] == $shortcode_attr) {

			$attributes = shortcode_parse_atts($regex_matches[0]);
			$preview_data['title'] = 'Untitled';

			if (isset($attributes['title'])) {
				$preview_data['title'] = $attributes['title'];
			}

			$preview_data['description'] = 'No description';

			if (isset($attributes['description'])) {

				$preview_data['description'] = $attributes['description'];
			}

			$preview_data['preview'] = plugin_dir_url(__FILE__) . 'img/preview.png';

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
	private function integrate_preview_functions($gutt, $elem, $wpb, $sc)
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
