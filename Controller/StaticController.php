<?php
/*
 * Copyright 2014-2018 Arnaud Bienvenu
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

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Yaml\Yaml;

/**
 * Static content controller.
 *
 * @Route("/")
 */
class StaticController extends PollSetterController
{
    /**
     * Load additionnal translations
     *
     * @param string $domain
     * @param string $locale
     * @return array
     */
    protected function loadTranslations($domain, $locale)
    {
        $r = new \ReflectionClass($this);
        $dirName = dirname($r->getFileName());
        $fullFileName = "$dirName/../Resources/translations/$domain.$locale.yml";
        return Yaml::parse(file_get_contents($fullFileName));
    }

    /**
     * Displays the FAQ
     *
     * @Route("/faq", name="kyela_faq", methods="GET")
     * @Template()
     */
    public function faqAction(Request $request)
    {
        return ["poll" => $this->poll, "faq" => $this->loadTranslations("faq", $request->getLocale())];
    }

    /**
     * Displays the About page
     *
     * @Route("/about", name="kyela_about", methods="GET")
     * @Template()
     */
    public function aboutAction(Request $request)
    {
        return ["poll" => $this->poll, "about" => $this->loadTranslations("about", $request->getLocale())];
    }

    /**
     * Displays the Tips page
     *
     * @Route("/tips", name="kyela_tips", methods="GET")
     * @Template()
     */
    public function tipsAction(Request $request)
    {
        return ["poll" => $this->poll, "tips" => $this->loadTranslations("tips", $request->getLocale())];
    }

    /**
     * Displays the Thanks page
     *
     * @Route("/thanks", name="kyela_thanks", methods="GET")
     * @Template()
     */
    public function thanksAction()
    {
        return ["poll" => $this->poll];
    }

    /**
     * Switch locale
     *
     * @Route("/switch/{_locale}", name="kyela_switch", methods="GET")
     */
    public function switchAction(Request $request)
    {
        if ($request->headers->has('referer'))
        {
            $returnUrl = $request->headers->get('referer');
        }
        else
        {
            $returnUrl = $this->generateUrl("poll_new");
        }
        return new RedirectResponse($returnUrl, 302);
    }
}
