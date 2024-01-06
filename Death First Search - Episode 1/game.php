<?php

class Game
{
    private $matrix = [];
    private $visited = [];
    private $width = 0;
    private $height = 0;
    private $memo = [];
    private $targetX = 0;
    private $targetY = 0;

    public function parseInput()
    {
        fscanf(STDIN, "%d", $this->width);
        fscanf(STDIN, "%d", $this->height);
        for ($y = 0; $y < $this->height; $y++) {
            $this->graph[$y] = array_map(
                function ($a) { return $a === 'O'; },
                str_split(stream_get_line(STDIN, $this->width + 1, "\n"))
            );
        }
    }

    public function floodFill(int $x, int $y)
    {
        if (!$this->isValidCoordinate($x, $y)|| !$this->graph[$y][$x] || isset($this->visited[$y][$x])) {
            return 0;
        }

        if (isset($this->memo[$y][$x])) {
            return $this->memo[$y][$x];
        }

        $cnt = 1;
        $this->visited[$y][$x] = true;

        $cnt += $this->floodFill($x, $y - 1);
        $cnt += $this->floodFill($x, $y + 1);
        $cnt += $this->floodFill($x - 1, $y);
        $cnt += $this->floodFill($x + 1, $y);

        if ($this->targetX === $x && $this->targetY === $y) {
            $this->memoizeState($cnt);
        }

        return $cnt;
    }

    public function run(int $x, int $y)
    {
        $this->visited = [];
        $this->targetX = $x;
        $this->targetY = $y;
        echo $this->floodFill($x, $y)."\n";
    }

    private function memoizeState(int $value)
    {
        foreach ($this->visited as $y => $row) {
            foreach ($row as $x => $val) {
                if (!isset($this->memo[$y])) {
                    $this->memo[$y] = [];
                }
                $this->memo[$y][$x] = $value;
            }
        }
    }

    private function isValidCoordinate(int $x, int $y)
    {
        return $x >= 0 && $x < $this->width && $y >= 0 && $y < $this->height;
    }
}

$game = new Game();
$game->parseInput();
fscanf(STDIN, "%d", $n);
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%d %d", $x, $y);
    $game->run($x, $y);
}

?>
