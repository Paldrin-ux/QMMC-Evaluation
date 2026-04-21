<?php

namespace App\Services;

use App\Models\Evaluation;
use Illuminate\Support\Facades\DB;

class EvaluationService
{
    public const SECTION_A = [
        ['field' => 'a1',  'label' => "Cleans, disinfects, deodorizes sinks, toilets, urinals, partitions, and mirrors.", 'pts' => 5],
        ['field' => 'a2',  'label' => "Cleans and disinfects patients' rooms/offices and assigned high traffic areas.", 'pts' => 5],
        ['field' => 'a3',  'label' => "Cleans/disinfects ALL high touch points (bed handrails, corners, side tables, light switches, etc.).", 'pts' => 5],
        ['field' => 'a4',  'label' => "Clean walls (including doors and windows), ceilings, dusting, and removing cobwebs.", 'pts' => 5],
        ['field' => 'a5',  'label' => "Floor: Scrubs/disinfects, removes stains, sticky substances, and applies wax/polishes.", 'pts' => 5],
        ['field' => 'a6',  'label' => "Hallways/Stairways: Sweeps, mops, scrubs, and polishes tiled floors.", 'pts' => 5],
        ['field' => 'a7',  'label' => "Waste bins: Washes, decontaminates, and replaces color-coded plastic bags when needed.", 'pts' => 5],
        ['field' => 'a8',  'label' => "Collects and transports segregated and labelled/coded healthcare wastes correctly.", 'pts' => 5],
        ['field' => 'a9',  'label' => "Does general cleaning of room before admission of new patient and after discharge.", 'pts' => 5],
        ['field' => 'a10', 'label' => "Does damp dusting of fixed structures (window, ledges, panels, blinds, railing).", 'pts' => 5],
    ];

    public const SECTION_B = [
        ['field' => 'b1', 'label' => "Follows established safety procedures and precautions when performing tasks.", 'pts' => 5],
        ['field' => 'b2', 'label' => "Follows proper and safe techniques/methods including appropriate PPE usage.", 'pts' => 5],
        ['field' => 'b3', 'label' => "Wears prescribed uniform and ID.", 'pts' => 5],
        ['field' => 'b4', 'label' => "Demonstrates respectful, courteous, and considerate conduct.", 'pts' => 5],
        ['field' => 'b5', 'label' => "Shows initiative and positive attitude towards work.", 'pts' => 5],
        ['field' => 'b6', 'label' => "Arrives on time. Punctual.", 'pts' => 5],
    ];

    public const SECTION_C = [
        ['field' => 'c1', 'label' => "Follows proper healthcare waste segregation and disposal.", 'pts' => 10],
        ['field' => 'c2', 'label' => "Ensures established infection control and precaution protocols are followed.", 'pts' => 10],
    ];

    public function store(array $data, array $checkboxes): Evaluation
    {
        return DB::transaction(function () use ($data, $checkboxes) {
            $allCriteria = array_merge(self::SECTION_A, self::SECTION_B, self::SECTION_C);

            $sectionA = 0;
            $sectionB = 0;
            $sectionC = 0;
            $scoreRows = [];

            foreach ($allCriteria as $criterion) {
                $field     = $criterion['field'];
                $compliant = ! empty($checkboxes[$field]);
                $pts       = $compliant ? $criterion['pts'] : 0;
                $section   = strtoupper($field[0]);

                if ($section === 'A') $sectionA += $pts;
                elseif ($section === 'B') $sectionB += $pts;
                else $sectionC += $pts;

                $scoreRows[] = [
                    'section'       => $section,
                    'field_key'     => $field,
                    'is_compliant'  => $compliant,
                    'points_earned' => $pts,
                ];
            }

            $total = $sectionA + $sectionB + $sectionC;

            $evaluation = Evaluation::create(array_merge($data, [
                'section_a_total' => $sectionA,
                'section_b_total' => $sectionB,
                'section_c_total' => $sectionC,
                'total_score'     => $total,
                'rating_label'    => Evaluation::computeRatingLabel($total),
            ]));

            $evaluation->scores()->createMany($scoreRows);

            return $evaluation;
        });
    }

    public function getSections(): array
    {
        return [
            'A' => self::SECTION_A,
            'B' => self::SECTION_B,
            'C' => self::SECTION_C,
        ];
    }
}