<?php

declare(strict_types=1);

namespace Jimbo2150\PhpUtilities\Tests;

use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TestTraitBase;
use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TestTraitLayer1_1;
use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TestTraitLayer1_2;
use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TestTraitLayer2_1;
use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TestTraitLayer2_Base;
use Jimbo2150\PhpUtilities\Tests\Mocks\Trait\TraitObjectInherited2;
use Jimbo2150\PhpUtilities\Traits;
use PHPUnit\Framework\TestCase;

class TraitsTest extends TestCase
{
	public function testIsInstanceOf()
	{
		$traitObjectInherited2 = new TraitObjectInherited2();

		$this->assertTrue(
			Traits::instanceOf(
				$traitObjectInherited2,
				TestTraitBase::class
			)
		);

		$this->assertTrue(
			Traits::instanceOf(
				$traitObjectInherited2,
				TestTraitLayer1_1::class
			)
		);

		$this->assertTrue(
			Traits::instanceOf(
				$traitObjectInherited2,
				TestTraitLayer1_2::class
			)
		);

		$this->assertTrue(
			Traits::instanceOf(
				$traitObjectInherited2,
				TestTraitLayer2_Base::class
			)
		);

		$this->assertTrue(
			Traits::instanceOf(
				$traitObjectInherited2,
				TestTraitLayer2_1::class
			)
		);
	}
}
