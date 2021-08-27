<?php

namespace App\Controller\NewsStatus;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class PublishNews extends AbstractController
{
    public function __invoke(News $data, Registry $registry): News
    {
        $stateMachine = $registry->get($data, 'news_publication_status');

        try {
            $stateMachine->apply($data, 'publish');
        } catch (LogicException $exception) {
        }

        return $data;
    }
}
