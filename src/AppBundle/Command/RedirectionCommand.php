<?php

namespace AppBundle\Command;

use AppBundle\Entity\Redirection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RedirectionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:redirection')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $con = $em->getConnection();
        $sql = "select * from djouza.wp_redirection_items where  status = 'enabled'  order by id ";
        $stmtoptions = $con->prepare($sql);
        $stmtoptions->execute();
        while($redirect = $stmtoptions->fetch()) {
            if (count($redirect)) {
                if ($redirect['action_code'] == "301") {
                    $data = explode(" ", $redirect['url']);
                    $countRedirect = count($data);
                    if ($countRedirect == 1) {
                        $slug = $redirect['action_data'];
                        $slug = str_replace("/", "", $slug);

                    } elseif ($countRedirect == 4) {
                        $slug = $data[2];
                    }

                }
            }
            $old = $this->updateUrls($redirect['url']);
           // $old = str_replace("/", "",$old );
            $slug = $this->updateUrls($slug);
            $red = new Redirection();
            $red->setOld($old);
            $red->setNew($slug);
            $em->persist($red);
        }
        $em->flush();

    }

    private function updateUrls($str){
        $str = str_replace('http://cuisinezavecdjouza.fr/', '', $str);
        $str = str_replace('https://cuisinezavecdjouza.fr/', '', $str);
        $str = str_replace('http://www.cuisinezavecdjouza.fr/', '', $str);
        $str = str_replace('https://www.cuisinezavecdjouza.fr/', '', $str);
        $str = str_replace('http://cuisinezavecdjouza.fr', '', $str);
        $str = str_replace('https://cuisinezavecdjouza.fr', '', $str);
        $str = str_replace('http://www.cuisinezavecdjouza.fr', '', $str);
        $str = str_replace('https://www.cuisinezavecdjouza.fr', '', $str);
        return $str;
    }
}
