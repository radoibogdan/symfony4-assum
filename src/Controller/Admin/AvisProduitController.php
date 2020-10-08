<?php

namespace App\Controller\Admin;

use App\Repository\AvisProduitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvisProduitController
 * @package App\Controller\Admin
 * @IsGranted ("ROLE_ADMIN")
 * @Route ("/admin/avis_produit", name="admin_avisproduit_")
 */
class AvisProduitController extends AbstractController
{
    /**
     * @Route("s", name="liste")
     * @param AvisProduitRepository $avisProduitRepository
     * @return Response
     */
    public function index(AvisProduitRepository $avisProduitRepository)
    {
        $avisproduit_list = $avisProduitRepository->findAll();
        return $this->render('admin_avis_produit/liste.html.twig', [
            'avisproduit_list' => $avisproduit_list
        ]);
    }
}
