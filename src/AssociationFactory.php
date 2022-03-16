<?php declare(strict_types = 1);

namespace Utilitte\Association;

final class AssociationFactory
{

	public function __construct(
		private ?ObjectAdapter $objectAdapter = null,
	)
	{
	}

	/**
	 * @template T
	 * @param array<string|int|object, T> $values
	 * @return Association<T>
	 */
	public function create(array $values): Association
	{
		return new Association($values, $this->objectAdapter);
	}

	/**
	 * @param array<int|string|object, bool> $values
	 * @return Association<bool>
	 */
	public function createBool(array $values): Association
	{
		$validator = new AssociationValidator('bool', static fn (mixed $value) => is_bool($value));

		return new Association($values, $this->objectAdapter, $validator);
	}

	/**
	 * @param array<int|string|object, string> $values
	 * @return Association<string>
	 */
	public function createString(array $values): Association
	{
		$validator = new AssociationValidator('string', static fn (mixed $value) => is_string($value));

		return new Association($values, $this->objectAdapter, $validator);
	}

	/**
	 * @param array<int|string|object, float> $values
	 * @return Association<float>
	 */
	public function createFloat(array $values): Association
	{
		$validator = new AssociationValidator('float', static fn (mixed $value) => is_float($value));

		return new Association($values, $this->objectAdapter, $validator);
	}

	/**
	 * @param array<int|string|object, mixed> $values
	 * @return Association<mixed>
	 */
	public function createMixed(array $values): Association
	{
		return new Association($values, $this->objectAdapter);
	}

	/**
	 * @template T of object
	 * @param class-string<T> $instance
	 * @param array<int|string|object, T> $values
	 * @return Association<T>
	 */
	public function createObject(string $instance, array $values): Association
	{
		$validator = new AssociationValidator($instance, static fn (mixed $value) => $value instanceof $instance);

		return new Association($values, $this->objectAdapter, $validator);
	}

	/**
	 * @template T of object
	 * @param array<int|string|object, T[]> $values
	 * @return Associations<T>
	 */
	public function createArrayOfObjects(array $values): Associations
	{
		return new Associations($values, $this->objectAdapter);
	}

	/**
	 * @template T
	 * @param array<int|string|object, T[]> $values
	 * @return Associations<T>
	 */
	public function createArray(array $values): Associations
	{
		return new Associations($values, $this->objectAdapter);
	}

}
