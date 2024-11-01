<?php


namespace TaxDo\WooCommerce\Infra\ServiceRegistry;


use ReflectionClass;
use RuntimeException;
use ReflectionException;
use InvalidArgumentException;

final class Container
{
	private static array $services = [];
	private static array $config = [];

	private function __construct()
	{
	}

	/**
	 * @throws ReflectionException
	 */
	public static function get_service(string $name)
	{
		if (!class_exists($name)) {
			throw new InvalidArgumentException();
		}

		if (!array_key_exists($name, self::$services)) {
			self::$services[$name] = self::initiate_service($name);
		}

		return self::$services[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws ReflectionException
	 */
	private static function initiate_service(string $name): mixed
	{
		if (
			array_key_exists($name, self::$config['serviceRegistry']['binding'])
			&& is_array(self::$config['serviceRegistry']['binding'][$name])
			&& array_key_exists('factory', self::$config['serviceRegistry']['binding'][$name])
		) {
			$factoryMethod = array_key_exists('method', self::$config['serviceRegistry']['binding'][$name])
				? self::$config['serviceRegistry']['binding'][$name]['method']
				: 'make';

			return self::initiate_service(self::$config['serviceRegistry']['binding'][$name]['factory'])->$factoryMethod();
		}

		if ((new ReflectionClass($name))->isInterface()) {
			if (!array_key_exists($name, self::$config['serviceRegistry']['binding'])) {
				throw new RuntimeException('No implementation found for interface: ' . $name);
			}
			$name = self::$config['serviceRegistry']['binding'][$name];
		}

		$constructor = (new ReflectionClass($name))->getConstructor();
		if (is_null($constructor)) {
			return new $name;
		}

		$dependencies = [];
		foreach ($constructor->getParameters() as $constructorParam) {
			$paramType = $constructorParam->getType();
			if (is_null($paramType)) {
				throw new RuntimeException();
			}

			$dependencies[] = !$paramType->isBuiltin()
				? self::initiate_service((string)$paramType)
				: self::get_config($constructorParam->getName());
		}

		return new $name(...$dependencies);
	}

	public static function get_config(string $key)
	{
		if (!array_key_exists($key, self::$config['data'])) {
			throw new InvalidArgumentException();
		}

		return self::$config['data'][$key];
	}

	public static function load_config(array $config): void
	{
		if (!array_key_exists('data', $config) || !array_key_exists('serviceRegistry', $config)) {
			throw new InvalidArgumentException();
		}
		self::$config = $config;
	}
}
