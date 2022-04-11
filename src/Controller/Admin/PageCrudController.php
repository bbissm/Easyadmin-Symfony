<?php

namespace App\Controller\Admin;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Controller\EventController;
use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\RequestContext;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }


    public function configureFields(string $pageName): iterable
    {
        //$files = glob('../templates/*.html.twig');
        $files = scandir('../templates');
        $templates = [];
        foreach ($files as $file) {
            $fileName = explode('.html.twig',$file)[0];
            if (strpos($file,'.html.twig')) $templates[$fileName] = $file;
        }

        $controllerFiles = scandir('../src/Controller');
        $namespace = explode('separator','App\Controller\separator')[0];
        foreach($controllerFiles as $file) {
            if (!strpos($file,'.php')) continue;
            $controllerName = explode('.php',$file)[0];
            $class = new ReflectionClass($namespace.$controllerName);
            foreach ($class->getMethods() as $method) {
                if ($method->name == '__construct') {
                    break;
                }
                $controllers[$controllerName.'::'.$method->name] = $controllerName.'::'.$method->name;
            }
        }
        return [
            IdField::new('id')->hideOnForm()->setPermission('ROLE_ADMIN'),
            TextField::new('title'),
            ChoiceField::new('template')->setChoices($templates)->setPermission('ROLE_ADMIN'),
            BooleanField::new('hide_menu'),
            ChoiceField::new('controller')->setChoices($controllers)->setPermission('ROLE_ADMIN'),
            TextField::new('route')->setPermission('ROLE_ADMIN')
        ];
    }


    // Set action permissions
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // use the 'setPermission()' method to set the permission of actions
            // (the same permission is granted to the action on all pages)
            ->setPermission('delete', 'ROLE_ADMIN')

            // you can set permissions for built-in actions in the same way
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ;
    }
}
