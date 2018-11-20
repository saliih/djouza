<?php

namespace AppBundle\Command;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class CommentsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:comments')
            ->setDescription('migrate comments from wrdpress');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = "SELECT * FROM djizou.wp_comments order by comment_parent asc";
        $stmt = $con -> prepare($sql);
        $stmt -> execute();
        $rows = $stmt->fetchAll();
        $progressBar = new ProgressBar($output, count($rows));
        foreach ($rows as $key=>$row)
        {
            $post = $em->getRepository('AppBundle:Posts')->findOneBy(array('oldId'=>$row['comment_post_ID']));
            if($post === null){
                continue;
            }
            $comment = $em->getRepository('AppBundle:Comments')->findOneBy(array('oldId'=>$row['comment_ID']));
            if($comment === null){
                $comment = new Comments();
                $comment->setOldId($row['comment_ID']);
            }
            $comment->setPost($post);
            $comment->setBody(strip_tags($row['comment_content']));
            $dtime = \DateTime::createFromFormat("Y-m-d G:i:s", $row['comment_date']);
            $comment->setDcr($dtime);
            $comment->setAuthor($row['comment_author']);
            $comment->setEmail($row['comment_author_email']);
            $comment->setUrl($row['comment_author_url']);
            $comment->setIp($row['comment_author_IP']);
            $comment->setAct(($row['comment_approved'] == 1 )?true:false);
            if($row['comment_parent'] != 0) {
                $parent = $container->get('doctrine')->getRepository('AppBundle:Comments')->findOneBy(array('oldId' => $row['comment_parent']));
                if($parent) {
                    $comment->setParent($parent);
                }
            }
            $em->persist($comment);
            if($key % 500 == 1){
                $em->flush();
            }
            $progressBar->advance();
        }
        $em->flush();
        $progressBar->finish();
    }
}
