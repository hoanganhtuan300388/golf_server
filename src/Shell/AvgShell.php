<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/3/2018
 * Time: 1:39 PM
 */
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class AvgShell extends Shell
{
    public function main()
    {
        $scoreMin   = 1;
        $scoreMax   = 99;
        $typeHalf   = 1;
        $typeFull   = 2;
        $avgTable   = TableRegistry::get('Avgs');
        $roundTable = TableRegistry::get('UserRounds');

        for($score = $scoreMin; $score <= $scoreMax; $score++) {
            for($type = $typeHalf; $type <= $typeFull; $type++) {
                $conditions = [
                    'type'      => $type,
                    'score'     => $score,
                    'status'    => 2
                ];

                //avg pat
                $roundQuery     = $roundTable->find();
                $roundResult    = $roundQuery->select([
                        'sum_putt_num'          => $roundQuery->func()->sum('putt_num'),
                        'sum_fairway_keep_num'  => $roundQuery->func()->sum('fairway_keep_num'),
                        'sum_par_on_num'        => $roundQuery->func()->sum('par_on_num'),
                        'sum_sand_save_num'     => $roundQuery->func()->sum('sand_save_num'),
                        'sum_scramble_num'      => $roundQuery->func()->sum('scramble_num'),
                    ])
                    ->where($conditions)
                    ->first();

                $count          = $roundTable->find()->where($conditions)->count();

                $avgData        = [
                    'avg_pat'       => !empty($roundResult['sum_putt_num']) ? round($roundResult['sum_putt_num'] / $count) : 0,
                    'avg_fairway'   => !empty($roundResult['sum_fairway_keep_num']) ? round($roundResult['sum_fairway_keep_num'] / $count) : 0,
                    'avg_gir'       => !empty($roundResult['sum_par_on_num']) ? round($roundResult['sum_par_on_num'] / $count) : 0,
                    'avg_sandsave'  => !empty($roundResult['sum_sand_save_num']) ? round($roundResult['sum_sand_save_num'] / $count) : 0,
                    'avg_scramble'  => !empty($roundResult['sum_scramble_num']) ? round($roundResult['sum_scramble_num'] / $count) : 0
                ];

                $avg = $avgTable->find('all', [
                    'conditions' => [
                        'round_type'    => $type,
                        'avg_score'     => $score
                    ]
                ])->first();

                if(empty($avg)) {
                    $avgData['round_type']  = $type;
                    $avgData['avg_score']   = $score;

                    $avgEntity = $avgTable->newEntity($avgData);
                } else {
                    $avgEntity = $avgTable->patchEntity($avg, $avgData);
                }

                if(!$avgTable->save($avgEntity)) {
                    $this->log(__('Lỗi xảy ra khi chạy batch avg'));
                    $this->log($avgData);
                }
            }
        }

        $this->out('Call avg shell');
    }
}