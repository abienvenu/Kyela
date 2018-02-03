<?php
/*
 * Copyright 2018 Arnaud Bienvenu
 *
 * This file is part of Kyela.

 * Kyela is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Kyela is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with Kyela.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Abienvenu\KyelaBundle\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;
use Behatch\Context\BaseContext;

class KyelaContext extends BaseContext
{
	/** @var MinkContext $minkContext */
	protected $minkContext;

	/**
	 * @BeforeScenario
	 */
	public function gatherContexts(BeforeScenarioScope $scope)
	{
		/** @var InitializedContextEnvironment $environment */
		$environment = $scope->getEnvironment();
		$this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');

		if ($this->minkContext->getSession()->getDriver() instanceof Selenium2Driver)
		{
			$this->minkContext->getSession()->getDriver()->resizeWindow(1440, 1800);
		}
	}

	/**
	 * @When I wait for the participation to update
	 */
	public function iWaitForTheParticipationToUpdate()
	{
		$this->minkContext->getSession()->wait(2000, "!$('.ajaxloader').is(':visible')");
	}

    /**
     * @Then the :colIndex column of the footer should contain :text
     */
	public function theColumnOfTheFooterInTheTableShouldContain($colIndex, $text)
    {
        $row = $this->minkContext->getSession()->getPage()->findAll('css', ".table tfoot tr th");
        if (!isset($row[$colIndex - 1]))
        {
            throw new \Exception("The col $colIndex was not found in the footer");
        }
        $actual = $row[$colIndex - 1]->getText();

        $this->assertContains($text, $actual);
    }
}
