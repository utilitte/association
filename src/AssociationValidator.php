<?php declare(strict_types = 1);

namespace Utilitte\Association;

use UnexpectedValueException;

final class AssociationValidator
{

	/** @var callable(mixed): bool */
	private $validator;

	/**
	 * @param callable(mixed): bool $validator
	 */
	public function __construct(
		private string $type,
		callable $validator,
	)
	{
		$this->validator = $validator;
	}

	public function validate(mixed $value): void
	{
		if (!($this->validator)($value)) {
			throw new UnexpectedValueException(
				sprintf('Value must by type of %s, %s given.', $this->type, get_debug_type($value))
			);
		}
	}

}
