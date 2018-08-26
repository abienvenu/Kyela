<?php
/*
 * Copyright 2014-2016 Arnaud Bienvenu
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Abienvenu\KyelaBundle\Entity\Comment;
use Abienvenu\KyelaBundle\Form\Type\CommentType;

/**
 * Comment controller.
 *
 * @Route("/{pollUrl}/comment")
 */
class CommentController extends CRUDController
{
    protected $entityName = 'KyelaBundle:Comment';
    protected $cancelRoute = 'poll_show';
    protected $successRoute = 'poll_show';
    protected $deleteRoute = 'comment_delete';
    protected $deleteSuccessRoute = 'poll_show';

    /**
     * Displays latest comments
     *
     * @Route("/", methods="GET")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('KyelaBundle:Comment')->getLatestComments($this->poll);
        return ['poll' => $this->poll, 'comments' => $comments];
    }

    /**
     * Displays a form to create a new Comment entity.
     *
     * @Route("/new", name="comment_new", methods={"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $comment = new Comment();
        if ($request->isMethod('POST'))
        {
            $comment->setDatetime(new \DateTime());
        }
        return $this->doNewAction(CommentType::class, $comment, $request, null, ['authors' => $this->poll->getParticipantsAsArrayOfNames()]);
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit", methods={"GET", "PUT"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEditAction(CommentType::class, $id, $request, ['authors' => $this->poll->getParticipantsAsArrayOfNames()]);
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}", name="comment_delete", methods="DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDeleteAction($request, $id);
    }
}
