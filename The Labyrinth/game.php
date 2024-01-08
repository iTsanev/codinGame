<?php

class Coord
{
    private $x;
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function equals(Coord $c)
    {
        return $c->getX() === $this->x && $c->getY() === $this->y;
    }
}

class Game
{
    private $map;
    private $width;
    private $height;
    private $curLocation;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function run()
    {
        while (1) {
            $this->readMap();

            // reveal entire map, until no ? signs are left
            while($this->explore('?', ['#', 'C']));

            // go to Control room
            $this->explore('C', ['#', '?']);

            // go to Exit
            $this->explore('T', ['#', '?']);
        }
    }

    private function getNeighbours(Coord $c, $avoid)
    {
        return array_filter(
            [
                new Coord($c->getX() - 1, $c->getY()),
                new Coord($c->getX() + 1, $c->getY()),
                new Coord($c->getX(), $c->getY() - 1),
                new Coord($c->getX(), $c->getY() + 1),
            ],
            function (Coord $c) use ($avoid) {
                return $this->isInMap($c) && !$this->shouldAvoid($c, $avoid);
            }
        );
    }

    private function isInMap($c)
    {
        return $c->getX() >= 0 && $c->getX() < $this->width && $c->getY() >= 0 && $c->getY() < $this->height;
    }

    private function getMapValue(Coord $c)
    {
        return $this->map[$c->getY()][$c->getX()];
    }

    private function shouldAvoid(Coord $c, array $avoid)
    {
        return in_array($this->getMapValue($c), $avoid);
    }

    private function getDirection(Coord $curPos, Coord $target)
    {
        if ($curPos->getX() < $target->getX()) {
            return 'RIGHT';
        } else if ($curPos->getX() > $target->getX()) {
            return 'LEFT';
        } else if ($curPos->getY() < $target->getY()) {
            return 'DOWN';
        } else {
            return 'UP';
        }
    }

    private function readMap()
    {
        $this->map = [];
        fscanf(STDIN, "%d %d", $y, $x);
        $this->curLocation = new Coord($x, $y);
        for ($i = 0; $i < $this->height; $i++)
        {
            fscanf(STDIN, "%s", $row);
            $this->map[] = str_split($row);
        }
    }

    private function getBfsPath($target, $avoid)
    {
        $queue = [];
        $visited = [];

        $visited[$this->curLocation->getY()][$this->curLocation->getX()] = true;
        $queue[] = [$this->curLocation];

        while (!empty($queue)) {
            // get first element from queue
            $path = array_shift($queue);
            $node = end($path);

            // check if we've reached the end
            if ($this->getMapValue($node) === $target) {
                return $path;
            }
            
            foreach ($this->getNeighbours($node, $avoid) as $neighbour) {
                if (!isset($visited[$neighbour->getY()][$neighbour->getX()])) {
                    $visited[$neighbour->getY()][$neighbour->getX()] = true;

                    $newPath = $path;
                    $newPath[] = $neighbour;

                    $queue[] = $newPath;
                }
            }
        }

        return [];
    }

    private function explore($target, $avoid)
    {
        $path = $this->getBfsPath($target, $avoid);

        if (empty($path)) {
            // can't get to target
            return false;
        } else {
            // iterate over path steps
            for ($i = 0; $i < count($path) - 1; $i++) {
                $c = $path[$i];
                $t = $path[$i+1];
                if ($this->shouldAvoid($t, $avoid)) {
                    // check whether node is accessible
                    // since map might have updated
                    break;
                }
                echo $this->getDirection($c, $t)."\n";
                $this->readMap();
            }

            return true;
        }
    }
}


fscanf(STDIN, "%d %d %d", $height, $width, $alarm);
$game = new Game($width, $height);
$game->run();

?>
