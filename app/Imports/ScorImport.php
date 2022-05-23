<?php

namespace App\Imports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\ToModel;

class ScorImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Nilai([
            'nis' => $row[1],
            'nama' => $row[2],
            'kelas' => $row[3],
            'kd' => $row[4],
            'scor' => $row[5],
        ]);
    }
}
