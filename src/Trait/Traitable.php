<?php

declare(strict_types=1);

namespace Jimbo2150\PhpUtilities\Trait;

use Jimbo2150\PhpUtilities\Traits;

trait Traitable
{
	public function instanceOfTrait(object|string $trait): bool
	{
		return Traits::instanceOf($this, $trait);
	}
}
