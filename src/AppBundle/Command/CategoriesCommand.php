<?php

namespace AppBundle\Command;

use AppBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategoriesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:categories')
            ->setDescription('import categories');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = "select wp_terms.term_id,
wp_terms.`name`,
wp_terms.slug,
wp_terms.term_group,
wp_term_taxonomy.description,
wp_term_taxonomy.parent from djouza.wp_terms
INNER JOIN djouza.wp_term_taxonomy on djouza.wp_term_taxonomy.term_id = djouza.wp_terms.term_id
where djouza.wp_term_taxonomy.taxonomy = 'category'";
        $stmt = $con -> prepare($sql);
		$stmt -> execute();
		$rows = $stmt->fetchAll();
		foreach ($rows as $row)
        {
            $catagory = $em->getRepository('AppBundle:Categories')->findOneBy(array('oldId'=>$row['term_id']));
            if($catagory === null){
                $catagory = new Categories();
                $catagory->setAct(true);
            }
            if($row['parent']!= 0){
                $catparent = $em->getRepository('AppBundle:Categories')->findOneBy(array('oldId'=>$row['parent']));
                $catagory->setParent($catparent);
            }
            $catagory->setName($row['name']);
            $catagory->setDescription($row['description']);
            $catagory->setSlug($row['slug']);
            $catagory->setOldId($row['term_id']);
            $em->persist($catagory);
        }
        $em->flush();
        $output->writeln("done categories");
    }
}
