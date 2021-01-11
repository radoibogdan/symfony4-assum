<?php

namespace App\Twig;

use App\Entity\FondsEuro;
use App\Entity\Produit;
use App\Repository\FondsEuroRepository;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
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
    /**
     * @var TagAwareAdapterInterface
     */
    private $cache;

    public function __construct(Environment $twig, FondsEuroRepository $fondsEuroRepository, TagAwareAdapterInterface $cache)
    {
        $this->twig = $twig;
        $this->fondsEuroRepository = $fondsEuroRepository;
        $this->cache = $cache;
    }

   public function getFunctions()
   {
       return [
           // utilisé dans _template, dans le footer
           new TwigFunction('is_taux_available',[$this, 'findIfTauxAvailable'],['is_safe' => ['html']]),
           // utilisé dans _template, dans le footer
           new TwigFunction('meilleur_taux',[$this, 'findMeilleurTaux'],['is_safe' => ['html']]),
           // utilisé pour afficher le dernier année ou un fonds euro a un taux différent de 0: Homepage, Tous produits, Produit individuel
           new TwigFunction('annee_fonds_euro_non_null',[$this, 'anneeFondsEuroNonNull'],['is_safe' => ['html']])
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
            $meilleur_fonds_euro = $this->fondsEuroRepository->meilleurFondsDeLanneeX($annee); // renvoie null si aucun fonds n'existe dans l'année
            $annee--;
            // tant qu'il n'y a pas de meilleur fonds et que ce fonds n'est pas 0 (le rendement s'affiche en mars)
        } while ($meilleur_fonds_euro === [] || $meilleur_fonds_euro[0]->getTauxPb() === 0);
        $meilleur_taux[$meilleur_fonds_euro[0]->getAnnee()] = $meilleur_fonds_euro[0]->getTauxPbFloat();
        return $meilleur_taux;
    }

    // utilisé pour afficher le dernier année ou un fonds euro a un taux différent de 0: Homepage, Tous produits, Produit individuel
    public function anneeFondsEuroNonNull(): ?int
    {
        return $this->cache->get('cache_fonds_euro_reference', function(ItemInterface $item){
            $item->tag('cache_fonds_euro');

            $annee = date('Y');
            do {
                /** @var FondsEuro $meilleur_fonds_euro */
                $meilleur_fonds_euro = $this->fondsEuroRepository->meilleurFondsDeLanneeX($annee); // renvoie null si aucun fonds n'existe dans l'année
                $annee--;
                // tant qu'il n'y a pas de meilleur fonds et que ce fonds n'est pas 0 (le rendement s'affiche en mars)
            } while ($meilleur_fonds_euro === [] || $meilleur_fonds_euro[0]->getTauxPb() === 0);

            return $meilleur_fonds_euro[0]->getTauxPb() == 0 ? null :$meilleur_fonds_euro[0]->getAnnee();
        });
    }
}