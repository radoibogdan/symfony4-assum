<?php


namespace App\Twig;


use App\Entity\FondsEuro;
use App\Entity\Produit;
use App\Repository\FondsEuroRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FonctionsCreated extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var FondsEuroRepository
     */
    private $fondsEuroRepository;

    public function __construct(
        Environment $twig,
        FondsEuroRepository $fondsEuroRepository
    )
    {
        $this->twig = $twig;
        $this->fondsEuroRepository = $fondsEuroRepository;
    }

   public function getFunctions()
   {
       return [
           // utilisé dans _template, dans le footer
           new TwigFunction('is_taux_available',[$this, 'findIfTauxAvailable'],['is_safe' => ['html']]),
           // utilisé dans _template, dans le footer
           new TwigFunction('meilleur_taux',[$this, 'findMeilleurTaux'],['is_safe' => ['html']])
       ];
   }

    // utilisé dans _template, dans le footer
    public function findIfTauxAvailable(Produit $produit): bool
    {
        $fonds = $produit->getFondsEuro();
        if ($fonds->isEmpty()) {
            return false;
        }
        return true;
    }

    // utilisé dans _template, dans le footer, renvoie le meilleur taux de cette année
    public function findMeilleurTaux(): array
    {
        $annee = date('Y');
        do {
            /** @var FondsEuro $meilleur_fonds_euro */
            $meilleur_fonds_euro = $this->fondsEuroRepository->meilleurFondsDeLanneeX($annee);
            $annee--;
        } while ($meilleur_fonds_euro === []);
        $meilleur_taux[$meilleur_fonds_euro[0]->getAnnee()] = $meilleur_fonds_euro[0]->getTauxPbFloat();
        return $meilleur_taux;
    }

}