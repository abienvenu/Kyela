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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Abienvenu\KyelaBundle\Form\ContactType;

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
     * @Method("GET")
     * @Template()
     */
    public function aboutAction()
    {
    	return ["about" => $this->loadTranslations("about", "fr")];
    }

    /**
     * Display the Contact form
     *
     * @Template()
     */
    public function contactAction(Request $request)
    {
    	$form = $this->createForm(new ContactType());
        $form->add('actions', 'form_actions', [
        	'buttons' => [
        		'send' => ['type' => 'submit', 'options' => ['label' => 'send']],
        	]
        ]);

    	if ($request->isMethod('POST')) {
    		$form->bind($request);

	        if ($form->isValid()) {
	            $message = \Swift_Message::newInstance()
	                ->setSubject($form->get('subject')->getData())
	                ->setFrom($form->get('email')->getData())
	                ->setTo($this->container->getParameter('kyela_contact_email'))
	                ->setBody(
	                    $this->renderView(
	                        'KyelaBundle:Mail:contact.html.twig',
	                        array(
	                            'ip' => $request->getClientIp(),
	                            'name' => $form->get('name')->getData(),
	                        	'subject' => $form->get('subject')->getData(),
	                            'message' => $form->get('message')->getData()
	                        )
	                    )
	                );

	            $this->get('mailer')->send($message);
	            $request->getSession()->getFlashBag()->add('success', 'mail.sent');
	            return $this->redirect($this->generateUrl('poll_new'));
	        }
	    }
	    return array(
	        'form' => $form->createView()
	    );
    }
}
