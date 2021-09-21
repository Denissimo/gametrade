<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

class UserAdmin extends BaseUserAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void
    {
        parent::configureFormFields($formMapper);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        parent::configureDatagridFilters($datagridMapper);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        parent::configureListFields($listMapper);
        $listMapper->remove('impersonating')
            ->add('firstname')
            ->add('lastname')
        ;
    }

    /**
     * @param User|object $object
     * @phpstan-param User $object
     */
    public function postPersist($object): void
    {
        $this->modelManager->update($object);
    }
}