<?php declare(strict_types = 1);

namespace Utilitte\Association;

use ArrayAccess;
use Countable;
use LogicException;
use OutOfBoundsException;

/**
 * @template T
 * @implements ArrayAccess<string|int|object, array<T>>
 */
final class Associations implements ArrayAccess, Countable
{

	/**
	 * @param array<mixed, T[]> $association
	 */
	public function __construct(
		protected array $association,
		protected ?ObjectAdapter $objectAdapter = null,
	)
	{
	}

	/**
	 * @param T[]|null $default
	 * @return T[]
	 */
	public function get(string|int|object $offset, ?array $default = null): mixed
	{
		if ($this->offsetExists($offset)) {
			return $this->offsetGet($offset);
		}

		if ($default !== null) {
			return $default;
		}

		return $this->offsetGet($offset);
	}

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->association[$this->getKey($offset)]);
	}

	/**
	 * @return T[]
	 */
	public function offsetGet(mixed $offset): mixed
	{
		return $this->association[$this->getKey($offset)]
			   ??
			   throw new OutOfBoundsException(
				   sprintf('Association with key %s does not exist.', $this->getKey($offset))
			   );
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
