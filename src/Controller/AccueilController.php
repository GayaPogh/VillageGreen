<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\CategorieRepository;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        CategorieRepository $categorieRepository,
        FournisseurRepository $fournisseurRepository,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $categories = $categorieRepository->findBy(['parent' => null]);
        $fournisseurs = $fournisseurRepository->findAll();

        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    // սահմանում ենք DEFAULT role
    if (!$user->getRole()) {
        $user->setRole('USER'); // կամ 'CLIENT' ըստ քո լոգիկայի
    }

    $user->setPassword(
        $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
    );
    $em->persist($user);
    $em->flush();

    $this->addFlash('success', 'Գրանցումը հաջողվեց։');
    return $this->redirectToRoute('app_accueil');
}


        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'fournisseurs' => $fournisseurs,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/categorie/{id}', name: 'categorie_show')]
    public function showCategorie(Categorie $categorie): Response
    {
        $sousCategories = $categorie->getEnfants();

        return $this->render('accueil/categorie.html.twig', [
            'categorie'      => $categorie,
            'sousCategories' => $sousCategories,
        ]);
    }

    #[Route('/souscategorie/{id}', name: 'souscategorie_show')]
    public function showSousCategorie(Categorie $sousCategorie): Response
    {
        $produits = $sousCategorie->getProduits()->toArray();

        foreach ($sousCategorie->getEnfants() as $enfant) {
            $produits = array_merge($produits, $enfant->getProduits()->toArray());
        }

        return $this->render('accueil/souscategorie.html.twig', [
            'sousCategorie' => $sousCategorie,
            'produits'      => $produits,
        ]);
    }
}
