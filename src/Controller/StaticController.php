<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Yaml\Yaml;

class StaticController extends AbstractController
{
	public function __construct(protected ParameterBagInterface $bag)
	{
	}

	/**
	 * Load content in YML format
	 */
	protected function loadYml(string $domain, string $locale): mixed
	{
		$root = $this->bag->get('kernel.project_dir');

		return Yaml::parseFile("$root/translations/$domain.$locale.yml");
	}

	/**
	 * Displays the FAQ
	 */
	#[Route('/faq', priority: 10)]
	public function faq(Request $request): Response
	{
		return $this->render('static/faq.html.twig', ['faq' => $this->loadYml('faq', $request->getLocale())]);
	}

	/**
	 * Displays the About page
	 */
	#[Route('/about', priority: 10)]
	public function about(Request $request): Response
	{
		return $this->render('static/about.html.twig', ['about' => $this->loadYml('about', $request->getLocale())]);
	}

	/**
	 * Displays the Tips page
	 */
	#[Route('/tips', priority: 10)]
	public function tips(Request $request): Response
	{
		return $this->render('static/tips.html.twig', ['tips' => $this->loadYml('tips', $request->getLocale())]);
	}

	/**
	 * Displays the Thanks page
	 */
	#[Route('/thanks', priority: 10)]
	public function thanks(): Response
	{
		return $this->render('static/thanks.html.twig');
	}
}
