<?php
/**
 * Admin settings page for Minimum, under the WooCommerce menu.
 *
 * @package Minimum\Rules
 */

declare(strict_types=1);

namespace Minimum\Rules;

defined( 'ABSPATH' ) || exit;

use Minimum\Contract\HasHooks;

/**
 * Renders the WooCommerce submenu settings screen: a rules builder (scope, min,
 * max, step), a minimum order total and customisable notice messages.
 */
final class Admin implements HasHooks {

	private const PAGE    = 'minimum-settings';
	private const GROUP   = 'minimum_settings_group';
	private const SECTION = 'minimum_general';

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings store.
	 */
	public function __construct(
		private readonly Settings $settings,
	) {}

	/**
	 * Register admin hooks.
	 */
	public function registerHooks(): void {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter(
			'plugin_action_links_' . plugin_basename( \Minimum\PLUGIN_FILE ),
			array( $this, 'action_links' ),
		);
	}

	/**
	 * Add a Settings link on the plugins screen.
	 *
	 * @param mixed $links Existing action links.
	 * @return array<int, string>
	 */
	public function action_links( mixed $links ): array {
		$links = is_array( $links ) ? $links : array();

		$url           = admin_url( 'admin.php?page=' . self::PAGE );
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html__( 'Settings', 'plogins-minimum' ),
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Register the WooCommerce submenu page.
	 */
	public function add_menu_page(): void {
		add_submenu_page(
			'woocommerce',
			__( 'Minimum - Order Quantity Rules', 'plogins-minimum' ),
			__( 'Minimum', 'plogins-minimum' ),
			'manage_woocommerce',
			self::PAGE,
			array( $this, 'render_page' ),
		);
	}

	/**
	 * Register the setting and its sanitiser.
	 */
	public function register_settings(): void {
		register_setting(
			self::GROUP,
			Settings::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this->settings, 'sanitize' ),
				'default'           => Settings::defaults(),
			),
		);

		add_settings_section(
			self::SECTION,
			'',
			'__return_false',
			self::PAGE,
		);
	}

	/**
	 * Enqueue the admin assets on this settings page only.
	 *
	 * @param string $hook Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook ): void {
		if ( false === strpos( $hook, self::PAGE ) ) {
			return;
		}

		$plugin = \Minimum\Plugin::instance();

		wp_enqueue_style(
			'minimum-admin',
			$plugin->url( 'assets/css/admin.css' ),
			array(),
			\Minimum\VERSION,
		);

		wp_enqueue_script(
			'minimum-admin',
			$plugin->url( 'assets/js/admin.js' ),
			array(),
			\Minimum\VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			),
		);

		wp_localize_script(
			'minimum-admin',
			'minimumAdmin',
			array(
				'optionName' => Settings::OPTION,
				'scopes'     => $this->scope_choices(),
				'i18n'       => array(
					'remove'      => __( 'Remove', 'plogins-minimum' ),
					'targetLabel' => __( 'Target ID', 'plogins-minimum' ),
					'scopeLabel'  => __( 'Scope', 'plogins-minimum' ),
					'minLabel'    => __( 'Min', 'plogins-minimum' ),
					'maxLabel'    => __( 'Max', 'plogins-minimum' ),
					'stepLabel'   => __( 'Step', 'plogins-minimum' ),
				),
			),
		);
	}

	/**
	 * Available rule scopes, label keyed by stored value.
	 *
	 * @return array<string, string>
	 */
	private function scope_choices(): array {
		return array(
			'global'   => __( 'All products (global)', 'plogins-minimum' ),
			'product'  => __( 'Specific product', 'plogins-minimum' ),
			'category' => __( 'Product category', 'plogins-minimum' ),
		);
	}

	/**
	 * Render the settings page.
	 */
	public function render_page(): void {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$all     = $this->settings->all();
		$rules   = $this->settings->rules();
		$enabled = (bool) $all['enabled'];
		$name    = Settings::OPTION;
		?>
		<div class="wrap minimum-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p class="minimum-settings__lead">
				<?php esc_html_e( 'Define quantity rules and a minimum order total. Rules are enforced when products are added to the cart and again at checkout, with clear notices that block checkout until every rule is satisfied.', 'plogins-minimum' ); ?>
			</p>

			<form method="post" action="options.php">
				<?php settings_fields( self::GROUP ); ?>

				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><?php esc_html_e( 'Enforcement', 'plogins-minimum' ); ?></th>
							<td>
								<label for="minimum_enabled">
									<input
										type="checkbox"
										id="minimum_enabled"
										name="<?php echo esc_attr( $name ); ?>[enabled]"
										value="1"
										<?php checked( $enabled, true ); ?>
									/>
									<?php esc_html_e( 'Enforce quantity and order-total rules.', 'plogins-minimum' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Master switch. Turn this off to keep your rules saved but stop enforcing them at the cart and checkout.', 'plogins-minimum' ); ?></p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="minimum_order_total"><?php esc_html_e( 'Minimum order total', 'plogins-minimum' ); ?></label>
							</th>
							<td>
								<input
									type="number"
									id="minimum_order_total"
									name="<?php echo esc_attr( $name ); ?>[min_order_total]"
									value="<?php echo esc_attr( (string) $this->settings->min_order_total() ); ?>"
									min="0"
									step="0.01"
									class="small-text"
								/>
								<span class="minimum-currency"><?php echo esc_html( $this->currency_symbol() ); ?></span>
								<p class="description"><?php esc_html_e( 'The smallest cart subtotal a customer can check out with. Set to 0 to disable the order-total rule.', 'plogins-minimum' ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>

				<h2 class="minimum-section-title"><?php esc_html_e( 'Quantity rules', 'plogins-minimum' ); ?></h2>
				<p class="description minimum-rules-intro">
					<?php esc_html_e( 'Each rule sets a floor, a ceiling and a step for how many units a customer can buy. When several rules could apply, the most specific one wins: a product rule beats a category rule, which beats the global rule. Leave any field at 0 to ignore that constraint.', 'plogins-minimum' ); ?>
				</p>

				<div id="minimum-rules">
					<p id="minimum-rules-empty" class="minimum-empty"<?php echo array() === $rules ? '' : ' hidden'; ?>>
						<?php esc_html_e( 'No rules yet. Add your first quantity rule below.', 'plogins-minimum' ); ?>
					</p>
					<table class="widefat minimum-rules-table"<?php echo array() === $rules ? ' hidden' : ''; ?>>
						<caption class="minimum-rules-caption">
							<?php esc_html_e( 'Floor (min), ceiling (max) and step apply per scope. To target a single product or a category, paste its numeric ID, open the product or category in the editor and read the post or term ID from the URL (the post= or tag_ID= number).', 'plogins-minimum' ); ?>
						</caption>
						<thead>
							<tr>
								<th scope="col"><abbr title="<?php esc_attr_e( 'Which products this rule covers: every product, one product, or a whole category.', 'plogins-minimum' ); ?>"><?php esc_html_e( 'Scope', 'plogins-minimum' ); ?></abbr></th>
								<th scope="col"><abbr title="<?php esc_attr_e( 'The numeric ID of the product or category this rule targets. Ignored for the global scope.', 'plogins-minimum' ); ?>"><?php esc_html_e( 'Product / Category ID', 'plogins-minimum' ); ?></abbr></th>
								<th scope="col"><abbr title="<?php esc_attr_e( 'Floor: customers must buy at least this many. 0 sets no floor.', 'plogins-minimum' ); ?>"><?php esc_html_e( 'Min', 'plogins-minimum' ); ?></abbr></th>
								<th scope="col"><abbr title="<?php esc_attr_e( 'Ceiling: customers can buy at most this many. 0 sets no ceiling.', 'plogins-minimum' ); ?>"><?php esc_html_e( 'Max', 'plogins-minimum' ); ?></abbr></th>
								<th scope="col"><abbr title="<?php esc_attr_e( 'Quantity must be a multiple of this number, e.g. 6 forces 6, 12, 18. 0 or 1 allows any amount.', 'plogins-minimum' ); ?>"><?php esc_html_e( 'Step', 'plogins-minimum' ); ?></abbr></th>
								<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Actions', 'plogins-minimum' ); ?></span></th>
							</tr>
						</thead>
						<tbody id="minimum-rules-rows">
							<?php foreach ( $rules as $i => $rule ) : ?>
								<?php $this->render_rule_row( $name, (int) $i, $rule ); ?>
							<?php endforeach; ?>
						</tbody>
					</table>
					<p>
						<button type="button" id="minimum-add-rule" class="button">
							<?php esc_html_e( '+ Add rule', 'plogins-minimum' ); ?>
						</button>
					</p>
				</div>

				<h2 class="minimum-section-title"><?php esc_html_e( 'Notice messages', 'plogins-minimum' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'Shown to a customer when a rule is not met. Each {token} is swapped for the live value at checkout, so the message names the exact product and number.', 'plogins-minimum' ); ?>
				</p>
				<p class="description minimum-token-example">
					<?php
					printf(
						/* translators: 1: template with tokens, 2: the same message after tokens are filled in. */
						esc_html__( 'Example: %1$s becomes %2$s', 'plogins-minimum' ),
						'<code>' . esc_html__( 'You must buy at least {min} of "{product}".', 'plogins-minimum' ) . '</code>',
						'<code class="minimum-token-rendered">' . esc_html__( 'You must buy at least 6 of "Espresso Beans".', 'plogins-minimum' ) . '</code>',
					);
					?>
				</p>

				<table class="form-table" role="presentation">
					<tbody>
						<?php
						$this->render_message_field( $name, 'msg_min_qty', __( 'Below minimum quantity', 'plogins-minimum' ), '{min}, {product}' );
						$this->render_message_field( $name, 'msg_max_qty', __( 'Above maximum quantity', 'plogins-minimum' ), '{max}, {product}' );
						$this->render_message_field( $name, 'msg_step_qty', __( 'Invalid step quantity', 'plogins-minimum' ), '{step}, {product}' );
						$this->render_message_field( $name, 'msg_min_total', __( 'Below minimum order total', 'plogins-minimum' ), '{min}, {total}' );
						?>
					</tbody>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render one rule row.
	 *
	 * @param string                                                           $name Option name.
	 * @param int                                                              $i    Row index.
	 * @param array{scope: string, target: int, min: int, max: int, step: int} $rule Rule data.
	 */
	private function render_rule_row( string $name, int $i, array $rule ): void {
		$is_global = 'global' === $rule['scope'];
		?>
		<tr class="minimum-rule-row">
			<td>
				<select
					name="<?php echo esc_attr( $name ); ?>[rules][<?php echo esc_attr( (string) $i ); ?>][scope]"
					class="minimum-rule-scope"
					aria-label="<?php esc_attr_e( 'Rule scope', 'plogins-minimum' ); ?>"
				>
					<?php foreach ( $this->scope_choices() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $rule['scope'], $value ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<input
					type="number"
					name="<?php echo esc_attr( $name ); ?>[rules][<?php echo esc_attr( (string) $i ); ?>][target]"
					value="<?php echo esc_attr( (string) $rule['target'] ); ?>"
					min="0"
					step="1"
					class="small-text minimum-rule-target"
					aria-label="<?php esc_attr_e( 'Target product or category ID', 'plogins-minimum' ); ?>"
					<?php disabled( $is_global, true ); ?>
				/>
			</td>
			<td>
				<input type="number" min="0" step="1" class="small-text"
					name="<?php echo esc_attr( $name ); ?>[rules][<?php echo esc_attr( (string) $i ); ?>][min]"
					value="<?php echo esc_attr( (string) $rule['min'] ); ?>"
					aria-label="<?php esc_attr_e( 'Minimum quantity', 'plogins-minimum' ); ?>" />
			</td>
			<td>
				<input type="number" min="0" step="1" class="small-text"
					name="<?php echo esc_attr( $name ); ?>[rules][<?php echo esc_attr( (string) $i ); ?>][max]"
					value="<?php echo esc_attr( (string) $rule['max'] ); ?>"
					aria-label="<?php esc_attr_e( 'Maximum quantity', 'plogins-minimum' ); ?>" />
			</td>
			<td>
				<input type="number" min="0" step="1" class="small-text"
					name="<?php echo esc_attr( $name ); ?>[rules][<?php echo esc_attr( (string) $i ); ?>][step]"
					value="<?php echo esc_attr( (string) $rule['step'] ); ?>"
					aria-label="<?php esc_attr_e( 'Step quantity', 'plogins-minimum' ); ?>" />
			</td>
			<td>
				<button type="button" class="button minimum-remove-rule">
					<?php esc_html_e( 'Remove', 'plogins-minimum' ); ?>
				</button>
			</td>
		</tr>
		<?php
	}

	/**
	 * Render a single message text field row.
	 *
	 * @param string $name   Option name.
	 * @param string $key    Message key.
	 * @param string $label  Field label.
	 * @param string $tokens Comma-separated available tokens.
	 */
	private function render_message_field( string $name, string $key, string $label, string $tokens ): void {
		$value = $this->settings->message( $key );
		?>
		<tr>
			<th scope="row">
				<label for="minimum_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			</th>
			<td>
				<input
					type="text"
					id="minimum_<?php echo esc_attr( $key ); ?>"
					name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $key ); ?>]"
					value="<?php echo esc_attr( $value ); ?>"
					class="large-text"
				/>
				<p class="description">
					<?php
					printf(
						/* translators: %s: list of available replacement tokens. */
						esc_html__( 'Available tokens: %s', 'plogins-minimum' ),
						'<code>' . esc_html( $tokens ) . '</code>',
					);
					?>
				</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Currency symbol for display next to the order-total field.
	 */
	private function currency_symbol(): string {
		if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
			return html_entity_decode( get_woocommerce_currency_symbol() );
		}

		return '';
	}
}
