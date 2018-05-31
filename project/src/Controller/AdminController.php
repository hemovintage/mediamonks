<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Entity\BlogPost;
use App\Form\EntryFormType;

class AdminController extends Controller
{
	private $entityManager;

	private $authorRepository;

	private $blogPostRepository;

	/**
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
	    $this->entityManager = $entityManager;
	    $this->blogPostRepository = $entityManager->getRepository('App:BlogPost');
	    $this->authorRepository = $entityManager->getRepository('App:Author');
	}

	/* ENTRIES */
	/* ******* */

	/**
	 * @Route("/admin/", name="admin_index")
	 * @Route("/admin/entries", name="admin_entries")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function entriesAction()
	{
	    $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

	    $blogPosts = [];

	    if ($author) {
	        $blogPosts = $this->blogPostRepository->findByAuthor($author);
	    }

	    return $this->render('admin/entries.html.twig', [
	        'blogPosts' => $blogPosts
	    ]);
	}

	/**
	 * @Route("/admin/create-entry", name="admin_create_entry")
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createEntryAction(Request $request)
	{
	    $blogPost = new BlogPost();

	    $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

	    $blogPost->setAuthor($author);

	    $form = $this->createForm(EntryFormType::class, $blogPost);
	    $form->handleRequest($request);

	    // Check is valid
	    if ($form->isSubmitted() && $form->isValid()) {
	        $this->entityManager->persist($blogPost);
	        $this->entityManager->flush($blogPost);

	        $this->addFlash('success', 'Congratulations! Your post is created');

	        return $this->redirectToRoute('admin_entries');
	    }

	    return $this->render('admin/entry_form.html.twig', [
	        'form' => $form->createView()
	    ]);
	}	

	/**
	 * @Route("/admin/edit-entry/{entryId}", name="admin_edit_entry")
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editEntryAction(Request $request, $entryId)
	{
		$blogPost = $this->blogPostRepository->findOneById($entryId);

	    $form = $this->createForm(EntryFormType::class, $blogPost);
	    $form->handleRequest($request);

	    // Check is valid
	    if ($form->isSubmitted() && $form->isValid()) {
	        $this->entityManager->persist($blogPost);
	        $this->entityManager->flush($blogPost);

	        $this->addFlash('success', 'Congratulations! Your post is created');

	        return $this->redirectToRoute('admin_entries');
	    }

	    return $this->render('admin/entry_form_edit.html.twig', [
	        'form' => $form->createView()
	    ]);
	}

	/**
	 * @Route("/admin/delete-entry/{entryId}", name="admin_delete_entry")
	 *
	 * @param $entryId
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteEntryAction($entryId)
	{
	    $blogPost = $this->blogPostRepository->findOneById($entryId);
	    $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

	    if (!$blogPost || $author !== $blogPost->getAuthor()) {
	        $this->addFlash('error', 'Unable to remove entry!');

	        return $this->redirectToRoute('admin_entries');
	    }

	    $this->entityManager->remove($blogPost);
	    $this->entityManager->flush();

	    $this->addFlash('success', 'Entry was deleted!');

	    return $this->redirectToRoute('admin_entries');
	}

	/* AUTHOR */
	/* ****** */

	/**
	 * @Route("/admin/author/create", name="author_create")
	 */
	public function createAuthorAction(Request $request)
	{
		if(!is_null($this->getUser())) {
		    if ($this->authorRepository->findOneByUsername($this->getUser()->getUserName())) {

		        $this->addFlash('error', 'Unable to create author, author already exists!');
		        // Redirect to dashboard.
		        return $this->redirectToRoute('homepage');
		    }
		}else{
	        $this->addFlash('error', 'Unable to create author, you must be authenticated!');

	        return $this->redirectToRoute('homepage');
		}

	    $author = new Author();
	    $author->setUsername($this->getUser()->getUserName());

	    $form = $this->createForm(AuthorFormType::class, $author);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        $this->entityManager->persist($author);
	        $this->entityManager->flush($author);

	        $request->getSession()->set('user_is_author', true);
	        $this->addFlash('success', 'Congratulations! You are now an author.');

	        return $this->redirectToRoute('homepage');
	    }

	    return $this->render('admin/create_author.html.twig', [
	        'form' => $form->createView()
	    ]);
	}



}
