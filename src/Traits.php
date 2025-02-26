<?php

declare(strict_types=1);

namespace Jimbo2150\PhpUtilities;

/**
 * Utility class for working with PHP traits.
 *
 * This class provides methods for determining if an object uses a specific trait,
 * including recursively checking traits used by other traits and inherited classes.
 */
final class Traits
{
	/**
	 * @var array<string, string[]>
	 *
	 * A cache of trait hierarchies. The keys are trait names, and the values are arrays
	 * of sub-trait names.
	 * This map stores the complete composition of each trait, including its dependencies.
	 */
	private static array $traitHierarchy = [];

	/**
	 * Private constructor to prevent instantiation.
	 *
	 * This class is intended to be used statically.
	 */
	private function __construct()
	{
	}

	/**
	 * Checks if an object or one of its ancestors uses a specific trait.
	 *
	 * This method recursively checks the object, its parent classes, and the traits
	 * used by each of them to determine if the given trait is present.
	 *
	 * @param object $object the object to check
	 * @param string $trait  the trait's class name
	 *
	 * @return bool true if the object (or one of its ancestors) uses the trait, false otherwise
	 *
	 * @throws \InvalidArgumentException if the $trait argument is not a trait
	 */
	public static function instanceOf(object $object, string $trait): bool
	{
		$traitReflection = new \ReflectionClass($trait);

		if (false === $traitReflection->isTrait()) {
			throw new \InvalidArgumentException('$trait argument must be a trait.');
		}

		$objectReflection = new \ReflectionClass($object);

		return self::isAncestryOrSelfInstanceOf(
			$objectReflection,
			$trait
		);
	}

	/**
	 * Checks if a reflection class or one of its ancestors directly uses a specific trait.
	 *
	 * @param \ReflectionClass $reflection the reflection class to check
	 * @param string           $traitName  the trait class name to check for
	 *
	 * @return bool true if the reflection class directly uses the trait, false otherwise
	 */
	private static function isReflectedInstanceOf(
		\ReflectionClass $reflection,
		string $traitName,
	): bool {
		$allTraits = self::getTraitsRecursive($reflection);

		return isset($allTraits[$traitName]);
	}

	/**
	 * Recursively checks if a reflection class or one of its ancestors uses a specific trait.
	 *
	 * @param \ReflectionClass $reflection the reflection class to check
	 * @param string           $traitName  the trait class name to check for
	 *
	 * @return bool true if the reflection class or an ancestor uses the trait, false otherwise
	 */
	private static function isAncestryOrSelfInstanceOf(
		\ReflectionClass $reflection,
		string $traitName,
	): bool {
		if (self::isReflectedInstanceOf($reflection, $traitName)) {
			return true;
		}
		while ($ancestor = $reflection->getParentClass()) {
			if (self::isReflectedInstanceOf($ancestor, $traitName)) {
				return true;
			}
			$reflection = $ancestor;
		}

		return false;
	}

	/**
	 * Recursively retrieves all traits used by a reflection class, including traits used by other traits.
	 *
	 * This method explores the entire trait hierarchy, building a map of trait composition
	 * and caching the results to avoid redundant computations and prevent infinite loops
	 * in cases of circular dependencies.
	 *
	 * @param \ReflectionClass    $reflection    the reflection class to examine
	 * @param array<string, bool> $visitedTraits an array of already visited traits (used internally for recursion)
	 *
	 * @return array<string, bool> an associative array where the keys are the names of the traits and the values are always true
	 */
	private static function getTraitsRecursive(
		\ReflectionClass $reflection,
		array $visitedTraits = [],
	): array {
		$className = $reflection->getName();
		$traits = [];

		// If the current reflection is a trait, add it to the list of traits.
		if ($reflection->isTrait()) {
			$traits[$className] = true;
		}

		// Get the traits directly used by this reflection class.
		foreach ($reflection->getTraits() as $trait) {
			$traitName = $trait->getName();

			// If we've already visited this trait in this recursive call, skip it to avoid loops.
			if (isset($visitedTraits[$traitName])) {
				continue;
			}
			// Mark this trait as visited for the current recursive call.
			$visitedTraits[$traitName] = true;

			// If we haven't yet built the hierarchy for this trait, do so.
			if (!isset(self::$traitHierarchy[$traitName])) {
				self::$traitHierarchy[$traitName] = array_keys(self::getTraitsRecursive($trait, []));
			}

			foreach (self::$traitHierarchy[$traitName] as $dependency) {
				$traits[$dependency] = true;
			}
			$traits[$traitName] = true;
		}

		// If the reflection class has a parent class, recursively get the parent's traits and merge them.
		if ($parent = $reflection->getParentClass()) {
			foreach (self::getTraitsRecursive($parent, $visitedTraits) as $key => $value) {
				$traits[$key] = $value;
			}
		}

		return $traits;
	}
}
