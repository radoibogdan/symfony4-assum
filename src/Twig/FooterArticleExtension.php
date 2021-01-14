<?php


namespace App\Twig;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FooterArticleExtension extends AbstractExtension
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TagAwareAdapterInterface
     */
    private $cache;

    public function __construct(
        ArticleRepository $articleRepository,
        Environment $twig,
        TagAwareAdapterInterface $cache
    )
    {
        $this->articleRepository = $articleRepository;
        $this->twig = $twig;
        $this->cache = $cache;
    }

   public function getFunctions()
   {
       return [
           // to call function use in twig footer_dernier_article()
           new TwigFunction('footer_dernier_article',[$this, 'getFooterArticle'],['is_safe' => ['html']])
       ];
   }

    public function getFooterArticle():string
    {
        return $this->cache->get('footer_dernier_article', function(ItemInterface $item){
            // Alternantive option : Cache is deleted after an hour
            // $item->expiresAfter(3600);
            // Cache will be deleted in AdminArticleController when new Article is created
            $item->tag('footer_article'); // tags the cache
            // call to function which renders the template
            return $this->renderFooterArticle();
        });
    }

    // User to render the template for getFooterArticle()
    public function renderFooterArticle(): string
    {
        /** @var Article $dernier_article */
        $dernier_article = $this->articleRepository->findLastArticlePublished()[0]; // find last news article published
        return $this->twig->render('includes/footer_dernier_article.html.twig',[
            'dernier_article' => $dernier_article
        ]);
    }
}