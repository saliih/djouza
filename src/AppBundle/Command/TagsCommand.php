<?php

namespace AppBundle\Command;

use AppBundle\Entity\Posts;
use AppBundle\Entity\Tags;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TagsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:tags')
            ->setDescription('migrate tags from wrdpress');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = 'SELECT
djouza.wp_posts.ID,
djouza.wp_terms.`name`,
djouza.wp_terms.slug,
djouza.wp_term_taxonomy.description
from djouza.wp_term_taxonomy 
inner JOIN djouza.wp_terms on djouza.wp_terms.term_id = djouza.wp_term_taxonomy.term_id
INNER JOIN djouza.wp_term_relationships on djouza.wp_term_relationships.term_taxonomy_id = djouza.wp_term_taxonomy.term_id
INNER JOIN djouza.wp_posts on djouza.wp_posts.ID = djouza.wp_term_relationships.object_id
WHERE djouza.wp_term_taxonomy.taxonomy = "post_tag"
order by djouza.wp_posts.ID DESC';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $progressBar = new ProgressBar($output, count($rows));
        foreach ($rows as $key => $row) {
            /** @var Posts $post */
            $post = $container->get('doctrine')->getRepository('AppBundle:Posts')->findOneBy(array('oldId' => $row['ID']));
            if($post) {
                $post->removeAllTags();
                $em->persist($post);
                $tag = $container->get('doctrine')->getRepository('AppBundle:Tags')->findOneBy(['slug'=>$row['slug']]);
                if($tag === null){
                    $tag = new Tags();
                    $tag->setName($row['name'])->setSlug($row['slug']);
                    $em->persist($tag);
                    $em->flush();
                }
                $post->addTag($tag);
                $em->persist($post);
                $em->flush();
               // echo $post->getTitle()."\n";
            }
            $progressBar->advance();
        }
        $progressBar->finish();
    }
}
