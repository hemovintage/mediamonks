<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use MediaMonks\RestApi\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\BlogPost;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;

class ApiController extends Controller
{
	private $entityManager;
	
	private $blogPostRepository;

	/**
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
	    $this->entityManager = $entityManager;
	    $this->blogPostRepository = $entityManager->getRepository('App:BlogPost');
	}

    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

	/**
	 * @Route("/api/blogs", name="api_blogs")
	 */
	public function entriesAction()
	{
		$fields = array('bp.title','bp.body');
		$blogPost = $this->blogPostRepository->getAllPosts($fields,'array');

		return $blogPost;
	}

	/**
	 * @Route("/api/blogs/{id}", name="api_blogs_id")
	 */
	public function entryAction($id)
	{
		$blogPost = $this->blogPostRepository->getPostById($id,'array');

		return $blogPost;
	}

}
