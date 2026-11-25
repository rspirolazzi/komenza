<?php

namespace App\Criteria\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ReservationCriteria
 * @package Prettus\Repository\Criteria
 */
class ReservationCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Apply criteria in query repository
     * Apply criteria in query repository
     *
     * @param         Builder|Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $orderBy = 'reservation_time';
        $sortedBy = 'asc';
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'asc';

        $store_id = $this->request->get('store_id');
        $reservation_date = $this->request->get('reservation_date');
        $status = $this->request->get('status');
        $user_term = $this->request->get('user_term');

        $model = $model->where(function ($query) use ($reservation_date, $store_id, $status, $user_term) {
            if (!empty($reservation_date)) {
                $query->where('reservation_date', $reservation_date);
            }
            if (!empty($store_id)) {
                $query->where('store_id', $store_id);
            }
            if (!empty($status)) {
                $query->where('status', $status);
            }
            if (!empty($user_term)) {
                $query->whereHas('user', function ($query) use ($user_term) {
                    $query->where(function ($query) use ($user_term) {
                        $query->orWhere('users.name', 'like', "%{$user_term}%")
                            ->orWhere('users.last_name', 'like', "%{$user_term}%")
                            ->orWhere('users.email', 'like', "%{$user_term}%")
                            ->orWhere('users.telephone', 'like', "%{$user_term}%");
                    });
                });
            }
        });
        //dd($model->toSql());

        if (isset($orderBy) && !empty($orderBy)) {
            $split = explode('|', $orderBy);
            if (count($split) > 1) {
                /*
                 * ex.
                 * products|description -> join products on current_table.product_id = products.id order by description
                 *
                 * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
                 * by products.description (in case both tables have same column name)
                 */
                $table = $model->getModel()->getTable();
                $sortTable = $split[0];
                $sortColumn = $split[1];

                $split = explode(':', $sortTable);
                if (count($split) > 1) {
                    $sortTable = $split[0];
                    $keyName = $table . '.' . $split[1];
                } else {
                    /*
                     * If you do not define which column to use as a joining column on current table, it will
                     * use a singular of a join table appended with _id
                     *
                     * ex.
                     * products -> product_id
                     */
                    $prefix = str_singular($sortTable);
                    $keyName = $table . '.' . $prefix . '_id';
                }

                $model = $model
                    ->leftJoin($sortTable, $keyName, '=', $sortTable . '.id')
                    ->orderBy($sortColumn, $sortedBy)
                    ->addSelect($table . '.*');
            } else {
                $model = $model->orderBy($orderBy, $sortedBy);
            }

        }

        return $model;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {

        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }


    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        if (!is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = config('repository.criteria.acceptedConditions', [
                '=',
                'like'
            ]);
            $originalFields = $fields;
            $fields = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field = $field_parts[0];
                        $condition = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index] = $field;
                    }
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \Exception(trans('repository::criteria.fields_not_accepted', ['field' => implode(',', $searchFields)]));
            }

        }

        return $fields;
    }
}
