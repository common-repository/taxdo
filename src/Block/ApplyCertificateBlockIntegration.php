<?php

namespace TaxDo\WooCommerce\Block;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class ApplyCertificateBlockIntegration implements IntegrationInterface
{

	public function get_name()
	{
		return 'taxdo/apply_certificate';
	}

	public function initialize()
	{
		$this->register_block_frontend_scripts();
		$this->register_block_editor_scripts();
		$this->register_block_editor_styles();
		$this->register_main_integration();
	}

	private function register_block_frontend_scripts()
	{
		$script_path = 'build/apply-certificate/frontend.js';
		$script_url = TAX_DO_URL . $script_path;
		$script_asset_path = TAX_DO_PATH . 'build/apply-certificate/frontend.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_asset_path),
			];

		wp_register_script(
			'apply-certificate-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
	}

	private function get_file_version($file)
	{
		if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG && file_exists($file)) {
			return filemtime($file);
		}
		return TAX_DO_VERSION;
	}

	private function register_block_editor_scripts()
	{
		$script_path = 'build/apply-certificate/index.js';
		$script_url = TAX_DO_URL . $script_path;

		$script_asset_path = TAX_DO_PATH . 'build/apply-certificate/index.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_asset_path),
			];

		wp_register_script(
			'apply-certificate-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
	}

	private function register_block_editor_styles()
	{
		$style_path = 'build/apply-certificate/style-index.css';

		$style_url = TAX_DO_URL . $style_path;
		wp_enqueue_style(
			'apply-certificate',
			$style_url,
			[],
			$this->get_file_version($style_path)
		);
	}

	private function register_main_integration()
	{
		$script_path = 'build/index.js';

		$script_url = TAX_DO_URL . $script_path;

		$script_asset_path = TAX_DO_PATH . 'build/index.asset.php';
		$script_asset = file_exists($script_asset_path)
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version' => $this->get_file_version($script_path),
			];

//		wp_enqueue_style(
//			'apply-certificate-blocks-integration',
//			$style_url,
//			[],
//			$this->get_file_version( $style_path )
//		);

		wp_register_script(
			'apply-certificate-blocks-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
	}

	public function get_script_handles()
	{
		return ['apply-certificate-blocks-integration', 'apply-certificate-block-frontend'];
	}

	public function get_editor_script_handles()
	{
		return ['apply-certificate-blocks-integration', 'apply-certificate-block-editor'];
	}

	public function get_script_data()
	{
		return [];
	}
}
