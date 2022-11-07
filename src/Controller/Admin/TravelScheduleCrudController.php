<?php

namespace App\Controller\Admin;
use App\Entity\TravelSchedule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;


class TravelScheduleCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return TravelSchedule::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->add('index', 'detail');
    }


    public function configureFields(string $pageName): iterable
    {
        $data = [
            $departFrom = TextField::new('departFrom'),
            $travelTo = TextField::new('travelTo'),
            $departingOn = DateTimeField::new('departingOn'),
            $returningOn = DatetimeField::new('returningOn'),
            $departureTime = TimeField::new('departureTime'),
            $timeOfArrival = TimeField::new('timeOfArrival'),
            $estimatedArrivalTime = TimeField::new('estimatedArrivalTime'),
            $fee = MoneyField::new('fee')->setCurrency('HRK'),
        ];

        return $data;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('TravelSchedule')->setEntityLabelInPlural('TravelSchedules');
    }

}
