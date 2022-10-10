<?php

namespace App\Controller\Admin;

use App\Entity\CartTicket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CartTicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CartTicket::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
