<?php


namespace Models\Cell;


class Cell
{
    private $id = 0;
    
    private $cellHtml = '';

    private $x = null;

    private $y = null;
    
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function drawCell($y, $x)
    {
        $this->x = $x;

        $this->y = $y;

        $classAdd = '';
        if ((($this->id)+1)%2) $classAdd = 'even';
        
        $this->cellHtml = <<< EOF
<div id="cell-{$this->id}" class="hex {$classAdd}">
  <div class="left"></div>
  <div class="middle"></div>
  <div class="right"></div>
</div>
EOF;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Cell
     */
    public function setId(int $id): Cell
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCellHtml(): string
    {
        return $this->cellHtml;
    }

    /**
     * @param string $cellHtml
     * @return Cell
     */
    public function setCellHtml(string $cellHtml): Cell
    {
        $this->cellHtml = $cellHtml;

        return $this;
    }
    
    
}
