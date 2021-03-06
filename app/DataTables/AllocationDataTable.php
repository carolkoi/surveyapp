<?php

namespace App\DataTables;

use App\Models\Allocation;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Carbon\Carbon;
class AllocationDataTable extends DataTable
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

        return $dataTable
            ->addColumn('type', 'allocations.datatables_type')
            ->addColumn('title', 'allocations.datatables_title')
            ->addColumn('date range', 'allocations.datatables_date_range')
            ->addColumn('status', 'allocations.datatables_status')
            ->addColumn('users', function ($query){
           return $query->CountUsersByTemplateId($query->template_id);
//            return count($query['user_type']);
        })
        ->addColumn('action', 'allocations.datatables_actions')
            ->rawColumns(['type', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Allocation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Allocation $model)
    {
        return $model->with(['template', 'template.user', 'template.questions', 'template.surveyType'])->groupBy(['template_id'])->newQuery();
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
//            'created by',
            'date range',
            'status',
//            'Sent to',
            'users',
//            'no of respondents',
//            '%age responses'

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
