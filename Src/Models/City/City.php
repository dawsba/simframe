<?php


namespace Models\City;


use Doctrine\Common\Collections\ArrayCollection;
use Models\Cell\Cell;
use Twig\Environment;

class City
{
    private $width = 28;
    
    private $height = 8;
    
    /** @var ArrayCollection|null  */
    private $cellsArray;

    /** @var ArrayCollection|null  */
    private $cellsHtmlArray;
    
    public function __construct()
    {
        $this->cellsArray = new ArrayCollection();
        $cellId = 0;
        for ($y=0; $y<=$this->height; $y++) {
            for ($x=0; $x<=$this->width; $x++) {
                $cell = new Cell($cellId);
                $this->cellsArray->set($cellId, $cell->drawCell($y, $x));
                $cellId++;
            }
        }
    }

    private function generateNewBoard()
    {
        $this->cellsArray = new ArrayCollection();
        $cellId = 0;
        for ($y=0; $y<=$this->height; $y++) {
            for ($x=0; $x<=$this->width; $x++) {
                $cell = new Cell($cellId);
                $this->cellsArray->set($cellId, $cell->drawCell($y, $x));
                $cellId++;
            }
        }
    }
    
    public function generateCellsHtml($row)
    {
        if ( ! $this->cellsHtmlArray instanceof ArrayCollection) {
            $this->cellsHtmlArray = new ArrayCollection();
        }

        $row = $this->width * $row;

        for ($i=0; $i<=$this->width-1; $i++) {
            $this->cellsHtmlArray->set($i, $this->getCellById($row+$i)->getCellHtml());
        }
        
        return implode('', $this->cellsHtmlArray->toArray());
    }
    
    public function generateRowsHtml(Environment $twig)
    {
        $rows = '';
        for ($i=0; $i<=$this->height-1; $i++) {
            $rows .= $twig->render(
                './Board/row.html',
                [
                    "rowid" => "row-{$i}",
                    "cells" => $this->generateCellsHtml($i),
                ]
            );
        }
        
        return $rows;
    }

    /**
     * @return ArrayCollection
     */
    public function getCellsArray(): ArrayCollection
    {
        return $this->cellsArray;
    }

    /**
     * @param integer $id
     * @return Cell
     */
    public function getCellById($id) : Cell
    {
        if ($this->cellsArray->containsKey($id)) {
            return $this->cellsArray->get($id);
        }

        return new Cell($id);
    }
}