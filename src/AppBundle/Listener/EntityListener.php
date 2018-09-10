<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 08/09/18
 * Time: 11:50
 */

namespace AppBundle\Listener;

use AppBundle\Entity\Redirection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;

class EntityListener
{
    /**
     * @var Container
     */
    protected $context;
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(Container $context)
    {
        $this->context = $context;
        $this->em = $context->get('doctrine');
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $document = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        $changed = $uow->getEntityChangeSet($document);
        $class_name_elems = explode('\\', get_class($document));
        $class_name = end($class_name_elems);
        foreach ($changed as $field => $data) {
            if($class_name == "Posts"){
                switch ($field){
                    case "slug":
                        $redirect = new Redirection();
                        $redirect->setNew($data[0]);
                        $redirect->setOld($data[1]);
                        $redirect->setPost($document);
                        $this->em->getManager()->persist($redirect);
                        //$this->em->getManager()->flush();
                    // TODO : no flush
                }
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        /*$document = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        $changed = $uow->getEntityChangeSet($document);
        $class_name_elems = explode('\\', get_class($document));
        $class_name = end($class_name_elems);
        foreach ($changed as $field => $data) {
            $func_name = "post_" . $class_name . "_" . $field . "_changed";
            if (method_exists($this, $func_name)) {
                $this->$func_name($document, $data[0], $data[1]);
            }
            $this->writelog($func_name, 'functionPersist.log');
        }*/

    }

    public function writelog($func_name, $file)
    {
        $logwrite = $this->context->getParameter('log_function_changed');
        if ($logwrite) {
            $app_path = $this->context->get('kernel')->getRootdir();
            $log_file = "$app_path/../var/logs/$file";
            error_log( date("d-M-Y H:i:s") . " - $func_name\n", 3, $log_file);
        }
    }

}