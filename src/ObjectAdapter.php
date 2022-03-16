<?php declare(strict_types = 1);

namespace Utilitte\Association;

interface ObjectAdapter
{

	public function getKey(object $object): string|int;

}
