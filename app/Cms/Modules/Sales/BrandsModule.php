<?php declare(strict_types = 1);

namespace App\Cms\Modules\Sales;

use Dms\Core\Auth\IAuthSystem;
use Dms\Core\Common\Crud\CrudModule;
use Dms\Core\Common\Crud\Definition\CrudModuleDefinition;
use Dms\Core\Common\Crud\Definition\Form\CrudFormDefinition;
use Dms\Core\Common\Crud\Definition\Table\SummaryTableDefinition;
use App\Domain\Services\Persistence\Sales\IBrandsRepository;
use App\Domain\Entities\Sales\Brands;
use Dms\Common\Structure\Field;
use App\Domain\Entities\Enums\Status;

/**
 * The brands module.
 */
class BrandsModule extends CrudModule
{


    public function __construct(IBrandsRepository $dataSource, IAuthSystem $authSystem)
    {

        parent::__construct($dataSource, $authSystem);
    }

    /**
     * Defines the structure of this module.
     *
     * @param CrudModuleDefinition $module
     */
    protected function defineCrudModule(CrudModuleDefinition $module)
    {
        $module->name('brands');

        $module->labelObjects()->fromProperty(Brands::CODE);

        $module->metadata([
            'icon' => ''
        ]);

        $module->crudForm(function (CrudFormDefinition $form) {
            $form->section('Details', [
                $form->field(
                    Field::create('code', 'Code')->string()
                )->bindToProperty(Brands::CODE),
                //
                $form->field(
                    Field::create('description', 'Description')->html()
                )->bindToProperty(Brands::DESCRIPTION),
                //
                $form->field(
                    Field::create('status', 'Status')->enum(Status::class, [
                        Status::ACTIVE => 'Active',
                        Status::INACTIVE => 'Inactive',
                    ])
                )->bindToProperty(Brands::STATUS),
                //
            ]);

        });

        $module->removeAction()->deleteFromDataSource();

        $module->summaryTable(function (SummaryTableDefinition $table) {
            $table->mapProperty(Brands::CODE)->to(Field::create('code', 'Code')->string());
            $table->mapProperty(Brands::DESCRIPTION)->to(Field::create('description', 'Description')->html());
            $table->mapProperty(Brands::STATUS)->to(Field::create('status', 'Status')->enum(Status::class, [
                Status::ACTIVE => 'Active',
                Status::INACTIVE => 'Inactive',
            ]));


            $table->view('all', 'All')
                ->loadAll()
                ->asDefault();
        });
    }
}