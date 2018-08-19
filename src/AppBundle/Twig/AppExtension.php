<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 15/08/2018
 * Time: 16:45
 */

namespace AppBundle\Twig;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{
    private $context;

    public function getName()
    {
        return 'app_extension';
    }

    public function __construct(ContainerInterface $context)
    {
        $this->context = $context;
        //$this->template = $context->get('templating');
    }

    public function getFilters()
    {
        return array(
            'gavatar' => new \Twig_Filter_Method($this, 'getGavatarHash'),
            'shortdesc' => new \Twig_Filter_Method($this, 'shortdesc'),

        );
    }
    public function shortdesc($str,$size = 400){
        $text = strip_tags($str);
        if(strlen($text)>=$size){
            $text = substr($text,0,$size);
            $espace = strrpos($text," ");
            $text = substr($text,0,$espace);
        }
        return $text;
    }
    public function getGavatarHash($email){
        return md5( strtolower( trim($email ) ) );
    }

}