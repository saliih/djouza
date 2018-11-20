<?php

namespace AppBundle\Command;

use AppBundle\Admin\CategoriesAdmin;
use AppBundle\Entity\Categories;
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
        $sql = "SELECT * FROM djizou.wp_posts WHERE post_status='publish' and post_type = 'post'";
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
            $post->setBody($this->updatebody($row['post_content']));

            $post->setSlug($this->checkRedirection($row['post_name']));
            // image
            $postimage = "select guid from djizou.wp_posts where post_type='attachment' and post_parent = ".$row['ID']."  ORDER BY post_date desc limit 1";
            $stmtimg = $con->prepare($postimage);
            $stmtimg->execute();
            $rowsimg = $stmtimg->fetch();
            $image = $rowsimg['guid'];
            $imagename = str_replace('http://cuisinezavecdjizou.fr/wp-content/','',$image);
            $imagename = str_replace('https://cuisinezavecdjizou.fr/wp-content/','',$imagename);
            $post->setImage($imagename);
            // category
             $sqlcat = "SELECT t.term_id FROM djizou.wp_terms AS t 
INNER JOIN djizou.wp_term_taxonomy AS tt ON (tt.term_id = t.term_id) 
INNER JOIN djizou.wp_term_relationships AS tr ON (tr.term_taxonomy_id = tt.term_taxonomy_id) 
WHERE tt.taxonomy IN ('category') AND tr.object_id = (".$row['ID'].");";
            $stmtcat = $con->prepare($sqlcat);
            $stmtcat->execute();
            while($rowscat = $stmtcat->fetch()){
                $cat = $container->get('doctrine')->getRepository('AppBundle:Categories')
                    ->findOneBy(array('oldId'=>$rowscat['term_id']));
                $post->setCategory($cat);

            }


            // options
            $sqlparams = "select * from djizou.wp_postmeta where post_id = " . $row['ID'] . " ";//and meta_key in ('_wp_attached_file','_aioseop_keywords','_aioseop_title','_aioseop_description','_aioseop_nofollow','_wp_attached_file')
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
                }/*elseif ($postmeta['meta_key'] == "_bsf_recipes_photo" && $post->getImage() == "") {
                    $image = $postmeta['meta_value'];
                    $imagename = str_replace('http://cuisinezavecdjizou.fr/wp-content/','',$image);
                    $imagename = str_replace('https://cuisinezavecdjizou.fr/wp-content/','',$imagename);
                    $folders = explode("/",$imagename);
                    unset($folders[count($folders)-1]);
                    $fold = $container->get('kernel')->getRootDir() . '/../web/'.implode('/',$folders);

                    if(is_file($fold."/".basename($image))) {
                        $post->setImage(implode('/', $folders) . "/" . basename($image));
                    }
                    //$output->writeln($imagename);
                    //$post->setNofollow();
                }*/else{
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
    private function checkRedirection($slug){
        /**/
        return $slug;
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = "select * from djizou.wp_redirection_items where url like '%$slug%' and status = 'enabled'  order by id desc limit 1";
        $stmtoptions = $con->prepare($sql);
        $stmtoptions->execute();
        $redirect = $stmtoptions->fetch();
        if (count($redirect)) {
            if ($redirect['action_code'] == "301") {
                $data = explode(" ", $redirect['url']);
                 $countRedirect = count($data);
                if($countRedirect == 1){
                    $slug = $redirect['action_data'];
                    $slug = str_replace("/","",$slug);

                }elseif ($countRedirect == 4){
                    $slug = $data[2];
                }
            }
        }
        $slug = $this->updateUrls($slug);
        return $slug;
    }
    private function updatebody($str){
        $formated =  str_replace("wp-content",'',$str);
        $formated = $this->updateUrls($formated);
        return $formated;
    }
    private function updateUrls($str){
        $str = str_replace('http://cuisinezavecdjizou.fr/', '', $str);
        $str = str_replace('[caption', '<caption', $str);
        $str = str_replace('[/caption]', '</caption>', $str);
        $str = str_replace(']', '>', $str);
        $str = str_replace('https://cuisinezavecdjizou.fr/', '', $str);
        $str = str_replace('http://www.cuisinezavecdjizou.fr/', '', $str);
        $str = str_replace('https://www.cuisinezavecdjizou.fr/', '', $str);
        $str = str_replace('http://cuisinezavecdjizou.fr', '', $str);
        $str = str_replace('https://cuisinezavecdjizou.fr', '', $str);
        $str = str_replace('http://www.cuisinezavecdjizou.fr', '', $str);
        $str = str_replace('https://www.cuisinezavecdjizou.fr', '', $str);
        return $str;
    }
}
