<?php declare(strict_types = 1);

namespace Utilitte\Association;

use ArrayAccess;
use Countable;
use LogicException;
use OutOfBoundsException;

/**
 * @template T
 * @implements ArrayAccess<string|int|object, T>
 */
final class Association implements ArrayAccess, Countable
{

	/**
	 * @param array<mixed, T> $association
	 */
	public function __construct(
		private array $association,
		private ?ObjectAdapter $objectAdapter = null,
		private ?AssociationValidator $associationValidator = null,
	)
	{
	}

	/**
	 * @param T $default
	 * @return T
	 */
	public function get(string|int|object $offset, mixed $default = null): mixed
	{
		if ($this->offsetExists($offset)) {
			return $this->offsetGet($offset);
		}

		if (func_num_args() !== 1) {
			return $default;
		}

		return $this->offsetGet($offset);
	}

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->association[$this->getKey($offset)]);
	}

	/**
	 * @return T
	 */
	public function offsetGet(mixed $offset): mixed
	{
		$key = $this->getKey($offset);
		if (!isset($this->association[$key])) {
			throw new OutOfBoundsException(
				sprintf('Association with key %s does not exist.', $this->getKey($offset))
			);
		}

		$value = $this->association[$key];

		$this->associationValidator?->validate($value);

		return $value;
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		throw new LogicException('Cannot set association, use constructor instead.');
	}

	public function offsetUnset(mixed $offset): void
	{
		throw new LogicException('Cannot unset association.');
	}

	public function count(): int
	{
		return count($this->association);
	}

	protected function getKey(mixed $offset): string|int
	{
		if (is_string($offset) || is_int($offset)) {
			return $offset;
		}

		if (is_object($offset)) {
			if (!$this->objectAdapter) {
				throw new LogicException(
					sprintf('Cannot get offset from object %s, please set object adapter.', get_debug_type($offset))
				);
			}

			return $this->objectAdapter->getKey($offset);
		}

		throw new LogicException(sprintf('Cannot get offset from %s.', get_debug_type($offset)));
	}

}
