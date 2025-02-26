<?php

declare(strict_types=1);

namespace Jimbo2150\PhpUtilities;

/**
 * Trait Traitable.
 *
 * This trait provides a simple method for checking if a class or its ancestors
 * use a specific trait.
 */
trait Traitable
{
	/**
	 * Checks if the current object (or one of its ancestors) uses the specified trait.
	 *
	 * This method provides a convenient way to determine if the current object
	 * utilizes a given trait, including checking the object's parent classes and
	 * the traits used by them.
	 *
	 * @param string $trait the fully qualified name of the trait to check for
	 *
	 * @return bool true if the object uses the trait, false otherwise
	 *
	 * @throws \InvalidArgumentException if the provided $trait argument is not a trait
	 */
	public function hasTrait(string $trait): bool
	{
		return Traits::instanceOf($this, $trait);
	}
}
