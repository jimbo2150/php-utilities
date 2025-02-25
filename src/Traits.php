<?php

declare(strict_types=1);

namespace Jimbo2150\PhpUtilities;

final class Traits
{
	/** @var array<string,array<int,string>> */
	private static array $traitHierarchy = [];

	private function __construct()
	{
	}

	public static function instanceOf(object $object, object|string $trait): bool
	{
		$objectReflection = new \ReflectionClass($object);
		$traitReflection = new \ReflectionClass($trait);

		if (false === $traitReflection->isTrait()) {
			throw new \InvalidArgumentException('$trait argument must be a trait.');
		}

		return self::isAncestryOrSelfInstanceOf(
			$objectReflection,
			$traitReflection,
			[self::class, 'isReflectedInstanceOf']
		);
	}

	private static function isReflectedInstanceOf(
		\ReflectionClass $reflection,
		\ReflectionClass $traitReflection,
	): bool {
		$traits = self::getTraitsRecursive($reflection);

		return isset($traits[$traitReflection->getName()]);
	}

	private static function isAncestryOrSelfInstanceOf(
		\ReflectionClass $reflection,
		\ReflectionClass $traitReflection,
		callable $callback,
	): bool {
		if ($callback($reflection, $traitReflection)) {
			return true;
		}
		while ($ancestor = $reflection->getParentClass()) {
			if ($callback($ancestor, $traitReflection)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	private static function getTraitsRecursive(
		\ReflectionClass $reflection,
	): array {
		$traits = $reflection->getTraits();

		foreach ($traits as $trait) {
			if (isset(self::$traitHierarchy[$trait->getName()])) {
				$traits = array_merge(
					$traits,
					array_flip(self::$traitHierarchy[$trait->getName()])
				);
				continue;
			}
			$theseTraits = self::getTraitsRecursive($trait);
			$newHierarchy = array_flip(
				array_map(
					fn (\ReflectionClass $ref) => $ref->getName(),
					$theseTraits
				)
			);
			self::$traitHierarchy[$trait->getName()] = array_combine(
				$newHierarchy,
				array_fill(0, count($newHierarchy), null)
			);
			$traits = array_merge($traits, $theseTraits);
		}

		if ($parent = $reflection->getParentClass()) {
			$traits = array_merge($traits, self::getTraitsRecursive($parent));
		}

		return $traits;
	}
}
