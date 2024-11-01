<?php

use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\App\Extend\ExtendCart;
use TaxDo\WooCommerce\App\RemoveCertificate;
use TaxDo\WooCommerce\App\AssignProductTaxClass;
use TaxDo\WooCommerce\Infra\TaxDo\ClientFactory;
use TaxDo\WooCommerce\App\Extend\ExtendCartItem;
use TaxDo\WooCommerce\App\PreProcess\PreProcess;
use TaxDo\WooCommerce\App\Invoice\CreateInvoice;
use TaxDo\WooCommerce\App\Legacy\ValidateZipCode;
use TaxDo\WooCommerce\Infra\Listener\OrderListener;
use TaxDo\WooCommerce\Infra\Listener\ExtendListener;
use TaxDo\WooCommerce\Infra\Listener\ProductListener;
use TaxDo\WooCommerce\Infra\Listener\RestApiListener;
use TaxDo\WooCommerce\App\Legacy\UpdateBillingAddress;
use TaxDo\WooCommerce\Infra\TaxDo\Client as TaxDoClient;
use TaxDo\WooCommerce\Infra\Listener\CalcTotalListener;
use TaxDo\WooCommerce\Infra\Repository\ProductRepository;
use TaxDo\WooCommerce\Infra\Listener\IntegrationListener;
use TaxDo\WooCommerce\Infra\Repository\TaxClassRepository;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\Infra\Repository\CustomerRepository;
use TaxDo\WooCommerce\Views\TaxClassesSelectInputPresenter;
use TaxDo\WooCommerce\Infra\Listener\Legacy\AddressListener;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;
use TaxDo\WooCommerce\Infra\Listener\Legacy\CartItemListener;
use TaxDo\WooCommerce\Infra\Listener\BlockRegistrationListener;

use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClass\OutputPort;
use TaxDo\WooCommerce\Infra\Listener\Legacy\CertificateListener;
use TaxDo\WooCommerce\Domain\SubTaxClass\Legacy\Service\SubTaxClassRepository;
use TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs\CertificateCodeInputOutputPort;
use TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs\UploadCertificateInputOutputPort;
use TaxDo\WooCommerce\Infra\UseCase\Legacy\LoadProductTaxClass\CartItemTaxClassPresenter;
use TaxDo\WooCommerce\Infra\Repository\Legacy\SubTaxClass\SubTaxClassRepositoryUsingCache;
use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses\SubTaxClassesOutputPort;
use TaxDo\WooCommerce\Infra\UseCase\Legacy\ShowCertificateInputs\CertificateCodeInputPresenter;
use TaxDo\WooCommerce\Infra\UseCase\Legacy\ShowCertificateInputs\UploadCertificateInputPresenter;
use TaxDo\WooCommerce\Infra\UseCase\Legacy\LoadProductTaxClassAndSubTaxClasses\SubTaxClassesPresenter;
use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses\LoadProductTaxClassAndSubTaxClasses;



defined('ABSPATH') || exit('NO ACCESS!');

return [
	'data' => [
		'tax_do_base_url' => 'https://api.taxdo.co',
		'sandbox_tax_do_base_url' => 'https://api-sandbox.taxdo.co',
		'hook_listeners' => [
			IntegrationListener::class,
			ProductListener::class,
			BlockRegistrationListener::class,
			ExtendListener::class,
			RestApiListener::class,
			CalcTotalListener::class,
			OrderListener::class,

			// Legacy
			CartItemListener::class,
			CertificateListener::class,
			AddressListener::class
		],
		'ajax_actions'    => [ 'taxdo_apply_certificate'],
	],
	'serviceRegistry' => [
		'binding' => [
			Setting::class => Setting::class,
			ClientFactory::class => ClientFactory::class,
			TaxDoClient::class => ['factory' => ClientFactory::class],
			AssignProductTaxClass::class => AssignProductTaxClass::class,
			TaxClassesSelectInputPresenter::class => TaxClassesSelectInputPresenter::class,
			TaxClassRepository::class => TaxClassRepository::class,
			ExtendCartItem::class => ExtendCartItem::class,
			ExtendCart::class => ExtendCart::class,
			ProductRepository::class => ProductRepository::class,
			CartItemRepository::class => CartItemRepository::class,
			CustomerRepository::class => CustomerRepository::class,
			CertificateRepository::class => CertificateRepository::class,
			PreProcess::class => PreProcess::class,
			CreateInvoice::class => CreateInvoice::class,
			RemoveCertificate::class => RemoveCertificate::class,

			// Legacy
			LoadProductTaxClassAndSubTaxClasses::class => LoadProductTaxClassAndSubTaxClasses::class,
			OutputPort::class => CartItemTaxClassPresenter::class,
			SubTaxClassesOutputPort::class => SubTaxClassesPresenter::class,
			SubTaxClassRepository::class => SubTaxClassRepositoryUsingCache::class,
			CertificateCodeInputOutputPort::class => CertificateCodeInputPresenter::class,
			UploadCertificateInputOutputPort::class => UploadCertificateInputPresenter::class,
			UpdateBillingAddress::class => UpdateBillingAddress::class,
			ValidateZipCode::class => ValidateZipCode::class
		]
	],
];
