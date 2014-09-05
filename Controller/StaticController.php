<?php
/**
 * Copyright 2014 Arnaud Bienvenu
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

namespace Abienvenu\KyelaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Yaml\Yaml;

/**
 * Static content controller.
 *
 * @Route("/")
 */
class StaticController extends Controller
{
	protected function loadTranslations($domain, $locale)
	{
		$r = new \ReflectionClass($this);
		$dirName = dirname($r->getFilename());
		$translations = [];
		foreach (["$domain.$locale.yml", "$domain-me.$locale.yml"] as $fileName)
		{
			$fullFileName = "$dirName/../Resources/translations/$fileName";
			if (file_exists($fullFileName))
			{
				$parsed = Yaml::parse(file_get_contents($fullFileName));
			}
			$translations += $parsed;
		}
    	return $translations;
	}

    /**
     * Displays the FAQ
     *
     * @Route("/faq", name="faq")
     * @Method("GET")
     * @Template()
     */
    public function faqAction()
    {
    	return ["faq" => $this->loadTranslations("faq", "fr")];
    }

    /**
     * Displays the About page
     *
     * @Route("/about", name="about")
     * @Method("GET")
     * @Template()
     */
    public function aboutAction()
    {
    	return ["about" => $this->loadTranslations("about", "fr")];
    }
}
