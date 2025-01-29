<?php
namespace App\Controller;

use App\Entity\Poll;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class PollSetterController extends AbstractController
{
	public function __construct(protected EntityManagerInterface $em)
	{
	}

    protected ?Poll $poll;

    /**
     * Set poll from Url or session
     */
    public function setPollFromRequest(Request $request): void
    {
        $pollUrl = $request->get('pollUrl');
        if ($pollUrl) {
            $request->getSession()->set('pollUrl', $pollUrl);
        }
        else {
            $pollUrl = $request->getSession()->get('pollUrl');
        }
        if ($pollUrl) {
            $this->poll = $this->em->getRepository(Poll::class)->findOneBy(['url' => $pollUrl]);
            if (!$this->poll)
            {
                $this->unsetPoll($request);
                throw new NotFoundHttpException('Poll object not found.');
            }
        }
    }

    protected function unsetPoll(Request $request): void
    {
        $this->poll = null;
        $request->getSession()->remove('pollUrl');
    }
}
