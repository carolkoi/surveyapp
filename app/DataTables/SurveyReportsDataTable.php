<?php

namespace App\DataTables;

use App\Models\Allocation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class SurveyReportsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('type', function ($query){
            return $query->template->type;
        })->addColumn('title', function ($query){
            return $query->template->name;
        })
            ->addColumn('created by', function ($query){
                return $query->template->user->first_name;
            })
            ->addColumn('start date', function ($query){
                return $query->template->valid_from->format('Y-m-d');
            })
            ->addColumn('end date', function ($query){
                return $query->template->valid_until->format('Y-m-d');
            })
            ->addColumn('status', 'allocations.datatables_status')
            ->addColumn('action', 'allocations.datatables_actions')
            ->rawColumns(['status','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Allocation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Allocation $model)
    {
        return $model->with(['template.user'])->groupBy(['template_id'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'type',
            'title',
            'created by',
            'start date',
            'end date',
            'status',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'allocationsdatatable_' . time();
    }
}