<?php

class Game
{
    private $graph;
    private $exits;
    private $linksCount;

    public function run(int $position)
    {
        // find the shortest of all possible paths
        $shortestPath = [];
        $fewestJumps = $this->linksCount;
        foreach ($this->exits as $exit) {
            $path = $this->bfsPath($position, $exit);
            $count = count($path);
            if ($count && $count <= $fewestJumps) {
                $fewestJumps = $count;
                $shortestPath = $path;
            }
        }
        // get the last two nodes
        $n1 = array_pop($shortestPath);
        $n2 = array_pop($shortestPath);

        // remove nodes link
        $this->unlinkNodes($n1, $n2);
    
        echo $n1.' '.$n2."\n";
    }

    public function parseInput()
    {
        fscanf(STDIN, "%d %d %d", $nodes, $links, $exits);

        $this->linksCount = $links;

        for ($i = 0; $i < $links; $i++) {
            fscanf(STDIN, "%d %d", $node1, $node2);
            $this->graph[$node1][$node2] = $node2;
            $this->graph[$node2][$node1] = $node1;
        }

        for ($i = 0; $i < $exits; $i++) {
            fscanf(STDIN, "%d", $exit);
            $this->exits[] = $exit;
        }
    }

    private function bfsPath(int $start, int $end)
    {
        $queue = [];
        $visited = [];

        $visited[$start] = true;
        $queue[] = [$start];

        while (!empty($queue)) {
            // get first element from queue
            $path = array_shift($queue);
            $node = end($path);

            // check if we've reached the end
            if ($node === $end) {
                return $path;
            }
            
            if (isset($this->graph[$node])) {
                foreach ($this->graph[$node] as $neighbour) {
                    if (!isset($visited[$neighbour])) {
                        $visited[$neighbour] = true;

                        $newPath = $path;
                        $newPath[] = $neighbour;

                        $queue[] = $newPath;
                    }
                }
            }
        }

        return [];
    }

    private function unlinkNodes(int $n1, int $n2)
    {
        unset($this->graph[$n1][$n2]);
        unset($this->graph[$n2][$n1]);
    }
}

$game = new Game();
$game->parseInput();

while (true) {
    fscanf(STDIN, "%d", $si);
    $game->run($si);
}

?>
