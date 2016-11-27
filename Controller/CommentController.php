<?php
/*
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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Comment;
use Abienvenu\KyelaBundle\Form\Type\CommentType;
use Abienvenu\KyelaBundle\Traits\CRUDTrait;

/**
 * Comment controller.
 *
 * @Route("/{pollUrl}/comment")
 */
class CommentController extends Controller
{
    use CRUDTrait;

    protected $entityName = 'KyelaBundle:Comment';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'comment_delete';
    protected $deleteSuccessRoute = 'poll_show';

    /**
     * Displays a form to create a new Comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $comment = new Comment();
        if ($request->isMethod('POST'))
        {
            $comment->setDatetime(new \DateTime());
        }
        return $this->doNewAction(new CommentType($this->poll->getParticipantsAsArrayOfNames()), $comment, $request);
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEditAction(new CommentType($this->poll->getParticipantsAsArrayOfNames()), $id, $request);
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDeleteAction($request, $id);
    }
}
