<?php

namespace TheSaturn\BranchAndBound;

/**
 * Формирование таблицы для обсчета
 */
class    TableBranchAndBound
{
    /**
     * Таблица затрат
     * @var array
     */
    public $table = [];

    function    __construct()
    {
        if (count($_POST) == 0 || isset($_POST['amount']) && !empty($_POST['amount']))
        {
            $this->random();
        }
        elseif (isset($_POST['table']))
        {
            $this->fromPost();
        }
        else
        {
            $this->fromExamples();
        }
    }

    /**
     * Генерация рандомной таблицы с учетом количества строк в $_POST['amount']
     */
    function    random()
    {
        $max = 7;
        if (isset($_POST['amount']))
        {
            $amount = (int)$_POST['amount'];
            $rows = ($amount > 2 && $amount < 20) ? $amount : 3;
        }
        else
        {
            $rows = rand(3, $max);
        }
        for ($i = 1; $i <= $rows; $i++)
        {
            for ($j = 1; $j <= $rows; $j++)
            {
                $this->table[$i][$j] = $i == $j ? INF : rand(0, 99);
            }
        }
    }

    /**
     * Заполнение таблицы на основе полученных данных
     */
    function    fromPost()
    {
        foreach ($_POST['table'] as $rowName => $row)
        {
            foreach ($row as $columnName => $value)
            {
                $this->table[$rowName][$columnName] = abs(intval($value));
            }
        }
        foreach ($this->table as $rowName => $row)
        {
            $this->table[$rowName][$rowName] = INF;
            ksort($this->table[$rowName]);
        }
        ksort($this->table);
    }

    /**
     * Вывод таблицы в HTML
     * @return string
     */
    function    __toString()
    {
        $str = '<table class="table table-bordered" id="tableInput"><tbody>';
        $str .= '<tr><td></td>';
        foreach ($this->table as $rowName => $row)
        {
            $str .= "<td>$rowName</td>";
        }
        $str .= '</tr>';
        foreach ($this->table as $rowName => $row)
        {
            $str .= "<tr><td>$rowName</td>";
            foreach ($row as $columnName => $value)
            {
                $str .= "<td>";
                $str .=
                    '<input class="form-control" type="text" value="' . $value . '" name="table[' . $rowName . '][' .
                    $columnName . ']" requied' . ($columnName == $rowName ? ' disabled' : '') . '>';
                $str .= "</td>";
            }
            $str .= '</tr>';
        }
        $str .= '</tbody></table>';
        return $str;
    }

}
