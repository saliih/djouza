<?php

namespace AppBundle\Command;

use AppBundle\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class PostsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:posts')
            ->setDescription('migrate posts from wrdpress');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = array();
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = "SELECT * FROM djouza.wp_posts WHERE post_status='publish' and post_type = 'post'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $progressBar = new ProgressBar($output, count($rows));
        foreach ($rows as $key => $row) {
            $post = $em->getRepository('AppBundle:Posts')->findOneBy(array('oldId' => $row['ID']));
            if ($post === null) {
                $post = new Posts();
                $post->setOldId($row['ID']);
            }
            $post->setTitle($row['post_title']);
            $dtime = \DateTime::createFromFormat("Y-m-d G:i:s", $row['post_date']);

            $post->setDcr($dtime);
            if ($row['post_modified'] != "000-00-00 00:00:00") {
                $dtime = \DateTime::createFromFormat("Y-m-d G:i:s", $row['post_modified']);
                $post->setDmj($dtime);
            }
            $post->setStatus(true);
            $post->setCommentStatus(( $row['comment_status'] == "open" ) ? true : false);
            $post->setBody($row['post_content']);
            $slug = $row['pinged'];
            $slug = str_replace('http://cuisinezavecdjouza.fr/', '', $slug);
            $slug = str_replace('https://cuisinezavecdjouza.fr/', '', $slug);
            $slug = str_replace('http://www.cuisinezavecdjouza.fr/', '', $slug);
            $slug = str_replace('https://www.cuisinezavecdjouza.fr/', '', $slug);
            $post->setSlug($slug);
            // options
            $sqlparams = "select * from djouza.wp_postmeta where post_id = " . $row['ID'] . " 
            ";//and meta_key in ('_wp_attached_file','_aioseop_keywords','_aioseop_title','_aioseop_description','_aioseop_nofollow','_wp_attached_file')
           /* if ($row['ID'] == 25584){
                echo "\n";
            $output->writeln($sqlparams);
        }*/
            $stmtoptions = $con->prepare($sqlparams);
            $stmtoptions->execute();
            $postmetas = $stmtoptions->fetchAll();
            $fileSystem = new Filesystem();
            foreach ($postmetas as $postmeta) {

                if ($postmeta['meta_key'] == "_aioseop_keywords") {
                    $post->setSeoKeywords($postmeta['meta_value']);
                } elseif ($postmeta['meta_key'] == "_aioseop_title") {
                    $post->setSeoTitle($postmeta['meta_value']);
                } elseif ($postmeta['meta_key'] == "_aioseop_description") {
                    $post->setSeoDescription($postmeta['meta_value']);
                } elseif ($postmeta['meta_key'] == "post-rating") {
                    //print_r($postmeta['meta_value']);
                } elseif ($postmeta['meta_key'] == "_aioseop_nofollow") {
                    $post->setNofollow(true);
                } elseif ($postmeta['meta_key'] == "_bsf_recipes_preptime") {
                    $post->setPreptime($postmeta['meta_value']);
                }elseif ($postmeta['meta_key'] == "_bsf_recipes_cooktime") {
                    $post->setCooktime($postmeta['meta_value']);
                }elseif ($postmeta['meta_key'] == "_bsf_recipes_totaltime") {
                    $post->setTotaltime($postmeta['meta_value']);
                }elseif ($postmeta['meta_key'] == "_bsf_recipes_photo") {
                    $image = $postmeta['meta_value'];
                    $imagename = str_replace('http://cuisinezavecdjouza.fr/wp-content/','',$image);
                    $imagename = str_replace('https://cuisinezavecdjouza.fr/wp-content/','',$imagename);
                    $folders = explode("/",$imagename);
                    unset($folders[count($folders)-1]);
                    $fold = $container->get('kernel')->getRootDir() . '/../web/'.implode('/',$folders);
                    $fileSystem->mkdir($fold,0777);
                    $fileSystem->copy($image,$fold);
                    $post->setImage(implode('/',$folders));
                    //$output->writeln($imagename);
                    //$post->setNofollow();
                }else{
                    //$output->writeln($postmeta['meta_key']);
                }/* elseif ($postmeta['meta_key'] == "_wp_attached_file") {
                    print_r($postmeta);
                }*/


            }

             $em->persist($post);
            if ($key % 50 == 1) {
                 $em->flush();
            }
            $progressBar->advance();
        }
        //print_r(array_unique($options));
         $em->flush();
        $progressBar->finish();
    }
}
