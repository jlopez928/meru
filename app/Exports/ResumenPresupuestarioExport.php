<?php

/*
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
//use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class ResumenPresupuestarioExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {


        return view('administrativo.meru_administrativo.formulacion.reportes.maestro_ley.ejecucionexcel', [
            'data' => $this->data
        ]);
    }   
}
*/

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
// use App\Helpers\AccountBalance;
// use App\Helpers\NumberFormat;
// use App\AccountType;
// use App\Account;

class ResumenPresupuestarioExport implements WithEvents
{
    protected $request;

    function __construct($request)
    {
        $this->request = $request;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet;
                dd($sheet);
                $row = 1;
                $sheet->append(['Income'],'A'.$row++);
                $sheet->append(['Juan'],'A'.$row++);
                $sheet->append(['Valentina', 'Duran'],'A'.$row++);
                $sheet->append(['Milenys', 'Melville'],'B'.$row++);

                /*
                $accounts = $this->getAccountWithBalance($this->request);

                $income = $accounts->where('account_type',AccountType::INCOME);
                $expense = $accounts->where('account_type',AccountType::EXPENSE);
                $netIncome = $income->sum('total') - $expense->sum('total');

                $generateCell = function($accounts,$sheet,&$row) use(&$generateCell)
                {
                    foreach ($accounts as $account) 
                    {
                        $sheet->append([
                            str_repeat(' ',$account->nodeLevel*4).$account->name,
                            NumberFormat::format($account->balance)
                        ],'A'.$row++);
                        if($account->child->count())
                        {
                            $generateCell($account->child,$sheet,$row);
                            $sheet->append([
                                str_repeat(' ',$account->nodeLevel*4).'Total '.$account->name,
                                NumberFormat::format($account->total)
                            ],'A'.$row++);
                        }
                    }
                    return $sheet;
                };
                $row = 1;
                $sheet->append(['Income'],'A'.$row++);
                $sheet = $generateCell($income,$sheet,$row);
                $sheet->append(['Total Income',NumberFormat::format($income->sum('total'))],'A'.$row++);
                $row++;
                $sheet->append(['Expense'],'A'.$row++);
                $sheet = $generateCell($expense,$sheet,$row);
                $sheet->append(['Total Expense',NumberFormat::format($expense->sum('total'))],'A'.$row++);
                $row++;
                $sheet->append(['Net Profit/Loss',NumberFormat::format($netIncome)],'A'.$row++);
                */
            }
        ];
    }
}