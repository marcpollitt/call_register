<?php

namespace App\Services;

use DateTime;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CloseIOA4UImportService
{
    /** @var Xls */
    private $xls;

    /** @var array */
    private $grid;

    /** @var DateTime */
    private $createdDate;

    /**
     * CloseIOA4UImportService constructor.
     * @param Xls $xls
     */
    public function __construct()
    {
        $this->xls = new Xls();
        $this->grid = [];

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importFile(InputInterface $input, OutputInterface $output)
    {
        $sheet = $this->getSheet($input);

        $this->getSheetData($sheet);

        return $this->grid;
    }

    /**
     * @param $sheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getSheetData($sheet): void
    {
        $x = ['A', 'B'];

        $numOfColumn = count($x);

        for ($i = 0; $i < $numOfColumn; $i++) {
            $this->getColumnData($sheet, $i, $x);
        }
    }

    /**
     * @param Worksheet $sheet
     * @param int $i
     * @param array $x
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getColumnData(Worksheet $sheet, int $i, array $x): void
    {
        $y = 4;
        $nullCounter = 0;

        do {
            $cell = $sheet->getCell(sprintf('%s%s', $x[$i], $y));

            $nullCounter++;

            if (!is_null($cell->getValue())) {
                $this->grid[$i][$y] = $cell->getValue();
                $nullCounter = 0;
            }

            $y++;
        } while ($nullCounter <= 2);

        $this->grid[$i] = array_values($this->grid[$i]);

        array_pop($this->grid[$i]);
    }

    /**
     * @param InputInterface $input
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function getSheet(InputInterface $input): \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
    {
        $filename = $input->getArgument('filename');

        $this->xls->canRead($filename);

        $spreadsheet = $this->xls->load($filename);

        return $spreadsheet->getActiveSheet();
    }
}