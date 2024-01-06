<?php

class Game
{
    private $matrix = [];
    private $visited = [];
    private $width = 0;
    private $height = 0;

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
        if (!$this->isValidCoordinate($x, $y) || !$this->graph[$y][$x] || isset($this->visited[$y][$x])) {
            return 0;
        }

        $cnt = 1;
        $this->visited[$y][$x] = true;

        $cnt += $this->floodFill($x, $y - 1);
        $cnt += $this->floodFill($x, $y + 1);
        $cnt += $this->floodFill($x - 1, $y);
        $cnt += $this->floodFill($x + 1, $y);

        return $cnt;
    }

    public function run(int $x, int $y)
    {
        $this->visited = [];
        echo $this->floodFill($x, $y)."\n";
    }

    private function isValidCoordinate(int $x, int $y)
    {
        return $x >= 0 && $x <= $this->width && $y >= 0 && $y < $this->height;
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
